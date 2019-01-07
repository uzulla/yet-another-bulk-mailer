<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>秘伝のバルクメーラー</title>
</head>

<body>
<?php
date_default_timezone_set('Asia/Tokyo');
ini_set('display_errors', 1);
echo "<pre>";
require_once("vendor/autoload.php");

$mail_list_raw = explode("\n", $_POST['mail_list']);

$mail_list = [];
foreach ($mail_list_raw as $email) {
    $email=trim($email);
    if (!preg_match("/@/", $email)) continue;
    
    $result = preg_match("|\A(.*)\t(.*)\z|u", $email, $m);
    if(!$result){
        echo "<span style='color:red'>宛先のフォーマットが不正です。（「本当に」タブでくぎっていますか？）</span>\n";
        var_dump($email);
        exit;
    }

    $mail_list[] = [
        'name'=>$m[1],
        'email'=>$m[2]
    ];
}

echo "<h3>送信先メアド</h3>";

echo htmlspecialchars(print_r($mail_list,1), ENT_QUOTES);
echo "<hr>";
echo "<h3>送信テンプレート</h3>";

$is_no_dry_run = (isset($_POST['no_dry_run']) && $_POST['no_dry_run'] == 1) ? true : false;


$from_name = $_POST['from_name'];
echo htmlspecialchars("\nFROM 名前:\n{$from_name}", ENT_QUOTES);

$from_email = $_POST['from_email'];
echo htmlspecialchars("\nFROM email:\n{$from_email}", ENT_QUOTES);

$cc_email = $_POST['cc'];
echo htmlspecialchars("\nCC:\n{$cc_email}", ENT_QUOTES);
$orig_cc_list = explode(',', $cc_email);
if(count($orig_cc_list)===1 && $orig_cc_list[0] === ""){ // explode不便すぎる
    $orig_cc_list = [];
}

$subject = $_POST['subject'];
echo htmlspecialchars("\n件名:\n{$subject}", ENT_QUOTES);

$template_body = $_POST['body'];
echo "\n本文:<br><pre style='background-color:#eeeeee'>";
echo htmlspecialchars("{$template_body}", ENT_QUOTES);
echo "</pre>\n";

echo "<hr>";

// swiftmailer 用意
$gmail_id = $_POST['gmail_id'];
$gmail_pass = $_POST['gmail_pass'];
$smtp_server = $_POST['smtp_server'];

$transport = (new Swift_SmtpTransport($smtp_server, 465, 'ssl'))
    ->setUsername($gmail_id)
    ->setPassword($gmail_pass);

$mailer = new Swift_Mailer($transport);

$message = new Swift_Message($subject);
$message
    ->setFrom([$from_email => $from_name]);

// 逐次送信
foreach ($mail_list as $mail) {
    echo "<h3>送信メール</h3>";

    if(preg_match('/,/u', $mail['email'])){
        $list = explode(',', $mail['email']);
        // １つ目はtoにする
        $to = array_shift($list);
        // 二つ目以降はCCに落とす
        $cc_list = array_merge($list, $orig_cc_list);
    }else{
        $to = $mail['email'];
        $cc_list = $orig_cc_list;
    }

    if($mail['name'] !== "-") { // 名前が-なら、名前を設定しない
        $message->setTo([$to => $mail['name']]);
    }else{
        $message->setTo([$to]);
    }
    $message->setCc($cc_list);

    // 置換を個々に差し込む
    $body = preg_replace('|{{name}}|u', $mail['name'], $template_body);
    $message->setBody($body);

    // 画面表示用に用意(Swiftmailerから取り出す)
    $swift_address = array_keys($message->getTo())[0]; // php7にしてarray_key_firstにしたい…
    $swift_name = $message->getTo()[$swift_address];
    
    echo "TO: ".htmlspecialchars("{$swift_name}<{$swift_address}>\n", ENT_QUOTES);
    echo "CC: ";
    var_dump(array_keys($message->getCc()));

    echo htmlspecialchars("件名:". $message->getSubject()."\n", ENT_QUOTES);
    echo "本文:<br><pre style='background-color:#eeeeee'>";
    echo htmlspecialchars($message->getBody()."\n", ENT_QUOTES);
    echo "</pre>";

    // Send the message
    if ($is_no_dry_run) {
        $result = $mailer->send($message);
        echo "result:";
        if($result>0){
            echo "成功 ({$result}通送信)";
        }else{
            echo "失敗？ ({$result}通送信)";
        }
    } else {
        echo "result: dry-runなので送信せず";
    }

    echo "<hr>";
}

echo "終了しました";
