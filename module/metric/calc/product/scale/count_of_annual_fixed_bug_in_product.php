<?php
/**
 * 按产品统计的年度修复Bug数。
 * count of annual fixed bug in product.
 *
 * 范围：product
 * 对象：Bug
 * 目的：scale
 * 度量名称：按产品统计的年度修复Bug数
 * 单位：个
 * 描述：产品中关闭时间为某年且解决方案为已解决的Bug的个数求和
 *       过滤已删除的Bug
 *       过滤已删除的产品
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_fixed_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.resolution', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $resolution = $data->resolution;
        $closedDate = $data->closedDate;

        if(empty($closedDate)) return;

        $year = substr($closedDate, 0, 4);
        if($resolution != 'fixed' || $year == '0000') return;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
