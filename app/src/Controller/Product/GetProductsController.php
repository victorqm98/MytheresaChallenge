<?php

namespace MytheresaChallenge\App\Controller\Product;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use MytheresaChallenge\Shared\Domain\HttpStatusCodes;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use MytheresaChallenge\Product\Application\UseCase\GetProductsUseCase;

final class GetProductsController
{
    public function __construct(
        private readonly GetProductsUseCase $getProductsUseCase,
        private readonly ValidatorInterface $validator,
        private readonly CacheInterface $cache
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        $getProductsRequest = $this->getRequestData($request);

        $errors = $this->validator->validate($getProductsRequest);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => (string) $errors,
            ], HttpStatusCodes::BAD_REQUEST);
        }

        $categoryIdsArray = $getProductsRequest->categoryIds;
        $skusArray = $getProductsRequest->skus;
        $page = $getProductsRequest->page;
        $limit = $getProductsRequest->limit;

        $cacheKey = $this->generateCacheKey($categoryIdsArray, $skusArray, $page, $limit);

        try {
            $productsResponse = $this->cache->get($cacheKey, function ($item) use ($categoryIdsArray, $skusArray, $page, $limit) {
                $item->expiresAfter(60);
                return $this->getProductsUseCase->execute($categoryIdsArray, $skusArray, $page, $limit)->toArray();
            });
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An unexpected error occurred: ' . $e], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($productsResponse);
    }

    private function generateCacheKey(array $categoryIds, array $skus, int $page, int $limit): string
    {
        $categoryIdsKey = implode('-', $categoryIds);
        $skusKey = implode('-', $skus);

        return sprintf('products_%s_%s_page_%d_limit_%d', $categoryIdsKey, $skusKey, $page, $limit);
    }

    private function getRequestData(Request $request): GetProductsRequest
    {
        $getProductsRequest = new GetProductsRequest();
        $categoryIds = $request->get('categoryIds', '');
        $getProductsRequest->categoryIds = $categoryIds ? explode(',', $categoryIds) : [];
        $skus = $request->get('skus', '');
        $getProductsRequest->skus = $skus ? explode(',', $skus) : [];
        $getProductsRequest->page = (int) $request->get('page', 1);
        $getProductsRequest->limit = (int) $request->get('limit', 5);

        return $getProductsRequest;
    }
}

