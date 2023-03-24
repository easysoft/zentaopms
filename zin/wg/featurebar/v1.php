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

        if(!isset($lang->$currentModule->featureBar[$currentMethod])) return NULL;
        $rawItems = $lang->$currentModule->featureBar[$currentMethod];
        if(!is_array($rawItems)) return NULL;

        $current  = $this->prop('current', data('browseType', ''));
        $recTotal = data('recTotal');
        $items    = array();
        $link     = $this->prop('link');

        if(empty($link))
        {
            $linkParams = $this->prop('linkParams');
            if(empty($linkParams)) $linkParams = 'browseType={key}&orderBy=' . data('orderBy', '');
            $link = createLink($currentModule, $currentMethod, $linkParams);
        }

        foreach($rawItems as $key => $text)
        {
            $isActive = $key == $current;
            $items[] = array
            (
                'text'   => $text,
                'active' => $isActive,
                'url'    => str_replace('{key}', $key, $link),
                'badge'  => $isActive && !empty($recTotal) ? array('text' => $recTotal, 'class' => 'size-sm circle white') : NULL,
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
            setClass('toolbar-nav'),
            $this->block('leading'),
            $this->buildNav(),
            $this->block('trailing')
        );
    }
}
