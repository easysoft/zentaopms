<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class featureBar extends wg
{
    static $defineProps = 'items?:array, current?:string, link?:string, linkParams?:string';

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
        if(!is_array($rawItems)) return NULL;

        $current      = $this->prop('current', data('browseType', ''));
        $recTotal     = data('recTotal');
        $items        = array();
        $link         = $this->prop('link');
        $currentStory = $this->prop('currentStory', data('storyBrowseType') ?? '');

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

            if($item->name == 'more' && !empty($lang->product->moreSelects))
            {

                $subItems = array();
                $callback = $this->prop('moreMenuLinkCallback');
                $callback = isset($callback[0]) ? $callback[0] : null;

                foreach($lang->product->moreSelects as $key => $text)
                {
                    $subItems[] = array
                    (
                        'text'   => $text,
                        'active' => $key == $currentStory,
                        'url'    => ($callback instanceof \Closure) ? $callback($key, $text) : createLink($app->rawModule, $app->rawMethod),
                        'props'  => ['data-id' => $key, 'data-load' => 'table']
                    );
                }

                $items[] = array
                (
                    'text'   => $item->text,
                    'active' => $isActive,
                    'url'    => str_replace('{key}', $item->name, $link),
                    'badge'  => $isActive && !empty($recTotal) ? array('text' => $recTotal, 'class' => 'size-sm circle white') : NULL,
                    'type'   => 'dropdown',
                    'items'  => $subItems,
                    'props'  => ['data-id' => $item->name, 'data-load' => 'table']
                );

                continue;
            }


            $items[] = array
            (
                'text'   => $item->text,
                'active' => $isActive,
                'url'    => str_replace('{key}', $item->name, $link),
                'badge'  => $isActive && !empty($recTotal) ? array('text' => $recTotal, 'class' => 'size-sm circle white') : NULL,
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
