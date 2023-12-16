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

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $title
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
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->project->name),
        set::value($copyProjectID ? $copyProject->name : ''),
        set::strong(true)
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
    formHidden('parent', 0),
    formHidden('model', $model),
    formHidden('vision', 'lite'),
    formHidden('hasProduct', 1)
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
