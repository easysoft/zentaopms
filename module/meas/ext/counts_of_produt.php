<?php
include_once __DIR__ . '/meas.class.php';

/**
 * 所有产品总数。
 * The count of all product.
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

        $this->table      = TABLE_PRODUCCT;

        $operator     = 'and';
        $conditions   = array();
        $conditions[] = array('table' => TABLE_PRODUCT, 'field' => 'deleted', 'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => TABLE_PRODUCT, 'field' => 'shadow',  'operator' => '=', 'value' => '0');

        $this->filters = array('operator' => $operator, 'conditions' => $conditions);

        $this->indicators   = array();
        $this->indicators[] = array('table' => TABLE_PRODUCT, 'field' => 'id', 'aggregate' => 'count');

        $this->result = 0;
    }

    public function calculate($data, $multiple = false)
    {
        if($multiple) foreach($data as $item) $this->calculate($item);

        if(!$this->parseCondition($data)) return;

        $this->result += 1;
    }

    public function getResult()
    {
        return $this->result;
    }
}
