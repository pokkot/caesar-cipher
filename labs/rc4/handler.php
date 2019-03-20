<?php declare(strict_types = 1);
session_start();

include_once('Rc4Cipher.php');
use Rc4Cipher\Rc4Cipher;

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

    if ($plainText === '') {
        $_SESSION['error'][] = 'plaintext is empty';
    }

    if ($action === DECRYPT && $key === '') {
        $_SESSION['error'][] = 'key is empty';
    }

    if ($_SESSION['error']) {
        redirectToIndex();
    }

    $cipher = new Rc4Cipher();
    try {
        $_SESSION['rc4_input']  = $plainText;
        $_SESSION['rc4_key']    = $key;
        $_SESSION['rc4_output'] =
            $action === ENCRYPT
                ? $cipher->encrypt($plainText, $key)
                : $cipher->decrypt($plainText, $key);
    } catch (Exception $e) {
        $_SESSION['error'][] = $e->getMessage();
    }

    redirectToIndex();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}