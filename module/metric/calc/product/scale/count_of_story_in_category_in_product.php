<?php
/**
 * 按类别统计的需求数。
 * Count of story in category
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按类别统计的需求数
 * 单位：个
 * 描述：按类别统计的需求数
 * 定义：按类别统计的需求数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_category_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->category])) $this->result[$row->category] = array();
        $this->result[$row->category][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $category => $stories)
        {
            if(!is_array($stories))
            {
                unset($this->result[$category]);
                continue;
            }
            $this->result[$category] = count($stories);
        }

        $records = $this->getRecords(array('category', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
