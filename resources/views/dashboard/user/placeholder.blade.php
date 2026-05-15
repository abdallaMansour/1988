@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $title }}</h4>
    </div>
    @include('dashboard.partials.user-empty-state', ['title' => $title, 'message' => $message])
</div>
@endsection
