<?php declare(strict_types=1);

namespace BSGS;

class BSGS
{
    private $y = 0;
    private $a = 0;
    private $p = 0;

    private $log = [];

    /**
     * @param integer $a
     * @param integer $b
     * @param integer $m
     * @return integer
     */
    public function calc(int $a, int $b, int $m): int
    {
        $this->y = $a;
        $this->a = $b;
        $this->p = $m;

        $this->log[] = sprintf('a = %d, b = %d, m = %d', $a, $b, $m);

        $n = (int) sqrt((float) $m) + 1;
        $this->log[] = sprintf('n = sqrt(m) + 1 = %d', $n);

        $an = 1; // a ^ n
        for ($i = 0; $i < $n; ++$i) {
            $an = ($an * $a) % $m;
        }
        $this->log[] = sprintf('a^n = %d', $an);

        $vals = [];
        $this->log[] = '==Giant steps:==';
        $this->log[] = 'gs[i] = a^(n*i)  mod p';
        $this->log[] = '===';
        for ($i = 1, $cur = $an; $i <= $n; ++$i) {
            $this->log[] = sprintf('gs[%d], val = %d', $i, $cur);
            if (!key_exists($cur, $vals)) {
                $vals[$cur] = $i;
            }

            $cur = ($cur * $an) % $m;
        }

        $this->log[] = '==Baby steps:==';
        $this->log[] = 'bs[j] = b*a^j mod p';
        $this->log[] = '===';
        for ($i = 0, $cur = $b; $i <= $n; ++$i) {
            $this->log[] = sprintf('bs[%d], val = %d', $i, $cur);
            if (key_exists($cur, $vals)) {
                $ans = $vals[$cur] * $n - $i;

                $this->log[] = 'bs[i] == gs[j]';
                $this->log[] = sprintf('x = n * j - i == %d', $ans);
                $this->log[] = sprintf('x = %d * %d - %d == %d', $n, $vals[$cur], $i, $ans);

                if ($ans < $m) {
                    return $ans;
                }
            }

            $cur = ($cur * $a) % $m;
        }

        return -1;
    }

    public function getLog()
    {
        return $this->log;
    }
}