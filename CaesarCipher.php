<?php declare(strict_types = 1);

namespace CaesarCipher;

/**
 * Caesar Cipher class.
 * Provides encryption & decryption via Caesar Cipher algorithm.
 */
class CaesarCipher
{
    /**
     * @var array
     */
    private $alphabet;

    /**
     * @see https://theasciicode.com.ar/
     * ASCII printable symbols: 32 - 126
     * ASCII external symbols:  127 - 255
     * Cyrillic letters:        1040 - 1103 (except 'ё' and 'Ё'), 1025 — Ё, 1105 — ё
     * @param array|null $alphabet
     */
    public function __construct(array $alphabet = null)
    {
        if ($alphabet === null) {
            $asciiPrintable  = range(
                chr(0),
                chr(255)
            );
            $alphabet = $asciiPrintable;
        }
        $this->alphabet = $alphabet;
    }

    /**
     * @param string   $plainText
     * @param integer  $key
     * @return string
     */
    public function encrypt(string $plainText, int $key): string
    {
        return $this->execute($plainText, $key);
    }

    /**
     * @param string   $cipherText
     * @param integer  $key
     * @return string
     */
    public function decrypt(string $cipherText, int $key): string
    {
        return $this->execute($cipherText, -$key);
    }

    /**
     * @param string  $text
     * @param integer $key
     * @return string
     */
    private function execute(string $text, int $key): string
    {
        $chars = str_split($text);
        foreach ($chars as $i => $char) {
            $chars[$i] = $this->shift($char, $key);
        }

        return implode($chars);
    }

    /**
     * @param string  $char
     * @param integer $key
     * @return string
     */
    private function shift(string $char, int $key): string
    {
        $alphabetSize = $this->getAlphabetSize();
        $maxKeyValue  = $this->getMaxKeyValue();
        $minKeyValue  = $this->getMinKeyValue();

        $ascii = ord($char);
        $offset = $key;

        if ($ascii + $offset > $maxKeyValue) {
            $offset -= $alphabetSize;
        }

        if ($ascii + $offset < $minKeyValue) {
            $offset += $alphabetSize;
        }

        return chr($ascii + $offset);
    }

    /**
     * @return integer
     */
    public function generateKey(): int
    {
        return mt_rand($this->getKeyValueRange());
    }

    /**
     * @return integer
     */
    public function getAlphabetSize(): int
    {
        return count($this->alphabet);
    }

    /**
     * @return array
     */
    public function getKeyValueRange(): array
    {
        return range($this->getMinKeyValue(), $this->getMaxKeyValue());
    }

    /**
     * @return integer
     */
    public function getMinKeyValue(): int
    {
        return ord($this->alphabet[0]);
    }

    /**
     * @return integer
     */
    public function getMaxKeyValue(): int
    {
        $alphabetSize = $this->getAlphabetSize();
        return ord($this->alphabet[$alphabetSize - 1]);
    }
}