<?php declare(strict_types = 1);
session_start();

include_once('CaesarCipher.php');
use CaesarCipher\CaesarCipher;

const ENCRYPT = 'Encrypt';
const DECRYPT = 'Decrypt';
const CRACK   = 'Crack';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== ENCRYPT && $action !== DECRYPT && $action !== CRACK) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $plainText = (string) $_POST['plain-text'];
    $key       = (int)    $_POST['key'];

    if ($_FILES['file']['size'] !== 0) {
        $path      = $_FILES['file']['tmp_name'];
        $handle    = fopen($path, "rb");
        $plainText = fread($handle, filesize($path));
        fclose($handle);
    }

    if ($plainText === '') {
        $_SESSION['error'][] = 'plaintext is empty';
    }

    $cipher = new CaesarCipher();
    if ($action === CRACK) {
        $_SESSION['key'] = $cipher->crack($plainText);
        redirectToIndex();
    }

    $alphabetSize = $cipher->getAlphabetSize();
    if ($key <= 0 || $key > $alphabetSize) {
        $_SESSION['error'][] = sprintf('key should be a number in range from 1 to %d', $alphabetSize);
    }

    if ($_SESSION['error']) {
        redirectToIndex();
    }

    $_SESSION['input']  = $plainText;
    $_SESSION['key']    = $key;
    $_SESSION['output'] =
        $action === ENCRYPT
        ? $cipher->encrypt($plainText, $key)
        : $cipher->decrypt($plainText, $key);

    redirectToIndex();
}

function execute() {

}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}