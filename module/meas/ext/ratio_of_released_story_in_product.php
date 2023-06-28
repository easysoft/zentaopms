<?php
include_once __DIR__ . '/meas.class.php';

/**
 * 按产品统计的已发布需求数相对于研发需求总数的比例。
 * The number of published requirements by product relative to the total number of R&D requirements.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class ratio_of_released_story_in_product extends measBase
{
    public function __construct()
    {
        $this->collection = 'realTime';
        $this->range      = 'glob';
        $this->purpose    = 'scale';

        $this->table = TABLE_STORY;

        $operator     = 'and';
        $conditions   = array();
        $conditions[] = array('table' => TABLE_STORY, 'field' => 'deleted',  'operator' => '=', 'value' => '0');


        $this->filters = array('operator' => $operator, 'conditions' => $conditions);

        $this->indicators   = array();
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'product',      'aggregate' => 'group');
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'stage',        'aggregate' => 'count', 'condition' => array('operator' => '=', 'value' => 'released'));
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'closedReason', 'aggregate' => 'count', 'condition' => array('operator' => '=', 'value' => 'done'));
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'status',       'aggregate' => 'count', 'condition' => array('operator' => '!=', 'value' => 'closed'));

        $this->result = array();
    }

    public function calculate($data, $multiple = false)
    {
        if($multiple) foreach($data as $item) $this->calculate($item);

        if(!$this->parseCondition($data)) return;

        foreach($this->indicators as $index => $indicator)
        {
            $field = $indicator['field'];
            $agg   = $indicator['aggregate'];

            if($agg == 'group') continue;

            $value     = $this->getIndicatorValue($data, $indicator);
            $productID = $data['product'];

            if(!isset($this->result[$productID])) $this->result[$productID] = array('stage' => 0, 'closedReason' => 0, 'status' => 0);

            $operator   = $indicator['condition']['operator'];
            $rightValue = $indicator['condition']['value'];

            if(self::getBoolByCondition($operator, $value, $rightValue)) $this->result[$productID][$field] += 1;
        }
    }

    public function getResult()
    {
        $result = array();

        foreach($this->result as $productID => $item)
        {
            $result[$productID] = ($item['closedReason'] + $item['status']) == 0 ? 0 : $item['stage'] / ($item['closedReason'] + $item['status']);
        }

        return $result;
    }
}
