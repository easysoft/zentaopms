<?php
declare(strict_types=1);
/**
 * The ajaxLoadSystemBlock view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

$apps        = array();
$linkedApps  = array();
foreach($linkedRelease as $releaseID)
{
    if(!isset($releases[$releaseID])) continue;

    $systemID = $releases[$releaseID]->system;
    if(!isset($appList[$systemID])) continue;

    $apps[$systemID] = $appList[$systemID]->name;
    unset($appList[$systemID]);

    $linkedApps[$systemID] = $releaseID;
}

foreach($appList as $system)
{
    $apps[$system->id]       = $system->name;
    $linkedApps[$system->id] = 0;
}

$appReleases = array();
foreach($releases as $releaseID => $release) $appReleases[$release->system][$releaseID] = $release->name;

if(!$apps) $apps = array(0 => '');

jsVar('releases',    $releases);
jsVar('appLength',   count($apps));

$systemTR = array();
$i        = 0;
foreach($apps as $system)
{
    $appID  = $linkedApps ? key($linkedApps) : 0;
    $linked = current($linkedApps);
    $systemTR[] = h::tr
    (
        setClass('form-row'),
        setData('index', $i),
        h::td
        (
            picker
            (
                set::id("apps{$i}"),
                set::name("apps[$i]"),
                set::items($apps),
                $appID ? set::value($appID) : null,
                set::required(true)
            )
        ),
        h::td
        (
            picker
            (
                set::id("releases{$i}"),
                set::name("releases[$i]"),
                set::items(zget($appReleases, $appID, array())),
                $appID ? set::value($linked) : null
            )
        ),
        h::td
        (
            set::className('actions-list'),
            btnGroup
            (
                set::items(array(
                    array('class' => 'btn btn-link text-gray add-item hidden', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                    array('class' => 'btn btn-link text-gray del-item', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
                ))
            )
        )
    );

    unset($linkedApps[key($linkedApps)]);
    $i ++;
}

jsVar('+itemIndex', $i);

div
(
    setID('systemForm'),
    h::table
    (
        on::change('[name^=apps]')->call("setRelease", jsRaw('target')),
        h::tbody
        (
            setClass('form'),
            $systemTR,
            input
            (
                set::type('hidden'),
                set::name('removeExecution'),
                set::value('no')
            )
        )
    )
);

$i = '_i';
h::table
(
    set::className('hidden'),
    set::id('addItem'),
    h::tr(
        setData('index', $i),
        h::td
        (
            picker
            (
                set::id("apps{$i}"),
                set::name("apps[$i]"),
                set::items($apps)
            )
        ),
        h::td
        (
            picker
            (
                set::id("releases{$i}"),
                set::name("releases[$i]"),
                set::items(array())
            )
        ),
        h::td
        (
            set::className('actions-list'),
            btnGroup
            (
                set::items(array(
                    array('class' => 'btn btn-link text-gray add-item', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                    array('class' => 'btn btn-link text-gray del-item', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
                ))
            )
        )
    )
);
