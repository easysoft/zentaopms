<?php
/**
 * 按系统统计的每日执行用例次数。
 * Count of daily run case.
 *
 * 范围：system
 * 对象：case
 * 目的：scale
 * 度量名称：按系统统计的每日执行用例次数
 * 单位：个
 * 描述：按系统统计的每日执行用例次数表示组织每日执行的用例次数，这个度量项可以反映测试团队每日的工作效率和进展情况。
 * 定义：所有用例的执行次数求和;过滤已删除的用例;过滤已删除的产品;执行时间为某日;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_run_case extends baseCalc
{
    public $dataset = 'getTestResults';

    public $fieldList = array('t1.id', 't1.`date`');

    public $result = array();

    public function calculate($row)
    {
        $date = $row->date;
        if(empty($date)) return false;

        $year = substr($date, 0, 4);
        if($year == '0000') return false;

        $month = substr($date, 5, 2);
        $day   = substr($date, 8, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
