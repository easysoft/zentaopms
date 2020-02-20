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

        $this->dao->insert(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->create->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule' && $integration->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($integration->triggerType === 'schedule' && $integration->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->exec();

        $integrationId = $this->dao->lastInsertID();
        if($integration->triggerType =='schedule')
        {
            $arr  = explode(":", $integration->scheduleTime);
            $hour = $arr[0];
            $min  = $arr[1];

            if($integration->scheduleDay == 'everyDay')
            {
                $days = '1-7';
            }
            elseif($integration->scheduleDay == 'workDay')
            {
                $days = '1-5';
            }

            $cron = (object)array('m' => $min, 'h' => $hour, 'dom' => '*', 'mon' => '*',
                'dow' => $days . '/' . $integration->scheduleInterval, 'command' => 'moduleName=ci&methodName=exeJob&parm=' . $integrationId,
                'remark' => ($this->lang->ci->extJob . $integrationId), 'type' => 'zentao',
                'buildin' => '-1', 'status' => 'normal', 'lastTime' => '0000-00-00 00:00:00');
            $this->dao->insert(TABLE_CRON)->data($cron)->exec();
        }

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

        $this->dao->update(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->edit->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule' && $integration->scheduleType == 'cron', "cronExpression", 'notempty')
            ->batchCheckIF($integration->triggerType === 'schedule' && $integration->scheduleType == 'custom', "scheduleDay,scheduleTime,scheduleInterval", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnFolder", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        if ($integration->triggerType === 'schedule')
        {
            $command = 'moduleName=ci&methodName=exeJob&parm=' . $id;

            $arr  = explode(":", $integration->scheduleTime);
            $hour = $arr[0];
            $min  = $arr[1];

            if($integration->scheduleDay == 'everyDay')
            {
                $days = '1-7';
            }
            elseif($integration->scheduleDay == 'workDay')
            {
                $days = '2-6';
            }

            $this->dao->update(TABLE_CRON)
                ->set('m')->eq($min)
                ->set('h')->eq($hour)
                ->set('dom')->eq('*')
                ->set('mon')->eq('*')
                ->set('dow')->eq($days . '/' . $integration->scheduleInterval)
                ->set('lastTime')->eq('0000-00-00 00:00:00')
                ->where('command')->eq($command)->exec();
        }

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

        if (!$integration) return false;

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
