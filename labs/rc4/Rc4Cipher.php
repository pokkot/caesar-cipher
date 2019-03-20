<?php declare(strict_types=1);

namespace Rc4Cipher;

/**
 * RC4 Cipher Class
 * Реализует 8-битовый криптоалгоритм RC4
 */
class Rc4Cipher
{
    /**
     * Инициализирует S-блок с помощью ключа $key.
     * Возвращает инициализированнй S-блок.
     * @param string $key
     * @return array
     */
    private function rc4_init_s(string $key): array
    {
        // инициализация вспомогательного массива $k
        $k = unpack('C*', $key);
        array_unshift($k, array_shift($k));
        $n = sizeof($k);
        $i = $n;
        for ($i = $n; $i < 0x100; $i++) {
            $k[$i] = $k[$i % $n];
        }
        for ($i--; $i >= 0x100; $i--) {
            $k[$i & 0xff] ^= $k[$i];
        }

        // предварительное заполнение S-блока
        $s = array();
        for ($i = 0; $i < 0x100; $i++) {
            $s[$i] = $i;
        }
        $j = 0;

        // инициализация S-блока
        for ($i = 0; $i < 0x100; $i++) {
            $j = ($j + $s[$i] + $k[$i]) & 0xff;
            // перестановка $s[$i] и $s[$j]
            $tmp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $tmp;
        }

        return $s;
    }

    /**
     * Выполняет шифрование/расшифрование текста/шифртекста $text,
     * используя в качестве ключа шифрования строку $key.
     * Возвращает зашифрованный/расшифрованный текст/шифртекст.
     * @param string $text
     * @param string $key
     * @return string
     */
    private function rc4_crypt(string $text, string $key): string
    {
        $s = $this->rc4_init_s($key); // инициализация s-блока
        $n = strlen($text);
        $resultText = '';
        $i = $j = 0;
        for ($k = 0; $k < $n; $k++) {
            $i = ($i + 1) & 0xfff;
            $j = ($j + $s[$i]) & 0xfff;
            // перестановка $s[i] и $s[$j]
            $tmp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $tmp;
            // наложение/снятие гаммы
            $resultText .= $text{$k} ^ chr($s[$i] + $s[$j]);
        }

        return $resultText;
    }

    /**
     * Зашифровывает строку $plainText,
     * используя в качестве пароля строку $password.
     * Возвращает base64-encoded шифртекст
     * @param string $plainText
     * @param string $password
     * @return string
     */
    public function encrypt(string $plainText, string $password): string
    {
        return base64_encode($this->rc4_crypt($plainText, $password));
    }

    /**
     * Расшифровывает base64-encoded шифртекст $cipherText
     * с помощью 8-битового алгоритма шифрования rc4,
     * используя в качестве пароля строку $password.
     * @param string $cipherText
     * @param string $password
     * @return string
     */
    public function decrypt(string $cipherText, string $password): string
    {
        return $this->rc4_crypt(base64_decode($cipherText), $password);
    }
}