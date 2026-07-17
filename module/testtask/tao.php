<?php
declare(strict_types=1);
class testtaskTao extends testtaskModel
{
    /**
     * 查询测试单列表。
     * Fetch testtask list.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $projectID
     * @param  string    $unit
     * @param  string    $scope
     * @param  string    $status
     * @param  string    $begin
     * @param  string    $end
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    public function fetchTesttaskList(int $productID, string $branch = '', int $projectID = 0, string $unit = 'no', string $scope = '', string $status = '', string $begin = '', string $end = '', string $browseType = 'all', int $queryID = 0, string $orderBy = '', ?object $pager = null): array
    {
        $testtaskQuery = '';
        if($browseType == 'bysearch') $testtaskQuery = $this->processSearchQuery($productID, $queryID, 'testtask');
        return $this->dao->select("t1.*, t5.multiple, IF(t2.shadow = 1, t5.name, t2.name) AS productName, t3.name AS executionName, t4.name AS buildName, t4.branch AS branch, t5.name AS projectName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t1.project = t5.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($unit == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($unit != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF(!$this->app->user->admin)
            ->andWhere('t1.execution')->in("0,{$this->app->user->view->sprints}")
            ->andWhere('t1.project')->in($this->app->user->view->projects)
            ->fi()
            ->beginIF($scope == 'local')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($scope == 'all')->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF(strtolower($status) == 'myinvolved')
            ->andWhere('(t1.owner')->eq($this->app->user->account)
            ->orWhere("FIND_IN_SET('{$this->app->user->account}', t1.members)")
            ->markRight(1)
            ->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(strtolower($status) == 'totalstatus')->andWhere('t1.status')->in('blocked,doing,wait,done')->fi()
            ->beginIF(strtolower($status) == 'review') // 工作流开启审批的时候才会使用，才会新增字段。
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.`reviewStatus`')->eq('doing')
            ->fi()
            ->beginIF($status == 'reviewedby')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.`reviewedBy`)")
            ->fi()
            ->beginIF(!in_array(strtolower($status), array('totalstatus', 'review', 'myinvolved'), true) && $status)->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($unit != 'unit')
            ->beginIF($begin)->andWhere('t1.begin')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.end')->le($end)->fi()
            ->fi()
            ->beginIF($unit == 'unit')
            ->beginIF($begin)->andWhere('t1.end')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.end')->le($end)->fi()
            ->fi()
            ->beginIF($testtaskQuery)->andWhere($testtaskQuery)->fi()
            ->beginIF($branch !== 'all' && $branch)->andWhere("CONCAT(',', t4.branch, ',')")->like("%,$branch,%")->fi()
            ->beginIF($branch == BRANCH_MAIN)
            ->orWhere('(t1.build')->eq('trunk')
            ->andWhere('t1.product')->eq($productID)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

     /**
     * 通过搜索获取测试单。
     * Get testtasks by search.
     *
     * @param  int    $productID
     * @param  int    $paramID
     * @param  string $module
     * @access public
     * @return string
     * */
    public function processSearchQuery(int $productID = 0, int $paramID = 0, string $module = ''): string
    {
        $defaultQuery = '( 1 = 1)';
        $queryName = $module . 'Query';
        $formName  = $module . 'Form';
        if(!empty($module))
        {
            if($paramID)
            {
                $query = $this->loadModel('search')->getQuery($paramID);
                if($query)
                {
                    $this->session->set($queryName, $query->sql);
                    $this->session->set($formName, $query->form);
                }
            }
            if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');

            $testtaskQuery = '(' . $this->session->$queryName;
            /* 处理查询中的产品条件。*/
            if(strpos($this->session->$queryName, "`product` = 'all'") !== false)
            {
                $testtaskQuery  = str_replace("`product` = 'all'", '1 = 1', $testtaskQuery);
                $testtaskQuery .= ' AND `product` ' . helper::dbIN($this->app->user->view->products);
            }
            elseif($productID)
	        {
                $testtaskQuery .= " AND `product` ='$productID'";
            }
            /* 处理查询中的项目条件。*/
            if(strpos($this->session->$queryName, "`project` = 'all'") !== false)
            {
                $testtaskQuery  = str_replace("`project` = 'all'", '1 = 1', $testtaskQuery);
                $testtaskQuery .= ' AND `project` ' . helper::dbIN($this->app->user->view->projects);
            }

            /* 处理查询中的版本条件。*/
            $testtaskQuery = str_replace(array('`id`', '`name`', '`type`', '`status`', '`owner`', '`pri`', '`begin`','`end`', '`createdDate`', '`realBegan`', '`realFinishedDate`', '`product`', '`project`', '`execution`'), array('t1.`id`', 't1.`name`', 't1.`type`', 't1.`status`', 't1.`owner`', 't1.`pri`', 't1.`begin`', 't1.`end`', 't1.`createdDate`', 't1.`realBegan`', 't1.`realFinishedDate`', 't1.`product`', 't1.`project`', 't1.`execution`'), $testtaskQuery);
            $testtaskQuery .= ')';

            return $testtaskQuery;
        }
        return $defaultQuery;
	}
}
