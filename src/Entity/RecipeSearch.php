<?php
namespace App\Entity;

class RecipeSearch
{    
    /**
     * titleSearch
     *
     * @var string|null
     */
    private $titleSearch;

    /**
     * Get titleSearch
     *
     * @return  string|null
     */ 
    public function getTitleSearch()
    {
        return $this->titleSearch;
    }

    /**
     * Set titleSearch
     *
     * @param  string|null  $title  title
     *
     * @return  self
     */ 
    public function setTitleSearch($titleSearch)
    {
        $this->titleSearch = $titleSearch;

        return $this;
    }
}
?>