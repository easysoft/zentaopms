<?php
/**
 * 按系统统计的年度新增文档个数。
 * Count of annual created doc.
 *
 * 范围：system
 * 对象：doc
 * 目的：scale
 * 度量名称：按系统统计的年度新增文档个数
 * 单位：个
 * 描述：按系统统计的年度新增文档个数是指在某年度系统或组织中新建的文档数量。反映了组织中信息产生的速度和增长的趋势。年度新增文档个数越大，说明组织的信息需求和创造力较强，也可能需要投入更多的资源来管理和维护这些新增文档。该度量项还可以用于评估组织的创新能力和知识管理水平。
 * 定义：所有文档个数求和;创建时间为某年;过滤已删除的文档;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_doc extends baseCalc
{
    public $dataset = 'getDocs';

    public $fieldList = array('t1.addedDate');

    public $result = array();

    public function calculate($row)
    {
        $addedDate = $row->addedDate;
        if(empty($addedDate)) return false;

        $year = substr($addedDate, 0, 4);
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
