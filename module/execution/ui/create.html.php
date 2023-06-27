<?php
declare(strict_types=1);
/**
 * The create view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('methodTip', $lang->execution->agileplusMethodTip);
jsVar('projectID', $projectID);
jsVar('weekend', $config->execution->weekend);
jsVar('errorSameProducts', $lang->execution->errorSameProducts);
jsVar('errorSameBranches', $lang->execution->errorSameBranches);
jsVar('isStage', $isStage);
jsVar('copyExecutionID', $copyExecutionID);
jsVar('multiBranchProducts', $multiBranchProducts);
jsVar('manageProductsLang', $lang->project->manageProducts);
jsVar('manageProductPlanLang', $lang->project->manageProductPlan);

$methodBox = null;
if(!empty($project->model) && $project->model == 'agileplus')
{
    unset($lang->execution->typeList['stage'], $lang->execution->typeList['']);
    $methodBox = formRow(
        formGroup
        (
            set::width('1/2'),
            set::name('type'),
            set::label($lang->execution->method),
            set::items($lang->execution->typeList),
            set::value($type),
            on::change('setType'),
        ),
        formGroup
        (
            set::width('1/2'),
            div
            (
                setClass('pl-2 flex self-center'),
                setStyle(['color' => 'var(--form-label-color)']),
                icon
                (
                    'help',
                    set('data-toggle', 'tooltip'),
                    set('id', 'methodHover'),
                )
            )
        )
    );
}

$typeBox = null;
if((empty($project) or $project->model != 'kanban') and $type != 'kanban')
{
    $typeBox = formRow(
        formGroup
        (
            set::width('1/2'),
            set::label($showExecutionExec ? $lang->execution->execType : $lang->execution->type),
            select
            (
                set::id($isStage ? 'attribute' : 'lifetime'),
                set::name($isStage ? 'attribute' : 'lifetime'),
                set::items($isStage ? $lang->stage->typeList : $lang->execution->lifeTimeList),
                set::required(true),
                !$isStage ? on::change('showLifeTimeTips') : null
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::id('lifeTimeTips'),
            set::class('text-gray hidden'),
            span($lang->execution->typeDesc),
        ),
    );
}

$productsBox = null;
if(isset($project->hasProduct) and !empty($project->hasProduct) and $products)
{
    $i = 0;
    foreach($products as $product)
    {
        $hasBranch = $product->type != 'normal' && isset($branchGroups[$product->id]);
        $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();
        if(!isset($linkedBranches)) $branchIdList = isset($product->branches) ? join(',', $product->branches) : '';
        if(isset($linkedBranches))  $branchIdList = !empty($linkedBranches[$product->id]) ? $linkedBranches[$product->id] : '';
        if(empty($productID) || (!empty($productID) || $productID != $product->id))
        {
            $plans  = isset($productPlans[$product->id]) ? $productPlans[$product->id] : array();
            $planID = isset($product->plans) ? $product->plans : '';
        }
        else
        {
            $plans  = !empty($productPlan) ? $productPlan : array();
            $planID = isset($productPlan[$plan->id]) ? $plan->id : '';
        }

        $productsBox[] = formRow
        (
            setClass('productsBox'),
            formGroup
            (
                set::width($hasBranch ? '1/4' : '1/2'),
                setClass('linkProduct'),
                $i == 0 ? set::label($lang->project->manageProducts) : set::label(''),
                inputGroup
                (
                    div
                    (
                        setClass('grow'),
                        select
                        (
                            set::id("products{$i}"),
                            set::name("products[$i]"),
                            set::value($product->id),
                            set::items($allProducts),
                            set::last($product->id),
                            set::disabled($isStage && $project->stageBy == 'project'),
                            set::required(true),
                            on::change('loadBranches'),
                            $isStage && $project->stageBy == 'project' ? formHidden("products[$i]", $product->id) : null,
                        )
                    ),
                )
            ),
            formGroup
            (
                set::width('1/4'),
                setClass('ml-px'),
                $hasBranch ? null : setClass('hidden'),
                inputGroup
                (
                    $lang->product->branchName['branch'],
                    select
                    (
                        set::id("branch{$i}"),
                        set::name("branch[$i][]"),
                        set::items($branches),
                        set::value($branchIdList),
                        set::disabled($isStage && $project->stageBy == 'project'),
                        on::change("loadPlans('#products{$i}', this)")
                    )
                ),
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->project->associatePlan),
                set::class('planBox'),
                inputGroup
                (
                    set::id("plan{$i}"),
                    select
                    (
                        set::name("plans[$product->id][]"),
                        set::items($plans),
                        set::value($planID)
                    )
                )
            ),
            $isStage && $project->stageBy == 'project' ? null : formGroup
            (
                set::width('1/6'),
                div
                (
                    setClass('pl-2 flex self-center'),
                    btn
                    (
                        setClass('btn btn-link addLine'),
                        on::click('addNewLine'),
                        icon('plus')
                    ),
                    btn
                    (
                        setClass('btn btn-link removeLine'),
                        setClass($i == 0 ? 'hidden' : ''),
                        icon('trash'),
                        on::click('removeLine'),
                    ),
                )
            )
        );

        $i ++;
    }
}
elseif(!empty($project) and empty($project->hasProduct) and !in_array($project->model, array('waterfall', 'kanban', 'waterfallplus')))
{
    $planProductID = current(array_keys($allProducts));
    $productsBox[] = formRow(
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->linkPlan),
            set('id', 'plansBox'),
            set::class('planBox'),
            select
            (
                set::name("plans[{$planProductID}][]"),
                set::items(isset($productPlan) ? $productPlan : array()),
                formHidden('products[]', $planProductID),
                formHidden('branch[0][0]', 0),
            )
        ),
    );
}
else
{
    $productsBox [] = formRow
    (
        setClass('productsBox'),
        formGroup
        (
            set::width('1/2'),
            setClass('linkProduct'),
            set::label($lang->project->manageProducts),
            select
            (
                set::id('products0'),
                set::name('products[0]'),
                set::items($allProducts),
                set::required(true),
                on::change('loadBranches')
            )
        ),
        formGroup
        (
            set::width('1/4'),
            setClass('hidden ml-px'),
            inputGroup
            (
                $lang->product->branchName['branch'],
                select
                (
                    set::id('branch0'),
                    set::name('branch[0][]'),
                    set::control('select'),
                    on::change("loadPlans('#products0', this)")
                )
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->associatePlan),
            set::class('planBox'),
            inputGroup
            (
                set::id("plan0"),
                select
                (
                    set::name('plans[0][]'),
                    set::items($productPlan),
                    set::multiple(true)
                )
            )
        ),
        $isStage && $project->stageBy == 'product' ? null : formGroup
        (
            set::width('1/6'),
            div
            (
                setClass('pl-2 flex self-center'),
                btn
                (
                    setClass('btn btn-link addLine'),
                    on::click('addNewLine'),
                    icon('plus')
                ),
                btn
                (
                    setClass('btn btn-link removeLine'),
                    setClass('hidden'),
                    icon('trash'),
                    on::click('removeLine'),
                ),
            ),
        )
    );
}

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $showExecutionExec ? $lang->execution->createExec : $lang->execution->create,
    )),
    to::headingActions
    (
        btn
        (
            setClass('primary-pale'),
            set::icon('copy'),
            set::url('#copyProjectModal'),
            set('data-destoryOnHide', true),
            set('data-toggle', 'modal'),
            $showExecutionExec ? $lang->execution->copyExec : $lang->execution->copy
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('project'),
        set::required(true),
        set::label($lang->execution->projectName),
        set::items($allProjects),
        set::value($projectID),
        on::change('refreshPage'),
    ),
    $methodBox,
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($showExecutionExec ? $lang->execution->execName : $lang->execution->name),
        set::value($name),
    ),
    isset($config->setCode) && $config->setCode == 1 ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::label($showExecutionExec ? $lang->execution->execCode : $lang->execution->code),
        set::value($code),
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->dateRange),
            set::required(true),
            inputGroup
            (
                input
                (
                    set::name('begin'),
                    set::type('date'),
                    set('id', 'begin'),
                    set::value((isset($plan) && !empty($plan->begin) ? $plan->begin : date('Y-m-d'))),
                    set::placeholder($lang->execution->begin),
                    set::required(true),
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                input
                (
                    set::name('end'),
                    set::type('date'),
                    set('id', 'end'),
                    set::value((isset($plan) && !empty($plan->end) ? $plan->end : ''),
                    set::placeholder($lang->execution->end),
                    set::required(true),
                    on::change('computeWorkDays')
                    ),
                )
            ),
        ),
        formGroup
        (
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->execution->endList),
                on::change('computeEndDate'),
            )
        ),
    ),
    formGroup
    (
        set::label($lang->execution->days),
        set::width('1/2'),
        inputGroup
        (
            setClass('has-suffix'),
            input
            (
                set::name('days'),
                set::value(isset($plan) && !empty($plan->begin) ? (helper::workDays($plan->begin, $plan->end) + 1) : ''),
            ),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->execution->day
            )
        )
    ),
    $typeBox,
    $isStage && isset($config->setPercent) && $config->setPercent == 1 ? formGroup
    (
        set::width('1/2'),
        set::name('percent'),
        set::label($lang->stage->percent),
        set::required(true),
    ) : null,
    $productsBox,
    formGroup
    (
        set::label($lang->execution->teamSetting),
        set::strong(true),
    ),
    h::hr(set::class('team-hr')),
    formGroup
    (
        set::width('1/2'),
        set::name('team'),
        set::label($lang->execution->teamname),
        set::value($team),
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->execution->copyTeam),
        select
        (
            set::id('teams'),
            set::name('teams'),
            set::items($teams),
            set::value(empty($copyExecution) ? $projectID : $copyExecutionID),
            set::required(true),
            set('data-placeholder', $lang->execution->copyTeamTip),
            on::change('loadMembers'),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->execution->PM),
            select
            (
                set::name('PM'),
                set::items($pmUsers),
                set::value(empty($copyExecution) ? '' : $copyExecution->PM),
                set::required(true),
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->execution->PO),
            select
            (
                set::name('PO'),
                set::items($poUsers),
                set::value(empty($copyExecution) ? '' : $copyExecution->PO),
                set::required(true),
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->execution->QD),
            select
            (
                set::name('QD'),
                set::items($qdUsers),
                set::value(empty($copyExecution) ? '' : $copyExecution->QD),
                set::required(true),
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->execution->RD),
            select
            (
                set::name('RD'),
                set::items($rdUsers),
                set::value(empty($copyExecution) ? '' : $copyExecution->RD),
                set::required(true),
            )
        ),
    ),
    formGroup
    (
        set::label($lang->execution->team),
        select
        (
            set::name('teamMembers[]'),
            set::items($users),
            set::multiple(true),
        )
    ),
    h::hr(),
    formGroup
    (
        set::name('desc'),
        set::label($showExecutionExec ? $lang->execution->execDesc : $lang->execution->desc),
        set::control('editor'),
    ),
    formRow
    (
        set::id('aclList'),
        formGroup
        (
            set::width('1/2'),
            set::name('acl'),
            set::label($lang->execution->acl),
            set::control('radioList'),
            set::items($lang->execution->aclList),
            set::value($acl),
            on::change('setWhite(this.value)'),
        )
    ),
    formGroup
    (
        set::label($lang->whitelist),
        set::id('whitelistBox'),
        select
        (
            set::name('whitelist[]'),
            set::items($users),
            set::multiple(true),
        )
    ),
);

/* ====== Render page ====== */
render();
