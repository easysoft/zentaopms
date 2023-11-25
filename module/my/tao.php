<?php
declare(strict_types=1);
/**
 * The tao file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
class myTao extends myModel
{

    /**
     * 获取产品相关的由我指派的数据。
     * Get the data related to the product assigned by me.
     *
     * @param  array     $objectIdList
     * @param  string    $objectType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getProductRelatedAssignedByMe(array $objectIdList, string $objectType, string $module, string $orderBy, object $pager = null): array
    {
        $nameField  = $objectType == 'bug' ? 'productName' : 'productTitle';
        $orderBy    = strpos($orderBy, 'priOrder') !== false || strpos($orderBy, 'severityOrder') !== false || strpos($orderBy, $nameField) !== false ? $orderBy : "t1.{$orderBy}";
        $select     = "t1.*, t2.name AS {$nameField}, t2.shadow AS shadow, " . (strpos($orderBy, 'severity') !== false ? "IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder" : "IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder");
        $objectList = $this->dao->select($select)->from($this->config->objectTables[$module])->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.id')->in($objectIdList)
            ->beginIF($module == 'story')->andWhere('t1.type')->eq($objectType)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if($module == 'story')
        {
            $planList = array();
            foreach($objectList as $story) $planList[$story->plan] = $story->plan;
            $planPairs = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in($planList)->fetchPairs('id');
            foreach($objectList as $story) $story->planTitle = zget($planPairs, $story->plan, '');
        }
        return $objectList;
    }
}

