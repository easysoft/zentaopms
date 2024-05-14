<?php

class biModel extends model
{

    /**
     * 获取sql中的表、字段。
     * Get tables and fields form sql.
     *
     * @param  string $sql
     * @access public
     * @return array|false
     */
    public function getTables(string $sql): array|false
    {
        $this->app->loadClass('sqlparser', true);
        $parser    = new sqlparser($sql);
        $statement = $parser->statements[0];

        if(empty($statement))  return false;

        $fields = array();
        if($statement->expr)
        {
            foreach($statement->expr as $fieldInfo)
            {
                $field = $fieldInfo->expr;
                $fields[$field] = $field;
            }
        }

        $tables = array();
        if($statement->from)
        {
            foreach($statement->from as $fromInfo)
            {
                $tables[] = $fromInfo->table;
            }
        }
        if($statement->join)
        {
            foreach($statement->join as $joinInfo)
            {
                $tables[] = $joinInfo->expr->table;
            }
        }

        return array('tables' => array_unique($tables), 'fields' => $fields);
    }

    /**
     * Get sql result columns.
     *
     * @param  string     $sql
     * @param  string     $driverName mysql|duckdb
     * @access public
     * @return array|false
     */
    public function getColumns(string $sql, $driverName = 'mysql'): array|false
    {
        if(!in_array($driverName, $this->config->bi->driverNames)) return false;

        if($driverName == 'mysql')
        {
            $columns = $this->dao->getColumns($sql);
        }
        else
        {
            $dbh     = $this->app->loadDriver('duckdb');
            $columns = $dbh->query("$sql", 'desc')->fetchAll();
        }

        $result = array();
        foreach($columns as $column)
        {
            $column = (array)$column;

            $name       = $driverName == 'mysql' ? $column['name']        : $column['column_name'];
            $nativeType = $driverName == 'mysql' ? $column['native_type'] : $column['column_type'];

            $result[$name] = array('name' => $name, 'native_type' => $nativeType);
        }

        return $result;
    }

    /**
     * 获取表的字段类型。
     * Get table data.
     *
     * @param  string $sql
     * @param  string $driverName mysql|duckdb
     * @param  array  $columns
     * @access public
     * @return object
     */
    public function getColumnsType(string $sql, string $driverName = 'mysql', array $columns = array()): object
    {
        if(empty($columns)) $columns = $this->getColumns($sql, $driverName);

        $columnTypes = new stdclass();
        foreach($columns as $column)
        {
            $field      = $column['name'];
            $nativeType = $column['native_type'];
            $type       = $this->config->bi->columnTypes->$driverName[$nativeType];

            if(isset($columnTypes->$field)) $field = $column['table'] . $field;
            $columnTypes->$field = $type;
        }

        return $columnTypes;
    }

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
            case 'project.status':
                $this->app->loadLang('project');
                $options = $this->lang->project->statusList;
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

    /*
     * 准备内置的图表sql语句。
     * Prepare builtin chart sql.
     *
     * @access public
     * @return array
     */
    public function prepareBuiltinChartSQL()
    {
        $charts = $this->config->bi->builtin->charts;

        $chartSQLs = array();
        foreach($charts as $chart)
        {
            $chart = (object)$chart;
            $chart->settings = $this->jsonEncode($chart->settings);
            $chart->filters  = $this->jsonEncode($chart->filters);
            $chart->fields   = $this->jsonEncode($chart->fields);
            $chart->langs    = $this->jsonEncode($chart->langs);

            $chart->createdBy   = 'system';
            $chart->createdDate = helper::now();

            $stmt = $this->dao->insert(TABLE_CHART)->data($chart)
                ->autoCheck();

            $chartSQLs[] = $stmt->get();
        }

        return $chartSQLs;
    }

    /**
     * 准备内置的透视表sql语句。
     * Prepare builtin pivot sql.
     *
     * @param  bool    $exec
     * @access public
     * @return array
     */
    public function prepareBuiltinPivotSQL()
    {
        $pivots = $this->config->bi->builtin->pivots;

        $pivotSQLs = array();
        foreach($pivots as $pivot)
        {
            $pivot = (object)$pivot;
            $pivot->name     = $this->jsonEncode($pivot->name);
            $pivot->desc     = $this->jsonEncode($pivot->desc);
            $pivot->settings = $this->jsonEncode($pivot->settings);
            $pivot->filters  = $this->jsonEncode($pivot->filters);
            $pivot->fields   = $this->jsonEncode($pivot->fields);
            $pivot->langs    = $this->jsonEncode($pivot->langs);
            $pivot->vars     = $this->jsonEncode($pivot->vars);

            $pivot->createdBy   = 'system';
            $pivot->createdDate = helper::now();

            $stmt = $this->dao->insert(TABLE_PIVOT)->data($pivot)
                ->autoCheck();

            $pivotSQLs[] = $stmt->get();
        }

        return $pivotSQLs;
    }

    /**
     * 获取DuckDB的可执行文件路径。
     * Get DcukDB path.
     *
     * @access public
     * @return object|false
     */
    public function getDuckDBPath()
    {
        $binPath   = $this->app->getBasePath() . 'bin' . DS . 'duckdb' . DS;
        $file      = $binPath . 'duckdb';
        $extension = $binPath . 'mysql_scanner.duckdb_extension';

        if(!file_exists($file) && !file_exists($extension) && !is_executable($file)) return false;

        return (object)array('bin' => $file, 'extension' => $extension);
    }

    /**
     * 获取DuckDB临时目录。
     * Get DuckDB temp directory.
     *
     * @access public
     * @return string|false
     */
    public function getDuckDBTmpDir()
    {
        $duckdbTmpPath = $this->app->getTmpRoot() . 'duckdb' . DS . 'bi' . DS;
        if(!is_dir($duckdbTmpPath) && !mkdir($duckdbTmpPath, 0755, true)) return false;

        return $duckdbTmpPath;
    }

    /**
     * 准备同步数据库所需的复制SQL。
     * Prepare copy SQL for sync.
     *
     * @param  string $duckdbTmpPath
     * @access public
     * @return string
     */
    public function prepareCopySQL($duckdbTmpPath)
    {
        $tables = $this->config->bi->duckdb->tables;
        if(empty($tables)) return '';

        $tablePrefix = $this->config->db->prefix;

        $copySQL  = '';
        foreach($tables as $table => $sql)
        {
            $table = $tablePrefix . $table;
            $sql   = str_replace('zt_', $tablePrefix, $sql);

            $tablePath = $duckdbTmpPath . $table;
            $copySQL .= "COPY ($sql) TO '$tablePath.parquet';\n";
        }

        return $copySQL;
    }

    /**
     * 准备同步命令。
     * Prepare sync command.
     *
     * @param  string    $binPath
     * @param  string    $extensionPath
     * @param  string    $copySQL
     * @access public
     * @return string
     */
    public function prepareSyncCommand($binPath, $extensionPath, $copySQL)
    {
        $sqlContent = $this->config->bi->duckSQLTemp;
        $dbConfig   = $this->config->db;
        $variables  = array(
            '{EXTENSIONPATH}' => $extensionPath,
            '{DATABASE}'      => $dbConfig->name,
            '{USER}'          => $dbConfig->user,
            '{PASSWORD}'      => $dbConfig->password,
            '{HOST}'          => $dbConfig->host,
            '{PORT}'          => $dbConfig->port,
            '{COPYSQL}'       => $copySQL
        );

        foreach($variables as $key => $value)
        {
            $sqlContent = str_replace($key, $value, $sqlContent);
        }

        return "$binPath :memory: \"$sqlContent\" 2>&1";
    }

    /**
     * Encode json.
     *
     * @param  object|array  $object
     * @access private
     * @return string|null
     */
    private function jsonEncode($object)
    {
        if(empty($object)) return null;
        if(is_scalar($object)) return $object;
        return json_encode($object);
    }
}
