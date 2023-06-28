<?php
include_once __DIR__ . '/meas.class.php';

/**
 * 年发布的总数。
 * The count of product release per year.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class counts_of_release_per_year extends measBase
{
    public function __construct()
    {
        $this->collection = 'realTime';
        $this->range      = 'glob';
        $this->purpose    = 'scale';

        $this->table     = TABLE_RELEASE;
        $this->leftJoins = array();
        $this->leftJoins = array('table' => TABLE_PRODUCT, 'alias' => 't2', 'on' => 't2.id=t1.product');
        $this->leftJoins = array('table' => TABLE_PROJECT,  'alias' => 't3', 'on' => 't3.id=t1.project');

        // $this->leftJoins = array('table' => TABLE_PROJECT, 'mainField' => 'project', 'subField' => 'id', 'operator' => '=');
        // $this->leftJoins = array('table' => TABLE_PROJECT, 'mainField' => 'project', 'subField' => 'id', 'operator' => 'IN');
        // $this->leftJoins = array('table' => TABLE_PROJECT, 'mainField' => 'project', 'subField' => 'id', 'operator' => 'NOT IN');
        // $this->leftJoins = array('table' => TABLE_PROJECT, 'mainField' => 'project', 'subField' => 'id', 'operator' => 'FIND_IN_SET');

        $operator     = 'and';
        $conditions   = array();
        $conditions[] = array('table' => 't1', 'field' => 'deleted',    'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => 't2', 'field' => 'deleted',    'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => 't3', 'field' => 'hasProduct', 'operator' => '=', 'value' => '1');

        $this->filters = array('operator' => $operator, 'conditions' => $conditions);


        $this->indicators   = array();
        $this->indicators[] = array('table' => TABLE_RELEASE, 'field' => 'id', 'aggregate' => 'count', 'value' => 0);

        $this->result = 0;
    }

    public function calculate($data, $multiple = false)
    {
        if($multiple) foreach($data as $item) $this->calculate($item);

        if(!$this->parseCondition($data, $this->filters)) return;

        foreach($this->indicators as $index => $indicator)
        {
            $this->result += 1;
        }
    }

    public function getResult()
    {
        return $this->result;
    }
}
