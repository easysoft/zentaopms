<?php
/**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * Main function.
     * 
     * @access public
     * @return void
     */
    public function main()
    {
        $lang = $this->get->lang;
        $this->app->setClientLang($lang);
        $this->app->loadLang('common');
        $this->app->loadLang('block');

        $mode = strtolower($this->get->mode);
        if($mode == 'getblocklist')
        {   
            echo $this->block->getAvailableBlocks();
        }   
        elseif($mode == 'getblockform')
        {   
            $code = strtolower($this->get->blockid);
            $func = 'get' . ucfirst($code) . 'Params';
            echo $this->block->$func();
        }   
        elseif($mode == 'getblockdata')
        {
            $code = strtolower($this->get->blockid);

            $params = $this->get->param;
            $params = json_decode(base64_decode($params));
            $sso    = base64_decode($this->get->sso);

            if(!isset($this->app->user)) $this->app->user = new stdclass();
            if(!isset($this->app->user->account) or $this->app->user->account != $params->account) $this->app->user->account = $params->account;

            $this->params     = $params;
            $this->view->sso  = $sso;
            $this->view->sign = strpos($sso, '&') === false ? '?' : '&';
            $this->view->code = $this->get->blockid;

            $func = 'print' . ucfirst($code) . 'Block';
            $this->$func();
        }
    }

    /**
     * Print todo block.
     * 
     * @access public
     * @return void
     */
    public function printTodoBlock()
    {
        $this->view->todos = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $this->params->num);
        $this->display();
    }

    /**
     * Print task block.
     * 
     * @access public
     * @return void
     */
    public function printTaskBlock()
    {
        $this->view->tasks = $this->loadModel('task')->getUserTasks($this->app->user->account, $this->params->type, $this->params->num, null, $this->params->orderBy);
        $this->display();
    }

    /**
     * Print bug block.
     * 
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->params->num);

        $this->display();
    }

    /**
     * Print case block.
     * 
     * @access public
     * @return void
     */
    public function printCaseBlock()
    {
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

        $cases = array();
        if($this->params->type == 'assigntome')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($this->params->orderBy)->limit($this->params->num)->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($this->params->orderBy)->limit($this->params->num)->fetchAll();
        }
        $this->view->cases = $cases;

        $this->display();
    }

    /**
     * Print story block.
     * 
     * @access public
     * @return void
     */
    public function printStoryBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->stories = $this->loadModel('story')->getUserStories($this->app->user->account, $this->params->type, $this->params->orderBy, $pager);
        $this->display();
    }

    /**
     * Print product block.
     * 
     * @access public
     * @return void
     */
    public function printProductBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->productStats = $this->loadModel('product')->getStats('code_asc', $pager);

        $this->display();
    }

    /**
     * Print project block.
     * 
     * @access public
     * @return void
     */
    public function printProjectBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->projectStats = $this->loadModel('project')->getProjectStats($status = 'undone', $productID = 0, $itemCounts = 30, $orderBy = 'code', $pager);

        $this->display();
    }
}
