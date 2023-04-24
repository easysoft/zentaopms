<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * Get a block by blockID. 
     * 根据区块ID获取区块信息.
     * 
     * @param  int    $blockID 
     * @access public
     * @return object | bool
     */
    public function getByID(int $blockID) : object | bool 
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)->where('id')->eq($blockID)->fetch();
    }

    /**
     * Get max order number by block module. 
     * 获取对应模块下区块的最大排序号.
     * 
     * @param  string $module 
     * @access public
     * @return int
     */
    public function getMaxOrderByModule(string $module) : int
    {
        return $this->dao->select('IF(MAX(`order`), MAX(`order`), 0) as order')->from(TABLE_BLOCK)
            ->where('module')->eq($module)
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch('order');
    }

    /**
     * Get block list.
     * 获取区块列表.
     * 
     * @param  string $module 
     * @param  string $type 
     * @param  int    $hidden 
     * @access public
     * @return array | bool
     */
    public function getList(string $module, string $type = '', int $hidden = 0) : array | bool
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('module')->eq($module)
            ->andWhere('hidden')->eq($hidden)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('`order`')
            ->fetchAll('id');
    }
}
