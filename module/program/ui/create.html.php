<?php
namespace zin;

$parentID          = $parentProgram->id ?? 0;
$currency          = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$aclList           = $parentProgram ? $lang->program->subAclList : $lang->program->aclList;
$budgetPlaceholder = $parentProgram ? $lang->program->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $budgetLeft : '';
$budgetAvaliable   = !$parentID || $budgetLeft;

jsVar('LONG_TIME', LONG_TIME);
jsVar('lang', ['budgetOverrun' => $lang->project->budgetOverrun, 'currencySymbol' => $lang->project->currencySymbol, 'ignore' => $lang->program->ignore]);
jsVar('weekend', $config->execution->weekend);

set::title($parentID ? $lang->program->children : $lang->program->create);

formPanel
(
    on::change('#parent', 'onParentChange'),
    on::change('#budget', 'onBudgetChange'),
    on::change('#future', 'onFutureChange'),
    on::change('#acl',    'onAclChange'),
    formGroup
    (
        set::width('1/2'),
        set::name('parent'),
        set::label($lang->program->parent),
        set::disabled($parentID),
        set::value($parentID),
        set::items($parents),
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
        set::items($pmUsers)
    ),
    formRow
    (
        set::id('budgetRow'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->program->budget),
            inputGroup
            (
                set::seg(true),
                input
                (
                    set::name('budget'),
                    set::placeholder($budgetPlaceholder),
                    set::disabled(!$budgetAvaliable),
                    set('data-budget-left', $budgetLeft),
                    set('data-currency-symbol', $parentProgram ? zget($lang->project->currencySymbol, $parentProgram->budgetUnit) : NULL),
                ),
                select
                (
                    zui::width('1/3'),
                    set::name('budgetUnit'),
                    set::disabled($parentID || !$budgetAvaliable),
                    set::items($budgetUnitList),
                    set::value($currency)
                )
            )
        ),
        formHidden('budgetUnit', $currency),
        formGroup
        (
            set::name('future'),
            set::value('1'),
            set::disabled(!$budgetAvaliable),
            set::control(['type' => 'checkbox', 'rootClass' => 'ml-4', 'text' => $lang->project->future, 'checked' => !$budgetAvaliable])
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
                set::seg(true),
                input
                (
                    set::type('date'),
                    set::name('begin'),
                    set::value(date('Y-m-d')),
                    set::placeholder($lang->project->begin),
                    set::required(true),
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                input
                (
                    set::type('date'),
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
            set::control(['type' => 'radioList', 'inline' => true, 'rootClass' => 'ml-4', 'items' => $lang->program->endList]),
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
        set::label($lang->program->acl),
        set::value('private'),
        set::items($aclList),
        set::control('radioList'),
    ),
    formRow
    (
        set::id('whitelistRow'),
        formGroup
        (
            set::width('3/4'),
            set::name('whitelist'),
            set::label($lang->whitelist),
            set::control('select')
        )
    )
);

render();
