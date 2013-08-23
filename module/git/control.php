<?php
/**
 * The control file of git currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
        $url = helper::safe64Decode($path);
        $this->view->url      = $path;
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
        $url = helper::safe64Decode($url);
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
            $logs = stripslashes($this->post->logs);
            $logs = simplexml_load_string($logs);
            foreach($logs->logentry as $entry)
            {
                $parsedLogs[] = $this->git->convertLog($entry);
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
}
