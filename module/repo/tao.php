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

    /**
     * 解析提交信息中的任务信息。
     * Parse task info from commit message.
     *
     * @param  string    $comment
     * @param  array     $rules
     * @param  array     $actions
     * @access protected
     * @return array
     */
    protected function parseTaskComment(string $comment, array $rules, array &$actions): array
    {
        $tasks = array();
        preg_match_all("/{$rules['startTaskReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[4] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id)
                {
                    $tasks[$id] = $id;
                    $actions['task'][$id]['start']['consumed'] = $matches[11][$i];
                    $actions['task'][$id]['start']['left']     = $matches[17][$i];
                }
            }
        }

        preg_match_all("/{$rules['effortTaskReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[4] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id)
                {
                    $tasks[$id] = $id;
                    $actions['task'][$id]['effort']['consumed'] = $matches[11][$i];
                    $actions['task'][$id]['effort']['left']     = $matches[17][$i];
                }
            }
        }

        preg_match_all("/{$rules['finishTaskReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[4] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id)
                {
                    $tasks[$id] = $id;
                    $actions['task'][$id]['finish']['consumed'] = $matches[11][$i];
                }
            }
        }

        return $tasks;
    }

    /**
     * 解析提交信息中的Bug信息。
     * Parse bug info from commit message.
     *
     * @param  string    $comment
     * @param  array     $rules
     * @param  array     $actions
     * @access protected
     * @return array
     */
    protected function parseBugComment(string $comment, array $rules, array &$actions): array
    {
        $bugs = array();
        preg_match_all("/{$rules['resolveBugReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[4] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id)
                {
                    $bugs[$id] = $id;
                    $actions['bug'][$id]['resolve'] = array();
                }
            }
        }

        return $bugs;
    }

    /**
     * 构造文件树结构。
     * Build file tree.
     *
     * @param  array  $allFiles
     * @access public
     * @return array
     */
    public function buildFileTree(array $allFiles = array()): array
    {
        $files = array();
        $id    = 0;
        foreach($allFiles as $file)
        {
            $fileName = explode('/', $file);
            $parent   = '';
            foreach($fileName as $path)
            {
                if($path === '') continue;

                $parentID = $parent == '' ? '0' : $files[$parent]['id'];
                $parent  .= $parent == '' ? $path : '/' . $path;
                if(!isset($files[$parent]))
                {
                    $id++;

                    $id = $this->encodePath($parent);
                    $files[$parent] = array(
                        'id'     => str_replace('=', '-', $id),
                        'parent' => $parentID,
                        'name'   => $path,
                        'path'   => $parent,
                        'key'    => $id,
                    );
                }
            }
        }

        sort($files);
        return $this->buildTree($files);
    }

    /**
     * Build tree.
     *
     * @param  array  $files
     * @param  string $parent
     * @access public
     * @return array
     */
    public function buildTree(array $files = array(), string $parent = '0'): array
    {
        $treeList = array();
        $key      = 0;
        $pathName = array();
        $fileName = array();

        foreach($files as $key => $file)
        {
            if ($file['parent'] === $parent)
            {
                $treeList[$key] = $file;
                $fileName[$key] = $file['name'];
                /* Default value is '~', because his ascii code is large in string. */
                $pathName[$key] = '~';

                $children = $this->buildTree($files, $file['id']);

                if($children)
                {
                    $treeList[$key]['children'] = $children;
                    $fileName[$key] = '';
                    $pathName[$key] = $file['path'];
                }

                $key++;
            }
        }

        array_multisort($pathName, SORT_ASC, $fileName, SORT_ASC, $treeList);
        return $treeList;
    }

    /**
     * 根据url获取匹配得版本库。
     * Get matched repos by url.
     *
     * @param  string    $url
     * @access protected
     * @return array
     */
    protected function getMatchedReposByUrl(string $url): array
    {
        /* Convert to id by url. */
        $this->loadModel('gitlab');
        $matches   = array();
        $parsedUrl = parse_url($url);
        $isSSH     = $parsedUrl['scheme'] == 'ssh';
        $baseURL   = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['port']) ? ":{$parsedUrl['port']}" : '');
        $url       = str_replace('https://', 'http://', strtolower($url));
        $gitlabs   = $this->loadModel('pipeline')->getList('gitlab');
        foreach($gitlabs as $gitlabID => $gitlab)
        {
            if((!$isSSH && $gitlab->url != $baseURL) || ($isSSH && strpos($gitlab->url, $parsedUrl['host']) === false))
            {
                unset($gitlabs[$gitlabID]);
                continue;
            }

            $projects = $this->gitlab->apiGetProjects($gitlabID);
            foreach($projects as $project)
            {
                $urlToRepo = str_replace('https://', 'http://', strtolower($project->http_url_to_repo));
                if((!$isSSH && $urlToRepo == $url) || ($isSSH && strtolower($project->ssh_url_to_repo) == $url)) $matches[] = array('gitlab' => $gitlabID, 'project' => $project->id);
            }
        }

        return $matches;
    }
}
