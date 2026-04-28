<!doctype html>
<html lang="ar" class="layout-wide customizer-hide" dir="rtl" data-skin="default" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>تأكيد الحساب | QMRA</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/svg/icons/qmra_icon.svg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<body>
    <div class="authentication-wrapper authentication-cover">
        <a href="{{ route('dashboard.index') }}" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-text demo text-heading fw-bold">QMRA</span>
        </a>
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('assets/img/illustrations/girl-unlock-password-light.png') }}" class="img-fluid scaleX-n1-rtl" alt="Verification" width="500" />
                </div>
            </div>
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-sm-12 mt-8">
                    <h4 class="mb-1">تأكيد الحساب 🔐</h4>
                    <p class="mb-6">يرجى تأكيد بريدك الإلكتروني ورقم هاتفك للوصول إلى لوحة التحكم</p>

                    @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif
                    @if (session('info'))
                    <div class="alert alert-info mb-4">{{ session('info') }}</div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @php $user = auth()->user(); @endphp

                    {{-- Email Verification --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bx bx-envelope me-2"></i>
                                تأكيد البريد الإلكتروني
                                @if ($user->email_verified_at)
                                <span class="badge bg-success ms-2">مؤكد</span>
                                @endif
                            </h5>
                            @if ($user->email_verified_at)
                            <p class="text-success mb-0"><i class="bx bx-check-circle me-1"></i> تم تأكيد بريدك الإلكتروني</p>
                            @else
                            <form action="{{ route('dashboard.verification.email.send') }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">إرسال رمز التحقق</button>
                            </form>
                            <form action="{{ route('dashboard.verification.email.verify') }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label for="email_code" class="form-label">رمز التحقق</label>
                                    <input type="text" id="email_code" name="code" class="form-control" placeholder="أدخل الرمز المكون من 6 أرقام" maxlength="6" pattern="[0-9]{6}" />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">تأكيد البريد</button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Phone Verification --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bx bx-phone me-2"></i>
                                تأكيد رقم الهاتف
                                @if ($user->phone_verified_at)
                                <span class="badge bg-success ms-2">مؤكد</span>
                                @endif
                            </h5>
                            @if ($user->phone_verified_at)
                            <p class="text-success mb-0"><i class="bx bx-check-circle me-1"></i> تم تأكيد رقم هاتفك</p>
                            @else
                            <form action="{{ route('dashboard.verification.phone.send') }}" method="POST" class="mb-3">
                                @csrf
                                <div class="mb-2">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="05xxxxxxxx أو 9665xxxxxxxx" value="{{ old('phone', $user->phone) }}" />
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-body-secondary">سيتم إرسال الرمز عبر واتساب</small>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary">إرسال رمز التحقق</button>
                            </form>
                            <form action="{{ route('dashboard.verification.phone.verify') }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label for="phone_code" class="form-label">رمز التحقق</label>
                                    <input type="text" id="phone_code" name="code" class="form-control" placeholder="أدخل الرمز المكون من 6 أرقام" maxlength="6" pattern="[0-9]{6}" />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">تأكيد الهاتف</button>
                            </form>
                            @endif
                        </div>
                    </div>

                    @if ($user->isFullyVerified())
                    <div class="text-center">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">الدخول إلى لوحة التحكم</a>
                    </div>
                    @else
                    <div class="text-center">
                        <a href="{{ route('website.landing-page') }}" class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-chevron-left icon-20px scaleX-n1-rtl me-1_5 align-top"></i>
                            العودة للموقع
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
</body>
</html>
