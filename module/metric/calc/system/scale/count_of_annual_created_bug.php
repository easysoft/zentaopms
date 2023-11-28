<?php
/**
 * 按系统统计的年度新增Bug数。
 * Count of annual created bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的年度新增Bug数
 * 单位：个
 * 描述：按系统统计的年度新增Bug数是指在一年内新发现的Bug数量。反映了一个系统或软件每年新增的Bug数量，用于评估评估系统质量、变更管理、资源规划、过程改进和趋势分析等方面。
 * 定义：所有Bug个数求和;创建时间为某年;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $openedDate = $row->openedDate;
        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
