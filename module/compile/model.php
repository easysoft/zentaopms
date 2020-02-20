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
        return $this->dao->select('t1.id, t1.name, t1.status, t1.createdDate, t2.triggerType, t3.name as repoName, t4.name as jenkinsName')->from(TABLE_COMPILE)->alias('t1')
            ->leftJoin(TABLE_INTEGRATION)->alias('t2')->on('t1.cijob=t2.id')
            ->leftJoin(TABLE_REPO)->alias('t3')->on('t2.repo=t3.id')
            ->leftJoin(TABLE_JENKINS)->alias('t4')->on('t2.jenkins=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.cijob')->eq($jobID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

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
     * Save build by job
     * 
     * @param  object    $job 
     * @access public
     * @return void
     */
    public function saveBuild($job)
    {
        $build = new stdClass();
        $build->cijob       = $job->jobId;
        $build->name        = $job->jobName;
        $build->queueItem   = $job->queueItem;
        $build->status      = $job->queueItem ? 'created' : 'create_fail';
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = helper::now();

        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
    }
}
