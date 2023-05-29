<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class featureBar extends wg
{
    static $defineProps = array(
        'items?:array',
        'current?:string',
        'link?:string',
        'current?:string',
        'linkParams?:string'
    );

    static $defineBlocks = array
    (
        'nav' => array('map' => 'nav'),
        'leading' => array(),
        'trailing' => array(),
    );

    protected function getItems()
    {
        $items = $this->prop('items');
        if(!empty($items)) return $items;

        global $app, $lang;
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        \common::sortFeatureMenu($currentModule, $currentMethod);

        $rawItems = \customModel::getFeatureMenu($app->rawModule, $app->rawMethod);
        if(!is_array($rawItems)) return null;

        $current      = $this->prop('current', data('browseType'));
        $recTotal     = data('recTotal');
        $items        = array();
        $link         = $this->prop('link');

        data('activeFeature', $current);

        if(empty($link))
        {
            $linkParams = $this->prop('linkParams');
            if(empty($linkParams)) $linkParams = 'browseType={key}&orderBy=' . data('orderBy') ?? '';
            $link = createLink($currentModule, $currentMethod, $linkParams);
        }

        foreach($rawItems as $item)
        {
            if(isset($item->hidden)) continue;

            $isActive = $item->name == $current;

            $moreSelects = array();
            if($item->name == 'more' && !empty($lang->$currentModule->moreSelects))    $moreSelects = $lang->$currentModule->moreSelects;
            if(isset($lang->$currentModule->moreSelects[$currentMethod][$item->name])) $moreSelects = $lang->$currentModule->moreSelects[$currentMethod][$item->name];
            if(!empty($moreSelects))
            {
                $activeText = $item->text;

                $subItems = array();
                $callback = $this->prop('moreMenuLinkCallback');
                $callback = isset($callback[0]) ? $callback[0] : null;

                foreach($moreSelects as $key => $text)
                {
                    $subItems[] = array
                    (
                        'text'   => $text,
                        'active' => $key == $current,
                        'url'    => ($callback instanceof \Closure) ? $callback($key, $text) : str_replace('{key}', $key, $link),
                        'attrs'  => ['data-id' => $key, 'data-load' => 'table']
                    );

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
                    'props'  => array('data-id' => $item->name)
                );

                continue;
            }


            $items[] = array
            (
                'text'   => $item->text,
                'active' => $isActive,
                'url'    => str_replace('{key}', $item->name, $link),
                'badge'  => $isActive && !empty($recTotal) ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null,
                'props'  => ['data-id' => $item->name, 'data-load' => 'table']
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
            set::class('nav-feature'),
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
