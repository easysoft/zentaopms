<?php
/**
 * ZenTaoPHP的dao和sql类。
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * Dameng类。
 * Dameng driver.
 *
 * @package framework
 */
class dm extends dao
{
    /**
     * 设置$table属性。
     * Set the $table property.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function setTable($table)
    {
        $this->table = trim($table, '`');
    }

    /**
     * Show tables.
     *
     * @access public
     * @return array
     */
    public function showTables()
    {
        $sql = "SELECT \"table_name\" FROM all_tables WHERE OWNER = '{$this->config->db->name}'";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get table engines.
     *
     * @access public
     * @return array
     */
    public function getTableEngines()
    {
        $tables = $this->query("SELECT \"table_name\" FROM all_tables WHERE OWNER = '{$this->config->db->name}'")->fetchAll();

        $tableEngines = array();
        foreach($tables as $table) $tableEngines[$table->table_name] = 'InnoDB';

        return $tableEngines;
    }

    /**
     * 类MySQL的DESC语法。
     * Desc table, show fields.
     *
     * @param  string $tableName
     * @access public
     * @return array
     */
    public function descTable($tableName)
    {
        $tableName = trim($tableName, '`');
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql = "select * from all_tab_columns where table_name = '$tableName'";
        $rawFields = $this->dbh->query($sql)->fetchAll();
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        $fields = array();
        foreach($rawFields as $rawField)
        {
            $field = new stdClass();
            $field->field = $rawField->column_name;
            $field->type  = $rawField->data_type;
            $field->null  = $rawField->nullable;
            $fields[] = $field;
        }
        return $fields;
    }

    /**
     * select方法，调用sql::select()。
     * The select method, call sql::select().
     *
     * @param  string $fields
     * @access public
     * @return static|sql|baseDAO the dao object self.
     */
    public function select($fields = '*')
    {
        /* Split by ','. */
        $fieldList = preg_split("/,(?![^(]+\))/", $fields);
        foreach($fieldList as $key => $field)
        {
            $field = trim($field);
            $pos   = strrpos($field, ' ');
            if($pos)
            {
                $originField = substr($field, 0, $pos);
                $alias       = trim(substr($field, $pos));

                $fieldList[$key] = $this->formatField($originField, $alias);
            }
            else
            {
                $fieldList[$key] = $this->formatField($field);
            }
        }

        return parent::select(implode(',', $fieldList));
    }

    /**
     * Format field: date => "date", t1.date => t1."date"
     *
     * @param string $originField
     * @param string $alias
     * @access private
     * @return tring
     */
    private function formatField($originField, $alias = '')
    {
        /* Format originField. */
        $replace = array(
            'GROUP_CONCAT' => 'WM_CONCAT',
        );

        if(stripos($originField, 'if(') !== false)
        {
            $originField = $this->dbh->formatDmIfFunction($originField);
        }
        elseif(strcasecmp($originField, 'distinct') !== 0)
        {
            $tableField = explode('.', $originField);
            if(count($tableField) == 2 and ctype_alnum($tableField[1]) and $tableField[1] != '*')
            {
                $originField = $tableField[0] . '."' . $tableField[1] . '"';
            }
            elseif(count($tableField) == 1 and ctype_alnum($tableField[0]) and $tableField[0] != '*')
            {
                $originField = '"' . $tableField[0] . '"';
            }
            $originField = str_ireplace(array_keys($replace), array_values($replace), $originField);
        }

        /* Format alias. */
        if($alias and !is_numeric($alias) and ctype_alnum($alias)) $alias = '"' . $alias . '"';

        return $originField . ' ' .  $alias;
    }

    /**
     * Format if function.
     *
     * @param  string $field
     * @access private
     * @return string
     */
    /*
    private function formatIfFunction($field)
    {
        preg_match('/if\(.+\)+/i', $field, $matches);

        $if = $matches[0];
        if(substr_count($if, '(') == 1)
        {
            $pos = strpos($if, ')');
            $if  = substr($if, 0, $pos+1);
        }

        // * fix sum(if(..., 1, 0)) , count(if(..., 1, 0)) * //
        if(substr($if, strlen($if)-2) == '))' and (stripos($field, 'sum(') == 0 or stripos($field, 'count(') == 0)) $if = substr($if, 0, strlen($if)-1);

        $parts = explode(',', substr($if, 3, strlen($if)-4)); // remove 'if(' and ')'
        $case  = 'CASE WHEN ' . implode(',', array_slice($parts, 0, count($parts)-2)) . ' THEN ' . $parts[count($parts)-2] . ' ELSE ' . $parts[count($parts)-1] . ' END';
        $field = str_ireplace($if, $case, $field);

        return $field;
    }
     */

    /**
     * 创建WHERE部分。
     * Create the where part.
     *
     * @param  string $arg1     the field name
     * @param  string $arg2     the operator
     * @param  string $arg3     the value
     * @access public
     * @return static|sql the sql object.
     */
    public function where($arg1 = '', $arg2 = null, $arg3 = null)
    {
        $arg1 = $this->formatWhere($arg1);
        return parent::where($arg1, $arg2, $arg3);
    }

    /**
     * 创建AND部分。
     * Create the AND part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function andWhere($condition = '', $addMark = false)
    {
        $condition = $this->formatWhere($condition);
        return parent::andWhere($condition, $addMark);
    }

    /**
     * 创建OR部分。
     * Create the OR part.
     *
     * @param  bool  $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function orWhere($condition)
    {
        $condition = $this->formatWhere($condition);
        return parent::orWhere($condition);
    }

    private function formatWhere($condition)
    {
        $condition = trim($condition);
        if($condition == '1') return '1 = 1';

        $pos = strrpos($condition, ' ');
        if($pos)
        {
            $originField = substr($condition, 0, $pos);
            return $this->formatField($originField) . substr($condition, $pos);
        }
        else
        {
            return $this->formatField($condition);
        }
    }

    /**
     * 创建GROUP BY部分。
     * Create the groupby part.
     *
     * @param  string $groupBy
     * @access public
     * @return static|sql the sql object.
     */
    public function groupBy($groupBy)
    {
        $groups = preg_split("/,(?![^(]+\))/", $groupBy);
        foreach($groups as $key => $group)
        {
            $groups[$key] = $this->formatField($group);
        }

        $groupBy = implode(',', $groups);
        return parent::groupBy($groupBy);
    }

    /**
     * 创建ORDER BY部分。
     * Create the order by part.
     *
     * @param  string $order
     * @access public
     * @return static|sql the sql object.
     */
    public function orderBy($order)
    {
        $order = str_replace('"', '', $order);
        return parent::orderBy($order);
    }

    /**
     * 创建ON部分。
     * Create the on part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function on($condition)
    {
        $fieldList = explode('=', $condition);
        foreach($fieldList as $key => $field) $fieldList[$key] = $this->formatField(trim($field));
        $condition = implode(' = ', $fieldList);

        return parent::on($condition);
    }

    /**
     * 获取唯一索引的列。
     * Get unique columns.
     *
     * @access public
     * @return array
     */
    public function getUniqueColumns()
    {
        $sql = "SELECT * from dba_ind_columns WHERE index_owner = '{$this->config->db->name}' AND index_name IN (SELECT index_name FROM dba_indexes WHERE table_name='{$this->table}' AND INDEX_TYPE = 'NORMAL' AND UNIQUENESS = 'UNIQUE' AND INDEX_NAME NOT IN (SELECT INDEX_NAME FROM DBA_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'P'))";

        $columns = $this->dbh->query($sql)->fetchAll();

        $cols = array();
        foreach($columns as $col) $cols[$col->COLUMN_NAME] = $col;
        return $cols;
    }

    /**
     * 执行SQL。query()会返回stmt对象，该方法只返回更改或删除的记录数。
     * Execute the sql. It's different with query(), which return the stmt object. But this not.
     *
     * @param  string $sql
     * @access public
     * @return int the modified or deleted records. 更改或删除的记录数。
     */
    public function exec($sql = '')
    {
        if(!empty(dao::$errors)) return new PDOStatement();   // If any error, return an empty statement object to make sure the remain method to execute.

        if($sql)
        {
            $this->sqlobj = new sql();
            $this->sqlobj->sql = $sql;
        }
        else
        {
            $sql = $this->processSQL();
            $this->sqlobj->sql = $sql;
        }

        if($this->method == 'replace' && !empty($this->sqlobj->data))
        {
            $insertSql = "INSERT INTO \"{$this->table}\" ";
            $fields = '(';
            $values = 'VALUES(';
            foreach($this->sqlobj->data as $field => $value)
            {
                $fields .= "`{$field}`,";
                if(is_string($value) or $value === null) $value = $this->sqlobj->quote($value);
                $values .= $value . ',';
            }
            $fields = substr($fields, 0, -1);
            $values = substr($values, 0, -1);
            $fields .= ')';
            $values .= ')';
            $insertSql .= $fields . ' ' . $values;

            $updateSql = str_replace('REPLACE', 'UPDATE', $sql);
            $cols      = $this->getUniqueColumns();

            /* No unique keys, no replace. */
            if(empty($cols)) return false;

            $conditions = array();
            foreach($cols as $colName => $col)
            {
                if(isset($this->sqlobj->data->{$colName})) $conditions[] = " \"{$colName}\" = '{$this->sqlobj->data->{$colName}}'";
            }
            if(!empty($conditions)) $updateSql .= ' WHERE ' . implode(' AND ', $conditions);

            $deleteSql = "DELETE FROM \"{$this->table}\" WHERE ";
            $ingore    = array();
            $ingore['`zt_config`'] = array('value');
            foreach($this->sqlobj->data as $field => $value)
            {
                if(isset($ingore[$this->table]) and in_array($field, $ingore[$this->table])) continue;
                if(!isset($cols[$field])) continue;
                $deleteSql .= "`{$field}` = ";
                $deleteSql .= is_string($value) ? "'{$value}'" : $value;
                $deleteSql .= ' AND ';
            }
            $deleteSql = rtrim($deleteSql, 'AND ');

            $sql = <<<EOT
DECLARE
  E_IDENTITY_INSERT EXCEPTION;
  E_INSTALL_DUP_VAL_ON_INDEX EXCEPTION;
  E_UPDATE_DUP_VAL_ON_INDEX EXCEPTION;
  PRAGMA EXCEPTION_INIT (E_IDENTITY_INSERT, -2723);
  PRAGMA EXCEPTION_INIT (E_INSTALL_DUP_VAL_ON_INDEX, -6602);
  PRAGMA EXCEPTION_INIT (E_UPDATE_DUP_VAL_ON_INDEX, -6610);

BEGIN
    BEGIN
        $insertSql;
    EXCEPTION
        WHEN DUP_VAL_ON_INDEX OR E_IDENTITY_INSERT THEN
            $updateSql;
        WHEN E_INSTALL_DUP_VAL_ON_INDEX THEN
            $updateSql;
    END;
EXCEPTION
    WHEN E_UPDATE_DUP_VAL_ON_INDEX THEN
      $deleteSql;
      $insertSql;
END;
EOT;
            self::$querys[] = $sql;
        }

        $sql = str_replace('`', '"', $sql);
        try
        {
            if($this->table) unset(dao::$cache[$this->table]);
            $this->reset();
            return $this->dbh->exec($sql);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
    }

    /**
     * 获取一个记录。
     * Fetch one record.
     *
     * @param  string $field        如果已经设置获取的字段，则只返回这个字段的值，否则返回这个记录。
     *                              if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch($field = '')
    {
        return parent::fetch($field);
    }

    /**
     * 获取所有记录。
     * Fetch all records.
     *
     * @param  string $keyField     返回以该字段做键的记录
     *                              the key field, thus the return records is keyed by this field
     * @access public
     * @return array the records
     */
    public function fetchAll($keyField = '')
    {
        return parent::fetchAll($keyField);
    }

    /**
     * 获取表的字段类型。
     * Get the defination of fields of the table.
     *
     * @access public
     * @return array
     */
    public function getFieldsType()
    {
        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "select * from all_tab_columns where table_name='{$this->table}'";
            $rawFields = $this->dbh->rawQuery($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }

        $fields = array();
        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->data_type, '(');
            $type     = substr($rawField->data_type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->data_type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny', 'var'), '', $type);
            $field    = array();

            if($type == 'VARCHAR' or $type == 'CHAR')
            {
                $length = $rawField->data_length;
                $field['rule'] = 'length';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'INTEGER')
            {
                $field['rule'] = 'int';
            }
            elseif($type == 'FLOAT' or $type == 'DOUBLE')
            {
                $field['rule'] = 'float';
            }
            elseif($type == 'DATE')
            {
                $field['rule'] = 'date';
            }
            elseif($type == 'DATETIME')
            {
                $field['rule'] = 'datetime';
            }
            else
            {
                $field['rule'] = 'skip';
            }
            $fields[$rawField->column_name] = $field;
        }
        return $fields;
    }
}
