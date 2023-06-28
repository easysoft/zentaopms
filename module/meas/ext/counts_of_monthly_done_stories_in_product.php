<?php
include_once __DIR__ . '/meas.class.php';

/**
 * 按产品统计的每月完成研发需求总数。
 * The count of monthly done stories in product.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class counts_of_product extends measBase
{
    public function __construct()
    {
        $this->collection = 'realTime';
        $this->range      = 'glob';
        $this->purpose    = 'scale';

        $this->table = TABLE_STORY;

        $operator     = 'and';
        $conditions   = array();
        $conditions[] = array('table' => TABLE_STORY, 'field' => 'deleted', 'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => TABLE_STORY, 'field' => 'closedReason',  'operator' => '=', 'value' => 'done');

        $this->filters = array('operator' => $operator, 'conditions' => $conditions);


        $this->indicators   = array();
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'product',    'aggregate' => 'group');
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'closedDate', 'aggregate' => 'group');
        $this->indicators[] = array('table' => TABLE_STORY, 'field' => 'id',         'aggregate' => 'count');

        $this->result = array();
    }

    public function calculate($data, $multiple = false)
    {
        if($multiple) foreach($data as $item) $this->calculate($item);

        if(!$this->parseCondition($data, $this->filters)) return;

        foreach($this->indicators as $index => $indicator)
        {
            $field = $indicator['field'];
            $agg   = $indicator['aggregate'];

            if($agg == 'group') continue;

            $productID = $data['product'];
            $month     = date('Y-m', strtotime($data['closedDate']));

            if(!isset($this->result[$productID])) $this->result[$productID] = array();
            if(!isset($this->result[$productID][$month])) $this->result[$productID][$month] = 0;

            $this->result[$productID][$month] += 1;
        }
    }

    public function getResult()
    {
        return $this->result;
    }
}
