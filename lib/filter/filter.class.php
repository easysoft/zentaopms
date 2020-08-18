<?php
/**
 * ZenTaoPHP的验证和过滤类。
 * The validater and fixer class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/filter/filter.class.php');
/**
 * validater类，检查数据是否符合规则。
 * The validater class, checking data by rules.
 * 
 * @package framework
 */
class validater extends baseValidater
{
}

/**
 * fixer类，处理数据。
 * fixer class, to fix data types.
 * 
 * @package framework
 */
class fixer extends baseFixer
{
    public function get($fields = '')
    {
        $fields = str_replace(' ', '', trim($fields));

        /* Get extend field by flow. */
        global $config;
        $flowFields = array();
        if(isset($config->bizVersion))
        {
            global $app, $dbh;
            $moduleName = $app->getModuleName();
            $stmt = $dbh->query("SELECT * FROM " . TABLE_WORKFLOWFIELD . " WHERE `module` = '{$moduleName}' and `buildin` = '0'");
            while($flowField = $stmt->fetch()) $flowFields[$flowField->field] = $flowField;
        }
        foreach($this->data as $field => $value)
        {
            /* Implode array when form has array. */
            if(isset($flowFields[$field]) and is_array($value))
            {
                $canImplode = true;
                foreach($value as $k => $v)
                {
                    if(is_object($v) or is_array($v))
                    {
                        $canImplode = false;
                        break;
                    }
                }
                if($canImplode) $this->data->$field = implode(',', $value);
            }
            if(isset($flowFields[$field]) and $flowFields[$field]->control == 'textarea') $this->skipSpecial($field);
            $this->specialChars($field);
        }

        if(empty($fields)) return $this->data;
        if(strpos($fields, ',') === false) return $this->data->$fields;

        /* Process fields for check by key. */
        $fields = array_flip(explode(',', $fields));
        foreach($this->data as $field => $value)
        {
            if(!isset($fields[$field])) unset($this->data->$field);
            if(!isset($this->stripedFields[$field])) $this->specialChars($field);
        }

        return $this->data;
    }
}
