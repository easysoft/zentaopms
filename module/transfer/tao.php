<?php
declare(strict_types=1);
/**
 * The zen file of transfer module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng<tanghucheng@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */

class transferTao extends transferModel
{
    /**
     * 根据config:dataSource中配置的方法获取字段数据源。
     * Get source by module method.
     *
     * @param  string $module
     * @param  string $callModule
     * @param  string $method
     * @param  string|array $params
     * @param  string|array $pairs
     * @access protected
     * @return array
     */
    protected function getSourceByModuleMethod(string $module, string $callModule, string $method, string|array $params = '', string|array $pairs = ''): array
    {
        /* 获取模块传递的参数。 */
        /* Get params. */
        $getParams = $this->session->{$module . 'TransferParams'};

        /* 解析dataSource params中配置的参数。 */
        /* Parse params. */
        if(is_string($params)) $params = explode('&', $params);
        foreach($params as $param => $value)
        {
            /* 如果参数是$开头的变量，则从SESSION中获取该变量。 */
            /* If the param is $var, get it from SESSION. */
            if(!is_string($value)) continue;
            if(strpos($value, '$') === false) continue;
            $params[$param] = isset($getParams[ltrim($value, '$')]) ? $getParams[ltrim($value, '$')] : '';
        }

        /* 调用模块的方法。 */
        /* If this method has multiple parameters use call_user_func_array. */
        if(is_array($params) and $params)
        {
            $values = call_user_func_array(array($this->loadModel($callModule), $method), $params);
        }
        else
        {
            $values = $this->loadModel($callModule)->$method($params);
        }

        /* 解析dataSource pairs中配置的参数(是否需要返回array(key => value)形式的关联数组。 */
        /* Parse pairs. */
        if(!empty($pairs))
        {
            $valuePairs = array();
            foreach($values as $key => $value)
            {
                if(is_object($value)) $value = get_object_vars($value);

                $valuePairs[$key] = $value[$pairs[1]];
                if(!empty($pairs[0])) $valuePairs[$value[$pairs[0]]] = $value[$pairs[1]];
            }
            $values = $valuePairs;
        }

        return $values;
    }

    /**
     * 根据id获取附件分组。
     * Get file group by id.
     *
     * @param  string $module
     * @param  array  $idList
     * @access protected
     * @return array
     */
    protected function getFileGroups(string $module, array $idList): array
    {
        return $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)
            ->where('objectType')->eq($module)
            ->beginIf($idList)->andWhere('objectID')->in($idList)->fi()
            ->andWhere('extra')
            ->ne('editor')
            ->fetchGroup('objectID');
    }

    /**
     * 获取级联数据列表。
     * Get cascade list for export excel.
     *
     * @param  array  $module
     * @param  array  $lists
     * @access public
     * @return array
     */
    protected function getCascadeList(string $module, array $lists): array
    {
        /* 如果没有配置config->cascade，则不需要进行级联操作。*/
        /* If has not cascade config, do not need to do cascade operation. */
        $this->commonActions($module);
        if(!isset($this->moduleConfig->cascade)) return $lists;

        $cascadeArray = $this->moduleConfig->cascade;

        foreach($cascadeArray as $field => $linkField)
        {
            $fieldName     = $field . 'List';
            $linkFieldName = $linkField . 'List';
            $tmpFieldName  = array();

            if(empty($lists[$fieldName]) and empty($lists[$linkFieldName])) continue;

            /* 根据字段名获取表名。*/
            /* Get table name by field name. */
            $table = zget($this->config->objectTables, $field);
            if(empty($table)) continue;

            /* 根据字段名获取关联数据。*/
            /* Get ID list by field name. */
            $fieldIDList = array_keys($lists[$fieldName]);
            $fieldDatas  = $this->dao->select("id, $linkField")->from($table)->where('id')->in($fieldIDList)->fetchPairs();

            if(empty($fieldDatas)) continue;

            /* 将获取到的数据替换到lists中。*/
            /* Replace data to lists. */
            foreach($fieldDatas as $id => $linkFieldID)
            {
                $tmpFieldName[$linkFieldID][$id] = $lists[$fieldName][$id];
            }

            $lists[$fieldName] = $tmpFieldName;
        }

        return $lists;
    }

    /**
     * 解析Excel中下拉框单元格的值。
     * Parse value of multiple dropdown cells.
     *
     * @param  string    $cellValue
     * @param  string    $field
     * @param  array     $values
     * @access protected
     * @return array
     */
    protected function extractElements(string $cellValue, string $field, array $values)
    {
        if(empty($values)) return $cellValue;

        /* 多行下拉框和单行下拉框都按照多行处理。*/
        /* Multiple dropdowns and single dropdowns are processed in multiple rows. */
        $cellValue = explode("\n", $cellValue);
        foreach($cellValue as &$value)
        {
            /* 解析下拉框的值value(#rawValue)，提取rawValue。*/
            /* Parse dropdown values value(#rawValue) and extract rawValue. */
            if(strrpos($value, '(#') !== false)
            {
                $value = trim(substr($value, strrpos($value,'(#') + 2), ')');
            }
            else
            {
                /* 如果value在values中存在则在values中查找（一般为语言项）。*/
                /* If value exists in values, find it in values (usually as a language item). */
                $valueKey = array_search($value, $values);
                $value    = $valueKey ? $valueKey : $value;
            }
        }

        /* 过滤掉数组中的空值（null、空字符串、0等）以及值为字符串 '0' 的元素，只保留其他非空元素。*/
        /* Filter out empty values (null, empty string, 0, etc.) in the array and only keep other non-empty elements. */
        $cellValue = array_filter($cellValue, function($v) {return (empty($v) && $v == '0') || !empty($v);});
        return implode(',', $cellValue);
    }

    /**
     * 配置导入字段。
     * Config import fields.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    protected function getImportFields(string $module = '')
    {
        $this->commonActions($module);
        $moduleLang = $this->moduleLang;
        $fields     = explode(',', $this->moduleConfig->templateFields); // 获取导入模板字段

        array_unshift($fields, 'id');
        foreach($fields as $key => $fieldName)
        {
            /* 匹配语言项。 */
            /* Match language item. */
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($moduleLang->$fieldName) ? $moduleLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        /* 获取工作流扩展字段。*/
        /* Get workflow extend fields. */
        if($this->config->edition != 'open')
        {
            $appendFields = $this->loadModel('workflowaction')->getFields($module, 'showimport', false);
            foreach($appendFields as $appendField)
            {
                /* 不是内置字段并且在导入确认页面展示。 */
                /* Is not builtin field and show in import confirm page. */
                if(!$appendField->buildin and $appendField->show) $fields[$appendField->field] = $appendField->name;
            }
        }

        return $fields;
    }

    /**
     * 更新子数据。
     * Update children datas.
     *
     * @param array $datas
     * @access public
     * @return array
     */
    protected function updateChildDatas(array $datas)
    {
        $children = array();
        foreach($datas as $data)
        {
            $id = $data->id;
            if(!empty($data->mode)) $datas[$id]->name = '[' . $this->lang->task->multipleAB . '] ' . $data->name;
            if(!empty($data->parent) and isset($datas[$data->parent]))
            {
                if(!empty($data->name)) $data->name = '>' . $data->name;
                elseif(!empty($data->title)) $data->title = '>' . $data->title;
                $children[$data->parent][$id] = $data;
                unset($datas[$id]);
            }
        }

        /* Move child data after parent data. */
        if(!empty($children))
        {
            $position = 0;
            foreach($datas as $data)
            {
                $position ++;
                if(isset($children[$data->id]))
                {
                    array_splice($datas, $position, 0, $children[$data->id]);
                    $position += count($children[$data->id]);
                }
            }
        }

        return $datas;
    }

    /**
     * 处理行数据。
     * Process rows for fields.
     *
     * @param  array  $rows
     * @param  array  $fields
     * @access public
     * @return array
     */
    protected function processRows4Fields($rows = array(), $fields = array())
    {
        $objectDatas = array();

        foreach($rows as $currentRow => $row)
        {
            $tmpArray = new stdClass();
            foreach($row as $currentColumn => $cellValue)
            {
                if($currentRow == 1)
                {
                    $field = array_search($cellValue, $fields);
                    $columnKey[$currentColumn] = $field ? $field : '';
                    continue;
                }

                if(empty($columnKey[$currentColumn]))
                {
                    $currentColumn++;
                    continue;
                }

                $field = $columnKey[$currentColumn];
                $currentColumn++;

                /* Check empty data. */
                if(empty($cellValue))
                {
                    $tmpArray->$field = '';
                    continue;
                }

                $tmpArray->$field = $cellValue;
            }

            if(!empty($tmpArray->title) and !empty($tmpArray->name)) $objectDatas[$currentRow] = $tmpArray;
            unset($tmpArray);
        }

        if(empty($objectDatas))
        {
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($this->lang->excel->noData);
            return print(js::locate('back'));
        }

        return $objectDatas;
    }
}
