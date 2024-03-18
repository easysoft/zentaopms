<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class storyRelatedList extends relatedList
{
    protected static array $defineProps = array
    (
        'fromBug'       => '?object',          // 来源 BUG。
        'bugs'          => '?array',           // 关联的 BUG 列表。
        'builds'        => '?array',           // 关联的版本列表。
        'releases'      => '?array',           // 关联的发布列表。
        'storyProducts' => '?array',           // 需求产品信息。
        'linkedMRs'     => '?array',           // 需求 MR 信息。
        'linkedCommits' => '?linkedCommits',   // 需求提交信息。
        'story'         => '?object'           // 当前需求。
    );

    protected function created()
    {
        $data = $this->prop('data');
        if($data) return;

        $story = $this->prop('story');
        if(!$story) $story = data('story');
        if(!$story) return;

        global $app, $lang;

        $isStoryType   = $story->type == 'story';
        $fromBug       = $this->prop('fromBug', data('fromBug'));
        $bugs          = $this->prop('bugs', data('bugs'));
        $cases         = $this->prop('cases', data('cases'));
        $builds        = $this->prop('builds', data('builds'));
        $releases      = $this->prop('releases', data('releases'));
        $storyProducts = $this->prop('storyProducts', data('storyProducts'));
        $linkedMRs     = $this->prop('linkedMRs', data('linkedMRs'));
        $linkedCommits = $this->prop('linkedCommits', data('linkedCommits'));
        $data          = array();

        if($isStoryType)
        {
            if(!empty($fromBug) && hasPriv('story', 'bugs'))
            {
                $data['fromBug'] = array
                (
                    'title' => $lang->story->legendFromBug,
                    'items' => array($fromBug),
                    'url'   => hasPriv('bug', 'view') ? createLink('bug', 'view', 'bugID={id}') : false
                );
            }

            $data['bug'] = array
            (
                'title'      => $lang->story->legendBugs,
                'items'      => $bugs,
                'statusList' => $lang->bug->statusList
            );

            if(hasPriv('story', 'cases'))
            {
                $data['testcase'] = array
                (
                    'title' => $lang->story->legendCases,
                    'items' => $cases,
                    'url'   => hasPriv('testcase', 'view') ? createLink('testcase', 'view', 'caseID={id}') : false
                );
            }

            $tab = $app->tab == 'product' ? 'project' : $app->tab;
            if($app->tab == 'system') $tab = 'project';
            $data['build'] = array
            (
                'title' => $lang->story->legendBuilds,
                'items' => $builds,
                'props' => array('data-app' => $tab)
            );

            $tab           = $app->tab == 'execution' ? 'product'        : $app->tab;
            $releaseModule = $app->tab == 'project'   ? 'projectrelease' : 'release';
            if($app->tab == 'system') $tab = 'product';
            $data['release'] = array
            (
                'title' => $lang->story->legendReleases,
                'items' => $releases,
                'url'   => hasPriv($releaseModule, 'view') ? createLink($releaseModule, 'view', 'releaseID={id}') : false,
                'props' => array('data-app' => $tab)
            );
        }

        $data['story'] = array
        (
            'title'    => $lang->story->linkStories,
            'items'    => $story->linkStoryTitles,
            'url'      => false,
            'onRender' => function($item, $linkedStory) use($storyProducts, $story, $app)
            {
                $storyID = $linkedStory->id;
                $hasPriv = ($app->user->admin || str_contains(",{$app->user->view->products},", ",{$storyProducts[$story->id]},"));
                $item['url'] = $hasPriv ? createLink("storyID=$storyID&version=0&param=0&storyType=$story->type") : false;
                if($hasPriv)
                {
                    $item['data-toggle'] = 'modal';
                    $item['data-size']   = 'lg';
                }
                return $item;
            }
        );

        if($isStoryType && helper::hasFeature('devops'))
        {
            $data['mr'] = array
            (
                'title' => $lang->story->linkMR,
                'items' => $linkedMRs,
                'url'   => hasPriv('mr', 'view') ? createLink('mr', 'view', 'MRID={id}') : false,
                'props' => array('data-app' => 'devops')
            );
        }

        $data['commit'] = array
        (
            'title'    => $lang->story->linkCommit,
            'items'    => $linkedCommits,
            'url'      => false,
            'onRender' => function($item, $commit)
            {
                $item['text'] = $commit->comment;
                if(hasPriv('repo', 'revision'))
                {
                    $item['url'] = createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}");
                    $item['data-app'] = 'devops';
                }
                return $item;
            }
        );

        $this->setProp('data', $data);
    }
}
