<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /**
     * Set menu.
     * 
     * @access public
     * @return void
     */
    public function setMenu()
    {
        /* Adjust the menu order according to the user role. */
        $flowModule = $this->config->global->flow . '_my';
        $customMenu = isset($this->config->customMenu->$flowModule) ? $this->config->customMenu->$flowModule : array();

        if(empty($customMenu))
        {
            $role = $this->app->user->role;
            if($role == 'qa')
            {
                $taskOrder = '15';
                $bugOrder  = '20';

                unset($this->lang->my->menuOrder[$taskOrder]);
                $this->lang->my->menuOrder[32] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',' . $this->lang->my->menuOrder[$bugOrder] . ',', $this->lang->my->dividerMenu);
            }
            elseif($role == 'po')
            {
                $requirementOrder = 29;
                unset($this->lang->my->menuOrder[$requirementOrder]);

                $this->lang->my->menuOrder[15] = 'story';
                $this->lang->my->menuOrder[16] = 'requirement';
                $this->lang->my->menuOrder[30] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',story,', $this->lang->my->dividerMenu);
            }
            elseif($role == 'pm')
            {
                $projectOrder = 35;
                unset($this->lang->my->menuOrder[$projectOrder]);

                $this->lang->my->menuOrder[17] = 'myProject';
            }
        }
    }
    
    /**
     * Get my charged products.
     *
     * @access public
     * @return object
     */
    public function getProducts()
    {
        $products = $this->dao->select('t1.id as id,t1.*')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
                ->orderBy('t1.order_asc')
                ->fetchAll('id');
        $productKeys = array_keys($products);

        $stories = $this->dao->select('product, sum(estimate) AS estimateCount')
                ->from(TABLE_STORY)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->groupBy('product')
                ->fetchPairs();
        $plans = $this->dao->select('product, count(*) AS count')
                ->from(TABLE_PRODUCTPLAN)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->andWhere('end')->gt(helper::now())
                ->groupBy('product')
                ->fetchPairs();
        $releases = $this->dao->select('product, count(*) AS count')
                ->from(TABLE_RELEASE)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->groupBy('product')
                ->fetchPairs();
        $executions = $this->dao->select('t1.product,t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->in($productKeys)
                ->andWhere('t2.type')->in('stage,sprint')
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('t1.project')
                ->fetchAll('product');
        foreach($executions as $key => $execuData)
        {
            $execution = $this->loadModel('execution')->getById($execuData->id);
            $executions[$key]->progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
        }

        $allCount      = count($products);
        $unclosedCount = 0;
        foreach($products as $key => $product)
        {
            $product->plans      = isset($plans[$product->id]) ? $plans[$product->id] : 0;
            $product->releases   = isset($releases[$product->id]) ? $releases[$product->id] : 0;
            if(isset($executions[$product->id])) $product->executions = $executions[$product->id];
            $product->storyEstimateCount = isset($stories[$product->id]) ? $stories[$product->id] : 0;
            if($product->status != 'closed') $unclosedCount++;
            if($product->status == 'closed') unset($products[$key]);
        }

        /* Sort by storyCount, get 5 records */
        array_multisort(array_column($products, 'storyEstimateCount'), SORT_DESC, $products);
        $products = array_slice($products, 0, 5);

        $data = new stdClass();
        $data->allCount      = $allCount;
        $data->unclosedCount = $unclosedCount;
        $data->products      = array_values($products);

        return $data;
    }

    /**
     * Get my projects.
     *
     * @access public
     * @return object
     */
    public function getProjects()
    {
        $data = new stdClass();
        $allConsumed      = 0;
        $thisYearConsumed = 0;
        $doingProjects    = array();

        $projects         = $this->loadModel('project')->getOverviewList('byStatus', 'all', 'id_desc');
        $projectsConsumed = $this->project->getProjectsConsumed(array_keys($projects), 'this_year');
        $doingCount       = 0;
        foreach($projects as $key => $project)
        {
            if($project->status == 'doing')
            {
                $doingCount++;
                $workhour = $this->project->getWorkhour($project->id);
                $projects[$key]->progress = ($workhour->totalConsumed + $workhour->totalLeft) ? floor($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 1000) / 1000 * 100 : 0;
                $doingProjects[] = $projects[$key];
            }
            $allConsumed      += $project->consumed;
            $thisYearConsumed += $projectsConsumed[$project->id]->totalConsumed;
        }

        /* Sort by consumed,get five record */
        array_multisort(array_column($doingProjects, 'consumed'), SORT_DESC, $doingProjects);
        $doingProjects = array_slice($doingProjects, 0, 5);

        $data->allCount           = count($projects);
        $data->doingCount         = $doingCount;
        $data->allConsumed        = $allConsumed;
        $data->thisYearConsumed   = $thisYearConsumed;
        $data->projects           = $doingProjects;

        return $data;
    }

    /**
     * Get my doc.
     *
     * @access public
     * @return object
     */
    public function getDocs()
    {
        /* Get count of docs created by me. */
        $doc = new stdclass();
        $doc->createdCount = $this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('addedBy')->eq($this->app->user->account)->andWhere('deleted')->eq('0')->fetch('count');

        return $doc;
    }

    /**
     * Get latest actions.
     *
     * @access public
     * @return array
     */
    public function getActions()
    {
        $actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc');
        $users   = $this->loadModel('user')->getPairs('noletter');
        foreach($actions as $key => $action)
        {
            $actions[$key]->actor = $users[$action->actor];
        }

        return $actions;
    }
}
