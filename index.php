<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>秘伝のバルクメーラー</title>
</head>

<body>

<h1>秘伝のバルクメーラー</h1>

<form action="do.php" method="post" onsubmit="return confirm('ホントに送信します？（Dry runでもでます）');">

    <p>gmail id</p>
    <input style="width:100%;" name="gmail_id"/>

    <p>gmail アプリパスワード(GMAILのアカウントのパスワードではない！）</p>
    ※ https://myaccount.google.com/security から設定すること
    <input style="width:100%;" name="gmail_pass"/>

    <p>FROM 表示名</p>
    <input style="width:100%;" name="from_name"/>

    <p>FROM メアド</p>
    <input style="width:100%;" name="from_email"/>

    <p>CC メアド</p>
    <input style="width:100%;" name="cc"/>

    <p>件名</p>
    <input style="width:100%;" name="subject"/>

    <p>本文</p>
    <textarea style="width:100%;height:400px;" name="body"></textarea>

    <p>宛先<br>
    名前\tメアド{改行}<br>
        <br>
        名前は、本文に{{name}}と書く事で置換できます。
    </p>
    <textarea style="width:100%;height:400px;" name="mail_list"></textarea>

    <p> 本当に実行するなら、ここをonに。offならdry run</p>
    <input type="checkbox" value=1 name="no_dry_run">

    <hr>
    <input type="submit" value="メール送信">

</form>

</body>
</html>