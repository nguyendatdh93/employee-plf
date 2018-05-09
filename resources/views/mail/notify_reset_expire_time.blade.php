{{ $user->name }}さん<br><br>

管理者により社員認証システムのパスワードがリセットされました。<br>
ログインしてパスワードを変更してください。<br><br>

メールアドレス: {{ $user->email }}<br>
パスワード: {{ $password }}<br><br>

アカウントの有効期限は{{ config('base.new_user_expired_hours') }}時間です。<br><br>

ヘルプデスク<br>
{{ config('base.helpdesk_mail') }}