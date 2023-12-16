<?php
/**
 * The importplan view file of kanban module of ZenTaoPMS.
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
        span(set::className('input-group-addon'), $lang->kanban->selectedProduct),
        picker(set::name('product'), set::items($products), set::value($selectedProductID), set::style(array('width' => '200px')), set('data-on', 'change'), set('data-call', 'changeProduct'), set::required(true)),
        span(set::className('input-group-addon'), $lang->kanban->selectedLane),
        picker(set::name('lane'), set::items($lanePairs), set::style(array('width' => '200px')), set::required(true))
    )
);

unset($config->productplan->dtable->fieldList['title']['link']);
unset($config->productplan->dtable->fieldList['title']['nestedToggle']);
unset($config->productplan->dtable->fieldList['branch']);
unset($config->productplan->dtable->fieldList['execution']);
unset($config->productplan->dtable->fieldList['actions']);

foreach($config->productplan->dtable->fieldList as $id => $field) $config->productplan->dtable->fieldList[$id]['sortType'] = false;

formBase
(
    set::id('linkForm'),
    set::actions(''),
    set::className('mt-2'),
    dtable
    (
        set::id('linkPlan'),
        set::fixedLeftWidth('0.33'),
        set::checkable(true),
        set::sortType(false),
        set::cols(array_values($config->productplan->dtable->fieldList)),
        set::data(array_values($plans2Imported)),
        set::footToolbar(array('items' => array(array('text' => $lang->kanban->importAB, 'btnType' => 'primary', 'className' => 'size-sm batch-btn', 'data-url'  => inlink('importplan', "kanbanID=$kanbanID&regionID=$regionID&groupID=$groupID&columnID=$columnID"))))),
        set::footPager(usePager())
    )
);
