<?php
namespace zin;

jsVar('model', $model);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);
jsVar('ignore', $lang->project->ignore);
jsVar('multiBranchProducts', $multiBranchProducts);
jsVar('errorSameProducts', $lang->project->errorSameProducts);
jsVar('copyProjectID', $copyProjectID);
jsVar('nameTips', $lang->project->copyProject->nameTips);
jsVar('codeTips', $lang->project->copyProject->codeTips);
jsVar('endTips', $lang->project->copyProject->endTips);
jsVar('daysTips', $lang->project->copyProject->daysTips);
jsVar('programTip', $lang->program->tips);

$projectModelItems = array();
foreach($lang->project->modelList as $key => $text)
{
    if(empty($key)) continue;

    $projectModelItems[] = array
    (
        'active'    => ($key == $model),
        'url'       => $this->createLink("project", "create", "model=$key&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"),
        'text'      => $text,
        'data-type' => 'ajax'
    );
}

$productsBox = array();
if(!empty($products))
{
    $i = 0;
    foreach($products as $product)
    {
        $hasBranch = $product->type != 'normal' && isset($branchGroups[$product->id]);
        $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();
        $plans     = isset($copyProject->productPlans[$product->id]) ? $copyProject->productPlans[$product->id] : array();
        $productsBox[] = formRow
        (
            setClass('productBox'),
            formGroup
            (
                set::width($hasBranch ? '1/4' : '1/2'),
                set('id', 'linkProduct'),
                set::required(true),
                $i == 0 ? set::label($lang->project->manageProducts) : set::label(''),
                inputGroup
                (
                    div
                    (
                        setClass('grow'),
                        select
                        (
                            set::name("products[$i]"),
                            set::value($product->id),
                            set::items($allProducts),
                            set::last($product->id),
                            set::required(true)
                        )
                    )
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
                        set::name("branch[$i][]"),
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
                    select
                    (
                        set::name("plans[$product->id][]"),
                        set::items($plans),
                        set::value($product->plans)
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
                        icon('trash'),
                        $i == 0 ? set::disabled(true) : null
                    )
                )
            )
        );

        $i ++;
    }
}

$currency   = $parentProgram ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$labelClass = $config->project->labelClass[$model];
formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $title,
        dropdown
        (
            btn
            (
                set::id('project-model'),
                setClass("$labelClass h-5 px-2"),
                zget($lang->project->modelList, $model, '')
            ),
            set::trigger('click'),
            set::placement('bottom'),
            set::menu(array('style' => array('color' => 'var(--color-fore)'))),
            set::items($projectModelItems)
        )
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
            $lang->project->copy
        )
    ),
    on::click('.addLine', 'addNewLine'),
    on::click('.removeLine', 'removeLine'),
    on::click('.project-type-1', 'changeType(1)'),
    on::click('.project-type-0', 'changeType(0)'),
    on::click('.project-stageBy-0', 'changeStageBy(0)'),
    on::click('.project-stageBy-1', 'changeStageBy(1)'),
    on::change('[name^=products]', 'productChange'),
    on::change('[name^=branch]', 'branchChange'),
    on::change('#parent', 'setParentProgram'),
    on::change('[name=multiple]', 'toggleMultiple'),
    on::change('#begin', 'computeWorkDays'),
    on::change('#end', 'computeWorkDays'),
    on::change('[name=delta]', 'setDate'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=newProduct]', 'addProduct'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->parent),
            set::id('parent'),
            picker
            (
                set::name('parent'),
                set::items($programList),
                set::value($programID),
                set::required(true)
            )
        ),
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
            set::value($model),
            set::control('hidden')
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->project->name),
        set::value($copyProjectID ? $copyProject->name : ''),
        set::strong(true)
    ),
    (isset($config->setCode) && $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::label($lang->project->code),
        set::value($copyProjectID ? $copyProject->code : ''),
        set::strong(true)
    ) : null,
    (in_array($model, array('scrum', 'kanban'))) ? formGroup
    (
        set::width('1/2'),
        set::name('multiple'),
        set::label($lang->project->multiple),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->project->multipleList),
        set::disabled($copyProjectID),
        set::value('1'),
        $copyProjectID ? formHidden('multiple', $copyProject->multiple) : null
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
                setClass('primary-pale project-type-1'),
                set::disabled($copyProjectID),
                $lang->project->projectTypeList[1]
            ),
            btn(
                setClass('project-type-0'),
                set::disabled($copyProjectID),
                $lang->project->projectTypeList[0]
            )
        ),
        formHidden('hasProduct', $copyProjectID ? $copyProject->hasProduct : 1)
    ),
    formGroup
    (
        set::width('1/4'),
        set::name('PM'),
        set::label($lang->project->PM),
        set::items($pmUsers)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('budget'),
            set::label($lang->project->budget),
            set::control(array
            (
                'type'        => 'inputControl',
                'prefix'      => zget($lang->project->currencySymbol, $currency),
                'prefixWidth' => 'icon',
                'suffix'      => $lang->project->tenThousandYuan,
                'suffixWidth' => 60
            )),
            $parentProgram ? null : formHidden('budgetUnit', $config->project->defaultCurrency)
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
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->planDate),
            set::required(true),
            inputGroup
            (
                datepicker
                (
                    set::name('begin'),
                    set('id', 'begin'),
                    set::value(date('Y-m-d')),
                    set::required(true)
                ),
                $lang->project->to,
                datepicker
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::placeholder($lang->project->end),
                    set::required(true)
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
                set::inline(true),
                set::items($lang->project->endList)
            )
        )
    ),
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
                set::required(true)
            ),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->project->day
            )
        )
    ),
    !empty($products) ? $productsBox :
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
                ),
                div
                (
                    setClass('flex items-center pl-2 clip newProductBox'),
                    checkbox
                    (
                        set::name('newProduct'),
                        set::text($lang->project->newProduct)
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
                div
                (
                    setClass('grow'),
                    picker
                    (
                        set::name("branch[0][]"),
                        set::items(array())
                    )
                ),
                div
                (
                    setClass('flex items-center pl-2 clip'),
                    checkbox
                    (
                        set::name('newProduct'),
                        set::text($lang->project->newProduct)
                    )
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
                    icon('trash'),
                    empty($i) ? set::disabled(true) : null
                )
            )
        )
    ),
    formRow
    (
        set::id('addProductBox'),
        setClass('hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->addProduct),
            set::required(true),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    input(set::name('productName'))
                ),
                div
                (
                    setClass('flex items-center pl-2'),
                    checkbox
                    (
                        set::name('newProduct'),
                        set::text($lang->project->newProduct)
                    )
                )
            )
        )
    ),
    ($model == 'waterfall' || $model == 'waterfallplus') ? formRow
    (
        setClass('stageBy hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->stageBy),
            inputGroup
            (
                set::seg(true),
                btn(
                    setClass('primary-pale project-stageBy-0'),
                    set::disabled($copyProjectID),
                    $lang->project->stageByList[0]
                ),
                btn
                (
                    setClass('project-stageBy-1'),
                    set::disabled($copyProjectID),
                    $lang->project->stageByList[1]
                )
            ),
            formHidden('stageBy', $copyProjectID ? $copyProject->stageBy : '0')
        )
    ) : null,
    formGroup
    (
        set::name('desc'),
        set::label($lang->project->desc),
        set::control('editor'),
        set::placeholder($lang->project->editorPlaceholder)
    ),
    formRow
    (
        set::id('aclList'),
        formGroup
        (
            set::name('acl'),
            set::label($lang->project->acl),
            set::control('radioList'),
            $programID ? set::items($lang->project->subAclList) : set::items($lang->project->aclList),
            set::value($copyProjectID ? $copyProject->acl : 'private')
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->whitelist),
        picker
        (
            set::name('whitelist[]'),
            set::items($users),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('auth'),
        set::label($lang->project->auth),
        set::control('radioList'),
        set::items($lang->project->authList),
        set::value($copyProjectID ? $copyProject->auth : 'extend')
    )
);

$copyProjectsBox = array();
foreach($copyProjects as $id => $name)
{
    $copyProjectsBox[] = btn(
        setClass('project-block justify-start'),
        setClass($copyProjectID == $id ? 'primary-outline' : ''),
        set('data-id', $id),
        set('data-pinyin', zget($copyPinyinList, $name, '')),
        icon
        (
            setClass('text-gray'),
            $lang->icons['project']
        ),
        span($name)
    );
}

modalTrigger
(
    modal
    (
        set::id('copyProjectModal'),
        to::header
        (
            span
            (
                h4
                (
                    set::className('copy-title'),
                    $lang->project->copyTitle
                )
            ),
            input
            (
                set::name('projectName'),
                set::placeholder($lang->project->searchByName)
            )
        ),
        div
        (
            set::id('copyProjects'),
            setClass('flex items-center flex-wrap'),
            $copyProjectsBox
        )
    )
);

render();
