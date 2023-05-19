<?php
declare(strict_types=1);
/**
 * The confirm view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('page', 'confirm');

formPanel
(
    set::title($bug->title),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->bug->assignedTo),
        select
        (
            set::name('assignedTo'),
            set::items($users),
            set::value($bug->assignedTo),
        ),
    ),
    formGroup
    ( 
        set::width('1/3'),
        set::name('type'),
        set::label($lang->bug->type),
        set::control(array('type' => 'select', 'items' => $lang->bug->typeList)),
        set::value($bug->type)
    ),
    formGroup
    ( 
        set::width('1/3'),
        set::name('pri'),
        set::label($lang->bug->pri),
        set::control(array('type' => 'select', 'items' => $lang->bug->priList)),
        set::value($bug->pri)
    ),
    formRow
    (
        set::class('hidden'),
        formGroup
        ( 
            set::width('1/3'),
            set::name('status'),
            set::control('hidden'),
            set::value($bug->status)
        ),
    ),
    formGroup
    (
        set::label($lang->bug->lblMailto),
        set::control(array('type' => 'select', 'items' => $users)),
        set::name('mailto'),
        set::value($bug->mailto),
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::value(),
        )
    ),
    history(),
);

render();
