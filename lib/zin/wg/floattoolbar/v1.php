<?php
namespace zin;

class floatToolbar extends wg
{
    protected static $defineProps = array(
        'prefix?:array',
        'main?:array',
        'suffix?:array',
        'object?:object'
    );

    protected static $defineBlocks = array(
        'prefix' => array(),
        'main'   => array(),
        'suffix' => array(),
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
        foreach ($items as &$item)
        {
            if(!$item) continue;

            if(!empty($item['url']))      $item['url']      = preg_replace_callback('/\{(\w+)\}/', array($this, 'getObjectValue'), $item['url']);
            if(!empty($item['data-url'])) $item['data-url'] = preg_replace_callback('/\{(\w+)\}/', array($this, 'getObjectValue'), $item['data-url']);

            $btns[] = btn(set($item), setClass('ghost text-white'));
        }
        return $btns;
    }

    public function getObjectValue($matches)
    {
        if(!isset($this->object)) $this->object = $this->prop('object');

        return zget($this->object, $matches[1]);
    }

    private function mergeBtns(array|null $btns, array|wg|null $block): array|wg|null
    {
        if(empty($btns) && empty($block)) return null;
        if(empty($block)) return $btns;

        foreach($block[0]->children() as $blockBtn) $blockBtn->add(setClass('ghost', 'text-white'));
        if(empty($btns)) return $block;

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
            empty($mainBtns) ? null : $this->buildDivider($suffixBtns),
            $suffixBtns,
        );
    }
}
