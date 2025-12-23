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
        'labelCount?: int=-1',
        'isModal?: bool=false'
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
        $isModal      = $this->prop('isModal');

        data('activeFeature', $current);

        if(empty($commonLink))   $commonLink   = createLink($app->rawModule, $app->rawMethod, $this->prop('linkParams'));
        if(empty($searchModule)) $searchModule = data("config.{$currentModule}.search.module");
        if(empty($searchModule)) $searchModule = $currentModule;

        foreach($rawItems as $rawItem)
        {
            if(isset($rawItem->hidden)) continue;
            if(isset($rawItem->name) && $isModal && $rawItem->name == 'QUERY') continue;
            if(isset($rawItem->type) && $rawItem->type === 'divider')
            {
                $items[] = array('type' => 'divider');
                continue;
            }

            $link     = ($itemLink && isset($itemLink[$rawItem->name])) ? $itemLink[$rawItem->name] : $commonLink;
            $isActive = $rawItem->name == $current;

            $moreSelects = array();
            if($rawItem->name == 'more'  && !empty($lang->$currentModule->moreSelects))   $moreSelects = $lang->$currentModule->moreSelects;
            if(isset($lang->$currentModule->moreSelects[$currentMethod][$rawItem->name])) $moreSelects = $lang->$currentModule->moreSelects[$currentMethod][$rawItem->name];
            if($rawItem->name == 'QUERY' && !empty($lang->custom->queryList))             $moreSelects = $lang->custom->queryList;
            if(!empty($moreSelects))
            {
                $activeText = $rawItem->text;

                $subItems = array();
                $callback = $this->prop(in_array($rawItem->name, array('more', 'status')) ? 'moreMenuLinkCallback' : 'queryMenuLinkCallback');
                $callback = isset($callback[0]) ? $callback[0] : null;

                foreach($moreSelects as $key => $text)
                {
                    $url = ($callback instanceof \Closure) ? $callback($key, $text) : str_replace('{key}', (string)$key, $link);
                    $subItem = array();
                    $subItem['text']   = $text;
                    $subItem['active'] = $rawItem->name == 'QUERY' ? $key == $param : $key == $current;
                    $subItem['attrs']  = ['data-id' => $key, 'data-load' => $load, 'data-target' => $loadID, 'data-app' => $tab, 'data-success' => "() => zui.updateSearchForm('$searchModule')"];
                    $subItem['url']    = $isModal ? '#featureBar' : $url;

                    if($isModal) $subItem['onClick'] = jsRaw("() => loadModal('{$url}')");

                    if($rawItem->name == 'QUERY')
                    {
                        $closeLink = createLink('search', 'ajaxRemoveMenu', "queryID={$key}");
                        $loadUrl   = $subItem['url'] . '#featureBar';

                        $subItem['innerClass'] = 'flex-auto';
                        $subItem['className']  = 'row gap-0';
                        $subItem['children']   = array(jsRaw("zui.h('a', {className: 'ajax-submit', 'data-url': '{$closeLink}', 'data-load': '{$loadUrl}'}, zui.h('span', {className: 'close'}))"));
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
                    'props'     => array('data-id' => $rawItem->name, 'title' => $activeText),
                    'textClass' => 'text-ellipsis max-w-32'
                );

                continue;
            }

            $url = str_replace('{key}', strval($rawItem->name), $link);
            $item = array
            (
                'text'      => $rawItem->text,
                'active'    => $isActive,
                'badge'     => $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm canvas ring-0 rounded-md') : null,
                'props'     => array('data-id' => $rawItem->name, 'data-load' => $load, 'data-target' => $loadID, 'data-app' => $tab, 'title' => $rawItem->text),
                'textClass' => 'text-ellipsis max-w-32',
                'url'       => $isModal ? '#featureBar' : $url
            );

            if($isModal) $item['onClick'] = "loadModal('{$url}')";
            $items[] = $item;
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
