<?php
namespace zin;

$parentID = $parentProgram->id ?? 0;
$currency = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$aclList  = $parentProgram ? $lang->program->subAclList : $lang->program->aclList;

jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('page', 'create');
jsVar('parentBudget', $lang->program->parentBudget);
jsVar('beginLessThanParent', $lang->program->beginLessThanParent);
jsVar('endGreatThanParent', $lang->program->endGreatThanParent);
jsVar('ignore', $lang->program->ignore);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('budgetOverrun', $lang->project->budgetOverrun);

set::title($parentID ? $lang->program->children : $lang->program->create);
formPanel
(
    set::id('createProgram'),
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            $title
        )
    ),
    on::change('#parent', 'onParentChange'),
    on::change('#budget', 'budgetOverrunTips'),
    on::change('#future', 'onFutureChange'),
    on::change('#acl',    'onAclChange'),
    formGroup
    (
        set::width('1/2'),
        set::name('parent'),
        set::label($lang->program->parent),
        set::value($parentID),
        set::items($parents)
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
        set::width('1/3'),
        set::name('PM'),
        set::label($lang->program->PM),
        set::items($pmUsers)
    ),
    formRow
    (
        set::id('budgetRow'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->project->budget),
            inputGroup
            (
                $parentProgram ? set::prefix(zget($lang->project->currencySymbol, $currency)) : null,
                $parentProgram ? set::prefixWidth('icon') : null,
                input(set::name('budget')),
                $parentProgram ? formHidden('budgetUnit', $parentProgram->budgetUnit) : picker(set::name('budgetUnit'), set::items($budgetUnitList), set::value($config->project->defaultCurrency), set::required(true))
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::name('future'),
            set::className('items-center'),
            set::control(array('type' => 'checkList', 'inline' => true)),
            set::items(array('1' => $lang->project->future))
        )
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
                datePicker
                (
                    set::name('begin'),
                    set::id('begin'),
                    set::value(date('Y-m-d')),
                    set::placeholder($lang->project->begin),
                    set::required(true),
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                datePicker
                (
                    set::name('end'),
                    set::id('end'),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    set::value(''),
                    on::change('computeWorkDays')
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
            )
        ),
        formGroup
        (
            set::name('delta'),
            set::className('pl-4 items-center'),
            set::control(array('type' => 'radioList', 'inline' => true, 'rootClass' => 'ml-4', 'items' => $lang->program->endList)),
            on::change('setDate')
        )
    ),
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
        set::value('open'),
        set::items($aclList),
        set::control('radioList')
    ),
    formRow
    (
        set::id('whitelistRow'),
        setClass('hidden'),
        formGroup
        (
            set::width('3/4'),
            set::label($lang->whitelist),
            whitelist(set::items($users))
        )
    )
);

render();
