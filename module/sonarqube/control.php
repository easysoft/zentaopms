<?php
/**
 * The control file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: ${FILE_NAME} 5144 2022/1/11 8:55 上午 caoyanyi@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class sonarqube extends control
{
    /**
     * The mr constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* This is essential when changing tab(menu) from gitlab to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse sonarqube.
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

        $sonarqubeList = $this->sonarqube->getList($orderBy, $pager);

        $this->view->title         = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browse;
        $this->view->sonarqubeList = $sonarqubeList;
        $this->view->orderBy       = $orderBy;
        $this->view->pager         = $pager;

        $this->display();
    }

    /**
     * Ajax get project select.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return void
     */
    public function ajaxGetProjectList($sonarqubeID, $projectKey = '')
    {
        $projectList = $this->loadModel('sonarqube')->apiGetProjects($sonarqubeID);

        $projectPairs = array('' => '');
        foreach($projectList as $project) $projectPairs[$project->key] = $project->name;

        echo html::select('projectKey', $projectPairs, str_replace('*', '-', $projectKey), "class='form-control chosen'");
    }

    /**
     * create a sonarqube.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->checkToken();
            $sonarqubeID = $this->loadModel('pipeline')->create('sonarqube');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('sonarqube', $sonarqubeID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->createServer;

        $this->display();
    }

    /**
     * Check post info.
     *
     * @param  int    $sonarqubeID
     * @access protected
     * @return void
     */
    protected function checkToken($sonarqubeID = 0)
    {
        $sonarqube = fixer::input('post')->get();
        $this->dao->update('sonarqube')->data($sonarqube)->batchCheck(empty($sonarqubeID) ? $this->config->sonarqube->create->requiredFields : $this->config->sonarqube->edit->requiredFields, 'notempty');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(strpos($sonarqube->url, 'http') !== 0) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->sonarqube->hostError))));

        /* Check name and url unique. */
        $existSonarQube = $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where("type='sonarqube' and (name='{$sonarqube->name}' or url='{$sonarqube->url}')")
            ->beginIF(!empty($sonarqubeID))->andWhere('id')->ne($sonarqubeID)->fi()
            ->fetch();
        if(isset($existSonarQube->name) and $existSonarQube->name == $sonarqube->name) return $this->send(array('result' => 'fail', 'message' => $this->lang->sonarqube->nameRepeatError));
        if(isset($existSonarQube->url) and $existSonarQube->url== $sonarqube->url) return $this->send(array('result' => 'fail', 'message' => $this->lang->sonarqube->urlRepeatError));

        $token  = base64_encode("{$sonarqube->account}:{$sonarqube->password}");
        $result = $this->sonarqube->apiValidate($sonarqube->url, $token);

        if(!isset($result->valid) or !$result->valid) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->sonarqube->validError))));
        $this->post->set('token', $token);
    }

    /**
     * Edit a sonarqube.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return void
     */
    public function edit($sonarqubeID)
    {
        $oldSonarQube = $this->sonarqube->getByID($sonarqubeID);

        if($_POST)
        {
            $this->checkToken($sonarqubeID);
            $this->loadModel('pipeline')->update($sonarqubeID);
            $sonarqube = $this->sonarqube->getByID($sonarqubeID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('sonarqube', $sonarqubeID, 'edited');
            $changes  = common::createChanges($oldSonarQube, $sonarqube);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->editServer;
        $this->view->sonarqube = $oldSonarQube;

        $this->display();
    }

    /**
     * Delete a sonarqube.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return void
     */
    public function delete($sonarqubeID, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->sonarqube->confirmDelete, inlink('delete', "sonarqubeID=$sonarqubeID&confirm=yes")));

        $oldSonarQube = $this->sonarqube->getByID($sonarqubeID);
        $this->loadModel('action');
        $actionID = $this->loadModel('pipeline')->delete($sonarqubeID, 'sonarqube');

        $sonarQube = $this->sonarqube->getByID($sonarqubeID);
        $changes   = common::createChanges($oldSonarQube, $sonarQube);
        $this->action->logHistory($actionID, $changes);
        echo js::reload('parent');
    }

    /**
     * Exec job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function execJob($jobID)
    {
        echo $this->fetch('job', 'exec', "jobID=$jobID");
    }

    /**
     * Browse sonarqube project.
     *
     * @param  int    $sonarqubeID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseProject($sonarqubeID, $orderBy = 'name_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $keyword = fixer::input('post')->setDefault('keyword', '')->get('keyword');

        $sonarqubeProjectList = $this->sonarqube->apiGetProjects($sonarqubeID, $keyword);
        foreach($sonarqubeProjectList as $key => $sonarqubeProject) !isset($sonarqubeProject->lastAnalysisDate) ? $sonarqubeProject->lastAnalysisDate = '' : '';

         /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($sonarqubeProjectList as $sonarqubeProject) $orderList[] = $sonarqubeProject->$order;
        array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $sonarqubeProjectList);

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal = count($sonarqubeProjectList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $sonarqubeProjectList = array_chunk($sonarqubeProjectList, $pager->recPerPage);

        $this->view->sonarqube            = $this->sonarqube->getByID($sonarqubeID);
        $this->view->keyword              = urldecode(urldecode($keyword));
        $this->view->pager                = $pager;
        $this->view->title                = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browseProject;
        $this->view->sonarqubeID          = $sonarqubeID;
        $this->view->sonarqubeProjectList = empty($sonarqubeProjectList) ? $sonarqubeProjectList: $sonarqubeProjectList[$pageID - 1];
        $this->view->orderBy              = $orderBy;
        $this->display();
    }
}
