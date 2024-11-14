<?php

declare(strict_types=1);

namespace MytheresaChallenge\App\Controller\Product;

use Symfony\Component\Validator\Constraints as Assert;


class GetProductsRequest
{
    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string")
     * })
     */
    public array $categoryIds = [];

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string")
     * })
     */
    public array $skus = [];

    /**
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     */
    public int $page = 1;

    /**
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     */
    public int $limit = 5;
}
