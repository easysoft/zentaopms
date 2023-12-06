<?php
declare(strict_types=1);
/**
 * The control file of svn module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     svn
 * @link        https://www.zentao.net
 */
class svn extends control
{
    /**
     * 定时任务，同步SVN.
     * Sync svn.
     *
     * @access public
     * @return void
     */
    public function run()
    {
        set_time_limit(0);
        $this->svn->run();
    }

    /**
     * 对比文件.
     * Diff a file.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return void
     */
    public function diff(string $url, int $revision)
    {
        if(isset($_GET['repoUrl'])) $url = $this->get->repoUrl;

        $url = helper::safe64Decode($url);
        if(common::hasPriv('repo', 'diff'))
        {
            $svnRepos = $this->loadModel('repo')->getListBySCM('Subversion', 'haspriv');
            foreach($svnRepos as $repo)
            {
                if(strpos(strtolower($url), strtolower($repo->path)) === 0)
                {
                    $entry       = $this->repo->encodePath(str_ireplace($repo->path, '', $url));
                    $oldRevision = $revision - 1;
                    $this->locate($this->repo->createLink('diff', "repoID=$repo->id&objectID=0&entry=$entry&oldRevision=$oldRevision&revision=$revision", 'html', true));
                }
            }
        }

        $this->view->url      = $url;
        $this->view->revision = $revision;
        $this->view->diff     = $this->svn->diff($url, $revision);

        $this->display();
    }

    /**
     * 查看文件.
     * Cat a file.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return void
     */
    public function cat(string $url, int $revision)
    {
        if(isset($_GET['repoUrl'])) $url = $this->get->repoUrl;

        $url = helper::safe64Decode($url);
        if(common::hasPriv('repo', 'view'))
        {
            $repos = $this->loadModel('repo')->getListBySCM('Subversion', 'haspriv');
            foreach($repos as $repo)
            {
                if(strpos(strtolower($url), strtolower($repo->path)) === 0)
                {
                    $entry = $this->repo->encodePath(str_ireplace(strtolower($repo->path), '', $url));
                    $this->locate($this->repo->createLink('view', "repoID=$repo->id&objectID=0&entry=$entry&revision=$revision", 'html', true));
                }
            }
        }

        $this->view->url      = $url;
        $this->view->revision = $revision;
        $this->view->code     = $this->svn->cat($url, $revision);

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
        if(!$this->post->logs) return;

        $repoRoot = $this->post->repoRoot;
        $logs = stripslashes($this->post->logs);
        $logs = simplexml_load_string($logs);
        foreach($logs->logentry as $entry)
        {
            $parsedLogs[] = $this->svn->convertLog($entry);
        }

        $this->loadModel('repo');
        $parsedObjects = array('stories' => array(), 'tasks' => array(), 'bugs' => array());
        foreach($parsedLogs as $log)
        {
            $objects = $this->repo->parseComment($log->msg);

            if($objects)
            {
                $this->repo->saveAction2PMS($objects, $log, $repoRoot);
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
     * 保存提交日志。
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

        /* Ignore git. */
        if(strpos($repoUrl, '://') === false) return;

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
            $parsedFiles[$action][] = $path;
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
            $this->repo->saveAction2PMS($objects, $log, $repoUrl);
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
        $repos = $this->svn->getRepos();
        echo json_encode($repos);
    }
}
