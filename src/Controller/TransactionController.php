<?php
declare(strict_types=1);

namespace App\Controller;

use App\AppResponse\AppResponseFactory;
use App\AppResponse\Result;
use App\Model\CreditInput;
use App\Model\DebitInput;
use App\Service\Transactions\OperationService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * Class TransactionController
 */
class TransactionController
{
    /**
     * @var AppResponseFactory
     */
    private $responseFactory;

    /**
     * @var OperationService
     */
    private $operationService;

    /**
     * TransactionController constructor.
     * @param AppResponseFactory $responseFactory
     * @param OperationService $operationService
     */
    public function __construct(AppResponseFactory $responseFactory, OperationService $operationService)
    {
        $this->responseFactory = $responseFactory;
        $this->operationService = $operationService;
    }

    /**
     * @Route(path="/credit", name="credit", methods={"POST"})
     *
     * @Rest\View(serializerGroups={"success"})
     *
     * @OA\Post(
     *     path="/credit",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="text/xml",
     *              @OA\Schema(ref="#components/schemas/CreditInput")
     *          )
     *     ),
     *     @OA\Response(response="200", description="Credit operation success", @OA\XmlContent(ref="#/components/schemas/CreditInput")),
     * )
     *
     * @ParamConverter(
     *      name="input",
     *      class="App\Model\CreditInput",
     *      converter="xml_converter"
     * )
     *
     * @param CreditInput $input
     *
     * @return Result
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function creditAction(CreditInput $input): Result
    {
        return $this->operationService->creditOperation($input);
    }

    /**
     * @Route(path="/debit", name="debit", methods={"POST"})
     *
     * @Rest\View(serializerGroups={"success", "error"})
     *
     * @OA\Post(
     *     path="/debit",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="text/xml",
     *              @OA\Schema(ref="#components/schemas/DebitInput")
     *          )
     *     ),
     *     @OA\Response(response="200", description="Debit operation success", @OA\XmlContent(ref="#/components/schemas/DebitInput")),
     * )
     *
     * @ParamConverter(
     *      name="input",
     *      class="App\Model\DebitInput",
     *      converter="xml_converter"
     * )
     *
     * @param DebitInput $input
     *
     * @return Result
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function debitAction(DebitInput $input): Result
    {
        return $this->operationService->depositOperation($input);
    }
}
