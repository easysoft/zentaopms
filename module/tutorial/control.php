<?php
/**
 * The control file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        $this->view->mode    = $this->setting->getItem('owner=system&module=common&section=global&key=mode');
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

        if($finish == 'keepAll') return $this->send(array('result' => 'fail', 'message' => $this->lang->tutorial->ajaxSetError));
        $account = $this->app->user->account;
        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem("$account.tutorial.tasks.setting", $finish);
        $this->session->set('tutorialMode', true);
        return $this->send(array('result' => 'success'));
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
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', 0);
        if(empty($referer)) $referer = helper::safe64Encode(helper::createLink('my', 'index', '', 'html'));
        echo js::locate(helper::safe64Decode($referer), 'parent');
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
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', 0);
        echo json_encode(array('result' => 'success'));
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
        if(!defined('TUTORIAL')) define('TUTORIAL', true);
        define('WIZARD_MODULE', $module);
        define('WIZARD_METHOD', $method);

        /* Check priv for tutorial. */
        $hasPriv = false;
        $moduleLower = strtolower($module);
        foreach($this->lang->tutorial->tasks as $task)
        {
            $taskModule     = strtolower($task['nav']['module']);
            $taskMenuModule = strtolower($task['nav']['menuModule']);
            if($taskModule == $moduleLower or $taskMenuModule == $moduleLower)
            {
                $hasPriv = true;
                break;
            }
        }
        if(!$hasPriv and $module == 'my' and $method == 'index') $hasPriv = true;
        if(!$hasPriv) return print(js::locate('back'));

        $params = helper::safe64Decode($params);
        if($_POST)
        {
            $target = 'parent';
            if(($module == 'story' or $module == 'task' or $module == 'bug') and $method == 'create') $target = 'self';
            if($module == 'execution' and $method == 'linkStory') $target = 'self';
            if($module == 'execution' and $method == 'managemembers') $target = 'self';

            if(helper::isAjaxRequest()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('tutorial', 'wizard', "module=$module&method=$method&params=" . helper::safe64Encode($params))));
            return print(js::locate(helper::createLink('tutorial', 'wizard', "module=$module&method=$method&params=" . helper::safe64Encode($params)), $target));
        }
        echo $this->fetch($module, $method, $params);
    }

    /**
     * Ajax save novice result.
     *
     * @param  string $novice
     * @param  string $reload
     *
     * @access public
     * @return void
     */
    public function ajaxSaveNovice($novice = 'true', $reload = 'false')
    {
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', $novice == true ? 1 : 0);
        if($reload == 'true') return print(js::reload('parent'));
    }

    /**
     * Ajax save tutorial score.
     *
     * @access public
     * @return void
     */
    public function ajaxFinish()
    {
        $tutorialMode = $this->session->tutorialMode;
        $this->session->set('tutorialMode', false);
        $this->loadModel('score')->create('tutorial', 'finish');
        $this->session->set('tutorialMode', $tutorialMode);
    }
}
