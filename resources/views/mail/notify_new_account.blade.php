{{ $user->name }}さん<br><br>

社員認証システムにアカウントが登録されました。<br>
ログインしてパスワードを変更してください。<br><br>

メールアドレス：{{ $user->email }}<br>
初期パスワード：{{ $password }}<br><br>

アカウントの有効期限は{{ config('base.new_user_expired_hours') }}時間です。<br><br>

ヘルプデスク<br>
{{ config('base.helpdesk_mail') }}