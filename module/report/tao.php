<?php
declare(strict_types=1);
/**
 * The tao file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     report
 * @link        https://www.zentao.net
 */
class reportTao extends reportModel
{
    /**
     * 获取某年的年度产品数据。
     * Get annual product stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    protected function getAnnualProductStat(array $accounts, string $year): array
    {
        /* Get created plans, created stories and closed stories in this year. */
        $planGroups = $this->dao->select('t1.id,t1.product')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id=t2.objectID and t2.objectType='productplan'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('LEFT(t2.date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t2.actor')->in($accounts)->fi()
            ->andWhere('t2.action')->eq('opened')
            ->fetchGroup('product', 'id');
        $createdStoryStats = $this->dao->select("product,sum(if((type = 'requirement'), 1, 0)) as requirement, sum(if((type = 'story'), 1, 0)) as story")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(openedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');
        $closedStoryStats = $this->dao->select("product,sum(if((status = 'closed'), 1, 0)) as closed")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(closedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

        /* Get products created or operated in this year. */
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(createdDate, 4)', true)->eq($year)
            ->beginIF($accounts)
            ->andWhere('createdBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->orWhere('id')->in(array_merge(array_keys($planGroups), array_keys($createdStoryStats), array_keys($closedStoryStats)))
            ->markRight(1)
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        return array($products, $planGroups, $createdStoryStats, $closedStoryStats);
    }
}
