<?php
declare(strict_types = 1);

namespace App\EventListener;

use App\AppResponse\AppResponseFactory;
use App\AppResponse\Result;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 */
class ExceptionListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ExceptionListener constructor.
     * @param SerializerInterface $serializer
     * @param AppResponseFactory $responseFactory
     * @param LoggerInterface $logger
     */
    public function __construct(SerializerInterface $serializer, AppResponseFactory $responseFactory, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->result = $responseFactory->create();
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        $this->result->setStatus(Result::ERROR);
        $this->result->setMessage(Result::INTERNAL_SERVER_ERROR);

        $this->addErrorToLog($event->getThrowable());

        $this->setXmlResponse($event, $statusCode);
    }

    /**
     * @param ExceptionEvent $event
     * @param int $statusCode
     */
    private function setXmlResponse(ExceptionEvent $event, int $statusCode): void
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
        $response->setContent($this->serializer->serialize($this->result, 'xml'));

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    /**
     * @param \Throwable $exception
     */
    private function addErrorToLog(\Throwable $exception): void
    {
        $this->logger->error('Error: ' . $exception,
            [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
            ]
        );
    }
}
