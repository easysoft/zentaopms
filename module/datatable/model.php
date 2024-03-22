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

        $fieldList = isset($config->dtable->fieldList) ? $config->dtable->fieldList : array();

        /* If doesn't need product, remove 'product' field. */
        if($this->session->hasProduct == 0 && (strpos($this->config->datatable->noProductModule, ",$module,") !== false))
        {
            $productIndex = array_search('product', $config->dtable->defaultField);
            if($productIndex) unset($config->dtable->defaultField[$productIndex]);
            if(isset($fieldList['product'])) unset($fieldList['product']);
        }

        /* Nomal product without 'branch' field. */
        if($this->session->currentProductType === 'normal') unset($config->fieldList['branch'], $config->fieldList['branchName']);

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
            $fields            = $this->loadModel('workflowfield')->getList($module);
            $workflowFieldList = $this->loadModel('flow')->buildDtableCols($fields);
            $fieldList         = array_merge($fieldList, $workflowFieldList);
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
        if(strpos(',product-browse,execution-story,', ",$module-$method,") !== false && strpos(',story,requirement,', $extra) !== false) $datatableId .= ucfirst($extra);

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);

        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->cols)) $setting = json_decode($this->config->datatable->$datatableId->cols, true);

        $fieldList    = $this->getFieldList($module, $method);
        $fieldSetting = array();
        foreach($fieldList as $field => $fieldConfig)
        {
            if(isset($setting[$field]))
            {
                $fieldSetting[$field] = $setting[$field];
                continue;
            }
            $fieldSetting[$field] = $fieldConfig;
        }

        if(empty($fieldSetting))
        {
            $fieldSetting = $this->formatFields($module, $fieldList, !$showAll);
        }
        else
        {
            foreach($fieldSetting as $field => $set)
            {
                if(!$showAll && empty($set['required']) && empty($set['show']))
                {
                    unset($fieldSetting[$field]);
                    continue;
                }

                if(isset($set['display']) && $set['display'] === false)
                {
                    unset($fieldSetting[$field]);
                    continue;
                }

                if($this->session->currentProductType === 'normal' && in_array($field, array('branch', 'branchName')))
                {
                    unset($fieldSetting[$field]);
                    continue;
                }

                if(isset($fieldList[$field]))
                {
                    foreach($fieldList[$field] as $key => $value)
                    {
                        if(!isset($set[$key])) $fieldSetting[$field][$key] = $value;
                    }
                }

                if(!isset($set['name'])) $fieldSetting[$field]['name'] = $field;
                if($module == 'testcase' && $field == 'id') $fieldSetting[$field]['name'] = 'caseID';
                if($field == 'actions' && empty($fieldSetting[$field]['width'])) $fieldSetting[$field]['width'] = $fieldList[$field]['width'];
            }

            if(in_array($module, array('product', 'project', 'execution')) and empty($this->config->setCode)) unset($fieldSetting['code']);
        }

        uasort($fieldSetting, array('datatableModel', 'sortCols'));

        return $fieldSetting;
    }

    /**
     * Get field list.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getOldFieldList(string $module)
    {
        if(!isset($this->config->$module)) $this->loadModel($module);
        if($this->session->hasProduct == 0 and (strpos($this->config->datatable->noProductModule, ",$module,") !== false))
        {
            $productIndex = array_search('product', $this->config->$module->datatable->defaultField);
            if($productIndex) unset($this->config->$module->datatable->defaultField[$productIndex]);
            if(isset($this->config->$module->datatable->fieldList['product'])) unset($this->config->$module->datatable->fieldList['product']);
        }
        if($this->session->currentProductType === 'normal') unset($this->config->$module->datatable->fieldList['branch']);
        foreach($this->config->$module->datatable->fieldList as $field => $items)
        {
            if($field === 'branch')
            {
                if($this->session->currentProductType === 'branch')   $this->config->$module->datatable->fieldList[$field]['title'] = $this->lang->datatable->branch;
                if($this->session->currentProductType === 'platform') $this->config->$module->datatable->fieldList[$field]['title'] = $this->lang->datatable->platform;
                continue;
            }
            $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
            $this->config->$module->datatable->fieldList[$field]['title'] = $title;
        }

        if($this->config->edition != 'open')
        {
            $fields = $this->loadModel('workflowfield')->getList($module);
            foreach($fields as $field)
            {
                if($field->buildin) continue;
                $this->config->$module->datatable->fieldList[$field->field]['title']    = $field->name;
                $this->config->$module->datatable->fieldList[$field->field]['width']    = '120';
                $this->config->$module->datatable->fieldList[$field->field]['fixed']    = 'no';
                $this->config->$module->datatable->fieldList[$field->field]['required'] = 'no';
            }
        }

        return $this->config->$module->datatable->fieldList;
    }

    /**
     * Get save setting field.
     *
     * @param  string $module
     * @access public
     * @return object
     */
    public function getOldSetting(string $module)
    {
        $method      = $this->app->getMethodName();
        $datatableId = $module . ucfirst($method);

        $mode = isset($this->config->datatable->$datatableId->mode) ? $this->config->datatable->$datatableId->mode : 'table';
        $key  = $mode == 'datatable' ? 'cols' : 'tablecols';

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->$key)) $setting = json_decode($this->config->datatable->$datatableId->$key);

        $fieldList = $this->getOldFieldList($module);
        if(empty($setting))
        {
            $setting = $this->config->$module->datatable->defaultField;
            $order   = 1;
            foreach($setting as $key => $value)
            {
                $id  = $value;
                $set = new stdclass();;
                $set->order = $order++;
                $set->id    = $id;
                $set->show  = true;
                $set->width = $fieldList[$id]['width'];
                $set->fixed = $fieldList[$id]['fixed'];
                $set->title = $fieldList[$id]['title'];
                $set->sort  = isset($fieldList[$id]['sort']) ? $fieldList[$id]['sort'] : 'yes';
                $set->name  = isset($fieldList[$id]['name']) ? $fieldList[$id]['name'] : '';

                if(isset($fieldList[$id]['minWidth'])) $set->minWidth = $fieldList[$id]['minWidth'];
                if(isset($fieldList[$id]['maxWidth'])) $set->maxWidth = $fieldList[$id]['maxWidth'];
                if(isset($fieldList[$id]['pri']))      $set->pri = $fieldList[$id]['pri'];

                $setting[$key] = $set;
            }
        }
        else
        {
            foreach($setting as $key => $set)
            {
                if(!isset($fieldList[$set->id]))
                {
                    unset($setting[$key]);
                    continue;
                }
                if($this->session->currentProductType === 'normal' and $set->id === 'branch')
                {
                    unset($setting[$key]);
                    continue;
                }

                if($set->id == 'actions') $set->width = $fieldList[$set->id]['width'];
                $set->title = $fieldList[$set->id]['title'];
                $set->sort  = isset($fieldList[$set->id]['sort']) ? $fieldList[$set->id]['sort'] : 'yes';
            }
        }

        usort($setting, array('datatableModel', 'sortOldCols'));

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

    /**
     * Sort old cols.
     *
     * @param  object $a
     * @param  object $b
     * @static
     * @access public
     * @return int
     */
    public static function sortOldCols(object $a, object $b)
    {
        if(!isset($a->order)) return 0;
        return $a->order - $b->order;
    }

    /**
     * 打印表格标题。
     * Print table head.
     *
     * @param  object $col
     * @param  string $orderBy
     * @param  string $vars
     * @param  bool   $checkBox
     * @access public
     * @return void
     */
    public function printHead(object $col, string $orderBy, string $vars, bool $checkBox = true)
    {
        $id = $col->id;
        if($col->show)
        {
            $fixed = zget($col, 'fixed', 'no') == 'no' ? 'true' : 'false';
            $width = is_numeric($col->width) ? "{$col->width}px" : $col->width;
            $title = isset($col->title) ? "title='$col->title'" : '';
            $title = (isset($col->name) and $col->name) ? "title='$col->name'" : $title;
            if($id == 'id' and (int)$width < 90) $width = '90px';
            $align = $id == 'actions' ? 'text-center' : '';
            $align = in_array($id, array('budget', 'teamCount', 'estimate', 'consume', 'consumed', 'left')) ? 'text-right' : $align;

            $style  = '';
            $data   = '';
            $data  .= "data-width='$width'";
            $style .= "width:$width;";
            if(isset($col->minWidth))
            {
                $data  .= "data-minWidth='{$col->minWidth}px'";
                $style .= "min-width:{$col->minWidth}px;";
            }
            if(isset($col->maxWidth))
            {
                $data  .= "data-maxWidth='{$col->maxWidth}px'";
                $style .= "max-width:{$col->maxWidth}px;";
            }
            if(isset($col->pri)) $data .= "data-pri='{$col->pri}'";

            echo "<th data-flex='$fixed' $data style='$style' class='c-$id $align' $title>";
            if($id == 'actions')
            {
                echo $this->lang->actions;
            }
            elseif(isset($col->sort) and $col->sort == 'no')
            {
                echo $col->title;
            }
            else
            {
                if($id == 'id' && $checkBox) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
                common::printOrderLink($id, $orderBy, $vars, $col->title);
            }
            echo '</th>';
        }
    }

    /**
     * Set fixed field width
     *
     * @param  array  $setting
     * @param  int    $minLeftWidth
     * @param  int    $minRightWidth
     * @access public
     * @return array
     */
    public function setFixedFieldWidth(array $setting, int $minLeftWidth = 550, int $minRightWidth = 140)
    {
        $widths['leftWidth']  = 30;
        $widths['rightWidth'] = 0;
        $hasLeftAuto  = false;
        $hasRightAuto = false;
        foreach($setting as $key => $value)
        {
            if($value->fixed != 'no')
            {
                if($value->fixed == 'left' and $value->width == 'auto')  $hasLeftAuto  = true;
                if($value->fixed == 'right' and $value->width == 'auto') $hasRightAuto = true;
                $widthKey = $value->fixed . 'Width';
                if(!isset($widths[$widthKey])) $widths[$widthKey] = 0;
                $widths[$widthKey] += (int)trim($value->width, 'px');
            }
        }
        if($widths['leftWidth'] <= 550 and $hasLeftAuto) $widths['leftWidth']  = 550;
        if($widths['rightWidth'] <= 0 and $hasRightAuto) $widths['rightWidth'] = 140;

        return $widths;
    }
}
