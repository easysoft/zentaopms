<?php
namespace zin;

$parentID = isset($parentProgram->id) ? $parentProgram->id : 0;
$currency = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$aclList  = $parentProgram ? $lang->program->subAclList : $lang->program->aclList;
useData('title', $parentID ? $lang->program->children : $lang->program->create);

form
(
    formGroup
    (
        set::width('1/2'),
        set::name('parent'),
        set::label($lang->program->parent),
        set::disabled($parentID),
        set::value($parentID),
        set::options($parents),
        on::change('setBudgetTipsAndAclList')
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::strong(true),
        set::label($lang->program->name)
    ),
    formGroup
    (
        set::width('1/4'),
        set::name('PM'),
        set::label($lang->program->PM),
        set::options($pmUsers)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('budget'),
            set::label($lang->program->budget),
            set::control(['type' => 'inputControl', 'prefix' => $lang->project->currencySymbol[$currency], 'prefixWidth' => 'icon']),
            on::change('budgetOverrunTips'),
            h::input(set(['type' => 'hidden', 'name' => 'budgetUnit', 'value' => $currency]))
        ),
        formGroup
        (
            set::name('future'),
            set::value('1'),
            set::control(['type' => 'checkbox', 'rootClass' => 'ml-4', 'text' => $lang->project->future])
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->dateRange),
            set::required(true),
            inputGroup
            (
                input
                (
                    set::name('begin'),
                    set::value(date('Y-m-d')),
                    set::placeholder($lang->project->begin),
                    set::required(true),
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                input
                (
                    set::name('end'),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    on::change('outOfDateTip')
                ),
            )
        ),
        formGroup
        (
            set::name('delta'),
            set::class('pl-4'),
            set::control(['type' => 'radioList', 'inline' => true, 'rootClass' => 'ml-4', 'options' => $lang->program->endList]),
            on::change('computeEndDate')
        ),
    ),
    /* TODO: printExtendFields() */
    formGroup
    (
        set::name('desc'),
        set::label($lang->program->desc),
        set::control('editor')
    ),
    formHidden('status', 'wait'),
    formGroup
    (
        set::name('acl'),
        set::label($lang->project->acl),
        set::value('private'),
        set::options($aclList),
        set::control('radioList'),
        on::change('setWhite')
    )
);

render();
