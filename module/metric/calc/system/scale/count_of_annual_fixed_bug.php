<?php
/**
 * 按全局统计的年度修复Bug数。
 * Count of annual fixed bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的年度修复Bug数
 * 单位：个
 * 描述：按全局统计的年度修复Bug数是指在一年内解决并关闭的Bug数量。这个度量项反映了系统或项目在一年内解决的缺陷数量。年度修复Bug数的增加说明开发团队在Bug解决方面的工作效率较高。
 * 定义：所有Bug个数求和; 状态为已关闭; 解决方案为已解决; 关闭时间为某年; 过滤已删除的Bug; 过滤已删除的产品
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
class count_of_annual_fixed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.closedDate', 't1.status', 't1.resolution');

    public $result = array();

    public function calculate($row)
    {
        $closedDate = $row->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        if($row->status == 'closed' and $row->resolution == 'fixed') $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
