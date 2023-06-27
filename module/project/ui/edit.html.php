<?php
namespace zin;

jsVar('model', $project->model);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('unmodifiableProducts', $unmodifiableProducts);
jsVar('unmodifiableBranches', $unmodifiableBranches);
jsVar('unmodifiableMainBranches', $unmodifiableMainBranches);
jsVar('linkedProjectsTip', $linkedProjectsTip);
jsVar('multiBranchProducts', $multiBranchProducts);
jsVar('errorSameProducts', $lang->project->errorSameProducts);

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

$currency     = $parentProgram ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$primaryLabel = 'primary-pale';

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
                set::width('1/2'),
                set('id', 'linkProduct'),
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
                            on::change('productChange')
                        )
                    ),
                )
            ),
            formGroup
            (
                set::width('1/3'),
                $hasBranch ? null : setClass('hidden'),
                inputGroup
                (
                    $lang->product->branchName['branch'],
                    select
                    (
                        set::name("branch[$i][]"),
                        set::items($branches),
                        set::value(join(',', $product->branches)),
                        on::change('branchChange')
                    )
                ),
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
                )
            ),
            formGroup
            (
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
                        icon('close'),
                        on::click('removeLine'),
                        $i == 0 ? set::disabled(true) : null
                    ),
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
        dropdown
        (
            btn
            (
                set::id('project-model'),
                setClass('secondary-outline h-5 px-2'),
                zget($lang->project->modelList, $model, '')
            ),
            set::trigger('click'),
            set::placement('bottom'),
            set::menuProps(array('style' => array('color' => 'var(--color-fore)'))),
            set::items($projectModelItems),
            on::click('changModel')
        )
    )),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('parent'),
            set::value($project->parent),
            set::label($lang->project->parent),
            set::items($programList),
            on::change('setParentProgram')
        ),
        formGroup
        (
            set::width('1/2'),
            div
            (
                setClass('pl-2 flex self-center'),
                setStyle(['color' => 'var(--form-label-color)']),
                icon('help')
            )
        ),
        formGroup
        (
            set::name('model'),
            set::value($project->model),
            set::control('hidden'),
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::value($project->name),
        set::label($lang->project->name),
        set::strong(true),
    ),
    (!isset($config->setCode) or $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::value($project->code),
        set::label($lang->project->code),
        set::strong(true),
    ) : null,
    (in_array($model, array('scrum', 'kanban'))) ? formGroup
    (
        set::width('1/2'),
        set::name('multiple'),
        set::label($lang->project->multiple),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->project->multipleList),
        set::value($project->multiple),
        set::disabled(true),
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
                $project->hasProduct ? setClass("$primaryLabel project-type-1") : setClass('project-type-1'),
                on::click('changeType(1)'),
                $lang->project->projectTypeList[1]
            ),
            btn(
                !$project->hasProduct ? setClass("$primaryLabel project-type-0") : setClass('project-type-0'),
                on::click('changeType(0)'),
                $lang->project->projectTypeList[0]
            )
        ),
        formHidden('hasProduct', $project->hasProduct)
    ),
    formGroup
    (
        set::width('1/4'),
        set::name('PM'),
        set::control('select'),
        set::value($project->PM),
        set::label($lang->project->PM),
        set::items($PMUsers)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('budget'),
            set::value($project->budget),
            set::label($lang->project->budget),
            set::control(array
            (
                'type'        => 'inputControl',
                'prefix'      => zget($lang->project->currencySymbol, $currency),
                'prefixWidth' => 'icon',
                'suffix'      => $lang->project->tenThousandYuan,
                'suffixWidth' => 60,
            )),
            $parentProgram ? null : formHidden('budgetUnit', $config->project->defaultCurrency)
        ),
        formGroup
        (
            set::width('1/4'),
            set::name('future'),
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
                input
                (
                    set::name('begin'),
                    set::type('date'),
                    set('id', 'begin'),
                    set::value($project->begin),
                    set::placeholder($lang->project->begin),
                    set::required(true),
                    /* TODO associate event */
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                input
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::type('date'),
                    set::value($project->end),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    /* TODO associate event */
                    on::change('computEndDate(this.value)')
                ),
            )
        ),
        formGroup
        (
            set::width('1/2'),
            radioList
            (
                on::change('setDate'),
                set::name('delta'),
                set::inline(true),
                set::items($lang->project->endList)
            )
        ),
    ),
    formGroup
    (
        set::label($lang->project->days),
        set::width('1/4'),
        inputGroup
        (
            setClass('has-suffix'),
            input
            (
                set::name('days'),
                set::value($project->days),
                set::required(true),
            ),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->project->day
            )
        )
    ),
    empty($linkedProducts) ? null : $productGroup,
    formGroup
    (
        set::name('desc'),
        set::value($project->desc),
        set::label($lang->project->desc),
        set::control('editor'),
        set::placeholder($lang->project->editorPlaceholder)
    ),
    /* TODO printExtendFields() */
    formGroup
    (
        set::width('1/2'),
        set::name('acl'),
        set::label($lang->project->acl),
        set::control('radioList'),
        set::items($lang->project->aclList),
        set::value($project->acl)
    ),
    /* TODO add events */
    formGroup
    (
        set::width('1/2'),
        set::name('whitelist[]'),
        set::label($lang->whitelist),
        set::value($project->whitelist),
        set::items($users),
        set::control(['type' => 'select', 'multiple' => false])
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
);

useData('title', $title);

render();
