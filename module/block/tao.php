<?php
declare(strict_types=1);
class blockTao extends blockModel
{
    /**
     * Get a block by blockID. 
     * 根据区块ID获取区块信息.
     * 
     * @param  int    $blockID 
     * @param  bool   $toMe 
     * @access public
     * @return void
     */
    public function getByID(int $blockID, bool $toMe = false) : object | bool 
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('id')->eq($blockID)
            ->beginIF($toMe)->andWhere('account')->eq($this->app->user->account)->fi()
            ->fetch();
    }
}
