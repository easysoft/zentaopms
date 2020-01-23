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
    public function setMenu($repos, $repoID = '')
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

        if(!empty($repos))
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

        if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
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
        if((time() - strtotime($repo->lastSync)) / 60 >= $this->config->repo->syncTime) $this->updateLatestCommit($repo);

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
                ->where('1=1')
                ->andWhere('t1.repo')->eq($repo->id)
                ->beginIF($type == 'dir')
                ->andWhere('t1.parent', true)->like(rtrim($entry, '/') . "/%")
                ->orWhere('t1.parent')->eq(rtrim($entry, '/'))
                ->markRight(1)
                ->fi()
                ->beginIF($type == 'file')->andWhere('t1.path')->eq("$entry")->fi()
                ->orderBy('t2.`time` desc')
                ->page($pager)
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
     * Get history.
     * 
     * @param  int    $repoID 
     * @param  array  $revisions 
     * @access public
     * @return array
     */
    public function getHistory($repoID, $revisions)
    {
        return $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t1.revision')->in($revisions)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->fetchAll('revision');
    }

    /**
     * Get review.
     * 
     * @param  int    $repoID 
     * @param  string $entry 
     * @param  string $revision 
     * @access public
     * @return array
     */
    public function getReview($repoID, $entry, $revision)
    {
        $reviews = array();
        $bugs    = $this->dao->select('t1.*, t2.realname')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.openedBy = t2.account')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t1.entry')->eq($entry)
            ->andWhere('t1.v2')->eq($revision)
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        $comments = $this->dao->select('t1.*, t2.realname')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.actor = t2.account')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t1.objectID')->in(array_keys($bugs))
            ->andWhere('t1.action')->eq('commented')
            ->fetchGroup('objectID', 'id');
        foreach($bugs as $bug)
        {
            if(common::hasPriv('bug', 'edit'))   $bug->edit   = true;
            if(common::hasPriv('bug', 'delete')) $bug->delete = true;
            $lines = explode(',', trim($bug->lines, ','));
            $line  = $lines[0];
            $reviews[$line]['bugs'][$bug->id] = $bug;

            if(isset($comments[$bug->id]))
            {
                foreach($comments[$bug->id] as $key => $comment)
                {
                    if($comment->actor == $this->app->user->account) $comment->edit = true; 
                }
                $reviews[$line]['comments'] = $comments;
            }
        }

        return $reviews;
    }

    /**
     * Get bugs by repo.
     * 
     * @param  int    $repoID 
     * @param  string $browseType 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getBugsByRepo($repoID, $browseType, $orderBy, $pager)
    {
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('repo')->eq($repoID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($browseType == 'assigntome')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'openedbyme')->andWhere('openedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'resolvedbyme')->andWhere('resolvedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assigntonull')->andWhere('assignedTo')->eq('')->fi()
            ->beginIF($browseType == 'unresolved')->andWhere('resolvedBy')->eq('')->fi()
            ->beginIF($browseType == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll(); 
        return $bugs;
    }

    /**
     * Get project pairs.
     * 
     * @param  int    $product 
     * @param  int    $branch 
     * @access public
     * @return array
     */
    public function getProjectPairs($product, $branch = 0)
    {
        $pairs    = array();
        $projects = $this->loadModel('project')->getList('undone', 0, $product, $branch);
        foreach($projects as $project) $pairs[$project->id] = $project->name;
        return $pairs;
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
     * create 
     * 
     * @access public
     * @return int
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

        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->insert(TABLE_REPO)->data($data)->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Save settings.
     * 
     * @param  int    $repoID 
     * @access public
     * @return bool
     */
    public function saveSettings($repoID)
    {
        $this->checkConnection();
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();
        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);

        if(empty($data->client)) $data->client = 'svn';
        $repo = $this->getRepoByID($repoID);
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
        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->update(TABLE_REPO)->data($data)->where('id')->eq($repoID)->exec();
        if($repo->path != $data->path)
        {
            $this->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->exec();
            $this->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->exec();
            return false;
        }
        return true;
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
     * Save bug.
     * 
     * @param  int    $repoID 
     * @param  string $file 
     * @param  int    $v1 
     * @param  int    $v2 
     * @access public
     * @return array
     */
    public function saveBug($repoID, $file, $v1, $v2)
    {
        $now  = helper::now();
        $data = fixer::input('post')
            ->add('severity', 3)
            ->add('openedBy', $this->app->user->account) 
            ->add('openedDate', $now)
            ->add('openedBuild', 'trunk')
            ->add('assignedDate', $now)
            ->add('type', 'codeimprovement')
            ->add('repo', $repoID)
            ->add('entry', $file)
            ->add('lines', $this->post->begin . ',' . $this->post->end)
            ->add('v1', $v1)
            ->add('v2', $v2)
            ->remove('commentText,begin,end,uid')
            ->get();

        $data->steps = $this->loadModel('file')->pasteImage($this->post->commentText, $this->post->uid);
        $this->dao->insert(TABLE_BUG)->data($data)->exec();

        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $bugID, 'bug');
            setcookie("repoPairs[$repoID]", $data->product);

            return array('result' => 'success', 'id' => $bugID, 'realname' => $this->app->user->realname, 'openedDate' => substr($now, 5, 11), 'edit' => true, 'delete' => true, 'lines' => $data->lines, 'line' => $this->post->begin, 'steps' => $data->steps, 'title' => $data->title);
        }

        return array('result' => 'fail', 'message' => join("\n", dao::getError()));
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

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $commitCount = $scm->getCommitCount(empty($latestInDB) ? 0 : $latestInDB->commit, empty($latestInDB) ? 0 : $latestInDB->revision);
        if($commitCount >= $version)
        {
            $revision = 'HEAD';
            $logs = $scm->getCommits($revision, $commitCount - $version + 1, $this->cookie->repoBranch);
            $logs['commits'] = array_reverse($logs['commits'], true);

            $commitCount = $this->saveCommit($repoID, $logs, $version, $this->cookie->repoBranch);
            if($repo->SCM == 'Git' and empty($latestInDB)) $this->fixCommit($repo->id);
            $this->updateCommitCount($repoID, $commitCount);
        }
        $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repoID)->exec();
    }

    /**
     * Update bug.
     * 
     * @param  int    $bugID 
     * @param  string $title 
     * @access public
     * @return string
     */
    public function updateBug($bugID, $title)
    {
        $this->dao->update(TABLE_BUG)->set('title')->eq($title)->where('id')->eq($bugID)->exec();
        return $title;
    }

    /**
     * Update comment.
     * 
     * @param  int    $commentID 
     * @param  string $comment 
     * @access public
     * @return string
     */
    public function updateComment($commentID, $comment)
    {
        $this->dao->update(TABLE_ACTION)->set('comment')->eq($comment)->where('id')->eq($commentID)->exec();
        return $comment;
    }

    /**
     * Delete comment.
     * 
     * @param  int    $commentID 
     * @access public
     * @return void
     */
    public function deleteComment($commentID)
    {
        return $this->dao->delete()->from(TABLE_ACTION)->where('id')->eq($commentID)->exec();
    }

    /**
     * Get cache file.
     * 
     * @param  int    $repoID 
     * @param  string $path 
     * @param  int    $revision 
     * @access public
     * @return string
     */
    public function getCacheFile($repoID, $path, $revision)
    {
        $cachePath = $this->app->getCacheRoot() . '/' . 'repo';
        if(!is_dir($cachePath)) mkdir($cachePath, 0777, true);
        if(!is_writable($cachePath)) return false;
        return $cachePath . '/' . $repoID . '-' . md5("{$this->cookie->repoBranch}-$path-$revision");
    }

    /**
     * Get last review info. 
     * 
     * @param  string $entry 
     * @access public
     * @return object
     */
    public function getLastReviewInfo($entry)
    {
        return $this->dao->select('*')->from(TABLE_BUG)->where('entry')->eq($entry)->orderby('id_desc')->fetch();
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
        $commonReg = "(?:\s){0,}((?:#|:|：){0,})([0-9, ]{1,})";
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
}
