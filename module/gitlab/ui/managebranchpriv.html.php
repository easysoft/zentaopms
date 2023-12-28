<?php
declare(strict_types=1);
/**
 * The managebranchpriv view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($permissionError))
{
    jsCall('alertJump', array($permissionError, $errorJump));
    return;
}

jsVar('hasAccessBranches', $hasAccessBranches);
formBatchPanel
(
    set::data(array_values($hasAccessBranches)),
    set::onRenderRow(jsRaw('onRenderRow')),
    formBatchItem
    (
        set::name('id'),
        set::label('ID'),
        set::control('index'),
        set::width('50px')
    ),
    formBatchItem
    (
        set::label($lang->gitlab->branch->name),
        set::width('1/3'),
        set::name('name'),
        set::control
        (
            array(
                'type' => 'picker',
                'name' => 'name',
                'cache' => false,
                'items' => $branchPairs,
                'menu'  => jsRaw('{getItem(item) {return getMenu(item)}}')
            )
        )
    ),
    formBatchItem
    (
        set::name('mergeAccess'),
        set::label($lang->gitlab->branch->mergeAllowed),
        set::control('picker'),
        set::width('1/3'),
        set::items($lang->gitlab->branch->branchCreationLevelList)
    ),
    formBatchItem
    (
        set::name('pushAccess'),
        set::label($lang->gitlab->branch->pushAllowed),
        set::control('picker'),
        set::width('1/3'),
        set::items($lang->gitlab->branch->branchCreationLevelList)
    )
);
