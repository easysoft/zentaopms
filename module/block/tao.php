<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * Get max order number by block dashboard.
     * 获取对应仪表盘下区块的最大排序号.
     *
     * @param  string $module
     * @access protected
     * @return int
     */
    protected function fetchMaxOrderByDashboard(string $dashboard): int
    {
        $order = $this->dao->select('MAX(`order`) as `order`')->from(TABLE_BLOCK)
            ->where('dashboard')->eq($dashboard)
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch('order');

        return (int)$order;
    }

    /**
     * Get block list of current user.
     * 获取当前用户的区块列表.
     *
     * @param  string $module
     * @param  int    $hidden 0|1
     * @access protected
     * @return int[]|false
     */
    protected function fetchMyBlocks(string $dashboard, int $hidden = 0): array|false
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('dashboard')->eq($dashboard)
            ->andWhere('hidden')->eq($hidden)
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
        $this->dao->insert(TABLE_BLOCK)->data($formData)->autoCheck()->exec();
        return true;
    }
}
