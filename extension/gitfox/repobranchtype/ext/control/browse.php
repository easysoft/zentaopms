<?php
helper::importControl('repobranchtype');

class myRepobranchtype extends repobranchtype
{
    /**
     * 浏览分支类型。
     * Browse branch type.
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
