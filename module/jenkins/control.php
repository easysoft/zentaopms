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
        $this->view->position[] = $this->lang->jenkins->common;
        $this->view->position[] = $this->lang->jenkins->browse;

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
            $jenkinsID = $this->jenkins->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $jenkinsID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->create;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[] = $this->lang->jenkins->create;

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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->edit;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[] = $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;

        $this->display();
    }

    /**
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confim = 'no')
    {
        if($confim != 'yes') return print(js::confirm($this->lang->jenkins->confirmDelete, inlink('delete', "id=$id&confirm=yes")));

        $jobs = $this->dao->select('*')->from(TABLE_JOB)->where('server')->eq($id)->andWhere('engine')->eq('jenkins')->andWhere('deleted')->eq('0')->fetchAll();
        if($jobs) return print(js::alert($this->lang->jenkins->error->linkedJob));

        $this->jenkins->delete(TABLE_PIPELINE, $id);
        echo js::reload('parent');
    }

    /**
     * AJAX: Get jenkins tasks.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function ajaxGetJenkinsTasks($id)
    {
        if(empty($id)) return print('');

        $this->view->tasks = $this->jenkins->getTasks($id, 3);
        $this->display();
    }
}
