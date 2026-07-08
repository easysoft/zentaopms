<?php
declare(strict_types=1);
/**
 * The exec view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;

jsVar('repoID', $repoID);

formPanel
(
    set::title($title),
    formGroup
    (
        set::name('plan'),
        $planID ? setClass('hidden') : null,
        set::width('1/2'),
        set::label($lang->codescan->plan),
        set::required(true),
        set::value($planID),
        set::items($plans),
        on::change()->call('getScanBranches')
    ),
    formGroup
    (
        set::name('branch'),
        set::width('1/2'),
        set::label($lang->codescan->branch),
        set::required(true),
        set::items(array()),
        on::init()->call('getScanBranches')
    )
);
