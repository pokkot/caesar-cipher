<?php declare(strict_types=1);

namespace RSA;

/**
 * RSA hash function reference implementation.
 * @see https://en.wikipedia.org/wiki/RSA_(cryptosystem)
 */
class RSA
{
    private const KEY_SIZE = 2;

    // Prime numbers 'p' и 'q'
    private $p = 0x0;
    private $q = 0x0;

    // Values of functions n(p,q) and ph(p,q)
    private $n  = 0x0;
    private $ph = 0x0;

    // Encryption and decryption key
    private $e = 0x0;
    private $d = 0x0;

    private $log = [];

    /**
     * Generates a k-bit RSA public/private key pair.
     * 1) Choose two distinct prime numbers: $q, $p.
     * 2) Compute n($p,$q) and ph($p,$q) functions.
     * 3) Choose any number 1 < $e < $ph that is co-prime to $ph.
     *    Choosing a prime number for $e leaves us only to check
     *    that $e is not a divisor of $ph (gcd($e, $ph) == 1).
     * 4) Compute $d, the modular multiplicative inverse of $e.
     * @param integer $keySize
     * @return string
     */
    public function generate(int $keySize = self::KEY_SIZE): string
    {
        do {
            $p = $this->generatePrimeNumber($keySize);
            $q = $this->generatePrimeNumber($keySize);
        } while ($p == $q);

        $n  = $p * $q;
        $ph = ($p - 1) * ($q - 1);

        do {
            $e = mt_rand(1, $ph - 1);
        } while (($this->gcd($e, $ph) != 1));

        $d = $this->modularInversion($e, $ph);

        $this->p = $p;
        $this->q = $q;
        $this->ph= $ph;
        $this->n = $n;
        $this->e = $e;
        $this->d = $d;

        return '';
    }

    /**
     * @param string $message
     * @return string
     */
    public function encrypt(string $message): string
    {
        $this->log[] = "===ENCRYPTION===\n";

        $bytes = unpack('C*', $message);
        array_unshift($bytes, array_shift($bytes));
        $size = sizeof($bytes);

        $cipher = [];
        // c = m^e mod n
        for ($j = 0; $j < $size; $j++) {
            $result = $this->modularPow(
                $bytes[$j], $this->e, $this->n
            );

            $cipher[] = dechex((int) $result);
            $this->log[] = sprintf("%s[%d] => %s[%d]", substr($message, $j, 1), $bytes[$j], $cipher[$j], $result);
        }

        $cipher = implode(" ", $cipher);
        return $cipher;
    }

    /**
     * @param string $cipher
     * @return string
     */
    public function decrypt(string $cipher): string
    {
        $this->log[] = "\n===DECRYPTION===\n";

        $bytes = explode(" ", $cipher);
        $size = sizeof($bytes);

        $message = [];
        // m = c^d mod n
        for ($j = 0; $j < $size; $j++) {
            $ascii = hexdec($bytes[$j]);

            $result = $this->modularPow(
                $ascii, $this->d, $this->n
            );

            $message[] = chr((int) $result);
            $this->log[] = sprintf("%s[%d] => %s[%d]", $bytes[$j], $ascii, $message[$j], $result);
        }

        return implode("", $message);
    }

    /**
     *
     * @param integer $b base
     * @param integer $e exponent
     * @param integer $m modulus
     * @return integer
     */
    private function modularPow(int $b, int $e, int $m): int
    {
        $result = 1;
        while ($e > 0) {
            if (($e % 2) == 1) {
                $result = ($result * $b) % $m;
            }
            $e >>= 1;
            $b = ($b * $b) % $m;
        }

        return $result;
    }

    /**
     * Generates a random prime number with predefined length.
     * @param integer $length
     * @return integer
     */
    private function generatePrimeNumber(int $length): int
    {
        $min = '1';
        $max = '9';
        for ($i = 1; $i < $length; $i++) {
            $min .= '0';
            $max .= '9';
        }
        $min = (int) $min;
        $max = (int) $max;

        $number = mt_rand($min, $max);
        while (false === $this->isPrime($number)) {
            $number--;
            if ($number < $min) {
                $number = $max;
            }
        };
        return $number;
    }

    /**
     * Checks if a number is prime.
     * @param integer $number
     * @return boolean
     */
    private function isPrime(int $number): bool
    {
        if ($number <= 3) {
            return true;
        } else if (
            (0 == $number % 2) ||
            (0 == $number % 3)
        ) {
            return false;
        }
        $i = 5;
        while ($i * $i <= $number) {
            if (
                (0 == $number % $i) ||
                (0 == $number % ($i + 2))
            ) {
                return false;
            }
            $i = $i + 6;
        }
        return true;
    }

    /**
     * Greatest common divisor of two numbers.
     * @param integer $a
     * @param integer $b
     * @return integer
     */
    private function gcd(int $a, int $b): int
    {
        do {
            $r = $a % $b;
            $a = $b;
            $b = $r;
        } while ($b != 0);

        return $a;
    }

    /**
     * Calculates modular multiplicative inverse
     * via Extended Euclidean algorithm.
     * @param integer $a
     * @param integer $b
     * @return integer
     */
    public function modularInversion(int $a, int $b): int
    {
        $s = 0;  $_s = 1;
        $r = $b; $_r = $a;

        while ($r != 0) {
            $quotient = intval($_r / $r);
            $tmp = $r;
            $r  = $_r - $quotient * $tmp;
            $_r = $tmp;

            $tmp = $s;
            $s  = $_s - $quotient * $tmp;
            $_s = $tmp;
        }

        if ($_s < 0) {
            $_s += $b;
        }

        return $_s;
    }

    public function getP()
    {
        return $this->p;
    }

    public function getQ()
    {
        return $this->q;
    }

    public function getE()
    {
        return $this->e;
    }


    public function getD()
    {
        return $this->d;
    }

    public function getN()
    {
        return $this->n;
    }

    public function getPh()
    {
        return $this->ph;
    }

    public function getLog()
    {
        return $this->log;
    }
}