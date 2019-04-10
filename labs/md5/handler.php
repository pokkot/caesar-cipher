<?php declare(strict_types = 1);

session_start();

const REGISTER = 'Register';
const LOGIN    = 'Login';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== REGISTER && $action !== LOGIN) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    $username = (string) $_POST['username'];
    $password = (string) $_POST['password'];
    $_SESSION['username'] = $username;
    $_SESSION['password']   = $password;

    if ($username === '' || $password === '') {
        $_SESSION['error'][] = 'username or password is empty';
    }
    if ($_SESSION['error']) {
        redirectToIndex();
    }

    if ($action === REGISTER) {
        $_SESSION['users'][] = sprintf("%s:%s", $username, MD5($password));
    } else {
        $userIsExists = false;
        foreach ($_SESSION['users'] as $user) {
            $credentials = explode(':', $user);
            if ($credentials[0] === $username) {
                $userIsExists = true;
                if ($credentials[1] === MD5($password)) {
                    $_SESSION["message"] = "Welcome, ".$username."!";
                } else {
                    $_SESSION["error"][] = "Password is wrong!";
                }
                break ;
            }
        }
        if (false === $userIsExists) {
            $_SESSION["error"][] = "User \"$username\" not found!";
        }
    }

    redirectToIndex();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}