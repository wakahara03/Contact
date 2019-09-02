<?php

date_default_timezone_set('Asia/Tokyo');

$log = '/Applications/MAMP/htdocs/contact/log.txt';
$log_time = date('Y-m-d H:i:s');


if (empty($_POST)) {
    error_log("[".date($log_time)."]"."フォームを表示しました" . "\n", 3, $log);
}

//送信ボタンがPOSTされたときに開始する
if (!empty($_POST["submit"])) {

    //エラーメッセージ一覧
    define("ERR_MSG01", "※この項目は入力必須です");
    define("ERR_MSG02", "※メールアドレスの形式で入力してください");
    define("ERR_MSG03", "※10文字以内で入力してください");
    define("ERR_MSG04", "※50文字以内で入力してください");
    define("ERR_MSG05", "※100文字以内で入力してください");

    //必須項目のバリデーションチェック
    if (empty($_POST["email"])) {
        $err_msg["email"] = ERR_MSG01;
        
        //【エラーログの表示】入力エラー（メールアドレス）
        error_log("[".date($log_time)."]". "入力エラー（メールアドレス）" . "\n", 3, $log);
    }

    if (empty($_POST["name"])) {
        $err_msg["name"] = ERR_MSG01;

        //【エラーログの表示】入力エラー（名前）
        error_log("[".date($log_time)."]". "入力エラー（名前）" . "\n", 3, $log);

    }

    if (empty($_POST["content"])) {
        $err_msg["content"] = ERR_MSG01;

         //【エラーログの表示】入力エラー（お問い合わせ内容）
        error_log("[".date($log_time)."]". "入力エラー（お問い合わせ内容）" . "\n", 3, $log);
    }

    //メールの形式や文字数のバリデーションチェック
    if (empty($err_msg)) {

        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\?\*\[|\]%'=~^\{\}\/\+!#&\$\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/", $_POST["email"])) {
            $err_msg["email"] = ERR_MSG02;

            //【エラーログの表示】入力エラー（メールアドレス）
            error_log("[".date($log_time)."]". "入力エラー（メールアドレス）" . "\n", 3, $log);
        }

        if (mb_strlen($_POST["name"]) > 10) {
            $err_msg["name"] = ERR_MSG03;

            //【エラーログの表示】入力エラー（名前）
            error_log("[".date($log_time)."]". "入力エラー（名前）" . "\n", 3, $log);
        }

        if (mb_strlen($_POST["email"]) > 50) {
            $err_msg["email"] = ERR_MSG04;

            //【エラーログの表示】入力エラー（メールアドレス）
            error_log("[".date($log_time)."]". "入力エラー（メールアドレス）" . "\n", 3, $log);
        }

        if (mb_strlen($_POST["content"]) > 100) {
            $err_msg["content"] = ERR_MSG05;

            //【エラーログの表示】入力エラー（お問い合わせ内容）
            error_log("[".date($log_time)."]". "入力エラー（お問い合わせ内容）" . "\n", 3, $log);
        }

        //セッションの開始とthanks.phpに遷移する
        if (empty($err_msg)) {

            //【エラーログの表示】バリデーションチェック、送信ボタンがPOSTされた場合
            error_log("[".date($log_time)."]". "名前：" . $_POST["name"]. "、" . "メールアドレス："  . $_POST["email"]. "、" . "お問い合わせ内容：" . $_POST["content"] . "\n", 3, $log);

			//宛先
			$to = $_POST["email"];
			
			//件名
			$subject = "お問い合わせありがとうございました";
			
			//本文
			$message = "{{" . $_POST["name"] . "}} 様" . "\n\n";
			$message .= "お問い合わせありがとうございました。" . "\r\n";
			$message .= "２営業日以内に、担当者よりご連絡させていただきます。";
			
			//差出人
			$header = "From: " . mb_encode_mimeheader("若原です") . " <wakahara0127@gmail.com>" ." \r\n";

			//CC情報を設定
			$header .= "Cc: hide121314@gmail.com";

            //【エラーログの表示】フォーム送信者宛にメールの送信
            error_log("[".date($log_time)."]". "メールを送信します（フォーム送信者宛）" . "\n", 3, $log);

			//お問い合わせいただいたユーザーへメール送信
			mb_send_mail($to, $subject, $message, $header);

			//管理者へ送るメールの件名
			$admin_subject = "お問い合わせがありました";
			
			// 管理者宛の本文
			$admin_message = "お問い合わせがありました。\n\n";
			$admin_message .= "名前：" . "{{" . $_POST["name"] . "}} 様" . "\r\n";
			$admin_message .= "メールアドレス：" . "{{" . $_POST["email"] . "}}" . "\r\n";
			$admin_message .= "お問い合わせ内容：" . "{{" . $_POST["content"] . "}}" . "\r\n";

            //【エラーログの表示】管理者宛にメールの送信
            error_log("[".date($log_time)."]". "メールを送信します（管理者宛）" . "\n", 3, $log);

			// 管理者へメール送信
			mb_send_mail( 'wakahara0127@gmail.com', $admin_subject, $admin_message, $header);

            session_start();

            $_SESSION['name'] = $_POST['name'];

            //【エラーログの表示】すべての処理が成功して、完了画面を表示
            error_log("[".date($log_time)."]". "すべての処理が成功しました。完了画面を表示します" . "\n", 3, $log);

            header('Location: /contact/thanks.php');
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<header>
    <div class="header-left">
        お問い合わせ
    </div>
</header>

<form action="index.php" method="post" class="form">
    <ul>
        <li class="name">
            <span><?php if (!empty($err_msg["name"])) { echo $err_msg["name"]; } ?></span><br>
            <label for="name">名前</label>
            <input type="text" name="name" id="name" value="<?php if (!empty($_POST["name"])) { echo $_POST["name"]; } ?>">
        </li>

        <li class="email">
            <span><?php if (!empty($err_msg["email"])) { echo $err_msg["email"]; } ?></span><br>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="<?php if (!empty($_POST["email"])) { echo $_POST["email"]; } ?>" >
        </li>

        <li class="content">
            <span><?php if (!empty($err_msg["content"])) { echo $err_msg["content"]; } ?></span><br>
            <label for="content">お問い合わせ内容</label>
            <textarea id="content" name="content"><?php if (!empty($_POST["content"])) { echo $_POST["content"]; } ?></textarea>
        </li>

        <li>
            <input type="submit" value="送信" name="submit">
        </li>
    </ul>
</form>
</body>
</html>
