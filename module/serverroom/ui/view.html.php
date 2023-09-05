<?php
declare(strict_types=1);
/**
 * The detail view file of serverroom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     serverroom
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

$actions = $this->loadModel('common')->buildOperateMenu($serverRoom);

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $serverRoom->id, 'level' => 1, 'text' => $serverRoom->name))
        )
    ),
);

detailBody
(
    sectionList
    (
        section
        (
            tableData
            (
                item
                (
                    set::name($lang->serverroom->name),
                    $serverRoom->name
                ),
                item
                (
                    set::name($lang->serverroom->city),
                    \zget($lang->serverroom->cityList, $serverRoom->city)
                ),
                item
                (
                    set::name($lang->serverroom->line),
                    \zget($lang->serverroom->lineList, $serverRoom->line)
                ),
                item
                (
                    set::name($lang->serverroom->bandwidth),
                    $serverRoom->bandwidth
                ),
                item
                (
                    set::name($lang->serverroom->provider),
                    \zget($lang->serverroom->providerList, $serverRoom->provider)
                ),
                item
                (
                    set::name($lang->serverroom->owner),
                    \zget($users, $serverRoom->owner)
                ),
                item
                (
                    set::name($lang->serverroom->createdBy),
                    \zget($users, $serverRoom->createdBy)
                ),
                item
                (
                    set::name($lang->serverroom->createdDate),
                    $serverRoom->createdDate
                ),
            )
        ),
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'serverroom', 'objectID' => $serverRoom->id))),
    ),
    floatToolbar
    (
        set::object($serverRoom),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::className('ghost text-white'), $lang->goback)),
        set::suffix($actions['suffixActions'])
    ),
);

render();
