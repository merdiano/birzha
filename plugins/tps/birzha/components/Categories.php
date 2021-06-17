<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use TPS\Birzha\Models\Category;

class Categories extends ComponentBase
{
    /**
     * @var Collection A collection of categories to display
     */
    public $categories;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    /**
     * @var string Reference to the current category slug.
     */
    public $currentCategorySlug;

    public function componentDetails()
    {
        return [
            'name'        => 'Categories',
            'description' => 'List of categories'
        ];
    }

    public function defineProperties()
    {
        return [
            'active' => [
                'title'       => 'Active categories',
                'description' => 'Filter active categories only',
                'type'        => 'checkbox'
            ],
            'slug' => [
                'title'       => 'Slug',
                'description' => 'Category slug',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('slug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categories = $this->page['categories'] = $this->loadCategories();
    }

    public function loadCategories()
    {
        $categories = Category::query();

        if($this->property('active'))
            $categories->where('status',1);

        return $categories->get();
    }

}
