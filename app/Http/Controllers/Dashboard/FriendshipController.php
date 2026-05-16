<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendshipController extends Controller
{
    public function guild(Request $request): View
    {
        $user = $request->user();

        $incomingRequests = Friendship::query()
            ->pending()
            ->where('addressee_id', $user->id)
            ->with(['requester.profileAvatar'])
            ->latest()
            ->get();

        $investigators = User::query()
            ->where('id', '!=', $user->id)
            ->whereNull('banned_at')
            ->with('profileAvatar')
            ->orderBy('investigator_name')
            ->paginate(15);

        $friendshipMap = $this->friendshipMapForUsers($user, $investigators->pluck('id')->all());

        return view('dashboard.user.detectives-guild', compact(
            'incomingRequests',
            'investigators',
            'friendshipMap',
        ));
    }

    public function friends(Request $request): View
    {
        $user = $request->user();
        $friendIds = $user->friendIds();

        $friends = User::query()
            ->whereIn('id', $friendIds)
            ->with('profileAvatar')
            ->orderBy('investigator_name')
            ->paginate(12);

        return view('dashboard.user.friends', compact('friends'));
    }

    public function sendRequest(User $user): RedirectResponse
    {
        $authUser = auth()->user();

        if ($user->id === $authUser->id) {
            return back()->withErrors(['friendship' => 'لا يمكنك إرسال طلب صداقة لنفسك.']);
        }

        if ($user->isBanned()) {
            return back()->withErrors(['friendship' => 'لا يمكن إرسال طلب لهذا المستخدم.']);
        }

        $existing = $authUser->friendshipWith($user);

        if ($existing) {
            if ($existing->status === Friendship::STATUS_ACCEPTED) {
                return back()->withErrors(['friendship' => 'أنتما صديقان بالفعل.']);
            }
            if ($existing->status === Friendship::STATUS_PENDING) {
                return back()->withErrors(['friendship' => 'يوجد طلب صداقة قيد الانتظار بالفعل.']);
            }
            if ($existing->status === Friendship::STATUS_REJECTED) {
                if ($existing->requester_id === $authUser->id) {
                    $existing->update(['status' => Friendship::STATUS_PENDING]);

                    return back()->with('success', 'تم إرسال طلب الصداقة.');
                }

                Friendship::create([
                    'requester_id' => $authUser->id,
                    'addressee_id' => $user->id,
                    'status' => Friendship::STATUS_PENDING,
                ]);

                return back()->with('success', 'تم إرسال طلب الصداقة.');
            }
        }

        Friendship::create([
            'requester_id' => $authUser->id,
            'addressee_id' => $user->id,
            'status' => Friendship::STATUS_PENDING,
        ]);

        return back()->with('success', 'تم إرسال طلب الصداقة.');
    }

    public function accept(Friendship $friendship): RedirectResponse
    {
        $this->authorizeIncomingPending($friendship);

        $friendship->update(['status' => Friendship::STATUS_ACCEPTED]);

        return back()->with('success', 'تم قبول طلب الصداقة.');
    }

    public function reject(Friendship $friendship): RedirectResponse
    {
        $this->authorizeIncomingPending($friendship);

        $friendship->update(['status' => Friendship::STATUS_REJECTED]);

        return back()->with('success', 'تم رفض طلب الصداقة.');
    }

    public function cancel(Friendship $friendship): RedirectResponse
    {
        $authUser = auth()->user();

        if ($friendship->requester_id !== $authUser->id || $friendship->status !== Friendship::STATUS_PENDING) {
            abort(403);
        }

        $friendship->delete();

        return back()->with('success', 'تم إلغاء طلب الصداقة.');
    }

    public function unfriend(User $user): RedirectResponse
    {
        $authUser = auth()->user();
        $friendship = $authUser->friendshipWith($user);

        if (! $friendship || $friendship->status !== Friendship::STATUS_ACCEPTED) {
            return back()->withErrors(['friendship' => 'لا توجد صداقة مع هذا المستخدم.']);
        }

        $friendship->delete();

        return back()->with('success', 'تمت إزالة الصديق.');
    }

    /**
     * @param  array<int>  $userIds
     * @return array<int, array{status: string, friendship: ?Friendship}>
     */
    private function friendshipMapForUsers(User $authUser, array $userIds): array
    {
        if ($userIds === []) {
            return [];
        }

        $friendships = Friendship::query()
            ->where(function ($q) use ($authUser) {
                $q->where('requester_id', $authUser->id)
                    ->orWhere('addressee_id', $authUser->id);
            })
            ->where(function ($q) use ($userIds) {
                $q->whereIn('requester_id', $userIds)
                    ->orWhereIn('addressee_id', $userIds);
            })
            ->get();

        $map = [];
        foreach ($userIds as $id) {
            $map[$id] = ['status' => 'can_add', 'friendship' => null];
        }

        foreach ($friendships as $friendship) {
            $otherId = $friendship->requester_id === $authUser->id
                ? $friendship->addressee_id
                : $friendship->requester_id;

            if (! array_key_exists($otherId, $map)) {
                continue;
            }

            $status = match ($friendship->status) {
                Friendship::STATUS_ACCEPTED => 'friend',
                Friendship::STATUS_PENDING => $friendship->requester_id === $authUser->id ? 'pending_sent' : 'pending_received',
                default => 'can_add',
            };

            $map[$otherId] = ['status' => $status, 'friendship' => $friendship];
        }

        return $map;
    }

    private function authorizeIncomingPending(Friendship $friendship): void
    {
        if ($friendship->addressee_id !== auth()->id() || $friendship->status !== Friendship::STATUS_PENDING) {
            abort(403);
        }
    }
}
