<?php
/**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * construct. 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if(!$this->loadModel('sso')->checkKey()) die('');
    }

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

            $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($params->account)->fetch();
            if(empty($this->app->user)) 
            {
                $this->app->user = new stdclass();
                $this->app->user->account = 'guest';
            }

            $this->viewType   = (isset($params->viewType) and $params->viewType == 'json') ? 'json' : 'html';
            $this->params     = $params;
            $this->view->sso  = $sso;
            $this->view->sign = strpos($sso, '&') === false ? '?' : '&';
            $this->view->code = $this->get->blockid;

            $func = 'print' . ucfirst($code) . 'Block';
            $this->$func();

            if($this->viewType == 'json')
            {
                unset($this->view->app);
                unset($this->view->config);
                unset($this->view->lang);
                unset($this->view->header);
                unset($this->view->position);
                unset($this->view->moduleTree);

                $output['status'] = is_object($this->view) ? 'success' : 'fail';
                $output['data']   = json_encode($this->view);
                $output['md5']    = md5(json_encode($this->view));
                die(json_encode($output));
            }

            $this->display();
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
        $this->view->todos    = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $this->viewType == 'json' ? 0 : $this->params->num);
        $this->view->listLink = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'todo', "type=all"));
    }

    /**
     * Print task block.
     * 
     * @access public
     * @return void
     */
    public function printTaskBlock()
    {
        $this->view->tasks    = $this->loadModel('task')->getUserTasks($this->app->user->account, $this->params->type, $this->viewType == 'json' ? 0 : $this->params->num, null, $this->params->orderBy);
        $this->view->listLink = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'task', "type={$this->params->type}"));
    }

    /**
     * Print bug block.
     * 
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->view->bugs     = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : $this->params->num);
        $this->view->listLink = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'bug', "type={$this->params->type}"));
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
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
                ->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
                ->fetchAll();
        }
        $this->view->cases    = $cases;
        $this->view->listLink = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'testcase', "type={$this->params->type}"));
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
        $this->view->stories  = $this->loadModel('story')->getUserStories($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType != 'json' ? $pager : '');
        $this->view->listLink = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'story', "type={$this->params->type}"));
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
        $this->view->productStats = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '');
        $this->view->listLink     = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('product', 'index', "locate=no&productID=" . current($this->view->productStats)->id));
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
        $this->view->projectStats = $this->loadModel('project')->getProjectStats($status = 'undone', $productID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'order_desc', $this->viewType != 'json' ? $pager : '');
        $this->view->listLink     = $this->view->sso . $this->view->sign . 'referer=' . base64_encode($this->createLink('my', 'project'));
    }
}
