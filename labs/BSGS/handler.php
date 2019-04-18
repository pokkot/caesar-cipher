<?php declare(strict_types = 1);
session_start();

include_once('BSGS.php');
use BSGS\BSGS;

const CALCULATE = 'Calculate';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== CALCULATE) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $y = (int) $_POST['y'];
    $a = (int) $_POST['a'];
    $p = (int) $_POST['p'];
    $_SESSION['y'] = $y;
    $_SESSION['a'] = $a;
    $_SESSION['p'] = $p;

    if ($y <= 0 || $a <= 0 || $p <= 0) {
        $_SESSION['error'][] = 'y, a, p parameters must be positive numbers';
    }
    if ($_SESSION['error']) {
        redirectToIndex();
    }

    try {
        $BSGS = new BSGS();
        $result = $BSGS->calc($y, $a, $p);
        $_SESSION['bsgs_result'] = $result;
        $_SESSION['bsgs_log'] = $BSGS->getLog();
    } catch (Exception $e) {
        $_SESSION['error'][] = $e->getMessage();
    }

    redirectToIndex();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}
