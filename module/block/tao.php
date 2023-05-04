<?php
declare(strict_types=1);
class blockTao extends blockModel
{
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
}
