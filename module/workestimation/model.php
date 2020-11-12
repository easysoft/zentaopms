<?php
/**
 * The model file of workestimation module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     workestimation
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class workestimationModel extends model
{
    /**
     * Get a budget.
     *
     * @param  int    $program
     * @access public
     * @return array
     */
    public function getBudget($program)
    {
        return $this->dao->select('*')->from(TABLE_WORKESTIMATION)->where('PRJ')->eq($program)->fetch();
    }

    /**
     * Get program scale.
     *
     * @param  int    $program
     * @access public
     * @return int
     */
    public function getProgramScale($program)
    {
        $products = $this->loadModel('product')->getPairs('', $program);
        $productIdList = array_keys($products);
        return $this->dao->select('cast(sum(estimate) as decimal(10,2)) as scale')->from(TABLE_STORY)->where('product')->in($productIdList)->andWhere('type')->eq('requirement')->fetch('scale');
    }

    /**
     * Save a budget.
     *
     * @param  int    $program
     * @access public
     * @return bool
     */
    public function save($program)
    {
        $postBudget = fixer::input('post')->get();
        $postBudget->PRJ = $program;

        $budget = $this->getBudget($program);
        if(!empty($budget)) $postBudget->id = $budget->id;

        $this->dao->replace(TABLE_WORKESTIMATION)->data($postBudget)
            ->batchCheck($this->config->workestimation->index->requiredFields, 'notempty')
            ->exec();

        return !dao::isError();
    }
}
