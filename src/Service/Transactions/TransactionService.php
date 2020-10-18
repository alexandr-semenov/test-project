<?php
declare(strict_types = 1);

namespace App\Service\Transactions;

use App\AppResponse\AppResponseFactory;
use App\AppResponse\Result;
use App\Model\InputInterface;
use App\Service\Transactions\Exception\InsufficientFundsException;
use App\Service\Transactions\Exception\TransactionAlreadyExistException;
use App\Service\Transactions\Operation\CreditOperation;
use App\Service\Transactions\Operation\DebitOperation;
use App\Service\Transactions\Operation\OperationInterface;
use App\Service\Transactions\OperationFactory\OperationFactory;
use Doctrine\DBAL\TransactionIsolationLevel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class TransactionService
 */
class TransactionService
{
    const CREDIT_OPERATION = CreditOperation::class;
    const DEBIT_OPERATION = DebitOperation::class;

    /**
     * @var OperationFactory
     */
    private $operationFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Result
     */
    private $response;

    /**
     * TransactionService constructor.
     * @param OperationFactory $operationFactory
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param AppResponseFactory $appResponseFactory
     */
    public function __construct(
        OperationFactory $operationFactory,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        AppResponseFactory $appResponseFactory
    )
    {
        $this->operationFactory = $operationFactory;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->response = $appResponseFactory->create();
    }

    /**
     * @param string $operationClass
     * @param InputInterface $input
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function run(string $operationClass, InputInterface $input)
    {
        $connection = $this->entityManager->getConnection();
        $connection->setTransactionIsolation(TransactionIsolationLevel::SERIALIZABLE);
        $connection->beginTransaction();

        /** @var OperationInterface $operation */
        $operation = $this->operationFactory->make($operationClass);

        try {
            $operation->execute($input);

            $connection->commit();

            $this->addOperationToLog($operation, $input);
            $this->response->setStatus(Result::SUCCESS);
        } catch (\Exception $e) {
            $connection->rollBack();
            $this->addOperationErrorToLog($operation, $e);

            $this->handleError($e);
        }
        return $this->response;
    }

    /**
     * @param OperationInterface $operation
     * @param \Exception $exception
     */
    private function addOperationErrorToLog(OperationInterface $operation, \Exception $exception): void
    {
        $context = [
            'operation' => $operation::OPERATION_NAME,
            'error_message' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
        ];

        $this->logger->error(sprintf('%s - Error', $operation::OPERATION_NAME), $context);
    }

    /**
     * @param OperationInterface $operation
     * @param InputInterface $input
     */
    private function addOperationToLog(OperationInterface $operation, InputInterface $input): void
    {
        $context = [
            'operation' => $operation::OPERATION_NAME,
            'amount' => $input->getAmount(),
            'tid' => $input->getTid(),
        ];

        $this->logger->info(sprintf('%s - Success', $operation::OPERATION_NAME), $context);
    }

    /**
     * @param \Exception $exception
     */
    private function handleError(\Exception $exception): void
    {
        if ($exception instanceof TransactionAlreadyExistException) {
            $this->response->setStatus(Result::SUCCESS);
            return;
        }

        if ($exception instanceof InsufficientFundsException) {
            $this->response->setStatus(Result::ERROR);
            $this->response->setMessage(Result::INSUFFICIENT_FUNDS_ERROR);
            return;
        }
    }
}
