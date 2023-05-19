<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        http://www.zentao.net
 */
namespace zin;
panel
(
    div('这是详情页面'),
    floatToolbar
    (
        set::prefix
        (
            array(array('icon' => 'back', 'text' => $lang->goback))
        ),
        set::main
        (
            array
            (
                array('icon' => 'ok', 'text' => $lang->bug->confirmBug, 'url' => helper::createLink('bug', 'confirmBug', "bugID=$bug->id"), 'data-toggle' => 'modal'),
                array('icon' => 'hand-right', 'text' => $lang->bug->assignTo, 'url' => helper::createLink('bug', 'assignTo', "bugID=$bug->id"), 'data-toggle' => 'modal'),
                array('icon' => 'checked', 'text' => $lang->bug->resolve, 'url' => helper::createLink('bug', 'resolve', "bugID=$bug->id"), 'data-toggle' => 'modal'),
                array('icon' => 'close', 'text' => $lang->bug->close, 'url' => helper::createLink('bug', 'close', "bugID=$bug->id"), 'data-toggle' => 'modal'),
                array('icon' => 'magic', 'text' => $lang->bug->activate, 'url' => helper::createLink('bug', 'activate', "bugID=$bug->id"), 'data-toggle' => 'modal'),
            )
        ),
        set::suffix
        (
            array
            (
                array('icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID=$bug->id")),
                array('icon' => 'copy'),
                array('icon' => 'delete'),
            )
        )
    )
);
render();
