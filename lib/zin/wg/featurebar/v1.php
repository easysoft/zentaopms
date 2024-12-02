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
        'linkParams?:string=""',
        'module?:string',
        'method?:string',
        'load?: string="table"',
        'loadID?: string',
        'app?: string=""',
        'param?: int=0',
        'searchModule?: string=""',
        'labelCount?: int=-1'
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

        if(!empty($items)) return array_values($items);

        global $app, $lang;
        $currentModule = $this->prop('module', $app->rawModule);
        $currentMethod = $this->prop('method', $app->rawMethod);

        \common::sortFeatureMenu($currentModule, $currentMethod);
        $rawItems = \customModel::getFeatureMenu($currentModule, $currentMethod);
        if(!is_array($rawItems)) return null;

        $current      = $this->prop('current', data('browseType'));
        $pager        = data('pager');
        $recTotal     = $pager ? $pager->recTotal : data('recTotal');
        $recTotal     = $this->prop('labelCount') >= 0 ? $this->prop('labelCount') : $recTotal;
        $items        = array();
        $loadID       = $this->prop('loadID');
        $load         = $this->prop('load');
        $tab          = $this->prop('app');
        $param        = $this->prop('param') ? $this->prop('param') : data('param');
        $searchModule = $this->prop('searchModule');
        $commonLink   = $this->prop('link');
        $itemLink     = $this->prop('itemLink');

        data('activeFeature', $current);

        if(empty($commonLink))   $commonLink = createLink($app->rawModule, $app->rawMethod, $this->prop('linkParams'));
        if(empty($searchModule)) $searchModule = data("config.{$currentModule}.search.module") ? data("config.{$currentModule}.search.module") : $currentModule;

        foreach($rawItems as $item)
        {
            if(isset($item->hidden)) continue;
            if(isset($item->type) && $item->type === 'divider')
            {
                $items[] = array('type' => 'divider');
                continue;
            }

            $link     = ($itemLink && isset($itemLink[$item->name])) ? $itemLink[$item->name] : $commonLink;
            $isActive = $item->name == $current;

            $moreSelects = array();
            if($item->name == 'more'  && !empty($lang->$currentModule->moreSelects))   $moreSelects = $lang->$currentModule->moreSelects;
            if(isset($lang->$currentModule->moreSelects[$currentMethod][$item->name])) $moreSelects = $lang->$currentModule->moreSelects[$currentMethod][$item->name];
            if($item->name == 'QUERY' && !empty($lang->custom->queryList))             $moreSelects = $lang->custom->queryList;
            if(!empty($moreSelects))
            {
                $activeText = $item->text;

                $subItems = array();
                $callback = $this->prop(in_array($item->name, array('more', 'status')) ? 'moreMenuLinkCallback' : 'queryMenuLinkCallback');
                $callback = isset($callback[0]) ? $callback[0] : null;

                foreach($moreSelects as $key => $text)
                {
                    $subItem = array();
                    $subItem['text']   = $text;
                    $subItem['active'] = $item->name == 'QUERY' ? $key == $param : $key == $current;
                    $subItem['url']    = ($callback instanceof \Closure) ? $callback($key, $text) : str_replace('{key}', (string)$key, $link);
                    $subItem['attrs']  = ['data-id' => $key, 'data-load' => $load, 'data-target' => $loadID, 'data-app' => $tab, 'data-success' => "() => zui.updateSearchForm('$searchModule')"];

                    if($item->name == 'QUERY')
                    {
                        $closeLink = createLink('search', 'ajaxRemoveMenu', "queryID={$key}");
                        $loadUrl   = $subItem['url'] . '#featureBar';

                        $subItem['className']    = 'flex-auto';
                        $subItem['rootClass']    = 'row gap-0';
                        $subItem['rootChildren'] = array(jsRaw("zui.h('a', {className: 'ajax-submit', 'data-url': '{$closeLink}', 'data-load': '{$loadUrl}'}, zui.h('span', {className: 'close'}))"));
                    }

                    $subItems[] = $subItem;

                    if($key === $current || ($current == 'bysearch' && $key === $param))
                    {
                        $isActive   = true;
                        $activeText = $text;
                    }
                }

                $items[] = array
                (
                    'text'      => $activeText,
                    'active'    => $isActive,
                    'type'      => 'dropdown',
                    'caret'     => 'down',
                    'items'     => $subItems,
                    'badge'     => $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm canvas ring-0 rounded-md') : null,
                    'props'     => array('data-id' => $item->name, 'title' => $activeText),
                    'textClass' => 'text-ellipsis max-w-32'
                );

                continue;
            }

            $items[] = array
            (
                'text'      => $item->text,
                'active'    => $isActive,
                'url'       => str_replace('{key}', strval($item->name), $link),
                'badge'     => $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm canvas ring-0 rounded-md') : null,
                'props'     => array('data-id' => $item->name, 'data-load' => $load, 'data-target' => $loadID, 'data-app' => $tab, 'title' => $item->text),
                'textClass' => 'text-ellipsis max-w-32'
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
            set::compact(),
            set::className('nav-feature'),
            set::items($this->getItems()),
            divorce($this->children())
        );
    }

    protected function build()
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
