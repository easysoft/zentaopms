<?php
/**
 * The model file of compile module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        return $this->dao->select('t1.id, t1.name, t1.job, t1.status, t1.createdDate,t1.testtask, t2.jkJob,t2.triggerType,t2.comment,t2.atDay,t2.atTime, t3.name as repoName, t4.name as jenkinsName')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
            ->leftJoin(TABLE_REPO)->alias('t3')->on('t2.repo=t3.id')
            ->leftJoin(TABLE_JENKINS)->alias('t4')->on('t2.jkHost=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.job')->ne('0')
            ->beginIF(!empty($jobID))->andWhere('t1.job')->eq($jobID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
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
        $url->url     = sprintf('%s/job/%s/buildWithParameters/api/json', $jenkinsServer, $jenkins->jkJob);

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
        $job = $this->dao->select('t1.id,t1.name,t1.repo,t1.jkJob,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_JENKINS)->alias('t2')->on('t1.jkHost=t2.id')
            ->where('t1.id')->eq($compile->job)
            ->fetch();

        if(!$job) return false;

        $data = new stdclass();
        $data->PARAM_TAG   = $compile->tag;
        $data->ZENTAO_DATA = "compile={$compile->id}";

        $url   = $this->getBuildUrl($job);
        $build = new stdclass();
        $build->queue      = $this->loadModel('ci')->sendRequest($url->url, $data, $url->userPWD);
        $build->status     = $build->queue ? 'created' : 'create_fail';
        $build->updateDate = helper::now();
        $this->dao->update(TABLE_COMPILE)->data($build)->where('id')->eq($compile->id)->exec();
        $this->dao->update(TABLE_JOB)->set('lastStatus')->eq($build->status)->set('lastExec')->eq($build->updateDate)->where('id')->eq($compile->job)->exec();

        return !dao::isError();
    }
}
