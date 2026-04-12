@extends('layouts.moonshine-page')

@section('title', 'Отправить опрос')

@section('breadcrumbs')
<a href="/admin/resource/questionnaire-resource/questionnaire-index-page" class="breadcrumb-item">Опросы</a>
<span class="breadcrumb-divider">/</span>
<span class="breadcrumb-item active">Отправить опрос</span>
@endsection

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Отправить опрос: {{ $questionnaire->name }}</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('questionnaire.mail.send', $questionnaire) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label"><b>Опрос</b></label>
                        <input type="text" class="form-control" value="{{ $questionnaire->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><b>Ссылка на опрос</b></label>
                        <input type="text" class="form-control" value="{{ $questionnaire->url }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><b>Тема письма *</b></label>
                        <input
                            type="text"
                            class="form-control"
                            name="subject"
                            value="{{ old('subject', 'Приглашение пройти опрос') }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><b>Текст сообщения *</b></label>
                        <textarea
                            class="form-control"
                            name="message"
                            rows="5"
                            required
                        >{{ old('message', "Здравствуйте!\n\nПриглашаем вас принять участие в опросе. Пожалуйста, перейдите по ссылке ниже и ответьте на вопросы.") }}</textarea>
                    </div>

                    <input type="hidden" name="emails" value="{{ $employeeEmails }}">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                        <a href="{{ url('/admin/resource/questionnaire-resource/questionnaire-index-page') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
