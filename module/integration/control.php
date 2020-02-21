<?php
/**
 * The control file of integration of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     integration
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class integration extends control
{
    /**
     * Construct 
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse ci job.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('compile');
        $this->view->jobList = $this->integration->getList($orderBy, $pager);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->integration->browse;
        $this->view->position[] = $this->lang->ci->job;
        $this->view->position[] = $this->lang->integration->browse;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create a ci job.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->integration->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->integration->create;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->ci->job);
        $this->view->position[] = $this->lang->integration->create;

        $repoList  = $this->loadModel('repo')->getAllRepos();
        $repoPairs = array(0 => '');
        $repoTypes = array();
        foreach($repoList as $repo)
        {
            $repoPairs[$repo->id] = $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
        }
        $this->view->repoPairs   = $repoPairs;
        $this->view->repoTypes   = $repoTypes;
        $this->view->jenkinsList = $this->loadModel('jenkins')->getPairs();

        $this->display();
    }

    /**
     * Edit a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $job = $this->integration->getByID($id);
        if($_POST)
        {
            $this->integration->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');

        $this->view->title       = $this->lang->ci->job . $this->lang->colon . $this->lang->integration->edit;
        $this->view->position[]  = html::a(inlink('browse'), $this->lang->ci->job);
        $this->view->position[]  = $this->lang->integration->edit;

        $repo      = $this->loadModel('repo')->getRepoByID($job->repo);
        $repoList  = $this->loadModel('repo')->getAllRepos();
        $repoPairs = array(0 => '', $repo->id => $repo->name);
        $repoTypes[$repo->id] = $repo->SCM;
        foreach($repoList as $repo)
        {
            $repoPairs[$repo->id] = $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
        }

        $this->view->repoPairs   = $repoPairs;
        $this->view->repoTypes   = $repoTypes;
        $this->view->job         = $job;
        $this->view->jenkinsList = $this->loadModel('jenkins')->getPairs();

        $this->display();
    }

    /**
     * Delete a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->integration->confirmDelete, inlink('delete', "jobID=$id&confirm=yes")));

        $this->integration->delete(TABLE_INTEGRATION, $id);

        $command = 'moduleName=ci&methodName=exe&parm=' . $id;
        $this->dao->delete()->from(TABLE_CRON)->where('command')->eq($command)->exec();

        die(js::reload('parent'));
    }

    /**
     * Exec a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function exec($id)
    {
        $result = $this->integration->exec($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(!$result) $this->send(array('result' => 'fail', 'message' => 'not found'));

        $this->send(array('result' => 'success'));
    }
}
