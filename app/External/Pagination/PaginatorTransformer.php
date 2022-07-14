<?php
declare(strict_types=1);

namespace App\External\Pagination;

use EoneoPay\Utils\XmlConverter;
use LoyaltyCorp\EasyPagination\Interfaces\LengthAwarePaginatorInterface;

final class PaginatorTransformer implements PaginatorTransformerInterface
{
    /**
     * @var null|mixed[]
     */
    private $items;

    /**
     * @var \LoyaltyCorp\EasyPagination\Interfaces\LengthAwarePaginatorInterface
     */
    private $paginator;

    /**
     * @var int
     */
    private $totalItems;

    /**
     * PaginatorWrapper constructor.
     *
     * @param \LoyaltyCorp\EasyPagination\Interfaces\LengthAwarePaginatorInterface $paginator
     */
    public function __construct(LengthAwarePaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get current page.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->paginator->getCurrentPage();
    }

    /**
     * Get current items being paginated.
     *
     * @return mixed[]
     */
    public function getItems(): array
    {
        if ($this->items !== null) {
            return $this->items;
        }

        return $this->items = $this->paginator->getItems();
    }

    /**
     * Get items to be shown per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->paginator->getItemsPerPage();
    }

    /**
     * Get total number of paginated items.
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        if ($this->totalItems !== null) {
            return $this->totalItems;
        }

        return $this->totalItems = $this->paginator->getTotalItems();
    }

    /**
     * Get total number of pages based on the total number of items.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->paginator->getTotalPages();
    }

    /**
     * When current page has a next page.
     *
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->paginator->hasNextPage();
    }

    /**
     * When current page has a previous page.
     *
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->paginator->hasPreviousPage();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Replace the items.
     *
     * @param mixed[] $items
     *
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * Get the contents of the repository as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'items' => $this->getItems(),
            'pagination' => [
                'current_page' => $this->getCurrentPage(),
                'has_next_page' => $this->hasNextPage(),
                'has_previous_page' => $this->hasPreviousPage(),
                'items_per_page' => $this->getItemsPerPage(),
                'total_items' => $this->getTotalItems(),
                'total_pages' => $this->getTotalPages()
            ]
        ];
    }

    /**
     * Generate json from the repository.
     *
     * @return string
     */
    public function toJson(): string
    {
        return (string)\json_encode($this->jsonSerialize());
    }

    /**
     * Generate XML string from the repository.
     *
     * @param null|string $rootNode The name of the root node
     *
     * @return null|string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function toXml(?string $rootNode = null): ?string
    {
        return (new XmlConverter())->arrayToXml($this->toArray(), $rootNode);
    }
}
