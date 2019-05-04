<?php declare(strict_types = 1);
session_start();

const START = 'Start';

if (isset($_POST)) {
    $action = $_POST['action'];
    if ($action !== START) {
        $_SESSION['error'][] = 'not supported action';
        redirectToIndex();
    }

    function getSha256Hash($input_value)
    {
        return hash('sha256', $input_value);
    }

    function checkIfBlockHashLessThanTarget($blockHash, $target)
    {
        return hexdec($blockHash) < hexdec($target);
    }

    // Initial block data (the transactions' merkle tree root, timestamp, client version, hash of the previous block)
    //$blockData = "0100000000000000000000000000000000000000000000000000000000000000000000003ba3edfd7a7b12b27ac72c3e67768f617fc81bc3888a51323a9fb8aa4b1e5e4a29ab5f49ffff001d1dac2b7c0101000000010000000000000000000000000000000000000000000000000000000000000000ffffffff4d04ffff001d0104455468652054696d65732030332f4a616e2f32303039204368616e63656c6c6f72206f6e20627266e6b206f66207365636f6e64206261696c6f757420666f722062616e6b73ffffffff0100f2052a01000000434104678afdb0fe5548271967f1a67130b7105cd6a828e03909a67962e0ea1f61deb649f6bc3f4cef38c4f35504e51ec112de5c384df7ba0b8d578a4c702b6bf11d5fac00000000";
    $blockData = '3ba3edfd';
    $_SESSION['block_data'] = $blockData;

    // Initial target - this is the easiest it will ever be to mine a Bitcoin block
    $target = '0x00000000FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF';
    $_SESSION['target'] = $target;

    $solution_found = false;
    $block_data_hexadecimal_value = hexdec($blockData);
    $nonce = 0;

    $_SESSION['nonce_log'] = [];

    $time_pre = microtime(true);
    while (!$solution_found) {
        $block_data_with_nonce = $block_data_hexadecimal_value + $nonce;

        // Find double hash
        $first_hash = getSha256Hash(dechex($block_data_with_nonce));
        $second_hash = getSha256Hash($first_hash);

        $solution_found = checkIfBlockHashLessThanTarget($second_hash, $target);

        if ($solution_found || $nonce == 1) {
            $_SESSION['nonce_log'][] = "Nonce: $nonce";
            $_SESSION['nonce_log'][] = "Block data (dec): $block_data_hexadecimal_value";
            $_SESSION['nonce_log'][] = "Block data + nonce: $block_data_with_nonce";
            $_SESSION['nonce_log'][] = "Block hash:";
            $_SESSION['nonce_log'][] = $second_hash;
            $_SESSION['nonce_log'][] = "Target:";
            $_SESSION['nonce_log'][] = $target;
            $_SESSION['nonce_log'][] = "Is the block hash less than the target?";
            $_SESSION['nonce_log'][] = $solution_found ? 'True' : 'False';
            $_SESSION['nonce_log'][] = '';
        }
        $nonce++;
    }
    $time_post = microtime(true);
    $time = $time_post - $time_pre;
    $_SESSION['nonce_log'][] = "start: $time_pre";
    $_SESSION['nonce_log'][] = "finish: $time_post";
    $_SESSION['nonce_log'][] = 'time: ' . $time;
    $_SESSION['nonce_log'][] = '';

    $_SESSION['nonce_result'] = $nonce - 1;
    redirectToIndex();
}

function redirectToIndex() {
    header('Location: ' . 'index.php');
    exit();
}
