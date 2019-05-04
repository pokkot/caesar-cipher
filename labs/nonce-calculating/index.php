<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>Mining (nonce calculating)</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>Mining (nonce calculating)</h2>

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

<div class="row">
    <div id="main" class="column">

        <form action="handler.php" method="post" class="upload-form" enctype="multipart/form-data">

            <label for="block_data">Block data:</label><br>
            <textarea disabled name="block_data" id="block_data" cols="80" rows="3"><?php echo $_SESSION['block_data'] ?? '' ?></textarea>

            <label for="target">Target:</label><br>
            <textarea disabled name="target" id="target" cols="80" rows="3"><?php echo $_SESSION['target'] ?? '' ?></textarea>

            <input type="submit" name="action" value="Start">
        </form>

        <div id="result">
            <label for="res">Nonce:</label><br>
            <input name="res" id="res" value="<? echo $_SESSION['nonce_result'] ?? '' ?>" type="number">
        </div>
    </div>

    <div id="side" class="column">
        <div id="log">
            <label for="log">Log:</label><br>
            <textarea name="log" id="log" cols="80" rows="30"><? if ($_SESSION['nonce_log']) foreach ($_SESSION['nonce_log'] as $log) { echo $log."\n"; } ?></textarea>
        </div>
    </div>
</div>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>