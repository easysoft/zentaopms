<?php
/**
 * 按人员统计的日解决Bug数。
 * Count of daily fixed bug in user.
 *
 * 范围：user
 * 对象：bug
 * 目的：scale
 * 度量名称：按人员统计的日解决Bug数
 * 单位：个
 * 描述：按人员统计的日解决Bug数表示每个人每日解决的Bug数量之和。反映了每个人每日解决Bug的规模。该数值越大，可能说明Bug的解决能力越强，工作效率越高。
 * 定义：所有Bug个数求和;解决者为某人;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_fixed_bug_in_user extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.resolvedBy', 't1.resolvedDate');

    public $result = array();

    public function calculate($row)
    {
        $resolvedDate = $row->resolvedDate;
        $resolvedBy   = $row->resolvedBy;

        if(empty($resolvedDate) || empty($resolvedBy)) return false;

        $year = substr($resolvedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($resolvedDate, 5, 2);
        $day   = substr($resolvedDate, 8, 2);

        if(!isset($this->result[$resolvedBy]))                      $this->result[$resolvedBy] = array();
        if(!isset($this->result[$resolvedBy][$year]))               $this->result[$resolvedBy][$year] = array();
        if(!isset($this->result[$resolvedBy][$year][$month]))       $this->result[$resolvedBy][$year][$month] = array();
        if(!isset($this->result[$resolvedBy][$year][$month][$day])) $this->result[$resolvedBy][$year][$month][$day] = 0;

        $this->result[$resolvedBy][$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
