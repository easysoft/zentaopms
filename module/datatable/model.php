<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Hao sun <sunhao@cnezsoft.com>
 * @package     datatable
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class datatableModel extends model
{
    /**
     * Get field list.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getFieldList($module)
    {
        if(!isset($this->config->$module)) $this->loadModel($module);
        if($this->session->hasProduct == 0 and (strpos($this->config->datatable->noProductModule, ",$module,") !== false))
        {
            $productIndex = array_search('product', $this->config->$module->dtable->defaultField);
            if($productIndex) unset($this->config->$module->dtable->defaultField[$productIndex]);
            if(isset($this->config->$module->dtable->fieldList['product'])) unset($this->config->$module->dtable->fieldList['product']);
        }
        if($this->session->currentProductType === 'normal') unset($this->config->$module->dtable->fieldList['branch']);
        foreach($this->config->$module->dtable->fieldList as $field => $items)
        {
            if($field === 'branch')
            {
                if($this->session->currentProductType === 'branch')   $this->config->$module->dtable->fieldList[$field]['title'] = $this->lang->datatable->branch;
                if($this->session->currentProductType === 'platform') $this->config->$module->dtable->fieldList[$field]['title'] = $this->lang->datatable->platform;
                continue;
            }

            if(!isset($items['title'])) $items['title'] = $field;
            $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
            $this->config->$module->dtable->fieldList[$field]['title'] = $title;

            /* Set col config default value. */
            if(!empty($items['type']) && isset($this->config->datatable->defaultColConfig[$items['type']]))
            {
                $this->config->$module->dtable->fieldList[$field] = array_merge($this->config->datatable->defaultColConfig[$items['type']], $this->config->$module->dtable->fieldList[$field]);
            }
        }

        if($this->config->edition != 'open')
        {
            $fields = $this->loadModel('workflowfield')->getList($module);
            foreach($fields as $field)
            {
                if($field->buildin) continue;
                $this->config->$module->dtable->fieldList[$field->field]['title']    = $field->name;
                $this->config->$module->dtable->fieldList[$field->field]['width']    = '120';
                $this->config->$module->dtable->fieldList[$field->field]['fixed']    = 'no';
                $this->config->$module->dtable->fieldList[$field->field]['required'] = 'no';
            }
        }

        return $this->config->$module->dtable->fieldList;
    }

    /**
     * Get save setting field.
     *
     * @param  string $module
     * @access public
     * @return object
     */
    public function getSetting(string $module)
    {
        $method      = $this->app->getMethodName();
        $datatableId = $module . ucfirst($method);

        $mode = isset($this->config->datatable->$datatableId->mode) ? $this->config->datatable->$datatableId->mode : 'table';

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->cols)) $setting = json_decode($this->config->datatable->$datatableId->cols, true);

        $fieldList = $this->getFieldList($module);
        if(empty($setting))
        {
            $setting = $this->formatFields($module, $fieldList);
        }
        else
        {
            foreach($setting as $key => $set)
            {
                if(empty($set['required']) && empty($set['show']))
                {
                    unset($setting[$key]);
                    continue;
                }

                if($this->session->currentProductType === 'normal' and $set['id'] === 'branch')
                {
                    unset($setting[$key]);
                    continue;
                }

                if($set['name'] == 'actions') $set['width'] = $fieldList['actions']['width'];
            }
        }

        uasort($setting, array('datatableModel', 'sortCols'));

        return $setting;
    }

    /**
     * Format fields by config.
     *
     * @param  string $module
     * @param  array  $fieldList
     * @param  bool   $onlyshow
     * @access public
     * @return array
     */
    public function formatFields($module, $fieldList, $onlyshow = true): array
    {
        $this->app->loadLang('module');

        $setting = array();
        $order   = 1;
        foreach($fieldList as $field => $config)
        {
            if(empty($config['required']) && empty($config['show']) && $onlyshow) continue;

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
     * Sort cols.
     *
     * @param  object $a
     * @param  object $b
     * @static
     * @access public
     * @return int
     */
    public static function sortCols($a, $b)
    {
        if(!isset($a['order']) or !isset($b['order'])) return 0;
        return $a['order'] - $b['order'];
    }

    /**
     * Print table head.
     *
     * @param  object $col
     * @param  string $orderBy
     * @param  string $vars
     * @param  bool   $checkBox
     * @access public
     * @return void
     */
    public function printHead($col, $orderBy, $vars, $checkBox = true)
    {
        $id = $col->id;
        if($col->show)
        {
            $fixed = $col->fixed == 'no' ? 'true' : 'false';
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
     * @param  object $setting
     * @param  int    $minLeftWidth
     * @param  int    $minRightWidth
     * @access public
     * @return array
     */
    public function setFixedFieldWidth($setting, $minLeftWidth = '550', $minRightWidth = '140')
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
