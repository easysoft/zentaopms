<?php
declare(strict_types=1);
/**
 * The manageprojectmembers view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('hasAccessUsers', $currentMembers);
formBatchPanel
(
    set::data(array_values($currentMembers)),
    set::onRenderRow(jsRaw('onRenderRow')),
    formBatchItem
    (
        set::name('index'),
        set::label('ID'),
        set::control('index'),
        set::width('50px'),
    ),
    formBatchItem
    (
        set::label($lang->gitlab->group->memberName),
        set::width('1/3'),
        set::name('id'),
        set::control
        (
            array(
                'type'  => 'picker',
                'name'  => 'id',
                'cache' => false,
                'items' => $gitlabUsers,
                'menu'  => jsRaw('{getItem(item) {return getMenu(item)}}'),
            )
        )
    ),
    formBatchItem
    (
        set::name('access_level'),
        set::label($lang->gitlab->group->memberAccessLevel),
        set::control('picker'),
        set::width('1/3'),
        set::items($lang->gitlab->accessLevels)
    ),
    formBatchItem
    (
        set::name('expires_at'),
        set::label($lang->gitlab->group->memberExpiresAt),
        set::control('datePicker'),
        set::width('1/3'),
    )
);
