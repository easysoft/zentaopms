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
}
