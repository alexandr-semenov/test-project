<?php
declare(strict_types = 1);

namespace App\Service\Transactions\Operation;

use App\Entity\Transaction;
use App\Model\InputInterface;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\Transactions\Exception\TransactionAlreadyExistException;

/**
 * Class AbstractOperation
 */
abstract class AbstractOperation implements OperationInterface
{
    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * CreditOperation constructor.
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
     * @param InputInterface $input
     */
    protected function startTransaction(InputInterface $input): void
    {
        $transaction = new Transaction();
        $transaction->setTid($input->getTid());

        try {
            $this->transactionRepository->create($transaction);
        } catch (\Exception $exception) {
            throw new TransactionAlreadyExistException("duplicate transaction");
        }
    }
}
