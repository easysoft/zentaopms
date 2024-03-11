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
    protected function getLastRevision(int $repoID)
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
     * Copy svn dir.
     *
     * @param  int       $repoID
     * @param  string    $copyfromPath
     * @param  string    $copyfromRev
     * @param  string    $dirPath
     * @access protected
     * @return viod
     */
    protected function copySvnDir(int $repoID, string $copyfromPath, string $copyfromRev, string $dirPath)
    {
        $copyFiles = $this->dao->select('t1.*')->from(TABLE_REPOFILES)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision = t2.id')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t2.revision+0')->le($copyfromRev)
            ->andWhere('t1.path')->like("{$copyfromPath}%")
            ->fetchAll();
        foreach($copyFiles as $copyFile)
        {
            unset($copyFile->id);
            $copyFile->path   = substr_replace($copyFile->path, $dirPath, 0, strlen($copyfromPath));
            $copyFile->parent = substr_replace($copyFile->parent, $dirPath, 0, strlen($copyfromPath));

            if($copyFile->path == $dirPath) continue;
            $this->dao->insert(TABLE_REPOFILES)->data($copyFile)->exec();
        }
    }
}

