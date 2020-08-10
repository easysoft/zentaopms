<?php
/**
 * The control file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Yidong Wang, Jinyong Zhu
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class repo extends control
{
    /**
     * Construct.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $disFuncs = str_replace(' ', '', ini_get('disable_functions'));
        if(stripos(",$disFuncs,", ',exec,') !== false or stripos(",$disFuncs,", ',shell_exec,') !== false)
        {
            echo js::alert($this->lang->repo->error->useless);
            die(js::locate('back'));
        }

        $this->scm = $this->app->loadClass('scm');
        $this->repos = $this->repo->getRepoPairs();
        if(empty($this->repos) and $this->methodName != 'create') die(js::locate($this->repo->createLink('create')));

        /* Unlock session for wait to get data of repo. */
        session_write_close();
    }

    /**
     * List all repo.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function maintain($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $repoID = $this->session->repoID;
        $this->repo->setMenu($this->repos, $repoID, false);
        if(common::hasPriv('repo', 'create')) $this->lang->modulePageActions = html::a(helper::createLink('repo', 'create'), "<i class='icon icon-plus'></i> " . $this->lang->repo->create, '', "class='btn btn-primary'");

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->repoList   = $this->repo->getList($orderBy, $pager);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->browse;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->browse;

        $this->view->repoID    = $repoID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a repo.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $repoID = $this->repo->create();

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->repo->setMenu($this->repos, '', false);
        $this->app->loadLang('action');

        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->create;
        $this->view->position[] = html::a(inlink('maintain'), $this->lang->repo->common);
        $this->view->position[] = $this->lang->repo->create;

        $this->display();
    }

    /**
     * Edit a repo.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function edit($repoID)
    {
        $repo = $this->repo->getRepoByID($repoID);
        if($_POST)
        {
            $noNeedSync = $this->repo->update($repoID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!$noNeedSync)
            {
                $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID");
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('maintain')));
        }

        $this->repo->setMenu($this->repos, $repo->id, false);
        $this->app->loadLang('action');

        $repo->repoType     = $repo->id . '-' . $repo->SCM;
        $this->view->repo   = $repo;
        $this->view->repoID = $repoID;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->edit;
        $this->view->position[] = html::a(inlink('maintain'), $this->lang->repo->common);
        $this->view->position[] = $this->lang->repo->edit;

        $this->display();
    }

    /**
     * Delete repo. 
     * 
     * @param  int    $repoID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($repoID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->repo->notice->delete, $this->repo->createLink('delete', "repoID=$repoID&confirm=yes")));
        }

        $this->dao->delete()->from(TABLE_REPO)->where('id')->eq($repoID)->exec();
        $this->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->exec();
        $this->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->exec();
        $this->dao->delete()->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->exec();

        if(dao::isError()) die(js::error(dao::getError()));
        die(js::reload('parent'));
    }

    /**
     * View repo file. 
     * 
     * @param  int    $repoID 
     * @param  string $entry 
     * @param  string $revision 
     * @param  string $showBug 
     * @param  string $encoding 
     * @access public
     * @return void
     */
    public function view($repoID, $entry, $revision = 'HEAD', $showBug = 'false', $encoding = '')
    {
        if($this->get->entry) $entry = $this->get->entry;
        $this->repo->setMenu($this->repos, $repoID);
        $this->repo->setBackSession('view', $withOtherModule = true);
        if($repoID == 0) $repoID = $this->session->repoID;

        $file  = $entry;
        $repo  = $this->repo->getRepoByID($repoID);
        $entry = $this->repo->decodePath($entry);

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $revision);
        if($info->kind == 'dir') $this->locate($this->repo->createLink('browse', "repoID=$repoID&path=&revision=$revision", "path=" . $this->repo->encodePath($info->path)));
        $content  = $this->scm->cat($entry, $revision);
        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry); 
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));

        $suffix   = ''; 
        if(isset($pathInfo["extension"])) $suffix = strtolower($pathInfo["extension"]); 
        if(!$suffix or (!array_key_exists($suffix, $this->config->program->suffix) and strpos($this->config->repo->images, "|$suffix|") === false)) $suffix = $this->repo->isBinary($content, $suffix) ? 'binary' : 'c';

        if(strpos($this->config->repo->images, "|$suffix|") !== false)
        {
            $content = base64_encode($content);
        }
        elseif($encoding != 'utf-8')
        {
            $content = helper::convertEncoding($content, $encoding);
        }

        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 8, 1);

        $commiters = $this->loadModel('user')->getCommiters();
        $logType   = 'file';
        $revisions = $this->repo->getCommits($repo, '/' . $entry, 'HEAD', $logType, $pager);

        $i = 0;
        foreach($revisions as $log)
        {
            if($revision == 'HEAD' and $i == 0) $revision = $log->revision;
            if($revision == $log->revision) $revisionName = $repo->SCM == 'Git' ?  $this->repo->getGitRevisionName($log->revision, $log->commit) : $log->revision;
            $log->committer = zget($commiters, $log->committer, $log->committer);
            $i++;
        }
        if(!isset($revisionName))
        {
            if($repo->SCM == 'Git') $gitCommit = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->eq($revision)->andWhere('repo')->eq($repo->id)->fetch('commit');
            $revisionName = ($repo->SCM == 'Git' and isset($gitCommit)) ? $this->repo->getGitRevisionName($revision, $gitCommit) : $revision;
        }

        $this->view->revisions    = $revisions;
        $this->view->title        = $this->lang->repo->common;
        $this->view->type         = 'view';
        $this->view->showBug      = $showBug;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->repoID       = $repoID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->revisionName = $revisionName;
        $this->view->preAndNext   = $this->repo->getPreAndNext($repo, '/' . $entry, $revision);
        $this->view->file         = $file;
        $this->view->entry        = $entry;
        $this->view->path         = $entry;
        $this->view->suffix       = $suffix;
        $this->view->content      = $content;
        $this->view->pager        = $pager;
        $this->view->logType      = $logType;
        $this->view->info         = $info;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->view;
        $this->display();
    }

    /**
     * Browse repo. 
     * 
     * @param  int    $repoID 
     * @param  string $path 
     * @param  string $revision 
     * @param  int    $refresh 
     * @access public
     * @return void
     */
    public function browse($repoID = 0, $path = '', $revision = 'HEAD', $refresh = 0)
    {
        if($this->get->path) $path = $this->get->path;
        if(empty($refresh) and $this->cookie->repoRefresh) $refresh = $this->cookie->repoRefresh;
        $this->repo->setMenu($this->repos, $repoID);
        $this->repo->setBackSession('list', $withOtherModule = true);
        if($repoID == 0) $repoID = $this->session->repoID;

        $repo = $this->repo->getRepoByID($repoID);
        if(!$repo->synced) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID"));

        $path      = $this->repo->decodePath($path);
        $cacheFile = $this->repo->getCacheFile($repoID, $path, $revision);
        $this->scm->setEngine($repo);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 8, 1);

        /* Cache infos. */
        if($refresh or !$cacheFile or !file_exists($cacheFile) or (time() - filemtime($cacheFile)) / 60 > $this->config->repo->cacheTime)
        {
            $infos = $this->scm->ls($path, $revision);

            if($infos and $repo->SCM == 'Subversion')
            {
                $revisionList = array();
                foreach($infos as $info) $revisionList[$info->revision] = $info->revision;
                $comments = $this->repo->getHistory($repoID, $revisionList);
                foreach($infos as $info)
                {
                    if(isset($comments[$info->revision]))
                    {
                        $comment = $comments[$info->revision];
                        $info->comment = $comment->comment;
                    }
                }
            }

            if($cacheFile)
            {
                if(!file_exists($cacheFile . '.lock'))
                {
                    touch($cacheFile .  '.lock');
                    file_put_contents($cacheFile, serialize($infos));
                    unlink($cacheFile . '.lock');
                }
            }
        }
        else
        {
            $infos = unserialize(file_get_contents($cacheFile));
        }
        if($this->cookie->repoRefresh) setcookie('repoRefresh', 0, 0, $this->config->webRoot);

        $logType   = 'dir';
        $revisions = $this->repo->getCommits($repo, $path, $revision, $logType, $pager);
        if($repo->SCM == 'Git' and $infos and empty($revisions)) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&branch={$this->cookie->repoBranch}"));
        $commiters = $this->loadModel('user')->getCommiters();
        foreach($infos as $info) $info->committer = zget($commiters, $info->account, $info->account);
        foreach($revisions as $log) $log->committer = zget($commiters, $log->committer, $log->committer);

        $this->view->title     = $this->lang->repo->common;
        $this->view->repo      = $repo;
        $this->view->revisions = $revisions;
        $this->view->revision  = $revision;
        $this->view->infos     = $infos;
        $this->view->repoID    = $repoID;
        $this->view->pager     = $pager;
        $this->view->path      = urldecode($path);
        $this->view->logType   = $logType;
        $this->view->cacheTime = date('m-d H:i', filemtime($cacheFile));

        $this->display();
    }

    /**
     * show repo log.
     * 
     * @param  int    $repoID 
     * @param  string $entry 
     * @param  string $revision 
     * @param  string $type 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function log($repoID = 0, $entry = '', $revision = 'HEAD', $type = 'dir', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        if($this->get->entry) $entry = $this->get->entry;
        $this->repo->setMenu($this->repos, $repoID);
        $this->repo->setBackSession('list', $withOtherModule = true);
        if($repoID == 0) $repoID = $this->session->repoID;

        $repo  = $this->repo->getRepoByID($repoID);
        $file  = $entry;
        $entry = $this->repo->decodePath($entry);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $revision);

        $logs      = $this->repo->getCommits($repo, $entry, $revision, $type, $pager);
        $commiters = $this->loadModel('user')->getCommiters();
        foreach($logs as $log) $log->committer = zget($commiters, $log->committer, $log->committer);

        $this->view->repo       = $repo;
        $this->view->title      = $this->lang->repo->common;
        $this->view->logs       = $logs;
        $this->view->revision   = $revision;
        $this->view->repoID     = $repoID;
        $this->view->entry      = urldecode($entry);
        $this->view->path       = urldecode($entry);
        $this->view->file       = urldecode($file);
        $this->view->pager      = $pager;
        $this->view->info       = $info;
        $this->display();
    }

    /**
     * Show repo revision.
     *
     * @param int    $repoID
     * @param int    $revision
     * @param string $path
     * @param string $type
     *
     * @access public
     * @return void
     */
    public function revision($repoID, $revision, $root = '', $type = 'dir')
    {
        $this->repo->setMenu($this->repos, $repoID);
        $this->repo->setBackSession();
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo  = $this->repo->getRepoByID($repoID);

        $this->scm->setEngine($repo);
        $log = $this->scm->log('', $revision, $revision);

        $history = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->eq($log[0]->revision)->andWhere('repo')->eq($repoID)->fetch();
        if($history)
        {
            if($repo->SCM == 'Git')
            {
                $thisAndPrevRevisions = $this->scm->exec("rev-list -n 2 {$history->revision} --");

                array_shift($thisAndPrevRevisions);
                if($thisAndPrevRevisions) $oldRevision = array_shift($thisAndPrevRevisions);
            }
            else
            {
                $oldRevision = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->lt($history->revision)->andWhere('repo')->eq($repoID)->orderBy('revision_desc')->limit(1)->fetch('revision');
            }

            $log[0]->commit = $history->commit;
        }

        if(empty($oldRevision))
        {
            $oldRevision = '^';
            if($history and $repo->SCM == 'Git') $oldRevision = "{$history->revision}^";
        }

        $changes  = array();
        $viewPriv = common::hasPriv('repo', 'view');
        $diffPriv = common::hasPriv('repo', 'diff');
        foreach($log[0]->change as $path => $change)
        {
            if($repo->prefix) $path = str_replace($repo->prefix, '', $path);
            $encodePath = $this->repo->encodePath($path);
            if($change['kind'] == '' or $change['kind'] == 'file')
            {
                $change['view'] = $viewPriv ? html::a($this->repo->createLink('view', "repoID=$repoID&entry=&revision=$revision", "entry=$encodePath"), $this->lang->repo->viewA) : '';
                if($change['action'] == 'M') $change['diff'] = $diffPriv ? html::a($this->repo->createLink('diff', "repoID=$repoID&entry=&oldRevision=$oldRevision&newRevision=$revision", "entry=$encodePath"), $this->lang->repo->diffAB) : '';
            }
            else
            {
                $change['view'] = $viewPriv ? html::a($this->repo->createLink('browse', "repoID=$repoID&path=&revision=$revision", "path=$encodePath"), $this->lang->repo->browse) : '';
                if($change['action'] == 'M') $change['diff'] = $diffPriv ? html::a($this->repo->createLink('diff', "repoID=$repoID&entry=&oldRevision=$oldRevision&newRevision=$revision", "entry=$encodePath"), $this->lang->repo->diffAB) : '';
            }
            $changes[$path] = $change;
        }

        $root   = $this->repo->decodePath($root);
        $parent = '';
        if($type == 'file')
        {
            $parent = $this->dao->select('parent')->from(TABLE_REPOFILES)
                ->where('revision')->eq($history->id)
                ->andWhere('path')->eq('/' . $root)
                ->fetch('parent');
        }

        $this->view->title       = $this->lang->repo->common;
        $this->view->log         = $log[0];
        $this->view->repo        = $repo;
        $this->view->path        = $root;
        $this->view->type        = $type;
        $this->view->changes     = $changes;
        $this->view->repoID      = $repoID;
        $this->view->revision    = $log[0]->revision;
        $this->view->parentDir   = $parent;
        $this->view->oldRevision = $oldRevision;
        $this->view->preAndNext  = $this->repo->getPreAndNext($repo, $root, $revision, $type, 'revision');

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->viewRevision;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->viewRevision;

        $this->display();
    }

    /**
     * Blame repo file. 
     * 
     * @param  int    $repoID 
     * @param  string $entry 
     * @param  string $revision 
     * @param  string $encoding 
     * @access public
     * @return void
     */
    public function blame($repoID, $entry, $revision = 'HEAD', $encoding = '')
    {
        if($this->get->entry) $entry = $this->get->entry;
        $this->repo->setMenu($this->repos, $repoID);
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo  = $this->repo->getRepoByID($repoID);
        $file  = $entry;
        $entry = $this->repo->decodePath($entry);

        $this->scm->setEngine($repo);
        $encoding  = empty($encoding) ? $repo->encoding : $encoding;
        $encoding  = strtolower(str_replace('_', '-', $encoding));
        $blames    = $this->scm->blame($entry, $revision);
        $revisions = array();
        foreach($blames as $i => $blame)
        {
            if(isset($blame['revision'])) $revisions[$blame['revision']] = $blame['revision'];
            if($encoding != 'utf-8') $blames[$i]['content'] = helper::convertEncoding($blame['content'], $encoding);
        }

        $log = $repo->SCM == 'Git' ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->eq($revision)->andWhere('repo')->eq($repo->id)->fetch() : '';

        $this->view->title        = $this->lang->repo->common;
        $this->view->repoID       = $repoID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->entry        = $entry;
        $this->view->file         = $file;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->historys     = $repo->SCM == 'Git' ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->andWhere('repo')->eq($repo->id)->fetchPairs() : '';
        $this->view->revisionName = ($log and $repo->SCM == 'Git') ? $this->repo->getGitRevisionName($log->revision, $log->commit) : $revision;
        $this->view->blames       = $blames;
        $this->display();
    }

    /**
     * Show diff.
     * 
     * @param  int    $repoID 
     * @param  string $entry 
     * @param  string $oldRevision 
     * @param  string $newRevision 
     * @param  string $showBug 
     * @param  string $encoding 
     * @access public
     * @return void
     */
    public function diff($repoID, $entry = '', $oldRevision = '0', $newRevision = 'HEAD', $showBug = 'false', $encoding = '')
    {
        if($this->get->entry) $entry = $this->get->entry;
        $this->repo->setMenu($this->repos, $repoID);
        if($repoID == 0) $repoID = $this->session->repoID;
        $file    = $entry;
        $repo    = $this->repo->getRepoByID($repoID);
        $entry   = $this->repo->decodePath($entry);

        $pathInfo = pathinfo($entry); 
        $suffix   = ''; 
        if(isset($pathInfo["extension"])) $suffix = strtolower($pathInfo["extension"]); 

        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';
        if($this->server->request_method == 'POST')
        {
            $oldRevision = isset($this->post->revision[1]) ?$this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ?$this->post->revision[0] : '';
            if($this->post->arrange) 
            {
                $arrange = $this->post->arrange;
                setcookie('arrange', $arrange);
            }
            if($this->post->encoding) $encoding = $this->post->encoding;
            if(!$oldRevision)
            {
                echo js::alert($this->lang->repo->error->diff);
                die(js::locate('back'));
            }
        }

        $this->scm->setEngine($repo);
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));
        $info     = $this->scm->info($entry, $newRevision);
        $diffs    = $this->scm->diff($entry, $oldRevision, $newRevision);
        foreach($diffs as $diff)
        {
            if($encoding != 'utf-8')
            {
                $diff->fileName = helper::convertEncoding($diff->fileName, $encoding);
                if(empty($diff->contents)) continue;
                foreach($diff->contents as $content)
                {
                    if(empty($content->lines)) continue;
                    foreach($content->lines as $lines)
                    {
                        if(empty($lines->line)) continue;
                        $lines->line = helper::convertEncoding($lines->line, $encoding);
                    }
                }
            }
        }

        /* When arrange is appose then adjust data for show them easy.*/
        if($arrange == 'appose')
        {
            foreach($diffs as $diffFile)
            {
                if(empty($diffFile->contents)) continue;
                foreach($diffFile->contents as $content)
                {
                    $old = array();
                    $new = array();
                    foreach($content->lines as $line)
                    {
                        if($line->type != 'new') $old[$line->oldlc] = $line->line;
                        if($line->type != 'old') $new[$line->newlc] = $line->line;
                    }
                    $content->old = $old;
                    $content->new = $new;
                }
            }
        }

        $this->view->type        = 'diff';
        $this->view->showBug     = $showBug;
        $this->view->entry       = urldecode($entry);
        $this->view->suffix      = $suffix;
        $this->view->file        = $file;
        $this->view->repoID      = $repoID;
        $this->view->repo        = $repo;
        $this->view->encoding    = str_replace('-', '_', $encoding);
        $this->view->arrange     = $arrange;
        $this->view->diffs       = $diffs;
        $this->view->newRevision = $newRevision;
        $this->view->oldRevision = $oldRevision;
        $this->view->revision    = $newRevision;
        $this->view->historys    = $repo->SCM == 'Git' ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in("$oldRevision,$newRevision")->andWhere('repo')->eq($repo->id)->fetchPairs() : '';
        $this->view->info        = $info;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->diff;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->diff;

        $this->display();
    }

    /**
     * Download repo file. 
     * 
     * @param  int    $repoID 
     * @param  string $path 
     * @param  string $fromRevision 
     * @param  string $toRevision 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function download($repoID, $path, $fromRevision = 'HEAD', $toRevision = '', $type = 'file')
    {
        if($this->get->path) $path = $this->get->path;
        $entry = $this->repo->decodePath($path);
        $repo  = $this->repo->getRepoByID($repoID);
        $this->scm->setEngine($repo);
        $content = $type == 'file' ? $this->scm->cat($entry, $fromRevision) : $this->scm->diff($entry, $fromRevision, $toRevision, 'patch');
        $fileName = basename(urldecode($entry));
        if($type != 'file') $fileName .= "r$fromRevision--r$toRevision.patch";
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $this->fetch('file', 'sendDownHeader', array("fileName" => $fileName, "fileType" => $extension,  "content" => $content));
    }

    /**
     * Set Rules.
     * 
     * @access public
     * @return void
     */
    public function setRules($module = '')
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.repo.rules', json_encode($this->post->rules));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('setRules')));
        }

        $this->repo->setMenu($this->repos, $this->session->repoID, false);

        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->setRules;
        $this->view->position[] = $this->lang->repo->setRules;

        $this->display();
    }

    /**
     * Show sync comment.
     * 
     * @param  int    $repoID 
     * @param  string $branch 
     * @access public
     * @return void
     */
    public function showSyncCommit($repoID = 0, $branch = '')
    {
        $this->repo->setMenu($this->repos, $repoID);
        if($repoID == 0) $repoID = $this->session->repoID;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->showSyncCommit;
        $this->view->position[] = $this->lang->repo->showSyncCommit;

        $latestInDB = $this->repo->getLatestCommit($repoID);
        $this->view->version = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID  = $repoID;
        $this->view->branch  = $branch;
        $this->display();
    }

    /**
     * Ajax sync comment.
     * 
     * @param  int    $repoID 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function ajaxSyncCommit($repoID = 0, $type = 'batch')
    {
        set_time_limit(0);
        $repo = $this->repo->getRepoByID($repoID);
        if(empty($repo)) die();
        if($repo->synced) die('finish');

        $this->scm->setEngine($repo);

        $branchID = '';
        if($repo->SCM == 'Git' and empty($branchID))
        {
            $branches = $this->scm->branch();
            if($branches)
            {
                /* Init branchID. */
                if($this->cookie->syncBranch) $branchID = $this->cookie->syncBranch;
                if(!isset($branches[$branchID])) $branchID = '';
                if(empty($branchID)) $branchID = 'master';

                /* Get unsynced branches. */
                unset($branches['master']);
                if($branchID != 'master')
                {
                    foreach($branches as $branch)
                    {
                        unset($branches[$branch]);
                        if($branch == $branchID) break;
                    }
                }

                $this->repo->setRepoBranch($branchID);
                setcookie("syncBranch", $branchID, 0, $this->config->webRoot);
            }
        }

        $latestInDB = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($repo->SCM == 'Git' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : ($repo->SCM == 'Git' ? $latestInDB->commit : $latestInDB->revision);
        if($type == 'batch')
        {
            $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branchID);
        }
        else
        {
            $logs = $this->scm->getCommits($revision, 0, $branchID);
        }

        $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branchID);
        if(empty($commitCount))
        {
            if(!$repo->synced)
            {
                if($repo->SCM == 'Git')
                {
                    if($branchID) $this->repo->saveExistCommits4Branch($repo->id, $branchID);

                    $branchID = reset($branches);
                    setcookie("syncBranch", $branchID, 0, $this->config->webRoot);

                    if($branchID) $this->repo->fixCommit($repoID);
                }

                if(empty($branchID))
                {
                    $this->repo->markSynced($repoID);
                    die('finish');
                }
            }
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $type == 'batch' ?  $commitCount : 'finish';
    }

    /**
     * Ajax sync git branch comment.
     * 
     * @param  int    $repoID 
     * @param  string $branch 
     * @access public
     * @return void
     */
    public function ajaxSyncBranchCommit($repoID = 0, $branch = '')
    {
        set_time_limit(0);
        $repo = $this->repo->getRepoByID($repoID);
        if(empty($repo)) die();
        if($repo->SCM != 'Git') die('finish');

        $this->scm->setEngine($repo);

        $this->repo->setRepoBranch($branch);
        setcookie("syncBranch", $branch, 0, $this->config->webRoot);

        $latestInDB = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($repo->SCM == 'Git' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : $latestInDB->commit;

        $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branch);
        $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branch);
        if(empty($commitCount))
        {
            if($branch) $this->repo->saveExistCommits4Branch($repo->id, $branch);

            setcookie("syncBranch", $branch, 0, $this->config->webRoot);
            $this->repo->markSynced($repoID);
            die('finish');
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $commitCount;
    }

    /**
     * Ajax show side logs.
     * 
     * @param  int    $repoID 
     * @param  string $path 
     * @param  string $type 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function ajaxSideCommits($repoID, $path, $type = 'dir', $recTotal = 0, $recPerPage = 8, $pageID = 1)
    {
        if($this->get->path) $path = $this->get->path;
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $repo      = $this->repo->getRepoByID($repoID);
        $path      = $this->repo->decodePath($path);
        $commiters = $this->loadModel('user')->getCommiters();
        $revisions = $this->repo->getCommits($repo, $path, 'HEAD', $type, $pager);
        foreach($revisions as $revision) $revision->committer = zget($commiters, $revision->committer, $revision->committer);

        $this->view->repo       = $this->repo->getRepoByID($repoID);
        $this->view->revisions  = $revisions;
        $this->view->pager      = $pager;
        $this->view->repoID     = $repoID;
        $this->view->logType    = $type;
        $this->view->path       = urldecode($path);
        $this->display();
    }

    /**
     * Ajax get svn tags 
     * 
     * @param  int    $repoID 
     * @param  string $path 
     * @access public
     * @return void
     */
    public function ajaxGetSVNDirs($repoID, $path = '')
    {
        $repo = $this->repo->getRepoByID($repoID);
        if($repo->SCM != 'Subversion') die(json_encode(array()));

        $path = $this->repo->decodePath($path);
        $dirs = array();
        if(empty($path))
        {
            $dirs['/'] = '';
            if(empty($repo->prefix)) $path = '/';
        }

        $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
        if($tags)
        {
            $dirs['/'] = $this->repo->encodePath($path);
            foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $this->repo->encodePath($dirPath);
        }
        die(json_encode($dirs));
    }
}
