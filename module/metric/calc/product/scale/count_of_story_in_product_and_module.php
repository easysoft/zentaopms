<?php
/**
 * 按产品和模块统计的研发需求数。
 * Count of story in product and module.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品和模块统计的研发需求数
 * 单位：个
 * 描述：按产品和模块统计的研发需求数。
 * 定义：和模块统计的研发需求数。
 *
 * @copyright Copyright 2009-2053 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_product_and_module extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = array('name'=> $row->product, 'value' => 0, 'children' => array());
        if(!isset($this->result[$row->product]['children'][$row->module])) $this->result[$row->product]['children'][$row->module] = array('name'=> $row->module, 'value' => 0, 'children' => array());
        if($row->secondModule && !isset($this->result[$row->product]['children'][$row->module]['children'][$row->secondModule])) $this->result[$row->product]['children'][$row->module]['children'][$row->secondModule] = array('name'=> $row->secondModule, 'value' => 0);

        $this->result[$row->product]['value'] ++;
        if($row->module) $this->result[$row->product]['children'][$row->module]['value'] ++;
        if($row->secondModule) $this->result[$row->product]['children'][$row->module]['children'][$row->secondModule]['value'] ++;
    }

    public function processRecords($options = array())
    {
        $records = array();
        foreach($options as $row)
        {
            if(!empty($row['children']))
            {
                $row['children'] = array_values($this->processRecords($row['children']));
            }
            else
            {
                unset($row['children']);
            }
            $records[] = $row;
        }
        return $records;
    }

    public function getResult($options = array())
    {
        $records = $this->processRecords($this->result);
        return $this->filterByOptions($records, $options);
    }
}
