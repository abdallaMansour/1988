@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">الجولات — {{ $issue->title }}</h4>
            <a href="{{ route('dashboard.issues.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> رجوع للقضايا
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                تأكد من إدخال كل الأسئلة والإجابات واختيار إجابة صحيحة واحدة لكل سؤال.
            </div>
        @endif

        <form action="{{ route('dashboard.issues.rounds.update', $issue) }}" method="POST">
            @csrf
            @method('PUT')

            @foreach ($rounds as $roundNumber => $roundData)
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>الجولة {{ $roundNumber }}</strong>
                    </div>
                    <div class="card-body">
                        @foreach ($roundData['questions'] as $questionNumber => $questionData)
                            @php
                                $questionPath = "rounds.$roundNumber.questions.$questionNumber";
                                $selectedCorrectAnswer = (int) old("$questionPath.correct_answer", $questionData['correct_answer']);
                            @endphp

                            <div class="border rounded p-3 mb-4">
                                <div class="mb-3">
                                    <label class="form-label" for="round_{{ $roundNumber }}_question_{{ $questionNumber }}">
                                        سؤال {{ $questionNumber }} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error("$questionPath.question") is-invalid @enderror"
                                        id="round_{{ $roundNumber }}_question_{{ $questionNumber }}"
                                        name="rounds[{{ $roundNumber }}][questions][{{ $questionNumber }}][question]"
                                        value="{{ old("$questionPath.question", $questionData['question']) }}"
                                        maxlength="255"
                                        required
                                    >
                                    @error("$questionPath.question")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    @foreach ($questionData['answers'] as $answerNumber => $answerData)
                                        @php
                                            $answerPath = "$questionPath.answers.$answerNumber";
                                        @endphp

                                        <div class="col-md-6">
                                            <label class="form-label" for="round_{{ $roundNumber }}_question_{{ $questionNumber }}_answer_{{ $answerNumber }}">
                                                إجابة {{ $answerNumber }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input
                                                        class="form-check-input mt-0"
                                                        type="radio"
                                                        name="rounds[{{ $roundNumber }}][questions][{{ $questionNumber }}][correct_answer]"
                                                        value="{{ $answerNumber }}"
                                                        @checked($selectedCorrectAnswer === $answerNumber)
                                                        required
                                                    >
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control @error("$answerPath.answer") is-invalid @enderror"
                                                    id="round_{{ $roundNumber }}_question_{{ $questionNumber }}_answer_{{ $answerNumber }}"
                                                    name="rounds[{{ $roundNumber }}][questions][{{ $questionNumber }}][answers][{{ $answerNumber }}][answer]"
                                                    value="{{ old("$answerPath.answer", $answerData['answer']) }}"
                                                    maxlength="255"
                                                    required
                                                >
                                                @error("$answerPath.answer")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error("$questionPath.correct_answer")
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> حفظ الجولات
                </button>
            </div>
        </form>
    </div>
@endsection
