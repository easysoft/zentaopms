<?php
/**
 * 按系统统计的测试完毕研发需求规模数。
 * Scale of finished story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的测试完毕研发需求规模数
 * 单位：个
 * 描述：按系统统计的测试完毕研发需求规模数反映了组织在测试完毕的研发需求上的规模总数，用于评估组织对于研发进展评估、质量控制、绩效评估和持续改进具有重要意义。
 * 定义：所有的研发需求规模数求和;过滤父研发需求;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_tested_story extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = 0;

    public function calculate($row)
    {
        if($row->isParent == '1') return false;
        if(empty($row->estimate)) return null;

        if(strpos(',tested,verified,rejected,delivering,delivered,released,closed', ",$row->stage,") !== false) $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
