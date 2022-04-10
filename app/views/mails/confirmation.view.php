<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmation</title>
</head>
<body>
<h2>Welcome <?= $user_name ?> to our webiste</h2>
<br>
<p>In order to continue, please confirm your email buy clicking to the following link
<a href="<?= $link ?>">link</a> or press the button bellow.</p>
<br>
<button onclick="location.href='<?= $link ?>'" type="button">Click Here</button>
<br>
<p>Best regards,</p>
<p><?= getAppValue('website_name') ?></p>


</body>
</html>