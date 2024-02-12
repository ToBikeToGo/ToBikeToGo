<?php

namespace App\Filter;

use App\Entity\Booking;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\FilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;

final class CustomDateFilter extends AbstractFilter implements FilterInterface
{
    private bool $already = false;
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void
    {
        if (!isset($context['filters']['startAt']) && !isset($context['filters']['endAt'])) {
            return;
        }
        $startDate = $context['filters']['startAt'];
        $endDate = $context['filters']['endAt'];

        $startDate = \DateTime::createFromFormat('d/m/Y', $startDate);
        $endDate = \DateTime::createFromFormat('d/m/Y', $endDate);

        if (!$this->already) {
            $queryBuilder
                ->leftJoin(Booking::class, 'bo', 'WITH', 'bo.bike = o.id')
                ->andWhere('bo.id IS NULL OR (bo.startDate > :endAt OR bo.endDate < :startAt)')
                ->setParameter('startAt', $startDate)
                ->setParameter('endAt', $endDate);
                $this->already = true;
        }
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        return [
            'startAt' => [
                'property' => 'startAt',
                'type' => 'date',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter by start date',
                    'name' => 'Start date',
                    'type' => 'date',
                ],
            ],
            'endAt' => [
                'property' => 'endAt',
                'type' => 'date',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter by end date',
                    'name' => 'End date',
                    'type' => 'date',
                ],
            ],
        ];
    }
}
