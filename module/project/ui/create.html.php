<?php
namespace zin;

jsVar('model', $model);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('beginLetterParent', $lang->project->beginLetterParent);
jsVar('endGreaterParent', $lang->project->endGreaterParent);
jsVar('ignore', $lang->project->ignore);

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

$currency = $parentProgram ? $parentProgram->budgetUnit : $config->project->defaultCurrency;

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
            set::items($projectModelItems)
        )
    )),
    to::headingActions
    (
        div
        (
            setClass('flex mr-5'),
            icon('cog-outline')
        ),
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
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('parent'),
            set::label($lang->project->parent),
            set::items($programList),
            set::value($programID),
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
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->project->name),
        set::strong(true),
    ),
    (!isset($config->setCode) or $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
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
        set::value('1'),
        on::change('toggleMultiple')
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
                on::click('changeType(1)'),
                $lang->project->projectTypeList[1]
            ),
            btn(
                setClass('project-type-0'),
                on::click('changeType(0)'),
                $lang->project->projectTypeList[0]
            )
        ),
        formHidden('hasProduct', 1)
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
                'suffixWidth' => 60,
            )),
            $parentProgram ? null : formHidden('budgetUnit', $config->project->defaultCurrency)
        ),
        formGroup
        (
            set::width('1/4'),
            set::name('future'),
            set::control(array('type' => 'checkList', 'inline' => true)),
            set::items(array('1' => $lang->project->future)),
            on::change('toggleBudget')
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
                    set::value(date('Y-m-d')),
                    set::placeholder($lang->project->begin),
                    set::required(true),
                    on::change('computeWorkDays')
                ),
                $lang->project->to,
                input
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::type('date'),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    on::change('computeWorkDays')
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
                set::required(true),
            ),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->project->day
            )
        )
    ),
    $products ? null :
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set('id', 'linkProduct'),
            set::label($lang->project->manageProducts),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    select
                    (
                        set::name('products[0]'),
                        set::items($allProducts),
                        set::multiple(false)
                    )
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
        ),
        formGroup
        (
            set::width('1/2'),
            inputGroup
            (
                $lang->project->associatePlan,
                select
                (
                    set::name('plans[][]'),
                    set::items(null),
                    set::multiple(false)
                )
            )
        )
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->project->desc),
        set::control('editor'),
        set::placeholder($lang->project->editorPlaceholder)
    ),
    /* TODO printExtendFields() */
    formRow
    (
        set::id('aclList'),
        formGroup
        (
            set::width('1/2'),
            set::name('acl'),
            set::label($lang->project->acl),
            set::control('radioList'),
            $programID ? set::items($lang->project->subAclList) : set::items($lang->project->aclList),
            set::value('private'),
            on::change('setWhite(this)')
        )
    ),
    /* TODO add events */
    formGroup
    (
        set::width('1/2'),
        set::name('whitelist[]'),
        set::label($lang->whitelist),
        set::items($users),
        set::control(['type' => 'select', 'multiple' => false])
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('auth'),
        set::label($lang->project->auth),
        set::control('radioList'),
        set::items($lang->project->authList),
        set::value('extend')
    ),
);

$copyProjectsBox = array();
foreach($copyProjects as $id => $name)
{
    $copyProjectsBox[] = btn(
        setClass('project-block justify-start'),
        setClass($copyProjectID == $id ? 'success-outline' : ''),
        set('data-id', $id),
        set('data-pinyin', zget($copyPinyinList, $name, '')),
        icon
        (
            setClass('text-gray'),
            $lang->icons['project']
        ),
        span($name),
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
                    set::class('copy-title'),
                    $lang->project->copyTitle
                )
            ),
            input
            (
                set::name('projectName'),
                set::placeholder($lang->project->searchByName),
            ),
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
