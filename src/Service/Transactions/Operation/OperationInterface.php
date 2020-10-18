<?php
declare(strict_types = 1);

namespace App\Service\Transactions\Operation;

use App\Model\InputInterface;

/**
 * Interface OperationInterface
 */
interface OperationInterface
{
    /**
     * @param InputInterface $input
     */
    public function execute(InputInterface $input): void;
}
