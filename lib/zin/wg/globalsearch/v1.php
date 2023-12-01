<?php
declare(strict_types=1);
namespace zin;

class globalSearch extends wg
{
    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): wg
    {
        global $config, $lang;

        if($config->systemMode == 'light') unset($lang->searchObjects['program']);
        unset($lang->searchObjects['all']);

        $searchItems = array();
        $searchItems[] = array('key' => 'search', 'text' => $lang->searchAB . ' {0}');
        foreach($lang->searchObjects as $key => $module) $searchItems[] = array('key' => $key, 'text' => $module . ' #{0}');

        return zui::globalSearch
        (
            set::_id('globalSearch'),
            set::_class('w-44'),
            set::items($searchItems),
            set::searchHint($lang->searchAB),
            set::popPlacement('top-start'),
            set::popWidth(240),
            set::searchBox(array('circle' => true, 'suffixClass' => 'text-primary opacity-100')),
            set::getSearchType(jsRaw('window.getGlobalSearchType')),
            set::onSearch(jsRaw('window.handleGlobalSearch')),
            inherit($this)
        );
    }
}
