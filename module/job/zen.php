<?php
declare(strict_types=1);
/**
 * The zen file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */
class jobZen extends job
{
    /**
     * 检测版本库数量。
     * Check repo empty.
     *
     * @access protected
     * @return void
     */
    protected function checkRepoEmpty(): void
    {
        $repos = $this->loadModel('repo')->getRepoPairs('devops');
        if(empty($repos)) $this->locate($this->repo->createLink('create'));
    }

    /**
     * 获取流水线列表。
     * Get job list.
     *
     * @param  int       $repoID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getJobList(int $repoID, string $orderBy, object $pager): array
    {
        $this->loadModel('gitlab');

        $products = $this->loadModel('product')->getPairs();
        $jobList  = $this->job->getList($repoID, $orderBy, $pager);
        foreach($jobList as $job)
        {
            $job->canExec = true;

            if($job->engine == 'gitlab')
            {
                $pipeline = json_decode($job->pipeline);
                $branch   = $this->gitlab->apiGetSingleBranch($job->server, $pipeline->project, $pipeline->reference);
                if($branch and isset($branch->can_push) and !$branch->can_push) $job->canExec = false;
                /* query buildSpec */
                if(is_numeric($job->pipeline))  $job->pipeline = $this->gitlab->getProjectName($job->server, $job->pipeline);
                if(isset($pipeline->reference)) $job->pipeline = $this->gitlab->getProjectName($job->server, $pipeline->project);
            }
            elseif($job->engine == 'jenkins')
            {
                if(strpos($job->pipeline, '/job/') !== false) $job->pipeline = trim(str_replace('/job/', '/', $job->pipeline), '/');
            }

            $job->lastExec    = $job->lastExec ? $job->lastExec : '';
            $job->triggerType = $this->job->getTriggerConfig($job);
            $job->buildSpec   = urldecode($job->pipeline) . '@' . $job->jenkinsName;
            $job->engine      = zget($this->lang->job->engineList, $job->engine);
            $job->frame       = zget($this->lang->job->frameList, $job->frame);
            $job->productName = zget($products, $job->product, '');
        }

        return $jobList;
    }

    /**
     * 返回创建或者编辑的响应。
     * Return reponse after create or edit.
     *
     * @param  int       $repoID
     * @access protected
     * @return array
     */
    protected function reponseAfterCreateEdit(int $repoID = 0): array
    {
        if(dao::isError())
        {
            $errors = dao::getError();
            if($this->post->engine == 'gitlab' and isset($errors['server']))
            {
                if(!isset($errors['repo'])) $errors['repo'][] = sprintf($this->lang->error->notempty, $this->lang->job->repoServer);
                unset($errors['server']);
                unset($errors['pipeline']);
            }
            elseif($this->post->engine == 'jenkins')
            {
                if(isset($errors['server']))
                {
                    $errors['jkServer'] = $errors['server'];
                    unset($errors['server']);
                }
                if(isset($errors['pipeline']))
                {
                    $errors['jkTask'] = $errors['pipeline'];
                    unset($errors['pipeline']);
                }
            }
            return array('result' => 'fail', 'message' => $errors);
        }

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', 'repoID=' . ($repoID ? $repoID : $this->post->repo)));
    }

    /**
     * 获取版本库列表。
     * Get repo list.
     *
     * @param  int       $projectID
     * @param  object    $repo
     * @access protected
     * @return array
     */
    protected function getRepoList(int $projectID, object $repo = null): array
    {
        $repoList    = $this->loadModel('repo')->getList($projectID);
        $repoPairs   = $repo ? array($repo->id => $repo->name) : array();
        $gitlabRepos = array();
        $repoTypes   = $repo ? array($repo->id => $repo->SCM) : array();

        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;

            $repoPairs[$repo->id] = "[{$repo->SCM}] " . $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
            if(strtolower($repo->SCM) == 'gitlab') $gitlabRepos[$repo->id] = "[{$repo->SCM}] " . $repo->name;
        }

        return array($repoPairs, $gitlabRepos, $repoTypes);
    }

    /**
     * 获取svn目录。
     * Get subversion dir.
     *
     * @param  object    $repo
     * @param  string    $triggerType
     * @access protected
     * @return void
     */
    protected function getSubversionDir(object $repo, string $triggerType): void
    {
        if($repo->SCM == 'Subversion' && $triggerType == 'tag')
        {
            $dirs = array();
            $path = empty($repo->prefix) ? '/' : $this->repo->decodePath('');
            $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
            if($tags)
            {
                $dirs['/'] = $path;
                foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $dirPath;
            }
            $this->view->dirs = $dirs;

            foreach($this->lang->job->triggerTypeList as $type => $name)
            {
                if($type == 'tag') $this->lang->job->triggerTypeList[$type] = $this->lang->job->dirChange;
            }
        }
    }
}

