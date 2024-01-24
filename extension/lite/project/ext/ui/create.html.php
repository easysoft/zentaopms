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
    on::change('[name=delta]', 'computeEndDate'),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->project->name),
        set::value($copyProjectID ? $copyProject->name : ''),
        set::strong(true)
    ),
    isset($config->setCode) && $config->setCode == 1 ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::label($lang->project->code),
        set::strong(true)
    ) : null,
    formGroup
    (
        set::width('1/4'),
        set::name('PM'),
        set::label($lang->project->PM),
        set::items($PMUsers)
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
                    set::value($copyProjectID ? $copyProject->begin : ''),
                    set::required(true)
                ),
                $lang->project->to,
                datepicker
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::placeholder($lang->project->end),
                    set::value($copyProjectID ? $copyProject->end : ''),
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
            on::change()->toggleClass('.whitelistBox', 'hidden', "\$element.find('[name=acl]:checked').val() === 'open'"),
            set::value($copyProjectID ? $copyProject->acl : 'private')
        )
    ),
    formGroup
    (
        setClass('whitelistBox' . ($copyProjectID && $copyProject->acl == 'open' ? ' hidden' : '')),
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
    formHidden('hasProduct', 0)
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
