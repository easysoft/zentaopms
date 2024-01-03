<?php
namespace zin;

$this->app->loadConfig('message');

if(trim($config->visions, ',') == 'lite')
{
    $version     = $config->liteVersion;
    $versionName = $lang->liteName . $config->liteVersion;
}
else
{
    $version     = $config->version;
    $versionName = ($config->inQuickon ? 'DevOps' : '') . $lang->pmsName . $config->version;
}

$scoreNotice = '';
if($config->vision != 'lite') $scoreNotice = $this->loadModel('score')->getNotice();

jsVar('scoreNotice', $scoreNotice);
jsVar('edition',     $config->edition);
jsVar('vision',      $config->vision);
jsVar('navGroup',    $lang->navGroup);
jsVar('oldPages',    $config->index->oldPages);
jsVar('appsItems',   $appsItems);
jsVar('defaultOpen', !empty($open) ? $open : '');
jsVar('manualText',  $lang->manual);
jsVar('manualUrl',   ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme']);
jsVar('lang',        array_merge(array('search' => $lang->index->search, 'searchAB' => $lang->searchAB), (array)$lang->index->dock));
jsVar('browserMessage', $browserMessage);
jsVar('pollTime',    (!empty($config->message->browser->turnon) && isset($config->message->browser->pollTime)) ? $config->message->browser->pollTime : 600);
jsVar('turnon',      empty($config->message->browser->turnon) ? 0 : 1);
jsVar('runnable',    $this->loadModel('cron')->runnable());
jsVar('showFeatures', $showFeatures);

set::zui(true);
set::bodyClass($this->cookie->hideMenu ? 'hide-menu' : 'show-menu');

/* The menu fixed on left */
div
(
    setID('menu'),
    div
    (
        setID('menuNav'),
        ul(setClass('nav'), setID('menuMainNav')),
        ul
        (
            setClass('nav'),
            setID('menuMoreNav'),
            li(setClass('divider')),
            li
            (
                a
                (
                    set::title($lang->more),
                    set::href('#menuMoreList'),
                    icon('more-circle'),
                    span(setClass('text'), $lang->more),
                    toggle::dropdown(array('placement' => 'right-end', 'offset' => 12))
                ),
                ul(setClass('dropdown-menu nav'), setID('menuMoreList'))
            )
        )
    ),
    div
    (
        setID('menuFooter'),
        ul
        (
            setClass('nav'),
            li
            (
                setID('menuToggleMenu'),
                a
                (
                    setClass('menu-toggle justify-center cursor-pointer'),
                    toggle::tooltip(array('placement' => 'right', 'collapse-text' => $lang->collapseMenu, 'unfold-text' => $lang->unfoldMenu)),
                    icon('menu-collapse icon-sm')
                )
            )
        )
    )
);

/* The div used to place the page iframes.  */
div(setID('apps'));

/* The toolbar docked on the bottom. */
div
(
    setID('appsBar'),
    ul
    (
        setID('appTabs'),
        setClass('nav')
    ),
    toolbar
    (
        setID('appsToolbar'),
        hasPriv('search', 'index') ? globalSearch() : null,
        item
        (
            setClass('ghost btn-zentao'),
            set::icon('zentao text-2xl'),
            set::url($lang->website),
            set::target('_blank'),
            set::hint($version),
            set::text($versionName)
        )
    )
);

render('pagebase');
