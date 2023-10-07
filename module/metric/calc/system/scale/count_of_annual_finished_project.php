<?php
/**
 * 按系统统计的年度完成项目数。
 * Count of annual finished project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的年度完成项目数
 * 单位：个
 * 描述：按系统统计的年度完成项目数是指在某年度完成并关闭的项目数量。反映了团队在某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度完成项目数表明团队在项目交付方面具有较高的效率。
 * 定义：所有的项目个数求和;实际完成时间为某年;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status', 't1.realEnd');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->realEnd)) return false;

        if(empty($row->realEnd)) return false;
        $year = substr($row->realEnd, 0, 4);

        if($year == '0000' || $row->status != 'closed') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value) $records[] = array('year' => $year, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }

}
