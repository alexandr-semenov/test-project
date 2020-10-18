<?php
declare(strict_types = 1);

namespace App\ParamConverter;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class XmlRequestConverter
 */
class XmlRequestConverter implements ParamConverterInterface
{
    protected const CONVERTER_NAME = 'xml_converter';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $requestContent = $request->getContent();
        $parameterName = $configuration->getName();
        $parameterValue = $this->serializer->deserialize($requestContent, $configuration->getClass(), 'xml');

        $this->validate($parameterValue);

        $request->attributes->set($parameterName, $parameterValue);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return (bool) $configuration->getClass() && self::CONVERTER_NAME === $configuration->getConverter();
    }

    /**
     * @param object $object
     */
    private function validate($object): void
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            $this->addValidationErrorToLog($errors);

            throw new ValidationFailedException($errorsString, $errors);
        }
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     */
    public function addValidationErrorToLog(ConstraintViolationListInterface $violationList): void
    {
        $context = [];

        foreach ($violationList as $violation) {
            $context['errors'][] = [
                'message' => $violation->getMessage(),
                'property' => $violation->getPropertyPath(),
            ];
        }

        $this->logger->error('Validation errors', $context);
    }
}
