<?php
include_once __DIR__ . '/meas.class.php';

/**
 * 项目消耗的工时相对于每人每日可用工时的比例。
 * The radio of consumed hours compare daily person hours in project.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class ratio_of_consumed_hours_in_project extends measBase
{
    public function __construct()
    {
        $this->collection = 'realTime';
        $this->range      = 'glob';
        $this->purpose    = 'scale';

        $this->table = TABLE_TASK;

        $t4 = "(select t1.root, sum(t1.hours) / count(t1.id) as perHours from zt_team t1 left join zt_user t2 on t2.account = t1.account where t2.deleted='0' group by t1.root)";

        $leftJoins = array();
        $leftJoins = array('table' => TABLE_PROJECT, 'alias' => 't2', 'on' => "t2.id=t1.project and t2.type='sprint'");
        $leftJoins = array('table' => TABLE_PROJECT, 'alias' => 't3', 'on' => "t3.id=t2.parent");
        $leftJoins = array('table' => $t4,           'alias' => 't4', 'on' => "t2.id=t4.root");

        $this->leftJoins = $leftJoins;

        $operator     = 'and';
        $conditions   = array();
        $conditions[] = array('table' => 't1', 'field' => 'parent',  'operator' => '!=', 'value' => '-1');
        $conditions[] = array('table' => 't1', 'field' => 'deleted', 'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => 't2', 'field' => 'deleted', 'operator' => '=', 'value' => '0');
        $conditions[] = array('table' => 't3', 'field' => 'deleted', 'operator' => '=', 'value' => '0');

        $this->filters = array('operator' => $operator, 'conditions' => $conditions);

        $this->indicators   = array();
        $this->indicators[] = array('table' => 't3', 'field' => 'id',       'aggregate' => 'group');
        $this->indicators[] = array('table' => 't1', 'field' => 'consumed', 'aggregate' => 'sum');
        $this->indicators[] = array('table' => 't4', 'field' => 'perHours', 'aggregate' => 'sum');

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
            $projectID = $data['t3_id'];

            if(!isset($this->result[$projectID])) $this->result[$projectID] = array('consumed' => 0, 'perHours' => 0);
            $this->result[$projectID][$field] += (float)$value;
        }
    }

    public function getResult()
    {
        $result = array();

        foreach($this->result as $projectID => $item)
        {
            $result[$projectID] = $item['perHours'] == 0 ? 0 : $item['consumed'] / $item['perHours'];
        }

        return $result;
    }
}
