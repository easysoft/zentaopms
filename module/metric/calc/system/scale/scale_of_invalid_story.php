<?php
/**
 * 按系统统计的无效研发需求规模数。
 * Scale of invalid story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的无效研发需求规模数
 * 单位：个
 * 描述：按系统统计的无效的研发需求规模数反映了组织中无效的研发需求的规模总数，用于评估组织对于资源管理、需求管理、质量控制、风险评估和持续改进具有重要意义。
 * 定义：所有的研发需求规模数求和;关闭原因为重复、不做、设计如此和已取消;过滤父研发需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_invalid_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.estimate', 't1.closedReason', 't1.parent');

    public $result = 0;

    public function calculate($row)
    {
        $parent = $row->parent;
        if($parent == '-1') return false;

        if(empty($row->estimate)) return null;

        if(in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
