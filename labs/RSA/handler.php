<?php declare(strict_types = 1);
session_start();

include_once('RSA.php');
use RSA\RSA;

const ENCRYPT = 'Encrypt';
const DECRYPT = 'Decrypt';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== ENCRYPT && $action !== DECRYPT) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $plainText = (string) $_POST['plain-text'];
    $_SESSION['rsa_input'] = $plainText;

    if ($plainText === '') {
        $_SESSION['error'][] = 'plaintext is empty';
    }
    if ($_SESSION['error']) {
        redirectToIndex();
    }

    try {
        $rsa = new RSA();
        $rsa->generate(2);
        $_SESSION['p'] = $rsa->getP();
        $_SESSION['q'] = $rsa->getQ();
        $_SESSION['n'] = $rsa->getN();
        $_SESSION['ph'] = $rsa->getPh();
        $_SESSION['e'] = $rsa->getE();
        $_SESSION['d'] = $rsa->getD();
        $_SESSION['rsa_input']  = $plainText;
        $encryptedMessage = $rsa->encrypt($plainText);
        $_SESSION['rsa_enc_message'] = $encryptedMessage;
        $decryptedMessage = $rsa->decrypt($encryptedMessage);
        $_SESSION['rsa_dec_message'] = $decryptedMessage;
        $_SESSION['rsa_log'] = $rsa->getLog();
    } catch (Exception $e) {
        $_SESSION['error'][] = $e->getMessage();
    }

    redirectToIndex();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}
