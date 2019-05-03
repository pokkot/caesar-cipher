<?php declare(strict_types=1);

namespace DiscLog;

class DiscLog
{
    private $y = 0;
    private $a = 0;
    private $p = 0;

    private $result = 0;

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

        $pow = 0;
        $result = 0;
        for ($x = 1; $x < $p; $x++) {
            $pow = $this->modularPow($a, $x, $p);
            if ($y == $pow) {
                $result = $x;
                break ;
            }
            $this->log[] = sprintf('%d != %d ^ %d mod %d != %d ^ %d', $y, $a, $x, $p, $a, $pow);
        }

        $this->log[] = sprintf('%d == %d ^ %d mod %d != %d ^ %d', $y, $a, $x, $p, $a, $pow);
        $this->result = $result;

        return $result;
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

    public function getLog()
    {
        return $this->log;
    }
}