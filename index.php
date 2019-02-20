<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Caesar Cipher</title>
</head>
<body>

<h3>Caesar Cipher</h3>

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
    <label for="plain-text">Enter the plaintext:</label><br>
    <textarea name="plain-text" id="plain-text" cols="60" rows="10"><? echo $_SESSION['input'] ?? 'Hello, world!' ?></textarea>

    <label for="filename">...or upload text file:</label>
    <input type="file" id="file" name="file" accept="text/plain">

    <label for="key">Encryption key:</label>
    <input type="number" name="key" id="key" placeholder="Number form 1 to 33" value="<? echo $_SESSION['key'] ?? '3' ?>">

    <input type="submit" name="action" value="Encrypt">
    <input type="submit" name="action" value="Decrypt">
    <input type="submit" name="action" value="Crack">
</form>

<div id="result">
    <label for="cipher">Encryption result:</label><br>
    <textarea name="cipher" id="cipher" cols="60" rows="10"><? echo $_SESSION['output'] ?? '' ?></textarea>
</div>

</body>
</html>