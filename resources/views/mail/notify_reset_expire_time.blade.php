Hi {{ $user->name }},<br><br>

We have reset expire time for your account.<br>
Please login & change password.<br>
Account will expired in {{ config('base.new_user_expired_hours') }} hours!<br><br>

Thanks,<br>
{{ config('app.name') }}