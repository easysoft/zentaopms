<?php
declare(strict_types=1);
/**
 * The managetagpriv view file of gitlab module of ZenTaoPMS.
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

jsVar('hasAccessTags', $hasAccessTags);
formBatchPanel
(
    set::data(array_values($hasAccessTags)),
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
        set::label($lang->gitlab->tag->name),
        set::width('1/3'),
        set::name('name'),
        set::control
        (
            array(
                'type' => 'picker',
                'name' => 'name',
                'cache' => false,
                'items' => $tagPairs,
                'menu'  => jsRaw('{getItem(item) {return getMenu(item)}}')
            )
        )
    ),
    formBatchItem
    (
        set::name('createAccess'),
        set::label($lang->gitlab->tag->accessLevel),
        set::control('picker'),
        set::width('1/2'),
        set::required(true),
        set::items($lang->gitlab->branch->branchCreationLevelList)
    )
);
