<?php
declare(strict_types=1);
/**
 * The view archived column view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

$colItems = array();
foreach($columns as $column)
{
    $colItems[] = div
    (
        setClass('col-item flex mt-2'),
        div
        (
            setClass('col-item-title flex-1'),
            $column->parent > 0 ? html("<span class='label gray-pale rounded-xl'>{$lang->kanban->child}</span> $column->name") : $column->name,
        ),
        div
        (
            setClass('col-item-action  flex-1 flex justify-end ml-2'),
            (commonModel::hasPriv('kanban', 'restoreColumn') && $column->archived == '1' && !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')) ?
            btn
            (
                set
                (
                    array
                    (
                        'class' => 'btn primary size-sm ajax-submit',
                        'url'   => inlink('restoreColumn', "colID=$column->id")
                    )
                ),
                $lang->kanban->restore,
            ) : null
        )
    );
}

panel
(
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            $lang->kanban->archivedColumn
        )
    ),
    to::headingActions
    (
        btn
        (
            setClass('closeBtn ghost'),
            'x'
        )
    ),
    div
    (
        setClass('panel-body'),
        $colItems
    )
);
