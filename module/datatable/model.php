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
            $productIndex = array_search('product', $this->config->$module->datatable->defaultField);
            if($productIndex) unset($this->config->$module->datatable->defaultField[$productIndex]);
            if(isset($this->config->$module->datatable->fieldList['product'])) unset($this->config->$module->datatable->fieldList['product']);
        }
        if($this->session->currentProductType === 'normal') unset($this->config->$module->datatable->fieldList['branch']);
        foreach($this->config->$module->datatable->fieldList as $field => $items)
        {
            if(zget($items, 'display', true) === false)
            {
                unset($this->config->$module->datatable->fieldList[$field]);
                continue;
            }

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
    public function getSetting($module)
    {
        $method      = $this->app->getMethodName();
        $datatableId = $module . ucfirst($method);

        $mode = isset($this->config->datatable->$datatableId->mode) ? $this->config->datatable->$datatableId->mode : 'table';
        $key  = $mode == 'datatable' ? 'cols' : 'tablecols';

        $module = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->$key))
        {
            if($datatableId == 'testcaseBrowse' && $key == 'tablecols' && $this->cookie->onlyScene)
            {
                $setting = json_decode('[{"id":"id","order":1,"show":true,"width":"70px","fixed":"left"},{"id":"title","order":2,"show":true,"width":"auto","fixed":"left"},{"id":"openedBy","order":8,"show":true,"width":"80px","fixed":"no"},{"id":"openedDate","order":9,"show":true,"width":"90px","fixed":"no"},{"id":"lastEditedBy","order":16,"show":true,"width":"80px","fixed":"no"},{"id":"lastEditedDate","order":17,"show":true,"width":"90px","fixed":"no"},{"id":"actions","order":23,"show":true,"width":"150px","fixed":"right"}]');
            }
            else
            {
                $setting = json_decode($this->config->datatable->$datatableId->$key);
            }
        }

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
                $set->sort  = isset($fieldList[$id]['sort']) ? $fieldList[$id]['sort'] : 'yes';
                $set->name  = isset($fieldList[$id]['name']) ? $fieldList[$id]['name'] : '';

                if(isset($fieldList[$id]['type']))     $set->type = $fieldList[$id]['type'];
                if(isset($fieldList[$id]['title']))    $set->title = $fieldList[$id]['title'];
                if(isset($fieldList[$id]['fixed']))    $set->fixed = $fieldList[$id]['fixed'];
                if(isset($fieldList[$id]['width']))    $set->width = $fieldList[$id]['width'];
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
     * @return int
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
     * @param  bool   $checkBox
     * @access public
     * @return void
     */
    public function printHead($col, $orderBy, $vars, $checkBox = true)
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
            if(zget($value, 'fixed', 'no') != 'no')
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
