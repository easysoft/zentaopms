<?php
declare(strict_types=1);
namespace zin;

class globalSearch extends wg
{
    protected static array $defineProps = array(
        'commonSearchText?: string',
        'commonSearchUrl: string',
        'searchItems: array',
        'searchFunc: callable'
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): array
    {
        global $config, $lang;

        jsVar('searchObjectList', array_keys($lang->searchObjects));

        if($config->systemMode == 'light') unset($lang->searchObjects['program']);
        unset($lang->searchObjects['all']);

        $searchItems = array();
        foreach($lang->searchObjects as $key => $module)
        {
            $searchItems[] = array('key' => $key, 'text' => $module);
        }

        $this->setDefaultProps(array(
            'commonSearchText' => $lang->searchAB,
            'commonSearchKey' => 'all',
            'searchItems' => $searchItems,
            'searchFunc' => jsRaw('window.globalSearch'),
        ));

        $input = inputGroup
        (
            set($this->getRestProps()),
            input
            (
                setID('globalSearchInput'),
                set::placeholder($lang->index->pleaseInput),
            ),
            btn
            (
                set::icon('search'),
                set::type('primary'),
            ),
        );
        $input->setProp('data-zin-id', $input->gid);
        $props = array_merge
        (
            $this->props->pick(array('commonSearchText', 'commonSearchKey', 'searchItems', 'searchFunc')),
            array('_to' => "[data-zin-id='{$input->gid}']")
        );
        return array(
            $input,
            zui::globalSearch(set($props))
        );
    }
}
