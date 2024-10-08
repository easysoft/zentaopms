<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class taskMiscInfo extends relatedList
{
    protected static array $defineProps = array
    (
        'task'         => '?object',  // 当前任务。
        'linkMRTitles' => '?array',   // 当前任务关联的提交。
        'linkCommits'  => '?array'    // 当前任务关联的提交。
    );

    protected function created()
    {
        global $lang;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        /* Linked MR. */
        if(helper::hasFeature('devops'))
        {
            $canViewMR  = common::hasPriv('mr', 'view');
            $linkMRList = $this->prop('linkMRTitles', data('linkMRTitles'));
            $linkedPRs  = $this->prop('linkedPRs', data('linkedPRs'));
            $data['mr'] = array
            (
                'title'    => $lang->task->linkMR,
                'items'    => $linkMRList,
                'url'      => $canViewMR ? createLink('mr', 'view', 'MRID={id}') : false,
                'props'    => array('data-app' => 'devops'),
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
                    'title' => $lang->task->linkPR,
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

            $linkedCommits = $this->prop('linkCommits', data('linkCommits'));
            $data['linkCommit'] = array
            (
                'title'    => $lang->task->linkCommit,
                'items'    => $linkedCommits,
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
