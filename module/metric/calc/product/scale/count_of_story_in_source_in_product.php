<?php
/**
 * 按来源统计的需求数。
 * Count of story in source
 *
 * 范围：source
 * 对象：story
 * 目的：scale
 * 度量名称：按来源统计的需求数
 * 单位：个
 * 描述：按来源统计的需求数
 * 定义：按来源统计的需求数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_source_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->source])) $this->result[$row->source] = array();
        $this->result[$row->source][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $source => $stories)
        {
            if(!is_array($stories))
            {
                unset($this->result[$source]);
                continue;
            }
            $this->result[$source] = count($stories);
        }

        $records = $this->getRecords(array('source', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
