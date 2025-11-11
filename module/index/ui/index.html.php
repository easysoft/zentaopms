<?php
namespace zin;

$this->app->loadConfig('message');
h::css("
#versionTitle {background-image: url('{$config->webRoot}theme/default/images/main/version-upgrade.svg');}
.icon-version {width: 20px; height: 24px; margin: -4px 3px 0px 0px; background-image: url('{$config->webRoot}theme/default/images/main/version-new.svg');}
.icon-version:before {content:'';}
");

$upgradeBtn = null;
if(trim($config->visions, ',') == 'lite')
{
    $version     = $config->liteVersion;
    $versionName = $lang->liteName . $config->liteVersion;
}
else
{
    $version     = $config->version;
    $versionName = $lang->pmsName . $config->version;
    $upgradeBtn  = $config->systemMode != 'PLM' ? btn
    (
        setID('bizLink'),
        on::click()->do(<<<'JS'
        $('#upgradeContent').toggle();
        $('#bizLink').toggleClass('active', $('#upgradeContent').prop('style') && $('#upgradeContent').prop('style').display != 'none');
        event.preventDefault();
        JS),
        setClass('ghost'),
        set::target('_blank'),
        span(setClass('upgrade'), $lang->index->upgrade),
        icon('up-circle', setClass('text-danger'))
    ) : null;
}

if(!empty($latestVersionList))
{
    $lastVersionList = (array)$latestVersionList;
    $lastVersion     = end($lastVersionList);
    $versionItems    = array();
    foreach($latestVersionList as $versionNumber => $versionInfo)
    {
        if(!isset($versionInfo['name'])) continue;
        $versionItems[] = div
        (
            setClass('version-list py-2'),
            div
            (
                setClass('version-name flex h-6 items-center'),
                icon('version', setClass('version-upgrade')),
                h5($versionInfo['name'])
            ),
            div
            (
                setClass('version-detail text-gray my-2'),
                $versionInfo['explain']
            ),
            div
            (
                setClass('version-footer flex justify-between'),
                btn
                (
                    setData(array('toggle' => 'modal')),
                    setClass('ghost'),
                    set::url(inLink('changeLog', 'version=' . $versionNumber)),
                    $lang->index->log
                ),
                btn
                (
                    setClass('primary upgrade-now'),
                    set::url($versionInfo['link']),
                    set::target('_blank'),
                    $lang->index->upgradeNow
                )
            )
        );
        if($versionInfo['name'] != $lastVersion['name']) $versionItems[] = h::hr(setClass('version-hr'));
    }
    $upgradeContent = div
    (
        setClass('version-content'),
        $versionItems
    );
}
else
{
    $upgradeContent = div
    (
        setClass('table-empty-tip py-10 mt-8 center'),
        btn
        (
            setClass('secondary-outline bg-secondary-50'),
            set::url($lang->website),
            set::target('_blank'),
            set::text($lang->index->website . ': '. $lang->website)
        )
    );
    h::css("#upgradeContent {height: 262px;} latestVersionList {height: 200px;} ");
}

$scoreNotice = '';
if($config->vision != 'lite') $scoreNotice = $this->loadModel('score')->getNotice();

jsVar('scoreNotice', $scoreNotice);
jsVar('edition',     $config->edition);
jsVar('vision',      $config->vision);
jsVar('navGroup',    $lang->navGroup);
jsVar('appNotFound', $lang->appNotFound);
jsVar('oldPages',    $config->index->oldPages);
jsVar('allAppsItems', $allAppsItems);
jsVar('isTutorialMode', common::isTutorialMode());
jsVar('defaultOpen', !empty($open) ? $open : '');
jsVar('manualText',  $lang->manual);
jsVar('manualUrl',   ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme']);
jsVar('langData',     array_merge(array('search' => $lang->index->search, 'searchAB' => $lang->searchAB), (array)$lang->index->dock));
jsVar('browserMessage', $browserMessage);
jsVar('pollTime',    (!empty($config->message->browser->turnon) && !empty($config->message->browser->pollTime)) ? $config->message->browser->pollTime : 600);
jsVar('turnon',      empty($config->message->browser->turnon) ? 0 : 1);
jsVar('runnable',    $this->loadModel('cron')->runnable());
jsVar('showFeatures', $showFeatures);

set::zui(true);
set::bodyClass($this->cookie->hideMenu ? 'hide-menu' : 'show-menu');

h::jsVar('window.appsItems', $appsItems, setID('appsItemsData'));

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
                    setID('menuMoreBtn'),
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
                setClass('hint-right'),
                setData(array('collapse-text' => $lang->collapseMenu, 'hint' => $lang->unfoldMenu)),
                a
                (
                    setClass('menu-toggle justify-center cursor-pointer'),
                    icon('menu-arrow-left icon-sm')
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
    div(setID('visionSwitcher'), visionSwitcher(), setData('vision', $app->config->vision)),
    ul(setID('appTabs'), setClass('nav')),
    toolbar
    (
        setID('appsToolbar'),
        setClass('space-x-1'),
        hasPriv('search', 'index') ? globalSearch() : null,
        chatBtn(),
        item
        (
            setClass('ghost btn-zentao px-1'),
            set::icon('zentao text-2xl'),
            set::url($lang->website),
            set::target('_blank'),
            set::hint($version),
            set::text($versionName)
        ),
        $upgradeBtn,
        panel
        (
            setID('upgradeContent'),
            to::heading
            (
                setClass('justify-start items-center gap-1'),
                icon(setClass('version-upgrade'), setID('versionTitle')),
                span($lang->index->upgradeVersion)
            ),
            set::headingClass('border-b'),
            set::bodyClass('p-0'),
            div
            (
                setID('latestVersionList'),
                setClass('p-4'),
                $upgradeContent
            )
        )
    )
);

/* Inject zai config to index page. */
$zaiLang = new stdClass();
$this->app->loadLang('aiapp');
if($zaiConfig && !empty($zaiConfig->host) && !empty($zaiConfig->token))
{
    if(!hasPriv('aiapp', 'conversation')) $zaiConfig->privs = 'disable-all';
    $zaiLang = $lang->aiapp->langData;
}
else
{
    $zaiLang->zaiConfigNotValid = $lang->aiapp->langData->zaiConfigNotValid;
}

if($config->edition != 'open')
{
    $this->app->loadLang('ai');
    $zaiLang->knowledgeLib = $lang->ai->knowledgeLib;
}

$zaiConfigUrl = createLink('zai', 'setting');
$zaiLang->zaiConfigNotValid = str_replace('{zaiConfigUrl}', $zaiConfigUrl, $lang->aiapp->langData->zaiConfigNotValid);
if(isset($zaiLang->unauthorizedError)) $zaiLang->unauthorizedError = str_replace('{zaiConfigUrl}', $zaiConfigUrl, $lang->aiapp->langData->unauthorizedError);
to::head
(
    $zaiConfig ? h::js('window.zai=' . js::value($zaiConfig) . ';') : null,
    h::js('window.zaiLang=', js::value($zaiLang)),
    h::importJs($app->getWebRoot() . 'js/zui3/ai.js', setID('aiJS'))
);

/**
 * Check if the tutorial mode is on, show confirm dialog if it is.
 * 检查是否处于教程模式，如果是则显示确认对话框是否继续。
 */
if(common::isTutorialMode())
{
    setData('tutorialTip', $lang->index->tutorialTip);
    to::head
    (
        js
        (<<<'JS'
            $(function()
            {
                if(window.top !== window) return;
                zui.Modal.confirm($('html').data('tutorialTip')).then(result =>
                {
                    window.location = result ? $.createLink('tutorial', 'index') : $.createLink('tutorial', 'quit');
                });
            })
        JS)
    );
}

render('pagebase');
