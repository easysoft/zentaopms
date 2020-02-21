<?php
/**
 * The model file of integration module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     integration
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class integrationModel extends model
{
    /**
     * Get by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_INTEGRATION)->where('id')->eq($id)->fetch();
    }

    /**
     * Get integration list.
     * 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as repoName, t3.name as jenkinsName')->from(TABLE_INTEGRATION)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_JENKINS)->alias('t3')->on('t1.jenkins=t3.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get list by triggerType field
     * 
     * @param  string    $triggerType 
     * @access public
     * @return array
     */
    public function getListByTriggerType($triggerType)
    {
        return $this->dao->select('*')->from(TABLE_INTEGRATION)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->eq($triggerType)
            ->fetchAll('id');
    }

    /**
     * Create integration
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $integration = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('repoType')
            ->get();
        if($integration->triggerType == 'schedule')
        {
            if(!isset($integration->scheduleDay)) $integration->scheduleDay = array();
            $integration->scheduleDay = join(',', $integration->scheduleDay);
        }

        $this->dao->insert(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->create->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule', "scheduleDay", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->exec();
        return true;
    }

    /**
     * Update integration
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function update($id)
    {
        $integration = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('repoType')
            ->get();
        if($integration->triggerType == 'schedule')
        {
            if(!isset($integration->scheduleDay)) $integration->scheduleDay = array();
            $integration->scheduleDay = join(',', $integration->scheduleDay);
        }

        $this->dao->update(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->edit->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule', "scheduleDay", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return true;
    }

    /**
     * Execute integration
     * 
     * @param  int    $integrationID 
     * @access public
     * @return bool
     */
    public function exec($integrationID)
    {
        $integration = $this->dao->select('t1.id as jobId,t1.name as jobName,t1.repo,t1.jenkinsJob,t2.name as jenkinsName,t2.serviceUrl,t2.account,t2.token,t2.password')
            ->from(TABLE_INTEGRATION)->alias('t1')
            ->leftJoin(TABLE_JENKINS)->alias('t2')->on('t1.jenkins=t2.id')
            ->where('t1.id')->eq($integrationID)
            ->fetch();

        if(!$integration) return false;

        $jenkinsServer   = $integration->serviceUrl;
        $jenkinsUser     = $integration->account;
        $jenkinsPassword = $integration->token ? $integration->token : base64_decode($integration->password);

        $jenkinsAuth   = '://' . $jenkinsUser . ':' . $jenkinsPassword . '@';
        $jenkinsServer = str_replace('://', $jenkinsAuth, $jenkinsServer);
        $buildUrl      = sprintf('%s/job/%s/buildWithParameters/api/json', $jenkinsServer, $integration->jenkinsJob);

        $data = new stdClass();
        //  TODO: 将参数加入$data，代码完成后删除注释
        //  PARAM_TAG：git tag name or svn tag url
        //  PARAM_REVISION：git revision string or svn revision number
        $data->PARAM_TAG      = "tag111";
        $data->PARAM_REVISION = "6c3a7bf931855842e261c7991bb143c1e0c3fb19";

        $integration->queueItem = $this->loadModel('ci')->sendRequest($buildUrl, $data);
        $this->loadModel('compile')->saveBuild($integration);

        return !dao::isError();
    }
}
