<?php
declare(strict_types=1);
/**
 * Create view of program plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao <chentao@easycorp.ltd>
 * @package     programplan
 * @link        https://www.zentao.net
 */

namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$fields         = $this->config->programplan->form->create;
$reviewedPoints = isset($reviewedPoints) ? $reviewedPoints : array();
$canParallel    = isset($canParallel)    ? $canParallel    : false;
$customKey      = 'createFields';
$section        = 'custom';
unset($fields['level']);

/* Generate custom config key by project model. */
if(in_array($project->model, array('waterfallplus', 'ipd', 'waterfall'))) $customKey = 'create' . ucfirst($project->model) . 'Fields';
if($executionType == 'agileplus')
{
    $section   = 'customAgilePlus';
    $customKey = 'createFields';
}

/* Generate title that is tailored to specific situation. */
$title = $lang->programplan->create;
if($planID)
{
    $title = $programPlan->name . $lang->project->stage . '（' . $programPlan->begin . $lang->project->to . $programPlan->end . '）';
}
else
{
    $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
    $title .= '（' . $project->name . '-' . $lang->execution->beginAndEnd . ' : ' . $project->begin . ' ~ ' . $project->end . '）';
}

/* Generate product list dropdown menu while stage by product. */
$fnGenerateStageByProductList = function() use ($productID, $productList, $project, $planID, $syncData)
{
    if(empty($productList) || $project->stageBy != 'product') return null;

    $defaultName = $productID != 0 ? zget($productList,$productID) : current($productList);

    $items = array();
    foreach($productList as $key => $product)
    {
        $items[] = array('text' => $product, 'active' => $productID == $key, 'url' => createLink('programplan', 'create', "projectID=$project->id&productID=$key&planID=$planID&executionType=stage&from=&syncData={$syncData}"));
    }

    return dropdown($defaultName, span(setClass('caret')), set::items($items));
};

/* Generate checkboxes for sub-stage management. */
$fnGenerateSubPlanManageFields = function() use ($lang, $planID, $project, $canParallel)
{
    if(!(empty($planID) && $project->model == 'ipd')) return div();

    foreach($lang->programplan->parallelList as $key => $value)
    {
        $items[] = div(setClass('px-1'), checkbox
        (
            set::type('radio'),
            set::name('parallel'),
            set::text($value),
            set::value($key),
            set::checked($key == $project->parallel),
            set::disabled($canParallel),
            on::change('window.onChangeParallel')
        ));
    }

    return div
    (
        setClass('flex w-1/2 items-center'),
        div(setClass('font-bold'), $lang->programplan->parallel . ':'),
        $items,
        html($lang->programplan->parallelTip)
    );
};

/* Generate form fields. */
$fnGenerateFields = function() use ($lang, $requiredFields, $showFields, $fields, $PMUsers, $enableOptionalAttr, $programPlan, $planID, $executionType, $project, $syncData)
{
    $items = array();

    $fields['attribute']['required'] = $fields['acl']['required'] = true;
    if(isset($requiredFields['code'])) $fields['code']['required'] = true;

    $renderFields = implode(',', array_keys($requiredFields));
    $renderFields = ",$renderFields,$showFields,";

    foreach($fields as $name => $field)
    {
        $field['name'] = $name;
        if($name == 'id')
        {
            $field['control'] = 'hidden';
            $field['hidden']  = true;
        }
        if(!empty($field['default'])) $field['value'] = $field['default'];

        /* Convert 'options' to 'items'. */
        if(!empty($field['options'])) $field['items'] = $field['options'];
        unset($field['options']);

        /* Assgn item data to PM field. */
        if($name == 'PM')       $field['items'] = $PMUsers;
        if($name == 'syncData') $field['value'] = $syncData;

        /* Set hidden attribute. */
        if(!str_contains($renderFields, ",$name,")) $field['hidden'] = true;

        /* Sub-stage. */
        if($name == 'attribute')
        {
            if(!$enableOptionalAttr) $field['disabled'] = true;
            if($programPlan)         $field['value']    = $programPlan->attribute;
        }

        if($name == 'acl' && $planID)
        {
            $field['disabled'] = true;
            $field['value']    = empty($programPlan) ? 'open' : $programPlan->acl;
        }

        /* Field for agileplus. */
        if($name == 'type' && in_array($project->model, array('waterfallplus', 'ipd')))
        {
            $field['hidden'] = false;
            $field['items']  = $lang->execution->typeList;
            if($project->model == 'waterfallplus')
            {
                $field['tipIcon']  = 'help';
                $field['tip']      = $lang->programplan->typeTip;
                $field['tipProps'] = array
                (
                    'id'              => 'tooltipHover',
                    'data-toggle'     => 'tooltip',
                    'data-placement'  => 'right',
                    'data-type'       => 'white',
                    'data-class-name' => 'text-gray border border-gray-300'
                );
            }
        }

        if($name == 'attribute' && in_array($project->model, array('waterfall', 'waterfallplus')))
        {
            $field['tipIcon']  = 'help';
            $field['tip']      = $lang->execution->typeTip;
            $field['tipProps'] = array
            (
                'id'              => 'tooltipHover',
                'data-toggle'     => 'tooltip',
                'data-placement'  => 'right',
                'data-type'       => 'white',
                'data-class-name' => 'text-gray border border-gray-300'
            );
        }

        if($name == 'milestone') $field['width'] = '100px';
        if($name == 'enabled')   $field['width'] = '80px';
        if($name == 'point')
        {
            $field['width']    = '200px';
            $field['tipIcon']  = 'help';
            $field['tip']      = zget($lang->programplan, 'pointTip', '');
            $field['tipProps'] = array
            (
                'id'              => 'tooltipHover',
                'data-toggle'     => 'tooltip',
                'data-placement'  => 'right',
                'data-type'       => 'white',
                'data-class-name' => 'text-gray border border-gray-300'
            );
        }
        if($name == 'defaultPoint') $field['hidden'] = true;

        if($name == 'name') $field['width'] = '240px';
        if(!isset($field['width'])) $field['width'] = '120px';

        $items[] = $field;
    }

    return $items;
};

/* Generate default rendering data. */
$fnGenerateDefaultData = function() use ($config, $plans, $planID, $stages, $executionType, $project)
{
    $items = array();

    /* Created a new project with no stages. */
    if(empty($plans) && $planID == 0)
    {
        foreach($stages as $stage)
        {
            $TRpoints  = isset($stage->pointList['TR']) ? array_keys($stage->pointList['TR']) : array();
            $DCPpoints = isset($stage->pointList['DCP']) ? array_keys($stage->pointList['DCP']) : array();
            $points    = array_merge($TRpoints, $DCPpoints);

            $item               = new stdClass();
            $item->name         = $stage->name;
            $item->code         = isset($stage->code) ? $stage->code : '';
            $item->percent      = $stage->percent ?: 0;
            $item->attribute    = $stage->type;
            $item->acl          = 'open';
            $item->milestone    = 0;
            $item->point        = implode(',', $points);
            $item->defaultPoint = implode(',', $points);
            $item->parallel     = 0;
            $item->stageID      = $stage->id;

            $items[] = $item;
        }
    }

    /* Create stages for exist project. */
    foreach($plans as $plan)
    {
        $points = isset($plan->enabledPoints) ? array_keys($plan->enabledPoints) : array();

        $item               = new stdClass();
        $item->disabled     = $plan->type != 'stage';
        $item->enabled      = $plan->enabled;
        $item->id           = $plan->id;
        $item->type         = $plan->type;
        $item->name         = $plan->name;
        $item->code         = $plan->code;
        $item->PM           = $plan->PM;
        $item->status       = $plan->status;
        $item->percent      = $plan->percent ?: 0;
        $item->attribute    = $plan->attribute;
        $item->acl          = $plan->acl;
        $item->milestone    = $plan->milestone;
        $item->begin        = $plan->begin;
        $item->end          = $plan->end;
        $item->realBegan    = $plan->realBegan;
        $item->realEnd      = $plan->realEnd;
        $item->desc         = $plan->desc;
        $item->setMilestone = isset($plan->setMilestone) ? $plan->setMilestone : false;
        $item->order        = $plan->order;
        $item->parallel     = $plan->parallel;
        $item->point        = implode(',', $points);
        $plan->stageID      = $plan->id;
        $plan->disabled     = !isset($plan->setMilestone);
        $plan->setMilestone = isset($plan->setMilestone) ? $plan->setMilestone : false;
        $plan->point        = implode(',', $points);
        if(in_array($config->edition, array('max', 'ipd')) && $executionType == 'stage')
        {
            $plan->output = empty($plan->output) ? 0 : explode(',', $plan->output);
        }
        $items[] = $plan;
    }

    if($project->model != 'ipd' || $planID) $items[] = array();
    return $items;
};

/* ZIN: layout. */
jsVar('projectID',        $project->id);
jsVar('productID',        $productID);
jsVar('planID',           $planID);
jsVar('type',             $executionType);
jsVar('project',          $project);
jsVar('plans',            $plans);
jsVar('initType',         ($planID && $plans) ? reset($plans)->type : 'stage');
jsVar('planGrade',        $programPlan ? $programPlan->grade + 1 : 1);
jsVar('syncData',         $syncData);
jsVar('cropStageTip',     $lang->programplan->cropStageTip);
jsVar('childEnabledTip',  $lang->programplan->childEnabledTip);
jsVar('typeList',         $lang->execution->typeList);
jsVar('confirmCreateTip', $lang->project->confirmCreateStage);
jsVar('errorLang',        $lang->programplan->error);
jsVar('ipdStagePoint',    $project->model == 'ipd' ? $ipdStagePoint : array());
jsVar('attributeList',    $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList);
jsVar('reviewedPoints',   $project->model == 'ipd' ? $reviewedPoints : array());
jsVar('reviewedPointTip', $project->model == 'ipd' ? $lang->programplan->reviewedPointTip : '');
jsVar('addSubTip',        $lang->programplan->error->notStage);
jsVar('addSiblingTip',    zget($lang->programplan, 'addSiblingTip', ''));
jsVar('sortableTip',      zget($lang->programplan, 'sortableTip', ''));

featureBar(li
(
    setClass('nav-item'),
    a(setClass('active'), $title),
    empty($project->isTpl) ? $fnGenerateStageByProductList() : null
));

toolbar
(
    backBtn(set::icon('back'), setClass('primary'), $lang->goback),
);

if($project->model == 'ipd' && empty($stages) && empty($plans) && empty($planID))
{
    div
    (
        setClass('dtable-empty-tip bg-white'),
        div
        (
            setClass('row gap-4 items-center'),
            div
            (
                setClass('text-gray'),
                $lang->programplan->emptyStageTip
            )
        )
    );
    return;
}

$batchFormOptions = array();
$batchFormOptions['fixedActions']  = true; // 滚动时固定操作列。
$batchFormOptions['actions']       = array('sort', array('type' => 'addSibling', 'icon' => 'icon-plus', 'text' => $lang->task->addSibling), array('type' => 'addSub', 'icon' => 'icon-split', 'text' => $lang->task->addSub), 'delete');
$batchFormOptions['onClickAction'] = jsRaw('window.handleClickBatchFormAction'); // 定义操作列按钮点击事件处理函数。
$batchFormOptions['onRenderRow']   = jsRaw('window.handleRenderRow'); // 定义行渲染事件处理函数。

formBatchPanel
(
    setID('dataform'),
    set::idKey('index'),
    set::batchFormOptions($batchFormOptions),
    to::headingActions(array($fnGenerateSubPlanManageFields())),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'createFields')),
    set::customUrlParams("module=programplan&section=$section&key=$customKey"),
    set::items($fnGenerateFields()),
    set::sortable(array('onMove' => jsRaw('window.onMove'), 'onSort' => jsRaw('window.onSort'))),
    set::data($fnGenerateDefaultData()),
    $app->session->projectPlanList ? set::actions(array('submit', array('text' => $lang->cancel, 'url' => $app->session->projectPlanList))) : null,
    on::change('[name^="enabled"]', 'changeEnabled(e.target)'),
    on::change('[name^="attribute"]', 'changeAttribute(e.target)'),
    on::change('[name^="type"]', 'changeType(e.target)'),
    ($project->model == 'ipd' && !$planID) ? set::maxRows(count($fnGenerateDefaultData())) : null,
);
