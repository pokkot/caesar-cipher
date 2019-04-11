<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>RSA</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>RSA</h2>

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

<div id="main">
    <form action="handler.php" method="post" class="upload-form" enctype="multipart/form-data">
        <label for="plain-text">Enter your message:</label><br>
        <textarea name="plain-text" id="plain-text" cols="50" rows="3"><? echo $_SESSION['rsa_input'] ?? 'Hello, world! Привет, мир!' ?></textarea>

        <div>
            <div style="display: inline-block">
                <p>Prime numbers:</p>
                <label for="p">P</label>
                <input name="p" id="p" min="0" max="99" value="<? echo $_SESSION['p'] ?? '' ?>" disabled>
                <label for="q">Q</label>
                <input name="q" id="q" min="0" max="99" value="<? echo $_SESSION['q'] ?? '' ?>" disabled>
            </div>
            <div style="display: inline-block">
                <p>Public key:</p>
                <label for="n">N:</label>
                <input name="n" id="n" min="0" max="99" value="<? echo $_SESSION['n'] ?? '' ?>" disabled>
                <label for="e">E:</label>
                <input name="e" id="e" min="0" max="99" value="<? echo $_SESSION['e'] ?? '' ?>" disabled>
            </div>
            <div style="display: inline-block">
                <p>Private key:</p>
                <label for="n">N:</label>
                <input name="n" id="n" min="0" max="99" value="<? echo $_SESSION['n'] ?? '' ?>" disabled>
                <label for="d">D:</label>
                <input name="d" id="d" min="0" max="99" value="<? echo $_SESSION['d'] ?? '' ?>" disabled>
            </div>
        </div>

        <input type="submit" name="action" value="Encrypt">
    </form>

    <div id="result">
        <label for="e">Encrypted message:</label><br>
        <textarea name="d" id="d" cols="50" rows="3" disabled><? echo $_SESSION['rsa_enc_message'] ?? '' ?></textarea>

        <label for="d">Decrypted message:</label><br>
        <textarea name="d" id="d" cols="50" rows="3" disabled><? echo $_SESSION['rsa_dec_message'] ?? '' ?></textarea>
    </div>
</div>

<div id="side">
    <div id="log">
        <label for="log">Log:</label><br>
        <textarea name="log" id="log" cols="50" rows="30"><? if ($_SESSION['rsa_log']) foreach ($_SESSION['rsa_log'] as $log) { echo $log."\n"; } ?></textarea>
    </div>
</div>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>