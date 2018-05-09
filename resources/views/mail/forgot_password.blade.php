{{ $notifiable->name }}さん<br><br>

社員認証システムのパスワードリマインダーが使用されました。<br>

<button><a href="{{ $url }}">パスワード リセット ボタン</a></button><br><br>

また、身に覚えが無い場合は、下記よりお問い合わせください。<br><br>

ヘルプデスク<br>
{{ config('base.helpdesk_mail') }}
