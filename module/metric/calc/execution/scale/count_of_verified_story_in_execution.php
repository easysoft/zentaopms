<?php
/**
 * 按执行统计的已验收的研发需求数。
 * Count of verified story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的已验收的研发需求数
 * 单位：个
 * 描述：按执行统计的已验收的研发需求数是指执行中已验收的研发需求的数量。这个度量项可以反映执行的进展。已验收的研发需求数越多，说明执行团队在该时间段内取得了更多的研发成果。
 * 定义：执行中所处阶段为已验收、交付中、已交付、已发布和关闭原因为已完成的研发需求个数求和;过滤已删除的研发需求;过滤已删除产品的研发需求;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_verified_story_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        $execution    = $row->project;
        $stage        = $row->stage;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$execution])) $this->result[$execution] = 0;

        if(in_array($stage, array('verified', 'delivering', 'delivered', 'released'))) $this->result[$execution] += 1;
        if($closedReason == 'done') $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
