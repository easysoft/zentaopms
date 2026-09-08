<?php
helper::importControl('repobranchtype');

class myRepobranchtype extends repobranchtype
{
    /**
     * GitFox 下分支类型同时存在于代码库设置和后台 DevOps配置中。
     * 根据 repoID 判断当前归属：带 repoID 属于代码库，不带 repoID 属于后台。
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, string $orderBy = 'id_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if($this->config->vision == 'gitfox') $this->app->tab = $repoID ? 'repo' : 'admin';

        parent::browse($repoID, $orderBy, $recTotal, $recPerPage, $pageID);
    }
}
