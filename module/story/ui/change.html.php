<?php
declare(strict_types=1);
/**
 * The change view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('lastReviewer', explode(',', $lastReviewer));
jsVar('storyID', $story->id);
jsVar('oldStoryTitle', $story->title);
jsVar('oldStorySpec', $story->spec);
jsVar('oldStoryVerify', $story->verify);
jsVar('changed', 0);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('page', $this->app->rawMethod);

$formTitle = div
(
    span
    (
        setClass('form-label'),
        $lang->story->changed
    ),
    div
    (
        setClass('form-group title'),
        $story->title,
        span
        (
            setClass('label text-gray size-sm'),
            $story->id
        )
    ),
);

$formItems = array();
$formItems['reviewer'] = formGroup
(
    set::width('full'),
    set::label($fields['reviewer']['title']),
    inputGroup
    (
        select
        (
            set::name('reviewer[]'),
            set::multiple(true),
            set::items($fields['reviewer']['options']),
            set::value($fields['reviewer']['default']),
        ),
        $forceReview ? null : span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::id('needNotReview'),
                set::name('needNotReview'),
                set::checked($needReview),
                set::text($lang->story->needNotReview)
            )
        )
    ),
    set::required($fields['reviewer']['required'])
);
$formItems['title'] = formGroup
(
    set::width('full'),
    set::label($fields['title']['title']),
    inputGroup
    (
        input
        (
            set::name('title'),
            set::value($fields['title']['default']),
        ),
        span
        (
            setClass('input-group-addon'),
            input
            (
                set::name('color'),
                set::type('color'),
                set::value($fields['color']['default']),
            )
        ),
        empty($story->twins) ? null : span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::id('relievedTwins'),
                set::name('relievedTwins'),
                set::title($lang->story->changeRelievedTwinsTips),
            )
        )
    ),
    set::required($fields['title']['required'])
);
$formItems['status'] = formRow
(
    set::hidden(true),
    formGroup
    (
        input(set::name('status'), set::value($fields['status']['default']))
    )
);
$formItems['spec'] = formGroup
(
    set::label($fields['spec']['title']),
    set::control($fields['spec']['control']),
    set::value($fields['spec']['default']),
    set::tip($lang->story->specTemplate)
);
unset($fields['reviewer'], $fields['title'], $fields['color'], $fields['status'], $fields['spec']);

foreach($fields as $field => $attr)
{
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    $formItems[$field] = formGroup
    (
        set::width('full'),
        set::name($fieldName),
        set::label($attr['title']),
        set::control($control),
        set::value($attr['default']),
        set::required($attr['required'])
    );
}
$formItems['file'] = formGroup
(
    set::width('full'),
    set::label($lang->attatch),
    input
    (
        set::type('file'),
        set::name('files'),
    )
);

$affectedProjects  = array();
$affectedTaskCount = 0;
foreach($story->executions as $executionID => $execution)
{
    $teams = '';
    foreach($story->teams[$executionID] as $member) $teams .= zget($users, $member) . ' ';
    $affectedTaskCount += count($story->tasks[$executionID]);
    $affectedProjects[] = h6
    (
        $execution->name,
        $teams ? small(icon('group'), $teams) : null
    );
    $affectedProjects[] = dtable
    (
        col($config->story->affect->projects->fields),
        data(array_values($story->tasks[$executionID]))
    );
}

$formItems['affected'] = formGroup
(
    set::width('full'),
    set::label($lang->story->checkAffection),
    tabs
    (
        tabPane
        (
            set::key('affectedProjects'),
            set::title($lang->story->affectedProjects),
            set::active(true),
            $affectedProjects,
        ),
        tabPane
        (
            set::key('affectedBugs'),
            set::title($lang->story->affectedBugs),
            empty($story->bugs) ? null : dtable
            (
                col($config->story->affect->bugs->fields),
                data(array_values($story->bugs))
            )
        ),
        tabPane
        (
            set::key('affectedCases'),
            set::title($lang->story->affectedCases),
            empty($story->cases) ? null : dtable
            (
                col($config->story->affect->cases->fields),
                data(array_values($story->cases))
            )
        ),
        empty($story->twins) ? null : tabPane
        (
            set::key('affectedTwins'),
            set::title($lang->story->affectedTwins),
            dtable
            (
                col($config->story->affect->twins->fields),
                data(array_values($story->twins))
            )
        ),
    )
);
$formItems['lastEditedDate'] = input(set::type('hidden'), set::name('lastEditedDate', set::id('lastEditedDate'), set::value($story->lastEditedDate)));

$formActions = formRow
(
    setClass('form-actions form-group no-label'),
    btn(setClass('primary'), set::id('saveButton'), $lang->save),
    btn(setClass('secondary'), set::id('saveDraftButton'), $lang->story->doNotSubmit),
    btn(set::url('javascript:history.go(-1)'), $lang->goback),
);

formPanel
(
    set::title(''),
    set::actions(false),
    $formTitle,
    $formItems,
    $formActions,
    h::hr(),
    history()
);
render();
