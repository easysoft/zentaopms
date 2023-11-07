<?php
/**
 * The importcard view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('kanbanID', $kanbanID);
jsVar('regionID', $regionID);
jsVar('groupID',  $groupID);
jsVar('columnID', $columnID);
jsVar('methodName', $this->app->rawMethod);
featureBar
(
    inputGroup
    (
        span(set::className('input-group-addon'), $lang->kanban->selectedKanban),
        picker(set::name('kanban'), set::items($kanbanPairs), set::value($selectedKanbanID), set::style(array('width' => '200px')), set('data-on', 'change'), set('data-call', 'changeKanban'), set::required(true)),
        span(set::className('input-group-addon'), $lang->kanban->selectedLane),
        picker(set::name('lane'), set::items($lanePairs), set::style(array('width' => '200px')), set::required(true)),
    )
);

formBase
(
    set::id('cardForm'),
    set::actions(''),
    set::className('mt-2'),
    dtable
    (
        set::id('linkCard'),
        set::fixedLeftWidth('0.44'),
        set::checkable(true),
        set::cols(array_values($config->kanban->dtable->card->fieldList)),
        set::data(array_values($cards2Imported)),
        set::footToolbar(array('items' => array(array('text' => $lang->kanban->importCard, 'btnType' => 'primary', 'className' => 'size-sm importcardBtn batch-btn', 'data-url'  => inlink('importcard', "kanbanID=$kanbanID&regionID=$regionID&groupID=$groupID&columnID=$columnID&selectedKanbanID=$selectedKanbanID"))))),
        set::footPager(usePager())
    )
);
