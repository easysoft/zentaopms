<?php

class biModel extends model
{
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
}
