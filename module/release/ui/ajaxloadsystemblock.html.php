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

jsVar('releases', $releases);
jsVar('appList',  $appList);

$systemTR = array();
$i        = 0;
foreach($appList as $system)
{
    $systemTR[] = h::tr
    (
        setClass('form-row'),
        h::td
        (
            picker
            (
                set::id("apps{$i}"),
                set::name("apps[$i]"),
                set::items($appList),
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
    );

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
                set::items(array()),
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
