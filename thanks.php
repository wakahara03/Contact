
<?php

session_start();

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>お問い合わせ完了</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
	<header>
		<div class="header-left">
			お問い合わせ完了
		</div>
	</header>

	<div class="thanks-msg">
		<ul>
			<li>
				<?php echo h($_SESSION["name"]) . "様"; ?><br>
				お問い合わせいただきありがとうございました。<br>
				<a href="index.php">続けて問い合わせる</a>
			</li>
		</ul>
	</div>
</body>
</html>
