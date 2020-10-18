<?php
declare(strict_types = 1);

namespace App\Service\Transactions\OperationFactory;

use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\Transactions\Operation\OperationInterface;

/**
 * Class OperationFactory
 */
class OperationFactory implements OperationFactoryInterface
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * OperationFactory constructor.
     * @param TransactionRepository $transactionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        UserRepository $userRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $operationClass
     *
     * @return OperationInterface
     */
    public function make(string $operationClass): OperationInterface
    {
        return new $operationClass($this->transactionRepository, $this->userRepository);
    }
}
