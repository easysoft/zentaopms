<?php
namespace zin;

jsVar('model', $model);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('parentBudget', $lang->project->parentBudget);
jsVar('budgetUnitLabel', $lang->project->tenThousandYuan);
jsVar('budgetUnitValue', $config->project->budget->tenThousand);
jsVar('LONG_TIME', LONG_TIME);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);
jsVar('ignore', $lang->project->ignore);
jsVar('multiBranchProducts', $multiBranchProducts);
jsVar('errorSameProducts', $lang->project->errorSameProducts);
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
    on::click('[type=submit]', 'removeAllTips'),
    on::click('#name, #code, #end, #days', 'removeTips'),
    on::change('#end, #days', 'removeTips'),
    on::change('#parent', 'setParentProgram'),
    on::change('#parent, #budget', 'checkBudget(0)'),
    on::change('#begin, [name=delta]', 'computeEndDate'),
    on::change('#begin, #end', 'computeWorkDays'),
    on::change('[name^=products]', 'productChange'),
    on::change('[name^=branch]', 'branchChange'),
    on::change('[name=multiple]', 'toggleMultiple'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=newProduct]', 'addProduct'),
    $config->systemMode != 'light' ? on::change('#begin, #end, #parent', 'checkDate') : null,
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
        set::label($lang->project->name),
        set::required(true),
        set::strong(true),
        input
        (
            set::name('name'),
            set::value($copyProjectID ? $copyProject->name : ''),
            $copyProjectID ? setClass('has-warning') : null
        ),
        $copyProjectID ? div(setClass('text-warning'), $lang->project->copyProject->nameTips) : null
    ),
    (isset($config->setCode) && $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::label($lang->project->code),
        set::required(true),
        set::strong(true),
        input
        (
            set::name('code'),
            set::value($copyProjectID ? $copyProject->code : ''),
            $copyProjectID ? setClass('has-warning') : null
        ),
        $copyProjectID ? div(setClass('text-warning'), $lang->project->copyProject->codeTips) : null
    ) : null,
    (in_array($model, array('scrum', 'kanban'))) ? formGroup
    (
        set::width('1/2'),
        set::name('multiple'),
        set::label($lang->project->multiple),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->project->multipleList),
        set::disabled($copyProjectID),
        set::value($copyProjectID ? $copyProject->multiple : 1)
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
            set::width('1/3'),
            set::label($lang->project->budget),
            inputGroup
            (
                set::prefix(zget($lang->project->currencySymbol, $currency)),
                set::prefixWidth('icon'),
                input(set::name('budget')),
                $parentProgram ? formHidden('budgetUnit', $parentProgram->budgetUnit) : picker(
                    set::name('budgetUnit'),
                    set::items($budgetUnitList),
                    set::value($config->project->defaultCurrency),
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
                datepicker
                (
                    setID('begin'),
                    set::name('begin'),
                    set::value(date('Y-m-d')),
                    set::required(true)
                ),
                $lang->project->to,
                datepicker
                (
                    setID('end'),
                    set::name('end'),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    $copyProjectID ? setClass('has-warning') : null
                ),
                inputControl
                (
                    setClass('has-suffix-icon w-full hidden'),
                    to::suffix(icon('calendar')),
                    input
                    (
                        set::value($lang->project->longTime),
                        set::disabled(true)
                    )
                )
            ),
            $copyProjectID ? div(setClass('text-warning'), $lang->project->copyProject->endTips) : null
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
    formGroup
    (
        set::label($lang->project->days),
        set::width('1/4'),
        inputControl
        (
            setClass('has-suffix'),
            input(set::name('days'), $copyProjectID ? setClass('has-warning') : null),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->project->day
            )
        ),
        $copyProjectID ? div(setClass('text-warning'), $lang->project->copyProject->daysTips) : null
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
            inputGroup
            (
                $programID ? setClass('required') : null,
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
                    setClass('flex items-center px-2 clip newProductBox'),
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
        set::control('editor')
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
if(!empty($copyProjects))
{
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
}
else
{
    $copyProjectsBox[] = div
        (
            setClass('inline-flex items-center w-full bg-lighter h-12 mt-2 mb-8'),
            icon('exclamation-sign icon-2x pl-2 text-warning'),
            span
            (
                set::className('font-bold ml-2'),
                $lang->project->copyNoProject
            )
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
