<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="transactions",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="tid", columns={"tid"})
 *    }
 * )
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", length=32)
     */
    private $tid;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getTid(): ?int
    {
        return $this->tid;
    }

    /**
     * @param int $tid
     * @return $this
     */
    public function setTid(int $tid): self
    {
        $this->tid = $tid;

        return $this;
    }
}
