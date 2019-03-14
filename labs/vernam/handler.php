<?php declare(strict_types = 1);
session_start();

include_once('VernamCipher.php');
use VernamCipher\VernamCipher;

const ENCRYPT = 'Encrypt';
const DECRYPT = 'Decrypt';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== ENCRYPT && $action !== DECRYPT) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $plainText = (string) $_POST['plain-text'];
    $key       = (string) $_POST['key'];
    $_SESSION['input']  = $plainText;
    $_SESSION['key']    = $key;

    if ($_FILES['file']['size'] !== 0) {
        $path      = $_FILES['file']['tmp_name'];
        $handle    = fopen($path, "rb");
        $plainText = fread($handle, filesize($path));
        fclose($handle);
    }

    if ($plainText === '') {
        $_SESSION['error'][] = 'plaintext is empty';
    }

    if ($action === DECRYPT && $key === '') {
        $_SESSION['error'][] = 'key is empty';
    }

    if ($_SESSION['error']) {
        redirectToIndex();
    }

    $cipher = new VernamCipher();
    if ($action === ENCRYPT) {
        $key = $cipher->makeKey(strlen($plainText));
//        $keyFilename = basename($_FILES['file']['name'], '.txt') . '_key.txt';
//        header('Content-Disposition: attachment; type="text/plain"; filename="' . $keyFilename . '"');
//        echo $key;
    }
//    else {
        header('Location: ' . 'index.php');
//    }


    try {
        $_SESSION['input']  = $plainText;
        $_SESSION['key']    = $key;
        $_SESSION['output'] =
            $action === ENCRYPT
                ? $cipher->encrypt($plainText, $key)
                : $cipher->decrypt($plainText, $key);
    } catch (Exception $e) {
        $_SESSION['error'][] = $e->getMessage();
        redirectToIndex();
    }

    exit();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}