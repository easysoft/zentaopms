<?php
/**
 * The importrelease view file of kanban module of ZenTaoPMS.
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

foreach($releases2Imported as $release)
{
    $projects = '';
    $builds   = '';
    if($release->builds)
    {
        foreach($release->builds as $build)
        {
            $builds   .= $build->name . ' ';
            $projects .= $build->projectName . ' ';
        }
        $release->build   = $builds;
        $release->project = $projects;
    }
}

featureBar
(
    inputGroup
    (
        span(set::className('input-group-addon'), $lang->kanban->selectedProduct),
        picker(set::name('product'), set::items($products), set::value($selectedProductID), set::style(array('width' => '200px')), set('data-on', 'change'), set('data-call', 'changeProduct'), set::required(true)),
        span(set::className('input-group-addon'), $lang->kanban->selectedLane),
        picker(set::name('lane'), set::items($lanePairs), set::style(array('width' => '200px')), set::required(true)),
    )
);

unset($config->release->dtable->fieldList['title']['link']);
unset($config->release->dtable->fieldList['branch']);
unset($config->release->dtable->fieldList['actions']);

formBase
(
    set::id('linkForm'),
    set::actions(''),
    set::className('mt-2'),
    dtable
    (
        set::fixedLeftWidth('0.33'),
        set::checkable(true),
        set::cols(array_values($config->release->dtable->fieldList)),
        set::data(array_values($releases2Imported)),
        set::footToolbar(array('items' => array(array('text' => $lang->kanban->importCard, 'btnType' => 'primary', 'className' => 'size-sm importcardBtn batch-btn', 'data-url'  => inlink('importrelease', "kanbanID=$kanbanID&regionID=$regionID&groupID=$groupID&columnID=$columnID"))))),
        set::footPager(usePager())
    )
);
