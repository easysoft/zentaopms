<?php
namespace zin;

class floatToolbar extends wg
{
    protected static $defineProps = array(
        'prefix?:array',
        'main?:array',
        'suffix?:array'
    );

    protected static $defineBlocks = array(
        'dropdowns' => array()
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function divider()
    {
        return div(setClass('divider w-px h-6 mx-2'));
    }

    private function buildBtns(array $items)
    {
        $btns = array();
        if(empty($items)) return $btns;

        foreach ($items as $item)
        {
            $btns[] = btn(set($item), setClass('ghost text-white'));
        }
        return $btns;
    }

    protected function build()
    {
        $prefix    = $this->prop('prefix');
        $main      = $this->prop('main');
        $suffix    = $this->prop('suffix');

        $dropdowns = $this->block('dropdowns');
        $mainBtns = $this->buildBtns($main);
        if(!empty($dropdowns))
        {
            foreach($dropdowns as $key => $dropdown)
            {
                array_splice($mainBtns, $key, 0, array($dropdown));
            }
        }

        return div
        (
            setClass('float-toolbar inline-flex rounded p-1.5 items-center'),
            $this->buildBtns($prefix),
            $this->divider(),
            $mainBtns,
            $this->divider(),
            $this->buildBtns($suffix),
        );
    }
}
