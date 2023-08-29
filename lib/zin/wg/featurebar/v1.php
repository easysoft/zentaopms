<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class featureBar extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'current?:string',
        'link?:string',
        'current?:string',
        'linkParams?:string=""',
        'module?:string',
        'method?:string',
        'load?: string="table"',
        'loadID?: string'
    );

    protected static array $defineBlocks = array
    (
        'nav'      => array('map' => 'nav'),
        'leading'  => array(),
        'trailing' => array()
    );

    protected function getItems()
    {
        $items = $this->prop('items');
        if(!empty($items)) return $items;

        global $app, $lang;
        $currentModule = $this->prop('module', $app->rawModule);
        $currentMethod = $this->prop('method', $app->rawMethod);

        \common::sortFeatureMenu($currentModule, $currentMethod);

        $rawItems = \customModel::getFeatureMenu($currentModule, $currentMethod);
        if(!is_array($rawItems)) return null;

        $current  = $this->prop('current', data('browseType'));
        $pager    = data('pager');
        $recTotal = $pager ? $pager->recTotal : data('recTotal');
        $items    = array();
        $link     = $this->prop('link');
        $loadID   = $this->prop('loadID');
        $load     = $this->prop('load');

        data('activeFeature', $current);

        if(empty($link)) $link = createLink($app->rawModule, $app->rawMethod, $this->prop('linkParams'));

        foreach($rawItems as $item)
        {
            if(isset($item->hidden)) continue;

            $isActive = $item->name == $current;

            $moreSelects = array();
            if($item->name == 'more'  && !empty($lang->$currentModule->moreSelects))   $moreSelects = $lang->$currentModule->moreSelects;
            if(isset($lang->$currentModule->moreSelects[$currentMethod][$item->name])) $moreSelects = $lang->$currentModule->moreSelects[$currentMethod][$item->name];
            if($item->name == 'QUERY' && !empty($lang->custom->queryList))             $moreSelects = $lang->custom->queryList;
            if(!empty($moreSelects))
            {
                $activeText = $item->text;

                $subItems = array();
                $callback = $this->prop($item->name == 'more' ? 'moreMenuLinkCallback' : 'queryMenuLinkCallback');
                $callback = isset($callback[0]) ? $callback[0] : null;

                foreach($moreSelects as $key => $text)
                {
                    $subItem = array();
                    $subItem['text']   = $text;
                    $subItem['active'] = $key == $current;
                    $subItem['url']    = ($callback instanceof \Closure) ? $callback($key, $text) : str_replace('{key}', (string)$key, $link);
                    $subItem['attrs']  = ['data-id' => $key, 'data-load' => $load, 'data-target' => $loadID];

                    if($item->name == 'QUERY')
                    {
                        $closeLink = createLink('search', 'ajaxRemoveMenu', "queryID={$key}");
                        $loadUrl   = $subItem['url'] . '#featureBar';

                        $subItem['className']    = 'flex-auto';
                        $subItem['rootClass']    = 'row gap-0';
                        $subItem['rootChildren'] = array(jsRaw("zui.h('a', {className: 'ajax-submit', 'data-url': '{$closeLink}', 'data-load': '{$loadUrl}'}, zui.h('span', {className: 'close'}))"));
                    }

                    $subItems[] = $subItem;

                    if($key === $current)
                    {
                        $isActive   = true;
                        $activeText = $text;
                    }
                }

                $items[] = array
                (
                    'text'   => $activeText,
                    'active' => $isActive,
                    'type'   => 'dropdown',
                    'caret'  => 'down',
                    'items'  => $subItems,
                    'badge'  => $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null,
                    'props'  => array('data-id' => $item->name)
                );

                continue;
            }

            $items[] = array
            (
                'text'   => $item->text,
                'active' => $isActive,
                'url'    => str_replace('{key}', $item->name, $link),
                'badge'  => $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null,
                'props'  => array('data-id' => $item->name, 'data-load' => $load, 'data-target' => $loadID)
            );
        }

        return $items;
    }

    protected function buildNav()
    {
        $nav = $this->block('nav');
        if(!empty($nav) && $nav[0] instanceof nav) return $nav;
        return new nav
        (
            set::className('nav-feature'),
            set::items($this->getItems()),
            divorce($this->children())
        );
    }

    protected function build(): wg
    {
        return div
        (
            set::id('featureBar'),
            $this->block('leading'),
            $this->buildNav(),
            $this->block('trailing')
        );
    }
}
