@extends('layouts.app')
@section('title')
    Опрос неактивен
@endsection
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="alert alert-secondary" role="alert">
                <h4 class="alert-heading">Опрос неактивен</h4>
                <p>К сожалению, этот опрос уже завершён или временно недоступен.</p>
            </div>
        </div>
    </div>
</div>
@endsection
