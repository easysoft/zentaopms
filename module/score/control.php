<?php
/**
 * The control file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: control.php $
 * @link        http://www.zentao.net
 */
class score extends control
{
    /**
     * score constructor.
     *
     * @param string $module
     * @param string $method
     *
     * @access public
     * @return mixed
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('my')->setMenu();
    }

    /**
     * Get score list
     *
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     *
     * @access public
     * @return mixed
     */
    public function browse($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $scores = $this->score->getScores($pager);

        $this->loadModel('score')->score('user','login');

        $this->view->title  = $this->lang->score->common;
        $this->view->user   = $this->loadModel('user')->getById($this->app->user->account);
        $this->view->pager  = $pager;
        $this->view->scores = $scores;
        $this->display();
    }

    /**
     * Ajax action score
     *
     * @param string $method
     *
     * @access public
     * @return void
     */
    public function ajax($method = '')
    {
        //处理由ajax提交过来的任务,主要是一次性任务 在请求模型的时候model=ajax,
        $this->loadModel('score')->score('ajax','login');
    }
}