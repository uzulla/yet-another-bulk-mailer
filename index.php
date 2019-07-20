<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>秘伝のバルクメーラー</title>
</head>

<body>

<h1>秘伝のバルクメーラー</h1>

<script>
    function checkDryRun(){
        if(document.getElementById("no_dry_run").checked) {
            if(confirm('本当に送信しますか？\nDry runでただしいか確認しましたか？')){
                return true;
            }else {
                return false;
            }
        }else{
            if(confirm('これはDry runです、実際には送信されないはずです。\n送信するときには本当に実行するチェックをOnにしてください。')){
                return true;
            }else {
                return false;
            }
        }
    }
</script>
<form action="do.php" method="post" onsubmit="return checkDryRun();">

    <p>smtp id</p>
    <input style="width:100%;" name="gmail_id"/>

    <p>smtp pass (or gmailアプリパスワード(GMAILのアカウントのパスワードではない！)）</p>
    ※ https://myaccount.google.com/security から設定すること
    <input style="width:100%;" name="gmail_pass"/>

    <p>smtp server (smtp.mailgun.org, smtp.gmail.com ... )</p>
    <input style="width:100%;" name="smtp_server" value="smtp.mailgun.org"/>

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
    例：名前\tメアド{改行}[[[[[\t追加フィールド]\t追加フィールド]\t追加フィールド]\t追加フィールド]\t追加フィールド]<br>
    
    ※ \tはタブです。<br>
    ※ メアドはカンマつなぎで複数指定できます、２つ目以降はCCに入ります。<br>
    ※ 名前に"-"を指定すると、名前指定なしとして扱います。<br>
    ※ サンプル
    <pre style="background-color:#CCCCCC;">たなかたろう	taro@example.com	追加フィールド1	追加フィールド2	追加フィールド3	追加フィールド4	追加フィールド5
山田次郎	jiro@example.com,jiro_s_boss@example.com	追加フィールド1	追加フィールド2	追加フィールド3	追加フィールド4	追加フィールド5
-	noname@exmaple.com	追加フィールド1	追加フィールド2	追加フィールド3	追加フィールド4	追加フィールド5</pre>
    <br>
    ※ 名前は、本文や件名に{{name}}と書く事で置換できます。<br>
    ※ 本文や件名に５個まで追加フィールドを入れられます。{{f1}}〜{{f5}}が使えます。使わない場合は不要です。<br>
    <textarea style="width:100%;height:400px;" name="mail_list"></textarea>

    <p> 本当に実行するなら、ここをonに。offならdry run</p>
    <input type="checkbox" value="1" name="no_dry_run" id="no_dry_run">

    <hr>
    <input type="submit" value="メール送信">

</form>

</body>
</html>