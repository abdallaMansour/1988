@extends('website.layouts.master')

@section('content')
<section class="section-py">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="bx bx-gift text-primary me-2"></i> لقد تلقيت هدية</h4>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $item = $purchase->purchasable;
                        @endphp
                        <p class="mb-4">
                            يمكنك قبول الهدية لتظهر في قسم <strong>مشترياتك</strong> مع إظهار أنها هدية من المرسل.
                        </p>
                        <div class="border rounded p-4 mb-6 bg-label-secondary bg-opacity-25">
                            @if ($item instanceof \App\Models\Product)
                            <p class="mb-1 text-body-secondary small">منتج</p>
                            <h5 class="mb-0">{{ $item->name }}</h5>
                            @elseif ($item instanceof \App\Models\Issue)
                            <p class="mb-1 text-body-secondary small">جريمة</p>
                            <h5 class="mb-0">{{ $item->title }}</h5>
                            @endif
                        </div>

                        <form action="{{ route('website.gifts.claim.accept', ['token' => $token]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">قبول الهدية</button>
                        </form>

                        <p class="small text-body-secondary mt-4 mb-0">
                            مسجل كـ {{ auth()->user()->name }} — إن لم تكن أنت المستلم، لا تقبل الهدية من هذا الحساب.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
