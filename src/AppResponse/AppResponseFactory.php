<?php
declare(strict_types = 1);

namespace App\AppResponse;

/**
 * Class AppResponseFactory
 */
class AppResponseFactory
{
    /**
     * @return Result
     */
    public function create(): Result
    {
        return new Result();
    }
}
