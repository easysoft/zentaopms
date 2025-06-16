<?php
/**
 * 按系统统计的测试完毕研发需求数。
 * Count of tested story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的测试完毕研发需求数
 * 单位：个
 * 描述：按系统统计的测试完毕研发需求数反映了组织在特定时间段内已经完成的产品研发需求数量，用于评估评组织的估研发成果、产品创新和竞争力，并提供绩效评估。
 * 定义：所有的研发需求个数求和;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_tested_story extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = 0;

    public function calculate($row)
    {
        if(strpos(',tested,verified,rejected,delivering,delivered,released,closed', ",$row->stage,") !== false) $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
