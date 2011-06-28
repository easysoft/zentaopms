<?php
/**
 * The control file of index module of ZenTaoPMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class index extends control
{
    /**
     * Construct function, load project, product.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The index page of whole zentao system.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->header->title = $this->lang->index->common;

        $projectStats = $this->loadModel('project')->getProjectStats();
        $productStats = $this->loadModel('product')->getStats();

        /* Set the dynamic pager. */
        $maxCounts = max(count($projectStats['projects']), count($productStats['products']));   // Get the max counts of projects and products, thus to get more dynamics to keep smae high.
        $this->app->loadClass('pager', true);
        $pager = new pager(0, $this->config->index->dynamicCounts + $maxCounts);

        $this->view->projectStats  = $projectStats;
        $this->view->productStats  = $productStats;
        $this->view->actions       = $this->loadModel('action')->getDynamic('all', 'all', 'id_desc', $pager);
        $this->view->todos         = $this->loadModel('todo')->getList('today', $this->app->user->account, 'wait, doing', $this->config->index->todoCounts);
        $this->view->tasks         = $this->loadModel('task')->getUserTasks($this->app->user->account, 'assignedTo', $this->config->index->taskCounts);
        $this->view->bugs          = $this->loadModel('bug')->getUserBugPairs($this->app->user->account, false, $this->config->index->bugCounts);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter|withguest');
        $this->display();
    }

    /**
     * Just test the extension engine.
     * 
     * @access public
     * @return void
     */
    public function testext()
    {
        echo $this->fetch('misc', 'getsid');
    }
}
