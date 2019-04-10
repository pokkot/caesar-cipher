<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>MD5</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>MD5 hash-based Authentication</h2>

<div class="errors">
    <?php
    if (isset($_SESSION['error'])) {
        echo '<ul>';
        foreach ($_SESSION['error'] as $error) {
            echo '<li>'.$error.'</li>';
        }
        echo '</ul>';
        $_SESSION['error'] = [];
    }
    ?>
</div>

<div class="message">
    <?php
    echo $_SESSION['message'];
    $_SESSION['message'] = '';
    ?>
</div>

<form action="handler.php" method="post" class="upload-form" enctype="multipart/form-data">
    <label for="username">Username:</label><br>
    <input name="username" id="username" value="<? echo $_SESSION['username'] ?? 'username' ?>">

    <label for="password">Password:</label><br>
    <input name="password" id="password" type="password" value="<? echo $_SESSION['password'] ?? 'password' ?>">

    <input type="submit" name="action" value="Register">
    <input type="submit" name="action" value="Login">
</form>

<div id="result">
    <label for="users">Users:</label><br>
    <textarea disabled name="users" id="users" cols="60" rows="8"><?php foreach ($_SESSION['users'] as $user) { echo $user."\n"; } ?></textarea>
</div>
<a href="users-clear.php">clear users list</a>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>