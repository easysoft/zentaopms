<?php
/**
 * 按全局统计的年度新增研发需求数。
 * count_of_annual_created_story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的年度新增研发需求数
 * 单位：个
 * 描述：按全局统计的年度新增研发需求数表示每年新增加的研发需求的数量。该度量项反映了组织每年新增加的研发需求数量，可以用于评估组织的研发需求增长和规模扩展情况。
 * 定义：所有的研发需求个数求和
创建时间为某年
过滤已删除的研发需求
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
class count_of_annual_created_story extends baseCalc
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