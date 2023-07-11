<?php
/**
 * 按产品统计的年度新增计划数。
 * Count of annual created plan in product.
 *
 * 范围：prod
 * 对象：productplan
 * 目的：scale
 * 度量名称：按产品统计的年度新增计划数
 * 单位：个
 * 描述：产品中创建时间为某年的计划个数求和
 * 过滤已删除的计划
 * 过滤已删除的产品
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_plan_in_product extends baseCalc
{
    public function getStatement($dao)
    {
        $dao->select('t1.product,year(t1.createdDate),count(t1.id) as count')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('year(t1.createdDate)')
            ->query();
    }

    public function calculate($row)
    {
    }

    public function getResult()
    {
    }
}
