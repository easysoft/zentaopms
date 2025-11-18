<?php
declare(strict_types=1);
/**
 * The tao file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     build
 * @link        https://www.zentao.net
 */
class buildTao extends buildModel
{
    /**
     * 根据条件获取版本列表信息。
     * Get build list inormation by condition.
     *
     * @param  array|int $productIdList
     * @param  string    $params        hasdeleted|hasproject|singled
     * @param  int       $objectID
     * @param  string    $objectType
     * @param  array     $shadows
     * @access protected
     * @return array
     */
    protected function fetchBuilds(array|int $productIdList, string $params = '', int $objectID = 0, string $objectType = '', array $shadows = array(), int $system = 0): array
    {
        $fieldList = 't1.id, t1.name, t1.product, t1.branch, t1.execution, t1.date, t1.deleted, t3.status as releaseStatus, t3.id as releaseID, t4.type as productType';
        if($objectType == 'execution' || $objectType == 'project') $fieldList .= ', t2.status as objectStatus';

        $userView = trim($this->app->user->view->projects, ',') . ',' . trim($this->app->user->view->sprints, ',');
        return $this->dao->select($fieldList)->from(TABLE_BUILD)->alias('t1')
            ->beginIF($objectType === 'execution')->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')->fi()
            ->beginIF($objectType === 'project')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->fi()
            ->leftJoin(TABLE_RELEASERELATED)->alias('t5')->on("t1.id=t5.objectID AND t5.objectType='build'")
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on('t5.release=t3.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product = t4.id')
            ->where('1=1')
            ->beginIf(!empty($shadows))->andWhere('t1.id')->notIN($shadows)->fi()
            ->beginIF(strpos($params, 'hasdeleted') === false)->andWhere('t1.deleted')->eq(0)->fi()
            ->beginIF(strpos($params, 'hasproject') !== false)->andWhere('t1.project')->ne(0)->fi()
            ->beginIF(strpos($params, 'singled') !== false)->andWhere('t1.execution')->ne(0)->fi()
            ->beginIF(!empty($productIdList))->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF($objectType === 'execution' && $objectID)->andWhere('t1.execution')->eq($objectID)->fi()
            ->beginIF($objectType === 'project' && $objectID)->andWhere('t1.project')->eq($objectID)->fi()
            ->beginIF($objectType === 'project' && !$this->app->user->admin)->andWhere('t1.project')->in($userView)->fi()
            ->beginIF($system)->andWhere('t1.system')->eq($system)->fi()
            ->orderBy('t1.date desc, t1.id desc')
            ->fetchAll('id');
    }

    /**
     * 获取项目、执行关联的版本信息。
     * Get the builds that the project,execution has been linked.
     *
     * @param  string    $buildIdList
     * @param  array|int $productIdList
     * @param  string    $params        hasdeleted
     * @param  int       $objectID
     * @param  string    $objectType
     * @access protected
     * @return array
     */
    protected function selectedBuildPairs(string $buildIdList, array|int $productIdList, string $params, int $objectID, string $objectType): array
    {
        $selectedBuilds = array();
        if($buildIdList)
        {
            $selectedBuilds = $this->dao->select('id, name')->from(TABLE_BUILD)
                ->where('id')->in($buildIdList)
                ->beginIF(!empty($productIdList))->andWhere('product')->in($productIdList)->fi()
                ->beginIF($objectType === 'execution' && $objectID)->andWhere('execution')->eq($objectID)->fi()
                ->beginIF($objectType === 'project' && $objectID)->andWhere('project')->eq($objectID)->fi()
                ->beginIF(strpos($params, 'hasdeleted') === false)->andWhere('deleted')->eq(0)->fi()
                ->fetchPairs();
        }
        return $selectedBuilds;
    }
}
