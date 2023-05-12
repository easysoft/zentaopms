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
        'prefix' => array(),
        'main'   => array(),
        'suffix' => array()
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildDivider(wg|array|null $wg): wg|null
    {
        if(empty($wg)) return null;

        return div(setClass('divider w-px h-6 mx-2'));
    }

    private function buildBtns(array|null $items): array|null
    {
        if(empty($items)) return null;

        $btns = array();
        foreach ($items as $item) $btns[] = btn(set($item), setClass('ghost text-white'));
        return $btns;
    }

    private function mergeBtns(array|null $btns, array|wg|null $block): array|wg|null
    {
        if(empty($btns) && empty($block)) return null;
        if(empty($btns)) return $block;
        if(empty($block)) return $btns;

        if(!is_array($block)) $block = array($block);
        return array_merge($btns, $block);
    }

    protected function build(): wg
    {
        $prefixBtns = $this->buildBtns($this->prop('prefix'));
        $mainBtns   = $this->buildBtns($this->prop('main'));
        $suffixBtns = $this->buildBtns($this->prop('suffix'));

        $prefixBlock = $this->block('prefix');
        $mainBlock   = $this->block('main');
        $suffixBlock = $this->block('suffix');

        $prefixBtns = $this->mergeBtns($prefixBtns, $prefixBlock);
        $mainBtns   = $this->mergeBtns($mainBtns, $mainBlock);
        $suffixBtns = $this->mergeBtns($suffixBtns, $suffixBlock);

        return div
        (
            setClass('float-toolbar inline-flex rounded p-1.5 items-center'),
            $prefixBtns,
            $this->buildDivider($prefixBtns),
            $mainBtns,
            $this->buildDivider($suffixBtns),
            $suffixBtns,
        );
    }
}
