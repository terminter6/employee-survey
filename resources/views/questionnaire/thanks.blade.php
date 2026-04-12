@extends('layouts.app')
@section('title')
    Спасибо! | {{ $questionnaire->name }}
@endsection
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <svg class="mb-4" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <h2 class="mb-3">Спасибо за участие!</h2>
                    <p class="lead mb-4">Ваши ответы успешно сохранены.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
