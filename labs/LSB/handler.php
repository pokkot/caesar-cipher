<?php declare(strict_types = 1);

namespace LSB;

include_once('LSB.php');
include_once ('helper.php');

session_start();

const ENCRYPT = 'Encrypt';
const DECRYPT = 'Decrypt';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== ENCRYPT && $action !== DECRYPT) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $plainText = (string) $_POST['plain-text'];
    $_SESSION['lsb_input']  = $plainText;
    $_SESSION['lsb_output'] = "";

    if ($plainText === '') {
        $_SESSION['error'][] = 'plaintext is empty';
    }

    if ($_FILES['file']['size'] === 0) {
        $_SESSION['error'][] = 'image is not uploaded';
    }

    if ($_SESSION['error']) {
        redirectToIndex();
    }

    $pathToImage = $_FILES['file']['tmp_name'];
    $pathToImageWithMessage = 'image_with_message.png';

    $cipher = new LSB();

    try {
        if ($action === ENCRYPT) {
            $cipher->encrypt($plainText, $pathToImage, $pathToImageWithMessage);
            header("Expires: 0");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            $ext = pathinfo($pathToImageWithMessage, PATHINFO_EXTENSION);
            $basename = pathinfo($pathToImageWithMessage, PATHINFO_BASENAME);

            header("Content-type: application/".$ext);
            header('Content-length: '.filesize($pathToImageWithMessage));
            header("Content-Disposition: attachment; filename=\"$basename\"");
            readfile($pathToImageWithMessage);
            exit();
        } else {
            $_SESSION['lsb_output'] = $cipher->decrypt($pathToImage);
            redirectToIndex();
        }
    } catch (\Exception $e) {
        $_SESSION['error'][] = $e->getMessage();
        redirectToIndex();
    }
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}