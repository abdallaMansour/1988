<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\GiftAcceptedNotification;
use App\Notifications\GiftInvitationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class GiftController extends Controller
{
    public function sent(Purchase $purchase): RedirectResponse|View
    {
        abort_unless(auth()->id() === $purchase->user_id, 403);
        abort_unless($purchase->status === 'paid', 403);

        if ($purchase->gift_claim_token === null) {
            return redirect()->route('website.my-purchases')->with('info', 'لا يوجد رابط إهداء نشط لهذا الطلب (إما ليس هدية أو تم قبولها مسبقاً).');
        }

        $purchase->loadMissing('purchasable');
        $claimUrl = route('website.gifts.claim.show', ['token' => $purchase->gift_claim_token]);

        return view('website.gifts.sent', compact('purchase', 'claimUrl'));
    }

    public function sendInvite(Request $request, Purchase $purchase): RedirectResponse
    {
        $this->authorizePendingGiftFromGifter($purchase);

        $validated = $request->validate([
            'recipient_email' => ['required', 'email', 'max:255'],
        ]);

        $purchase->update(['gift_invite_email' => $validated['recipient_email']]);

        $purchase->loadMissing('purchasable');

        Notification::route('mail', $validated['recipient_email'])->notify(new GiftInvitationNotification(
            route('website.gifts.claim.show', ['token' => $purchase->gift_claim_token]),
            $this->purchasableTitle($purchase),
            (string) auth()->user()->name,
        ));

        return back()->with('success', 'تم إرسال الدعوة إلى البريد المحدد.');
    }

    public function claimShow(string $token): View|RedirectResponse
    {
        $purchase = Purchase::query()
            ->where('gift_claim_token', $token)
            ->where('status', 'paid')
            ->with('purchasable')
            ->firstOrFail();

        if (auth()->id() === $purchase->user_id) {
            return redirect()->route('website.landing-page')->with('error', 'لا يمكنك قبول هدية أنت من أرسلها لنفسك.');
        }

        return view('website.gifts.claim', compact('purchase', 'token'));
    }

    public function claimAccept(string $token): RedirectResponse
    {
        try {
            [$gifterId, $itemTitle] = DB::transaction(function () use ($token) {
                $purchase = Purchase::query()
                    ->where('gift_claim_token', $token)
                    ->where('status', 'paid')
                    ->lockForUpdate()
                    ->first();

                if (! $purchase) {
                    throw new \RuntimeException('رابط الهدية غير صالح أو انتهى.');
                }

                if ($purchase->gift_from_user_id !== null) {
                    throw new \RuntimeException('تم استلام هذه الهدية مسبقاً.');
                }

                if (auth()->id() === $purchase->user_id) {
                    throw new \RuntimeException('لا يمكنك قبول هدية أنت من أرسلها لنفسك.');
                }

                $purchase->load('purchasable');
                $itemTitle = $this->purchasableTitle($purchase);
                $gifterId = $purchase->user_id;

                $purchase->update([
                    'user_id' => auth()->id(),
                    'gift_from_user_id' => $gifterId,
                    'gift_claim_token' => null,
                ]);

                return [$gifterId, $itemTitle];
            });

            $gifter = User::query()->find($gifterId);
            if ($gifter) {
                $gifter->notify(new GiftAcceptedNotification(
                    (string) auth()->user()->name,
                    $itemTitle,
                ));
            }

            return redirect()->route('website.my-purchases')->with('success', 'تم قبول الهدية بنجاح. تجد المحتوى في قسم المشتريات.');
        } catch (\RuntimeException $e) {
            return redirect()->route('website.landing-page')->with('error', $e->getMessage());
        }
    }

    private function authorizePendingGiftFromGifter(Purchase $purchase): void
    {
        abort_unless(auth()->id() === $purchase->user_id, 403);
        abort_unless($purchase->status === 'paid', 403);
        abort_unless($purchase->gift_claim_token !== null, 403);
    }

    private function purchasableTitle(Purchase $purchase): string
    {
        $item = $purchase->purchasable;

        return match (true) {
            $item instanceof Product => $item->name,
            $item instanceof Issue => $item->title,
            default => 'عنصر',
        };
    }
}
