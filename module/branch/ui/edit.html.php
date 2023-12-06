<?php
declare(strict_types=1);
/**
 * The edit view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title(sprintf($lang->branch->edit, $lang->product->branchName[$product->type])),
    formGroup
    (
        set::label(sprintf($lang->branch->name, $lang->product->branchName[$product->type])),
        set::required(true),
        set::name('name'),
        set::value($branch->name),
        set::control('input')
    ),
    formGroup
    (
        set::label($lang->branch->status),
        set::required(true),
        set::width('1/2'),
        set::name('status'),
        set::value($branch->status),
        set::items($lang->branch->statusList),
        set::control('picker')
    ),
    formGroup
    (
        set::label(sprintf($lang->branch->desc, $lang->product->branchName[$product->type])),
        textarea
        (
            set::name('desc'),
            set::value($branch->desc),
            set::rows('5')
        )
    )
);
