@extends('layouts.app')
@section('title')
    {{ $questionnaire->name }}
@endsection
@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>{{ $questionnaire->name }}</h2>
            @if($questionnaire->category)
                <p class="text-muted">Категория: {{ $questionnaire->category->name }}</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('questionnaire.store', $questionnaire->hash) }}">
                @csrf
                @foreach($questions as $index => $question)
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label">
                            {{ $index + 1 }}. {{ $question->text }}
                            @if($question->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        @if($question->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="" class="img-fluid rounded object-fit-cover" style="max-height: 400px;">
                            </div>
                        @endif
                        @if($question->type === 'scale')
                            <div class="d-flex gap-3 flex-wrap">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="answers[{{ $question->id }}]"
                                               id="q{{ $question->id }}_{{ $i }}"
                                               value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $i }}">
                                            {{ $i }}
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        @elseif($question->type === 'text')
                            <textarea class="form-control"
                                      name="answers[{{ $question->id }}]"
                                      rows="3"
                                      placeholder="Ваш ответ..."
                                      {{ $question->is_required ? 'required' : '' }}></textarea>
                        @elseif($question->type === 'single_choice')
                            <div class="d-flex flex-column gap-2">
                                @foreach($question->getOptionsArray() as $optionIndex => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="answers[{{ $question->id }}]"
                                               id="q{{ $question->id }}_{{ $optionIndex }}"
                                               value="{{ $option }}" {{ $question->is_required ? 'required' : '' }}>
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $optionIndex }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->type === 'multiple_choice')
                            <div class="d-flex flex-column gap-2">
                                @foreach($question->getOptionsArray() as $optionIndex => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="answers[{{ $question->id }}][]"
                                               id="q{{ $question->id }}_{{ $optionIndex }}"
                                               value="{{ $option }}">
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $optionIndex }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                <div class="mt-4">
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="6Ld_hqYsAAAAANXDpUdy-nJGZjeb_wujvbJNOLx2" data-callback="enableSubmit"></div>
                        @error('g-recaptcha-response')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                        Отправить ответы
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function enableSubmit() {
        document.getElementById('submit-btn').disabled = false;
    }
</script>
@endsection


