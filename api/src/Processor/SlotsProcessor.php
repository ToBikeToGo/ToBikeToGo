<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

/**
 * @template T
 */
class SlotsProcessor implements ProcessorInterface
{
    /**
     * @param $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return T
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof \DateTimeInterface) {
            $data = $data->format('Y-m-d');
        }
        return $data;
    }
}
