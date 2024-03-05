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
        'headerClass?:string=""'
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .tabs-header {position: relative; z-index: 1}
        .tabs-nav>.nav-item>a {font-size: 14px; font-weight: 800; padding: 0; padding-right: 0; color: var(--color-gray-800);}
        .tabs-nav>.nav-item>a:after {border-width: 0;}
        .tabs-nav>.nav-item>a:before {background: none;}
        .tabs-nav>.nav-item>a.active {color: var(--color-primary-500);}
        .tabs-nav>.nav-item>a.active:after {border-bottom-color: var(--color-primary-500) !important; border-bottom-width: 2px;}
        .tabs-nav>.divider {height: 20px; border-right: 1px solid #DDD;}
        .tabs-collapse-btn {position: absolute; top: 0; right: 0; width: 24px; height: 24px;}
        .tab-content {padding-top: 10px;}
        CSS;
    }

    protected function buildTitleView(tabPane $tabPane): node
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
                $suffix
            )
        );
    }

    /**
     * @param array $titleViews
     * @return node
     */
    protected function buildTabHeader(array $titleViews): node
    {
        $isVertical  = $this->prop('direction') === 'v';
        $collapse    = $this->prop('collapse');
        $headerClass = $this->prop('headerClass');

        return div
        (
            setClass('tabs-header'),
            ul
            (
                setClass('tabs-nav nav nav-tabs gap-x-5', $collapse ? 'relative' : null, $headerClass ?: null),
                $isVertical ? setClass('nav-stacked') : null,
                $titleViews
            ),
            $this->buildCollapseBtn()
        );
    }

    /**
     * @param array $titleViews
     * @return node
     */
    protected function buildTabBody(array $contentViews): node
    {
        return div
        (
            setClass('tab-content'),
            $contentViews
        );
    }

    private function filterChildren()
    {
        $hasActived = false;
        foreach ($this->children() as $child)
        {
            if($child instanceof tabPane)
            {
                $this->tabPanes[] = $child;
                if($child->prop('active')) $hasActived = true;
                continue;
            }

            $this->children[] = $child;
        }
        if(!$hasActived && !empty($this->tabPanes)) $this->tabPanes[0]->setProp('active', true);
    }

    private function buildCollapseBtn(): ?node
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

    protected function build()
    {
        $isVertical = $this->prop('direction') === 'v';

        $this->filterChildren();

        $titleViews = array();
        $tabPanes   = array();
        foreach($this->tabPanes as $tabPane)
        {
            $titleViews[] = $this->buildTitleView($tabPane);
            if($tabPane->prop('divider')) $titleViews[] = div(set::className('divider'));

            $tabPanes[] = $tabPane;
        }

        return div
        (
            setClass('tabs', $isVertical ? 'flex' : null),
            set($this->getRestProps()),

            $this->buildTabHeader($titleViews),
            $this->buildTabBody($tabPanes),
            $this->children
        );
    }
}
