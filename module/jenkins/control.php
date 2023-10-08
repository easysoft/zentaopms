<?php

/**
 * The control file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class jenkins extends control
{
    /**
     * jenkins constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        if(stripos($this->methodName, 'ajax') === false)
        {
            if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        }
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse jenkinss.
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

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->browse;

        $this->view->jenkinsList = $this->jenkins->getList($orderBy, $pager);
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;

        $this->display();
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $jenkins = form::data($this->config->jenkins->form->create)
                ->add('type', 'jenkins')
                ->add('private',md5(rand(10,113450)))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->trim('url,token,account,password')
                ->skipSpecial('url,token,account,password')
                ->remove('appType')
                ->get();
            $jenkinsID = $this->loadModel('pipeline')->create($jenkins);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('jenkins', $jenkinsID, 'created');
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $jenkinsID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->create;

        $this->display();
    }

    /**
     * Edit a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $jenkins = $this->jenkins->getByID($id);
        if($_POST)
        {
            $this->jenkins->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newJenkins = $this->jenkins->getByID($id);
            $actionID   = $this->loadModel('action')->create('jenkins', $id, 'edited');
            $changes    = common::createChanges($jenkins, $newJenkins);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;

        $this->display();
    }

    /**
     * 删除一条jenkins数据。
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $jobs = $this->dao->select('*')->from(TABLE_JOB)->where('server')->eq($id)->andWhere('engine')->eq('jenkins')->andWhere('deleted')->eq('0')->fetchAll();
        if($jobs)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->jenkins->error->linkedJob);

            return $this->send($response);
        }

        $this->jenkins->delete(TABLE_PIPELINE, $id);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';

        return $this->send($response);
    }

    /**
     * AJAX: Get jenkins tasks.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function ajaxGetJenkinsTasks($id = 0)
    {
        $this->app->loadLang('job');

        $tasks = array();
        if($id) $tasks = $this->jenkins->getTasks($id, 3);

        $this->view->tasks = $this->jenkinsZen->buildTree($tasks);
        $this->display();
    }
}
