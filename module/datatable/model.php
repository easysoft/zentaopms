<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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

        if(isset($this->config->bizVersion))
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
    public function getSetting($module)
    {
        $method      = $this->app->getMethodName();
        $datatableId = $module . ucfirst($method);

        $mode = isset($this->config->datatable->$datatableId->mode) ? $this->config->datatable->$datatableId->mode : 'table';
        $key  = $mode == 'datatable' ? 'cols' : 'tablecols';

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->$key)) $setting = json_decode($this->config->datatable->$datatableId->$key);

        $fieldList = $this->getFieldList($module);
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

        usort($setting, array('datatableModel', 'sortCols'));

        return $setting;
    }

    /**
     * Sort cols.
     * 
     * @param  int    $a 
     * @param  int    $b 
     * @static
     * @access public
     * @return void
     */
    public static function sortCols($a, $b)
    {
        if(!isset($a->order)) return 0;
        return $a->order - $b->order;
    }

    /**
     * Print table head.
     * 
     * @param  object $col 
     * @param  string $orderBy 
     * @param  string $vars 
     * @access public
     * @return void
     */
    public function printHead($col, $orderBy, $vars, $checkBox = true)
    {
        $id = $col->id;
        if($col->show)
        {
            $fixed = $col->fixed == 'no' ? 'true': 'false';
            $width = is_numeric($col->width) ? "{$col->width}px" : $col->width;
            $title = isset($col->title) ? "title='$col->title'" : '';
            $title = (isset($col->name) and $col->name) ? "title='$col->name'" : $title;
            if($id == 'id' and (int)$width < 90) $width = '90px';
            $width = "data-width='$width' style='width:$width'";
            $align = ($id == 'actions' || $id == 'progress') ? 'text-center' : '';

            echo "<th data-flex='$fixed' $width class='c-$id $align' $title>";
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
