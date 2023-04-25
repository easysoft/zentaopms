<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * Get a block by blockID.
     * 根据区块ID获取单个区块信息.
     *
     * @param  int    $blockID
     * @access public
     * @return object|bool
     */
    protected function fetch(int $blockID): object|bool 
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
    protected function fetchMaxOrderByModule(string $module): int
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
     * @param  string $module project|scrum|agileplus|waterfall|waterfallplus|product|execution|qa|todo|doc
     * @param  string $type   scrum|waterfall|kanban
     * @param  int    $hidden 0|1
     * @access public
     * @return array|bool
     */
    protected function fetchList(string $module, string $type = '', int $hidden = 0): array|bool
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

    /**
     * Replace a block data.
     * 更新一条区块数据.
     *
     * @param  object $data
     * @access public
     * @return bool
     */
    protected function replace(object $data): bool
    {
        $this->dao->replace(TABLE_BLOCK)->data($data)->exec();
        return true;
    }
}
