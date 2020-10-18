<?php
declare(strict_types = 1);

namespace App\Model;

/**
 * Interface InputInterface
 */
interface InputInterface
{
    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @return int
     */
    public function getTid(): int;

    /**
     * @return string
     */
    public function getUid(): string;
}
