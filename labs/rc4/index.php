<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>RC4 Cipher</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>RC4 Cipher</h2>

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

<form action="handler.php" method="post" class="upload-form" enctype="multipart/form-data">
    <label for="plain-text">Enter the plaintext or ciphertext:</label><br>
    <textarea name="plain-text" id="plain-text" cols="60" rows="8"><? echo $_SESSION['rc4_input'] ?? 'Hello, world!' ?></textarea>

    <label for="key">Encryption key:</label>
    <textarea name="key" id="key" cols="60" rows="8"><? echo $_SESSION['rc4_key'] ?? 'keyword' ?></textarea>

    <input type="submit" name="action" value="Encrypt">
    <input type="submit" name="action" value="Decrypt">
</form>

<div id="result">
    <label for="cipher">Result:</label><br>
    <textarea name="cipher" id="cipher" cols="60" rows="8"><? echo $_SESSION['rc4_output'] ?? '' ?></textarea>
</div>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>