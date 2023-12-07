<?php
declare(strict_types=1);
/**
 * The control file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: control.php 5002 2013-07-03 08:25:39Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class tutorial extends control
{
    /**
     * 新手教程开始页面。
     * Tutorial start page.
     *
     * @access public
     * @return void
     */
    public function start()
    {
        $this->view->title = $this->lang->tutorial->common;
        $this->display();
    }

    /**
     * 新手教程页面。
     * Tutorial page.
     *
     * @access public
     * @return void
     */
    public function index(string $referer = '', string $task = '')
    {
        $setting = isset($this->config->tutorial->tasks->setting) ? $this->config->tutorial->tasks->setting : '';

        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', 0);
        $this->session->set('tutorialMode', true);

        $this->view->title   = $this->lang->tutorial->common;
        $this->view->current = $task;
        $this->view->tasks   = $this->config->tutorial->tasksConfig;
        $this->view->setting = $setting;
        $this->view->referer = base64_decode($referer);
        $this->view->mode    = $this->setting->getItem('owner=system&module=common&section=global&key=mode');
        $this->display();
    }

    /**
     * Ajax设置任务。
     * Ajax set tasks
     *
     * @param  string $finish
     * @access public
     * @return string
     */
    public function ajaxSetTasks(string $finish = 'keepAll')
    {
        if($_POST && isset($_POST['finish'])) $finish = $_POST['finish'];

        if($finish == 'keepAll') return $this->send(array('result' => 'fail', 'alert' => $this->lang->tutorial->ajaxSetError));

        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem("{$this->app->user->account}.tutorial.tasks.setting", $finish);
        $this->session->set('tutorialMode', true);
        return $this->send(array('result' => 'success'));
    }

    /**
     * 退出新手教程模式。
     * Exit tutorial mode.
     *
     * @access public
     * @return void
     */
    public function quit()
    {
        $this->session->set('tutorialMode', false);
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.novice', 0);
        return $this->send(array('result' => 'success', 'open' => $this->createLink('index', 'index')));
    }

    /**
     * 通过ajax退出新手教程模式。
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
     * 向导页面。
     * Wizard.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function wizard(string $module, string $method, string $params = '')
    {
        if(!commonModel::isTutorialMode()) $_SESSION['tutorialMode'] = true;
        define('WIZARD_MODULE', $module);
        define('WIZARD_METHOD', $method);

        /* Check priv for tutorial. */
        $hasPriv = false;
        $moduleLower = strtolower($module);
        foreach($this->config->tutorial->tasksConfig as $task)
        {
            $taskModule     = strtolower($task['nav']['module']);
            $taskMenuModule = strtolower($task['nav']['menuModule']);
            if($taskModule == $moduleLower || $taskMenuModule == $moduleLower)
            {
                $hasPriv = true;
                break;
            }
        }
        if(!$hasPriv && $module == 'my' && $method == 'index') $hasPriv = true;
        if(!$hasPriv)
        {
            if(helper::isAjaxRequest()) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied, 'load' => array('back' => true)));
            return print(js::locate('back'));
        }

        $params = helper::safe64Decode($params);
        if($_POST)
        {
            $target = 'parent';
            if(($module == 'story' || $module == 'task' || $module == 'bug') && $method == 'create') $target = 'self';
            if($module == 'execution' && ($method == 'linkStory' || $method == 'managemembers')) $target = 'self';

            if(helper::isAjaxRequest()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink('tutorial', 'wizard', "module={$module}&method={$method}&params=" . helper::safe64Encode($params))));
            return print(js::locate(helper::createLink('tutorial', 'wizard', "module={$module}&method={$method}&params=" . helper::safe64Encode($params)), $target));
        }
        echo $this->fetch($module, $method, $params);
    }

    /**
     * 通过ajax保存新手教程结果。
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
     * 通过ajax保存新手教程进度。
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
