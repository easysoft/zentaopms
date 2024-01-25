<?php
/**
 * 按系统统计的用例总数。
 * Count of case.
 *
 * 范围：system
 * 对象：case
 * 目的：scale
 * 度量名称：按系统统计的用例总数
 * 单位：个
 * 描述：按系统统计的用例总数是指系统或项目中的测试用例总数量。反映了一个系统或软件的功能广度和复杂性，用于评估功能完整性、需求管理、项目规模评估、测试覆盖度评估以及变更管理等方面。通过统计和跟踪用例总数，可以评估系统的功能广度和复杂性，帮助团队进行需求管理、项目规模评估、测试覆盖和变更管理，从而提高系统的开发效率和质量。
 * 定义：所有用例个数求和;过滤已删除的用例;过滤已删除的产品;
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
