<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>Least Significant Bit</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>Least Significant Bit</h2>

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
    <label for="plain-text">Hiding message:</label><br>
    <textarea name="plain-text" id="plain-text" cols="60" rows="8"><? echo $_SESSION['lsb_input'] ?? "The basic idea in Image Steganography lies in the fact that a change in the Least Significant Bit (LSB) is not detected by human eye. So we modify the LSB of RGB value to store the hidden message in the message without affecting the color of the image."?></textarea>

    <label for="filename">Image to hide the message / to get hidden message:</label>
    <input type="file" id="file" name="file" accept="image/png,image/jpeg">

    <input type="submit" name="action" value="Encrypt">
    <input type="submit" name="action" value="Decrypt">
</form>

<div id="result">
    <label for="cipher">Secret message from image:</label><br>
    <textarea name="cipher" id="cipher" cols="60" rows="8"><? echo $_SESSION['lsb_output'] ?? '' ?></textarea>
</div>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>