<?php
/**
 * The model file of workestimation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     workestimation
 * @version     $Id
 * @link        http://www.zentao.net
 */
class workestimationModel extends model
{
    /**
     * Get a budget.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getBudget($projectID)
    {
        return $this->dao->select('*')->from(TABLE_WORKESTIMATION)->where('PRJ')->eq($projectID)->fetch();
    }

    /**
     * Get project scale.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getProjectScale($projectID)
    {
        $products      = $this->loadModel('product')->getProductPairsByProject($projectID);
        $productIdList = array_keys($products);
        return $this->dao->select('cast(sum(estimate) as decimal(10,2)) as scale')->from(TABLE_STORY)->where('product')->in($productIdList)->andWhere('type')->eq('requirement')->andWhere('deleted')->eq(0)->fetch('scale');
    }

    /*
     * Save a budget.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function save($projectID)
    {
        $data = fixer::input('post')
            ->setDefault('PRJ', $projectID)
            ->setIF(!isset($_POST['productivity']), 'productivity', 1)
            ->get();

        $budget = $this->getBudget($projectID);
        if(!empty($budget)) $data->id = $budget->id;

        $this->dao->replace(TABLE_WORKESTIMATION)->data($data)
            ->batchCheck($this->config->workestimation->index->requiredFields, 'notempty')
            ->exec();

        return !dao::isError();
    }
}
