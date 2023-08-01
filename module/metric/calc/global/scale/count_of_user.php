<?php
/**
 * 按全局统计的用户总数。
 * Count of user.
 *
 * 范围：global
 * 对象：user
 * 目的：scale
 * 度量名称：按全局统计的用户总数
 * 单位：个
 * 描述：按全局统计的人员总数是指在项目或系统中参与开发和管理的人员总数。该度量项可以帮助评估项目的规模和团队规模。人员总数的增加可能意味着项目的扩大或资源投入的增加。
 * 定义：系统所有用户个数求和;过滤已删除的用户;
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
