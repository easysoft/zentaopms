<?php
/**
 * The model file of ddimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     dimension
 * @version     $Id: model.php 5086 2022-11-1 10:26:23Z $
 * @link        http://www.zentao.net
 */
class dimensionModel extends model
{
    /**
     * Get dimension by ID.
     *
     * @param  int    $dimensionID
     * @access public
     * @return object
     */
    public function getByID($dimensionID)
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('id')->eq($dimensionID)->fetch();
    }

    /**
     * Get first dimension.
     *
     * @access public
     * @return object
     */
    public function getFirst()
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('deleted')->eq('0')->orderBy('id')->limit(1)->fetch();
    }

    /**
     * Get dimension list.
     *
     * @access public
     * @return array
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * Set switcher menu and save last dimension.
     *
     * @param  int    $dimensionID
     * @param  string $type         screen | pivot | chart
     * @access public
     * @return void
     */
    public function setSwitcherMenu($dimensionID = 0, $type = '')
    {
        $dimensionID = $this->saveState($dimensionID);
        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $this->lang->switcherMenu = $this->getSwitcher($dimensionID, $moduleName, $methodName, $type);

        return $dimensionID;
    }

    /**
     * Save dimension state.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function saveState($dimensionID)
    {
        $dimensions = $this->getList();

        /* When the session do not exist, get it from the database. */
        if(empty($dimensionID) and isset($this->config->dimension->lastDimension) and isset($dimensions[$this->config->dimension->lastDimension]))
        {
            $this->session->set('dimension', $this->config->dimension->lastDimension, $this->app->tab);
            return $this->session->dimension;
        }

        if($dimensionID == 0 and $this->session->dimension)        $dimensionID = $this->session->dimension;
        if($dimensionID == 0 or !isset($dimensions[$dimensionID])) $dimensionID = key($dimensions);

        $this->session->set('dimension', (int)$dimensionID, $this->app->tab);

        return $this->session->dimension;
    }

    /*
     * Get project swapper.
     *
     * @param  int    $dimensionID
     * @param  string $currentModule
     * @param  string $currentMethod
     * @param  string $type             screen | pivot | chart
     * @access public
     * @return string
     */
    public function getSwitcher($dimensionID, $currentModule, $currentMethod, $type)
    {
        $currentDimensionName = $this->lang->dimension->common;
        if($dimensionID)
        {
            $currentDimension     = $this->getByID($dimensionID);
            $currentDimensionName = $currentDimension->name;
        }

        if($this->app->viewType == 'mhtml' and $dimensionID)
        {
            $output  = $this->lang->dimension->common . $this->lang->colon;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('dimension', '$dimensionID', '$currentModule', '$currentMethod', '')\">{$currentDimensionName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('dimension', 'ajaxGetDropMenu', "currentModule=$currentModule&currentMethod=$currentMethod&dimensionID=$dimensionID&type=$type");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentDimensionName}' style='padding-bottom:2px;'><span class='text'>{$currentDimensionName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }
}
