<?php declare(strict_types=1);

namespace BSGS;

class BSGS
{
    private $y = 0;
    private $a = 0;
    private $p = 0;

    private $log = [];

    /**
     * @param integer $y
     * @param integer $a
     * @param integer $p
     * @return integer
     */
    public function calc(int $y, int $a, int $p): int
    {
        $this->y = $y;
        $this->a = $a;
        $this->p = $p;

        $m = (int) sqrt((float) $p) + 1;
        $k = $m - 1;

        $this->log[] = sprintf('y = %d, a = %d, p = %d', $y, $a, $p);
        $this->log[] = sprintf('m = %d, k = %d', $m, $k);
        $this->log[] = '';

        $result = -1;

        $this->log[] = '-- Baby steps --';
        $this->log[] = 'bs[j] = b*a^i mod p';
        $babySeries = [];
        for ($i = 0; $i < $m - 1; $i++) {
            $babySeries[$i] = ($this->modularPow($a, $i, $p) * $y) % $p;
            $this->log[] = sprintf('[%d]%d', $i, $babySeries[$i]);
        }

        $this->log[] = '';
        $this->log[] = '-- Giant steps --';
        $this->log[] = 'gs[i] = a^(m*j) mod p';
        $giantSeries = [];
        for ($j = 1; $j < $k; $j++) {
            $giantSeries[$j] = $this->modularPow($a, $m * $j, $p);
            $this->log[] = sprintf('[%d]%d', $j, $giantSeries[$j]);

            $i = array_search($giantSeries[$j], $babySeries);
            if (false !== $i) {
                $result = $j * $m - $i;
                $this->log[] = '';
                $this->log[] = 'bs[i] == gs[j]';
                $this->log[] = sprintf('x = i * m - j == %d', $result);
                $this->log[] = sprintf('x = %d * %d - %d == %d', $i, $giantSeries[$j], $j, $result);
                break;
            }
        }

        return $result;
    }

    public function getLog()
    {
        return $this->log;
    }

    /**
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
}