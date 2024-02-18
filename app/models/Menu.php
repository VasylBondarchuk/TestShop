<?php

class Menu extends Model
{
    private int $id;
    private string $name;
    private string $path;
    private bool $active;
    private ?int $sortOrder;

    /**
     * Menu constructor.
     */
    function __construct()
    {
        $this->table_name = "menu";
        $this->id_column = "id";
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setSortOrder(?int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * Retrieve a collection of menu items.
     * 
     * @return array Array of Menu objects.
     */
    public function getCollection(): array
    {
        $db = new DB();
        $menusData = $db->query("SELECT * FROM $this->table_name");

        $menus = [];
        foreach ($menusData as $menuData) {
            $menu = new Menu();
            $menu->setId($menuData['id']);
            $menu->setName($menuData['name']);
            $menu->setPath($menuData['path']);
            $menu->setActive((bool)$menuData['active']);
            $menu->setSortOrder($menuData['sort_order'] ?? null);
            $menus[] = $menu;
        }

        return $menus;
    }
}

