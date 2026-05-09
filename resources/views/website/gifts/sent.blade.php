@extends('website.layouts.master')

@section('content')
<section class="section-py">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="bx bx-gift text-primary me-2"></i> تم شراء الهدية بنجاح</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @php
                            $item = $purchase->purchasable;
                        @endphp
                        <p class="text-body-secondary mb-4">
                            شارك الرابط أدناه مع صديقك ليقبل الهدية بعد تسجيل الدخول أو إنشاء حساب.
                            @if ($item instanceof \App\Models\Product)
                            <strong>{{ $item->name }}</strong>
                            @elseif ($item instanceof \App\Models\Issue)
                            <strong>{{ $item->title }}</strong>
                            @endif
                        </p>

                        <label class="form-label">رابط الهدية</label>
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" id="giftClaimUrl" value="{{ $claimUrl }}" readonly dir="ltr">
                            <button type="button" class="btn btn-primary" id="copyGiftLink">نسخ</button>
                        </div>

                        <hr class="my-6">

                        <h6 class="mb-3">أو أرسل الدعوة بالبريد (اختياري)</h6>
                        <form action="{{ route('website.gift.sent.invite', $purchase) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="recipient_email" class="form-label">بريد صديقك</label>
                                <input type="email" name="recipient_email" id="recipient_email" class="form-control @error('recipient_email') is-invalid @enderror" value="{{ old('recipient_email') }}" placeholder="friend@example.com" maxlength="255">
                                @error('recipient_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-label-primary">إرسال الدعوة</button>
                        </form>

                        <div class="mt-6">
                            <a href="{{ route('website.my-purchases') }}" class="btn btn-label-secondary">العودة إلى مشترياتي</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.getElementById('copyGiftLink')?.addEventListener('click', function () {
    const input = document.getElementById('giftClaimUrl');
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function () {
        const btn = document.getElementById('copyGiftLink');
        if (btn) { btn.textContent = 'تم النسخ'; setTimeout(function () { btn.textContent = 'نسخ'; }, 2000); }
    });
});
</script>
@endsection
