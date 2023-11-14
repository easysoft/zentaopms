<?php
declare(strict_types=1);
/**
 * The createbranch view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('gitlabCreateForm'),
    set::title($lang->gitlab->createBranch),
    set::actionsClass('w-1/2'),
    formGroup
    (
        set::name('branch'),
        set::label($lang->gitlab->branch->name),
        set::required(true),
        set::width('1/2')
    ),
    formGroup
    (
        set::label($lang->gitlab->branch->from),
        set::required(true),
        set::width('1/2'),
        picker
        (
            set::name('ref'),
            set::items($branchPairs),
            set::required(true)
        )
    )
);
