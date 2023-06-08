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

jsVar('storyID', $story->id);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('isMultiple', count($reviewers) > 1);
jsVar('isLastOne', $isLastOne);

$formTitle = div
(
    span
    (
        setClass('form-label'),
        $lang->story->reviewAction
    ),
    div
    (
        setClass('form-group form-title'),
        $story->title,
        span
        (
            setClass('label text-gray size-sm'),
            $story->id
        )
    ),
);

$formItems = array();
foreach($fields as $field => $attr)
{
    $width     = zget($attr, 'width', '1/3');
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    $formItems[$field] = formRow
    (
        formGroup
        (
            set::width($width),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required'])
        )
    );
}

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
        set::cols($config->story->affect->projects->fields),
        set::data(array_values($story->tasks[$executionID]))
    );
}

$formItems['affected'] = formGroup
(
    setClass('w-full'),
    set::label($lang->story->checkAffection),
    tabs
    (
        setClass('w-full'),
        tabPane
        (
            to::suffix(label($affectedTaskCount)),
            set::key('affectedProjects'),
            set::title($lang->story->affectedProjects),
            set::active(true),
            $affectedProjects,
        ),
        tabPane
        (
            to::suffix(label(count($story->bugs))),
            set::key('affectedBugs'),
            set::title($lang->story->affectedBugs),
            empty($story->bugs) ? null : dtable
            (
                set::cols($config->story->affect->bugs->fields),
                set::data(array_values($story->bugs))
            )
        ),
        tabPane
        (
            to::suffix(label(count($story->cases))),
            set::key('affectedCases'),
            set::title($lang->story->affectedCases),
            empty($story->cases) ? null : dtable
            (
                set::cols($config->story->affect->cases->fields),
                set::data(array_values($story->cases))
            )
        ),
        empty($story->twins) ? null : tabPane
        (
            to::suffix(label(count($story->twins))),
            set::key('affectedTwins'),
            set::title($lang->story->affectedTwins),
            dtable
            (
                set::cols($config->story->affect->twins->fields),
                set::data(array_values($story->twins))
            )
        ),
    )
);

$formItems['result']->add(on::change('switchShow(e.target);'));
$formItems['assignedTo']->add(set::id('assignedToBox'))->add(set::hidden(!$isLastOne));
$formItems['closedReason']->add(set::id('rejectedReasonBox'))->add(set::hidden(true))->add(on::change('setStory(e.target);'));
$formItems['duplicateStory']->add(set::id('duplicateStoryBox'))->add(set::hidden(true));
$formItems['pri']->add(set::id('priBox'))->add(set::hidden(true));
$formItems['estimate']->add(set::id('estimateBox'))->add(set::hidden(true));
$formItems['childStories']->add(set::id('childStoriesBox'))->add(set::hidden(true));
$formItems['status']->add(set::hidden(true));

panel
(
    setClass('panel-form mx-auto'),
    set::title(''),
    form
    (
        $formTitle,
        $formItems,
    ),
    h::hr(set::class('mt-6 mb-6')),
    history()
);

render();
