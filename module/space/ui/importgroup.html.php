<?php
declare(strict_types=1);
/**
 * The import group view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->space->importGroup));
formPanel
(
    set::labelWidth(common::checkNotCN() ? '140px' : '100px'),
    formGroup
    (
        set::label($lang->space->sourceSpace),
        set::required(true),
        set::name('sourceSpace'),
        set::items($spaces),
        on::change()->call('loadGroup')
    ),
    formGroup
    (
        setID('sourceGroup'),
        on::change()->call('copyGroup'),
        set::label($lang->space->sourceGroup),
        set::required(true),
        set::name('sourceGroup'),
        set::items(array())
    ),
    formGroup
    (
        set::label($lang->group->name),
        set::required(true),
        set::name('name')
    ),
    formGroup
    (
        set::label($lang->group->desc),
        textarea
        (
            set::name('desc'),
            set::rows('5')
        )
    )
);
