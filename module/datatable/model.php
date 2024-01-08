<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Hao sun <sunhao@cnezsoft.com>
 * @package     datatable
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php
class datatableModel extends model
{
    /**
     * 获取列表字段的基本配置信息。
     * Get field list.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFieldList(string $module, string $method = ''): array
    {
        /* Load corresponding module. */
        if(!isset($this->config->$module)) $this->loadModel($module);

        $config = $this->config->$module;
        if(!empty($method) && isset($config->$method) && isset($config->$method->dtable)) $config = $config->$method;

        $fieldList = $config->dtable->fieldList;

        /* If doesn't need product, remove 'product' field. */
        if($this->session->hasProduct == 0 && (strpos($this->config->datatable->noProductModule, ",$module,") !== false))
        {
            $productIndex = array_search('product', $config->dtable->defaultField);
            if($productIndex) unset($config->dtable->defaultField[$productIndex]);
            if(isset($fieldList['product'])) unset($fieldList['product']);
        }

        /* Nomal product without 'branch' field. */
        if($this->session->currentProductType === 'normal') unset($config->fieldList['branch']);

        foreach($fieldList as $fieldName => $items)
        {
            /* Translate field title. */
            if(!isset($items['title'])) $items['title'] = $fieldName;
            $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
            $fieldList[$fieldName]['title'] = $title;

            /* Set col config default value. */
            if(!empty($items['type']) && isset($this->config->datatable->defaultColConfig[$items['type']]))
            {
                $fieldList[$fieldName] = array_merge($this->config->datatable->defaultColConfig[$items['type']], $fieldList[$fieldName]);
            }
        }

        /* Logic except open source version .*/
        if($this->config->edition != 'open')
        {
            $fields = $this->loadModel('workflowfield')->getList($module);
            foreach($fields as $field)
            {
                if($field->buildin) continue;
                $fieldList[$field->field]['title']    = $field->name;
                $fieldList[$field->field]['width']    = '120';
                $fieldList[$field->field]['fixed']    = 'no';
                $fieldList[$field->field]['required'] = 'no';
            }
        }

        return $fieldList;
    }

    /**
     * 获取列表显示的字段信息。
     * Get save setting field.
     *
     * @param  string $module
     * @param  string $method
     * @param  bool   $showAll
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getSetting(string $module, string $method = '', bool $showAll = false, string $extra = ''): array
    {
        if(!$method) $method = $this->app->getMethodName();
        $datatableId = $module . ucfirst($method);

        /* Split story and requirement custom fields. */
        if($module == 'product' && $method == 'browse' && strpos(',story,requirement,', $extra) !== false) $datatableId .= ucfirst($extra);

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);

        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->cols)) $setting = json_decode($this->config->datatable->$datatableId->cols, true);

        $fieldList = $this->getFieldList($module, $method);
        if(empty($setting))
        {
            $setting = $this->formatFields($module, $fieldList, !$showAll);
        }
        else
        {
            foreach($setting as $field => $set)
            {
                if(isset($fieldList[$field]))
                {
                    foreach($fieldList[$field] as $key => $value)
                    {
                        if(!isset($set[$key])) $setting[$field][$key] = $value;
                    }
                }

                if(!$showAll && empty($set['required']) && empty($set['show']))
                {
                    unset($setting[$field]);
                    continue;
                }

                if($this->session->currentProductType === 'normal' and $field === 'branch')
                {
                    unset($setting[$field]);
                    continue;
                }

                if(!isset($set['name'])) $setting[$field]['name'] = $field;
                if($module == 'testcase' && $field == 'id') $setting[$field]['name'] = 'caseID';
                if($field == 'actions' && empty($setting[$field]['width'])) $setting[$field]['width'] = $fieldList[$field]['width'];
            }
        }

        uasort($setting, array('datatableModel', 'sortCols'));

        return $setting;
    }

    /**
     * 获取期望的配置项。
     * Format fields by config.
     *
     * @param  string $module
     * @param  array  $fieldList
     * @param  bool   $onlyshow
     * @access public
     * @return array
     */
    public function formatFields(string $module, array $fieldList, bool $onlyshow = true): array
    {
        $this->app->loadLang($module);

        $setting = array();
        $order   = 1;
        foreach($fieldList as $field => $config)
        {
            if((isset($config['display']) && !$config['display']) || (empty($config['required']) && empty($config['show']) && $onlyshow)) continue;

            $config['order']    = $order++;
            $config['id']       = $field;
            $config['show']     = !empty($config['show']);
            $config['sortType'] = !empty($config['sortType']);
            $config['title']    = zget($config, 'title', zget($this->lang->$module, $field, zget($this->lang, $field)));
            $config['name']     = zget($config, 'name',  $field);
            $config['type']     = zget($config, 'type',  'text');
            $config['width']    = zget($config, 'width', '');
            $config['fixed']    = zget($config, 'fixed', '');
            $config['link']     = zget($config, 'link',  '');
            $config['group']    = zget($config, 'group', '');

            $setting[$field] = $config;
        }

        return $setting;
    }

    /**
     * 字段排序规则。
     * Sort cols.
     *
     * @param  array $a
     * @param  array $b
     * @static
     * @access public
     * @return int
     */
    public static function sortCols(array $a, array $b): int
    {
        if(!isset($a['order']) or !isset($b['order'])) return 0;
        return $a['order'] - $b['order'];
    }
}
