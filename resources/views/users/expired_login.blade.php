@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body bg-warning">
                        {{ __('expired_login.expired_message') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
