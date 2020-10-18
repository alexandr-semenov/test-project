<?php
declare(strict_types = 1);

namespace App\Service\Transactions;

use App\AppResponse\Result;
use App\Model\InputInterface;

/**
 * Class OperationService
 */
class OperationService
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * OperationService constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param InputInterface $input
     *
     * @return Result
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function creditOperation(InputInterface $input): Result
    {
        return $this->transactionService->run(TransactionService::CREDIT_OPERATION, $input);
    }

    /**
     * @param InputInterface $input
     *
     * @return Result
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function depositOperation(InputInterface $input): Result
    {
        return $this->transactionService->run(TransactionService::DEBIT_OPERATION, $input);
    }
}
