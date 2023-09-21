<?php
/**
 * 按全局统计的无效研发需求规模数。
 * Scale of invalid story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的无效研发需求规模数
 * 单位：个
 * 描述：按全局统计的无效研发需求数是指被判定为无效的研发需求数量。无效需求可能包括重复需求、不可实现的需求、或者与产品策略和目标不符的需求。通过对无效需求的统计，可以帮助团队优化需求管理和筛选机制，以提高需求有效性和资源利用率。较高的无效需求数量可能需要对需求收集和评估流程进行改进。
 * 定义：所有的研发需求规模数求和;关闭原因为重复、不做、设计如此和已取消;过滤已删除的研发需求;过滤已删除的产品;
 * 度量库：
 * 收集方式：realtime
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

    public $fieldList = array('t1.estimate', 't1.closedReason');

    public $result = 0;

    public function calculate($row)
    {
        if(empty($row->estimate)) return null;

        if(in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
