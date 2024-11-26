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

foreach($appList as $system) $apps[$system->id] = $system->name;

$appReleases = array();
foreach($releases as $releaseID => $release) $appReleases[$release->system][$releaseID] = $release->name;

jsVar('releases', $releases);

$systemTR = array();
$i        = 0;
foreach($apps as $system)
{
    $appID = $linkedApps ? key($linkedApps) : 0;
    $systemTR[] = h::tr
    (
        setClass('form-row'),
        h::td
        (
            picker
            (
                set::id("apps{$i}"),
                set::name("apps[$i]"),
                set::items($apps),
                $appID ? set::value($appID) : null,
                set('onchange', "setRelease(event, '{$i}')")
            )
        ),
        h::td
        (
            picker
            (
                set::id("releases{$i}"),
                set::name("releases[$i]"),
                set::items($appID ? $appReleases[$appID] : array()),
                $appID ? set::value(current($linkedApps)) : null
            )
        ),
        h::td
        (
            set::className('actions-list'),
            btnGroup
            (
                set::items(array(
                    array('class' => 'btn btn-link text-gray', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                    array('class' => 'btn btn-link text-gray', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
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
        h::td
        (
            picker
            (
                set::id("apps{$i}"),
                set::name("apps[$i]"),
                set::items($apps),
                set('onchange', "setRelease(event, '{$i}')")
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
                    array('class' => 'btn btn-link text-gray', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                    array('class' => 'btn btn-link text-gray', 'icon' => 'trash', 'onclick' => 'deleteItem(this)')
                ))
            )
        )
    )
);
