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
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function fetchTesttaskList(int $productID, string $branch = '', int $projectID = 0, string $unit = 'no', string $scope = '', string $status = '', string $begin = '', string $end = '', string $orderBy = '', object $pager = null): array
    {
        return $this->dao->select("t1.*, t5.multiple, IF(t2.shadow = 1, t5.name, t2.name) AS productName, t3.name AS executionName, t4.name AS buildName, t4.branch AS branch, t5.name AS projectName")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->leftJoin(TABLE_PROJECT)->alias('t5')->on('t3.project = t5.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($unit == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($unit != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in("0,{$this->app->user->view->sprints}")->fi()
            ->beginIF($scope == 'local')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($scope == 'all')->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF(strtolower($scope[1]) == 'myinvolved')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.members)")->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(strtolower($status) == 'totalstatus')->andWhere('t1.status')->in('blocked,doing,wait,done')->fi()
            ->beginIF(strtolower($status) == 'review') // 工作流开启审批的时候才会使用，才会新增字段。
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
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
            ->beginIF($branch !== 'all' && $branch)->andWhere("CONCAT(',', t4.branch, ',')")->like("%,$branch,%")->fi()
            ->beginIF($branch == BRANCH_MAIN)
            ->orWhere('(t1.build')->eq('trunk')
            ->andWhere('t1.product')->eq($productID)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }
}
