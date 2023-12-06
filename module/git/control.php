<?php
declare(strict_types=1);
/**
 * The control file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     git
 * @link        https://www.zentao.net
 */
class git extends control
{
    /**
     * 定时任务：同步git。
     * Sync git.
     *
     * @access public
     * @return void
     */
    public function run()
    {
        $this->git->run();
    }

    /**
     * Git对比文件。
     * Diff a file.
     *
     * @param  string $path
     * @param  int    $revision
     * @access public
     * @return void
     */
    public function diff(string $path, string $revision)
    {
        if(isset($_GET['repoUrl'])) $path = $this->get->repoUrl;

        $path        = helper::safe64Decode($path);
        $repos       = $this->loadModel('repo')->getListBySCM('Git,Gitlab,Gogs,Gitea', 'haspriv');
        $currentRepo = null;
        foreach($repos as $repo)
        {
            if(!empty($repo->path) && strpos($path, $repo->path) === 0) $currentRepo = $repo;
        }

        if($currentRepo && common::hasPriv('repo', 'diff'))
        {
            $entry       = $this->repo->encodePath(str_replace($currentRepo->path, '', $path));
            $oldRevision = "$revision^";
            return $this->locate($this->repo->createLink('diff', "repoID=$currentRepo->id&objectID=0&entry=$entry&oldRevision=$oldRevision&revision=$revision", 'html', 'true'));
        }

        if($currentRepo)
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($currentRepo);
        }

        $this->view->path     = $path;
        $this->view->revision = $revision;
        $this->view->diff     = $currentRepo ? $scm->diff($path, $revision) : '';
        $this->display();
    }

    /**
     * Git查看文件。
     * Cat a file.
     *
     * @param  string $path
     * @param  int    $revision
     * @access public
     * @return void
     */
    public function cat(string $path, string $revision)
    {
        if(isset($_GET['repoUrl'])) $path = $this->get->repoUrl;

        $path        = helper::safe64Decode($path);
        $repos       = $this->loadModel('repo')->getListBySCM('Git,Gitlab,Gogs,Gitea', 'haspriv');
        $currentRepo = null;
        foreach($repos as $repo)
        {
            if(!empty($repo->path) && strpos($path, $repo->path) === 0) $currentRepo = $repo;
        }

        if($currentRepo && common::hasPriv('repo', 'view'))
        {
            $entry = $this->repo->encodePath(str_replace($currentRepo->path, '', $path));
            return $this->locate($this->repo->createLink('view', "repoID=$currentRepo->id&objectID=0&entry=$entry&revision=$revision", 'html', true));
        }

        if($currentRepo)
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($currentRepo);
        }
        $this->view->path     = $path;
        $this->view->revision = $revision;
        $this->view->code     = $currentRepo ? $scm->cat($path, $revision) : '';

       $this->display();
    }

    /**
     * 通过api同步提交信息。
     * Sync from the syncer by api.
     *
     * @access public
     * @return void
     */
    public function apiSync()
    {
        if($this->post->logs) return;

        $repoRoot = $this->post->repoRoot;
        $list     = json_decode($this->post->logs);

        $logs = array();
        $i    = 0;
        foreach($list as $line)
        {
            if(!$line)
            {
                $i++;
                continue;
            }
            $logs[$i][] = $line;
        }
        foreach($logs as $log)
        {
            $parsedLogs[] = $this->git->convertLog($log);
        }

        $this->loadModel('repo');
        $parsedObjects = array('stories' => array(), 'tasks' => array(), 'bugs' => array());
        foreach($parsedLogs as $log)
        {
            $objects = $this->repo->parseComment($log->msg);

            if($objects)
            {
                $this->git->saveAction2PMS($objects, $log, $repoRoot);
                if($objects['stories']) $parsedObjects['stories'] = array_merge($parsedObjects['stories'], $objects['stories']);
                if($objects['tasks'])   $parsedObjects['tasks'  ] = array_merge($parsedObjects['tasks'],   $objects['tasks']);
                if($objects['bugs'])    $parsedObjects['bugs']    = array_merge($parsedObjects['bugs'],    $objects['bugs']);
            }
        }
        $parsedObjects['stories'] = array_unique($parsedObjects['stories']);
        $parsedObjects['tasks']   = array_unique($parsedObjects['tasks']);
        $parsedObjects['bugs']    = array_unique($parsedObjects['bugs']);
        $this->view->parsedObjects = $parsedObjects;
        return $this->display();
    }

    /**
     * 保存git提交日志。
     * Ajax save log.
     *
     * @access public
     * @return void
     */
    public function ajaxSaveLog()
    {
        $repoUrl  = trim($this->post->repoUrl);
        $message  = trim($this->post->message);
        $revision = trim($this->post->revision);
        $files    = $this->post->files;
        if(empty($repoUrl)) return;

        $repoUrl     = rtrim($repoUrl, '/') . '/';
        $parsedFiles = array();
        $repoDirs    = explode('/', trim($repoUrl, '/'));
        foreach($files as $file)
        {
            $file = trim($file);
            if(empty($file)) continue;

            $action = '';
            if(preg_match('/^[\w][ \t]/', $file))
            {
                $action = $file[0];
                $file   = trim(substr($file, 2));
            }
            $fileDirs = explode('/', trim($file, '/'));
            $diffDirs = array_diff($fileDirs, $repoDirs);

            foreach($fileDirs as $i => $dir)
            {
                if(isset($diffDirs[$i])) break;
                if(!isset($diffDirs[$i])) unset($fileDirs[$i]);
            }
            $path = '/' . join('/', $fileDirs);
            $parsedFiles[$action][] = ltrim($path, '/');
        }

        $objects = $this->loadModel('repo')->parseComment($message);
        if($objects)
        {
            $log = new stdclass();
            $log->author   = $this->app->user->account;
            $log->date     = helper::now();
            $log->msg      = $message;
            $log->revision = $revision;
            $log->files    = $parsedFiles;
            $this->git->saveAction2PMS($objects, $log, $repoUrl);
        }
    }

    /**
     * 获取代码库列表。
     * Ajax get repos.
     *
     * @access public
     * @return void
     */
    public function ajaxGetRepos()
    {
        $repos = $this->git->getRepos();
        echo json_encode($repos);
    }
}
