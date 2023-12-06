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
        $acl     = !empty($repo->acl->acl) ? $repo->acl->acl : 'custom';
        if(empty($repo->acl))         $repo->acl = new stdclass();
        if(empty($repo->acl->users))  $repo->acl->users  = array();
        if(empty($repo->acl->groups)) $repo->acl->groups = array();

        if(strpos(",{$this->app->company->admins},", ",$account,") !== false || $acl == 'open') return true;
        if($acl == 'custom' && empty(array_filter($repo->acl->groups)) && empty(array_filter($repo->acl->users))) return true;

        if($acl == 'private')
        {
            $userProjects = explode(',', $this->app->user->view->projects);
            $userProducts = explode(',', $this->app->user->view->products);
            $repoProjects = explode(',', $repo->projects);
            $repoProducts = explode(',', $repo->product);

            $sameProjects = array_intersect($userProjects, $repoProjects);
            $sameProducts = array_intersect($userProducts, $repoProducts);
            if(!empty($sameProjects) || !empty($sameProducts)) return true;
        }

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
            $repo = $this->getByID($repoID);
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
        }

        if(!in_array($this->app->methodName, array('maintain', 'create', 'createrepo', 'edit','import'))) common::setMenuVars('devops', $repoID);
        if(!session_id()) session_start();
        $this->session->set('repoID', $repoID);
        session_write_close();
    }

    /**
     * Get repo list.
     *
     * @param  int    $projectID
     * @param  string $SCM  Subversion|Git|Gitlab
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $getCodePath
     * @access public
     * @return array
     */
    public function getList(int $projectID = 0, string $SCM = '', string $orderBy = 'id_desc', object $pager = null, bool $getCodePath = false, bool $lastSubmitTime = false, string $type = '', int $param = 0): array
    {
        $repoQuery = $type == 'bySearch' ? $this->repoTao->processSearchQuery($param) : '';

        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($repoQuery))->andWhere($repoQuery)->fi()
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
            elseif($projectID)
            {
                $hasPriv = false;
                foreach(explode(',', $repo->product) as $productID)
                {
                    if(isset($productIdList[$productID])) $hasPriv = true;
                }

                if(!$hasPriv) unset($repos[$i]);
            }

            if($lastSubmitTime) $repo->lastSubmitTime = $repo->lastCommit ? $repo->lastCommit : $this->repoTao->getLastRevision($repo->id);

            if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo, $getCodePath);
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
            ->fetchAll('id');

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
     * 创建版本库。
     * Create a repo.
     *
     * @param  object $repo
     * @param  bool   $isPipelineServer
     * @access public
     * @return int|false
     */
    public function create(object $repo, bool $isPipelineServer)
    {
        $this->dao->insert(TABLE_REPO)->data($repo, 'serviceToken')
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->batchCheckIF($repo->SCM != 'Gitlab', 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceHost,serviceProject', 'notempty')
            ->batchCheckIF($repo->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM))
            ->checkIF(!$isPipelineServer, 'path', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM) . " and `serviceHost` = " . $this->dao->sqlobj->quote($repo->serviceHost))
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        $repoID = $this->dao->lastInsertID();

        $repo = $this->getByID($repoID);
        if($repo->SCM == 'Gitlab')
        {
            $token = time();
            $res   = $this->loadModel('gitlab')->addPushWebhook($repo, $token);
            if($res === false)
            {
                $this->dao->delete()->from(TABLE_REPO)->where('id')->eq($repoID)->exec();
                dao::$errors['webhook'][] = $this->lang->gitlab->failCreateWebhook;
                return false;
            }
            else
            {
                $this->dao->update(TABLE_REPO)->set('password')->eq($token)->where('id')->eq($repoID)->exec();
            }
        }
        $this->rmClientVersionFile();

        return $repoID;
    }

    /**
     * 创建远程版本库。
     * Create a repo.
     *
     * @param  object $repo
     * @param  int   $namespace
     * @access public
     * @return int|false
     */
    public function createRepo(object $repo, int $namespace)
    {
        $check = $this->repoTao->checkName($repo);
        if(!$check)
        {
            dao::$errors['name'] = $this->lang->repo->error->repoNameInvalid;
            return false;
        }

        $createRepoFunc = "create{$repo->SCM}Repo";
        $response = $this->$createRepoFunc($repo, $namespace);

        $this->loadModel($repo->SCM);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create($repo->SCM . 'project', $response->id, 'created', '', $response->name);
            $repo->path           = $response->path;
            $repo->serviceProject = $response->serviceProject;
            $repo->extra          = $response->extra;

            if(in_array($repo->SCM, array('Gitea', 'Gogs')))
            {
                $path = $this->checkGiteaConnection($repo->SCM, $repo->name, $repo->serviceHost, $repo->serviceProject);

                if($path === false) return false;
                $repo->path = $path;
            }

            $repoID = $this->create($repo, false);
            if(dao::isError())
            {
                $this->{$repo->SCM}->apiDeleteProject($repo->serviceHost, $response->id);
                return false;
            }
            return $repoID;
        }

        return $this->{$repo->SCM}->apiErrorHandling($response);
    }

    /**
     * 创建gitlab远程版本库。
     * Create gitlab repo.
     *
     * @param  object $repo
     * @param  int    $namespace
     * @access public
     * @return object|false
     */
    public function createGitlabRepo(object $repo, int $namespace)
    {
        $project = new stdclass();
        $project->name                   = $repo->name;
        $project->path                   = $repo->name;
        $project->description            = $repo->desc;
        $project->namespace_id           = $namespace;
        $project->initialize_with_readme = true;

        $response = $this->loadModel('gitlab')->apiCreateProject($repo->serviceHost, $project);

        if(empty($response->id)) return $response;

        $result = new stdclass();
        $result->id             = $response->id;
        $result->path           = $response->web_url;
        $result->serviceProject = $response->id;
        $result->extra          = $response->id;

        return $result;
    }

    /**
     * 创建gitea远程版本库。
     * Create gitlab repo.
     *
     * @param  object $repo
     * @param  int    $namespace
     * @access public
     * @return object|false
     */
    public function createGiteaRepo(object $repo, int $namespaceID)
    {
        $namespace = $this->getGroups($repo->serviceHost, $namespaceID);

        $response = $this->loadModel('gitea')->apiCreateRepository($repo->serviceHost, $repo->name, $namespace, $repo->desc);

        if(empty($response->id)) return $response;

        $result = new stdclass();
        $result->id             = $response->id;
        $result->path           = $response->html_url;
        $result->serviceProject = $response->full_name;
        $result->extra          = '';

        return $result;
    }

    /**
     * 批量创建版本库。
     * Batch create repos.
     *
     * @param  array  $repos
     * @access public
     * @return bool
     */
    public function batchCreate(array $repos, int $serviceHost): bool
    {
        foreach($repos as $data)
        {
            $repo = new stdclass();
            $repo->serviceHost    = $serviceHost;
            $repo->serviceProject = $data['serviceProject'];
            $repo->product        = $data['product'];
            $repo->name           = $data['name'];
            $repo->projects       = $data['projects'];
            $repo->SCM            = 'Gitlab';
            $repo->encoding       = 'utf-8';
            $repo->encrypt        = 'base64';

            $this->dao->insert(TABLE_REPO)->data($repo)
                ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
                ->check('serviceHost,serviceProject', 'notempty')
                ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM))
                ->check('serviceProject', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM) . " and `serviceHost` = " . $this->dao->sqlobj->quote($repo->serviceHost))
                ->autoCheck()
                ->exec();

            if(dao::isError()) return false;

            $repoID = $this->dao->lastInsertID();

            if($repo->SCM == 'Gitlab')
            {
                /* Add webhook. */
                $repo = $this->getByID($repoID);
                $this->loadModel('gitlab')->addPushWebhook($repo);
                $this->gitlab->updateCodePath($repo->serviceHost, $repo->serviceProject, $repo->id);
            }

            $this->loadModel('action')->create('repo', $repoID, 'created');
        }

        return true;
    }

    /**
     * 更新版本库。
     * Update a repo.
     *
     * @param  object $data
     * @param  object $repo
     * @param  bool   $isPipelineServer
     * @access public
     * @return bool
     */
    public function update(object $data, object $repo, bool $isPipelineServer): bool
    {
        if(($repo->serviceHost != $data->serviceHost || $repo->serviceProject != $data->serviceProject) && $data->SCM == 'Gitlab')
        {
            $repo->gitService = $data->serviceHost;
            $repo->project    = $data->serviceProject;

            $token = time();
            $res   = $this->loadModel('gitlab')->addPushWebhook($repo, $token);
            if($res === false)
            {
                dao::$errors['webhook'][] = $this->lang->gitlab->failCreateWebhook;
                return false;
            }
            else
            {
                $data->password = $token;
            }
        }

        if($data->SCM == 'Subversion' && $data->path != $repo->path)
        {
            $data->synced     = 0;
            $data->lastSync   = null;
            $data->lastCommit = null;
        }

        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->update(TABLE_REPO)->data($data, 'serviceToken')
            ->batchCheck($this->config->repo->edit->requiredFields, 'notempty')
            ->batchCheckIF($data->SCM != 'Gitlab', 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceHost,serviceProject', 'notempty')
            ->batchCheckIF($data->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($data->SCM) . " and `id` != $repo->id")
            ->checkIF(!$isPipelineServer, 'path', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($data->SCM) . " and `serviceHost` = " . $this->dao->sqlobj->quote($data->serviceHost) . " and `id` != $repo->id")
            ->autoCheck()
            ->where('id')->eq($repo->id)->exec();

        $this->rmClientVersionFile();

        if($data->SCM == 'Gitlab')
        {
            $this->loadModel('gitlab')->updateCodePath($data->serviceHost, $data->serviceProject, $repo->id);
            $data->path = $this->getByID($repo->id)->path;
            $this->updateCommitDate($repo->id);
        }

        if(($repo->serviceHost != $data->serviceHost || $repo->serviceProject != $data->serviceProject) && $repo->path != $data->path)
        {
            $this->repoTao->deleteInfoByID($repo->id);
            return false;
        }

        return true;
    }

    /**
     * Create commit link.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $type
     * @param  string $from     repo|commit
     * @access public
     * @return void
     */
    public function link($repoID, $revision, $type = 'story', $from = 'repo')
    {
        $this->loadModel('action');
        if($type == 'story') $links = $this->post->stories;
        if($type == 'bug')   $links = $this->post->bugs;
        if($type == 'task')  $links = $this->post->tasks;

        $revisionInfo = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch();
        if(empty($revisionInfo))
        {
            $repo = $this->getByID($repoID);
            if($repo->SCM == 'Gitlab')
            {
                $scm = $this->app->loadClass('scm');
                $scm->setEngine($repo);
                $logs = $scm->getCommits($revision, 1);
                $this->saveCommit($repoID, $logs, 0);
            }
            else
            {
                $this->updateCommit($repoID);
            }
        }

        $revisionInfo = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch();
        if(empty($revisionInfo))
        {
            dao::$errors = $this->lang->fail;
            return false;
        }

        $revisionID = $revisionInfo->id;
        $committer  = $this->dao->select('account')->from(TABLE_USER)->where('commiter')->eq($revisionInfo->committer)->fetch('account');
        if(empty($committer)) $committer = $revisionInfo->committer;
        if($from == 'repo') $committer = $this->app->user->account;
        foreach($links as $linkID)
        {
            $relation           = new stdclass;
            $relation->AType    = 'revision';
            $relation->AID      = $revisionID;
            $relation->relation = 'commit';
            $relation->BType    = $type;
            $relation->BID      = $linkID;

            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            $this->action->create($type, $linkID, 'linked2revision', '', $revisionID, $committer);
        }
    }

    /**
     * 删除一个版本库。
     * Delete a repo.
     *
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function deleteRepo(int $repoID): bool
    {
        $this->repoTao->deleteInfoByID($repoID);
        $this->dao->delete()->from(TABLE_REPO)->where('id')->eq($repoID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('repo', $repoID, 'deleted', '');
        return true;
    }

    /**
     * Unlink object and commit revision.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $objectType story|bug|task
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function unlink($repoID, $revision, $objectType, $objectID)
    {
        $revisionID = $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch('id');
        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AID')->eq($revisionID)
            ->andWhere('AType')->eq('revision')
            ->andWhere('relation')->eq('commit')
            ->andWhere('BType')->eq($objectType)
            ->andWhere('BID')->eq($objectID)->exec();

        if(!dao::isError()) $this->loadModel('action')->create($objectType, $objectID, 'unlinkedfromrevision', '', $revisionID);
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
     * @param  bool   $showScm
     * @access public
     * @return array
     */
    public function getRepoPairs($type, $projectID = 0, $showScm = true)
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
            $scm = '';
            if($showScm) $scm = $repo->SCM == 'Subversion' ? '[svn] ' : '[' . strtolower($repo->SCM) . '] ';
            if($this->checkPriv($repo))
            {
                if(($type == 'project' or $type == 'execution') and $projectID)
                {
                    foreach($productIdList as $productID)
                    {
                        if(strpos(",$repo->product,", ",$productID,") !== false) $repoPairs[$repo->id] = $scm . $repo->name;
                    }
                }
                else
                {
                    $repoPairs[$repo->id] = $scm . $repo->name;
                }
            }
        }

        return $repoPairs;
    }

    /**
     * Get repos group by repo type.
     *
     * @param  string $type
     * @param  int    $projectID
     * @param  string $repoType
     * @access public
     * @return array
     */
    public function getRepoGroup(string $type, int $projectID = 0, string $repoType = ''): array
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->beginIF($repoType == 'git')->andWhere('SCM')->in($this->config->repo->gitServiceTypeList)->fi()
            ->fetchAll();

        $productIds = $productItems = array();
        if($projectID)
        {
            $productIds = $this->loadModel('product')->getProductIDByProject($projectID, false);
        }
        else
        {
            foreach($repos as $repo)
            {
                $productIds = array_merge($productIds, explode(',', $repo->product));
            }
        }
        $products = $this->loadModel('product')->getByIdList(array_filter(array_unique($productIds)));
        foreach($products as $productID => $product)
        {
            $productItem = array();
            $productItem['pid']   = $productID;
            $productItem['type']  = 'product';
            $productItem['text']  = $product->name;
            $productItem['items'] = array();

            $productItems[$productID] = $productItem;
        }

        /* Get project products. */
        $projectProductIds = ($type == 'project' or $type == 'execution') ? $this->loadModel('product')->getProductIDByProject((int)$projectID, false) : array();

        /* Get repo data for dropmenu. */
        $repoPairs = array();
        foreach($repos as $repo)
        {
            $repo->acl = json_decode($repo->acl);
            $scm = $repo->SCM == 'Subversion' ? 'svn' : strtolower($repo->SCM);
            if($this->checkPriv($repo))
            {
                $repoItem = array();
                $repoItem['id']       = $repo->id;
                $repoItem['text']     = $repo->name;
                $repoItem['keys']     = zget(common::convert2Pinyin(array($repo->name)), $repo->name, '');
                $repoItem['data-app'] = $this->app->tab;

                $repoProducts = explode(',', $repo->product);
                $repoProducts = array_filter($repoProducts);
                foreach($repoProducts as $productID)
                {
                    if(($type == 'project' or $type == 'execution') and $projectID)
                    {
                        if(!in_array($productID, $projectProductIds)) continue;
                    }

                    if(strpos(",$repo->product,", ",$productID,") !== false)
                    {
                        if(!isset($repoPairs[$productID])) $repoPairs[$productID] = $productItems[$productID];
                        $repoPairs[$productID]['items'][] = $repoItem;
                    }
                }
            }
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
    public function getByID($repoID)
    {
        if(empty($repoID)) return new stdclass();
        $repo = $this->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return false;

        if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
        $repo->codePath = $repo->path;
        if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo);
        $repo->acl = json_decode($repo->acl);
        if(empty($repo->acl)) $repo->acl = new stdclass();
        if(empty($repo->acl->acl)) $repo->acl->acl = 'custom';
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
        $conditions = '(' . implode(' OR ', $conditions). ')';

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
        $conditions = '(' . implode(' OR ', $conditions). ')';

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
        foreach($repos as $repo)
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
     * @param  string  $source  select current repo's branches from scm or database.
     * @access public
     * @return array
     */
    public function getBranches($repo, $printLabel = false, $source = 'scm')
    {
        if($source == 'database')
        {
            $branches = $this->dao->select('branch')->from(TABLE_REPOBRANCH)
                ->where('repo')->eq($repo->id)
                ->fetchPairs();

            if($printLabel)
            {
                foreach($branches as &$branch) $branch = 'Branch::' . $branch;
            }
        }
        else
        {
            $this->scm = $this->app->loadClass('scm');
            $this->scm->setEngine($repo);
            $branches = $this->scm->branch();

            if($printLabel)
            {
                foreach($branches as &$branch) $branch = 'Branch::' . $branch;
            }
        }

        return $branches;
    }

    public function getCommitsByRevisions($revisions)
    {
        return $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->fetchPairs('id');
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
        if(!isset($repo->id)) return array();
        if($repo->SCM == 'Gitlab') return $this->loadModel('gitlab')->getCommits($repo, $entry, $revision, $type, $pager, $begin, $end);

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
            $historyIdList = $this->dao->select('DISTINCT t2.id,t2.`time`')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
                ->where('1=1')
                ->andWhere('t1.repo')->eq($repo->id)
                ->beginIF($revisionTime)->andWhere('t2.`time`')->le($revisionTime)->fi()
                ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
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
            ->andWhere('left(t1.`comment`, 12)')->ne('Merge branch')
            ->beginIF($repo->SCM != 'Subversion' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->beginIF($entry != '/' and !empty($entry))->andWhere('t1.id')->in($historyIdList)->fi()
            ->beginIF($begin)->andWhere('t1.time')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.time')->le($end)->fi()
            ->orderBy('time desc');
        if($entry == '/' or empty($entry)) $comments->page($pager, 't1.id');
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

        $repo = $this->getByID($repoID);
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
        $cachePath = $this->app->getCacheRoot() . '/repo/' . $repoID;
        if(!is_dir($cachePath)) mkdir($cachePath, 0777, true);
        if(!is_writable($cachePath)) return false;
        return $cachePath . '/' . md5("{$this->cookie->repoBranch}-$path-$revision");
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
        $repo = $this->getByID($repoID);
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

                        if($file->action == 'R' && !empty($file->oldPath))
                        {
                            $file->path    = $file->oldPath;
                            $file->parent  = dirname($file->path);
                            $file->oldPath = '';
                            $file->action  = 'D';
                            $this->dao->insert(TABLE_REPOFILES)->data($file)->exec();
                        }
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
                $repoFile->oldPath  = empty($info['oldPath']) ? '' : $info['oldPath'];
                $this->dao->insert(TABLE_REPOFILES)->data($repoFile)->exec();

                if($repoFile->oldPath and $repoFile->action == 'R')
                {
                    $parentPath = dirname($repoFile->oldPath);

                    $repoFile->path    = $repoFile->oldPath;
                    $repoFile->parent  = $parentPath == '\\' ? '/' : $parentPath;
                    $repoFile->type    = $info['kind'];
                    $repoFile->action  = 'D';
                    $repoFile->oldPath = '';
                    $this->dao->insert(TABLE_REPOFILES)->data($repoFile)->exec();
                }
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
            if(isset($lastInDB->revision) and $lastInDB->revision == $log->revision)
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
        helper::setcookie("repoBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
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
        $stmt = $this->dao->select('DISTINCT t1.id,t1.`time`')->from(TABLE_REPOHISTORY)->alias('t1')
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
            substr_count($blk, "^\r\n")/512 > 0.3 ||
            substr_count($blk, "^ -~")/512 > 0.3 ||
            substr_count($blk, "\x00") > 0
        );
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
            $this->session->set('clientVersionFile', '');
            session_write_close();

            if(file_exists($clientVersionFile)) @unlink($clientVersionFile);
        }
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
        $action->actor   = $log->author;
        $action->date    = $log->date;
        $action->extra   = $scm == 'svn' ? $log->revision : substr($log->revision, 0, 10);
        $action->comment = $this->lang->repo->revisionA . ': #' . $action->extra . "<br />" . htmlSpecialString($this->iconvComment($log->msg, $encodings));

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
                        if($this->config->edition != 'open')
                        {
                            $this->loadModel('effort')->batchCreate();
                        }
                        else
                        {
                            $this->task->recordWorkhour($taskID);
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
            $record['product']   = isset($products[$taskID]) ? "," . implode(',', array_keys($products[$taskID])) . "," : ",0,";
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
     * @param  bool      $getCodePath
     * @access public
     * @return object
     */
    public function processGitService($repo, $getCodePath = false)
    {
        $service = $this->loadModel('pipeline')->getByID($repo->serviceHost);
        if($repo->SCM == 'Gitlab')
        {
            if($getCodePath) $project = $this->loadModel('gitlab')->apiGetSingleProject($repo->serviceHost, $repo->serviceProject);

            $repo->path     = (!$repo->path && $service) ? sprintf($this->config->repo->{$service->type}->apiPath, $service->url, $repo->serviceProject) : $repo->path;
            $repo->apiPath  = sprintf($this->config->repo->{$service->type}->apiPath, $service->url, $repo->serviceProject);
            $repo->client   = $service ? $service->url : '';
            $repo->password = $service ? $service->token : '';
            $repo->codePath = isset($project->web_url) ? $project->web_url : $repo->path;
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
            if($repo->SCM == 'Gitlab')
            {
                $scm = $this->app->loadClass('scm');
                $scm->setEngine($repo);

                $this->loadModel('repo');
                $jobs = zget($commentGroup, $repo->id, array());

                $accountPairs  = array();
                $userList      = $this->loadModel('gitlab')->apiGetUsers($repo->gitService);
                $acountIDPairs = $this->gitlab->getUserIdAccountPairs($repo->gitService);
                foreach($userList as $gitlabUser) $accountPairs[$gitlabUser->realname] = zget($acountIDPairs, $gitlabUser->id, '');

                foreach($data->commits as $commit)
                {
                    $log = new stdclass();
                    $log->revision = $commit->id;
                    $log->msg      = $commit->message;
                    $log->author   = $commit->author->name;
                    $log->date     = date("Y-m-d H:i:s", strtotime($commit->timestamp));
                    $log->files    = array();

                    $diffs = $scm->engine->getFilesByCommit($log->revision);
                    if(!empty($diffs))
                    {
                        foreach($diffs as $diff) $log->files[$diff->action][] = $diff->path;
                    }

                    $objects = $this->repo->parseComment($log->msg);
                    $this->repo->saveAction2PMS($objects, $log, $repo->path, $repo->encoding, 'git', $accountPairs);

                    foreach($jobs as $job)
                    {
                        foreach(explode(',', $job->comment) as $comment)
                        {
                            if(strpos($log->msg, $comment) !== false) $this->loadModel('job')->exec($job->id);
                        }
                    }
                }
            }
            else
            {
                $this->loadModel('git')->updateCommit($repo, $commentGroup, false);
            }
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
        $parents    = $this->dao->select('distinct parent,parent')->from(TABLE_EXECUTION)->where('type')->eq('stage')->andWhere('grade')->gt(1)->andWhere('deleted')->eq(0)->fetchPairs();
        foreach($executions as $execution)
        {
            if(!empty($parents[$execution->id]) or ($execution->type == 'stage' and in_array($execution->attribute, array('request', 'design', 'review')))) continue;

            if($execution->type == 'stage' and $execution->grade > 1)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                $execution->name  = implode('/', $parentExecutions);
            }
            $pairs[$execution->id] = $execution->name;
        }
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

    /**
     * Get file commits.
     *
     * @param  object $repo
     * @param  string $branch
     * @param  string $parent
     * @access public
     * @return array
     */
    public function getFileCommits($repo, $branch, $parent = '')
    {
        $parent = '/' . ltrim($parent, '/');

        /* Get file commits by repo. */
        if($repo->SCM != 'Subversion' and empty($branch)) $branch = $this->cookie->repoBranch;
        $fileCommits = $this->dao->select('t1.id,t1.path,t1.type,t1.action,t1.oldPath,t1.parent,t2.revision,t2.comment,t2.committer,t2.time')->from(TABLE_REPOFILES)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
            ->where('t1.repo')->eq($repo->id)
            ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
            ->beginIF($repo->SCM != 'Subversion' and $branch)->andWhere('t3.branch')->eq($branch)->fi()
            ->beginIF($repo->SCM == 'Subversion')->andWhere('t1.parent')->eq("$parent")->fi()
            ->beginIF($repo->SCM != 'Subversion')->andWhere('t1.parent')->like("$parent%")->fi()
            ->orderBy('t2.`time` asc')
            ->fetchAll('path');

        $files    = array();
        $folders  = array();
        $dirList  = array();
        $fileSort = $dirSort = array(); // Use it to sort array.
        foreach($fileCommits as $fileCommit)
        {
            /* Filter by parent. */
            if($fileCommit->action == 'D') continue;
            if(strpos($fileCommit->path, $parent) !== 0) continue;

            $pathList = explode('/', ltrim($fileCommit->path, '/'));
            if($fileCommit->parent == $parent and $fileCommit->type == 'file')
            {
                $file = new stdclass();
                $file->name     = end($pathList);
                $file->kind     = 'file';
                $file->revision = $fileCommit->revision;
                $file->comment  = $fileCommit->comment;
                $file->account  = $fileCommit->committer;
                $file->date     = $fileCommit->time;

                $files[]    = $file;
                $fileSort[] = $file->name;
            }
            else
            {
                $childPath = ltrim(substr($fileCommit->path, strlen($parent)), '/');
                $childPath = explode('/', $childPath);
                $fileName  = $fileCommit->type == 'dir' ? end($pathList) : $childPath[0];
                if(in_array($fileName, $dirList)) continue;

                $folder = new stdclass();
                $folder->name     = $fileName;
                $folder->kind     = 'dir';
                $folder->revision = $fileCommit->revision;
                $folder->comment  = $fileCommit->comment;
                $folder->account  = $fileCommit->committer;
                $folder->date     = $fileCommit->time;

                $dirList[] = $fileName;
                $folders[] = $folder;
                $dirSort[] = $fileName;
            }
        }
        array_multisort($fileSort, SORT_ASC, $files);
        array_multisort($dirSort, SORT_ASC, $folders);

        return array_merge($folders, $files);
    }

    /**
     * Get Repo file list.
     *
     * @param object $repo
     * @param string $branch
     * @param string $path
     * @access public
     * @return array
     */
    public function getFileList($repo, $branch, $path = '')
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $paths = array();
        $files = $scm->engine->tree($path, 0);
        if(empty($files)) $files = array();
        foreach($files as $file)
        {
            $paths[] = $file->path;
        }

        $requests = array();
        foreach($paths as $path)
        {
            $requests[]['url'] = $scm->engine->getCommitsByPath($path, '', '', 1, 1, true);
        }
        $this->app->loadClass('requests', true);
        $commits = requests::request_multiple($requests);

        foreach($files as $key => $file)
        {
            $files[$key]->kind = $file->type == 'tree' ? 'dir' : 'file';

            $commit = isset($commits[$key]->body) ? json_decode($commits[$key]->body) : array();
            $files[$key]->revision = isset($commit[0]->id) ? $commit[0]->id : '';
            $files[$key]->comment  = isset($commit[0]->title) ? $commit[0]->title : '';
            $files[$key]->account  = isset($commit[0]->committer_name) ? $commit[0]->committer_name : '';
            $files[$key]->date     = isset($commit[0]->committed_date) ? $commit[0]->committed_date : '';
        }
        return $files;
    }

    /**
     * Get html for file tree.
     *
     * @param  object $repo
     * @param  string $branch
     * @param  array  $diffs
     * @access public
     * @return string
     */
    public function getFileTree($repo, $branch = '', $diffs = null)
    {
        set_time_limit(0);
        $allFiles = array();
        if(is_null($diffs))
        {
            if($repo->SCM == 'Gitlab')
            {
                $cacheFile    = $this->getCacheFile($repo->id, 'tree-list', 'tree-list');
                $lastRevision = $this->dao->select('t1.revision')->from(TABLE_REPOHISTORY)->alias('t1')
                    ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
                    ->where('t1.repo')->eq($repo->id)
                    ->andWhere('t2.branch')->eq($this->cookie->repoBranch)
                    ->orderBy('t1.commit desc')
                    ->fetch('revision');

                if($cacheFile and file_exists($cacheFile)) $infos = unserialize(file_get_contents($cacheFile));
                if(!$cacheFile or !file_exists($cacheFile) or $infos['revision'] != $lastRevision)
                {
                    $scm = $this->app->loadClass('scm');
                    $scm->setEngine($repo);

                    $this->app->loadClass('requests', true);
                    $files = $scm->engine->tree('', 1, true);

                    $allFiles = array();
                    foreach($files as $file)
                    {
                        $allFiles[] = $file->path;
                    }
                    $infos = array('revision' => $lastRevision, 'files' => $allFiles);

                    if($cacheFile && !file_exists($cacheFile . '.lock'))
                    {
                        touch($cacheFile . '.lock');
                        file_put_contents($cacheFile, serialize($infos));
                        unlink($cacheFile . '.lock');
                    }
                }
                else
                {
                    $infos    = unserialize(file_get_contents($cacheFile));
                    $allFiles = $infos['files'];
                }
            }
            else
            {
            if($repo->SCM != 'Subversion' and empty($branch)) $branch = $this->cookie->repoBranch;
            $files = $this->dao->select('t1.path,t2.time,t1.action')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
                ->where('t1.repo')->eq($repo->id)
                ->andWhere('t1.type')->eq('file')
                ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
                ->beginIF($repo->SCM != 'Subversion' and $branch)->andWhere('t3.branch')->eq($branch)->fi()
                ->orderBy('t2.`time` asc')
                ->fetchAll('path');

            $removeDirs = array();
            if($repo->SCM == 'Subversion')
            {
                $removeDirs = $this->dao->select('t2.time,t1.path')->from(TABLE_REPOFILES)->alias('t1')
                    ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                    ->where('t1.repo')->eq($repo->id)
                    ->andWhere('t1.type')->eq('dir')
                    ->andWhere('t1.action')->eq('D')
                    ->fetchPairs();

            }
            foreach($files as $file)
            {
                foreach($removeDirs as $removeTime => $dir)
                {
                    if(strpos($file->path, $dir . '/') === 0 and $file->time <= $removeTime)
                    {
                        $file->action = 'D';
                        break;
                    }
                }
                if($file->action != 'D')
                {
                    $allFiles[] = $file->path;
                }
            }
            }
        }
        else
        {
            foreach($diffs as $diff) $allFiles[] = $diff->fileName;
        }

        return $this->buildFileTree($allFiles);
    }

    /**
     * Build file tree.
     *
     * @param  array  $allFiles
     * @access public
     * @return string
     */
    public function buildFileTree($allFiles = array())
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

                $parentID = $parent == '' ? 0 : $files[$parent]['id'];
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
     * @param  int    $parent
     * @access public
     * @return array
     */
    public function buildTree($files = array(), $parent = 0)
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
     * Get front files.
     *
     * @param  array $nodes
     * @access public
     * @return string
     */
    public function getFrontFiles($nodes)
    {
        $html = '<ul>';
        foreach($nodes as $childNode)
        {
            $html .= "<li class='open'>";
            if(isset($childNode['children']))
            {
                $html .= "<div class='tree-group'>";
                $html .= "<i class='module-name icon icon-folder'></i> {$childNode['name']}";
                $html .= '</div>';
                $html .= $this->getFrontFiles($childNode['children']);
            }
            else
            {
                $html .= "<span class='item doc-title text-ellipsis'><i class='file icon icon-file-text-alt'></i> " . html::a('#filePath' . $childNode['key'], $childNode['name'], '', "class='repoFileName' data-path='{$childNode['path']}' title='{$childNode['path']}'") . '</span>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Get git branch and tag.
     *
     * @param  int    $repoID
     * @param  string $oldRevision
     * @param  string $newRevision
     * @access public
     * @return object
     */
    public function getBranchesAndTags($repoID, $oldRevision = '0', $newRevision = 'HEAD')
    {
        $output = new stdClass();

        $scm  = $this->app->loadClass('scm');
        $repo = $this->getByID($repoID);
        if(!$repo) return $output;

        $scm->setEngine($repo);
        $branches     = $scm->branch();
        $tags         = $scm->tags('');
        $branchAndtag = array('branch' => $branches, 'tag' =>$tags);

        $html = '<ul class="tree tree-angles" data-ride="tree" data-idx="0" id="branchesAndTags">';
        foreach($branchAndtag as $type => $data)
        {
            if(empty($data)) continue;

            $html .= "<li data-idx='$type' data-id='$type' class='has-list open in' style='cursor: pointer;'><i class='list-toggle icon'></i>";
            $html .= "<div class='hide-in-search'><a class='text-muted' title='{$this->lang->repo->{$type}}'>{$this->lang->repo->{$type}}</a></div><ul data-idx='$type'>";

            foreach($data as $name)
            {
                $selectedSource = $name == $oldRevision ? 'selected-source' : '';
                $selectedTarget = $name == $newRevision ? 'selected-target' : '';
                $html .= "<li data-idx='$name' data-id='$type-$name'><a href='javascript:;' id='$type-$name' class='$selectedSource $selectedTarget branch-or-tag text-ellipsis' title='$name' data-key='$name'>$name</a></li>";
            }

            $html .= '</ul></li>';
        }
        $html .= '</ul>';

        $sourceHtml = str_replace('branch-or-tag', 'branch-or-tag source', $html);
        $targetHtml = str_replace('branch-or-tag', 'branch-or-tag target', $html);

        $output->sourceHtml = str_replace('selected-source', 'selected', $sourceHtml);
        $output->targetHtml = str_replace('selected-target', 'selected', $targetHtml);
        return $output;
    }

    /**
     * Get relation by commit.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @param  string $type story|bug|task
     * @access public
     * @return array
     */
    public function getRelationByCommit($repoID, $commit, $type = '')
    {
        $relationList = $this->dao->select('t1.BID as id, t1.BType as type')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.AID = t2.id')
            ->where('t2.revision')->eq($commit)
            ->andWhere('t2.repo')->eq($repoID)
            ->andWhere('t1.AType')->eq('revision')
            ->beginIF($type)->andWhere('t1.BType')->eq($type)->fi()
            ->fetchAll();

        $storyIDs = array();
        $bugIDs   = array();
        $taskIDs  = array();
        foreach($relationList as $relation)
        {
            if($relation->type == 'story')
            {
                $storyIDs[] = $relation->id;
            }
            elseif($relation->type == 'bug')
            {
                $bugIDs[] = $relation->id;
            }
            elseif($relation->type == 'task')
            {
                $taskIDs[] = $relation->id;
            }
        }
        $stories = empty($storyIDs) ? array() : $this->loadModel('story')->getByList($storyIDs);
        $bugs    = empty($bugIDs)   ? array() : $this->loadModel('bug')->getByList($bugIDs);
        $tasks   = empty($taskIDs)  ? array() : $this->loadModel('task')->getByList($taskIDs);

        $titleList = array();
        foreach($relationList as $key => $relation)
        {
            if($type) $key = $relation->id;

            $titleList[$key] = array(
                'id'    => $relation->id,
                'type'  => $relation->type,
                'title' => "#$relation->id "
            );
            if($relation->type == 'story')
            {
                $story = zget($stories, $relation->id, array());
                $titleList[$key]['title'] .=  zget($story, 'title', '');
            }
            elseif($relation->type == 'bug')
            {
                $bug = zget($bugs, $relation->id, array());
                $titleList[$key]['title'] .=  zget($bug, 'title', '');
            }
            elseif($relation->type == 'task')
            {
                $task = zget($tasks, $relation->id, array());
                $titleList[$key]['title'] .=  zget($task, 'name', '');
            }
        }

        return $titleList;
    }

    /**
     * Get relation commit.
     *
     * @param  int    $objectID
     * @param  string $objectType story|bug|task
     * @access public
     * @return array
     */
    public function getCommitsByObject($objectID, $objectType)
    {
        return $this->dao->select('t2.*')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.AID = t2.id')
            ->where('t1.BID')->eq($objectID)
            ->andWhere('t1.BType')->eq($objectType)
            ->andWhere('t1.AType')->eq('revision')
            ->andWhere('t1.relation')->eq('commit')
            ->fetchAll();
    }

    /**
     * Insert delete record.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function insertDeleteRecord($repoID)
    {
        set_time_limit(0);
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(empty($repo)) return false;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $values = '';
        if($repo->SCM == 'Gitlab')
        {
            $renameRevisions = $this->dao->select('t1.revision as revisionID,t2.revision')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->where('t1.action')->eq('R')
                ->andWhere('t1.repo')->eq($repoID)
                ->andWhere('t1.oldPath')->eq('')
                ->orderBy('t2.time desc')
                ->fetchAll('revisionID');

            foreach($renameRevisions as $revision)
            {
                $files = $scm->getFilesByCommit($revision->revision);
                foreach($files as $file)
                {
                    if($file->action != 'R') continue;
                    $parentPath = dirname($file->oldPath) == '\\' ? '/' : dirname($file->oldPath);
                    $values    .= "($repoID,{$revision->revisionID},'{$file->oldPath}','','$parentPath','{$file->type}','D'),";

                    $this->dao->update(TABLE_REPOFILES)->set('oldPath')->eq($file->oldPath)->where('revision')->eq($revision->revisionID)->andWhere('path')->eq($file->path)->exec();
                }
            }
        }
        else
        {
            $branchGroups = $this->dao->select('t1.id as fileID,t1.revision as revisionID,t2.revision,t3.branch')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t3.revision=t2.id')
                ->where('t1.action')->eq('R')
                ->andWhere('t1.repo')->eq($repoID)
                ->andWhere('t1.oldPath')->eq('')
                ->orderBy('t2.time desc')
                ->fetchGroup('branch');

            $revisionPairs = $this->dao->select('revision,id')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetchPairs();

            foreach($branchGroups as $branch => $group)
            {
                $firstCommit = end($group);
                $commits     = $scm->getCommits($firstCommit->revision, 0, $branch);
                foreach($commits['files'] as $revision => $commit)
                {
                    if(!isset($revisionPairs[$revision])) continue;
                    $revisionID = $revisionPairs[$revision];

                    foreach($commit as $file)
                    {
                        if(!$file->oldPath) continue;
                        $parentPath = dirname($file->oldPath) == '\\' ? '/' : dirname($file->oldPath);
                        $values    .= "($repoID,$revisionID,'{$file->oldPath}','','$parentPath','{$file->type}','D'),";

                        $this->dao->update(TABLE_REPOFILES)->set('oldPath')->eq($file->oldPath)->where('revision')->eq($revisionID)->andWhere('path')->eq($file->path)->exec();
                    }
                }
            }
        }

        if($values)
        {
            $sql    = 'INSERT INTO ' . TABLE_REPOFILES . ' (`repo`,`revision`,`path`,`oldPath`,`parent`,`type`,`action`) VALUES ' . trim($values, ',');
            $this->dao->exec($sql);
        }

        $this->loadModel('setting')->setItem('system.repo.synced', $this->config->repo->synced . ',' . $repoID);
    }

    /*
     * Remove projects without privileges.
     *
     * @param  array   $productIDList
     * @param  array   $projectIDList
     * @access public
     * @return array
     */
    public function filterProject($productIDList, $projectIDList = array())
    {
        /* Get all projects that can be accessed. */
        $accessProjects = array();
        foreach($productIDList as $productID)
        {
            $projects       = $this->loadModel('product')->getProjectPairsByProduct($productID);
            $accessProjects = $accessProjects + $projects;
        }

        /* Get linked projects. */
        $linkedProjects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->fetchPairs('id', 'name');
        return $accessProjects + $linkedProjects; // Merge projects can be accessed and exists.
    }

    /**
     * Update commit history.
     *
     * @param  int    $repoID
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function updateCommit($repoID, $objectID = 0, $branchID = 0)
    {
        $repo = $this->getByID($repoID);
        if($repo->SCM == 'Gitlab') return;
        /* Update code commit history. */
        $commentGroup = $this->loadModel('job')->getTriggerGroup('commit', array($repoID));

        if(in_array($repo->SCM, $this->config->repo->gitTypeList))
        {
            $branch = $this->cookie->repoBranch;

            if($branchID)
            {
                $currentBranches = $this->getBranches($repo, false, 'database');
                if(!in_array($branch, $currentBranches))
                {
                    $link = $this->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=$branchID", '', false) . '#app=' . $this->app->tab;
                    return print(js::locate($link));
                }
            }
            $this->loadModel('git')->updateCommit($repo, $commentGroup, false);
            $_COOKIE['repoBranch'] = $branch;
        }
        if($repo->SCM == 'Subversion') $this->loadModel('svn')->updateCommit($repo, $commentGroup, false);
    }

    /**
     * Delete the deleted branch.
     *
     * @param int   $repoID
     * @param array $latestBranches
     * @access public
     * @return bool
     */
    public function checkDeletedBranches($repoID, $latestBranches)
    {
        if(empty($latestBranches)) return false;

        $currentBranches = $this->dao->select('branch')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->groupBy('branch')->fetchPairs('branch');

        $deletedBranches = array_diff($currentBranches, $latestBranches);
        foreach($deletedBranches as $deletedBranch)
        {
            if($deletedBranch == 'master') continue;

            $revisionIds       = $this->dao->select('revision')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->andWhere('branch')->eq($deletedBranch)->fetchPairs('revision');
            $branchRevisionIds = $this->dao->select('revision,count(branch) as count')->from(TABLE_REPOBRANCH)->where('revision')->in($revisionIds)->groupBy('revision')->having('count')->eq(1)->fetchPairs('revision', 'revision');
            $fileIds           = $this->dao->select('id')->from(TABLE_REPOFILES)->where('revision')->in($branchRevisionIds)->fetchPairs('id');

            $this->dao->delete()->from(TABLE_REPOHISTORY)->where('id')->in($branchRevisionIds)->exec();
            $this->dao->delete()->from(TABLE_REPOFILES)->where('id')->in($fileIds)->exec();
            $this->dao->delete()->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->andWhere('branch')->eq($deletedBranch)->exec();
        }
        return true;
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $repo
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $repo, string $action): bool
    {
        $action = strtolower($action);

        if(!commonModel::hasPriv('repo', $action)) return false;
        if($action == 'execjob')    return $repo->exec == '';
        if($action == 'reportview') return $repo->report == '';

        return true;
    }

    /**
     * 获取gitlab项目列表。
     * Get gitlab projects.
     *
     * @param  int    $gitlabID
     * @param  string $projectIdList
     * @param  string $filter
     * @access public
     * @return array
     */
    public function getGitlabProjects(int $gitlabID, string $projectIdList = '', string $filter = ''): array
    {
        $showAll = ($filter == 'ALL' and common::hasPriv('repo', 'create')) ? true : false;
        if($this->app->user->admin or $showAll)
        {
            $projects = $this->loadModel('gitlab')->apiGetProjects($gitlabID, true, 0, 0, false);
        }
        else
        {
            $gitlabUser = $this->loadModel('gitlab')->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$gitlabUser) $this->app->control->send(array('message' => array()));

            $projects    = $this->gitlab->apiGetProjects($gitlabID, $filter ? 'false' : 'true');
            $groupIDList = array(0 => 0);
            $groups      = $this->gitlab->apiGetGroups($gitlabID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;
            if($filter == 'IS_DEVELOPER')
            {
                foreach($projects as $key => $project)
                {
                    if(!$this->gitlab->checkUserAccess($gitlabID, 0, $project, $groupIDList, 'developer')) unset($projects[$key]);
                }
            }
        }

        return $projects;
    }

    /**
     * Get repo groups.
     *
     * @param  int    $serverID
     * @param  int    $groupID
     * @access public
     * @return string|array|false
     */
    public function getGroups($serverID, $groupID = 0)
    {
        $server       = $this->loadModel('pipeline')->getByID($serverID);
        $getGroupFunc = 'get' . $server->type . 'Groups';

        $groups = $this->$getGroupFunc($serverID);

        if($groupID !== 0)
        {
            foreach($groups as $group)
            {
                if($group['value'] == $groupID) return $group['text'];
            }
            return false;
        }

        return $groups;
    }

    /**
     * Get gitlab groups.
     *
     * @param  int    $gitlabID
     * @access public
     * @return void
     */
    public function getGitlabGroups(int $gitlabID): array
    {
        $groups = $this->loadModel('gitlab')->apiGetGroups($gitlabID, 'name_asc');
        $options = array();
        foreach($groups as $group)
        {
            $options[] = array('text' => $group->name, 'value' => $group->id);
        }
        return $options;
    }

    /**
     * Get gitea groups.
     *
     * @param  int $giteaID
     * @access public
     * @return array
     */
    public function getGiteaGroups(int $giteaID): array
    {
        $groups = $this->loadModel('gitea')->apiGetGroups($giteaID);
        $options = array();
        foreach($groups as $group)
        {
            $options[] = array('text' => $group->username, 'value' => $group->id);
        }
        return $options;
    }

    /**
     * Check str in array.
     *
     * @param  string $str
     * @param  array  $checkAry
     * @access public
     * @return bool
     */
    public function strposAry($str, $checkAry)
    {
        foreach($checkAry as $check)
        {
            if(mb_strpos($str, $check) !== false) return true;
        }

        return false;
    }

    /**
     * 更新版本库最后提交时间。
     * Update repo last commited date.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function updateCommitDate(int $repoID): void
    {
        $repo = $this->getByID($repoID);
        if($repo->SCM == 'Gitlab')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $commits = $scm->engine->getCommitsByPath('', '', '', 1, 1);
            $commit  = $commits[0];
            if(!empty($commit->committed_date))
            {
                $lastCommitDate = date('Y-m-d H:i:s', strtotime($commit->committed_date));
                $this->dao->update(TABLE_REPO)->set('lastCommit')->eq($lastCommitDate)->where('id')->eq($repoID)->exec();
            }
        }
    }

    /**
     * 检查gitea连接。
     * Check gitea connection.
     *
     * @param  string      $scm
     * @param  string      $name
     * @param  int|string  $serviceHost
     * @param  int|string  $serviceProject
     * @access public
     * @return string|false
     */
    public function checkGiteaConnection(string $scm, string $name, $serviceHost, $serviceProject)
    {
        if($name != '' and $serviceProject != '')
        {
            $module  = strtolower($scm);
            $project = $this->loadModel($module)->apiGetSingleProject($serviceHost, $serviceProject);
            if(isset($project->tokenCloneUrl))
            {
                $path = $this->app->getAppRoot() . 'www/data/repo/' . $name . '_' . $module;
                if(!realpath($path))
                {
                    $cmd = 'git clone --progress -v "' . $project->tokenCloneUrl . '" "' . $path . '"  > "' . $this->app->getTmpRoot() . "log/clone.progress.$module.{$name}.log\" 2>&1 &";
                    if(PHP_OS == 'WINNT') $cmd = "start /b $cmd";
                    exec($cmd);
                }
                return $path;
            }
            else
            {
                dao::$errors['serviceProject'] = $this->lang->repo->error->noCloneAddr;
                return false;
            }
        }
    }

    /*
     * 保存任务和分支的关联关系。
     * Save task and branch relation.
     *
     * @param  int    $repoID
     * @param  int    $taskID
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function saveTaskRelation(int $repoID, int $taskID, string $branch): bool
    {
        $relation = new stdclass();
        $relation->AType    = 'task';
        $relation->AID      = $taskID;
        $relation->BType    = 'repobranch';
        $relation->BID      = $repoID;
        $relation->relation = 'linkrepobranch';
        $relation->extra    = $branch;
        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

        return !dao::isError();
    }

    /**
     * 根据路径获取gitlab文件列表。
     * Get gitlab files by path.
     *
     * @param  object $repo
     * @param  string $path
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getGitlabFilesByPath(object $repo, string $path = '', string $branch = ''): array
    {
        $fileList   = $this->getTreeByGraphql($repo, $path, $branch, 'blobs');
        $folderList = $this->getTreeByGraphql($repo, $path, $branch, 'trees');
        if(empty($fileList) && empty($folderList)) return array();

        $files    = array();
        $folders  = array();
        $fileSort = $dirSort = array(); // Use it to sort array.

        foreach($fileList as $file)
        {
            if(in_array($file->name, $fileSort)) continue;

            $base64Name = base64_encode($file->path);

            $fileInfo = new stdclass();
            $fileInfo->id   = $base64Name;
            $fileInfo->name = $file->name;
            $fileInfo->text = $file->name;
            $fileInfo->path = $file->path;
            $fileInfo->key  = $base64Name;
            $fileInfo->kind = 'file';

            $files[]    = $fileInfo;
            $fileSort[] = $file->name;
        }

        foreach($folderList as $dir)
        {
            if(in_array($dir->name, $dirSort)) continue;

            $base64Name = base64_encode($dir->path);

            $folder = new stdclass();
            $folder->id   = $base64Name;
            $folder->name = $dir->name;
            $folder->text = $dir->name;
            $folder->path = $dir->path;
            $folder->key  = $base64Name;
            $folder->kind = 'dir';
            $folder->items = array('url' => helper::createLink('repo', 'ajaxGetFiles', "repoID={$repo->id}&branch={$branch}&path=" . helper::safe64Encode($dir->path)));

            $folders[] = $folder;
            $dirSort[] = $dir->name;
        }
        array_multisort($fileSort, SORT_ASC, $files);
        array_multisort($dirSort, SORT_ASC, $folders);

        return array_merge($folders, $files);
    }

    /**
     * 通过Graphql获取GitLab文件列表。
     * Get GitLab files by Graphql.
     *
     * @param  object $repo
     * @param  string $path
     * @param  string $branch
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTreeByGraphql(object $repo, string $path = '', string $branch = '', string $type = 'blobs'): array
    {
        if(!$branch) $branch = $this->cookie->branch;

        $this->loadModel('gitlab');
        $fileList    = array();
        $endCursor   = '';
        $hasNextPage = true;
        $fullPath    = trim(str_replace($repo->client, '', $repo->codePath), '/');
        while($hasNextPage)
        {
            $query    = array('query' => 'query { project(fullPath: "' . $fullPath . '") {repository {tree(path: "' . trim($path, '/') . '", ref: "' . $branch . '") {' . $type . '(after: "' . $endCursor . '") {pageInfo {endCursor hasNextPage} nodes {name path}}}}}}');
            $response = $this->gitlab->apiGetByGraphql($repo->serviceHost, $query);

            if(!$endCursor && !isset($response->data->project->repository)) return array();

            $fileList    = array_merge($fileList, $response->data->project->repository->tree->{$type}->nodes);
            $hasNextPage = $response->data->project->repository->tree->{$type}->pageInfo->hasNextPage;
            $endCursor   = $response->data->project->repository->tree->{$type}->pageInfo->endCursor;
        }
        return $fileList;
    }
}
