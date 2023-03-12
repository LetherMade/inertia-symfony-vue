<?php
declare(strict_types=1);

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;

trait PaginationTrait
{
    protected function getPaginationLimitAndOffset(int $page, int $itemsPerPage = 10): array
    {
        $limit = $itemsPerPage;
        $offset = --$page * 10;

        return [$limit, $offset];
    }

    protected function getPaginationMaxPageNumber(int $totalCount, int $itemsPerPage = 10): int
    {
        return (int)ceil($totalCount / $itemsPerPage);
    }

    protected function wrapWithPaginationData(
        Request $request,
        array $data,
        int $totalCount,
        int $currentPage,
        string $targetRoute
    ): array
    {
        $result = [];

        $result['data'] = $data;
        $result['links'] = [];

        $result['links'][] = (object)[
            'url' => $currentPage === 1
                ? null
                : $this->generateUrl($targetRoute, $this->createSearchParams($request, ['page' => $currentPage - 1])),
            'label' => 'Previous',
            'active' => false
        ];

        $maxPageNumber = $this->getPaginationMaxPageNumber($totalCount);

        for ($i = 1; $i <= $maxPageNumber; $i++) {
            $result['links'][] = (object)[
                'url' => $this->generateUrl($targetRoute, $this->createSearchParams($request, ['page' => $i])),
                'label' => (string)$i,
                'active' => $currentPage === $i
            ];
        }

        $result['links'][] = (object)[
            'url' => $currentPage === $maxPageNumber
                ? null
                : $this->generateUrl($targetRoute, $this->createSearchParams($request, ['page' => $currentPage + 1])),
            'label' => 'Next',
            'active' => false
        ];

        return $result;
    }

    protected function createSearchParams(Request $request, array $params): array
    {
        return [...$request->query->all(), ...$params];
    }
}
