<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class taskMiscInfo extends wg
{
    protected static array $defineProps = array
    (
        'task'         => '?object',  // 当前任务。
        'linkMRTitles' => '?array',   // 当前任务关联的提交。
        'linkCommits'  => '?array'    // 当前任务关联的提交。
    );

    protected function getItems(): array
    {
        global $lang, $app;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        $items = array();

        if($task->linkedBranch)
        {
            $items[$lang->task->relatedBranch] = $task->linkedBranch;
        }

        $linkMRTitles = $this->prop('linkMRTitles', data('linkMRTitles'));
        $mrItems      = array();
        $canViewMR    = common::hasPriv('mr', 'view');
        foreach($linkMRTitles as $MRID => $linkMRTitle)
        {
            $mrItems[] = array
            (
                'text'     => "#$MRID $linkMRTitle",
                'url'      => $canViewMR ? createLink('mr', 'view', "MRID=$MRID") : false,
                'data-app' => $app->tab
            );
        }
        $items[$lang->task->linkMR] = array
        (
            'control' => 'list',
            'items'   => $mrItems
        );

        $linkCommits = $this->prop('linkCommits', data('linkCommits'));
        $commitItems = array();
        $canRevision = hasPriv('repo', 'revision');
        foreach($linkCommits as $commit)
        {
            $revision = substr($commit->revision, 0, 10);
            $commitItems[] = array
            (
                'text'     => "#$revision",
                'url'      => $canRevision ? createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}") : false,
                'data-app' => $app->tab
            );
        }
        $items[$lang->task->linkCommit] = array
        (
            'control' => 'list',
            'items'   => $commitItems
        );

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('task-effort-info'),
            set::items($this->getItems()),
            set::labelWidth(90)
        );
    }
}
