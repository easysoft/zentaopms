<?php
/**
 * 按系统统计的年度新增用例数。
 * Count of annual created case.
 *
 * 范围：system
 * 对象：case
 * 目的：scale
 * 度量名称：按系统统计的年度新增用例数
 * 单位：个
 * 描述：按系统统计的年度新增用例数是指在一年内新增的测试用例数量。统计年度新增用例数可以帮助评估系统或项目在不同阶段的测试覆盖和测试深度。年度新增用例数的增加可能意味着对新功能和需求进行了更充分的测试。
 * 定义：所有用例个数求和;创建时间在某年;过滤已删除的用例;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_case extends baseCalc
{
    public $dataset = 'getCases';

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
