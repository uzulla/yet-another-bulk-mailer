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

$gmail_id = $_POST['gmail_id'];
$gmail_pass = $_POST['gmail_pass'];

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername($gmail_id)
    ->setPassword($gmail_pass);

$mailer = new Swift_Mailer($transport);

$subject = $_POST['subject'];
echo htmlspecialchars("\n件名:\n{$subject}", ENT_QUOTES);

$template_body = $_POST['body'];
echo htmlspecialchars("\n本文:\n{$template_body}", ENT_QUOTES);

$from_name = $_POST['from_name'];
echo htmlspecialchars("\nFROM 名前:\n{$from_name}", ENT_QUOTES);

$from_email = $_POST['from_email'];
echo htmlspecialchars("\nFROM email:\n{$from_email}", ENT_QUOTES);

$cc_email = $_POST['cc'];
echo htmlspecialchars("\nCC:\n{$cc_email}", ENT_QUOTES);
$orig_cc_list = [$cc_email];

$is_no_dry_run = (isset($_POST['no_dry_run']) && $_POST['no_dry_run'] == 1) ? true : false;

$mail_list_raw = explode("\n", $_POST['mail_list']);

$mail_list = [];
foreach ($mail_list_raw as $email) {
    if (!preg_match("/@/", $email)) continue;
    $mail_list[] = trim($email);
}

echo htmlspecialchars("\n to addresses :\n", ENT_QUOTES);
echo htmlspecialchars(print_r($mail_list,1), ENT_QUOTES);
echo "<hr>";

$message = new Swift_Message($subject);
$message
    ->setFrom([$from_email => $from_name]);

foreach ($mail_list as $line) {
    $cc_list = $orig_cc_list;

    preg_match("|(?P<name>.*)\t(?P<email>.*)|u", $line, $m);
    list($to, $name) = [$m['email'], $m['name']];
    echo htmlspecialchars("{$to}<{$name}>\n", ENT_QUOTES);

    if(preg_match('/,/', $to)){
        $list = explode(',', $to);
        $to = array_shift($list);
        $cc_list = array_merge($list, $orig_cc_list);
    }

    // 置換を個々に差し込む
    $body = preg_replace('|{{name}}|u', $name, $template_body);
    $message->setTo($to);
    $message->setBody($body);
    $message->setCc($cc_list);

    // Send the message
    if ($is_no_dry_run) {
        $result = $mailer->send($message);
        var_dump($message->getTo());
        var_dump($message->getCc());
        var_dump($message->getBody());
        var_dump($result);
    } else {
        var_dump($message->getTo());
        var_dump($message->getCc());
        var_dump($message->getBody());
    }

    echo "<hr>";

}

echo "<hr> 終了しました";
