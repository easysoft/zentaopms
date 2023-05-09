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
            ->orderBy('`order`')
            ->fetchAll('id');
    }

    /**
     * 新增一个区块。
     * Insert a block data.
     *
     * @param  object    $formData
     * @access protected
     * @return bool
     */
    protected function insert(object $formData): bool
    {
        $this->dao->insert(TABLE_BLOCK)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->block->create->requiredFields, 'notempty')
            ->exec();

        return !dao::isError();
    }
}
