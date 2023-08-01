<?php
/**
 * 按全局统计的计划总数。
 * Count of productplan.
 *
 * 范围：global
 * 对象：productplan
 * 目的：scale
 * 度量名称：按全局统计的计划总数
 * 单位：个
 * 描述：按全局统计的计划总数表示整个组织中产品计划的数量。该度量项反映了组织中所有计划的总数，包括正在进行中的计划和已经完成的计划，可以用于评估组织的计划管理情况。
 * 定义：所有的计划的个数求和;过滤已删除的计划;
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
class count_of_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($data)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
