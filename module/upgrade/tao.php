<?php
declare(strict_types=1);
class upgradeTao extends upgradeModel
{
    /**
     * 获取中间表分组ID。
     * Get dataview group ID.
     *
     * @param  string $name
     * @access protected
     * @return int
     */
    protected function getDataviewGroupID(string $name): int
    {
        $groupID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('dataview')->andWhere('name')->eq($name)->fetch('id');
        if(empty($groupID))
        {
            $group = new stdclass();
            $group->root   = 0;
            $group->name   = $name;
            $group->parent = 0;
            $group->grade  = 1;
            $group->order  = 10;
            $group->type   = 'dataview';

            $this->dao->insert(TABLE_MODULE)->data($group)->exec();
            $groupID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_MODULE)->set("`path` = CONCAT(',', `id`, ',')")->where('id')->eq($groupID)->exec();
        }
        return $groupID;
    }

    /**
     * 将内置透视表转化为中间表。
     * Convert built-in dataset to dataview.
     *
     * @access protected
     * @return void
     */
    protected function ConvertBuiltInDataSet(): void
    {
        $this->loadModel('dataset');
        $this->loadModel('dataview');

        $dataview = new stdclass();
        $dataview->group       = $this->getDataviewGroupID($this->lang->dataview->builtIn);
        $dataview->createdBy   = 'system';
        $dataview->createdDate = helper::now();

        foreach($this->lang->dataset->tables as $code => $dataset)
        {
            $sameCodeList = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('code')->eq($code)->fetchAll();
            if(!empty($sameCodeList)) continue;

            $dataview->name = $dataset['name'];
            $dataview->code = $code;
            $dataview->view = 'ztv_' . $code;

            $table = $this->dataset->getTableInfo($code);
            $dataview->sql = $this->dataset->getTableData($table->schema, 'id_desc', 100, true);

            $fields = array();
            foreach($table->schema->fields as $key => $field)
            {
                $relatedField = '';
                if($field['type'] == 'object' && isset($field['show']))
                {
                    $key = str_replace('.', '_', $field['show']);
                    $relatedField  = substr($field['show'], strpos($field['show'], '.') + 1);
                    $relatedObject = isset($field['object']) ? $field['object'] : '';
                    if(!empty($relatedObject) && isset($table->schema->objects[$relatedObject]))
                    {
                        foreach($table->schema->objects[$relatedObject] as $fieldID => $fieldName)
                        {
                            if($fieldID == $relatedField) continue;

                            $addField = "{$relatedObject}_{$fieldID}";
                            $fields[$addField] = array();
                            $fields[$addField]['name']   = isset($fieldName['name']) ? $fieldName['name'] : $addField;
                            $fields[$addField]['field']  = $fieldID;
                            $fields[$addField]['object'] = $relatedObject;
                            $fields[$addField]['type']   = 'object';
                        }
                    }
                }

                if(!isset($fields[$key])) $fields[$key] = array();
                $fields[$key]['name']   = $field['name'];
                $fields[$key]['field']  = empty($relatedField) ? $key : $relatedField;
                $fields[$key]['object'] = isset($field['object']) ? $field['object'] : $code;
                $fields[$key]['type']   = $field['type'];
            }
            $dataview->fields = json_encode($fields);

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) && !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }
    }

    /**
     * 将自定义透视表转化为中间表。
     * Convert custom dataset to dataview.
     *
     * @param  array $customDataset
     * @access protected
     * @return void
     */
    protected function ConvertCustomDataSet(array $customDataset): void
    {
        $this->loadModel('dataview');

        $dataview = new stdclass();
        $dataview->group = $this->getDataviewGroupID($this->lang->dataview->default);

        foreach($customDataset as $datasetID => $dataset)
        {
            $dataview->name        = $dataset->name;
            $dataview->code        = 'custom_' . $datasetID;
            $dataview->view        = 'ztv_custom_' . $datasetID;
            $dataview->sql         = $dataset->sql;
            $dataview->fields      = $dataset->fields;
            $dataview->createdBy   = $dataset->createdBy;
            $dataview->createdDate = $dataset->createdDate;
            $dataview->deleted     = $dataset->deleted;

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) && !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }
    }

    /**
     * 获取标准的版本 sql 语句。
     * Get standard sqls by version.
     *
     * @param  string $version
     * @access protected
     * @return string
     */
    protected function getStandardSQLs(string $version): string
    {
        if(empty($version)) $version = $this->config->installedVersion;

        $version      = str_replace('.', '_', $version);
        $openVersion  = $this->getOpenVersion($version);
        $openVersion  = str_replace('_', '.', $openVersion);
        $checkVersion = version_compare($openVersion, '16.5', '<') ? str_replace('_', '.', $version) : $openVersion;

        $standardSQL = $this->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'zentao' . $checkVersion . '.sql';
        if(!file_exists($standardSQL)) return '';
        $sqls = file_get_contents($standardSQL);

        if(empty($this->config->isINT))
        {
            $xStandardSQL = $this->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'xuanxuan' . $openVersion . '.sql';
            if(file_exists($xStandardSQL)) $sqls .= "\n" . file_get_contents($xStandardSQL);
        }
        return $sqls;
    }

    /**
     * 根据 sql 语句获取数据库中的字段。
     * Get fields in database by sql.
     *
     * @param  string    $sql
     * @access protected
     * @return array
     */
    protected function getFieldsBySQL(string $sql): array
    {
        $return = array('fields' => array(), 'lines' => array(), 'table' => '', 'tableExists' => true, 'changeEngineSQL' => '');
        $lines  = explode("\n", $sql);

        /* If table name isn't exist, skip it. . */
        $createHead = array_shift($lines);
        preg_match_all('/CREATE TABLE `([^`]*)`/', $createHead, $out);
        if(!isset($out[1][0])) return array_values($return);

        $return['table'] = str_replace('zt_', $this->config->db->prefix, $out[1][0]);
        try
        {
            $dbCreateSQL = $this->dbh->query("SHOW CREATE TABLE `{$return['table']}`")->fetch(PDO::FETCH_ASSOC);
            $dbSQLLines  = explode("\n", $dbCreateSQL['Create Table']);
            $dbSQLFoot   = array_pop($dbSQLLines);
            preg_match_all('/ENGINE=(\w+) /', $dbSQLFoot, $out);
            $dbEngine = isset($out[1][0]) ? $out[1][0] : 'InnoDB';

            array_shift($dbSQLLines);
            foreach($dbSQLLines as $dbSQLLine)
            {
                $dbSQLLine = trim($dbSQLLine);
                if(!preg_match('/^`([^`]*)` /', $dbSQLLine)) continue; // Skip no describe field line.

                $dbSQLLine = rtrim($dbSQLLine, ',');
                $dbSQLLine = str_replace('utf8 COLLATE utf8_general_ci', 'utf8', $dbSQLLine);
                $dbSQLLine = preg_replace('/ DEFAULT (\-?\d+\.?\d*)$/', " DEFAULT '$1'", $dbSQLLine);
                list($field) = explode(' ', $dbSQLLine);
                $return['fields'][$field] = rtrim($dbSQLLine, ',');
            }
        }
        catch(PDOException $e)
        {
            $errorInfo = $e->errorInfo;
            $errorCode = $errorInfo[1];
            if($errorCode == '1146') $return['tableExists'] = false; // If table is not extists, try create this table by create sql.
        }

        /* If the table engine in the database isn't the standard engine, change the table engine in the database to the standard engine. */
        $createFoot = array_pop($lines);
        preg_match_all('/ENGINE=(\w+) /', $createFoot, $out);
        $stdEngine = isset($out[1][0]) ? $out[1][0] : 'InnoDB';
        if($stdEngine != $dbEngine) $return['changeEngineSQL'] = "ALTER TABLE `{$return['table']}` ENGINE='{$stdEngine}'";

        $return['lines'] = $lines;
        return array_values($return);
    }

    /**
     * 根据标准 sql 语句修改数据库中的字段。
     * Change fields in database by standard sql.
     *
     * @param  string    $stdLine
     * @param  string    $dbLine
     * @access protected
     * @return string
     */
    protected function changeField(string $stdLine, string $dbLine): string
    {
        /* Get configs. */
        $stdConfigs = explode(' ', $stdLine);
        $dbConfigs  = explode(' ', $dbLine);
        if($stdConfigs[1] != $dbConfigs[1])
        {
            /* Get field type. */
            $stdType   = $stdConfigs[1];
            $dbType    = $dbConfigs[1];
            $stdLength = 0;
            $dbLength  = 0;

            /* Get field type and length. */
            preg_match_all('/^(\w+)(\((\d+)\))?$/', $stdConfigs[1], $stdOutput);
            if(!empty($stdOutput[1][0])) $stdType   = $stdOutput[1][0];
            if(!empty($stdOutput[3][0])) $stdLength = $stdOutput[3][0];
            preg_match_all('/^(\w+)(\((\d+)\))?$/', $dbConfigs[1], $dbOutput);
            if(!empty($dbOutput[1][0])) $dbType   = $dbOutput[1][0];
            if(!empty($dbOutput[3][0])) $dbLength = $dbOutput[3][0];

            $stdIsInt     = stripos($stdType, 'int') !== false;
            $stdIsVarchar = stripos($stdType, 'varchar') !== false;
            $stdIsText    = stripos($stdType, 'text') !== false;
            $dbIsInt      = stripos($dbType, 'int') !== false;
            $dbIsText     = stripos($dbType, 'text') !== false;
            /* If the type in database is int and the length of type is empty, get the length from the config. */
            if($dbIsInt && $dbLength == 0)
            {
                $intFieldLengths = zget($this->config->upgrade->dbFieldLengths, $dbConfigs[2] == 'unsigned' ? 'unsigned' : 'int', array());
                $dbLength = zget($intFieldLengths, $dbType, 0);
            }
            if($dbLength > $stdLength)     $stdConfigs[1] = $dbConfigs[1]; // If the length in database is longer than the length in standard, use the length in database.
            if($stdIsInt && $dbIsText)     $stdConfigs[1] = $dbConfigs[1]; // If the type in standard is int and the type in database is text, use text as the type.
            if($stdIsVarchar && $dbIsText) $stdConfigs[1] = $dbConfigs[1]; // If the type in standard is varchar and the type in database is text, use text as the type.
            /* If both of the type in standard and in database are text, get the length from the config. */
            if($stdIsText && $dbIsText)
            {
                $textFieldLengths = $this->config->upgrade->dbFieldLengths['text'];
                if($textFieldLengths[$stdType] < $textFieldLengths[$dbType]) $stdConfigs[1] = $dbConfigs[1];
            }
            if(stripos($stdConfigs[1], 'int') === false && $stdConfigs[2] == 'unsigned') unset($stdConfigs[2]);

            $stdLine = implode(' ', $stdConfigs);
            if($stdLine == $dbLine) return ''; // If the sql is same, won't change it.
        }
        return $stdLine;
    }

    /**
     * 获取查询关联到发布的动作的sql。
     * Get sql of query action linked to release.
     *
     * @access protected
     * @return string
     */
    protected function getLinked2releaseActionsSql()
    {
        return $this->dao->select('objectID, extra, max(`date`) as date, action')
            ->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('action')->eq('linked2release')
            ->groupBy('objectID')
            ->get();
    }

    /**
     * 获取关联分支记录相关的数据。
     * Get data linked to release.
     *
     * @access protected
     * @return array
     */
    protected function getReleasedStorys()
    {
        $linked2releaseActions = $this->getLinked2releaseActionsSql();

        return $this->dao->select('t2.id, t1.date')
            ->from("($linked2releaseActions)")->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.objectID = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->leftJoin(TABLE_RELEASE)->alias('t4')->on('t1.extra = t4.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t2.stage', true)->eq('released')
            ->orWhere('t2.closedReason')->eq('done')
            ->markRight(1)
            ->fetchAll();
    }
}
