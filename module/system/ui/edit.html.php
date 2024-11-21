<?php
declare(strict_types=1);
/**
 * The edit view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::id('systemEditForm'),
    set::title($lang->system->edit),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::name('integrated'),
        set::width('1/2'),
        set::label($lang->system->integrated),
        set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
        set::control('radioListInline'),
        set::value($system->integrated),
        set::items($lang->system->integratedList),
        set::disabled(true)
    ),
    formGroup
    (
        set::name('name'),
        set::width(common::checkNotCN() ? '3/4' : '1/2'),
        set::label($lang->system->name),
        set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
        set::required(true),
        set::value($system->name)
    ),
    formGroup
    (
        setID('children'),
        $system->integrated ? null : setClass('hidden'),
        set::name('children'),
        set::width(common::checkNotCN() ? '3/4' : '1/2'),
        set::required(true),
        set::label($lang->system->children),
        set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
        set::control('picker'),
        set::items($systemList),
        set::multiple(true),
        set::value($system->children)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->system->desc),
        set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
        set::control(array('type' => 'textarea', 'rows' => '4')),
        set::value($system->desc)
    )
);
