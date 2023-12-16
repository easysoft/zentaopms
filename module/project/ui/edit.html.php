<?php
namespace zin;

jsVar('model', $project->model);
jsVar('labelClass', $config->project->labelClass);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('parentBudget', $lang->project->parentBudget);
jsVar('budgetUnitLabel', $lang->project->tenThousandYuan);
jsVar('budgetUnitValue', $config->project->budget->tenThousand);
jsVar('LONG_TIME', LONG_TIME);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('unmodifiableProducts', $unmodifiableProducts);
jsVar('unmodifiableBranches', $unmodifiableBranches);
jsVar('unmodifiableMainBranches', $unmodifiableMainBranches);
jsVar('multiBranchProducts', $multiBranchProducts);
jsVar('errorSameProducts', $lang->project->errorSameProducts);
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);
jsVar('ignore', $lang->project->ignore);
jsVar('unLinkProductTip', $lang->project->unLinkProductTip);
jsVar('allProducts', $allProducts);
jsVar('branchGroups', $branchGroups);
jsVar('programTip', $lang->program->tips);
jsVar('projectID', $project->id);
jsVar('from', $from);

$projectModelItems = array();
foreach($lang->project->modelList as $key => $text)
{
    if(empty($key)) continue;

    $projectModelItems[] = array
    (
        'active'     => ($key == $model),
        'class'      => 'model-drop',
        'data-key'   => $key,
        'data-value' => $text,
        'text'       => $text,
    );
}

$currency       = $parentProgram ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$labelClass     = $config->project->labelClass[$model];
$stageByClass   = ($project->stageBy == 'product' and count($linkedProducts) < 2) ? 'hidden' : '';
$disableStageBy = !empty($executions) ? true : false;
$disableParent  = false;
$delta          = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;
if(!isset($programList[$project->parent]))
{
    $disableParent = true;
    $programList   = array($project->parent => $program->name);
}

/* Build linked products and plans form row. */
if($linkedProducts)
{
    $i = 0;
    foreach($linkedProducts as $product)
    {
        $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);
        $plans     = isset($productPlans[$product->id]) ? $productPlans[$product->id] : array();
        $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();
        $productGroup[] = formRow
        (
            setClass('productBox'),
            formGroup
            (
                set::width($hasBranch ? '1/4' : '1/2'),
                set('id', 'linkProduct'),
                set::required(true),
                $hasBranch ? setClass('has-branch') : null,
                $i == 0 ? set::label($lang->project->manageProducts) : set::label(''),
                inputGroup
                (
                    div
                    (
                        setClass('grow'),
                        picker
                        (
                            set::name("products[$i]"),
                            set::value($product->id),
                            set::items($allProducts),
                            set::last($product->id),
                            $hasBranch ? set::lastBranch(implode(',', $product->branches)) : null
                        )
                    )
                )
            ),
            formGroup
            (
                set::width('1/4'),
                $hasBranch ? null : setClass('hidden'),
                inputGroup
                (
                    $lang->product->branchName['branch'],
                    picker
                    (
                        set::name("branch[$i][]"),
                        set::multiple(true),
                        set::items($branches),
                        set::value(implode(',', $product->branches))
                    )
                )
            ),
            formGroup
            (
                set::width('1/2'),
                inputGroup
                (
                    set::id("plan{$i}"),
                    $lang->project->associatePlan,
                    picker
                    (
                        set::name("plans[$product->id][]"),
                        set::items($plans),
                        set::value(implode(',', $product->plans)),
                        set::multiple(true)
                    )
                )
            ),
            formGroup
            (
                div
                (
                    setClass('pl-2 flex self-center'),
                    btn
                    (
                        setClass('btn ghost addLine'),
                        icon('plus')
                    ),
                    btn
                    (
                        setClass('btn ghost removeLine'),
                        icon('trash'),
                        $i == 0 ? set::disabled(true) : null
                    )
                )
            )
        );

        $i ++;
    }
}

useData('title', null);

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $title,
        $disableModel ?
        btn
        (
            set::id('project-model'),
            setClass("$labelClass h-5 px-2"),
            zget($lang->project->modelList, $model, '')
        ) :
        dropdown
        (
            btn
            (
                set::id('project-model'),
                setClass("$labelClass h-5 px-2"),
                zget($lang->project->modelList, $model, '')
            ),
            set::placement('bottom'),
            set::menu(array('style' => array('color' => 'var(--color-fore)'))),
            set::items($projectModelItems)
        )
    )),
    on::click('.addLine', 'addNewLine'),
    on::click('.removeLine', 'removeLine'),
    on::click('.project-type-1', 'changeType(1)'),
    on::click('.project-type-0', 'changeType(0)'),
    on::click('.project-stageBy-0', 'changeStageBy(0)'),
    on::click('.project-stageBy-1', 'changeStageBy(1)'),
    on::change('#parent', 'setParentProgram'),
    on::change('#parent, #budget', "checkBudget({$project->id})"),
    on::change('#begin, [name=delta]', 'computeEndDate'),
    on::change('#begin, #end', 'computeWorkDays'),
    on::change('#begin, #end, #parent', 'checkDate'),
    on::change('[name^=products]', 'productChange'),
    on::change('[name^=branch]', 'branchChange'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=newProduct]', 'addProduct'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::id('parent'),
            set::label($lang->project->parent),
            picker
            (
                set::name('parent'),
                set::value($project->parent),
                set::disabled($disableParent),
                set::items($programList),
                set::required(true)
            )
        ),
        $disableParent ? formHidden('parent', $project->parent) : null,
        formGroup
        (
            set::width('1/2'),
            div
            (
                setClass('pl-2 flex self-center'),
                setStyle(array('color' => 'var(--form-label-color)')),
                icon
                (
                    'help',
                    set('data-toggle', 'tooltip'),
                    set('id', 'programHover')
                )
            )
        ),
        formGroup
        (
            set::name('model'),
            set::value($project->model),
            set::control('hidden')
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::value($project->name),
        set::label($lang->project->name),
        set::strong(true)
    ),
    (isset($config->setCode) && $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::value($project->code),
        set::label($lang->project->code),
        set::strong(true)
    ) : null,
    (in_array($model, array('scrum', 'kanban'))) ? formGroup
    (
        set::width('1/2'),
        set::name('multiple'),
        set::label($lang->project->multiple),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->project->multipleList),
        set::value($project->multiple),
        set::disabled(true)
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->project->type),
        inputGroup
        (
            set::seg(true),
            btn
            (
                $project->hasProduct ? setClass("primary-pale project-type-1") : setClass('project-type-1'),
                set::disabled(true),
                $lang->project->projectTypeList[1]
            ),
            btn
            (
                !$project->hasProduct ? setClass("primary-pale project-type-0") : setClass('project-type-0'),
                set::disabled(true),
                $lang->project->projectTypeList[0]
            )
        ),
        formHidden('hasProduct', $project->hasProduct)
    ),
    formGroup
    (
        set::width('1/4'),
        set::label($lang->project->PM),
        picker
        (
            set::name('PM'),
            set::value($project->PM),
            set::items($PMUsers)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->project->budget),
            inputGroup
            (
                set::prefix(zget($lang->project->currencySymbol, $currency)),
                set::prefixWidth('icon'),
                input(set::name('budget'), set::value($project->budget)),
                $parentProgram ? formHidden('budgetUnit', $parentProgram->budgetUnit) : picker(
                    set::name('budgetUnit'),
                    set::items($budgetUnitList),
                    set::value($project->budgetUnit),
                    set::width('200px'),
                    set::required(true)
                )
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::name('future'),
            setClass('items-center'),
            set::control(array('type' => 'checkList', 'inline' => true)),
            set::items(array('1' => $lang->project->future))
        )
    ),
    formRow
    (
        setID('budgetTip'),
        setClass('hidden'),
        formGroup
        (
            set::label(''),
            span(setClass('text-warning'), html($lang->project->budgetOverrun)),
            a(setClass('underline text-warning'), set::href('javascript:;'), on::click("ignoreTip('budgetTip')"), $lang->project->ignore)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->planDate),
            set::required(true),
            inputGroup
            (
                datePicker
                (
                    set::name('begin'),
                    set('id', 'begin'),
                    set::value($project->begin),
                    set::placeholder($lang->project->begin),
                    set::required(true)
                ),
                $lang->project->to,
                datePicker
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::value($project->end),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    $project->end == LONG_TIME ? setClass('hidden') : null
                ),
                inputControl
                (
                    setClass('has-suffix-icon w-full' . ($project->end != LONG_TIME ? ' hidden' : '')),
                    to::suffix(icon('calendar')),
                    input
                    (
                        set::value($lang->project->longTime),
                        set::disabled(true)
                    )
                )
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            radioList
            (
                set::name('delta'),
                set::value($delta),
                set::inline(true),
                set::items($lang->project->endList)
            )
        )
    ),
    formRow
    (
        setID('dateTip'),
        setClass('hidden'),
        formGroup
        (
            set::label(''),
            span(setID('beginLess'), setClass('text-warning hidden'), html($lang->project->beginLessThanParent)),
            span(setID('endGreater'), setClass('text-warning hidden'), html($lang->project->endGreatThanParent)),
            a(setClass('underline text-warning'), set::href('javascript:;'), on::click("ignoreTip('dateTip')"), $lang->project->ignore)
        )
    ),
    formRow
    (
        setClass($delta == 999 ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->project->days),
            set::width('1/4'),
            inputControl
            (
                setClass('has-suffix'),
                input
                (
                    set::name('days'),
                    set::value($project->days),
                    set::required(true)
                ),
                div
                (
                    setClass('input-control-suffix z-50'),
                    $lang->project->day
                )
            )
        )
    ),
    empty($linkedProducts) ?
    formRow
    (
        setClass('productBox'),
        formGroup
        (
            set::width('1/2'),
            set('id', 'linkProduct'),
            set::label($lang->project->manageProducts),
            set::required(true),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    picker
                    (
                        set::name('products[0]'),
                        set::items($allProducts)
                    )
                )
            )
        ),
        formGroup
        (
            set::width('1/4'),
            setClass('hidden'),
            inputGroup
            (
                $lang->product->branchName['branch'],
                picker
                (
                    set::name("branch[0][]"),
                    set::items(array())
                )
            )
        ),
        formGroup
        (
            set::width('1/2'),
            inputGroup
            (
                set::id("plan0"),
                $lang->project->associatePlan,
                picker
                (
                    set::name('plans[0][]'),
                    set::items(array())
                )
            ),
            div
            (
                setClass('pl-2 flex self-center line-btn'),
                btn
                (
                    setClass('btn ghost addLine'),
                    icon('plus')
                ),
                btn
                (
                    setClass('btn ghost removeLine'),
                    icon('trash')
                )
            )
        ),
    ) : $productGroup,
    ($model == 'waterfall' || $model == 'waterfallplus') ? formRow
    (
        setClass("stageBy $stageByClass"),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->stageBy),
            inputGroup
            (
                set::seg(true),
                btn
                (
                    setClass('primary-pale project-stageBy-0'),
                    set::disabled($disableStageBy),
                    $lang->project->stageByList[0]
                ),
                btn
                (
                    setClass('project-stageBy-1'),
                    set::disabled($disableStageBy),
                    $lang->project->stageByList[1]
                )
            ),
            formHidden('stageBy', $project->stageBy)
        )
    ) : null,
    formGroup
    (
        set::label($lang->project->desc),
        editor
        (
            set::name('desc'),
            html($project->desc)
        )
    ),
    formRow
    (
        set::id('aclList'),
        formGroup
        (
            set::name('acl'),
            set::label($lang->project->acl),
            set::control('radioList'),
            set::items($lang->project->aclList),
            $programID ? set::items($lang->project->subAclList) : set::items($lang->project->aclList),
            set::value($project->acl)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->whitelist),
        picker
        (
            set::name('whitelist[]'),
            set::value($project->whitelist),
            set::items($users),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('auth'),
        set::value($project->auth),
        set::label($lang->project->auth),
        set::control('radioList'),
        set::items($lang->project->authList),
        set::value('extend')
    ),
    formHidden('multiple', $project->multiple)
);

useData('title', $title);

render();
