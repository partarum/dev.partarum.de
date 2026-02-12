<?php

$domain = $_ENV["DOMAIN"] ?? $_SERVER["SERVER_NAME"];

session_start();

unset($_SESSION);

session_destroy();

setcookie("beer", time(), [
    "expires" => time() + 120,
    "path" => "/dashboard",
    "domain" => $domain,
    "secure" => true,
    "httponly" => true,
    "samesite" => "strict"
]);

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Dr. Franz-Josef Lückge Consulting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <link rel="stylesheet" type="text/css" href="/public/assets/css_backup/login.css" media="all">

</head>
<body>
<header id="header"></header>
<main id="content" class="single-box-center-small">
    <form method="POST" action="dashboard" class="login-form login-form--red" id="login">
        <fieldset class="login-form__container">
            <section class="login-form__field">
                <label for="user">Email / Name</label>
                <input type="text" id="user" name="user" autofocus required>
            </section>
            <section class="login-form__field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </section>
            <section class="login-form__actions">
                <button class="woodenButton" type="submit" form="login">Anmelden</button>
            </section>
        </fieldset>
    </form>
</main>
<footer id="footer"></footer>
</body>
</html>
