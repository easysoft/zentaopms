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
        $datatableId = $module . ucfirst($this->app->getMethodName());

        $module = zget($this->config->datatable->moduleAlias, $module, $module);
        if(!isset($this->config->$module)) $this->loadModel($module);
        if(isset($this->config->datatable->$datatableId->cols)) $setting = json_decode($this->config->datatable->$datatableId->cols);

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
                $setting[$key] = $set;
            }
        }
        else
        {
            foreach($setting as $key => $set)
            {
                if($this->session->currentProductType === 'normal' and $set->id === 'branch')
                {
                    unset($setting[$key]);
                    continue;
                }
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
    public function printHead($col, $orderBy, $vars)
    {
        $id = $col->id;
        if($col->show)
        {
            echo "<th data-flex='" . ($col->fixed == 'no' ? 'true': 'false') . "' data-width='{$col->width}' class='w-$id'>";
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
        $widths['leftWidth']  = 0;
        $widths['rightWidth'] = 0;
        foreach($setting as $key => $value)
        {
            if($value->fixed != 'no')
            {
                $widthKey = $value->fixed . 'Width';
                if(!isset($widths[$widthKey])) $widths[$widthKey] = 0;
                $widths[$widthKey] += (int)trim($value->width, 'px');
            }
        }
        if($widths['leftWidth'] <= 550) $widths['leftWidth']  = 550;
        if($widths['rightWidth'] <= 0)  $widths['rightWidth'] = 140;

        return $widths;
    }
}
