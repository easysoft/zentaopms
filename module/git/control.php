<?php
/**
 * The control file of git currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     git
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class git extends control
{
    /**
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
     * Diff a file.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return void
     */
    public function diff($path, $revision)
    {
        $path = helper::safe64Decode($path);
        $this->view->path     = $path;
        $this->view->revision = $revision;
        $this->view->diff     = $this->git->diff($path, $revision);
        
        $this->display();
    }

    /**
     * Cat a file.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return void
     */
    public function cat($path, $revision)
    {
        $path = helper::safe64Decode($path);
        $this->view->path     = $path;
        $this->view->revision = $revision;
        $this->view->code     = $this->git->cat($path, $revision);
        
       $this->display(); 
    }

    /**
     * Sync from the syncer by api.
     * 
     * @access public
     * @return void
     */
    public function apiSync()
    {
        if($this->post->logs)
        {
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
                $parsedLogs[] = $this->convertLog($log);
            }

            $parsedObjects = array('stories' => array(), 'tasks' => array(), 'bugs' => array());
            foreach($parsedLogs as $log)
            {
                $objects = $this->git->parseComment($log->msg);
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
            $this->display();
            exit;
        }
    }

    /**
     * Ajax save log.
     * 
     * @access public
     * @return void
     */
    public function ajaxSaveLog()
    {
        $repoUrl  = trim($this->post->repoUrl);
        $repoRoot = str_replace('\\', '/', trim($this->post->repoRoot));
        $message  = trim($this->post->message);
        $revision = trim($this->post->revision);
        $files    = $this->post->files;
        if(empty($repoUrl)) die();
        $repoUrl = rtrim($repoUrl, '/') . '/';

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

        $objects = $this->git->parseComment($message);
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
        die();
    }

    /**
     * Ajax get repos.
     * 
     * @access public
     * @return void
     */
    public function ajaxGetRepos()
    {
        $repos = $this->git->getRepos();
        die(json_encode($repos));
    }
}
