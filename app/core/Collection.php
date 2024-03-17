<?php
namespace app\core;


class Collection
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add($item): void
    {
        $this->items[] = $item;
    }

    public function remove($index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
        }
    }

    public function get($index)
    {
        return $this->items[$index] ?? null;
    }

    public function getAll(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    // Additional methods for filtering, sorting, pagination, etc. can be added here
}
