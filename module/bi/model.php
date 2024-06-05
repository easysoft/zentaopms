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
    public function getTableAndFields(string $sql): array|false
    {
        $this->app->loadClass('sqlparser', true);
        $parser    = new sqlparser($sql);
        $statement = $parser->statements[0];

        if(empty($statement))  return false;

        $fields = $this->getFields($statement);

        $tables = array();
        if($statement->from)
        {
            foreach($statement->from as $fromInfo)
            {
                if($fromInfo->table) $tables[] = $fromInfo->table;
            }
        }
        if($statement->join)
        {
            foreach($statement->join as $joinInfo)
            {
                if($joinInfo->expr->table) $tables[] = $joinInfo->expr->table;
            }
        }

        return array('tables' => array_unique($tables), 'fields' => $fields);
    }

    /**
     * 获取sql中的字段。
     * Get tables and fields form sqlparser statment.
     *
     * @param  object $statment
     * @param  bool   $deep
     * @access public
     * @return array
     */
    public function getFields(object $statement, bool $deep = false)
    {
        $fields = array();
        if($statement->expr)
        {
            foreach($statement->expr as $fieldInfo)
            {
                $field = $fieldInfo->expr;
                $fields[$field] = $field;
            }
        }

        return $fields;
    }

    /**
     * Try to explain sql.
     *
     * @param  string     $sql
     * @param  string     $driver mysql|duckdb
     * @access public
     * @return array
     */
    public function explainSQL($sql, $driver = 'mysql')
    {
        $dbh = $this->app->loadDriver($driver);

        $prefixSQL = $driver == 'mysql' ? 'EXPLAIN' : 'PRAGMA enable_profiling=json; EXPLAIN ANALYZE';
        try
        {
            $rows = $dbh->query("$prefixSQL $sql")->fetchAll();
        }
        catch(Exception $e)
        {
            $message = preg_replace("/\r|\n|\t/", "", $e->getMessage());
            $message = strip_tags($message);
            return array('result' => 'fail', 'message' => $message);
        }

        return array('result' => 'success');
    }

    /**
     * Try to explain sql.
     *
     * @param  string     $sql
     * @param  string     $limitSql
     * @param  string     $driver mysql|duckdb
     * @access public
     * @return array
     */
    public function querySQL($sql, $limitSql, $driver = 'mysql')
    {
        $dbh = $this->app->loadDriver($driver);

        try
        {
            if($driver == 'mysql')
            {
                $rows      = $dbh->query($limitSql)->fetchAll();
                $count     = $dbh->query("SELECT FOUND_ROWS() as count")->fetch();
                $rowsCount = $count->count;
            }
            elseif($driver == 'duckdb')
            {
                $rows      = $dbh->query($limitSql)->fetchAll();
                $allRows   = $dbh->query("SELECT COUNT(1) as count FROM ( $sql )")->fetch();
                $rowsCount = $allRows->count;
            }
        }
        catch(Exception $e)
        {
            $message = preg_replace("/\r|\n|\t/", "", $e->getMessage());
            $message = strip_tags($message);
            return array('result' => 'fail', 'message' => $message);
        }

        return array('result' => 'success', 'rows' => $rows, 'rowsCount' => $rowsCount);
    }

    /**
     * Get sql result columns.
     *
     * @param  string     $sql
     * @param  string     $driver mysql|duckdb
     * @access public
     * @return array|false
     */
    public function getColumns(string $sql, $driver = 'mysql'): array|false
    {
        if(!in_array($driver, $this->config->bi->drivers)) return false;

        if($driver == 'mysql')
        {
            $columns = $this->dao->getColumns($sql);
        }
        else
        {
            $dbh     = $this->app->loadDriver('duckdb');
            $columns = $dbh->query("DESCRIBE $sql")->fetchAll();
        }

        $result = array();
        foreach($columns as $column)
        {
            $column = (array)$column;

            $name       = $driver == 'mysql' ? $column['name']        : $column['column_name'];
            $nativeType = $driver == 'mysql' ? $column['native_type'] : $column['column_type'];

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
     * @param  string $driver
     * @access public
     * @return array
     */
    public function getOptionsFromSql(string $sql, $driver, string $keyField, string $valueField): array
    {
        $options = array();
        $dbh     = $this->app->loadDriver($driver);
        $cols    = $dbh->query($sql)->fetchAll();
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
    public function prepareBuiltinChartSQL($operate = 'insert')
    {
        $charts = $this->config->bi->builtin->charts;

        $chartSQLs = array();
        foreach($charts as $chart)
        {
            $currentOperate = $operate;
            $chart = (object)$chart;
            if(isset($chart->settings)) $chart->settings = $this->jsonEncode($chart->settings);
            if(isset($chart->filters))  $chart->filters  = $this->jsonEncode($chart->filters);
            if(isset($chart->fields))   $chart->fields   = $this->jsonEncode($chart->fields);
            if(isset($chart->langs))    $chart->langs    = $this->jsonEncode($chart->langs);

            $exists = $this->dao->select('id')->from(TABLE_CHART)->where('id')->eq($chart->id)->fetch();
            if(!$exists) $currentOperate = 'insert';

            $stmt = null;
            if($currentOperate == 'insert')
            {
                $chart->createdBy   = 'system';
                $chart->createdDate = helper::now();
                $stmt = $this->dao->insert(TABLE_CHART)->data($chart);
            }
            if($currentOperate == 'update')
            {
                $id = $chart->id;
                unset($chart->group);
                unset($chart->id);
                $stmt = $this->dao->update(TABLE_CHART)->data($chart)->where('id')->eq($id);
            }

            if(isset($stmt)) $chartSQLs[] = $stmt->get();
        }

        return $chartSQLs;
    }

    /**
     * 准备内置的透视表sql语句。
     * Prepare builtin pivot sql.
     *
     * @param  string  $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinPivotSQL($operate = 'insert')
    {
        $pivots = $this->config->bi->builtin->pivots;

        $pivotSQLs = array();
        foreach($pivots as $pivot)
        {
            $currentOperate = $operate;
            $pivot = (object)$pivot;
            $pivot->name     = $this->jsonEncode($pivot->name);
            if(isset($pivot->desc))     $pivot->desc     = $this->jsonEncode($pivot->desc);
            if(isset($pivot->settings)) $pivot->settings = $this->jsonEncode($pivot->settings);
            if(isset($pivot->filters))  $pivot->filters  = $this->jsonEncode($pivot->filters);
            if(isset($pivot->fields))   $pivot->fields   = $this->jsonEncode($pivot->fields);
            if(isset($pivot->langs))    $pivot->langs    = $this->jsonEncode($pivot->langs);
            if(isset($pivot->vars))     $pivot->vars     = $this->jsonEncode($pivot->vars);

            $exists = $this->dao->select('id')->from(TABLE_PIVOT)->where('id')->eq($pivot->id)->fetch();
            if(!$exists) $currentOperate = 'insert';

            $stmt = null;
            if($currentOperate == 'insert')
            {
                $pivot->createdBy   = 'system';
                $pivot->createdDate = helper::now();
                $stmt = $this->dao->insert(TABLE_PIVOT)->data($pivot);
            }
            if($currentOperate == 'update')
            {
                $id = $pivot->id;
                unset($pivot->group);
                unset($pivot->id);
                $stmt = $this->dao->update(TABLE_PIVOT)->data($pivot)->where('id')->eq($id);
            }

            if(isset($stmt)) $pivotSQLs[] = $stmt->get();
        }

        return $pivotSQLs;
    }

    /**
     * 准备内置的度量项sql语句。
     * Prepare builtin metric sql.
     *
     * @param  string  $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinMetricSQL($operate = 'insert')
    {
        $metrics = $this->config->bi->builtin->metrics;

        $metricSQLs = array();
        $this->dao->delete()->from(TABLE_METRIC)
            ->where('builtin')->eq('1')
            ->andWhere('code')->notIn(array_column($metrics, 'code'))
            ->exec();
        foreach($metrics as $metric)
        {
            $currentOperate = $operate;
            $metric = (object)$metric;
            $metric->stage   = 'released';
            $metric->type    = 'php';
            $metric->builtin = '1';

            $exists = $this->dao->select('code')->from(TABLE_METRIC)->where('code')->eq($metric->code)->fetch();
            if(!$exists) $currentOperate = 'insert';

            $stmt = null;
            if($currentOperate == 'insert')
            {
                $metric->createdBy   = 'system';
                $metric->createdDate = helper::now();
                $stmt = $this->dao->insert(TABLE_METRIC)->data($metric);
            }
            if($currentOperate == 'update')
            {
                $code = $metric->code;
                unset($metric->code);
                $stmt = $this->dao->update(TABLE_METRIC)->data($metric)->where('code')->eq($code);
            }

            if(isset($stmt)) $metricSQLs[] = $stmt->get();
        }

        return $metricSQLs;
    }

    /**
     * 准备内置的大屏sql语句。
     * Prepare builtin screen sql.
     *
     * @param  string  $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinScreenSQL($operate = 'insert')
    {
        $screens = $this->config->bi->builtin->screens;

        $screenSQLs = array();
        foreach($screens as $screenID)
        {
            $currentOperate = $operate;
            $screenJson = file_get_contents(__DIR__ . DS . 'json' . DS . "screen{$screenID}.json");
            $screen = json_decode($screenJson);
            if(isset($screen->scheme)) $screen->scheme = json_encode($screen->scheme, JSON_UNESCAPED_UNICODE);

            $exists = $this->dao->select('id')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();

            if(!$exists) $currentOperate = 'insert';

            $screen->status = 'published';

            $stmt = null;
            if($currentOperate == 'insert')
            {
                $screen->createdBy   = 'system';
                $screen->createdDate = helper::now();
                $stmt = $this->dao->insert(TABLE_SCREEN)->data($screen);
            }
            if($currentOperate == 'update')
            {
                $id = $screen->id;
                unset($screen->id);
                $stmt = $this->dao->update(TABLE_SCREEN)->data($screen)->where('id')->eq($id);
            }

            if(isset($stmt)) $screenSQLs[] = $stmt->get();
        }

        return $screenSQLs;
    }

    /*
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
        $tables    = $this->config->bi->duckdb->tables;
        $ztvtables = $this->config->bi->duckdb->ztvtables;
        if(empty($tables)) return '';

        $tablePrefix = $this->config->db->prefix;
        $ztvPrefix   = 'ztv_';

        $copySQL  = '';
        foreach($tables as $table => $sql)
        {
            $table = $tablePrefix . $table;
            $sql   = str_replace('zt_', $tablePrefix, $sql);

            $tablePath = $duckdbTmpPath . $table;
            $copySQL .= "COPY ($sql) TO '$tablePath.parquet';\n";
        }

        foreach($ztvtables as $table => $sql)
        {
            $table = $ztvPrefix . $table;

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
     * Process filter variables in sql.
     *
     * @param  string    $sql
     * @param  array  $filters
     * @access public
     * @return string
     */
    public function processVars($sql, $filters = array())
    {
        return $sql;
    }

    /**
     * Build statement object from sql.
     *
     * @param  string    $sql
     * @access public
     * @return object
     */
    public function sql2Statement($sql)
    {
        $this->app->loadClass('sqlparser', true);
        $parser = new sqlparser($sql);

        if(count($parser->statements) == 0) return $this->lang->dataview->empty;
        if(count($parser->statements) > 1)  return $this->lang->dataview->onlyOne;

        $statement = $parser->statements[0];
        if($statement instanceof PhpMyAdmin\SqlParser\Statements\SelectStatement == false) return $this->lang->dataview->allowSelect;

        return $statement;
    }

    /**
     * Validate sql.
     *
     * @param  string    $sql
     * @access public
     * @return string|true
     */
    public function validateSql($sql)
    {
        $this->loadModel('dataview');

        $driver = 'mysql';

        if(empty($sql)) return $this->lang->dataview->empty;
        try
        {
            $rows = $this->dbh->query("EXPLAIN $sql")->fetchAll();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }

        $sqlColumns = $this->getColumns($sql, $driver);
        list($isUnique, $repeatColumn) = $this->dataview->checkUniColumn($sql, $driver, true, $sqlColumns);

        if(!$isUnique) return sprintf($this->lang->dataview->duplicateField, implode(',', $repeatColumn));

        return true;
    }

    /**
     * Prepare pager from sql.
     *
     * @param  object    $statement
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return string
     */
    public function prepareSqlPager($statement, $recPerPage, $pageID)
    {
        if(!$statement->limit)
        {
            $statement->limit = new stdclass();
        }
        $statement->limit->offset   = $recPerPage * ($pageID - 1);
        $statement->limit->rowCount = $recPerPage;

        $statement->options->options[] = 'SQL_CALC_FOUND_ROWS';

        $limitSql = $statement->build();

        return $limitSql;
    }

    /**
     * Prepare columns setting from sql.
     *
     * @param  string    $sql
     * @param  object    $statement
     * @access public
     * @return array
     */
    public function prepareColumns($sql, $statement)
    {
        $this->loadModel('chart');
        $this->loadModel('dataview');

        // TODO 需要替换成实际图表或者透视表的driver。
        $driver = 'mysql';

        $sqlColumns   = $this->getColumns($sql, $driver);
        $columnTypes  = $this->getColumnsType($sql, $driver, $sqlColumns);
        $columnFields = array();
        foreach($columnTypes as $column => $type) $columnFields[$column] = $column;

        $tableAndFields = $this->getTableAndFields($sql);
        $tables   = $tableAndFields['tables'];
        $fields   = $tableAndFields['fields'];

        $moduleNames = array();
        $aliasNames  = array();
        if($tables)
        {
            $moduleNames = $this->dataview->getModuleNames($tables);
            $aliasNames  = $this->dataview->getAliasNames($statement, $moduleNames);
        }

        list($fields, $objects) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames, $aliasNames);

        $columns = array();
        foreach($fields as $field => $name)
        {
            $columns[$field] = array('name' => $name, 'field' => $field, 'type' => $columnTypes->$field, 'object' => $objects[$field]);
        }

        return $columns;
    }

    /**
     * Query sql.
     *
     * @param  string    $sql
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return object
     */
    public function query($sql, $recPerPage = 100, $pageID = 1)
    {
        $queryResult = new stdclass();
        $queryResult->error    = false;
        $queryResult->errorMsg = '';
        $queryResult->cols     = array();
        $queryResult->rows     = array();
        $queryResult->sql      = '';
        $queryResult->fieldSettings = array();

        $sql = $this->processVars($sql);

        $statement = $this->sql2Statement($sql);
        if(is_string($statement))
        {
            $queryResult->error    = true;
            $queryResult->errorMsg = $statement;

            return $queryResult;
        }

        $checked = $this->validateSql($sql);
        if($checked !== true)
        {
            $queryResult->error    = true;
            $queryResult->errorMsg = $checked;

            return $queryResult;
        }

        $limitSql = $this->prepareSqlPager($statement, $recPerPage, $pageID);

        try
        {
            $queryResult->rows      = $this->dbh->query($limitSql)->fetchAll();
            $queryResult->rowsCount = $this->dbh->query("SELECT FOUND_ROWS() as count")->fetch()->count;
        }
        catch(Exception $e)
        {
            $queryResult->error = true;
            $queryResult->errorMsg = $e;

            return $queryResult;
        }

        $columns    = $this->prepareColumns($limitSql, $statement);
        $clientLang = $this->app->getClientLang();

        $fieldSettings = array();
        foreach($columns as $field => $settings)
        {
            $title = $settings['name'];

            if(!isset($settings[$clientLang]) || empty($settings[$clientLang])) $settings[$clientLang] = $title;
            $fieldSettings[$field] = $settings;
        }

        $queryResult->cols          = $this->buildQueryResultTableColumns($fieldSettings);
        $queryResult->fieldSettings = $fieldSettings;
        $queryResult->sql           = $limitSql;

        return $queryResult;
    }

    /**
     * Build table columns from query result.
     *
     * @param  array    $fieldSettings
     * @access public
     * @return array
     */
    public function buildQueryResultTableColumns($fieldSettings)
    {
        $cols = array();
        $clientLang = $this->app->getClientLang();
        foreach($fieldSettings as $field => $settings)
        {
            $settings = (array)$settings;
            $title = $settings[$clientLang];
            $type  = $settings['type'];
            $cols[] = array('name' => $field, 'title' => $title, 'sortType' => false);
        }

        return $cols;
    }

    /**
     * Prepare field objects.
     *
     * @access public
     * @return array
     */
    public function prepareFieldObjects()
    {
        $this->loadModel('dataview');
        $options = array();
        foreach($this->lang->dataview->objects as $table => $name)
        {
            $fields = $this->dataview->getTypeOptions($table);
            $options[] = array('text' => $name, 'value' => $table, 'fields' => $fields);
        }

        return $options;
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
