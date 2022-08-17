<?php

namespace App\Repositories\Presenters;

use App\Repositories\Contracts\PaginationInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatePresenter implements PaginationInterface
{
    protected $paginator;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function items(): array
    {
        return $this->paginator->items();
    }

    public function total(): int
    {
        return (int) $this->paginator->total() ?? 0;
    }

    public function currentPage(): int
    {
        return (int) $this->paginator->currentPage() ?? 1;
    }

    public function perPage(): int
    {
        return (int) $this->paginator->perPage() ?? 1;
    }

    public function firstPage(): int
    {
        return (int) $this->paginator->firstItem() ?? 1;
    }

    public function lastPage(): int
    {
        return (int) $this->paginator->lastPage() ?? 1;
    }
}
