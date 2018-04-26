Hi {{ $user->name }},<br><br>

This is your new account:<br><br>
Email: {{ $user->email }}<br>
Password: {{ $password }}<br><br>

Please login & change password.<br>
Account will expired in {{ config('base.new_user_expired_hours') }} hours!<br><br>

Thanks,<br>
{{ config('app.name') }}