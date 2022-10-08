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
     * @param  bool   $showSeleter
     * @access public
     * @return void
     */
    public function setMenu($repos, $repoID = '', $showSeleter = true)
    {
        if(empty($repoID)) $repoID = $this->session->repoID ? $this->session->repoID : key($repos);
        if(!isset($repos[$repoID])) $repoID = key($repos);

        /* Init switcher menu. */
        $this->lang->switcherMenu = '';

        /* Check the privilege. */
        if($repoID)
        {
            $repo = $this->getRepoByID($repoID);
            if(empty($repo))
            {
                echo(js::alert($this->lang->repo->error->noFound));
                return print(js::locate('back'));
            }

            if(!$this->checkPriv($repo))
            {
                echo(js::alert($this->lang->repo->error->accessDenied));
                return print(js::locate('back'));
            }

            if(!in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) unset($this->lang->devops->menu->mr);
            if(count($repos) > 1) $this->lang->switcherMenu = $this->getSwitcher($repoID);
        }

        common::setMenuVars('devops', $repoID);
        if(!session_id()) session_start();
        $this->session->set('repoID', $repoID);
        session_write_close();
    }

    /**
     * Get switcher.
     *
     * @param  int    $repoID
     * @access public
     * @return string
     */
    public function getSwitcher($repoID)
    {
        $currentModule = $this->app->moduleName;
        $currentMethod = $this->app->methodName;
        if(!in_array($currentModule, $this->config->repo->switcherModuleList)) return '';
        if($currentModule == 'repo' and !in_array($currentMethod, $this->config->repo->switcherMethodList)) return '';

        $currentRepo     = $this->getRepoByID($repoID);
        $currentRepoName = $currentRepo->name;

        if($this->app->viewType == 'mhtml' and $repoID)
        {
            $output  = $this->lang->repo->common . $this->lang->colon;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('repo', '$repoID', '$currentModule', '$currentMethod', '')\">{$currentRepoName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('repo', 'ajaxGetDropMenu', "repoID=$repoID&module=$currentModule&method=$currentMethod");

        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentRepoName}'><span class='text'>{$currentRepoName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>'; $output .= "</div></div>";

        return $output;
    }

    /**
     * Get repo list.
     *
     * @param  int    $projectID
     * @param  string $SCM  Subversion|Git|Gitlab
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($projectID = 0, $SCM = '', $orderBy = 'id_desc', $pager = null)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF($SCM)->andWhere('SCM')->eq($SCM)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Get products. */
        $productIdList = $this->loadModel('product')->getProductIDByProject($projectID, false);
        foreach($repos as $i => $repo)
        {
            $repo->acl      = json_decode($repo->acl);
            $repo->codePath = $repo->path;
            if(!$this->checkPriv($repo))
            {
                unset($repos[$i]);
            }
            else
            {
                if($projectID)
                {
                    $hasPriv = false;
                    foreach(explode(',', $repo->product) as $productID)
                    {
                        if(isset($productIdList[$productID])) $hasPriv = true;
                    }

                    if(!$hasPriv) unset($repos[$i]);
                }
            }

            if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo);
        }

        return $repos;
    }

    /**
     * Get list by SCM.
     *
     * @param  string $scm
     * @param  string $type  all|haspriv
     * @access public
     * @return array
     */
    public function getListBySCM($scm, $type = 'all')
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('SCM')->in($scm)
            ->andWhere('synced')->eq(1)
            ->orderBy('id')
            ->fetchAll();

        foreach($repos as $i => $repo)
        {
            if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
            $repo->acl      = json_decode($repo->acl);
            $repo->codePath = $repo->path;
            if($type == 'haspriv' and !$this->checkPriv($repo)) unset($repos[$i]);
            if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo);
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
        if(!$this->checkClient()) return false;
        if(!$this->checkConnection()) return false;

        $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList) ? true : false;

        $data = fixer::input('post')
            ->setIf($isPipelineServer, 'password', $this->post->serviceToken)
            ->setIf($this->post->SCM == 'Gitlab', 'path', '')
            ->setIf($this->post->SCM == 'Gitlab', 'client', '')
            ->setIf($this->post->SCM == 'Gitlab', 'extra', $this->post->serviceProject)
            ->setIf($isPipelineServer, 'prefix', '')
            ->setIf($this->post->SCM == 'Git', 'account', '')
            ->setIf($this->post->SCM == 'Git', 'password', '')
            ->skipSpecial('path,client,account,password')
            ->setDefault('product', '')
            ->join('product', ',')
            ->get();

        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);
        if($data->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);
            $data->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }

        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->insert(TABLE_REPO)->data($data, $skip = 'serviceToken')
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->batchCheckIF($data->SCM != 'Gitlab', 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceHost,serviceProject', 'notempty')
            ->batchCheckIF($data->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = '{$data->SCM}'")
            ->checkIF($isPipelineServer, 'serviceProject', 'unique', "`SCM` = '{$data->SCM}' and `serviceHost` = '{$data->serviceHost}'")
            ->checkIF(!$isPipelineServer, 'path', 'unique', "`SCM` = '{$data->SCM}' and `serviceHost` = '{$data->serviceHost}'")
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;

        $this->rmClientVersionFile();

        $repoID = $this->dao->lastInsertID();

        if($this->post->SCM == 'Gitlab')
        {
            /* Add webhook. */
            $repo = $this->getRepoByID($repoID);
            $this->loadModel('gitlab')->addPushWebhook($repo);
        }

        return $repoID;
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
        $repo = $this->getRepoByID($id);

        $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList) ? true : false;

        $data = fixer::input('post')
            ->setIf($isPipelineServer, 'password', $this->post->serviceToken)
            ->setIf($this->post->SCM == 'Gitlab', 'path', '')
            ->setIf($this->post->SCM == 'Gitlab', 'client', '')
            ->setIf($this->post->SCM == 'Gitlab', 'extra', $this->post->serviceProject)
            ->setDefault('prefix', $repo->prefix)
            ->setIf($this->post->SCM == 'Gitlab', 'prefix', '')
            ->setDefault('client', 'svn')
            ->setDefault('product', '')
            ->skipSpecial('path,client,account,password')
            ->join('product', ',')
            ->get();

        if($data->path != $repo->path) $data->synced = 0;

        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);

        if($data->SCM == 'Subversion' and $data->path != $repo->path)
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);
            $data->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }
        elseif($data->SCM != $repo->SCM and $data->SCM == 'Git')
        {
            $data->prefix = '';
        }

        if($data->client != $repo->client and !$this->checkClient()) return false;
        if(!$this->checkConnection()) return false;

        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->update(TABLE_REPO)->data($data, $skip = 'serviceToken')
            ->batchCheck($this->config->repo->edit->requiredFields, 'notempty')
            ->batchCheckIF($data->SCM != 'Gitlab', 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceHost,serviceProject', 'notempty')
            ->batchCheckIF($data->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = '{$data->SCM}' and `id` <> $id")
            ->checkIF($isPipelineServer, 'serviceProject', 'unique', "`SCM` = '{$data->SCM}' and `serviceHost` = '{$data->serviceHost}' and `id` <> $id")
            ->checkIF(!$isPipelineServer, 'path', 'unique', "`SCM` = '{$data->SCM}' and `serviceHost` = '{$data->serviceHost}' and `id` <> $id")
            ->autoCheck()
            ->where('id')->eq($id)->exec();

        $this->rmClientVersionFile();

        if($data->SCM == 'Gitlab') $data->path = $this->getRepoByID($id)->path;

        if($repo->path != $data->path)
        {
            $this->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($id)->exec();
            $this->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($id)->exec();
            return false;
        }
        return true;
    }

    /**
     * Save repo state.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function saveState($repoID = 0, $objectID = 0)
    {
        if($repoID > 0) $this->session->set('repoID', (int)$repoID);

        $repos = $this->getRepoPairs($this->app->tab, $objectID);
        if($repoID == 0 and $this->session->repoID == '')
        {
            $this->session->set('repoID', key($repos));
        }

        if(!isset($repos[$this->session->repoID]))
        {
            $this->session->set('repoID', key($repos));
        }

        return $this->session->repoID;
    }

    /**
     * Get repo pairs.
     *
     * @param  string $type  project|execution|repo
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getRepoPairs($type, $projectID = 0)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->fetchAll();

        /* Get products. */
        $productIdList = ($type == 'project' or $type == 'execution') ? $this->loadModel('product')->getProductIDByProject($projectID, false) : array();

        $repoPairs = array();
        foreach($repos as $repo)
        {
            $repo->acl = json_decode($repo->acl);
            $scm = $repo->SCM == 'Subversion' ? 'svn' : strtolower($repo->SCM);
            if($this->checkPriv($repo))
            {
                if(($type == 'project' or $type == 'execution') and $projectID)
                {
                    foreach($productIdList as $productID)
                    {
                        if(strpos(",$repo->product,", ",$productID,") !== false) $repoPairs[$repo->id] = "[{$scm}] " . $repo->name;
                    }
                }
                else
                {
                    $repoPairs[$repo->id] = "[{$scm}] " . $repo->name;
                }
            }
        }

        return $repoPairs;
    }

    /**
     * Get repos group by repo type.
     *
     * @param  string type
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getRepoGroup($type, $projectID = 0)
    {
        $repoPairs = $this->getRepoPairs($type, $projectID);

        $repos = array();
        foreach($this->lang->repo->scmList as $scmType => $scm) $repos[$scmType] = array();

        foreach($repoPairs as $id => $repo)
        {
            if(strpos($repo, '[gitlab]') !== false)
            {
                $repo = str_replace('[gitlab]', '', $repo);
                $repos['Gitlab'][$id] = $repo;
            }
            elseif(strpos($repo, '[gogs]') !== false)
            {
                $repo = str_replace('[gogs]', '', $repo);
                $repos['Gogs'][$id] = $repo;
            }
            elseif(strpos($repo, '[gitea]') !== false)
            {
                $repo = str_replace('[gitea]', '', $repo);
                $repos['Gitea'][$id] = $repo;
            }
            elseif(strpos($repo, '[svn]') !== false)
            {
                $repo = str_replace('[svn]', '', $repo);
                $repos['SVN'][$id] = $repo;
            }
            elseif(strpos($repo, '[git]') !== false)
            {
                $repo = str_replace('[git]', '', $repo);
                $repos['Git'][$id] = $repo;
            }
        }
        return $repos;
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
        $repo->codePath = $repo->path;
        if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo);
        $repo->acl = json_decode($repo->acl);
        return $repo;
    }

    /**
     * Get repo by url.
     *
     * @param  string $url
     * @access public
     * @return array
     */
    public function getRepoByUrl($url)
    {
        if(empty($url)) return array('result' => 'fail', 'message' => 'Url is empty.');

        $parsedUrl = parse_url($url);

        $isSSH   = $parsedUrl['scheme'] == 'ssh';
        $baseURL = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['port']) ? ":{$parsedUrl['port']}" : '');

        /* Get gitlabs by URL. */
        $gitlabs = $this->dao->select('*')->from(TABLE_PIPELINE)->where('type')->eq('gitlab')
            ->beginIF($isSSH)->andWhere('url')->like("%{$parsedUrl['host']}%")->fi()
            ->beginIF(!$isSSH)->andWhere('url')->eq($baseURL)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');

        /* Convert to id by url. */
        $this->loadModel('gitlab');
        $url     = str_replace('https://', 'http://', strtolower($url));
        $matches = array();
        foreach($gitlabs as $gitlabID => $gitlab)
        {
            $matched = new stdclass();
            $matched->gitlab  = 0;
            $matched->project = 0;

            $projects = $this->gitlab->apiGetProjects($gitlabID);
            foreach($projects as $project)
            {
                $urlToRepo = str_replace('https://', 'http://', strtolower($project->http_url_to_repo));
                if((!$isSSH and $urlToRepo == $url) or ($isSSH and strtolower($project->ssh_url_to_repo) == $url))
                {
                    $matched->gitlab  = $gitlabID;
                    $matched->project = $project->id;

                    $matches[] = $matched;
                }
            }
        }
        if(empty($matches)) return array('result' => 'fail', 'message' => 'No matched gitlab.');

        $conditions = array();
        foreach($matches as $matched) $conditions[] = "(`client`='$matched->gitlab' and `path`='$matched->project')";
        $conditions = '(' . join(' OR ', $conditions). ')';

        $matchedRepos = $this->dao->select('*')->from(TABLE_REPO)->where('SCM')->eq('Gitlab')
            ->andWhere($conditions)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
        if(empty($matchedRepos)) return array('result' => 'fail', 'message' => 'No matched gitlab.');

        $matchedRepo = '';
        foreach($matchedRepos as $repo)
        {
            if(!empty($repo->preMerge))
            {
                $matchedRepo = $repo;
                break;
            }
        }
        if(empty($matchedRepo)) return array('result' => 'fail', 'message' => 'Matched gitlab is not open pre merge.');
        if(empty($matchedRepo->job)) return array('result' => 'fail', 'message' => 'No linked job.');

        $job = $this->dao->select('*')->from(TABLE_JOB)->where('id')->eq($matchedRepo->job)->andWhere('deleted')->eq(0)->fetch();
        if(empty($job)) return array('result' => 'fail', 'message' => 'Linked job is not exists.');

        $matchedRepo->job = $job;
        return array('result' => 'success', 'data' => $matchedRepo);
    }

    /**
     * Get repo list by url.
     *
     * @param  string $url
     * @access public
     * @return array
     */
    public function getRepoListByUrl($url = '')
    {
        if(empty($url)) return array('status' => 'fail', 'message' => 'Url is empty.');

        $parsedUrl = parse_url($url);

        $isSSH   = $parsedUrl['scheme'] == 'ssh';
        $baseURL = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['port']) ? ":{$parsedUrl['port']}" : '');

        /* Get gitlabs by URL. */
        $gitlabs = $this->dao->select('*')->from(TABLE_PIPELINE)->where('type')->eq('gitlab')
            ->beginIF($isSSH)->andWhere('url')->like("%{$parsedUrl['host']}%")->fi()
            ->beginIF(!$isSSH)->andWhere('url')->eq($baseURL)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');

        /* Convert to id by url. */
        $this->loadModel('gitlab');
        $url     = str_replace('https://', 'http://', strtolower($url));
        $matches = array();
        foreach($gitlabs as $gitlabID => $gitlab)
        {
            $matched = new stdclass();
            $matched->gitlab  = 0;
            $matched->project = 0;

            $projects = $this->gitlab->apiGetProjects($gitlabID);
            foreach($projects as $project)
            {
                $urlToRepo = str_replace('https://', 'http://', strtolower($project->http_url_to_repo));
                if((!$isSSH and $urlToRepo == $url) or ($isSSH and strtolower($project->ssh_url_to_repo) == $url))
                {
                    $matched->gitlab  = $gitlabID;
                    $matched->project = $project->id;

                    $matches[] = $matched;
                }
            }
        }
        if(empty($matches)) return array('status' => 'fail', 'message' => 'No matched gitlab.');

        $conditions = array();
        foreach($matches as $matched) $conditions[] = "(`client`='$matched->gitlab' and `path`='$matched->project')";
        $conditions = '(' . join(' OR ', $conditions). ')';

        $matchedRepos = $this->dao->select('*')->from(TABLE_REPO)->where('SCM')->eq('Gitlab')
            ->andWhere($conditions)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
        foreach($matchedRepos as $key => $repo)
        {
            if(!$this->checkPriv($repo)) unset($matchedRepos[$key]);
        }
        if(empty($matchedRepos)) return array('status' => 'fail', 'message' => 'No matched gitlab.');

        return array('status' => 'success', 'repos' => $matchedRepos);
    }

    /**
     * Get by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq(0)->andWhere('id')->in($idList)->fetchAll();
        foreach($repos as $i => $repo)
        {
            if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
            $repo->acl = json_decode($repo->acl);
        }

        return $repos;
    }

    /**
     * Get git branches.
     *
     * @param  object  $repo
     * @param  bool    $printLabel
     * @access public
     * @return array
     */
    public function getBranches($repo, $printLabel = false)
    {
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        $branches = $this->scm->branch();

        if($printLabel)
        {
            foreach($branches as &$branch) $branch = 'Branch::' . $branch;
        }

        return $branches;
    }

    /**
     * Get commits.
     *
     * @param  object $repo
     * @param  string $entry
     * @param  string $revision
     * @param  string $type
     * @param  object $pager
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getCommits($repo, $entry, $revision = 'HEAD', $type = 'dir', $pager = null, $begin = 0, $end = 0)
    {
        $entry = ltrim($entry, '/');
        $entry = $repo->prefix . (empty($entry) ? '' : '/' . $entry);

        $repoID       = $repo->id;
        $revisionTime = $this->dao->select('time')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($revision != 'HEAD')->andWhere('t1.revision')->eq($revision)->fi()
            ->beginIF($repo->SCM != 'Subversion' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
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
                ->beginIF($revisionTime)->andWhere('t2.`time`')->le($revisionTime)->fi()
                ->andWhere('left(t2.comment, 12)')->ne('Merge branch')
                ->beginIF($repo->SCM != 'Subversion' and $this->cookie->repoBranch)->andWhere('t3.branch')->eq($this->cookie->repoBranch)->fi()
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
            ->beginIF($revisionTime)->andWhere('t1.`time`')->le($revisionTime)->fi()
            ->andWhere('left(t1.comment, 12)')->ne('Merge branch')
            ->beginIF($repo->SCM != 'Subversion' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->beginIF($entry != '/' and !empty($entry))->andWhere('t1.id')->in($historyIdList)->fi()
            ->beginIF($begin)->andWhere('t1.time')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.time')->le($end)->fi()
            ->orderBy('time desc');
        if($entry == '/' or empty($entry))$comments->page($pager, 't1.id');
        $comments = $comments->fetchAll('revision');

        foreach($comments as $repoComment)
        {
            $repoComment->originalComment = $repoComment->comment;
            $repoComment->comment         = $this->replaceCommentLink($repoComment->comment);
        }
        return $comments;
    }

    /**
     * Get latest commit.
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getLatestCommit($repoID)
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
     * Get products by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getProductsByRepo($repoID)
    {
        $repo = $this->getRepoByID($repoID);
        if(empty($repo)) return array();

        return $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('id')->in($repo->product)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
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
            $commit->comment = htmlSpecialString($commit->comment);
            $this->dao->insert(TABLE_REPOHISTORY)->data($commit)->exec();
            if(!dao::isError())
            {
                $commitID = $this->dao->lastInsertID();
                if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($commitID)->set('branch')->eq($branch)->exec();
                if(!empty($logs['files']))
                {
                    foreach($logs['files'][$i] as $file)
                    {
                        $parentPath = dirname($file->path);

                        $file->parent   = $parentPath == '\\' ? '/' : $parentPath;
                        $file->revision = $commitID;
                        $file->repo     = $repoID;
                        $this->dao->insert(TABLE_REPOFILES)->data($file)->exec();
                    }
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
     * Save One Commit.
     *
     * @param  int    $repoID
     * @param  object $commit
     * @param  int    $version
     * @param  string $branch
     * @access public
     * @return int
     */
    public function saveOneCommit($repoID, $commit, $version, $branch = '')
    {
        $existsRevision  = $this->dao->select('id,revision')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($commit->revision)->fetch();
        if($existsRevision)
        {
            if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($existsRevision->id)->set('branch')->eq($branch)->exec();
            return $version;
        }

        $history = new stdclass();
        $history->repo      = $repoID;
        $history->revision  = $commit->revision;
        $history->committer = $commit->committer;
        $history->time      = $commit->time;
        $history->commit    = $version;
        $history->comment   = htmlSpecialString($commit->comment);
        $this->dao->insert(TABLE_REPOHISTORY)->data($history)->exec();
        if(!dao::isError())
        {
            $commitID = $this->dao->lastInsertID();
            if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($commitID)->set('branch')->eq($branch)->exec();
            foreach($commit->change as $file => $info)
            {
                $parentPath = dirname($file);

                $repoFile = new stdclass();
                $repoFile->repo     = $repoID;
                $repoFile->revision = $commitID;
                $repoFile->path     = $file;
                $repoFile->parent   = $parentPath == '\\' ? '/' : $parentPath;
                $repoFile->type     = $info['kind'];
                $repoFile->action   = $info['action'];
                $this->dao->insert(TABLE_REPOFILES)->data($repoFile)->exec();
            }
            $version++;
        }
        else
        {
            dao::getError();
        }

        return $version;
    }

    /**
     * Save exists log branch.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function saveExistCommits4Branch($repoID, $branch)
    {
        $lastBranchLog = $this->dao->select('t1.time')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t2.branch')->eq($branch)
            ->orderBy('time')
            ->limit(1)
            ->fetch();
        if(empty($lastBranchLog)) return false;

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
     * Get unsync commits
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getUnsyncedCommits($repo)
    {
        $repoID   = $repo->id;
        $lastInDB = $this->getLatestCommit($repoID);

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $logs = $scm->log('', $lastInDB ? $lastInDB->revision : 0);
        if(empty($logs)) return false;

        /* Process logs. */
        $logs = array_reverse($logs, true);
        foreach($logs as $i => $log)
        {
            if($lastInDB->revision == $log->revision)
            {
                unset($logs[$i]);
                continue;
            }

            $log->author = $log->committer;
            $log->msg    = $log->comment;
            $log->date   = $log->time;

            /* Process files. */
            $log->files = array();
            foreach($log->change as $file => $info) $log->files[$info['action']][] = $file;
        }
        return $logs;
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
     * @param  string $viewType
     * @param  bool   $onlybody
     * @access public
     * @return string
     */
    public function createLink($method, $params = '', $viewType = '', $onlybody = false)
    {
        if($this->config->requestType == 'GET') return helper::createLink('repo', $method, $params, $viewType, $onlybody);

        $parsedParams = array();
        parse_str($params, $parsedParams);

        $pathParams = '';
        $pathKey    = 'path';
        if(isset($parsedParams['entry'])) $pathKey = 'entry';
        if(isset($parsedParams['file']))  $pathKey = 'file';
        if(isset($parsedParams['root']))  $pathKey = 'root';
        if(isset($parsedParams[$pathKey]))
        {
            $pathParams = 'repoPath=' . $parsedParams[$pathKey];
            $parsedParams[$pathKey] = '';
        }

        $params = http_build_query($parsedParams);
        $link   = helper::createLink('repo', $method, $params, $viewType, $onlybody);
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
        session_start();
        $uri = $this->app->getURI(true);
        if(!empty($_GET) and $this->config->requestType == 'PATH_INFO') $uri .= (strpos($uri, '?') === false ? '?' : '&') . http_build_query($_GET);

        $backKey = 'repo' . ucfirst(strtolower($type));
        $this->session->set($backKey, $uri);

        if($type == 'list') unset($_SESSION['repoView']);
        if($withOtherModule)
        {
            $this->session->set('bugList', $uri, 'qa');
            $this->session->set('taskList', $uri, 'execution');
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
        setcookie("repoBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
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
        if(empty($path)) return $path;
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
        if(empty($path)) return $path;
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
     * Check svn/git client.
     *
     * @access public
     * @return bool
     */
    public function checkClient()
    {
        if($this->post->SCM == 'Gitlab') return true;
        if(!$this->config->features->checkClient) return true;

        if(!$this->post->client)
        {
            dao::$errors['client'] = sprintf($this->lang->error->notempty, $this->lang->repo->client);
            return false;
        }

        if(strpos($this->post->client, ' '))
        {
            dao::$errors['client'] = $this->lang->repo->error->clientPath;
            return false;
        }

        $clientVersionFile = $this->session->clientVersionFile;
        if(empty($clientVersionFile))
        {
            $clientVersionFile = $this->app->getLogRoot() . uniqid('version_') . '.log';

            session_start();
            $this->session->set('clientVersionFile', $clientVersionFile);
            session_write_close();
        }

        if(file_exists($clientVersionFile)) return true;

        $cmd = $this->post->client . " --version > $clientVersionFile";
        dao::$errors['client'] = sprintf($this->lang->repo->error->safe, $clientVersionFile, $cmd);

        return false;
    }


    /**
     * remove client version file.
     *
     * @access public
     * @return void
     */
    public function rmClientVersionFile()
    {
        $clientVersionFile = $this->session->clientVersionFile;
        if($clientVersionFile)
        {
            session_start();
            $this->session->set('clientVersionFile', $clientVersionFile);
            session_write_close();

            if(file_exists($clientVersionFile)) unlink($clientVersionFile);
        }
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
            /* Get svn version. */
            $versionCommand = "$client --version --quiet 2>&1";
            exec($versionCommand, $versionOutput, $versionResult);
            if($versionResult)
            {
                $message = sprintf($this->lang->repo->error->output, $versionCommand, $versionResult, join("<br />", $versionOutput));
                dao::$errors['client'] = $this->lang->repo->error->cmd . "<br />" . nl2br($message);
                return false;
            }
            $svnVersion = end($versionOutput);

            $path = '"' . str_replace(array('%3A', '%2F', '+'), array(':', '/', ' '), urlencode($path)) . '"';
            if(stripos($path, 'https://') === 1 or stripos($path, 'svn://') === 1)
            {
                if(version_compare($svnVersion, '1.6', '<'))
                {
                    dao::$errors['client'] = $this->lang->repo->error->version;
                    return false;
                }

                $command = "$client info --username $account --password $password --non-interactive --trust-server-cert-failures=cn-mismatch --trust-server-cert --no-auth-cache $path 2>&1";
                if(version_compare($svnVersion, '1.9', '<')) $command = "$client info --username $account --password $password --non-interactive --trust-server-cert --no-auth-cache $path 2>&1";
            }
            else if(stripos($path, 'file://') === 1)
            {
                $command = "$client info --non-interactive --no-auth-cache $path 2>&1";
            }
            else
            {
                $command = "$client info --username $account --password $password --non-interactive --no-auth-cache $path 2>&1";
            }

            exec($command, $output, $result);
            if($result)
            {
                $message = sprintf($this->lang->repo->error->output, $command, $result, join("<br />", $output));
                if(stripos($message, 'Expected FS format between') !== false and strpos($message, 'found format') !== false)
                {
                    dao::$errors['client'] = $this->lang->repo->error->clientVersion;
                    return false;
                }
                if(preg_match('/[^\:\/A-Za-z0-9_\-\'\"\.]/', $path))
                {
                    dao::$errors['encoding'] = $this->lang->repo->error->encoding . "<br />" . nl2br($message);
                    return false;
                }

                dao::$errors['submit'] = $this->lang->repo->error->connect . "<br>" . nl2br($message);
                return false;
            }
        }
        elseif(in_array($scm, array('Gitea', 'Gogs')))
        {
            if($this->post->name != '' and $this->post->serviceProject != '')
            {
                $module  = strtolower($scm);
                $project = $this->loadModel($module)->apiGetSingleProject($this->post->serviceHost, $this->post->serviceProject);
                if(isset($project->tokenCloneUrl))
                {
                    $path = $this->app->getAppRoot() . 'www/data/repo/' . $this->post->name . '_' . $module;
                    if(!realpath($path))
                    {
                        $cmd = 'git clone --progress -v "' . $project->tokenCloneUrl . '" "' . $path . '"  > "' . $this->app->getTmpRoot() . "log/clone.progress.$module.{$this->post->name}.log\" 2>&1 &";
                        if(PHP_OS == 'WINNT') $cmd = "start /b $cmd";
                        exec($cmd);
                    }
                    $_POST['path'] = $path;
                }
                else
                {
                    dao::$errors['serviceProject'] = $this->lang->repo->error->noCloneAddr;
                    return false;
                }
            }
        }
        elseif($scm == 'Git')
        {
            if(!is_dir($path))
            {
                dao::$errors['path'] = sprintf($this->lang->repo->error->noFile, $path);
                return false;
            }

            if(!chdir($path))
            {
                if(!is_executable($path))
                {
                    dao::$errors['path'] = sprintf($this->lang->repo->error->noPriv, $path);
                    return false;
                }
                dao::$errors['path'] = $this->lang->repo->error->path;
                return false;
            }

            $command = "$client tag 2>&1";
            exec($command, $output, $result);
            if($result)
            {
                dao::$errors['submit'] = $this->lang->repo->error->connect . "<br />" . sprintf($this->lang->repo->error->output, $command, $result, join("<br />", $output));
                return false;
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
        $rules   = $this->processRules();
        $stories = array();
        $tasks   = array();
        $bugs    = array();
        $storyReg = '/' . $rules['storyReg'] . '/i';
        $taskReg  = '/' . $rules['taskReg'] . '/i';
        $bugReg   = '/' . $rules['bugReg'] . '/i';
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
        if(preg_match_all($storyReg, $comment, $result))
        {
            $storyLinks = $this->addLink($result, 'story');
            foreach($storyLinks as $search => $replace) $comment = str_replace($search, $replace, $comment);
        }
        return $comment;
    }

    /**
     * Add link.
     *
     * @param  array  $matches
     * @param  string $method
     * @access public
     * @return array
     */
    public function addLink($matches, $method)
    {
        if(empty($matches)) return null;
        $replaceLines = array();
        foreach($matches[3] as $i => $idList)
        {
            $links = $matches[2][$i] . ' ' . $matches[4][$i];
            preg_match_all('/\d+/', $idList, $idMatches);
            foreach($idMatches[0] as $id)
            {
                $links .= html::a(helper::createLink($method, 'view', "id=$id"), $id) . $matches[6][$i];
            }
            $replaceLines[$matches[0][$i]] = rtrim($links, $matches[6][$i]);
        }
        return $replaceLines;
    }

    /**
     * Parse the comment of git and svn, extract object id list from it.
     *
     * @param  string    $comment
     * @access public
     * @return array
     */
    public function parseComment($comment)
    {
        $rules   = $this->processRules();
        $stories = array();
        $tasks   = array();
        $bugs    = array();
        $actions = array();

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

        preg_match_all("/{$rules['resolveBugReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[4] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id)
                {
                    $bugs[$id] = $id;
                    $actions['bug'][$id]['resolve'] = array();
                }
            }
        }

        preg_match_all("/{$rules['taskReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $tasks[$id] = $id;
            }
        }

        preg_match_all("/{$rules['bugReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $bugs[$id] = $id;
            }
        }

        preg_match_all("/{$rules['storyReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $i => $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $stories[$id] = $id;
            }
        }

        return array('stories' => $stories, 'tasks' => $tasks, 'bugs' => $bugs, 'actions' => $actions);
    }

    /**
     * Iconv Comment.
     *
     * @param  string $comment
     * @param  string $encodings
     * @access public
     * @return string
     */
    public function iconvComment($comment, $encodings)
    {
        /* Get encodings. */
        if($encodings == '') return $comment;
        $encodings = explode(',', $encodings);

        /* Try convert. */
        foreach($encodings as $encoding)
        {
            if($encoding == 'utf-8') continue;
            $result = helper::convertEncoding($comment, $encoding);
            if($result) return $result;
        }

        return $comment;
    }

    /**
     * Process rules to REG.
     *
     * @access public
     * @return array
     */
    public function processRules()
    {
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);
        $rules = $this->config->repo->rules;

        $idMarks       = str_replace(';', '|', preg_replace('/([^;])/', '\\\\\1', trim($rules['id']['mark'], ';')));
        $idSplits      = str_replace(';', '|', preg_replace('/([^;])/', '\\\\\1', trim($rules['id']['split'], ';')));
        $costs         = str_replace(';', '|', trim($rules['task']['consumed'], ';'));
        $costMarks     = str_replace(';', '|', preg_replace('/([^;])/', '\\\\\1', trim($rules['mark']['consumed'], ';')));
        $lefts         = str_replace(';', '|', trim($rules['task']['left'], ';'));
        $leftMarks     = str_replace(';', '|', preg_replace('/([^;])/', '\\\\\1', trim($rules['mark']['left'], ';')));
        $storyModule   = str_replace(';', '|', trim($rules['module']['story'], ';'));
        $taskModule    = str_replace(';', '|', trim($rules['module']['task'], ';'));
        $bugModule     = str_replace(';', '|', trim($rules['module']['bug'], ';'));
        $costUnit      = str_replace(';', '|', trim($rules['unit']['consumed'], ';'));
        $leftUnit      = str_replace(';', '|', trim($rules['unit']['left'], ';'));
        $startAction   = str_replace(';', '|', trim($rules['task']['start'], ';'));
        $finishAction  = str_replace(';', '|', trim($rules['task']['finish'], ';'));
        $effortAction  = str_replace(';', '|', trim($rules['task']['logEfforts'], ';'));
        $resolveAction = str_replace(';', '|', trim($rules['bug']['resolve'], ';'));

        $storyReg = "(($storyModule) *(({$idMarks})[0-9]+(({$idSplits})[0-9]+)*))";
        $taskReg  = "(($taskModule) *(({$idMarks})[0-9]+(({$idSplits})[0-9]+)*))";
        $bugReg   = "(($bugModule) *(({$idMarks})[0-9]+(({$idSplits})[0-9]+)*))";
        $costReg  = "($costs) *(($costMarks)([0-9]+(\.?[0-9]+)?)($costUnit))";
        $leftReg  = "($lefts) *(($leftMarks)([0-9]+(\.?[0-9]+)?)($leftUnit))";

        $startTaskReg  = "({$startAction}) *{$taskReg}.*$costReg.*$leftReg";
        $effortTaskReg = "({$effortAction}) *{$taskReg}.*$costReg.*$leftReg";
        $finishTaskReg = "({$finishAction}) *{$taskReg}.*$costReg";
        $resolveBugReg = "({$resolveAction}) *{$bugReg}";

        $reg = array();
        $reg['storyReg']      = $storyReg;
        $reg['taskReg']       = $taskReg;
        $reg['bugReg']        = $bugReg;
        $reg['costReg']       = $costReg;
        $reg['leftReg']       = $leftReg;
        $reg['startTaskReg']  = $startTaskReg;
        $reg['effortTaskReg'] = $effortTaskReg;
        $reg['finishTaskReg'] = $finishTaskReg;
        $reg['resolveBugReg'] = $resolveBugReg;
        return $reg;
    }

    /**
     * Save action to pms.
     *
     * @param  array    $objects
     * @param  object   $log
     * @param  string   $repoRoot
     * @param  string   $encodings
     * @param  string   $scm
     * @param  array    $gitlabAccountPairs
     * @access public
     * @return void
     */
    public function saveAction2PMS($objects, $log, $repoRoot = '', $encodings = 'utf-8', $scm = 'svn', $gitlabAccountPairs = array())
    {
        if(isset($gitlabAccountPairs[$log->author]) and $gitlabAccountPairs[$log->author])
        {
            $log->author = $gitlabAccountPairs[$log->author];
        }
        else
        {
            $commiters   = $this->loadModel('user')->getCommiters('account');
            $log->author = zget($commiters, $log->author);
        }

        if(isset($this->app->user))
        {
            $account = $this->app->user->account;
            $this->app->user->account = $log->author;
        }

        $action  = new stdclass();
        $action->actor = $log->author;
        $action->date  = $log->date;
        $action->extra = $scm == 'svn' ? $log->revision : substr($log->revision, 0, 10);

        $comment = htmlSpecialString($this->iconvComment($log->msg, $encodings));

        $this->loadModel('action');
        $actions = $objects['actions'];
        if(isset($actions['task']))
        {
            $this->loadModel('task');
            $productsAndExecutions = $this->getTaskProductsAndExecutions($objects['tasks']);
            foreach($actions['task'] as $taskID => $taskActions)
            {
                $task = $this->task->getById($taskID);
                if(empty($task)) continue;

                $action->objectType = 'task';
                $action->objectID   = $taskID;
                $action->product    = $productsAndExecutions[$taskID]['product'];
                $action->execution  = $productsAndExecutions[$taskID]['execution'];
                $action->comment    = $this->lang->repo->revisionA . ': #' . $action->extra . "<br />" . $comment;
                foreach($taskActions as $taskAction => $params)
                {
                    $_POST = array();
                    foreach($params as $field => $param) $this->post->set($field, $param);

                    if($taskAction == 'start' and $task->status == 'wait')
                    {
                        $this->post->set('consumed', $this->post->consumed + $task->consumed);
                        $this->post->set('realStarted', date('Y-m-d'));
                        $changes = $this->task->start($taskID);
                        foreach($this->createActionChanges($log, $repoRoot, $scm) as $change) $changes[] = $change;
                        if($changes)
                        {
                            $action->action = $this->post->left == 0 ? 'finished' : 'started';
                            $this->saveRecord($action, $changes);
                        }
                    }
                    elseif($taskAction == 'effort' and in_array($task->status, array('wait', 'pause', 'doing')))
                    {
                        unset($_POST['consumed']);
                        unset($_POST['left']);

                        $_POST['id'][1]         = 1;
                        $_POST['dates'][1]      = date('Y-m-d');
                        $_POST['consumed'][1]   = $params['consumed'];
                        $_POST['left'][1]       = $params['left'];
                        $_POST['objectType'][1] = 'task';
                        $_POST['objectID'][1]   = $taskID;
                        $_POST['work'][1]       = str_replace('<br />', "\n", $action->comment);
                        if(is_dir($this->app->getModuleRoot() . 'effort'))
                        {
                            $this->loadModel('effort')->batchCreate();
                        }
                        else
                        {
                            $this->task->recordEstimate($taskID);
                        }

                        $action->action     = $scm == 'svn' ? 'svncommited' : 'gitcommited';
                        $action->objectType = 'task';
                        $action->objectID   = $taskID;
                        $action->product    = $productsAndExecutions[$taskID]['product'];
                        $action->execution  = $productsAndExecutions[$taskID]['execution'];

                        $changes = $this->createActionChanges($log, $repoRoot, $scm);
                        $this->saveRecord($action, $changes);
                    }
                    elseif($taskAction == 'finish' and in_array($task->status, array('wait', 'pause', 'doing')))
                    {
                        $this->post->set('finishedDate', date('Y-m-d'));
                        $this->post->set('realStarted', date('Y-m-d'));
                        $this->post->set('currentConsumed', $this->post->consumed);
                        $this->post->set('consumed', $this->post->consumed + $task->consumed);
                        $changes = $this->task->finish($taskID, 'DEVOPS');
                        foreach($this->createActionChanges($log, $repoRoot, $scm) as $change) $changes[] = $change;
                        if($changes)
                        {
                            $action->action = 'finished';
                            $this->saveRecord($action, $changes);
                        }
                    }
                }
                unset($objects['tasks'][$taskID]);
            }
        }
        if(isset($actions['bug']))
        {
            $this->loadModel('bug');
            $productsAndExecutions = $this->getBugProductsAndExecutions($objects['bugs']);
            foreach($actions['bug'] as $bugID => $bugActions)
            {
                $bug = $this->bug->getByID($bugID);
                if(empty($bug)) continue;

                $action->objectType = 'bug';
                $action->objectID   = $bugID;
                $action->product    = $productsAndExecutions[$bugID]->product;
                $action->execution  = $productsAndExecutions[$bugID]->execution;
                foreach($bugActions as $bugAction => $params)
                {
                    $_POST = array();
                    if($bugAction == 'resolve' and $bug->status == 'active')
                    {
                        $this->post->set('resolvedBuild', 'trunk');
                        $this->post->set('resolution', 'fixed');
                        $changes = $this->bug->resolve($bugID);
                        foreach($this->createActionChanges($log, $repoRoot, $scm) as $change) $changes[] = $change;
                        if($changes)
                        {
                            $action->action = 'resolved';
                            $action->extra  = 'fixed';
                            $this->saveRecord($action, $changes);
                        }
                    }
                }
                unset($objects['bugs'][$bugID]);
            }
        }

        $action->action = $scm == 'svn' ? 'svncommited' : 'gitcommited';
        $changes = $this->createActionChanges($log, $repoRoot, $scm);

        if($objects['stories'])
        {
            $stories = $this->loadModel('story')->getByList($objects['stories']);
            foreach($objects['stories'] as $storyID)
            {
                $storyID = (int)$storyID;
                if(!isset($stories[$storyID])) continue;

                $action->objectType = 'story';
                $action->objectID   = $storyID;
                $action->product    = $stories[$storyID]->product;
                $action->execution  = 0;

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['tasks'])
        {
            $productsAndExecutions = $this->getTaskProductsAndExecutions($objects['tasks']);
            foreach($objects['tasks'] as $taskID)
            {
                $taskID = (int)$taskID;
                if(!isset($productsAndExecutions[$taskID])) continue;

                $action->objectType = 'task';
                $action->objectID   = $taskID;
                $action->product    = $productsAndExecutions[$taskID]['product'];
                $action->execution  = $productsAndExecutions[$taskID]['execution'];

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['bugs'])
        {
            $productsAndExecutions = $this->getBugProductsAndExecutions($objects['bugs']);
            foreach($objects['bugs'] as $bugID)
            {
                $bugID = (int)$bugID;
                if(!isset($productsAndExecutions[$bugID])) continue;

                $action->objectType = 'bug';
                $action->objectID   = $bugID;
                $action->product    = $productsAndExecutions[$bugID]->product;
                $action->execution  = $productsAndExecutions[$bugID]->execution;

                $this->saveRecord($action, $changes);
            }
        }

        if(isset($this->app->user)) $this->app->user->account = $account;
    }

    /**
     * Save an action to pms.
     *
     * @param  object $action
     * @param  object $log
     * @access public
     * @return bool
     */
    public function saveRecord($action, $changes)
    {
        /* Remove sql error. */
        dao::getError();

        $record = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($action->objectType)
            ->andWhere('objectID')->eq($action->objectID)
            ->andWhere('extra')->eq($action->extra)
            ->andWhere('action')->eq($action->action)
            ->fetch();
        if($record)
        {
            $this->dao->update(TABLE_ACTION)->data($action)->where('id')->eq($record->id)->exec();
            if($changes)
            {
                $historyIdList = $this->dao->findByAction($record->id)->from(TABLE_HISTORY)->fetchPairs('id', 'id');
                if($historyIdList) $this->dao->delete()->from(TABLE_HISTORY)->where('id')->in($historyIdList)->exec();
                $this->loadModel('action')->logHistory($record->id, $changes);
            }
        }
        else
        {
            $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
            if($changes)
            {
                $actionID = $this->dao->lastInsertID();
                $this->loadModel('action')->logHistory($actionID, $changes);
            }
        }
    }

    /**
     * Create changes for action from a log.
     *
     * @param  object    $log
     * @param  string    $repoRoot
     * @access public
     * @return array
     */
    public function createActionChanges($log, $repoRoot, $scm = 'svn')
    {
        if(!$log->files) return array();
        $diff = '';

        $oldSelf = $this->server->PHP_SELF;
        $this->server->set('PHP_SELF', $this->config->webRoot, '', false, true);

        if(!$repoRoot) $repoRoot = $this->repoRoot;

        foreach($log->files as $action => $actionFiles)
        {
            foreach($actionFiles as $file)
            {
                $catLink  = trim(html::a($this->buildURL('cat',  $repoRoot . $file, $log->revision, $scm), 'view', '', "class='iframe' data-width='960'"));
                $diffLink = trim(html::a($this->buildURL('diff', $repoRoot . $file, $log->revision, $scm), 'diff', '', "class='iframe' data-width='960'"));
                $diff .= $action . " " . $file . " $catLink ";
                $diff .= $action == 'M' ? "$diffLink\n" : "\n" ;
            }
        }
        $change = new stdclass();
        $change->field = $scm == 'svn' ? 'subversion' : 'git';
        $change->old   = '';
        $change->new   = '';
        $change->diff  = trim($diff);
        $changes[] = $change;

        $this->server->set('PHP_SELF', $oldSelf);
        return $changes;
    }

    /**
     * Get products and executions of tasks.
     *
     * @param  array    $tasks
     * @access public
     * @return array
     */
    public function getTaskProductsAndExecutions($tasks)
    {
        $records = array();
        $products = $this->dao->select('t1.id,t1.execution,t2.product')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.execution = t2.project')
            ->where('t1.id')->in($tasks)->fetchGroup('id','product');

        $executions = $this->dao->select('id, execution')->from(TABLE_TASK)->where('id')->in($tasks)->fetchPairs();

        foreach($executions as $taskID => $executionID)
        {
            $record = array();
            $record['execution'] = $executionID;
            $record['product']   = isset($products[$taskID]) ? "," . join(',', array_keys($products[$taskID])) . "," : ",0,";
            $records[$taskID] = $record;
        }
        return $records;
    }

    /**
     * Get products and executions of bugs.
     *
     * @param  array    $bugs
     * @access public
     * @return array
     */
    public function getBugProductsAndExecutions($bugs)
    {
        $records = $this->dao->select('id, execution, product')->from(TABLE_BUG)->where('id')->in($bugs)->fetchAll('id');
        foreach($records as $record) $record->product = ",{$record->product},";
        return $records;
    }

    /**
     * Build URL.
     *
     * @param  string $methodName
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string
     */
    public function buildURL($methodName, $url, $revision, $scm = 'svn')
    {
        $buildedURL  = helper::createLink($scm, $methodName, "url=&revision=$revision", 'html');
        $buildedURL .= strpos($buildedURL, '?') === false ? '?' : '&';
        $buildedURL .= 'repoUrl=' . helper::safe64Encode($url);

        return $buildedURL;
    }

    /**
     * Process git service repo.
     *
     * @param  object    $repo
     * @access public
     * @return object
     */
    public function processGitService($repo)
    {
        $service = $this->loadModel('pipeline')->getByID($repo->serviceHost);
        if($repo->SCM == 'Gitlab')
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($repo->serviceHost, $repo->serviceProject);

            $repo->path     = $service ? sprintf($this->config->repo->{$service->type}->apiPath, $service->url, $repo->serviceProject) : '';
            $repo->client   = $service ? $service->url : '';
            $repo->password = $service ? $service->token : '';
            $repo->codePath = $project ? $project->web_url : $repo->path;
        }
        elseif(in_array($repo->SCM, array('Gitea', 'Gogs')))
        {
            $repo->codePath = $service ? "{$service->url}/{$repo->serviceProject}" : $repo->path;
        }
        $repo->gitService = $repo->serviceHost;
        $repo->project    = $repo->serviceProject;
        return $repo;
    }

    /**
     * Get repositories which scm is GitLab and specified gitlabID and projectID.
     *
     * @param  int $gitlabID
     * @param  int $projectID
     * @return array
     */
    public function getRepoListByClient($gitlabID, $projectID = 0)
    {
        $server = $this->loadModel('pipeline')->getByID($gitlabID);
        return $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('synced')->eq(1)
            ->beginIF($server)->andWhere('SCM')->eq(ucfirst($server->type))->fi()
            ->andWhere('serviceHost')->eq($gitlabID)
            ->beginIF($projectID)->andWhere('serviceProject')->eq($projectID)->fi()
            ->fetchAll();
    }

    /**
     * Handle received GitLab webhook.
     *
     * @param  string $event
     * @param  string $token
     * @param  string $data
     * @param  object $repo
     * @access public
     * @return void
     */
    public function handleWebhook($event, $token, $data, $repo)
    {
        if($event == 'Push Hook' or $event == 'Merge Request Hook')
        {
            /* Update code commit history. */
            $commentGroup = $this->loadModel('job')->getTriggerGroup('commit', array($repo->id));
            $this->loadModel('git')->updateCommit($repo, $commentGroup, false);
        }
    }

    /**
     * Get products which scm is GitLab by projects.
     *
     * @param  array $projectIDs
     * @return array
     */
    public function getGitlabProductsByProjects($projectIDs)
    {
        return $this->dao->select('path,product')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('SCM')->eq('Gitlab')
            ->andWhere('path')->in($projectIDs)
            ->fetchPairs('path', 'product');
    }

    /**
     * Sync the latest commit.
     *
     * @param int $repoID
     * @param string $branchID
     * @access public
     * @return void
     */
    public function syncCommit($repoID, $branchID)
    {
        $repo = $this->getRepoByID($repoID);
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);

        $latestInDB = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($repo->SCM == 'Git' and $branchID)->andWhere('t2.branch')->eq($branchID)->fi()
            ->beginIF($repo->SCM == 'Gitlab' and $branchID)->andWhere('t2.branch')->eq($branchID)->fi()
            ->orderBy('t1.time desc')
            ->limit(1)
            ->fetch();
        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $revision = $version == 1 ? 'HEAD' : ($repo->SCM == 'Git' ? $latestInDB->commit : $latestInDB->revision);

        $logs        = $this->scm->getCommits('since' . $revision, $this->config->repo->batchNum, $branchID);
        $commitCount = $this->saveCommit($repoID, $logs, $version, $branchID);
        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();

        $this->fixCommit($repoID);
    }

    /**
     * Get execution pairs.
     *
     * @param  int    $product
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getExecutionPairs($product, $branch = 0)
    {
        $pairs      = array();
        $executions = $this->loadModel('execution')->getList(0, 'all', 'undone', 0, $product, $branch);
        foreach($executions as $execution) $pairs[$execution->id] = $execution->name;
        return $pairs;
    }

    /**
     * Get clone url.
     *
     * @param  object $repo
     * @access public
     * @return object
     */
    public function getCloneUrl($repo)
    {
        if(empty($repo)) return null;

        $url = new stdClass();
        if($repo->SCM == 'Subversion')
        {
            $url->svn = $repo->path;
        }
        elseif($repo->SCM == 'Gitlab')
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($repo->gitService, $repo->project);
            if(isset($project->id))
            {
                $url->http = $project->http_url_to_repo;
                $url->ssh  = $project->ssh_url_to_repo;
            }
        }
        elseif($repo->SCM == 'Gitea')
        {
            $project = $this->loadModel('gitea')->apiGetSingleProject($repo->gitService, $repo->project);
            if(isset($project->id))
            {
                $url->http = $project->clone_url;
                $url->ssh  = $project->ssh_url;
            }
        }
        elseif($repo->SCM == 'Gogs')
        {
            $project = $this->loadModel('gogs')->apiGetSingleProject($repo->gitService, $repo->project);
            if(isset($project->id))
            {
                $url->http = $project->clone_url;
                $url->ssh  = $project->ssh_url;
            }
        }
        else
        {
            $this->scm = $this->app->loadClass('scm');
            $this->scm->setEngine($repo);
            $url = $this->scm->getCloneUrl();
        }

        return $url;
    }
}
