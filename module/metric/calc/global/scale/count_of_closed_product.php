<?php
/**
 * 按全局统计的结束的产品数。
 * count_of_closed_product.
 *
 * 范围：global
 * 对象：product
 * 目的：scale
 * 度量名称：按全局统计的结束的产品数
 * 单位：个
 * 描述：按全局统计的结束的产品数表示已经停止研发和运营的产品数量。此度量项反映了组织中已经停止研发和运营的产品数量，可以用于评估组织的产品生命周期管理和战略调整。
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
class count_of_closed_product extends baseCalc
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