<?php
/**
 * 按系统统计的用户总数。
 * Count of user.
 *
 * 范围：system
 * 对象：user
 * 目的：scale
 * 度量名称：按系统统计的用户总数
 * 单位：个
 * 描述：按系统统计的人员总数是指在项目或系统中参与开发和管理的人员总数。反映了系统的用户基础和用户规模，用于评估组织内部资源、增长趋势等方面的有用信息。这对于组织发展、内部管理和战略决策具有重要意义。
 * 定义：系统所有用户个数求和;过滤已删除的用户;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_user extends baseCalc
{
    public $dataset = 'getUsers';

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
