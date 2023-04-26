<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * Get max order number by block module.
     * 获取对应模块下区块的最大排序号.
     *
     * @param  string $module 
     * @access protected
     * @return int
     */
    protected function fetchMaxOrderByModule(string $module): int
    {
        $order = $this->dao->select('MAX(`order`) as order')->from(TABLE_BLOCK)
            ->where('module')->eq($module)
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch('order');

        return $order;
    }

    /**
     * Get block list of current user.
     * 获取当前用户的区块列表.
     *
     * @param  string $module project|scrum|agileplus|waterfall|waterfallplus|product|execution|qa|todo|doc
     * @param  string $type   scrum|waterfall|kanban
     * @param  int    $hidden 0|1
     * @access protected
     * @return int[]|false
     */
    protected function fetchMyBlocks(string $module, string $type = '', int $hidden = 0): array|false
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
     * Insert a block data.
     *
     * @param  object $formData 
     * @access protected
     * @return bool
     */
    protected function insert($formData): bool
    {
        $this->dao->insert(TABLE_BLOCK)->data($formData)->exec();
        return true;
    }
}
