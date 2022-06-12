@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#newModal">{{ __('maratauto.newcar') }}</button>
                    @include('modals/new')
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('maratauto.advertisments') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @include('partials/filters')
                    @include('partials/list')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
