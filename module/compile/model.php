<?php
/**
 * The model file of compile module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class compileModel extends model
{
    /**
     * Get by id
     *
     * @param  int    $buildID
     * @access public
     * @return object
     */
    public function getByID($buildID)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq($buildID)->fetch();
    }

    /**
     * Get By Queue.
     *
     * @param  int    $queue
     * @access public
     * @return void
     */
    public function getByQueue($queue)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('queue')->eq($queue)->fetch();
    }

    /**
     * Get build list.
     *
     * @param  int    $jobID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($jobID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.id, t1.name, t1.job, t1.status, t1.createdDate, t1.testtask, t2.pipeline, t2.triggerType, t2.comment, t2.atDay, t2.atTime, t2.engine, t3.name as repoName, t4.name as jenkinsName')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
            ->leftJoin(TABLE_REPO)->alias('t3')->on('t2.repo=t3.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t4')->on('t2.server=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.job')->ne('0')
            ->beginIF(!empty($jobID))->andWhere('t1.job')->eq($jobID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get list by jobID.
     *
     * @param  int $jobID
     * @return array
     */
    public function getListByJobID($jobID)
    {
        return $this->dao->select('id, name, status')->from(TABLE_COMPILE)
            ->where('deleted')->eq('0')
            ->andWhere('job')->ne('0')
            ->beginIF(!empty($jobID))->andWhere('job')->eq($jobID)->fi()
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Get unexecuted list.
     *
     * @access public
     * @return array
     */
    public function getUnexecutedList()
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('status')->eq('')->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get last result.
     *
     * @param  int    $jobID
     * @access public
     * @return object
     */
    public function getLastResult($jobID)
    {
        return $this->dao->select('*')->from(TABLE_COMPILE)->where('job')->eq($jobID)->andWhere('status')->ne('')->orderBy('createdDate_desc')->limit(1)->fetch();
    }

    /**
     * Get success jobs by job id list.
     *
     * @param  array  $jobIDList
     * @access public
     * @return array
     */
    public function getSuccessJobs($jobIDList)
    {
        return $this->dao->select('job')->from(TABLE_COMPILE)
            ->where('job')->in($jobIDList)
            ->andWhere('status')->eq('success')
            ->fetchPairs();
    }

    /**
     * Get build url.
     *
     * @param  object $jenkins
     * @access public
     * @return object
     */
    public function getBuildUrl($jenkins)
    {
        $jenkinsServer   = $jenkins->url;
        $jenkinsUser     = $jenkins->account;
        $jenkinsPassword = $jenkins->token ? $jenkins->token : base64_decode($jenkins->password);

        $url = new stdclass();
        $url->userPWD = "$jenkinsUser:$jenkinsPassword";
        $url->url     = sprintf('%s/job/%s/buildWithParameters/api/json', $jenkinsServer, $jenkins->pipeline);

        return $url;
    }

    /**
     * Save build by job
     *
     * @param  int    $jobID
     * @param  string $data
     * @param  string $type
     * @access public
     * @return void
     */
    public function createByJob($jobID, $data = '', $type = 'tag')
    {
        $job = $this->dao->select('id,name')->from(TABLE_JOB)->where('id')->eq($jobID)->fetch();

        $build = new stdClass();
        $build->job         = $job->id;
        $build->name        = $job->name;
        $build->$type       = $data;
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
    }

    /**
     * Execute compile
     *
     * @param  object $compile
     * @access public
     * @return bool
     */
    public function exec($compile)
    {
        $job = $this->dao->select('t1.id,t1.name,t1.repo,t1.engine,t1.pipeline,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password,t1.triggerType,t1.customParam,t1.server')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.server=t2.id')
            ->where('t1.id')->eq($compile->job)
            ->fetch();

        if(!$job) return false;

        $compileID = $compile->id;
        $repo      = $this->loadModel('repo')->getRepoById($job->repo);

        if($job->triggerType == 'tag')
        {
            $lastTag = $this->loadModel('job')->getLastTagByRepo($repo, $job);
            if($lastTag)
            {
                $job->lastTag = $lastTag;
                $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
            }

            $this->dao->update(TABLE_COMPILE)->set('tag')->eq($lastTag)->where('id')->eq($compile->id)->exec();
        }

        $this->loadModel('job');
        if($job->engine == 'gitlab')  $compile = $this->job->execGitlabPipeline($job, $compileID);
        if($job->engine == 'jenkins') $compile = $this->job->execJenkinsPipeline($job, $repo, $compileID);

        $this->dao->update(TABLE_COMPILE)->data($compile)->where('id')->eq($compileID)->exec();
        $this->dao->update(TABLE_JOB)
            ->set('lastStatus')->eq($compile->status)
            ->set('lastExec')->eq($compile->updateDate)
            ->where('id')->eq($job->id)
            ->exec();

        return !dao::isError();
    }
}
