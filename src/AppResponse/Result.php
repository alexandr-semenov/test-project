<?php
declare(strict_types = 1);

namespace App\AppResponse;

use JMS\Serializer\Annotation as JMS;
use OpenApi\Annotations as OA;

/**
 * Class Result
 * @JMS\XmlRoot("result")
 *
 * @OA\Schema(@OA\Xml(name="result"))
 */
class Result
{
    const SUCCESS = 'OK';
    const ERROR = 'ERROR';
    const INTERNAL_SERVER_ERROR = 'internal server error';
    const INSUFFICIENT_FUNDS_ERROR = 'insufficient funds';

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\XmlAttribute()
     * @JMS\Groups(groups={"success", "error"})
     *
     * @OA\Property(@OA\Xml(attribute=true))
     */
    private $status;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\XmlAttribute()
     * @JMS\Groups(groups={"error"})
     * @JMS\SerializedName("msg")
     *
     * @OA\Property(@OA\Xml(name="msg", attribute=true))
     */
    private $message;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
