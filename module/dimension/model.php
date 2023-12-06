<?php
/**
 * The model file of ddimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     dimension
 * @version     $Id: model.php 5086 2022-11-1 10:26:23Z $
 * @link        https://www.zentao.net
 */
class dimensionModel extends model
{
    /**
     * 根据 id 获取一个维度对象。
     * Get a dimension object by id.
     *
     * @param  int    $dimensionID
     * @access public
     * @return object
     */
    public function getByID(int $dimensionID): object
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('id')->eq($dimensionID)->fetch();
    }

    /**
     * 获取第一个维度对象。
     * Get the first dimension object.
     *
     * @access public
     * @return object
     */
    public function getFirst(): object
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('deleted')->eq('0')->orderBy('id')->limit(1)->fetch();
    }

    /**
     * 获取维度对象数组。
     * Get dimension object array.
     *
     * @access public
     * @return array
     */
    public function getList(): array
    {
        return $this->dao->select('*')->from(TABLE_DIMENSION)->where('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * 获取当前的维度 ID 并保存到数据库中。
     * Get current dimension ID and save to database.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function getDimension(int $dimensionID = 0): int
    {
        $dimensionID = $this->saveState($dimensionID);
        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

        return $dimensionID;
    }

    /**
     * 把维度 ID 保存到 session 中并返回。
     * Save dimension ID to session and return.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function saveState(int $dimensionID): int
    {
        /* 如果维度 ID 为空，尝试从数据库中获取最后一次记录的维度。*/
        /* If dimension ID is empty, try to get the last dimension from database. */
        if(!$dimensionID && !empty($this->config->dimensions->lastDimension)) $dimensionID = $this->config->dimensions->lastDimension;

        /* 如果维度 ID 为空，尝试从 session 中获取维度。*/
        /* If dimension ID is empty, try to get dimension from session. */
        if(!$dimensionID && $this->session->dimension) $dimensionID = $this->session->dimension;

        /* 如果维度 ID 不为空，检查对应的对象是否存在。*/
        /* If dimension ID is not empty, check if the object exists. */
        if($dimensionID)
        {
            $dimension = $this->getByID($dimensionID);
            if(!$dimension) $dimensionID = 0;
        }

        /* 如果维度 ID 为空，尝试从数据库中获取第一个维度。*/
        /* If dimension ID is empty, try to get the first dimension from database. */
        if(!$dimensionID)
        {
            $dimension = $this->getFirst();
            if($dimension) $dimensionID = $dimension->id;
        }

        /* 把维度 ID 保存到 session 中并返回。*/
        /* Save dimension ID to session and return. */
        $this->session->set('dimension', (int)$dimensionID, $this->app->tab);
        return $this->session->dimension;
    }
}
