{{ $notifiable->name }}さん<br><br>

社員認証システムのパスワードリマインダーが使用されました。<br><br>

<a href="{{ $url }}" style="
    color: white;
    padding: 5px 9px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 3px;
    border-width: 1px;
    background-color: #3c8dbc;
    border-color: #367fa9;
    margin-left: 50px;">パスワードリセット</a><br><br>

また、身に覚えが無い場合は、下記よりお問い合わせください。<br><br>

ヘルプデスク<br>
{{ config('base.helpdesk_mail') }}
