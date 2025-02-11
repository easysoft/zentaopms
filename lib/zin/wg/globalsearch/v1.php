<?php
declare(strict_types=1);
namespace zin;

class globalSearch extends wg
{
    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $config, $lang;

        if($config->systemMode == 'light') unset($lang->searchObjects['program'], $lang->searchObjects['charter']);
        unset($lang->searchObjects['all']);

        $searchItems = array();
        $searchItems[] = array('key' => 'search', 'text' => $lang->searchAB . ' {0}');
        foreach($lang->searchObjects as $key => $module) $searchItems[] = array('key' => $key, 'text' => $module . ' #{0}');
        $searchItemsEncoded = json_encode($searchItems);

        pageJS(<<<JS
            window.getSearchItems = () => JSON.parse(`{$searchItemsEncoded}`);
        JS);

        return zui::globalSearch
        (
            set::_id('globalSearch'),
            set::_class('w-44'),
            set::items($searchItems),
            set::searchHint($lang->searchAB),
            set::popPlacement('top-start'),
            set::popWidth(240),
            set::searchBox(array('circle' => true, 'prefixClass' => 'text-primary opacity-100', 'className' => 'size-md')),
            set::getSearchType(jsRaw('window.getGlobalSearchType')),
            set::onSearch(jsRaw('window.handleGlobalSearch')),
            inherit($this)
        );
    }
}
