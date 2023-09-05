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

    protected static array $defineProps = array(
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
        $param  = $tabPane->prop('param');
        $prefix = $tabPane->block('prefix');
        $suffix = $tabPane->block('suffix');

        return li
        (
            setClass('nav-item'),
            a
            (
                set('data-toggle', 'tab'),
                set('data-param', $param),
                setClass('font-medium', $active ? 'active' : null),
                set::href("#$key"),
                $prefix,
                span($title),
                $suffix,
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
        $collapse   = $this->prop('collapse');

        return ul
        (
            setClass('nav nav-tabs gap-x-5', $collapse ? 'relative' : null),
            $isVertical ? setClass('nav-stacked') : null,
            $titleViews,
            $this->buildCollapseBtn(),
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

    private function buildCollapseBtn(): ?wg
    {
        $collapse = $this->prop('collapse');
        if(!$collapse) return null;

        return collapseBtn
        (
            setClass('tabs-collapse-btn'),
            set::target('.tab-content'),
            set::parent('.tabs')
        );
    }

    protected function build(): wg
    {
        $isVertical = $this->prop('direction') === 'v';

        $this->filterChildren();

        $titleViews   = array();
        $contentViews = array();
        foreach($this->tabPanes as $tabPane)
        {
            $titleViews[] = $this->buildTitleView($tabPane);

            $divider = $tabPane->block('divider');
            if($divider) $titleViews[] = div(set::className('divider'));

            $contentViews[] = $tabPane;
        }

        return div
        (
            setClass('tabs', $isVertical ? 'flex' : null),
            set($this->getRestProps()),

            $this->buildTabHeader($titleViews),
            $this->buildTabBody($contentViews),
            $this->children
        );
    }
}
