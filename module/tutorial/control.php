<?php
/**
 * The control file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: control.php 5002 2013-07-03 08:25:39Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class tutorial extends control
{
    /**
     * Start page
     * @access public
     * @return void
     */
    public function start()
    {
        $this->view->title = $this->lang->tutorial->common;
        $this->display();
    }

    /**
     * Index page
     * @access public
     * @return void
     */
    public function index($referer = '', $task = '')
    {
        $setting = isset($this->config->tutorial->tasks->setting) ? $this->config->tutorial->tasks->setting : '';

        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', true);
        $this->session->set('tutorialMode', true);

        $this->view->title   = $this->lang->tutorial->common;
        $this->view->current = $task;
        $this->view->setting = $setting;
        $this->view->referer = base64_decode($referer);
        $this->display();
    }

    /**
     * Ajax set tasks
     *
     * @param  string $finish
     * @access public
     * @return void
     */
    public function ajaxSetTasks($finish = 'keepAll')
    {
        if($_POST && isset($_POST['finish'])) $finish = $_POST['finish'];

        if($finish == 'keepAll') $this->send(array('result' => 'fail', 'message' => $this->lang->tutorial->ajaxSetError));

        $account = $this->app->user->account;
        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem("$account.tutorial.tasks.setting", $finish);
        $this->session->set('tutorialMode', true);
        $this->send(array('result' => 'success'));
    }

    /**
     * Exit tutorial mode
     * 
     * @param  string $referer
     * @access public
     * @return void
     */
    public function quit($referer = '')
    {
        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', false);

        if(empty($referer)) $referer = $this->createLink('index');
        die(js::locate(helper::safe64Decode($referer), 'parent'));
    }

    /**
     * Ajax quit tutorial mode
     * 
     * @access public
     * @return void
     */
    public function ajaxQuit()
    {
        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', false);
        die(json_encode(array('result' => 'success')));
    }

    /**
     * Wizard. 
     * 
     * @param  string $module 
     * @param  string $method 
     * @param  string $params 
     * @access public
     * @return void
     */
    public function wizard($module, $method, $params = '')
    {
        define('TUTORIAL',      true);
        define('WIZARD_MODULE', $module);
        define('WIZARD_METHOD', $method);
        $params = helper::safe64Decode($params);
        if($_POST)
        {
            $target = 'parent';
            if(($module == 'story' or $module == 'task' or $module == 'bug') and $method == 'create') $target = 'self';
            if($module == 'project' and $method == 'linkStory') $target = 'self';
            if($module == 'project' and $method == 'managemembers') $target = 'self';
            die(js::locate(helper::createLink('tutorial', 'wizard', "module=$module&method=$method&params=" . helper::safe64Encode($params)), $target));
        }
        die($this->fetch($module, $method, $params));
    }

    /**
     * Ajax save novice result.
     * 
     * @param  string $novice 
     * @access public
     * @return void
     */
    public function ajaxSaveNovice($novice = 'true', $reload = 'false')
    {
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', $novice);
        if($reload == 'true') die(js::reload('parent'));
    }
}
