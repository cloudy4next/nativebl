<?php

namespace App\Services\TigerWeb;


use App\Repositories\TigerWeb\CommonRepository;
use Illuminate\Support\Collection;

final class CommonService

{

    private $commonRepository;

    public function __construct(CommonRepository $commonRepository)
    {

        $this->commonRepository = $commonRepository;
    }

    public function articleList()
    {
        return $this->commonRepository->articleList();
    }

    public function articleCategoryList()
    {
        return $this->commonRepository->articleCategoryList();
    }

    public function faqList()
    {
        return $this->commonRepository->faqList();
    }

    /**
     * @param $searchTerm
     * @param Collection $collection
     * @param array $columnNames
     * @return Collection
     */
    public function highlightKeyword($searchTerm, Collection $collection, array $columnNames, $skipImage = true): Collection
    {
        // Words to highlight
        $wordsToHighlight = preg_split('/\s+/', $searchTerm);

        // Pre-compute the pattern outside the loop for keywords
        $patternKeyword = '/\b(' . implode('|', array_map('preg_quote', $wordsToHighlight)) . ')\b/i';

        // Pattern to find and remove image tags
        $patternImageRemove = '/<img[^>]*>/i';

        $collection->each(function ($item) use ($patternKeyword, $patternImageRemove, $columnNames, $skipImage) {
            foreach ($columnNames as $columnName) {
                // Check if the column exists for the item and if it is a string
                if (isset($item->$columnName) && is_string($item->$columnName)) {
                    // Remove all image tags from the specified column
                    if ($skipImage) {
                        $item->$columnName = preg_replace($patternImageRemove, '', $item->$columnName);
                    }
                    // Apply the highlighting to the specified column for keywords
                    $item->$columnName = preg_replace($patternKeyword, '<strong class="highlight">$0</strong>', $item->$columnName);
                }
            }
        });

        return $collection;
    }

}
