<?php
declare(strict_types=1);
/**
 * The viewcard view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

$assignedToList  = '';
$assignedToPairs = array_filter(explode(',', $card->assignedTo));
foreach($assignedToPairs as $index => $assignedTo)
{
    $userName = \zget($users, $assignedTo, '');
    if($userName) $assignedToList .= $userName . ' ';
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($card->id),
            set::level(1),
            set::text($card->name)
        )
    )
);

$actions   = $this->loadModel('common')->buildOperateMenu($card);
$canModify = !empty($this->config->CRKanban) || $kanban->status != 'closed';
detailBody
(
    sectionList
    (
        set::style(array('padding' => '0px 0px 20px 0px')),
        section
        (
            set::title($lang->kanbancard->desc),
            set::content($card->desc),
            set::useHtml(true)
        )
    ),
    history(set(array('objectType' => 'kanbancard', 'objectID' => $card->id))),
    $canModify ? floatToolbar
    (
        set::object($card),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::className('ghost text-white'), $lang->goback)),
        set::main($actions['mainActions']),
        set::suffix($actions['suffixActions'])
    ) : null,
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key(''),
                set::title($lang->kanbancard->legendBasicInfo),
                set::active(true),
                tableData
                (
                    item(set::name($lang->kanbancard->assignedTo), $assignedToList),
                    item(set::name($lang->kanbancard->space), $space->name),
                    item(set::name($lang->kanbancard->kanban), $kanban->name),
                    item(set::name($lang->kanbancard->begin), $card->begin),
                    item(set::name($lang->kanbancard->end), $card->end),
                    item(set::name($lang->kanbancard->pri), priLabel($card->pri)),
                    item(set::name($lang->kanbancard->estimate), round((float)$card->estimate, 2) . ' ' . $lang->kanbancard->lblHour),
                    $kanban->performable ? item(set::name($lang->kanbancard->progress), round((float)$card->progress, 2) . ' %') : null
                )
            )
        ),
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendLifeTime'),
                set::title($lang->kanbancard->legendLifeTime),
                set::active(true),
                tableData
                (
                    item(set::name($lang->kanbancard->createdBy),    \zget($users, $card->createdBy) . $lang->at . $card->createdDate),
                    item(set::name($lang->kanbancard->archivedBy),   $card->archivedBy ? \zget($users, $card->archivedBy) . $lang->at . $card->archivedDate : ''),
                    item(set::name($lang->kanbancard->lastEditedBy), $card->lastEditedBy ? \zget($users, $card->lastEditedBy) . $lang->at . $card->lastEditedDate : '')
                )
            )
        )
    )
);

render();
