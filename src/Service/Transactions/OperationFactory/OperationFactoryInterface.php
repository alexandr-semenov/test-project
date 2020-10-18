<?php
declare(strict_types = 1);

namespace App\Service\Transactions\OperationFactory;

use App\Service\Transactions\Operation\OperationInterface;

/**
 * Interface OperationFactoryInterface
 */
interface OperationFactoryInterface
{
    /**
     * @param string $operationClass
     *
     * @return OperationInterface
     */
    public function make(string $operationClass): OperationInterface;
}
