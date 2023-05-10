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

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function divider(): wg
    {
        return div(setClass('divider w-px h-6 mx-2'));
    }

    private function buildBtns(array|null $items): array|null
    {
        if(empty($items)) return null;

        $btns = array();
        foreach ($items as $item) $btns[] = btn(set($item), setClass('ghost text-white'));
        return $btns;
    }

    protected function build(): wg
    {
        $prefix    = $this->prop('prefix');
        $main      = $this->prop('main');
        $suffix    = $this->prop('suffix');

        $dropdowns = $this->block('dropdowns');
        $mainBtns = $this->buildBtns($main);
        if(!empty($dropdowns)) $mainBtns = array_merge($mainBtns, $dropdowns);
        $prefixBtns = $this->buildBtns($prefix);
        $suffixBtns = $this->buildBtns($suffix);

        return div
        (
            setClass('float-toolbar inline-flex rounded p-1.5 items-center'),
            $prefixBtns,
            empty($prefixBtns) ? null : $this->divider(),
            $mainBtns,
            empty($suffixBtns) ? null : $this->divider(),
            $suffixBtns,
        );
    }
}
