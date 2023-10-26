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
    public function getDimension($dimensionID = 0, $type = '')
    {
        $dimensionID = $this->saveState($dimensionID);
        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

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
}
