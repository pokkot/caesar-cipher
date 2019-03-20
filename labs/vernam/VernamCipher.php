<?php declare(strict_types=1);

namespace VernamCipher;

/**
 * Vernam Cipher class.
 */
class VernamCipher
{
    /** @var integer */
    const KEY_LENGTH = 1024;

    /** @var string */
    private $key = null;

    /**
     * @param string|null $key
     */
    public function __construct(?string $key = null)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $plainText
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function encrypt(string $plainText, string $key): string
    {
        $cipherText = $this->execute($plainText, $key);
        return $cipherText;
    }

    /**
     * @param string $cipherText
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function decrypt(string $cipherText, string $key): string
    {
        $plainText = $this->execute($cipherText, $key);
        return $plainText;
    }

    /**
     * @param string $text
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function execute(string $text, string $key): string
    {
        if (strlen($text) !== strlen($key)) {
            throw new \Exception('Key length must be equal to plaintext length.');
        }

        return $this->charByCharXor($text, $key);
    }

    public function charByCharXor(string $str1, string $str2): string
    {
        $result = '';
        $n = strlen($str1);
        for ($i = 0; $i < $n; $i++) {
            $ascii = ord ($str1{$i} ^ $str2{$i});
            $min = 32;
            $max = 126;
            if ($ascii > $max) {
                $ascii = $ascii - $max;
            }
            if ($ascii < $min) {
                $ascii = $ascii + $min;
            }
            $result .= chr($ascii);
        }

        return $result;
    }

    /**
     * @param integer $keyLength
     * @return string
     */
    public function makeKey(int $keyLength = self::KEY_LENGTH): string
    {
        try {
            $bytes     = random_bytes($keyLength);
            $this->key = substr(bin2hex($bytes), 0, $keyLength);
        } catch (\Exception $e) {
        }

        return $this->key;
    }
}