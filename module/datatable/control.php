<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     business(商业软件) 
 * @author      Hao sun <sunhao@cnezsoft.com>
 * @package     datatable 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class datatable extends control
{
    /**
     * Construct function, set menu. 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Save config
     * 
     * @access public
     * @return void
     */
    public function ajaxSave()
    {
        if(!empty($_POST))
        {
            $account = $this->app->user->account;
            if($account == 'guest') $this->send(array('result' => 'fail', 'target' => $target, 'message' => 'guest.'));

            $name = $account . '.datatable.' . $this->post->target . '.' . $this->post->name;
            $this->loadModel('setting')->setItem($name, $this->post->value);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => 'dao error.'));
            $this->send(array('result' => 'success'));
        }
    }

    /**
     * custom fields.
     * 
     * @param  string $module 
     * @param  string $method 
     * @access public
     * @return void
     */
    public function ajaxCustom($module, $method)
    {
        $account = $this->app->user->account;
        $name = 'owner=' . $account . '&module=datatable&section=' . $module . ucfirst($method) . '&key=cols';
        if($module == 'testtask')
        {
            $this->loadModel('testcase');
            $this->app->loadConfig('testtask');
            $this->config->testcase->datatable->defaultField = $this->config->testtask->datatable->defaultField;
            $this->config->testcase->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
            $this->config->testcase->datatable->fieldList['assignedTo']['fixed']    = 'no';
            $this->config->testcase->datatable->fieldList['assignedTo']['width']    = '80';
            $this->config->testcase->datatable->fieldList['assignedTo']['required'] = 'no';
            $this->config->testcase->datatable->fieldList['actions']['width']       = '100';
        }

        $module = zget($this->config->datatable->moduleAlias, $module, $module);
        $this->view->cols    = $this->datatable->getFieldList($module);
        $this->view->setting = $this->loadModel('setting')->getItem($name);
        if(empty($this->view->setting)) $this->view->setting = json_encode($this->config->$module->datatable->defaultField);

        $this->display();
    }
}
