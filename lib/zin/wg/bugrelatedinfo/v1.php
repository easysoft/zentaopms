<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class bugRelatedInfo extends wg
{
    protected static array $defineProps = array
    (
        'bug' => '?object',  // 当前Bug。
    );

    protected function getItems(): array
    {
        global $lang, $app;

        $bug = $this->prop('bug', data('bug'));
        if(!$bug) return array();

        $project            = $this->prop('project', data('project'));
        $canViewProduct     = common::hasPriv('product', 'view');
        $canBrowseExecution = common::hasPriv('execution', 'browse');
        $canViewStory       = common::hasPriv('story', 'view');
        $canViewTask        = common::hasPriv('task', 'view');
        $executionTitle     = isset($project->model) && $project->model == 'kanban' ? $lang->bug->kanban : $lang->bug->execution;
        $projectLink        = $bug->project   && $canViewProduct     ? helper::createLink('project',   'view',   "projectID={$bug->project}")     : '';
        $executionLink      = $bug->execution && $canBrowseExecution ? helper::createLink('execution', 'browse', "executionID={$bug->execution}") : '';
        $storyLink          = $bug->story     && $canViewStory       ? helper::createLink('story',     'view',   "storyID={$bug->story}")         : '';
        $taskLink           = $bug->task      && $canViewTask        ? helper::createLink('task',      'view',   "taskID={$bug->task}")           : '';

        $items = array();
        $items[$lang->bug->project] = $projectLink ? array
        (
            'control' => 'link',
            'url'     => $projectLink,
            'text'    => zget($bug, 'projectName', '')
        ) : zget($bug, 'projectName', '');

        if(empty($project) || !empty($project->multiple))
        {
            $items[$executionTitle] = $executionLink ? array
            (
                'control' => 'link',
                'url'     => $executionLink,
                'text'    => zget($bug, 'executionName', '')
            ) : zget($bug, 'executionName', '');
        }

        $storyHtml = $bug->story ? div
        (
            label
            (
                setClass('dark-outline rounded-full size-sm mr-2'),
                $bug->story
            ),
            $storyLink ? a(
                zget($bug, 'storyTitle', ''),
                set::href($storyLink),
                setData('toggle', 'modal'),
                setData('size', 'lg')
            ) : span(zget($bug, 'storyTitle', '')),
            $bug->storyStatus == 'active' && $bug->latestStoryVersion > $bug->storyVersion && common::hasPriv('bug', 'confirmStoryChange') ? span
            (
                ' (',
                a
                (
                    set::href(createLink('bug', 'confirmStoryChange', "bugID={$bug->id}")),
                    $lang->confirm,
                ),
                ')'
            ) : null
        ) : null;

        $items[$lang->bug->story] = array
        (
            'control' => 'div',
            'content' => $storyHtml
        );

        $taskHtml = div
        (
            label(setClass('dark-outline rounded-full size-sm mr-2'), $bug->task),
            $taskLink ? a(
                zget($bug, 'taskName', ''),
                set::href($taskLink),
                setData('toggle', 'modal'),
                setData('size', 'lg')
            ) : span(zget($bug, 'taskName', ''))
        );

        $items[$lang->bug->task] = array
        (
            'control' => 'div',
            'content' => $bug->task ? $taskHtml : ''
        );

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('bug-related-info'),
            set::items($this->getItems()),
        );
    }
}
