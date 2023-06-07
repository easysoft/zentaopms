<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'tabpane' . DS . 'v1.php';

class tabs extends wg
{
    private $children = array();

    /**
     * @var tabPane[]
     */
    private $tabPanes = array();

    protected static $defineProps = array(
        /* Tabs direction: h - horizontal, v - vertical */
        'direction?:string="h"',
        'collapse?: bool=false',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildTitleView(tabPane $tabPane): wg
    {
        $key    = $tabPane->prop('key');
        $title  = $tabPane->prop('title');
        $active = $tabPane->prop('active');
        $prefix = $tabPane->block('prefix');
        $suffix = $tabPane->block('suffix');

        return li
        (
            setClass('nav-item', $active ? 'active' : null),
            a
            (
                set('data-toggle', 'tab'),
                setClass('font-medium'),
                set::href("#$key"),
                $prefix,
                span($title),
                $suffix
            )
        );
    }

    /**
     * @param wg[] $titleViews
     * @return wg
     */
    protected function buildTabHeader(array $titleViews): wg
    {
        $isVertical = $this->prop('direction') === 'v';
        return ul
        (
            setClass('nav nav-tabs gap-x-5'),
            $isVertical ? setClass('nav-stacked') : null,
            $titleViews
        );
    }

    /**
     * @param wg[] $titleViews
     * @return wg
     */
    protected function buildTabBody(array $contentViews): wg
    {
        return div
        (
            setClass('tab-content'),
            $contentViews
        );
    }

    private function filterChildren()
    {
        foreach ($this->children() as $child)
        {
            if($child instanceof tabPane)
            {
                $this->tabPanes[] = $child;
                continue;
            }

            $this->children[] = $child;
        }
    }

    private function buildCollapseBtn()
    {
        $collapse = $this->prop('collapse');
        if(!$collapse) return null;

        return btn
        (
            setClass('btn-link'),
            setStyle(array(
                'position' => 'absolute',
                'right' => '24px',
                'top' => '16px'
            )),
            set::icon('angle-down'),
            on::click
            (
                <<<FUNC
                    const btn = event.target;
                    const icon = btn.querySelector('.icon');
                    const tabs = btn.closest('.tabs');
                    const tabContent = tabs.querySelector('.tab-content');
                    if(tabContent) tabContent.classList.toggle('hidden');
                    icon.classList.toggle('icon-angle-down');
                    icon.classList.toggle('icon-angle-top');
                FUNC
            )
        );
    }

    protected function build(): wg
    {
        $isVertical = $this->prop('direction') === 'v';
        $collapse   = $this->prop('collapse');

        $this->filterChildren();

        $titleViews   = array();
        $contentViews = array();
        foreach($this->tabPanes as $tabPane)
        {
            $titleViews[]   = $this->buildTitleView($tabPane);
            $contentViews[] = $tabPane;
        }

        return div
        (
            setClass('tabs', $isVertical ? 'flex' : null, $collapse ? 'relative' : null),
            set($this->props->skip(array_keys(static::getDefinedProps()))),

            $this->buildTabHeader($titleViews),
            $this->buildTabBody($contentViews),
            $this->buildCollapseBtn(),
            $this->children
        );
    }
}
