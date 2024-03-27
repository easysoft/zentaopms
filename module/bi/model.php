<?php

class biModel extends model
{
    /**
     * Get object options.
     *
     * @param  string $type user|product|project|execution|dept
     * @access public
     * @return array
     */
    public function getScopeOptions($type)
    {
        $options = array();
        switch($type)
        {
            case 'user':
                $options = $this->loadModel('user')->getPairs('noletter');
                break;
            case 'product':
                $options = $this->loadModel('product')->getPairs();
                break;
            case 'project':
                $options = $this->loadModel('project')->getPairsByProgram();
                break;
            case 'execution':
                $options = $this->loadModel('execution')->getPairs();
                break;
            case 'dept':
                $options = $this->loadModel('dept')->getOptionMenu(0);
                break;
        }

        return $options;
    }

    /**
     * Get object options.
     *
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function getDataviewOptions($object, $field)
    {
        $options = array();
        $path    = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
        if(is_file($path))
        {
            include $path;
            $options = $schema->fields[$field]['options'];
        }

        return $options;
    }

    /**
     * Get object options.
     *
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function getObjectOptions($object, $field)
    {
        $options = array();
        $useTable = $object;
        $useField = $field;

        $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
        if(is_file($path))
        {
            include $path;
            $fieldObject = isset($schema->fields[$field]['object']) ? $schema->fields[$field]['object'] : '';
            $fieldShow   = isset($schema->fields[$field]['show']) ? explode('.', $schema->fields[$field]['show']) : array();

            if($fieldObject) $useTable = $fieldObject;
            if(count($fieldShow) == 2) $useField = $show[1];
        }

        $table = isset($this->config->objectTables[$useTable]) ? $this->config->objectTables[$useTable] : zget($this->config->objectTables, $object, '');
        if($table)
        {
            $columns = $this->dbh->query("SHOW COLUMNS FROM $table")->fetchAll();
            foreach($columns as $id => $column) $columns[$id] = (array)$column;
            $fieldList = array_column($columns, 'Field');

            $useField = in_array($useField, $fieldList) ? $useField : 'id';
            $options = $this->dao->select("id, {$useField}")->from($table)->fetchPairs();
        }

        return $options;
    }

    /**
     * Get pairs from column by keyField and valueField.
     *
     * @param  string $sql
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return array
     */
    public function getOptionsFromSql(string $sql, string $keyField, string $valueField): array
    {
        $options = array();
        $cols    = $this->dbh->query($sql)->fetchAll();
        $sample  = current($cols);

        if(!isset($sample->$keyField) or !isset($sample->$valueField)) return $options;

        foreach($cols as $col)
        {
            $key   = $col->$keyField;
            $value = $col->$valueField;
            $options[$key] = $value;
        }

        return $options;
    }

    /**
     * 生成水球图参数。
     * Generate water polo options.
     *
     * @param  array $fields
     * @param  array $settings
     * @param  string $sql
     * @param  array $filters
     * @access public
     * @return array
     */
    public function genWaterpolo(array $fields, array $settings, string $sql, array $filters): array
    {
        $this->loadModel('chart');
        $operate = "{$settings['calc']}({$settings['goal']})";
        $sql = "select $operate count from ($sql) tt ";

        $moleculeSQL    = $sql;
        $denominatorSQL = $sql;

        $moleculeWheres    = array();
        $denominatorWheres = array();
        foreach($settings['conditions'] as $condition)
        {
            $where = "{$condition['field']} {$this->lang->chart->conditionList[$condition['condition']]} '{$condition['value']}'";
            $moleculeWheres[]    = $where;
        }

        if(!empty($filters))
        {
            $wheres = array();
            foreach($filters as $field => $filter)
            {
                $wheres[] = "$field {$filter['operator']} {$filter['value']}";
            }
            $moleculeWheres    = array_merge($moleculeWheres, $wheres);
            $denominatorWheres = $wheres;
        }

        if($moleculeWheres)    $moleculeSQL    .= 'where ' . implode(' and ', $moleculeWheres);
        if($denominatorWheres) $denominatorSQL .= 'where ' . implode(' and ', $denominatorWheres);

        $molecule    = $this->dao->query($moleculeSQL)->fetch();
        $denominator = $this->dao->query($denominatorSQL)->fetch();

        $percent = $denominator->count ? round((int)$molecule->count / (int)$denominator->count, 4) : 0;

        $series  = array(array('type' => 'liquidFill', 'data' => array($percent), 'color' => array('#2e7fff'), 'outline' => array('show' => false), 'label' => array('fontSize' => 26)));
        $tooltip = array('show' => true);
        $options = array('series' => $series, 'tooltip' => $tooltip);

        return $options;
    }

    /**
     * Get multi data.
     *
     * @param  int    $settings
     * @param  int    $defaultSql
     * @param  int    $filters
     * @access public
     * @return void
     */
    public function getMultiData($settings, $defaultSql, $filters, $sort = false)
    {
        $this->loadModel('chart');

        $group   = isset($settings['xaxis'][0]['field']) ? $settings['xaxis'][0]['field'] : '';
        $date    = isset($settings['xaxis'][0]['group']) ? zget($this->config->chart->dateConvert, $settings['xaxis'][0]['group']) : '';
        $metrics = array();
        $aggs    = array();
        foreach($settings['yaxis'] as $yaxis)
        {
            $metrics[] = $yaxis['field'];
            $aggs[]    = $yaxis['valOrAgg'];
        }
        $yCount  = count($metrics);

        $xLabels = array();
        $yStats  = array();

        for($i = 0; $i < $yCount; $i ++)
        {
            $metric   = $metrics[$i];
            $agg      = $aggs[$i];

            $groupSql   = $groupBySql = "tt.`$group`";
            if(!empty($date))
            {
                $groupSql   = $date == 'MONTH' ? "YEAR(tt.`$group`) as ttyear, $date(tt.`$group`) as ttgroup" : "$date(tt.`$group`) as $group";
                $groupBySql = $date == 'MONTH' ? "YEAR(tt.`$group`), $date(tt.`$group`)" : "$date(tt.`$group`)";
            }

            if($agg == 'distinct')
            {
                $aggSQL = "count($agg tt.`$metric`) as `$metric`";
            }
            else
            {
                $aggSQL = "$agg(tt.`$metric`) as `$metric`";
            }

            $sql = "select $groupSql,$aggSQL from ($defaultSql) tt";
            if(!empty($filters))
            {
                $wheres = array();
                foreach($filters as $field => $filter)
                {
                    $wheres[] = "`$field` {$filter['operator']} {$filter['value']}";
                }

                $whereStr = implode(' and ', $wheres);
                $sql .= " where $whereStr";
            }
            $sql .= " group by $groupBySql";
            $rows = $this->dao->query($sql)->fetchAll();
            $stat = $this->processRows($rows, $date, $group, $metric);

            $maxCount = 50;
            if($sort) arsort($stat);
            $yStats[] = $stat;

            $xLabels = array_merge($xLabels, array_keys($stat));
            $xLabels = array_unique($xLabels);
        }

        return array($group, $metrics, $aggs, $xLabels, $yStats);
    }

    /**
     * Process rows.
     *
     * @param  array  $rows
     * @param  string $date
     * @param  string $group
     * @param  string $metric
     * @access public
     * @return array
     */
    public function processRows($rows, $date, $group, $metric)
    {
        $this->loadModel('chart');

        $stat = array();
        foreach($rows as $row)
        {
            if(!empty($date) and $date == 'MONTH')
            {
                $stat[sprintf("%04d", $row->ttyear) . '-' . sprintf("%02d", $row->ttgroup)] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEARWEEK')
            {
                $yearweek  = sprintf("%06d", $row->$group);
                $year = substr($yearweek, 0, strlen($yearweek) - 2);
                $week = substr($yearweek, -2);

                $weekIndex = in_array($this->app->getClientLang(), array('zh-cn', 'zh-tw')) ? sprintf($this->lang->chart->groupWeek, $year, $week) : sprintf($this->lang->chart->groupWeek, $week, $year);
                $stat[$weekIndex] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEAR')
            {
                $stat[sprintf("%04d", $row->$group)] = $row->$metric;
            }
            else
            {
                $stat[$row->$group] = $row->$metric;
            }
        }

        return $stat;
    }
}
