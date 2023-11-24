<?php
declare(strict_types=1);
/**
 * The tao file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

class repoTao extends repoModel
{
    /**
     * 获取最后一次提交信息。
     * Get last revision.
     *
     * @param  int       $repoID
     * @access protected
     * @return string|false
     */
    protected function getLastRevision(int $repoID): string|false
    {
        return $this->dao->select('time')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('time_desc')->fetch('time');
    }

    /**
     * 根据id删除版本库信息。
     * Delete repo info by id.
     *
     * @param  int $repoID
     * @access protected
     * @return void
     */
    protected function deleteInfoByID(int $repoID): void
    {
        $this->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->exec();
        $this->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->exec();
        $this->dao->delete()->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->exec();
    }

    /**
     * 处理版本库搜索查询。
     * Process repo search query.
     *
     * @param  int       $queryID
     * @access protected
     * @return string
     */
    protected function processSearchQuery(int $queryID): string
    {
            $queryName = 'repoQuery';

            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);

                if($query)
                {
                    $this->session->set($queryName, $query->sql);
                    $this->session->set('repoForm', $query->form);
                }
            }
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');

            return  $this->session->$queryName;
    }

    /**
     * Check repo name.
     *
     * @param  object $repo
     * @access protected
     * @return bool
     */
    protected function checkName(object $repo)
    {
        $pattern = "/^[a-zA-Z0-9_\-\.]+$/";
        return preg_match($pattern, $repo->name);
    }

    /**
     * 根据条件获取版本库列表。
     * Get repo list by condition.
     *
     * @param  string    $repoQuery
     * @param  string    $SCM
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getListByCondition(string $repoQuery, string $SCM, string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($repoQuery))->andWhere($repoQuery)->fi()
            ->beginIF($SCM)->andWhere('SCM')->in($SCM)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取代码库分支的最后提交时间。
     * Get the last commit time of repo branch.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $branch
     * @access protected
     * @return string
     */
    protected function getLatestCommitTime(int $repoID, string $revision, string $branch): string
    {
        return $this->dao->select('time')->from(TABLE_REPOHISTORY)->alias('t1')
            ->beginIF($branch)->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')->fi()
            ->where('t1.repo')->eq($repoID)
            ->beginIF($revision != 'HEAD')->andWhere('t1.revision')->eq($revision)->fi()
            ->beginIF($branch)->andWhere('t2.branch')->eq($branch)->fi()
            ->orderBy('time desc')
            ->fetch('time');
    }
}
