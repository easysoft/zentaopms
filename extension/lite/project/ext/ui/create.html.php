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
jsVar('LONG_TIME', LONG_TIME);

$checkDeltaChecked = jsCallback()->do(<<<'JS'
    const beginDate = new Date($('[name=begin]').zui('datePicker').$.value);
    const endDate   = new Date($('[name=end]').zui('datePicker').$.value);
    const days      = parseInt((endDate.getTime() - beginDate.getTime()) / (24 * 60 * 60 * 1000)) + 1;
    if(parseInt($('input[name=delta]:checked').val()) != 999) $('[name=delta]').prop('checked', false);
    if($('#delta' + days).length > 0 && days != 999) $('#delta' + days).prop('checked', true);
JS);

$projectEnd = $copyProjectID ? $copyProject->end : '';
$isLongTime = $projectEnd == LONG_TIME;

$delta = 0;
if($copyProjectID) $delta = $isLongTime ? 999 : (strtotime($copyProject->end) - strtotime($copyProject->begin)) / 3600 / 24 + 1;

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
            set('data-destroyOnHide', true),
            set('data-toggle', 'modal'),
            set('data-size', 'md'),
            $lang->project->copy
        )
    ),
    on::change('[name=delta]', 'computeEndDate'),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->project->name),
        set::value($copyProjectID ? $copyProject->name : '')
    ),
    isset($config->setCode) && $config->setCode == 1 ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::label($lang->project->code)
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
                on::change('[name=end], [name=begin]', $checkDeltaChecked),
                datepicker
                (
                    set::name('begin'),
                    set('id', 'begin'),
                    set::value($copyProjectID ? $copyProject->begin : date('Y-m-d')),
                    set::required(true)
                ),
                $lang->project->to,
                datepicker
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::placeholder($lang->project->end),
                    set::value($projectEnd),
                    set::required(true),
                    set::disabled($isLongTime)
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
                set::items($lang->project->endList),
                set::value($delta)
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
            set::multiple(true),
            set::value($copyProjectID ? $copyProject->whitelist : '')
        )
    ),
    formHidden('LONG_TIME', LONG_TIME),
    formHidden('parent', 0),
    formHidden('model', $model),
    formHidden('vision', 'lite'),
    formHidden('hasProduct', 0)
);

$toggleActiveProject = jsCallback()->do(<<<'JS'
    if($this.hasClass('primary-outline'))
    {
        $this.removeClass('primary-outline');
    }
    else
    {
        $('#copyProjects button.project-block.primary-outline').removeClass('primary-outline');
        $this.addClass('primary-outline');
    }
JS
);

$copySelectedProject = jsCallback()->const('model', $model)->do(<<<'JS'
    const copyProjectID = $('#copyProjects button.project-block.primary-outline').length == 1 ? $('#copyProjects button.project-block.primary-outline').data('id') : 0;
    loadPage($.createLink('project', 'create', 'model=' + model + '&programID=0' + '&copyProjectID=' + copyProjectID));
    zui.Modal.hide();
JS
);

$copyProjectsBox = array();
foreach($copyProjects as $id => $project)
{
    $copyProjectsBox[] = btn(
        setClass('project-block justify-start'),
        setClass($copyProjectID == $id ? 'primary-outline' : ''),
        set('data-id', $id),
        set('data-pinyin', zget($copyPinyinList, $project->name, '')),
        icon
        (
            setClass('text-gray'),
            $lang->icons['project']
        ),
        on::click($toggleActiveProject),
        span($project->name, set::title($project->name), setClass('text-left'))
    );
}

modalTrigger
(
    modal
    (
        set::id('copyProjectModal'),
        to::header
        (
            div
            (
                setClass('w-full'),
                span
                (
                    h4
                    (
                        set::className('copy-title'),
                        $lang->project->copyTitle
                    )
                ),
                div
                (
                    setClass('py-4 border-b border-b-1'),
                    inputControl
                    (
                        to::suffix(icon('search')),
                        set::suffixWidth('sm'),
                        input
                        (
                            set::name('projectName'),
                            set::placeholder($lang->project->searchByName)
                        )
                    )
                )
            )

        ),
        div
        (
            set::id('copyProjects'),
            setClass('flex items-center flex-wrap gap-4'),
            $copyProjectsBox
        ),
        to::footer
        (
            div
            (
                setClass('flex mt-4 w-full justify-center'),
                btn
                (
                    setClass('px-6'),
                    set::type('primary'),
                    on::click($copySelectedProject),
                    $lang->confirm
                )
            )
        )
    )
);

render();
