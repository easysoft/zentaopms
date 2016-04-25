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
        if($_POST)
        {
            $account = $this->app->user->account;
            $setting   = $_POST['finish'];
            
            $this->loadModel('setting')->setItem("$account.tutorial.tasks.setting", $setting);
            $this->send(array('result' => 'success'));
        }
        $setting = isset($this->config->tutorial->tasks->setting) ? $this->config->tutorial->tasks->setting : '';

        if($this->viewType === 'json')
        {
            die(json_encode(array('result' => isset($setting) ? 'success' : 'fail', 'setting' => $setting), JSON_HEX_QUOT | JSON_HEX_APOS));
        }

        $this->view->title   = $this->lang->tutorial->common;
        $this->view->current = $task;
        $this->view->setting = $setting;
        $this->view->referer = base64_decode($referer);
        $this->display();
    }
}
