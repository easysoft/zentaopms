<?php
/**
 * 按执行统计的无效研发需求数。
 * Count of invalid story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的无效研发需求数
 * 单位：个
 * 描述：按执行统计的无效研发需求数是指被判定为无效的研发需求数量。无效需求可能包括重复需求、不可实现的需求、或者与项目策略和目标不符的需求。通过对无效需求的统计，可以帮助执行团队优化需求管理和筛选机制，以提高需求有效性和资源利用率。较高的无效需求数量可能需要对需求收集和评估流程进行改进。
 * 定义：执行中研发需求的个数求和;关闭原因为重复、不做、设计如此和已取消;过滤已删除的研发需求;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_invalid_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->project;
        if(!isset($this->result[$execution])) $this->result[$execution] = 0;
        if(in_array($row->closedReason, array('duplicate','willnotdo','bydesign'))) $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
