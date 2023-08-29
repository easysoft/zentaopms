<?php
/**
 * 按全局统计的用例总数。
 * Count of case.
 *
 * 范围：global
 * 对象：testcase
 * 目的：scale
 * 度量名称：按全局统计的用例总数
 * 单位：个
 * 描述：按全局统计的用例总数是指系统或项目中的测试用例总数量。用例是用来验证系统功能和性能的测试场景。统计用例总数可以帮助评估测试覆盖的广度和深度。用例总数越高可能意味着项目进行了全面和充分的测试。
 * 定义：所有用例个数求和;过滤已删除的用例;过滤已删除的产品;
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
class count_of_case extends baseCalc
{
    public $dataset = 'getCases';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
