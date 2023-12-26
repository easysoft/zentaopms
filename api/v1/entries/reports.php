<?php
/**
 * The reports entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class reportsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $fields  = $this->param('fields', '');
        $dept    = $this->param('dept', 0);
        $account = $this->param('account', '');
        $year    = $this->param('year', date('Y'));
        if(empty($fields)) return $this->send(400, 'Need fields param for report.');

        $accounts = array();
        if($account) $accounts = array($account => $account);
        if(empty($accounts) and $dept) $accounts = array_keys($this->loadModel('dept')->getDeptUserPairs($dept));
        if(empty($accounts) and empty($dept)) $accounts = array_keys($this->loadModel('user')->getPairs('noclosed'));

        $fields = explode(',', strtolower($fields));
        $report = array();
        foreach($fields as $field)
        {
            $field = trim($field);
            if(empty($field)) continue;

            if($field == 'projectoverview')
            {
                $report['projectOverview'] = $this->projectOverview($accounts);
            }
            elseif($field == 'radar')
            {
                $report['radar'] = $this->radar($accounts, $year);
            }
            elseif($field == 'projectprogress')
            {
                $report['projectProgress'] = $this->projectProgress();
            }
            elseif($field == 'executionprogress')
            {
                $report['executionProgress'] = $this->executionProgress();
            }
            elseif($field == 'productprogress')
            {
                $report['productProgress'] = $this->productProgress();
            }
            elseif($field == 'bugprogress')
            {
                $report['bugProgress'] = $this->bugProgress();
            }
            elseif($field == 'bugprogress')
            {
                $report['bugProgress'] = $this->bugProgress();
            }
            elseif($field == 'output')
            {
                $report['output'] = $this->loadModel('report')->getOutput4API($accounts, $year);
            }
        }

        return $this->send(200, $report);
    }

    /**
     * Get project overview by status.
     *
     * @param  array    $accounts
     * @access public
     * @return array
     */
    public function projectOverview($accounts)
    {
        $statusOverview = $this->loadModel('report')->getProjectStatusOverview($accounts);

        $this->app->loadLang('project');
        $total    = 0;
        $overview = array();
        foreach($statusOverview as $status => $count)
        {
            $total += $count;
            $statusName = zget($this->lang->project->statusList, $status);

            $overview[$status] = array();
            $overview[$status]['code']  = $status;
            $overview[$status]['name']  = $statusName;
            $overview[$status]['total'] = $count;
        }

        $projectOverview = array();
        $projectOverview['total']    = $total;
        $projectOverview['overview'] = array_values($overview);

        return $projectOverview;
    }

    /**
     * Get radar data. include product, execution, qa, devel and other.
     *
     * @param  array    $accounts
     * @param  string   $year
     * @access public
     * @return array
     */
    public function radar($accounts, $year)
    {
        $contributions    = $this->loadModel('report')->getUserYearContributions($accounts, $year);
        $annualDataConfig = $this->config->report->annualData;

        $radarData = array('product' => 0, 'execution' => 0, 'devel' => 0, 'qa' => 0, 'other' => 0);
        foreach($contributions as $objectType => $objectContributions)
        {
            foreach($objectContributions as $actionName => $count)
            {
                $radarTypes = isset($annualDataConfig['radar'][$objectType][$actionName]) ? $annualDataConfig['radar'][$objectType][$actionName] : array('other');
                foreach($radarTypes as $radarType) $radarData[$radarType] += $count;
            }
        }

        $radar = array();
        foreach($radarData as $radarType => $total)
        {
            $radar[$radarType]['code']  = $radarType;
            $radar[$radarType]['name']  = $this->lang->report->annualData->radarItems[$radarType];
            $radar[$radarType]['total'] = $total;
        }

        return array_values($radar);
    }

    /**
     * Get project progress.
     *
     * @access public
     * @return array
     */
    public function projectProgress()
    {
        $projects = $this->loadModel('program')->getProjectStats(0, 'all');
        $this->app->loadLang('project');

        $processedProjects = array();
        $statusList['all']['total']    = 0;
        $statusList['doing']['total']  = 0;
        $statusList['wait']['total']   = 0;
        $statusList['closed']['total'] = 0;
        foreach($projects as $project)
        {
            $newProject = new stdclass();
            $newProject->id            = $project->id;
            $newProject->name          = $project->name;
            $newProject->status        = $project->status;
            $newProject->progress      = round($project->hours->progress, 1);
            $newProject->totalConsumed = round($project->hours->totalConsumed, 1);
            $newProject->totalLeft     = round($project->hours->totalLeft, 1);
            if(isset($project->delay)) $newProject->delay = $project->delay;

            $statusList['all']['total'] += 1;
            if(isset($statusList[$project->status])) $statusList[$project->status]['total'] += 1;

            $processedProjects[$project->id] = $newProject;
        }

        foreach(array_keys($statusList) as $status)
        {
            $statusName = zget($this->lang->project->statusList, $status);
            if($status == 'all') $statusName = $this->lang->project->featureBar['browse']['all'];

            $statusList[$status]['code'] = $status;
            $statusList[$status]['name'] = $statusName;
        }

        return array('statusList' => $statusList, 'projects' => array_values($processedProjects));
    }

    /**
     * Get execution progress.
     *
     * @access public
     * @return array
     */
    public function executionProgress()
    {
        $executions = $this->loadModel('execution')->getStatData(0, 'all', 0, 0, false, '', 'id_desc');

        $processedExecutions = array();
        $statusList['all']['total']    = 0;
        $statusList['doing']['total']  = 0;
        $statusList['wait']['total']   = 0;
        $statusList['closed']['total'] = 0;
        foreach($executions as $execution)
        {
            $newExecution = new stdclass();
            $newExecution->id            = $execution->id;
            $newExecution->name          = $execution->name;
            $newExecution->status        = $execution->status;
            $newExecution->progress      = round($execution->hours->progress, 1);
            $newExecution->totalConsumed = round($execution->hours->totalConsumed, 1);
            $newExecution->totalLeft     = round($execution->hours->totalLeft, 1);
            if(isset($execution->delay)) $newExecution->delay = $execution->delay;

            $statusList['all']['total'] += 1;
            if(isset($statusList[$execution->status])) $statusList[$execution->status]['total'] += 1;

            $processedExecutions[$execution->id] = $newExecution;
        }

        foreach(array_keys($statusList) as $status)
        {
            $statusName = zget($this->lang->execution->statusList, $status);
            if($status == 'all') $statusName = $this->lang->execution->allTasks;

            $statusList[$status]['code'] = $status;
            $statusList[$status]['name'] = $statusName;
        }

        return array('statusList' => $statusList, 'executions' => array_values($processedExecutions));
    }

    /**
     * Get product progress with story.
     *
     * @access public
     * @return array
     */
    public function productProgress()
    {
        $this->app->loadLang('product');
        $this->app->loadLang('story');

        $storyStatusStat = $this->dao->select('t1.product,t2.name,t2.status,t1.status as storyStatus,count(*) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->products)->fi()
            ->groupBy('t1.product,t1.status')
            ->orderBy('t1.product_desc,t1.status')
            ->fetchAll();

        $productStatusList['all']['total']    = 0;
        $productStatusList['normal']['total'] = 0;
        $productStatusList['closed']['total'] = 0;

        $processedProducts = array();
        $productStoryStat  = array();
        foreach($storyStatusStat as $product)
        {
            $productStoryStat[$product->product][$product->storyStatus] = $product->storyCount;

            if(isset($processedProducts[$product->product])) continue;

            $newProduct = new stdclass();
            $newProduct->id     = $product->product;
            $newProduct->name   = $product->name;
            $newProduct->status = $product->status;

            $processedProducts[$product->product] = $newProduct;
            $productStatusList['all']['total'] += 1;
            if(isset($productStatusList[$product->status])) $productStatusList[$product->status]['total'] += 1;
        }

        /* Set story status statistics integrate into product. */
        $storyStatusList = array('draft' => array(), 'reviewing' => array(), 'active' => array(), 'changing' => array(), 'closed' => array());
        foreach($processedProducts as $productID => $product)
        {
            $product->storyStat = array();
            foreach(array_keys($storyStatusList) as $storyStatus) $product->storyStat[$storyStatus] = isset($productStoryStat[$productID][$storyStatus]) ? $productStoryStat[$productID][$storyStatus] : 0;
            $product->progress = $product->storyStat['closed'] == 0 ? 0 : round($product->storyStat['closed'] / array_sum($product->storyStat) * 100, 1);
        }

        foreach(array_keys($productStatusList) as $status)
        {
            $statusName = zget($this->lang->product->statusList, $status);
            if($status == 'all') $statusName = $this->lang->product->allStory;
            if($status == 'normal') $statusName = $this->lang->product->unclosed;

            $productStatusList[$status]['code'] = $status;
            $productStatusList[$status]['name'] = $statusName;
        }

        foreach(array_keys($storyStatusList) as $status)
        {
            $statusName = zget($this->lang->story->statusList, $status);

            $storyStatusList[$status]['code'] = $status;
            $storyStatusList[$status]['name'] = $statusName;
        }

        return array('productStatusList' => $productStatusList, 'products' => array_values($processedProducts), 'storyStatusList' => $storyStatusList);
    }

    /**
     * Get bug progress by product.
     *
     * @access public
     * @return array
     */
    public function bugProgress()
    {
        $this->app->loadLang('product');
        $this->app->loadLang('bug');

        $bugStatusStat = $this->dao->select('t1.product,t2.name,t2.status,t1.status as bugStatus,count(*) as bugCount')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->products)->fi()
            ->groupBy('t1.product,t1.status')
            ->orderBy('t1.product_desc,t1.status')
            ->fetchAll();

        $productStatusList['all']['total']    = 0;
        $productStatusList['normal']['total'] = 0;
        $productStatusList['closed']['total'] = 0;

        $processedProducts = array();
        $productBugStat    = array();
        foreach($bugStatusStat as $product)
        {
            $productBugStat[$product->product][$product->bugStatus] = $product->bugCount;

            if(isset($processedProducts[$product->product])) continue;

            $newProduct = new stdclass();
            $newProduct->id     = $product->product;
            $newProduct->name   = $product->name;
            $newProduct->status = $product->status;

            $processedProducts[$product->product] = $newProduct;
            $productStatusList['all']['total'] += 1;
            if(isset($productStatusList[$product->status])) $productStatusList[$product->status]['total'] += 1;
        }

        /* Set bug status statistics integrate into product. */
        $bugStatusList = array('active' => array(), 'resolved' => array(), 'closed' => array());
        foreach($processedProducts as $productID => $product)
        {
            $product->bugStat = array();
            foreach(array_keys($bugStatusList) as $bugStatus) $product->bugStat[$bugStatus] = isset($productBugStat[$productID][$bugStatus]) ? $productBugStat[$productID][$bugStatus] : 0;
        }

        foreach(array_keys($productStatusList) as $status)
        {
            $statusName = zget($this->lang->product->statusList, $status);
            if($status == 'all') $statusName = $this->lang->product->allStory;
            if($status == 'normal') $statusName = $this->lang->product->unclosed;

            $productStatusList[$status]['code'] = $status;
            $productStatusList[$status]['name'] = $statusName;
        }

        foreach(array_keys($bugStatusList) as $status)
        {
            $statusName = zget($this->lang->bug->statusList, $status);

            $bugStatusList[$status]['code'] = $status;
            $bugStatusList[$status]['name'] = $statusName;
        }

        return array('productStatusList' => $productStatusList, 'bugs' => array_values($processedProducts), 'bugStatusList' => $bugStatusList);
    }
}
