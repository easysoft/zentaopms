<?php
declare(strict_types=1);
/**
 * The models view file of aiapp module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang <wangzemei@easycorp.ltd>
 * @package     aiapp
 * @link        https://www.zentao.net
 */
namespace zin;

$langData = [];
$langData['model']        = $lang->aiapp->model;
$langData['modelID']      = $lang->aiapp->modelID;
$langData['actions']      = $lang->actions;
$langData['startChat']    = $lang->aiapp->converse;
$langData['pageSummary']  = $lang->aiapp->pageSummary;
$langData['abilities']    = $lang->aiapp->abilities;
$langData['abilityTypes'] = $lang->aiapp->abilityTypes;
$langData['noDataTip']    = $lang->aiapp->tips->noData;

$featureBarItems = [];
$featureBarItems['all'] = ['text' => $lang->all, 'data-id' => 'all', 'active' => $type === 'all', 'url' => createLink('aiapp', 'models', 'type=all')];
foreach($lang->aiapp->abilityTypes as $ability => $abilityType)
{
    $featureBarItems[$ability] = ['text' => $abilityType, 'data-id' => $ability, 'active' => $type === $ability, 'url' => createLink('aiapp', 'models', 'type=' . str_replace('-', '_', $ability))];
}
featureBar(set::items($featureBarItems));

toolbar
(
    zui::searchBox
    (
        set::placeholder($lang->aiapp->searchModels),
        set::onChange(jsRaw('window.handleSearchModels'))
    )
);

div
(
    setClass('models-view'),
    on::init()->call('initModelList'),
    dtable
    (
        setID('modelsList'),
        set::canStartChat(hasPriv('aiapp', 'conversation')),
        set::langData($langData),
        set::modelType($type),
        set::cols(array()),
        set::data(array()),
        set::footer(jsRaw('function(){return window.setModelsStatistics.call(this);}')),
        set::emptyTip($lang->loading),
        set::noDataTip($lang->aiapp->tips->noData)
    )
);
