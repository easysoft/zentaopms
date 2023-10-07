<?php
/**
 * 按产品统计的月度解决Bug数。
 * Count of monthly fixed bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的月度解决Bug数
 * 单位：个
 * 描述：按产品统计的月度解决Bug数是指每天在产品开发过程中被解决并关闭的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。
 * 定义：产品中创建时间在某年某月的Bug个数求和;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_fixed_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.resolution', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $resolution = $data->resolution;
        $closedDate = $data->closedDate;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($resolution != 'fixed' || $year == '0000') return false;

        $month = substr($closedDate, 5, 2);

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;

        $this->result[$product][$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
