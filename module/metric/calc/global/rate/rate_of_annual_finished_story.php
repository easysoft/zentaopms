<?php
/**
 * 按全局统计的年度研发需求完成率。
 * rate_of_annual_finished_story.
 *
 * 范围：global
 * 对象：story
 * 目的：rate
 * 度量名称：按全局统计的年度研发需求完成率
 * 单位：%
 * 描述：按全局统计的研发需求完成率表示按全局统计的年度已完成的研发需求数相对于按全局统计的有效研发需求数。这个指标衡量了整体研发团队在完成年度研发需求方面的效率和质量。完成率越高，说明研发团队能够按时完成年度目标，并且需求达到预期的质量标准。
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
class rate_of_annual_finished_story extends baseCalc
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