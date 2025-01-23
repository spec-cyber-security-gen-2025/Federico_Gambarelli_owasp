<?php

namespace App\Services;

use DOMDocument;
use Illuminate\Database\Eloquent\Collection;

class HtmlFilterService
{
    public function filterHtml($html)
    {
        // New DOMDocument object
        $doc = new DOMDocument();
        
        // Load html content
        $doc->loadHTML($html);
        
        // Find all script tags in document
        $scriptTags = $doc->getElementsByTagName('script');
        
        // Remove all script tags from document
        foreach ($scriptTags as $scriptTag) {
            $scriptTag->parentNode->removeChild($scriptTag);
        }
        
        // Return new filtered document
        return $doc->saveHTML();
    }
    
    public function filterHtmlCollectionByField(Collection $collection, string $key)
    {
        return $collection->map(function ($item) use($key){
            $item->$key = $this->filterHtml($item->$key);
            return $item;
        });
    }
}
