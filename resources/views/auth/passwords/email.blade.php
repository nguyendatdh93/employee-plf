@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('form_send_mail_reset_password.title') }}</div>

                <div class="card-body">
                    @if (session()->get('send_mail_forgot_password'))
                        <p style="text-align: center; font-size: 12px">{{ __('passwords.sent') }}</p>
                    @else
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <label for="email" class="col-md-6 col-form-label text-md-left" style="font-size: 12px">
                                ご登録されたメールアドレスを入力してください。<br/>
                                パスワードのリセットリンクをメールにてご連絡します。<br/>
                                ※注意：パスワード再設定は自分のPCで行ってください。
                            </label>

                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('form_send_mail_reset_password.email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12" style="text-align: center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('form_send_mail_reset_password.btn_send_mail') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
