<?php
declare(strict_types = 1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

/**
 * Class DebitInput
 *
 * @JMS\XmlRoot("debit")
 *
 * @OA\Schema(@OA\Xml(name="debit"))
 */
class DebitInput implements InputInterface
{
    /**
     * @var int
     *
     * @JMS\Type("integer")
     * @JMS\XmlAttribute()
     *
     * @OA\Property(@OA\Xml(attribute=true))
     *
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value="0")
     */
    private $amount;

    /**
     * @var int
     *
     * @JMS\Type("int")
     * @JMS\XmlAttribute()
     *
     * @OA\Property(@OA\Xml(attribute=true))
     *
     * @Assert\Type(type="integer")
     * @Assert\Length(min="12", max="32")
     */
    private $tid;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\XmlAttribute()
     *
     * @OA\Property(@OA\Xml(attribute=true))
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="1", max="32")
     */
    private $uid;

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getTid(): int
    {
        return $this->tid;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }
}
