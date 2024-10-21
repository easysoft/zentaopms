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
$enabledPoints  = isset($enabledPoints)  ? $enabledPoints  : new stdclass();
$reviewedPoints = isset($reviewedPoints) ? $reviewedPoints : array();
$canParallel    = isset($canParallel)    ? $canParallel    : false;
$customKey      = 'createFields';
$section        = 'custom';

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
$fnGenerateStageByProductList = function() use ($productID, $productList, $project, $planID)
{
    if(empty($productList) || $project->stageBy != 'product') return null;

    $defaultName = $productID != 0 ? zget($productList,$productID) : current($productList);

    $items = array();
    foreach($productList as $key => $product)
    {
        $items[] = array('text' => $product, 'active' => $productID == $key, 'url' => createLink('programplan', 'create', "projectID=$project->id&productID=$key&planID=$planID"));
    }

    return dropdown
    (
        $defaultName,
        span(setClass('caret')),
        set::items($items)
    );
};

/* Generate checkboxes for sub-stage management. */
$fnGenerateSubPlanManageFields = function() use ($lang, $planID, $project, $executionType, $canParallel)
{
    if((empty($planID) && $project->model != 'ipd') || !in_array($project->model, array('waterfallplus', 'ipd'))) return div();

    if(empty($planID) && $project->model == 'ipd')
    {
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
    }

    $typeList = $lang->programplan->typeList;

    $items = array();
    if(count($typeList) > 1)
    {
        foreach($typeList as $key => $value)
        {
            $items[] = div(setClass('px-1'), checkbox
            (
                set::type('radio'),
                set::name('executionType'),
                set::text($value),
                set::value($key),
                on::change('window.onChangeExecutionType'),
                set::checked($key == $executionType)
            ));
        }
    }
    else
    {
        $items[] = div(setClass('px-1'), zget($typeList, $executionType));
    }

    /* Append method tip. */
    $items[] = icon(
                'help',
                setID('methodTip'),
                setClass('ml-2 text-gray'),
                setData(array('toggle' => 'tooltip', 'title' => $lang->programplan->methodTip, 'placement' => 'right', 'type' => 'white', 'class-name' => 'text-gray border border-light')),
            );

    $items[] = tooltip(
        set::_to('#methodTip'),
        set::title($lang->programplan->methodTip),
        set::placement('right'),
        set::type('white'),
        setClass('text-darker border border-light')
    );

    return div
    (
        setClass('flex w-1/2 items-center'),
        div(setClass('font-bold'), $lang->programplan->subPlanManage . ':'),
        $items
    );
};

/* Generate form fields. */
$fnGenerateFields = function() use ($config, $lang, $requiredFields, $showFields, $fields, $PMUsers, $enableOptionalAttr, $programPlan, $planID, $executionType, $project)
{
    $items   = array();
    $items[] = $project->model == 'ipd' ? null : array('name' => 'index', 'label' => $lang->programplan->idAB, 'control' => 'index', 'width' => '40px');

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
        if($name == 'PM') $field['items'] = $PMUsers;

        /* Set hidden attribute. */
        if(!str_contains($renderFields, ",$name,")) $field['hidden'] = true;

        /* Sub-stage. */
        if($name == 'attribute' && !$enableOptionalAttr)
        {
            $field['disabled'] = true;
            $field['value']    = $programPlan->attribute;
        }

        if($name == 'acl' && $planID)
        {
            $field['disabled'] = true;
            $field['value']    = empty($programPlan) ? 'open' : $programPlan->acl;
        }

        /* Field for agileplus. */
        if($name == 'type' && !empty($planID) && in_array($project->model, array('waterfallplus', 'ipd')))
        {
            $field['hidden'] = $executionType == 'stage';
            $field['items']  = $lang->execution->typeList;
        }
        if($name == 'milestone') $field['width'] = '100px';
        if($name == 'enabled')   $field['width'] = '80px';
        if($name == 'point')     $field['width'] = '200px';

        $items[] = $field;
    }

    return $items;
};

/* Generate default rendering data. */
$fnGenerateDefaultData = function() use ($config, $plans, $planID, $stages, $executionType, $enabledPoints)
{
    $items = array();

    /* Created a new project with no stages. */
    if(empty($plans) && $planID == 0)
    {
        foreach($stages as $stage)
        {
            $points = !empty($enabledPoints->{$stage->type}) ? $enabledPoints->{$stage->type} : array();

            $item            = new stdClass();
            $item->name      = $stage->name;
            $item->code      = isset($stage->code) ? $stage->code : '';
            $item->percent   = $stage->percent;
            $item->attribute = $stage->type;
            $item->acl       = 'open';
            $item->milestone = 0;
            $item->point     = implode(',', $points);
            $item->parallel  = 0;

            $items[] = $item;
        }

        return $items;
    }

    /* Create stages for exist project. */
    foreach($plans as $plan)
    {
        $points = !empty($enabledPoints->{$plan->attribute}) ? $enabledPoints->{$plan->attribute} : array();

        $item               = new stdClass();
        $item->disabled     = $plan->type != 'stage';
        $item->enabled      = $plan->enabled;
        $item->id           = $plan->id;
        $item->type         = $plan->type;
        $item->name         = $plan->name;
        $item->code         = $plan->code;
        $item->PM           = $plan->PM;
        $item->status       = $plan->status;
        $item->percent      = $plan->percent;
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
        $plan->disabled     = !isset($plan->setMilestone);
        $plan->setMilestone = isset($plan->setMilestone) ? $plan->setMilestone : false;
        $plan->point        = implode(',', $points);
        if(in_array($config->edition, array('max', 'ipd')) && $executionType == 'stage')
        {
            $plan->output = empty($plan->output) ? 0 : explode(',', $plan->output);
        }
        $items[] = $plan;
    }

    return $items;
};

/* ZIN: layout. */
jsVar('projectID',        $project->id);
jsVar('productID',        $productID);
jsVar('planID',           $planID);
jsVar('type',             $executionType);
jsVar('project',          $project);
jsVar('plans',            $plans);
jsVar('cropStageTip',     $lang->programplan->cropStageTip);
jsVar('ipdStagePoint',    $project->model == 'ipd' ? $config->review->ipdReviewPoint : array());
jsVar('attributeList',    $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList);
jsVar('reviewedPoints',   $project->model == 'ipd' ? $reviewedPoints : array());
jsVar('reviewedPointTip', $project->model == 'ipd' ? $lang->programplan->reviewedPointTip : '');

featureBar(li
(
    setClass('nav-item'),
    a
    (
        setClass('active'),
        $title
    ),
    $fnGenerateStageByProductList()
));

toolbar
(
    backBtn(set::icon('back'), setClass('primary'), $lang->goback),
);

formBatchPanel
(
    setID('dataform'),
    set::idKey('index'),
    set::onRenderRow(jsRaw('window.onRenderRow')),
    to::headingActions(array($fnGenerateSubPlanManageFields())),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'createFields')),
    set::customUrlParams("module=programplan&section=$section&key=$customKey"),
    set::items($fnGenerateFields()),
    set::sortable(true),
    set::data($fnGenerateDefaultData()),
    $app->session->projectPlanList ? set::actions(array('submit', array('text' => $lang->cancel, 'url' => $app->session->projectPlanList))) : null,
    on::change('[name^="enabled"]', 'changeEnabled(e.target)'),
    ($project->model == 'ipd' && !$planID) ? set::maxRows(count($fnGenerateDefaultData())) : null,
);
