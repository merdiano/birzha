<?php namespace TPS\Birzha\Models;

use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
use Model;

/**
 * Model
 */
class Category extends Model
{

    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sortable;

    protected $dates = ['deleted_at'];

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'tps_birzha_category';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'   => 'required',
        'slug'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:tps_birzha_category'],
        'status' => 'required',
    ];

    public $translatable = ['name','slug'];

    public $hasMany = [
        'products' => 'TPS\Birzha\Models\Product'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function getMenuTypeInfo($type){
        $result = [];

        if ($type == 'category') {
            $result = [
                'references'   => Category::active()->pluck('name','id'),
                'nesting'      => true,
                'dynamicItems' => true
            ];
        }

        if ($type == 'all-categories') {
            $result = [
                'dynamicItems' => true
            ];
        }

        if ($result) {
            $theme = Theme::getActiveTheme();

            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];
            foreach ($pages as $page) {
                if (!$page->hasComponent('categories')) {
                    continue;
                }

                /*
                 * Component must use a category filter with a routing parameter
                 * eg: categoryFilter = "{{ :somevalue }}"
                 */
                $properties = $page->getComponentProperties('blogPosts');
                if (!isset($properties['categoryFilter']) || !preg_match('/{{\s*:/', $properties['categoryFilter'])) {
                    continue;
                }

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }
        return $result;
    }

    public static function resolveMenuItem($item, $url, $theme){

    }
}
