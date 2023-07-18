<?php
/**
 * 按全局统计的年度交付研发需求规模数。
 * scale_of_annual_delivered_story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的年度交付研发需求规模数
 * 单位：个
 * 描述：按全局统计的年度交付研发需求规模数表示某年度交付的研发需求的规模总数。该度量项反映了组织每年交付给其他团队或部门的研发需求的规模总数，可以用于评估组织的研发需求交付管理和效果。
 * 定义：所有的所处阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年的研发需求规模数求和
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
class scale_of_annual_delivered_story extends baseCalc
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