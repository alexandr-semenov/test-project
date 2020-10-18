<?php
declare(strict_types = 1);

namespace App\Service\Transactions\Operation;

use App\Entity\User;
use App\Model\InputInterface;
use App\Service\Transactions\Exception\TransactionAlreadyExistException;

/**
 * Class CreditOperation
 */
class CreditOperation extends AbstractOperation
{
    const OPERATION_NAME = "Credit Operation";

    /**
     * @param InputInterface $input
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws TransactionAlreadyExistException
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
     */
    private function updateAmount(User $user, int $amount): void
    {
        $newAmount = $user->getAmount() + $amount;

        $user->setAmount($newAmount);
        $this->userRepository->update($user);
    }
}
