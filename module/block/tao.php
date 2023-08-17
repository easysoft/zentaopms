<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * 获取当前用户的区块列表。
     * Get block list of current user.
     *
     * @param  string      $dashboard
     * @param  int         $hidden 0|1
     * @access protected
     * @return array|false
     */
    protected function fetchMyBlocks(string $dashboard, int $hidden = 0): array|false
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('dashboard')->eq($dashboard)
            ->andWhere('hidden')->eq($hidden)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('width_desc,top_asc,id_asc')
            ->fetchAll();
    }
}
