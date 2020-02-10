<?php
class repoModel extends model
{
    /**
     * Check repo priv.
     * 
     * @param  object $repo 
     * @access public
     * @return bool
     */
    public function checkPriv($repo)
    {
        $account = $this->app->user->account;
        if(strpos(",{$this->app->company->admins},", ",$account,") !== false) return true;
        if(empty($repo->acl->groups) and empty($repo->acl->users)) return true;
        if(!empty($repo->acl->groups))
        {
            foreach($this->app->user->groups as $group)
            {
                if(in_array($group, $repo->acl->groups)) return true;
            }
        }
        if(!empty($repo->acl->users) and in_array($account, $repo->acl->users)) return true;
        return false;
    }

    /**
     * Set menu.
     * 
     * @param  array  $repos 
     * @param  int    $repoID 
     * @access public
     * @return void
     */
    public function setMenu($repos, $repoID = '', $showRepoSeletion = true)
    {
        if(empty($repoID)) $repoID = $this->session->repoID ? $this->session->repoID : key($repos);
        if(!isset($repos[$repoID])) $repoID = key($repos);

        /* Check the privilege. */
        if($repoID)
        {
            $repo = $this->getRepoByID($repoID);
            if(empty($repo))
            {
                echo(js::alert($this->lang->repo->error->noFound));
                die(js::locate('back'));
            }

            if(!$this->checkPriv($repo))
            {
                echo(js::alert($this->lang->repo->error->accessDenied));
                die(js::locate('back'));
            }
        }

        if($showRepoSeletion && !empty($repos))
        {
            $repoIndex  = '<div class="btn-group angle-btn"><div class="btn-group"><button data-toggle="dropdown" type="button" class="btn">' . ($repo->SCM == 'Subversion' ? '[SVN] ' : '[GIT] ') . $repo->name . ' <span class="caret"></span></button>';
            $repoIndex .= $this->select($repos, $repoID);
            $repoIndex .= '</div></div>';

            $branches = $this->getBranches($repo);
            if(empty($branches))
            {
                $this->setRepoBranch('');
            }
            else
            {
                $branchID = 'master';
                if($this->cookie->repoBranch) $branchID = $this->cookie->repoBranch;
                if(!isset($branches[$branchID])) $branchID = 'master';

                $branch = zget($branches, $branchID);
                if(empty($branch)) $branchID = $branch = current($branches);

                $this->setRepoBranch($branchID);

                $repoIndex .= '<div class="btn-group angle-btn"><div class="btn-group"><button data-toggle="dropdown" type="button" class="btn">' . $branch . ' <span class="caret"></span></button>';
                $repoIndex .= "<div class='dropdown-menu search-list' data-ride='searchList'>";
                $repoIndex .= "<div class='list-group'>";
                foreach($branches as $branch)
                {
                    if(empty($branch)) continue;

                    $class = $branchID == $branch ? "class='active'" : '';
                    $repoIndex .= html::a("javascript:switchBranch(\"$branch\")", $branch, '', $class);
                }
                $repoIndex .= "</div></div></div></div>";
            }

            $this->lang->modulePageNav = $repoIndex;
        }

        foreach($this->lang->repo->menu as $key => $menu)
        {
            common::setMenuVars($this->lang->repo->menu, $key, $repoID);
        }

        session_start();
        $this->session->set('repoID', $repoID);
        session_write_close();
    }

    /**
     * Create the select code of repos. 
     * 
     * @param  array     $repos 
     * @param  int       $repoID 
     * @param  string    $currentModule 
     * @param  string    $currentMethod 
     * @access public
     * @return string
     */
    public function select($repos, $repoID)
    {
        $selectHtml  = "<div class='dropdown-menu search-list' data-ride='searchList'>";
        $selectHtml .= "<div class='input-control search-box has-icon-left has-icon-right search-example'>";
        $selectHtml .= "<input id='repoSearchBox' type='search' autocomplete='off' class='form-control search-input empty'>";
        $selectHtml .= "<label for='repoSearchBox' class='input-control-icon-left search-icon'><i class='icon icon-search'></i></label>";
        $selectHtml .= "<a class='input-control-icon-right search-clear-btn'><i class='icon icon-close icon-sm'></i></a></div>";
        $selectHtml .= "<div class='list-group'>";
        foreach($repos as $id => $name)
        {
            $class = $repoID == $id ? "class='active'" : '';
            $selectHtml .= html::a(helper::createLink('repo', 'browse', "repoID={$id}"), $name, '', $class);
        }
        $selectHtml .= "</div></div>";

        return $selectHtml;
    }

    /**
     * Get repo list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listAll($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($repos as $i => $repo)
        {
            $repo->acl = json_decode($repo->acl);
            if(!$this->checkPriv($repo)) unset($repos[$i]);
        }

        return $repos;
    }

    /**
     * Create a repo.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $this->checkConnection();
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();
        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);
        if(empty($data->client)) $data->client = 'svn';

        if($data->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info = $scm->info('');
            $data->prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }

        $data->password = base64_encode($data->password);
        $this->dao->insert(TABLE_REPO)->data($data)
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->checkIF($data->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Get all repos.
     * 
     * @access public
     * @return array
     */
    public function getAllRepos()
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq(0)->fetchAll();
        foreach($repos as $i => $repo)
        {
            $repo->acl = json_decode($repo->acl);
            if(!$this->checkPriv($repo)) unset($repos[$i]);
        }

        return $repos;
    }

    /**
     * Update a repo.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $this->checkConnection();
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();
        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);

        if(empty($data->client)) $data->client = 'svn';
        $repo = $this->getRepoByID($id);
        $data->prefix = $repo->prefix;
        if($data->SCM == 'Subversion' and $data->path != $repo->path)
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info = $scm->info('');
            $data->prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }
        elseif($data->SCM != $repo->SCM and $data->SCM == 'Git')
        {
            $data->prefix = '';
        }

        if($data->path != $repo->path) $data->synced = 0;

        $data->password = base64_encode($data->password);
        $this->dao->update(TABLE_REPO)->data($data)
            ->batchCheck($this->config->repo->edit->requiredFields, 'notempty')
            ->checkIF($data->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)->exec();

        if($repo->path != $data->path)
        {
            $this->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($id)->exec();
            $this->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($id)->exec();
            return false;
        }
        return true;
    }

    /**
     * Get repo pairs.
     * 
     * @access public
     * @return array
     */
    public function getRepoPairs()
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq(0)->fetchAll();
        $repoPairs = array();
        foreach($repos as $repo)
        {
            $repo->acl = json_decode($repo->acl);
            $scm = $repo->SCM == 'Subversion' ? 'svn' : 'git';
            if($this->checkPriv($repo)) $repoPairs[$repo->id] = "[{$scm}] " . $repo->name;
        }

        return $repoPairs;
    }

    /**
     * Get repo by id. 
     * 
     * @param  int    $repoID 
     * @access public
     * @return object
     */
    public function getRepoByID($repoID)
    {
        $repo = $this->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return false;

        $repo->password = base64_decode($repo->password);
        $repo->acl = json_decode($repo->acl);
        return $repo;
    }

    /**
     * Get git branches.
     * 
     * @param  object    $repo 
     * @access public
     * @return array
     */
    public function getBranches($repo)
    {
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        return $this->scm->branch();
    }

    /**
     * Get logs.
     * 
     * @param  object $repo 
     * @param  string $entry 
     * @param  string $revision 
     * @param  string $type 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getLogs($repo, $entry, $revision = 'HEAD', $type = 'dir', $pager = null)
    {
        $entry = ltrim($entry, '/');
        $entry = $repo->prefix . (empty($entry) ? '' : '/' . $entry);

        $repoID       = $repo->id;
        $revisionTime = $this->dao->select('time')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($revision != 'HEAD')->andWhere('t1.revision')->eq($revision)->fi()
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('time desc')
            ->limit(1)
            ->fetch('time');

        $historyIdList = array();
        if($entry != '/' and !empty($entry))
        {
            $historyIdList = $this->dao->select('DISTINCT t2.id')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
                ->where('1=1')
                ->andWhere('t1.repo')->eq($repo->id)
                ->andWhere('t2.`time`')->le($revisionTime)
                ->andWhere('left(t2.comment, 12)')->ne('Merge branch')
                ->beginIF($this->cookie->repoBranch)->andWhere('t3.branch')->eq($this->cookie->repoBranch)->fi()
                ->beginIF($type == 'dir')
                ->andWhere('t1.parent', true)->like(rtrim($entry, '/') . "/%")
                ->orWhere('t1.parent')->eq(rtrim($entry, '/'))
                ->markRight(1)
                ->fi()
                ->beginIF($type == 'file')->andWhere('t1.path')->eq("$entry")->fi()
                ->orderBy('t2.`time` desc')
                ->page($pager, 't2.id')
                ->fetchPairs('id', 'id');
        }

        $comments = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t1.`time`')->le($revisionTime)
            ->andWhere('left(t1.comment, 12)')->ne('Merge branch')
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->beginIF($entry != '/' and !empty($entry))->andWhere('t1.id')->in($historyIdList)->fi()
            ->orderBy('time desc');
        if($entry == '/' or empty($entry))$comments->page($pager, 't1.id');
        $comments = $comments->fetchAll('revision');

        foreach($comments as $repoComment) $repoComment->comment = $this->replaceCommentLink($repoComment->comment);
        return $comments;
    }

    /**
     * Get latest comment.
     * 
     * @param  int    $repoID 
     * @access public
     * @return object
     */
    public function getLatestComment($repoID)
    {
        $count = $this->dao->select('count(DISTINCT t1.id) as count')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->fetch('count');

        $lastComment = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time desc')
            ->limit(1)
            ->fetch();
        if(empty($lastComment)) return null;

        $repo = $this->getRepoByID($repoID);
        if($repo->SCM == 'Git' and $lastComment->commit != $count)
        {
            $this->fixCommit($repo->id);
            $lastComment->commit = $count;
        }

        return $lastComment;
    }

    /**
     * Get revisions from db. 
     * 
     * @param  int    $repoID 
     * @param  string $limit 
     * @param  string $maxRevision 
     * @param  string $minRevision 
     * @access public
     * @return array
     */
    public function getRevisionsFromDB($repoID, $limit = '', $maxRevision = '', $minRevision = '')
    {
        $revisions = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF(!empty($maxRevision))->andWhere('t1.revision')->le($maxRevision)->fi()
            ->beginIF(!empty($minRevision))->andWhere('t1.revision')->ge($minRevision)->fi()
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.revision desc')
            ->beginIF(!empty($limit))->limit($limit)->fi()
            ->fetchAll('revision');
        $commiters = $this->loadModel('user')->getCommiters();
        foreach($revisions as $revision)
        {
            $revision->comment   = $this->replaceCommentLink($revision->comment);
            $revision->committer = isset($commiters[$revision->committer]) ? $commiters[$revision->committer] : $revision->committer;
        }
        return $revisions;
    }

    /**
     * Get git revisionName.
     * 
     * @param  string $revision 
     * @param  int    $commit 
     * @access public
     * @return string
     */
    public function getGitRevisionName($revision, $commit)
    {
        if(empty($commit)) return substr($revision, 0, 10);
        return substr($revision, 0, 10) . '<span title="' . sprintf($this->lang->repo->commitTitle, $commit) . '"> (' . $commit . ') </span>';
    }

    /**
     * Save commit.
     * 
     * @param  int    $repoID 
     * @param  array  $logs 
     * @param  int    $version 
     * @param  string $branch 
     * @access public
     * @return int
     */
    public function saveCommit($repoID, $logs, $version, $branch = '')
    {
        $count = 0;
        if(empty($logs)) return $count;

        foreach($logs['commits'] as $i => $commit)
        {
            $existsRevision  = $this->dao->select('id,revision')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($commit->revision)->fetch();
            if($existsRevision)
            {
                if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($existsRevision->id)->set('branch')->eq($branch)->exec();
                continue;
            }

            $commit->repo    = $repoID;
            $commit->commit  = $version;
            $commit->comment = htmlspecialchars($commit->comment);
            $this->dao->insert(TABLE_REPOHISTORY)->data($commit)->exec();
            if(!dao::isError())
            {
                $commitID = $this->dao->lastInsertID();
                if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($commitID)->set('branch')->eq($branch)->exec();
                foreach($logs['files'][$i] as $file)
                {
                    $parentPath = dirname($file->path);

                    $file->parent   = $parentPath == '\\' ? '/' : $parentPath;
                    $file->revision = $commitID;
                    $file->repo     = $repoID;
                    $this->dao->insert(TABLE_REPOFILES)->data($file)->exec();
                }
                $revisionPairs[$commit->revision] = $commit->revision;
                $version++;
                $count++;
            }
            else
            {
                dao::getError();
            }
        }
        return $count;
    }

    /**
     * Save exists log branch.
     * 
     * @param  int    $repoID 
     * @param  string $branch 
     * @access public
     * @return void
     */
    public function saveExistsLogBranch($repoID, $branch)
    {
        $lastBranchLog = $this->dao->select('t1.time')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t2.branch')->eq($branch)
            ->orderBy('time')
            ->limit(1)
            ->fetch();
        $stmt = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('time')->lt($lastBranchLog->time)->query();
        while($log = $stmt->fetch())
        {
            $this->dao->REPLACE(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($log->id)->set('branch')->eq($branch)->exec();
        }
    }

    /**
     * Update commit count.
     * 
     * @param  int    $repoID 
     * @param  int    $count 
     * @access public
     * @return void
     */
    public function updateCommitCount($repoID, $count)
    {
        return $this->dao->update(TABLE_REPO)->set('commits')->eq($count)->where('id')->eq($repoID)->exec();
    }

    /**
     * Update latest commit.
     * 
     * @param  object $repo 
     * @access public
     * @return void
     */
    public function updateLatestCommit($repo)
    {
        $repoID     = $repo->id;
        $latestInDB = $this->getLatestComment($repoID);
        $version    = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $commits    = 0;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $commitCount = $scm->getCommitCount(empty($latestInDB) ? 0 : $latestInDB->commit, empty($latestInDB) ? 0 : $latestInDB->revision);
        if($commitCount >= $version)
        {
            $revision = 'HEAD';
            $logs = $scm->getCommits($revision, $commitCount - $version + 1, $this->cookie->repoBranch);
            $logs['commits'] = array_reverse($logs['commits'], true);

            $commits = $this->saveCommit($repoID, $logs, $version, $this->cookie->repoBranch);
            if($repo->SCM == 'Git' and empty($latestInDB)) $this->fixCommit($repo->id);
            $this->updateCommitCount($repoID, $commits);
        }
        $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repoID)->exec();

        return $commits;
    }

    /**
     * Get pre and next revision.
     * 
     * @param  object $repo
     * @param  string $entry
     * @param  string $revision
     * @param  string $fileType
     * @param  string $method
     *
     * @access public
     * @return object
     */
    public function getPreAndNext($repo, $entry, $revision = 'HEAD', $fileType = 'dir', $method = 'view')
    {
        $entry  = ltrim($entry, '/');
        $entry  = $repo->prefix . '/' . $entry;
        $repoID = $repo->id;

        if($method == 'view')
        {
            $revisions = $this->dao->select('DISTINCT t1.revision,t1.commit')->from(TABLE_REPOHISTORY)->alias('t1')
                ->leftJoin(TABLE_REPOFILES)->alias('t2')->on('t1.id=t2.revision')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t1.id=t3.revision')
                ->where('t1.repo')->eq($repoID)
                ->beginIF($this->cookie->repoBranch)->andWhere('t3.branch')->eq($this->cookie->repoBranch)->fi()
                ->andWhere('t2.path')->eq("$entry")
                ->orderBy('commit desc')
                ->fetchPairs();
        }
        else
        {
            $revisions = $this->dao->select('DISTINCT t1.revision,t1.commit')->from(TABLE_REPOHISTORY)->alias('t1')
                ->leftJoin(TABLE_REPOFILES)->alias('t2')->on('t1.id=t2.revision')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t1.id=t3.revision')
                ->where('t1.repo')->eq($repoID)
                ->beginIF($this->cookie->repoBranch)->andWhere('t3.branch')->eq($this->cookie->repoBranch)->fi()
                ->beginIF($entry == '/')->andWhere('t2.revision = t1.id')->fi()
                ->beginIF($fileType == 'dir' && $entry != '/')
                ->andWhere('t2.parent', true)->like(rtrim($entry, '/') . "/%")
                ->orWhere('t2.parent')->eq(rtrim($entry, '/'))
                ->markRight(1)
                ->fi()
                ->beginIF($fileType == 'file' && $entry != '/')->andWhere('t2.path')->eq($entry)->fi()
                ->orderBy('commit desc')
                ->fetchPairs();
        }

        $preRevision  = false;
        $preAndNext   = new stdclass(); 
        $preAndNext->pre  = ''; 
        $preAndNext->next = ''; 
        foreach($revisions as $version => $commit)
        {
            /* Get next object. */
            if($preRevision === true)
            {   
                $preAndNext->next = $version;
                break;
            }   

            /* Get pre object. */
            if($revision == $version)
            {   
                if($preRevision) $preAndNext->pre = $preRevision;
                $preRevision = true;
            }   
            if($preRevision !== true) $preRevision = $version;
        }
        return $preAndNext;
    }

    /**
     * Create link for repo
     * 
     * @param  string $method 
     * @param  string $params 
     * @param  string $pathParams 
     * @param  string $viewType 
     * @param  bool   $onlybody 
     * @access public
     * @return string
     */
    public function createLink($method, $params = '', $pathParams = '', $viewType = '', $onlybody = false)
    {
        $link  = helper::createLink('repo', $method, $params, $viewType, $onlybody);
        if(empty($pathParams)) return $link;

        $link .= strpos($link, '?') === false ? '?' : '&';
        $link .= $pathParams;
        return $link;
    }

    /**
     * Set back session/
     * 
     * @param  string $type 
     * @param  bool   $withOtherModule 
     * @access public
     * @return void
     */
    public function setBackSession($type = 'list', $withOtherModule = false)
    {
        $backKey = 'repo' . ucfirst(strtolower($type));
        session_start();
        $uri = $this->app->getURI(true);
        if(!empty($_GET) and $this->config->requestType == 'PATH_INFO') $uri .= "?" . http_build_query($_GET);
        $_SESSION[$backKey] = $uri;
        if($type == 'list') unset($_SESSION['repoView']);
        if($withOtherModule)
        {
            $this->session->set('bugList', $uri);
            $this->session->set('taskList', $uri);
        }
        session_write_close();
    }

    /**
     * Set repo branch.
     * 
     * @param  string $branch 
     * @access public
     * @return void
     */
    public function setRepoBranch($branch)
    {
        setcookie("repoBranch", $branch, 0, $this->config->webRoot);
        $_COOKIE['repoBranch'] = $branch;
    }

    /**
     * Mark synced status.
     * 
     * @param  int    $repoID 
     * @access public
     * @return void
     */
    public function markSynced($repoID)
    {
        $this->fixCommit($repoID);
        $this->dao->update(TABLE_REPO)->set('synced')->eq(1)->where('id')->eq($repoID)->exec();
    }

    /**
     * Fix commit.
     * 
     * @param  int    $repoID 
     * @access public
     * @return void
     */
    public function fixCommit($repoID)
    {
        $stmt = $this->dao->select('DISTINCT t1.id')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('time')
            ->query();

        $i = 1;
        while($repoHistory = $stmt->fetch())
        {
            $this->dao->update(TABLE_REPOHISTORY)->set('`commit`')->eq($i)->where('id')->eq($repoHistory->id)->exec();
            $i++;
        }
    }

    /**
     * Encode repo path.
     * 
     * @param  string $path 
     * @access public
     * @return string
     */
    public function encodePath($path = '')
    {
        return helper::safe64Encode(urlencode($path));
    }

    /**
     * Decode repo path.
     * 
     * @param  string $path 
     * @access public
     * @return string
     */
    public function decodePath($path = '')
    {
        return trim(urldecode(helper::safe64Decode($path)), '/');
    }

    /**
     * Check content is binary.
     * 
     * @param  string $content 
     * @param  string $suffix 
     * @access public
     * @return bool
     */
    public function isBinary($content, $suffix = '')
    {
        if(strpos($this->config->repo->binary, "|$suffix|") !== false) return true;

        $blk = substr($content, 0, 512);
        return ( 
            false ||
            substr_count($blk, "^\r\n")/512 > 0.3 ||
            substr_count($blk, "^ -~")/512 > 0.3 ||
            substr_count($blk, "\x00") > 0
        ); 
    }

    /**
     * Check connection 
     * 
     * @access public
     * @return void
     */
    public function checkConnection()
    {
        if(empty($_POST)) return false;
        $scm      = $this->post->SCM;
        $client   = $this->post->client;
        $account  = $this->post->account;
        $password = $this->post->password;
        $encoding = strtoupper($this->post->encoding);
        $path     = $this->post->path;
        if($encoding != 'UTF8' and $encoding != 'UTF-8') $path = helper::convertEncoding($path, 'utf-8', $encoding);

        if($scm == 'Subversion')
        {
            $path = '"' . $path . '"';
            if(stripos($path, 'https://') === 1 or stripos($path, 'svn://') === 1)
            {
                $ssh     = true;
                $remote  = true; 
                $command = "$client info --username $account --password $password --non-interactive --trust-server-cert-failures=cn-mismatch --trust-server-cert --no-auth-cache $path 2>&1";
            }
            else if(stripos($path, 'file://') === 1)
            {
                $ssh     = false;
                $remote  = false; 
                $command = "$client info --non-interactive --no-auth-cache $path 2>&1";
            }
            else
            {
                $ssh     = false;
                $remote  = true; 
                $command = "$client info --username $account --password $password --non-interactive --no-auth-cache $path 2>&1";
            }
            exec($command, $output, $result);
            if($result) 
            {
                $versionCommand = "$client --version --quiet 2>&1";
                exec($versionCommand, $versionOutput, $versionResult);
                if($versionResult)
                {
                    $message = sprintf($this->lang->repo->error->output, $versionCommand, $versionResult, join("\n", $versionOutput));
                    echo $message;
                    die(js::alert($this->lang->repo->error->cmd . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
                }
                if($ssh and version_compare(end($versionOutput), '1.6', '<')) die(js::alert($this->lang->repo->error->version));
                $message = sprintf($this->lang->repo->error->output, $command, $result, join("\n", $output));
                echo $message;
                if(stripos($message, 'Expected FS format between') !== false and strpos($message, 'found format') !== false) die(js::alert($this->lang->repo->error->clientVersion));
                if(preg_match('/[^\:\/\\A-Za-z0-9_\-\'\"]/', $path)) die(js::alert($this->lang->repo->error->encoding . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
                die(js::alert($this->lang->repo->error->connect . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
            }
        }
        elseif($scm == 'Git')
        {
            if(!chdir($path))
            {
                if(!is_dir($path)) die(js::alert(sprintf($this->lang->repo->error->noFile, $path)));
                if(!is_executable($path)) die(js::alert(sprintf($this->lang->repo->error->noPriv, $path)));
                die(js::alert($this->lang->repo->error->path));
            }

            $command = "$client tag 2>&1";
            exec($command, $output, $result);
            if($result)
            {
                echo sprintf($this->lang->repo->error->output, $command, $result, join("\n", $output));
                die(js::alert($this->lang->repo->error->connect));
            }
        }
        return true;
    }

    /**
     * Replace comment link.
     * 
     * @param  string $comment 
     * @access public
     * @return string
     */
    public function replaceCommentLink($comment)
    {
        $stories = array();
        $tasks   = array();
        $bugs    = array();
        $commonReg = "(?:\s){0,}((?:#|:|��){0,})([0-9, ]{1,})";
        $taskReg  = '/task' .  $commonReg . '/i';
        $storyReg = '/story' . $commonReg . '/i';
        $bugReg   = '/bug'   . $commonReg . '/i';
        if(preg_match_all($storyReg, $comment, $result))
        {
            $storyLinks = $this->addLink($result, 'story');
            foreach($storyLinks as $search => $replace) $comment = str_replace($search, $replace, $comment);
        }
        if(preg_match_all($taskReg, $comment, $result))
        {
            $taskLinks = $this->addLink($result, 'task');
            foreach($taskLinks as $search => $replace) $comment = str_replace($search, $replace, $comment);
        }
        if(preg_match_all($bugReg, $comment, $result))
        {
            $bugLinks = $this->addLink($result, 'bug');
            foreach($bugLinks as $search => $replace) $comment = str_replace($search, $replace, $comment);
        }
        return $comment;
    }

    /**
     * Add link.
     * 
     * @param  string $matches 
     * @param  string $method 
     * @access public
     * @return string
     */
    public function addLink($matches, $method)
    {
        if(empty($matches)) return null;
        $replaceLines = array();
        foreach($matches[2] as $key => $ids)
        {
            $spit = strpos($ids, ',') !== false ? ',' : ' ';
            $ids = explode(' ', str_replace(',', ' ', $ids));
            $links = $method . " " . $matches[1][$key];
            foreach($ids as $id)
            {
                if($id) $links .= html::a(helper::createLink($method, 'view', "id=$id"), $id) . $spit;
            }
            $replaceLines[$matches[0][$key]] = rtrim($links, $spit);
        }
        return $replaceLines;
    }

    /**
     * list repos for jenkins job edit
     *
     * @return mixed
     */
    public function listForSelection($whr)
    {
        $repos = $this->dao->select('id, name')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere('(' . $whr . ')')->fi()
            ->orderBy(id)
            ->fetchPairs();
        $repos[''] = '';
        return $repos;
    }
    /**
     * list repos for jenkins job edit， key will be 12-git
     *
     * @return mixed
     */
    public function listForSelectionWithType($whr)
    {
        $repos = $this->dao->select("concat(id, '-', SCM), name")->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere('(' . $whr . ')')->fi()
            ->orderBy(id)
            ->fetchPairs();
        $repos[''] = '';
        return $repos;
    }

    /**
     * list repos for sync
     *
     * @return mixed
     */
    public function listForSync($whr)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere('(' . $whr . ')')->fi()
            ->orderBy(id)
            ->fetchAll();

        foreach($repos as $repo)
        {
            $repo->password = base64_decode($repo->password);
        }

        return $repos;
    }
}
