<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style.css">
    <title>Discrete Logarithm</title>
</head>
<body>

<div class="btn-to-home"><a href="../../index.php">&larr; go back</a></div>

<h2>Discrete Logarithm</h2>

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

        <p>Y = A ^ X mod P</p>
        <p>X — ?</p>

        <form action="handler.php" method="post" class="upload-form" enctype="multipart/form-data">
            <div>
                <div style="display: inline-block">
                    <label for="y">Y</label>
                    <input name="y" id="y" value="<? echo $_SESSION['y'] ?? '' ?>" type="number">
                </div>
                <div style="display: inline-block">
                    <label for="a">A:</label>
                    <input name="a" id="a" value="<? echo $_SESSION['a'] ?? '' ?>" type="number">
                </div>
                <div style="display: inline-block">
                    <label for="p">P:</label>
                    <input name="p" id="p" value="<? echo $_SESSION['p'] ?? '' ?>" type="number">
                </div>
            </div>

            <input type="submit" name="action" value="Calculate">
        </form>

        <div id="result">
            <label for="res">X:</label><br>
            <input name="res" id="res" value="<? echo $_SESSION['disc_log_result'] ?? '' ?>" type="number">
        </div>
    </div>

    <div id="side" class="column">
        <div id="log">
            <label for="log">Log:</label><br>
            <textarea name="log" id="log" cols="50" rows="30"><? if ($_SESSION['disc_log_log']) foreach ($_SESSION['disc_log_log'] as $log) { echo $log."\n"; } ?></textarea>
        </div>
    </div>
</div>

<p class="author">by Oleg Evgrashin</p>

</body>
</html>