<?php
/**
 * 按全局统计的计划总数。
 * count_of_productplan.
 *
 * 范围：global
 * 对象：productplan
 * 目的：scale
 * 度量名称：按全局统计的计划总数
 * 单位：个
 * 描述：按全局统计的计划总数表示整个组织中产品计划的数量。该度量项反映了组织中所有计划的总数，包括正在进行中的计划和已经完成的计划，可以用于评估组织的计划管理情况。
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
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}