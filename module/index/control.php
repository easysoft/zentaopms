<?php
/**
 * The control file of index module of ZenTaoMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class index extends control
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('project');
        $this->loadModel('product');
    }

    public function index()
    {
        $this->loadModel('report');
        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');
        $this->app->loadLang('todo');
        $this->view->header->title = $this->lang->index->common;

        /* 项目的统计数据。*/
        $burns    = array();
        $projects = $this->project->getList('doing');
        foreach($projects as $project)
        {
            $dataXML = $this->report->createSingleXML($this->project->getBurnData($project->id), $this->lang->project->charts->burn->graph);
            $burns[$project->id] = $this->report->createJSChart('line', $dataXML, 'auto', 220);
        }

        /* 综合的统计数据。*/
        $stats['project'] = $this->dao->select('status, count(*) as count')->from(TABLE_PROJECT)->groupBy('status')->fetchPairs();
        $stats['product'] = $this->dao->select('status, count(*) as count')->from(TABLE_PRODUCT)->groupBy('status')->fetchPairs();
        $stats['task']    = $this->dao->select('status, count(*) as count')->from(TABLE_TASK)->groupBy('status')->fetchPairs();
        $stats['story']   = $this->dao->select('status, count(*) as count')->from(TABLE_STORY)->groupBy('status')->fetchPairs();
        $stats['bug']     = $this->dao->select('status, count(*) as count')->from(TABLE_BUG)->groupBy('status')->fetchPairs();
        $stats['todo']    = $this->dao->select('status, count(*) as count')->from(TABLE_TODO)->groupBy('status')->fetchPairs();

        /* 当前用户的相关任务、todo和bug。*/
        $my['tasks'] = $this->dao->select('id, name')->from(TABLE_TASK)->where('owner')->eq($this->session->user->account)->andWhere('deleted')->eq(0)->andWhere('status')->in('wait,doing')->orderBy('id desc')->limit(10)->fetchPairs();
        $my['bugs']  = $this->dao->select('id, title')->from(TABLE_BUG)->where('assignedTo')->eq($this->session->user->account)->andWhere('deleted')->eq(0)->orderBy('id desc')->limit(10)->fetchPairs();
        $my['todos'] = $this->loadModel('todo')->getList('all', $this->session->user->account, 'wait, doing');

        $this->view->projects = $projects;
        $this->view->burns    = $burns;
        $this->view->stats    = $stats;
        $this->view->my       = $my;
        $this->view->actions  = $this->loadModel('action')->getDynamic('all', 23);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->users['guest']= 'guest';    // append the guest account.
        $this->display();
    }

    /* 测试扩展机制。*/
    public function testext()
    {
        echo $this->fetch('misc', 'getsid');
    }
}
