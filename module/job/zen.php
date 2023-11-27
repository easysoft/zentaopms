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
}

