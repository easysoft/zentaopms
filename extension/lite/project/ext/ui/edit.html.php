<?php
namespace zin;

jsVar('model', $project->model);
jsVar('labelClass', $config->project->labelClass);
jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
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
jsVar('LONG_TIME', LONG_TIME);

$labelClass     = $config->project->labelClass[$model];
$delta          = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;

$checkDeltaChecked = jsCallback()->do(<<<'JS'
    const beginDate = new Date($('[name=begin]').zui('datePicker').$.value);
    const endDate   = new Date($('[name=end]').zui('datePicker').$.value);
    const days      = parseInt((endDate.getTime() - beginDate.getTime()) / (24 * 60 * 60 * 1000)) + 1;
    if(parseInt($('input[name=delta]:checked').val()) != 999) $('[name=delta]').prop('checked', false);
    if($('#delta' + days).length > 0 && days != 999) $('#delta' + days).prop('checked', true);
JS);

formPanel
(
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            $title,
            btn
            (
                set::id('project-model'),
                setClass("$labelClass h-5 px-2"),
                zget($lang->project->modelList, $model, '')
            )
        )
    ),
    on::change('[name=delta]', 'computeEndDate'),
    formHidden('parent', $project->parent),
    formHidden('model', $project->model),
    formHidden('hasProduct', $project->hasProduct),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::value($project->name),
        set::label($lang->project->name)
    ),
    (isset($config->setCode) && $config->setCode == 1) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::value($project->code),
        set::label($lang->project->code)
    ) : null,
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
            set::width('1/2'),
            set::label($lang->project->planDate),
            set::required(true),
            inputGroup
            (
                on::change('[name=end], [name=begin]', $checkDeltaChecked),
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
                    set::value($project->end != LONG_TIME ? $project->end : ''),
                    set::placeholder($lang->project->end),
                    set::required(true),
                    set::disabled($project->end == LONG_TIME)
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
    formGroup
    (
        set::label($lang->project->desc),
        editor
        (
            set::name('desc'),
            html($project->desc),
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
            on::change()->toggleClass('.whitelistBox', 'hidden', "\$element.find('[name=acl]:checked').val() === 'open'"),
            set::value($project->acl)
        )
    ),
    formGroup
    (
        setClass('whitelistBox' . ($project->acl == 'open' ? ' hidden' : '')),
        set::width('1/2'),
        set::label($lang->whitelist),
        picker
        (
            set::name('whitelist[]'),
            set::value($project->whitelist),
            set::items($users),
            set::multiple(true)
        )
    )
);

setPageData('title', $title);

render();
