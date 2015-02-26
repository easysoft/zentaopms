<?php
class devModel extends model
{
    public function getTables()
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql = "SHOW TABLES";
        $tables = array();
        $datatables = $this->dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach($datatables as $table)
        {   
            $tables[current($table)] = current($table);
        }
        return $tables;
    }

    public function descTable($table)
    {
        $module = substr($table, strpos($table, '_') + 1);
        try
        {
            if($module == 'case') $module = 'testcase';
            $this->app->loadLang($module);
        }
        catch(PDOException $e)
        {
            $this->lang->$module = new stdclass();
        }

        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $table";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny'), '', $type);
            $field    = array();
            $field['name'] = isset($this->lang->$module->{$rawField->field}) ? $this->lang->$module->{$rawField->field} : '';
            $field['null'] = $rawField->null;

            if($type == 'enum' or $type == 'set')
            {
                $rangeBegin  = $firstPOS + 2;                       // Remove the first quote.
                $rangeEnd    = strrpos($rawField->type, ')') - 1;   // Remove the last quote.
                $range       = substr($rawField->type, $rangeBegin, $rangeEnd - $rangeBegin);
                $field['type'] = $rawField->type;
                $field['options']['enum']  = str_replace("','", ',', $range);
            }
            elseif($type == 'varchar')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type']   = 'varchar';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'char')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type']   = 'char';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'int')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type'] = 'int';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'float' or $type == 'double')
            {
                $field['type'] = 'float';
            }
            elseif($type == 'date')
            {
                $field['type'] = 'date';
            }
            else
            {
                $field['type'] = $type;
            }
            $fields[$rawField->field] = $field;
        }
        return $fields;
    }
}
