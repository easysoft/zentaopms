<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class bugRelatedList extends relatedList
{
    protected static array $defineProps = array
    (
        'bug' => '?object', // 当前Bug。
    );

    protected function created()
    {
        $data = $this->prop('data');
        if($data) return;

        $bug = $this->prop('bug');
        if(!$bug) $bug = data('bug');
        if(!$bug) return;

        global $lang, $config;

        $canViewCase   = common::hasPriv('testcase', 'view');
        $canViewStory  = common::hasPriv('story', 'view');
        $canViewTask   = common::hasPriv('task', 'view');
        $canViewMR     = common::hasPriv('mr', 'view');
        $linkedCommits = $this->prop('linkedCommits', data('linkCommits'));

        $data = array();

        /* Related bugs. */
        if($config->edition == 'open')
        {
            $relatedBugList  = isset($bug->relatedBugTitles) ? $bug->relatedBugTitles :array();
            $relatedBugItems = array();
            foreach($relatedBugList as $relatedBugID => $relatedBugTitle)
            {
                $relatedBugItem = new stdclass();
                $relatedBugItem->id    = $relatedBugID;
                $relatedBugItem->title = $relatedBugTitle;
                $relatedBugItems[] = $relatedBugItem;
            }

            $data['relatedBug'] = array
            (
                'title'       => $lang->bug->relatedBug,
                'items'       => $relatedBugItems,
                'url'         => createLink('bug', 'view', 'bugID={id}'),
                'data-toggle' => 'modal',
                'data-size'   => 'lg'
            );
        }

        /* To cases. */
        $toCaseList  = isset($bug->toCases) ? $bug->toCases :array();
        $toCaseItems = array();
        foreach($toCaseList as $caseID => $caseTitle)
        {
            $toCaseItem = new stdclass();
            $toCaseItem->id    = $caseID;
            $toCaseItem->title = $caseTitle;

            $toCaseItems[] = $toCaseItem;
        }
        $data['toCase'] = array
        (
            'title' => $lang->bug->toCase,
            'items' => $toCaseItems,
            'url'   => $canViewCase ? createLink('testcase', 'view', 'caseID={id}') : false,
            'props' => $canViewCase ? array('data-toggle' => 'modal', 'data-size' => 'lg') : array()
        );

        /* To stories. */
        $toStoryItems = array();
        if($bug->toStory)
        {
            $toStoryItem = new stdclass();
            $toStoryItem->id    = $bug->toStory;
            $toStoryItem->title = $bug->toStoryTitle;

            $toStoryItems[] = $toStoryItem;
        }
        $data['toStory'] = array
        (
            'title' => $lang->bug->toStory,
            'items' => $toStoryItems,
            'url'   => $canViewStory ? createLink('story', 'view', 'story={id}') : false,
            'props' => $canViewStory ? array('data-toggle' => 'modal', 'data-size' => 'lg') : array()
        );

        /* To tasks. */
        $toTaskItems = array();
        if($bug->toTask)
        {
            $toTaskItem = new stdclass();
            $toTaskItem->id    = $bug->toTask;
            $toTaskItem->title = $bug->toTaskTitle;

            $toTaskItems[] = $toTaskItem;
        }

        $data['toTask'] = array
        (
            'title' => $lang->bug->toTask,
            'items' => $toTaskItems,
            'url'   => $canViewTask ? createLink('task', 'view', 'task={id}') : false,
            'props' => $canViewTask ? array('data-toggle' => 'modal', 'data-size' => 'lg') : array()
        );


        /* Linked MR. */
        $linkMRList  = isset($bug->linkMRTitles) ? $bug->linkMRTitles :array();
        $linkedPRs   = $this->prop('linkedPRs', data('linkedPRs'));
        if(helper::hasFeature('devops'))
        {
            $data['mr'] = array
            (
                'title' => $lang->bug->linkMR,
                'items' => $linkMRList,
                'url'   => $canViewMR ? createLink('mr', 'view', 'MRID={id}') : false,
                'props' => array('data-app' => 'devops'),
                'onRender' => function($item, $mr) use($lang)
                {
                    $item['titleClass'] = 'w-0 flex-1';
                    $statusClass = $mr->status;
                    if($mr->status == 'opened') $statusClass = 'draft';
                    if($mr->status == 'merged') $statusClass = 'done';
                    $item['content'] = array('html' => "<span class='status-{$statusClass}'>" . zget($lang->mr->statusList, $mr->status) . '</span>');
                    return $item;
                }
            );

            if($linkedPRs)
            {
                $data['pr'] = array
                (
                    'title' => $lang->bug->linkPR,
                    'items' => $linkedPRs,
                    'url'   => hasPriv('pullreq', 'view') ? createLink('pullreq', 'view', 'MRID={id}') : false,
                    'props' => array('data-app' => 'devops'),
                    'onRender' => function($item, $mr) use($lang)
                    {
                        $item['titleClass'] = 'w-0 flex-1';
                        $statusClass = $mr->status;
                        if($mr->status == 'opened') $statusClass = 'draft';
                        if($mr->status == 'merged') $statusClass = 'done';
                        $item['content'] = array('html' => "<span class='status-{$statusClass}'>" . zget($lang->mr->statusList, $mr->status) . '</span>');
                        return $item;
                    }
                );
            }

            $data['linkCommit'] = array
            (
                'title'    => $lang->bug->linkCommit,
                'items'    => $linkedCommits,
                'url'      => false,
                'onRender' => function($item, $commit)
                {
                    $item['text'] = $commit->comment;
                    if(hasPriv('repo', 'revision'))
                    {
                        $item['url']      = createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}");
                        $item['data-app'] = 'devops';
                    }
                    return $item;
                }
            );
        }
        $this->setProp('data', $data);
    }
}
