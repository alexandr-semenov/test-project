<?php
declare(strict_types = 1);

namespace App\Service\Transactions\Operation;

use App\Entity\User;
use App\Model\InputInterface;
use App\Service\Transactions\Exception\InsufficientFundsException;
use App\Service\Transactions\Exception\TransactionAlreadyExistException;

/**
 * Class DebitOperation
 */
class DebitOperation extends AbstractOperation
{
    const OPERATION_NAME = "Debit Operation";

    /**
     * @param InputInterface $input
     *
     * @throws TransactionAlreadyExistException
     * @throws InsufficientFundsException
     */
    public function execute(InputInterface $input): void
    {
        $this->startTransaction($input);

        $user = $this->userRepository->findByUid($input->getUid());
        $this->updateAmount($user, $input->getAmount());
    }

    /**
     * @param User $user
     * @param int $amount
     *
     * @throws InsufficientFundsException
     */
    private function updateAmount(User $user, int $amount): void
    {
        $newAmount = $user->getAmount() - $amount;

        if ($newAmount > 0) {
            $user->setAmount($newAmount);
            $this->userRepository->update($user);

            return;
        }

        throw new InsufficientFundsException('insufficient funds');
    }
}
