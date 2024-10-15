<?php 
namespace Src\Common\Application\DTOs;

use Illuminate\Http\Request;
use Src\Common\Application\Enums\PaginationInfo;
use Src\Common\Application\Enums\SortInfo;

class PageMetaDTO
{
    public function __construct(
        public int $page, 
        public int $pageSize, 
        public string $sort, 
        public string $sortDirection
    ) {}

    public static function fromRequest(Request $request): self
    {
        $page = max(PaginationInfo::DEFAULT_PAGE->value, (int) $request->page);
        $pageSize = max(PaginationInfo::DEFAULT_PAGE_SIZE->value, (int) $request->page_size);

        $sort = $request->sort ?? SortInfo::DEFAULT_SORT->value;
        $sortDirection = $request->sort_direction;

        if(!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = SortInfo::DEFAULT_SORT_DIRECTION->value; 
        }

        return new self(
            $page,
            $pageSize,
            $sort,
            $sortDirection
        );
    }
}