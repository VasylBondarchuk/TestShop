<?php
namespace app\core;

class Pagination {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;

    public function __construct(int $totalItems, int $itemsPerPage, int $currentPage = 1) {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = $currentPage;
    }

    public function getTotalPages(): int {
        return ceil($this->totalItems / $this->itemsPerPage);
    }

    public function getOffset(): int {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function getItemsPerPage(): int {
        return $this->itemsPerPage;
    }

    public function hasNextPage(): bool {
        return $this->currentPage < $this->getTotalPages();
    }

    public function hasPreviousPage(): bool {
        return $this->currentPage > 1;
    }

    public function getNextPage(): int {
        return min($this->currentPage + 1, $this->getTotalPages());
    }

    public function getPreviousPage(): int {
        return max($this->currentPage - 1, 1);
    }

    public function getLastPage(): int {
        return $this->getTotalPages();
    }

    public function getFirstPage(): int {
        return 1;
    }
}

