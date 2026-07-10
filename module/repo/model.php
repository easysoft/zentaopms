<?php
declare(strict_types=1);
/**
 * The model file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@cnezsoft.com>
 * @package     repo
 * @property    dao     $dao
 * @property    object  $lang
 * @property    repoTao $repoTao
 * @link        https://www.zentao.net
 */
class repoModel extends model
{
    /**
     * 判断是否为 SVN 类型代码库。
     * Check if repo is subversion type.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function isSvn(object $repo): bool
    {
        return isset($repo->scmType) && strtolower($repo->scmType) == 'svn';
    }

    /**
     * 检查代码库的权限。
     * Check repo priv.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function checkPriv(object $repo): bool
    {
        $account = $this->app->user->account;
        $acl     = $repo->acl;

        if(strpos(",{$this->app->company->admins},", ",$account,") !== false || $acl == 'open') return true;

        if($acl == 'private')
        {
            $repoUsers = $this->getRepoUsers($repo->id);
            return in_array($account, $repoUsers);
        }
        return false;
    }

    /**
     * 设置菜单链接信息。
     * Set menu.
     *
     * @param  array  $repos
     * @param  int    $repoID
     * @param  int   $spaceID
     * @access public
     * @return void
     */
    public function setMenu(array $repos, int $repoID = 0, int $spaceID = 0)
    {
        if(empty($repoID)) $repoID = $this->session->repoID ? $this->session->repoID : key($repos);
        if(!isset($repos[$repoID])) $repoID = key($repos);

        $repoID = (int)$repoID;

        /* Check the privilege. */
        if($repoID)
        {
            $repo = $this->getByID($repoID);
            if(!$repo || !$this->checkPriv($repo)) $repoID = 0;
        }
        $this->loadModel('space')->setMenu($spaceID);

        if(!in_array($this->app->methodName, $this->config->repo->notSetMenuVars)) common::setMenuVars($this->config->vision == 'devops' ? 'repo' : 'devops', $repoID);
        $this->session->set('repoID', $repoID);
        $repo = $this->fetchByID($repoID);
        $this->session->set('devopsSpace', empty($repo) ? 0 : $repo->spaceID);

        /* 镜像代码库屏蔽"扫描(repoCodeScan)"、"代码问题(review)"与"设置(settings)"三个一级菜单。 */
        if($repo && !empty($repo->mirror))
        {
            unset($this->lang->devops->menu->repoCodeScan);
            unset($this->lang->devops->menu->review);
            unset($this->lang->devops->menu->settings);
        }

        /* SVN 代码库屏蔽分支、标签、代码评审、制品库、设置 5 个一级菜单。SVN 无原生分支/MR/制品库等概念。 */
        if($repo && $this->isSvn($repo))
        {
            unset($this->lang->devops->menu->branch);
            unset($this->lang->devops->menu->tag);
            unset($this->lang->devops->menu->ppm);
            unset($this->lang->devops->menu->artifact);
            unset($this->lang->devops->menu->settings);
        }

        if(in_array($this->app->methodName, array('setarchive', 'browsewebhooks', 'createwebhook', 'editwebhook', 'logwebhook'))) $this->lang->devops->menu->settings['subModule'] .= ',repo';
    }

    /**
     * 获取代码库列表。
     * Get repo list.
     *
     * @param  int    $projectID
     * @param  int    $space
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $getCodePath
     * @access public
     * @return array
     */
    public function getList(int $projectID = 0, int $space = 0, string $orderBy = 'id_desc', ?object $pager = null, bool $getCodePath = false, bool $lastSubmitTime = false, string $type = '', int $param = 0): array
    {
        $repoQuery = $type == 'bysearch' ? $this->repoTao->processSearchQuery($param) : '';
        $repos     = $this->getListByCondition($repoQuery, $space, $orderBy, $pager);

        /* Get products. */
        $productIdList = $this->loadModel('product')->getProductIDByProject($projectID, false);
        $providerParis = $this->loadModel('provider')->getList();
        foreach($repos as $i => $repo)
        {
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

            if(!empty($repo->mirror))
            {
                $repo->origin = isset($providerParis[$repo->providerID]) ? $providerParis[$repo->providerID]->type : '';
            }
            else
            {
                $repo->origin = 'GitFox';
            }
        }

        return $repos;
    }

    /**
     * 根据权限获取代码库列表。
     * Get list by priv.
     *
     * @param  string $type  all|haspriv
     * @access public
     * @return array
     */
    public function getListByPriv(string $type = 'all')
    {
        $repos = $this->dao->select('*,acl')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('status')->ne('importing')
            ->andWhere('synced')->eq(1)
            ->fetchAll('id', false);

        foreach($repos as $i => $repo)
        {
            if($type == 'haspriv' and !$this->checkPriv($repo)) unset($repos[$i]);
            $repo = $this->processGitService($repo);
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
    public function create(object $repo, bool $isPipelineServer): int|false
    {
        $this->dao->insert(TABLE_REPO)->data($repo, 'serviceToken,members,serviceHost')
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->batchCheckIF(!in_array($repo->SCM, $this->config->repo->notSyncSCM), 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceProject', 'notempty')
            ->batchCheckIF($repo->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM))
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        $repoID = $this->dao->lastInsertID();

        if($repoID && !empty($repo->acl) && $repo->acl === 'private')
        {
            $members = array_filter(explode(',', $repo->members ?? ''));
            if(!in_array($this->app->user->account, $members)) $members[] = $this->app->user->account;
            $this->updateMembers($repoID, $members);
        }

        $repo = $this->getByID($repoID);
        $res  = $this->loadModel('gitfox')->addPushWebhook($repo);
        if(!$res)
        {
            dao::$errors['webhook'][] = isset($res['message']) ? $res['message'] : $this->lang->gitlab->failCreateWebhook;
            return false;
        }
        $this->rmClientVersionFile();

        return $repoID;
    }

    /**
     * 创建远程版本库。
     * Create a repo.
     *
     * @param  object $repo
     * @access public
     * @return int|false
     */
    public function createRepo(object $repo): int|false
    {
        $check = $this->checkName($repo->name);
        if(!$check)
        {
            dao::$errors['name'] = $this->lang->repo->error->repoNameInvalid;
            return false;
        }
        $entry = $this->loadModel('entry')->getByCode('gitfox');
        if(empty($entry)) return false;

        $response = $this->loadModel('gitfox')->apiCreateRepo($repo);
        if(empty($response->id)) return false;

        $repoID = $response->id;

        if($repoID && !empty($repo->acl) && $repo->acl === 'private')
        {
            $members = array_filter(explode(',', $repo->members ?? ''));
            if(!in_array($this->app->user->account, $members)) $members[] = $this->app->user->account;
            $this->updateMembers($repoID, $members);
        }

        if($repoID)
        {
            $repo->id = $repoID;
            $res = $this->loadModel('gitfox')->addPushWebhook($repo);
            if(!$res)
            {
                dao::$errors['webhook'][] = isset($res['message']) ? $res['message'] : $this->lang->gitlab->failCreateWebhook;
                return false;
            }
        }

        return $repoID;
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
    public function createGitlabRepo(object $repo, string $namespace): object|false
    {
        $project = new stdclass();
        $project->name                   = $repo->name;
        $project->path                   = $repo->name;
        $project->description            = $repo->desc;
        $project->namespace_id           = (int)$namespace;
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
     * 批量创建版本库。
     * Batch create repos.
     *
     * @param  array  $repos
     * @param  int    $serviceHost
     * @param  string $scm
     * @access public
     * @return bool
     */
    public function batchCreate(array $repos, int $serviceHost, string $scm): bool
    {
        $this->loadModel('instance');
        foreach($repos as $index => $repo)
        {
            if(empty($repo->product) || empty($repo->space)) continue;
            if(empty($repo->name))
            {
                dao::$errors["name[$index]"] = sprintf($this->lang->error->notempty, $this->lang->repo->name);
                return false;
            }

            $repo->serviceHost = $serviceHost;
            $repo->SCM         = $scm;

            $this->dao->insert(TABLE_REPO)->data($repo)
                ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
                ->check('serviceHost,serviceProject', 'notempty')
                ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM))
                ->check('serviceProject', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM) . " and `serviceHost` = " . $this->dao->sqlobj->quote($repo->serviceHost))
                ->autoCheck()
                ->exec();

            if(dao::isError()) return false;

            $repoID = $this->dao->lastInsertID();

            if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
            {
                /* Add webhook. */
                $repo = $this->getByID($repoID);
                $this->loadModel($repo->SCM)->addPushWebhook($repo);
                $this->{$repo->SCM}->updateCodePath($repo->serviceHost, (int)$repo->serviceProject, (int)$repo->id);
            }

            $this->loadModel('action')->create('repo', $repoID, 'created');
            if(method_exists($this->instance, 'saveWaitSyncData')) $this->instance->saveWaitSyncData('repo', (string)$repoID, 'add', false);
        }

        return true;
    }

    /**
     * 更新版本库。
     * Update a repo.
     *
     * @param  object $data
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function update(object $data, object $repo): bool
    {
        $this->dao->update(TABLE_REPO)->data($data, 'serviceToken,members')
            ->batchCheck($this->config->repo->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($repo->id)
            ->exec();

        if($data->acl == 'private')
        {
            $this->updateMembers($repo->id, explode(',', $data->members));
        }
        else
        {
            $this->dao->delete()->from(TABLE_DEVOPSREPOUSER)->where('`repo`')->eq($repo->id)->exec();
        }
        if(dao::isError()) return false;

        return true;
    }

    /**
     * 代码提交关联任务、需求、Bug。
     * Link commit to story, bug, task.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $type
     * @param  string $from     repo|commit
     * @access public
     * @return void
     */
    public function link(int $repoID, string $revision, string $type = 'story', string $from = 'repo'): bool
    {
        $this->loadModel('action');
        if($type == 'story') $links = $objects['stories'] = $this->post->stories;
        if($type == 'bug')   $links = $objects['bugs']    = $this->post->bugs;
        if($type == 'task')  $links = $objects['task']    = $this->post->tasks;

        $revisionInfo = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch();
        if(empty($revisionInfo))
        {
            $repo = $this->getByID($repoID);
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $logs = $scm->getCommits($revision, 1);
            $this->saveCommit($repoID, $logs, 0);
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
            $relation->product  = 0;

            /* record module related information. */
            $this->loadModel($type)->updateLinkedCommits((int)$linkID, $repoID, [$revisionID]);
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            $this->action->create($type, (int)$linkID, 'linked2revision', '', substr($revisionInfo->revision, 0, 10), $committer);
        }
        return !dao::isError();
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
     * 取消代码提交关联的任务、需求、Bug。
     * Unlink object and commit revision.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $objectType story|bug|task
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function unlink(int $repoID, string $revision, string $objectType, int $objectID): bool
    {
        $revisionID = $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch('id');
        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AID')->eq($revisionID)
            ->andWhere('AType')->eq('revision')
            ->andWhere('relation')->eq('commit')
            ->andWhere('BType')->eq($objectType)
            ->andWhere('BID')->eq($objectID)->exec();

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq($objectType)
            ->andWhere('AID')->eq($objectID)
            ->andWhere('BType')->eq('commit')
            ->andWhere('BID')->eq($revisionID)
            ->andWhere('relation')->eq('completedin')->exec();

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq('commit')
            ->andWhere('AID')->eq($revisionID)
            ->andWhere('BType')->eq('story')
            ->andWhere('BID')->eq($objectID)
            ->andWhere('relation')->eq('completedfrom')->exec();

        if(!dao::isError()) $this->loadModel('action')->create($objectType, $objectID, 'unlinkedfromrevision', '', substr($revision, 0, 10));
        return !dao::isError();
    }

    /**
     * 设置代码库id。
     * Save repo state.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function saveState(int $repoID = 0, int $objectID = 0): int
    {
        if($repoID > 0) $this->session->set('repoID', (int)$repoID);

        $repos = $this->getRepoPairs($this->app->tab, $objectID);
        if($repoID == 0 && $this->session->repoID == '') $this->session->set('repoID', key($repos));

        if(!isset($repos[$this->session->repoID])) $this->session->set('repoID', key($repos));

        $repoID = (int)$this->session->repoID;

        return $repoID;
    }

    /**
     * 获取代码库列表键值对。
     * Get repo pairs.
     *
     * @param  string $type  project|execution|repo
     * @param  int    $projectID
     * @param  bool   $showScm
     * @access public
     * @return array
     */
    public function getRepoPairs(string $type = '', int $projectID = 0, bool $showScm = false): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRepoPairs();

        $userSpaces = $this->loadModel('space')->getPairs($this->app->user->account);
        $repos = $this->dao->select('*,acl')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('importing')
            ->andWhere('spaceID')->in(array_keys($userSpaces))
            ->fetchAll('id', false);

        /* Get products. */
        $productIdList = ($type == 'project' or $type == 'execution') ? $this->loadModel('product')->getProductIDByProject($projectID, false) : array();

        $repoPairs = array();
        foreach($repos as $repo)
        {
            $scm = '';
            if($showScm) $scm = '[GitFox] ';
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
     * 根据应用获取代码库分组。
     * Get repos group by repo type.
     *
     * @param  string $type
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getRepoGroup(string $type, int $projectID = 0): array
    {
        $repos      = $this->getList();
        $productIds = $productItems = array();
        if($projectID)
        {
            $productIds = $this->loadModel('product')->getProductIDByProject($projectID, false);
        }
        else
        {
            foreach($repos as $repo) $productIds = array_merge($productIds, explode(',', $repo->product));
        }

        $products = $this->loadModel('product')->getByIdList(array_unique($productIds));
        foreach($products as $productID => $product)
        {
            $productItem = array();
            $productItem['pid']   = $productID;
            $productItem['type']  = $product->shadow ? $this->lang->project->common : 'product';
            $productItem['text']  = $product->name;
            $productItem['items'] = array();

            $productItems[$productID] = $productItem;
        }

        /* Get project products. */
        $projectProductIds = in_array($type, array('project', 'execution')) ? $this->loadModel('product')->getProductIDByProject($projectID, false) : array();

        /* Get repo data for dropmenu. */
        $repoPairs = array();
        foreach($repos as $repo)
        {
            if($this->checkPriv($repo))
            {
                $repoItem = array();
                $repoItem['id']       = $repo->id;
                $repoItem['text']     = $repo->name;
                $repoItem['keys']     = zget(common::convert2Pinyin(array($repo->name)), $repo->name, '');
                $repoItem['data-app'] = $this->app->tab;

                $repoProducts = explode(',', $repo->product);
                foreach($repoProducts as $productID)
                {
                    if(!$productID) continue;
                    if(in_array($type, array('project', 'execution')) && $projectID && !in_array($productID, $projectProductIds)) continue;

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
     * 根据ID获取代码库。
     * Get repo by id.
     *
     * @param  int    $repoID
     * @access public
     * @return object|false
     */
    public function getByID(int $repoID): object|false
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRepo();

        if(empty($repoID)) return false;
        $repo = $this->fetchByID($repoID);
        if(!$repo) return false;

        if($repo->acl == 'private')
        {
            $repo->members = $this->getRepoUsers($repo->id);
        }
        else
        {
            $space = $this->loadModel('space')->getByID($repo->spaceID);
            $repo->members = $space->acl == 'private' ? zget($space, 'members', array()) : $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        }

        $repo = $this->processGitService($repo);
        return $repo;
    }

    /**
     * 处理代码库路径。
     * Parse repo path.
     *
     * @param  string $serverUrl
     * @param  string $path
     * @access public
     * @return string
     */
    public function parseRepoPath(string $path): string
    {
        $serverURL = $this->config->devops->gitfoxURL;
        if(!empty($this->config->devops->gitfoxPort) && $this->config->devops->gitfoxPort != 80) $serverURL .= ':' . $this->config->devops->gitfoxPort;

        $serverUrl = trim($serverURL, '/');
        $parseUrl  = parse_url($path);

        $replaceStr = $parseUrl['scheme'] . '://' . $parseUrl['host'];
        if(isset($parseUrl['port'])) $replaceStr .= ':' . $parseUrl['port'];
        $path = str_replace($replaceStr, $serverUrl,  $path);

        return $path;
    }

    /**
     * 根据URL获取代码库。
     * Get repo by url.
     *
     * @param  string $url
     * @access public
     * @return array
     */
    public function getRepoByUrl(string $url): array
    {
        if(empty($url)) return array('result' => 'fail', 'message' => 'Url is empty.');

        $matches = $this->repoTao->getMatchedReposByUrl($url);
        if(empty($matches)) return array('result' => 'fail', 'message' => 'No matched gitlab.');

        $conditions = array();
        foreach($matches as $matched) $conditions[] = "(`serviceHost`='{$matched['gitlab']}' and `serviceProject`='{$matched['project']}')";

        $matchedRepos = $this->getListByCondition('(' . implode(' OR ', $conditions). ')');
        if(empty($matchedRepos)) return array('result' => 'fail', 'message' => 'No matched gitlab.');

        $matchedRepo = new stdclass();
        foreach($matchedRepos as $repo)
        {
            if(!empty($repo->preMerge))
            {
                $matchedRepo = $repo;
                break;
            }
        }
        if(empty($matchedRepo)) return array('result' => 'fail', 'message' => 'Matched gitlab is not open pre merge.');

        return array('result' => 'success', 'data' => $matchedRepo);
    }

    /**
     * 根据URL获取代码库列表。
     * Get repo list by url.
     *
     * @param  string $url
     * @access public
     * @return array
     */
    public function getRepoListByUrl(string $url = ''): array
    {
        if(empty($url)) return array('status' => 'fail', 'message' => 'Url is empty.');

        $matches = $this->repoTao->getMatchedReposByUrl($url);
        if(empty($matches)) return array('status' => 'fail', 'message' => 'No matched gitlab.');

        $conditions = array();
        foreach($matches as $matched) $conditions[] = "(`serviceHost`='{$matched['gitlab']}' and `serviceProject`='{$matched['project']}')";

        $matchedRepos = $this->getListByCondition('(' . implode(' OR ', $conditions). ')', 'Gitlab');
        foreach($matchedRepos as $key => $repo)
        {
            if(!$this->checkPriv($repo)) unset($matchedRepos[$key]);
        }
        if(empty($matchedRepos)) return array('status' => 'fail', 'message' => 'No matched gitlab.');

        return array('status' => 'success', 'repos' => $matchedRepos);
    }

    /**
     * 根据ID列表获取代码库列表。
     * Get by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByIdList(array $idList): array
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('importing')
            ->andWhere('id')->in($idList)->fetchAll('id', false);
        foreach($repos as $repo)
        {
            if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
        }

        return $repos;
    }

    /**
     * 获取代码库的分支列表。
     * Get git branches.
     *
     * @param  object  $repo
     * @param  bool    $printLabel
     * @param  string  $source  select current repo's branches from scm or database.
     * @access public
     * @return array
     */
    public function getBranches(object $repo, bool $printLabel = false, string $source = 'scm'): array
    {
        if($source == 'database')
        {
            $branches = $this->dao->select('branch')->from(TABLE_REPOBRANCH)
                ->where('repo')->eq($repo->id)
                ->fetchPairs();
        }
        else
        {
            $this->scm = $this->app->loadClass('scm');
            $this->scm->setEngine($repo);
            $branches = $this->scm->branch();
        }

        if($printLabel)
        {
            foreach($branches as &$branch) $branch = 'Branch::' . $branch;
        }

        return $branches;
    }

    /**
     * 根据提交ID获取提交信息。
     * Get commit by id.
     *
     * @param  array  $revisions
     * @access public
     * @return void
     */
    public function getCommitsByRevisions(array $revisions): array
    {
        return $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->fetchPairs('id');
    }

    /**
     * 获取代码库的提交列表。
     * Get commits.
     *
     * @param  object        $repo
     * @param  string        $entry
     * @param  string        $revision
     * @param  string        $type
     * @param  object        $pager
     * @param  string        $begin
     * @param  string        $end
     * @param  object|string $query
     * @access public
     * @return array
     */
    public function getCommits(object $repo, string $entry, string $revision = 'HEAD', string $type = 'dir', ?object $pager = null, string $begin = '', string $end = '', mixed $query = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCommits();

        if(!isset($repo->id)) return array();
        return $this->loadModel('gitfox')->getCommits($repo, $entry, $pager, $begin, $end, $query);
    }

    /**
     * 获取最后一次提交的信息。
     * Get latest commit.
     *
     * @param  int    $repoID
     * @param  bool   $checkCount
     * @access public
     * @return object|false
     */
    public function getLatestCommit(int $repoID, bool $checkCount = true): object|false
    {
        $repo        = $this->fetchByID($repoID);
        $branchID    = (string)$this->cookie->repoBranch;
        $lastComment = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($branchID)->andWhere('t2.branch')->eq($branchID)->fi()
            ->orderBy('t1.`time` desc')
            ->fetch();
        if(empty($lastComment)) return false;

        $lastComment->svnRevision = intval($lastComment->revision);
        if(!$checkCount) return $lastComment;

        $count = $this->dao->select('count(DISTINCT t1.id) as count')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($branchID)->andWhere('t2.branch')->eq($branchID)->fi()
            ->fetch('count');

        if($repo->SCM == 'Git' && $lastComment->commit != $count)
        {
            $this->fixCommit($repo->id);
            $lastComment->commit = $count;
        }

        return $lastComment;
    }

    /**
     * 从数据库中获取提交记录。
     * Get revisions from db.
     *
     * @param  int    $repoID
     * @param  int    $limit
     * @param  string $maxRevision
     * @param  string $minRevision
     * @access public
     * @return array
     */
    public function getRevisionsFromDB(int $repoID, int $limit = 0, string $maxRevision = '', string $minRevision = ''): array
    {
        $revisions = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->beginIF($this->cookie->repoBranch)->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')->fi()
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
     * 获取代码提交记录。
     * Get history.
     *
     * @param  int    $repoID
     * @param  array  $revisions
     * @access public
     * @return array
     */
    public function getHistory(int $repoID, array $revisions): array
    {
        return $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->beginIF($this->cookie->repoBranch)->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')->fi()
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t1.revision')->in($revisions)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->fetchAll('revision');
    }

    /**
     * 查询提交记录的名称。
     * Get git revisionName.
     *
     * @param  string $revision
     * @param  int    $commit
     * @access public
     * @return string
     */
    public function getGitRevisionName(string $revision, int $commit): string
    {
        if(empty($commit)) return substr($revision, 0, 10);
        return substr($revision, 0, 10) . '<span title="' . sprintf($this->lang->repo->commitTitle, $commit) . '"> (' . $commit . ') </span>';
    }

    /**
     * 获取缓存文件位置。
     * Get cache file.
     *
     * @param  int    $repoID
     * @param  string $path
     * @param  int    $revision
     * @access public
     * @return string
     */
    public function getCacheFile(int $repoID, string $path, string $revision): string
    {
        $cachePath = $this->app->getCacheRoot() . '/repo/' . $repoID;
        if(!is_dir($cachePath)) mkdir($cachePath, 0777, true);
        if(!is_writable($cachePath)) return false;
        return $cachePath . '/' . md5("{$this->cookie->repoBranch}-$path-$revision");
    }

    /**
     * 查询代码库关联的产品列表。
     * Get products by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getProductsByRepo(int $repoID): array
    {
        $repo = $this->getByID($repoID);
        if(empty($repo->id)) return array();

        return $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('id')->in($repo->product)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * 保存代码提交信息并返回保存数量。
     * Save commit.
     *
     * @param  int    $repoID
     * @param  array  $logs
     * @param  int    $version
     * @param  string $branch
     * @access public
     * @return int
     */
    public function saveCommit(int $repoID, array $logs, int $version, string $branch = ''): int
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

                        $copyfromPath = !empty($file->copyfromPath) ? $file->copyfromPath : '';
                        $copyfromRev  = !empty($file->copyfromRev) ? $file->copyfromRev : '';
                        unset($file->copyfromPath);
                        unset($file->copyfromRev);

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

                        if(!empty($copyfromPath) && !empty($copyfromRev)) $this->repoTao->copySvnDir($repoID, $copyfromPath, $copyfromRev, $file->path);
                    }
                }
                $revisionPairs[$commit->revision] = $commit->revision;
                $version ++;
                $count ++;
            }

            dao::$errors = array();
        }
        return $count;
    }

    /**
     * 保存单个提交信息。
     * Save One Commit.
     *
     * @param  int    $repoID
     * @param  object $commit
     * @param  int    $version
     * @param  string $branch
     * @access public
     * @return int
     */
    public function saveOneCommit(int $repoID, object $commit, int $version, string $branch = ''): int
    {
        $existsRevision = $this->dao->select('id,revision')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($commit->revision)->fetch();
        if($existsRevision)
        {
            if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($existsRevision->id)->set('branch')->eq($branch)->exec();
            return $version;
        }

        $history = new stdclass();
        $history->repo      = $repoID;
        $history->commit    = $version;
        $history->revision  = $commit->revision;
        $history->comment   = htmlSpecialString($commit->comment);
        $history->committer = $commit->committer;
        $history->time      = $commit->time;
        $this->dao->insert(TABLE_REPOHISTORY)->data($history)->exec();
        if(!dao::isError())
        {
            $commitID = $this->dao->lastInsertID();
            if($branch) $this->dao->replace(TABLE_REPOBRANCH)->set('repo')->eq($repoID)->set('revision')->eq($commitID)->set('branch')->eq($branch)->exec();
            foreach($commit->change as $file => $info)
            {
                $parentPath = dirname($file);

                $copyfromPath = !empty($info['copyfrom-path']) ? $info['copyfrom-path'] : '';
                $copyfromRev  = !empty($info['copyfrom-rev']) ? $info['copyfrom-rev']: '';

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

                if(!empty($copyfromPath) && !empty($copyfromRev)) $this->repoTao->copySvnDir($repoID, $copyfromPath, $copyfromRev, $repoFile->path);
            }

            $version ++;
        }

        dao::$errors = array();
        return $version;
    }

    /**
     * 保存已存在的分支日志。
     * Save exists log branch.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function saveExistCommits4Branch(int $repoID, string $branch): bool
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

        return !dao::isError();
    }

    /**
     * 更新代码库的提交次数。
     * Update commit count.
     *
     * @param  int    $repoID
     * @param  int    $count
     * @access public
     * @return bool
     */
    public function updateCommitCount(int $repoID, int $count): bool
    {
        $this->dao->update(TABLE_REPO)->set('commits')->eq($count)->where('id')->eq($repoID)->exec();
        return !dao::isError();
    }

    /**
     * 获取未同步的提交。
     * Get unsync commits.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getUnsyncedCommits(object $repo): array
    {
        $repoID   = $repo->id;
        $lastInDB = $this->getLatestCommit($repoID);

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $logs = $scm->log('', $lastInDB ? $lastInDB->revision : 0);
        if(empty($logs)) return array();

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
     * 生成链接。
     * Create link for repo.
     *
     * @param  string $method
     * @param  string $params
     * @param  string $viewType
     * @access public
     * @return string
     */
    public function createLink(string $method, string $params = '', string $viewType = 'html')
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api' && isset($this->config->originRequestType)) $this->config->requestType = $this->config->originRequestType;
        if($this->config->requestType == 'GET') return helper::createLink('repo', $method, $params, $viewType);

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
        $link   = helper::createLink('repo', $method, $params, $viewType);
        if(empty($pathParams)) return $link;

        $link .= strpos($link, '?') === false ? '?' : '&';
        $link .= $pathParams;
        return $link;
    }

    /**
     * 更新代码库的同步状态。
     * Mark synced status.
     *
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function markSynced(int $repoID): bool
    {
        $this->fixCommit($repoID);
        $this->dao->update(TABLE_REPO)->set('synced')->eq(1)->where('id')->eq($repoID)->exec();
        return !dao::isError();
    }

    /**
     * 更新提交记录的排序。
     * Fix commit.
     *
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function fixCommit(int $repoID): bool
    {
        $historyList = $this->dao->select('DISTINCT t1.id,t1.`time`')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('time')
            ->query();

        foreach($historyList as $i => $repoHistory)
        {
            $i++;

            $this->dao->update(TABLE_REPOHISTORY)->set('`commit`')->eq($i)->where('id')->eq($repoHistory->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * 转义代码库文件路径。
     * Encode repo path.
     *
     * @param  string $path
     * @access public
     * @return string
     */
    public function encodePath(string $path = ''): string
    {
        if(empty($path)) return $path;
        return helper::safe64Encode(urlencode($path));
    }

    /**
     * 解析代码库文件路径。
     * Decode repo path.
     *
     * @param  string $path
     * @access public
     * @return string
     */
    public function decodePath(string $path = ''): string
    {
        if(empty($path)) return $path;
        return trim(urldecode(helper::safe64Decode($path)), '/');
    }

    /**
     * 删除客户端代码工具生成的版本文件。
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
            $this->session->set('clientVersionFile', '');

            if(file_exists($clientVersionFile)) @unlink($clientVersionFile);
        }
    }

    /**
     * 替换提交记录中的链接。
     * Replace comment link.
     *
     * @param  string $comment
     * @access public
     * @return string
     */
    public function replaceCommentLink(string $comment): string
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
     * 解析提交记录中的链接。
     * Add link.
     *
     * @param  array  $matches
     * @param  string $method
     * @access public
     * @return array
     */
    public function addLink(array $matches, string $method): array
    {
        if(empty($matches)) return array();

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
     * 解析git和svn的注释，从中提取对象id列表。
     * Parse the comment of git and svn, extract object id list from it.
     *
     * @param  string $comment
     * @access public
     * @return array
     */
    public function parseComment(string $comment): array
    {
        $rules   = $this->processRules();
        $stories = array();
        $actions = array();
        $designs = array();

        $tasks = $this->repoTao->parseTaskComment($comment, $rules, $actions);
        $bugs  = $this->repoTao->parseBugComment($comment, $rules, $actions);

        preg_match_all("/{$rules['taskReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $tasks[$id] = $id;
            }
        }

        preg_match_all("/{$rules['bugReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $bugs[$id] = $id;
            }
        }

        preg_match_all("/{$rules['storyReg']}/i", $comment, $matches);
        if($matches[0])
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
                foreach($idMatches[0] as $id) $stories[$id] = $id;
            }
        }

        preg_match_all("/{$rules['designReg']}/i", $comment, $matches);
        if($matches[0])
        {
            $designs = implode(' ', $matches[1]);
            if($designs) $designs = array_unique(explode(' ', str_replace(',', ' ', $designs)));
        }

        return array('stories' => $stories, 'tasks' => $tasks, 'bugs' => $bugs, 'actions' => $actions, 'designs' => $designs);
    }

    /**
     * 转码提交注释信息。
     * Convert encoding of comment.
     *
     * @param  string $comment
     * @param  string $encodings
     * @access public
     * @return string
     */
    public function iconvComment(string $comment, string $encodings): string
    {
        /* Get encodings. */
        if($encodings == '') return $comment;

        /* Try convert. */
        $encodings = explode(',', $encodings);
        foreach($encodings as $encoding)
        {
            if($encoding == 'utf-8') continue;

            $result = helper::convertEncoding($comment, $encoding);
            if($result) return $result;
        }

        return $comment;
    }

    /**
     * 解析提交指令规则。
     * Process rules to REG.
     *
     * @access public
     * @return array
     */
    public function processRules(): array
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
        $reg['designReg']     = 'design(?:\s){0,}(?:#|:|：){0,}([0-9, ]{1,})';
        return $reg;
    }

    /**
     * 保存提交信息到系统。
     * Save action to pms.
     *
     * @param  array  $objects
     * @param  object $log
     * @param  string $repoRoot
     * @param  string $encodings
     * @param  string $scm
     * @param  array  $gitlabAccountPairs
     * @access public
     * @return bool
     */
    public function saveAction2PMS(array $objects, object $log, string $repoRoot = '', string $encodings = 'utf-8', string $scm = 'svn', array $gitlabAccountPairs = array()): bool
    {
        $committers  = $this->loadModel('user')->getCommiters('account');
        $log->author = zget($gitlabAccountPairs, $log->author, zget($committers, $log->author));

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
        $changes = $this->createActionChanges($log, $repoRoot, $scm);
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

                $this->setTaskByCommit($task, $taskActions, $action, $changes, $scm);
                unset($objects['tasks'][$taskID]);
                dao::$errors = array();
            }
        }

        if(isset($actions['bug'])) $objects['bugs'] = $this->setBugStatusByCommit($objects['bugs'], $actions, $action, $changes);

        $action->action = $scm == 'svn' ? 'svncommited' : 'gitcommited';
        $this->saveObjectToPms($objects, $action, $changes);

        if(isset($this->app->user)) $this->app->user->account = $account;
        return !dao::isError();
    }

    /**
     * 保存commit触发的操作日志信息。
     * Save an action to pms.
     *
     * @param  object $action
     * @param  array  $changes
     * @access public
     * @return bool
     */
    public function saveRecord(object $action, array $changes): bool
    {
        /* Remove sql error. */
        dao::getError();

        $record = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($action->objectType)
            ->andWhere('objectID')->eq($action->objectID)
            ->andWhere('extra')->eq($action->extra)
            ->andWhere('action')->eq($action->action)
            ->beginIf(!empty($action->comment))->andWhere('comment')->eq(zget($action, 'comment', ''))->fi()
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

        return !dao::isError();
    }

    /**
     * 从日志中为设置变更信息。
     * Create changes for action from a log.
     *
     * @param  object $log
     * @param  string $repoRoot
     * @access public
     * @return array
     */
    public function createActionChanges(object $log, string $repoRoot, string $scm = 'svn'): array
    {
        if(empty($log->files)) return array();

        $oldSelf = $this->server->PHP_SELF;
        $this->server->set('PHP_SELF', $this->config->webRoot, '', false, true);

        $diff = '';
        foreach($log->files as $action => $actionFiles)
        {
            foreach($actionFiles as $file)
            {
                $path = $this->encodePath($file);
                $viewURL = $this->createLink('view', "repoID={$log->repo->id}&objectID=0&entry={$path}&revision={$log->revision}");
                $diffURL = $this->createLink('diff', "repoID={$log->repo->id}&objectID=0&entry={$path}&oldRevision={$log->revision}&revision={$log->revision}");

                $catLink  = trim(html::a($viewURL, 'view', 'modal', "data-toggle='modal' data-size='lg'"));
                $diffLink = trim(html::a($diffURL, 'diff', 'modal', "data-toggle='modal' data-size='lg'"));

                $catLink  = str_replace('+', '%2B', $catLink);
                $diffLink = str_replace('+', '%2B', $diffLink);

                $diff .= $action . " " . $file . " $catLink ";
                $diff .= $action == 'M' ? "$diffLink\n" : "\n" ;
            }
        }

        $this->server->set('PHP_SELF', $oldSelf);

        $change = new stdclass();
        $change->field = $scm == 'svn' ? 'subversion' : 'git';
        $change->old   = '';
        $change->new   = '';
        $change->diff  = trim($diff);
        return array($change);
    }

    /**
     * 根据任务列表获取产品和执行。
     * Get products and executions of tasks.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function getTaskProductsAndExecutions(array $tasks): array
    {
        $records = array();
        $products = $this->dao->select('t1.id,t1.execution,t2.product')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.execution = t2.project')
            ->where('t1.id')->in($tasks)
            ->fetchGroup('id','product');

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
     * 根据bug列表获取产品和执行。
     * Get products and executions of bugs.
     *
     * @param  array    $bugs
     * @access public
     * @return array
     */
    public function getBugProductsAndExecutions(array $bugs): array
    {
        $records = $this->dao->select('id, execution, product')->from(TABLE_BUG)->where('id')->in($bugs)->fetchAll('id');
        foreach($records as $record) $record->product = ",{$record->product},";
        return $records;
    }

    /**
     * 构造git和svn的展示链接。
     * Build url for git and svn.
     *
     * @param  string $methodName
     * @param  string $url
     * @param  string $revision
     * @access public
     * @return string
     */
    public function buildURL(string $methodName, string $url, string $revision, string $scm = 'svn'): string
    {
        $buildedURL  = helper::createLink($scm, $methodName, "url=&revision=$revision", 'html');
        $buildedURL .= strpos($buildedURL, '?') === false ? '?' : '&';
        $buildedURL .= 'repoUrl=' . helper::safe64Encode($url);

        return $buildedURL;
    }

    /**
     * 处理代码库信息，增加代码路径和api路径。
     * Process git service, add code path and api path.
     *
     * @param  object $repo
     * @access public
     * @return object
     */
    public function processGitService(object $repo): object
    {
        $server = $this->loadModel('gitfox')->getServer();

        $singleRepo = $this->gitfox->apiGetSingleRepo((int)$repo->id);
        $repo->path = $singleRepo->gitURL;

        $repo->apiPath   = $server ? sprintf($this->config->repo->gitfox->apiPath, $server->url, $repo->id) : $repo->path;
        $repo->client    = $server ? $server->url : '';
        $repo->password  = $server ? $server->token : '';
        $repo->codePath  = isset($singleRepo->gitURL) ? $singleRepo->gitURL : $repo->path;
        $repo->importing = isset($singleRepo->importing) ? $singleRepo->importing : false;
        return $repo;
    }

    /**
     * 检查webhook提交的工时是否已被记录。
     * Check whether the webhook commit effort has been recorded.
     *
     * @param  object $commit
     * @access protected
     * @return bool
     */
    protected function isRecordedWebhookCommit(object $commit): bool
    {
        $revision = '';
        if(isset($commit->id)  && is_scalar($commit->id))  $revision = (string)$commit->id;
        if($revision === '' && isset($commit->sha) && is_scalar($commit->sha)) $revision = (string)$commit->sha;

        $message = '';
        if(isset($commit->message) && is_string($commit->message)) $message = $commit->message;
        if($message === '' && isset($commit->Message) && is_string($commit->Message)) $message = $commit->Message;
        if($revision === '' || $message === '') return false;

        $shortRevision = substr($revision, 0, 10);
        $workSuffix    = '#' . $shortRevision . "\n" . htmlspecialchars($this->iconvComment($message, 'utf-8'), ENT_QUOTES, 'UTF-8');

        /* @phpstan-ignore-next-line */
        $efforts = $this->dao->select('id,work')->from(TABLE_EFFORT)
            ->where('deleted')->eq('0')
            ->andWhere('work')->like("%#{$shortRevision}%")
            ->fetchAll();

        foreach($efforts as $effort)
        {
            if(isset($effort->work) && is_string($effort->work) && str_ends_with($effort->work, $workSuffix)) return true;
        }

        return false;
    }

    /**
     * 处理webhook请求。
     * Handle received GitLab webhook.
     *
     * @param  string $event
     * @param  object $data
     * @param  object $repotime
     * @access public
     * @return bool
     */
    public function handleWebhook(string $event, object $data, object $repo): bool
    {
        if(!in_array($event, array('Push Hook', 'Merge Request Hook', 'branch_updated'))) return false;
        if(empty($data->commits)) return false;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $accountPairs = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');

        foreach($data->commits as $commit)
        {
            if($this->isRecordedWebhookCommit($commit)) continue;

            $time = zget($commit, 'timestamp', '');
            if(isset($commit->author->when)) $time = $commit->author->when;

            $log = new stdclass();
            $log->revision = isset($commit->id) ? $commit->id : $commit->sha;
            $log->msg      = $commit->message;
            $log->author   = isset($commit->author->identity->name) ? $commit->author->identity->name : $commit->author->name;
            $log->date     = date("Y-m-d H:i:s", strtotime($time));
            $log->files    = array();
            $log->repo     = $repo;

            if(!isset($commit->added))
            {
                $diffs = $scm->engine->getFilesByCommit($log->revision);
                if(!empty($diffs))
                {
                    foreach($diffs as $diff) $log->files[$diff->action][] = $diff->path;
                }
            }
            else
            {
                foreach($commit->added as $file)    $log->files['A'][] = $file;
                foreach($commit->removed as $file)  $log->files['D'][] = $file;
                foreach($commit->modified as $file) $log->files['M'][] = $file;
            }

            $objects = $this->parseComment($log->msg);
            $this->saveAction2PMS($objects, $log, '', 'utf-8', 'git', $accountPairs);

            if(!empty($objects['stories']) || !empty($objects['tasks']) || !empty($objects['bugs']))
            {
                $historyLog = new stdclass();
                $historyLog->committer = $log->author;
                $historyLog->revision  = $log->revision;
                $historyLog->comment   = $commit->message;
                $historyLog->time      = date("Y-m-d H:i:s", strtotime($time));
                $this->saveCommit($repo->id, array('commits' => [$historyLog]), 0);
                $revisions = $this->dao->select('id')->from(TABLE_REPOHISTORY)
                    ->where('revision')->in($log->revision)
                    ->andWhere('repo')->eq($repo->id)
                    ->fetchPairs('id');
                foreach (array('stories' => 'story', 'tasks' => 'task', 'bugs' => 'bug') as $objectType=>$modelType)
                {
                    if(!empty($objects[$objectType]))
                    {
                        foreach($objects[$objectType] as $modelID)
                        {
                            $this->loadModel($modelType)->updateLinkedCommits((int)$modelID, $repo->id, $revisions);
                        }
                    }
                }
            }
        }
        return !dao::isError();
    }

    /**
     * Get execution pairs.
     *
     * @param  int    $product
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getExecutionPairs(int $product, int $branch = 0): array
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
     * 获取代码库的clone地址。
     * Get clone url.
     *
     * @param  object $repo
     * @access public
     * @return object
     */
    public function getCloneUrl(object $repo): object
    {
        if(empty($repo->id)) return new stdclass();

        $url = new stdClass();
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        $url = $this->scm->getCloneUrl();

        return $url;
    }

    /**
     * 获取代码文件的提交信息。
     * Get file commits.
     *
     * @param  object $repo
     * @param  string $branch
     * @param  string $parent
     * @access public
     * @return array
     */
    public function getFileCommits(object $repo, string $branch, string $parent = ''): array
    {
        /* Get file commits by repo. */
        if(empty($branch)) $branch = $this->cookie->repoBranch;

        $parent = '/' . ltrim($parent, '/');
        if($repo->prefix) $parent = rtrim($repo->prefix . $parent, '/');

        $fileCommits = $this->dao->select('t1.id,t1.path,t1.type,t1.action,t1.`oldPath`,t1.parent,t2.revision,t2.comment,t2.committer,t2.time')->from(TABLE_REPOFILES)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
            ->beginIF($repo->SCM != 'Subversion' && $branch)->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')->fi()
            ->where('t1.repo')->eq($repo->id)
            ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
            ->beginIF($branch)->andWhere('t3.branch')->eq($branch)->fi()
            ->andWhere('t1.parent')->like("$parent%")
            ->orderBy('t2.`time` asc')
            ->fetchAll('path');

        $files = $folders = $fileSort = $dirSort = array();
        $existsFiles = array();
        foreach($fileCommits as $fileCommit)
        {
            if($fileCommit->action != 'D' && strpos($fileCommit->path, $parent) === 0) $existsFiles[$fileCommit->path] = true;
            if($fileCommit->action == 'R' && isset($existsFiles[$fileCommit->oldPath])) unset($existsFiles[$fileCommit->oldPath]);
        }

        foreach($fileCommits as $fileCommit)
        {
            /* Filter by parent. */
            if(!isset($existsFiles[$fileCommit->path])) continue;

            $pathList = explode('/', ltrim($fileCommit->path, '/'));
            $file     = new stdclass();
            $file->revision = $fileCommit->revision;
            $file->comment  = $fileCommit->comment;
            $file->account  = $fileCommit->committer;
            $file->date     = $fileCommit->time;
            $file->kind     = 'file';
            $file->name     = end($pathList);

            if($fileCommit->parent == $parent && $fileCommit->type == 'file')
            {
                $files[]    = $file;
                $fileSort[] = $file->name;
            }
            else
            {
                $childPath = explode('/', ltrim(substr($fileCommit->path, strlen($parent)), '/'));
                $fileName  = $fileCommit->type == 'dir' ? end($pathList) : $childPath[0];
                if(in_array($fileName, $dirSort)) continue;

                $file->name = $fileName;
                $file->kind = 'dir';
                $folders[]  = $file;
                $dirSort[]  = $fileName;
            }
        }
        array_multisort($fileSort, SORT_ASC, $files);
        array_multisort($dirSort, SORT_ASC, $folders);

        return array_merge($folders, $files);
    }

    /**
     * 获取目录树。
     * Get html for file tree.
     *
     * @param  object $repo
     * @param  string $branch
     * @param  array  $diffs
     * @access public
     * @return array
     */
    public function getFileTree(object $repo, string $branch = '', ?array $diffs = null): array
    {
        set_time_limit(0);
        $allFiles = array();
        if(is_null($diffs))
        {
            if(empty($branch)) $branch = $this->cookie->repoBranch;
            $files = $this->dao->select('t1.path,t2.time,t1.action')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
                ->where('t1.repo')->eq($repo->id)
                ->andWhere('t1.type')->eq('file')
                ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
                ->beginIF($branch)->andWhere('t3.branch')->eq($branch)->fi()
                ->orderBy('t2.`time` asc')
                ->fetchAll('path');

            foreach($files as $file)
            {
                if($file->action != 'D') $allFiles[] = $file->path;
            }
        }
        else
        {
            foreach($diffs as $diff) $allFiles[] = $diff->fileName;
        }

        return $this->repoTao->buildFileTree($allFiles);
    }

    /**
     * 根据提交获取关联信息。
     * Get relation by commit.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @param  string $type story|bug|task
     * @access public
     * @return array
     */
    public function getRelationByCommit(int $repoID, string $commit, string $type = ''): array
    {
        $relationList = $this->dao->select('t1.`BID` as id, t1.`BType` as type')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.`AID` = t2.id')
            ->where('t2.revision')->eq($commit)
            ->andWhere('t2.repo')->eq($repoID)
            ->andWhere('t1.`AType`')->eq('revision')
            ->beginIF($type)->andWhere('t1.`BType`')->eq($type)->fi()
            ->fetchGroup('type', 'id');

        $stories = empty($relationList['story']) ? array() : $this->loadModel('story')->getByList(array_keys($relationList['story']));
        $bugs    = empty($relationList['bug'])   ? array() : $this->loadModel('bug')->getByIdList(array_keys($relationList['bug']));
        $tasks   = empty($relationList['task'])  ? array() : $this->loadModel('task')->getByIdList(array_keys($relationList['task']));

        $index     = 0;
        $titleList = array();
        foreach($relationList as $objectType => $objects)
        {
            foreach($objects as $object)
            {
                $titleList[$index] = array(
                    'id'    => $object->id,
                    'type'  => $objectType,
                    'title' => "#$object->id "
                );
                if($objectType == 'story')
                {
                    $story = zget($stories, $object->id, array());
                    $titleList[$index]['title'] .=  zget($story, 'title', '');
                }
                elseif($objectType == 'bug')
                {
                    $bug = zget($bugs, $object->id, array());
                    $titleList[$index]['title'] .=  zget($bug, 'title', '');
                }
                elseif($objectType == 'task')
                {
                    $task = zget($tasks, $object->id, array());
                    $titleList[$index]['title'] .=  zget($task, 'name', '');
                }

                $index ++;
            }
        }

        return $type ? $titleList : array_values($titleList);
    }

    /**
     * 根据关联对象获取提交。
     * Get relation commit.
     *
     * @param  int    $objectID
     * @param  string $objectType story|bug|task
     * @access public
     * @return array
     */
    public function getCommitsByObject(int $objectID, string $objectType): array
    {
        return $this->dao->select('t2.*')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.`AID` = t2.id')
            ->where('t1.`BID`')->eq($objectID)
            ->andWhere('t1.`BType`')->eq($objectType)
            ->andWhere('t1.`AType`')->eq('revision')
            ->andWhere('t1.relation')->eq('commit')
            ->fetchAll('', false);
    }

    /*
     * 移除没有权限的项目。
     * Remove projects without privileges.
     *
     * @param  array   $productIDList
     * @param  array   $projectIDList
     * @access public
     * @return array
     */
    public function filterProject(array $productIDList, array $projectIDList = array()): array
    {
        /* Get all projects that can be accessed. */
        $accessProjects = array();
        foreach($productIDList as $productID)
        {
            $projects       = $this->loadModel('product')->getProjectPairsByProduct((int)$productID);
            $accessProjects = $accessProjects + $projects;
        }

        /* Get linked projects. */
        $linkedProjects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->fetchPairs('id', 'name');
        return $accessProjects + $linkedProjects; // Merge projects can be accessed and exists.
    }

    /**
     * 更新代码提交历史。
     * Update commit history.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $branchID
     * @access public
     * @return bool
     */
    public function updateCommit(int $repoID, int $objectID = 0, string $branchID = ''): bool
    {
        $repo = $this->getByID($repoID);
        if($repo->SCM == 'Gitlab') return true;

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
                    $link = $this->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=$branchID", '', false);
                    return $this->app->control->locate($link);
                }
            }
            $this->loadModel('git')->updateCommit($repo, $commentGroup, false);
            $_COOKIE['repoBranch'] = $branch;
        }

        if($repo->SCM == 'Subversion') $this->loadModel('svn')->updateCommit($repo, $commentGroup, false);
        return !dao::isError();
    }

    /**
     * 移除已经删除的分支。
     * Delete the deleted branch.
     *
     * @param  int    $repoID
     * @param  array  $latestBranches
     * @access public
     * @return bool
     */
    public function checkDeletedBranches(int $repoID, array $latestBranches): bool
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

        if($action == 'execjob')      return common::hasPriv('sonarqube', $action) && !$repo->exec;
        if($action == 'reportview')   return common::hasPriv('sonarqube', $action) && !$repo->report;
        if($action == 'deletebranch') return $repo->deletable;
        if($action == 'scanexec')     return empty($repo->mirror);
        if($action == 'scanissue')    return empty($repo->mirror);

        return true;
    }

    /**
     * 获取代码库列表。
     * Get provider repo list.
     *
     * @param  object $provider
     * @param  string $groupID
     * @param  bool   $showPairs
     * @access public
     * @return array
     */
    public function getProviderRepos(object $provider, bool $showPairs = false): array
    {
        if(empty($provider->type)) return array();
        $apiRoot = $this->loadModel('provider')->getApiRoot($provider);
        if(empty($apiRoot)) return array();

        $getRepoFunc = 'get' . $provider->type . 'Repos';
        $repos       = $this->$getRepoFunc($apiRoot);

        if(!$showPairs) return $repos;

        $pairs = array();
        foreach($repos as $repo)
        {
            $pairs[$repo->id] = $repo->name;
        }
        return $pairs;
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
        if(empty($repo->id)) return;

        if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $commits = $scm->engine->getCommitsByPath('', '', 'HEAD', 1, 1);
            if(empty($commits)) return;

            $commitDate = $repo->SCM == 'Gitlab' ? $commits[0]->committed_date : $commits[0]->author->when;
            if(!empty($commitDate))
            {
                $lastCommitDate = date('Y-m-d H:i:s', strtotime($commitDate));
                $this->dao->update(TABLE_REPO)->set('lastCommit')->eq($lastCommitDate)->where('id')->eq($repoID)->exec();
            }
        }
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
            $base64Name = $this->encodePath($file->path);

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
            $base64Name = $this->encodePath($dir->path);

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
        while($hasNextPage)
        {
            $query    = 'query { project(fullPath: "%s") {repository {tree(path: "' . trim($path, '/') . '", ref: "' . $branch . '") {' . $type . '(after: "' . $endCursor . '") {pageInfo {endCursor hasNextPage} nodes {sha name path}}}}}}';
            $response = $this->gitlab->apiGetByGraphql($repo, $query);

            if(!$endCursor && !isset($response->data->project->repository)) return array();
            if(empty($response->data->project->repository->tree)) break;

            $fileList    = array_merge($fileList, $response->data->project->repository->tree->{$type}->nodes);
            $hasNextPage = $response->data->project->repository->tree->{$type}->pageInfo->hasNextPage;
            $endCursor   = $response->data->project->repository->tree->{$type}->pageInfo->endCursor;
        }
        return $fileList;
    }

    /**
     * 查询提交记录的版本号。
     * Get history revision.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  bool   $withCommit
     * @param  string $condition
     * @access public
     * @return string|object|false
     */
    public function getHistoryRevision(int $repoID, string $revision, bool $withCommit = false, string $condition = 'eq'): string|object|false
    {
        $field = $withCommit ? 'revision, commit' : 'revision';
        return $this->dao->select($field)->from(TABLE_REPOHISTORY)
            ->where('repo')->eq($repoID)
            ->beginIF($condition != 'lt')->andWhere('revision')->eq($revision)->fi()
            ->beginIF($condition == 'lt')->andWhere('revision')->lt($revision)->fi()
            ->orderBy('id desc')
            ->fetch($withCommit ? '' : 'revision');
    }

    /**
     * 通过指令开始任务。
     * Start task by commit.
     *
     * @param  object  $task
     * @param  array   $params
     * @param  object  $action
     * @param  array   $changes
     * @access private
     * @return bool
     */
    private function startTask(object $task, array $params, object $action, array $changes): bool
    {
        $now     = helper::now();
        $newTask = new stdclass();
        $newTask->id             = $task->id;
        $newTask->status         = 'doing';
        $newTask->left           = $params['left'];
        $newTask->consumed       = $params['consumed'] + $task->consumed;
        $newTask->realStarted    = $now;
        $newTask->lastEditedBy   = $this->app->user->account;
        $newTask->lastEditedDate = $now;
        if($newTask->left == 0 && empty($task->team))
        {
            $newTask->status       = 'done';
            $newTask->finishedBy   = $this->app->user->account;
            $newTask->finishedDate = $now;
            $newTask->assignedTo   = $task->openedBy;
        }

        $this->loadModel('task');
        $currentTeam = !empty($task->team) ? $this->task->getTeamByAccount($task->team) : array();
        $effort      = new stdclass();
        $effort->date     = helper::today();
        $effort->task     = $newTask->id;
        $effort->consumed = zget($newTask, 'consumed', 0);
        $effort->left     = zget($newTask, 'left', 0);
        $effort->account  = $this->app->user->account;
        $effort->work     = $this->lang->action->label->started . $this->lang->task->task . " : " . $task->name;
        $effort->consumed = !empty($task->team) && $currentTeam ? $effort->consumed - $currentTeam->consumed : $effort->consumed - $task->consumed;
        if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
        if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEffortOrder($effortID, $currentTeam->order);

        $taskChanges = $this->task->start($task, $newTask);
        if($taskChanges)
        {
            $taskChanges    = array_merge($taskChanges, $changes);
            $action->action = $newTask->left == 0 ? 'finished' : 'started';
            $this->saveRecord($action, $taskChanges);

            $this->task->afterStart($task, array(), (float)$newTask->left, array());
        }
        return !dao::isError();
    }

    /**
     * 根据指令完成任务。
     * Finish task by commit.
     *
     * @param  object  $task
     * @param  array   $params
     * @param  object  $action
     * @param  array   $changes
     * @access private
     * @return bool
     */
    private function finishTask(object $task, array $params, object $action, array $changes): bool
    {
        $now     = helper::now();
        $newTask = new stdclass();
        $newTask->status         = 'done';
        $newTask->left           = zget($params, 'left', 0);
        $newTask->consumed       = $params['consumed'] + $task->consumed;
        $newTask->assignedTo     = $task->openedBy;
        $newTask->realStarted    = $task->realStarted ? $task->realStarted : $now;
        $newTask->finishedDate   = $now;
        $newTask->lastEditedDate = $now;
        $newTask->assignedDate   = $now;
        $newTask->finishedBy     = $this->app->user->account;
        $newTask->lastEditedBy   = $this->app->user->account;

        $this->loadModel('task');
        if(empty($task->team))
        {
            $consumed = $params['consumed'];
        }
        else
        {
            $currentTeam = $this->task->getTeamByAccount($task->team);
            $consumed = $currentTeam ? $task->consumed - $currentTeam->consumed : $newTask->consumed;
        }

        $effort = new stdclass();
        $effort->date     = helper::today();
        $effort->task     = $task->id;
        $effort->left     = 0;
        $effort->account  = $this->app->user->account;
        $effort->consumed = $consumed > 0 ? $consumed : 0;
        $effort->work     = $this->lang->action->label->finished . $this->lang->task->task . " : " . $task->name;
        if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
        if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEffortOrder($effortID, $currentTeam->order);

        $taskChanges = $this->task->finish($task, $newTask);
        if($taskChanges)
        {
            $taskChanges    = array_merge($taskChanges, $changes);
            $action->action = 'finished';
            $this->saveRecord($action, $taskChanges);

            $this->task->afterStart($task, array(), 0, array());
        }
        return !dao::isError();
    }

    /**
     * 根据提交信息设置任务信息。
     * Set task by commit.
     *
     * @param  object $task
     * @param  array  $taskActions
     * @param  object $action
     * @param  array  $changes
     * @param  string $scm
     * @access public
     * @return bool
     */
    public function setTaskByCommit(object $task, array $taskActions, object $action, array $changes, string $scm): bool
    {
        foreach($taskActions as $taskAction => $params)
        {
            if($taskAction == 'start' && $task->status == 'wait')
            {
                $this->startTask($task, $params, $action, $changes);
                dao::$errors = array();
            }
            elseif($taskAction == 'effort' && in_array($task->status, array('wait', 'pause', 'doing')))
            {
                $action->action = $scm == 'svn' ? 'svncommited' : 'gitcommited';
                $this->saveEffortForCommit($task->id, $params, $action, $changes);
            }
            elseif($taskAction == 'finish' and in_array($task->status, array('wait', 'pause', 'doing')))
            {
                $this->finishTask($task, $params, $action, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * 根据提交信息设置工时。
     * Set effort by commit message.
     *
     * @param  int    $taskID
     * @param  array  $params
     * @param  object $action
     * @param  array  $changes
     * @access public
     * @return bool
     */
    public function saveEffortForCommit(int $taskID, array $params, object $action, array $changes): bool
    {
        unset($_POST['consumed']);
        unset($_POST['left']);

        $_POST['date'][1]     = date('Y-m-d');
        $_POST['consumed'][1] = $params['consumed'];
        $_POST['left'][1]     = $params['left'];
        $_POST['work'][1]     = str_replace('<br />', "\n", $action->comment);

        $this->loadModel('task');
        $workhour = form::batchData($this->config->task->form->recordWorkhour)->get();
        $this->task->recordWorkhour($taskID, $workhour);

        $this->saveRecord($action, $changes);
        return !dao::isError();
    }

    /**
     * 根据提交信息设置Bug状态。
     * Set bug status by commit.
     *
     * @param  array  $bugs
     * @param  array  $actions
     * @param  object $action
     * @param  array  $changes
     * @access public
     * @return array
     */
    public function setBugStatusByCommit(array $bugs, array $actions, object $action, array $changes): array
    {
        global $app;
        $productsAndExecutions = $this->loadModel('bug')->getByIdList($bugs);
        foreach($actions['bug'] as $bugID => $bugActions)
        {
            $app->rawModule = 'bug';
            $bug = $this->bug->getByID($bugID);
            if(empty($bug)) continue;

            $action->objectType = 'bug';
            $action->objectID   = $bugID;
            $action->product    = ",{$productsAndExecutions[$bugID]->product},";
            $action->execution  = $productsAndExecutions[$bugID]->execution;
            foreach($bugActions as $bugAction => $params)
            {
                $_POST = array();
                if($bugAction == 'resolve' && $bug->status == 'active')
                {
                    $app->rawMethod = 'resolve';
                    $this->post->set('resolvedBuild', 'trunk');
                    $this->post->set('resolution', 'fixed');

                    $newBug = form::data($this->config->bug->form->resolve)
                        ->setDefault('assignedTo', $bug->openedBy)
                        ->add('id',        $bug->id)
                        ->add('execution', $bug->execution)
                        ->get();

                    $changes = array();
                    $result  = $this->bug->resolve($newBug);
                    if($result)
                    {
                        $newBug  = $this->bug->getByID($bugID);
                        $changes = common::createChanges($bug, $newBug);
                    }

                    foreach($changes as $change) $changes[] = $change;
                    if($changes)
                    {
                        $action->action = 'resolved';
                        $action->extra  = 'fixed';
                        $this->saveRecord($action, $changes);
                    }
                }
            }

            dao::$errors = array();
            unset($bugs[$bugID]);
        }

        return $bugs;
    }

    /**
     * 保存提交信息关联的日志。
     * Save commit linkage log.
     *
     * @param  array  $objects
     * @param  object $action
     * @param  array  $changes
     * @access public
     * @return bool
     */
    public function saveObjectToPms(array $objects, object $action, array $changes): bool
    {
        $singular = array('stories' => 'story', 'tasks' => 'task', 'bugs' => 'bug', 'designs' => 'design');
        foreach(array_keys($objects) as $objectType)
        {
            if($objectType == 'actions') continue;

            if($objects[$objectType])
            {
                $objectList = array();
                if($objectType == 'stories')
                {
                    $objectList = $this->loadModel('story')->getByList($objects[$objectType]);
                }
                elseif($objectType == 'bugs')
                {
                    $objectList = $this->getBugProductsAndExecutions($objects[$objectType]);
                }
                elseif($objectType != 'designs')
                {
                    $objectList = $this->getTaskProductsAndExecutions($objects[$objectType]);
                }

                foreach($objects[$objectType] as $objectID)
                {
                    $objectID = (int)$objectID;
                    if(!isset($objectList[$objectID])) continue;

                    $action->objectType = $singular[$objectType];
                    $action->objectID   = $objectID;

                    if($objectType != 'designs')
                    {
                        $action->product    = in_array($objectType, array('stories', 'bugs')) ? $objectList[$objectID]->product : $objectList[$objectID]['product'];
                        $action->execution  = in_array($objectType, array('stories', 'bugs')) ? 0 : $objectList[$objectID]['execution'];
                    }

                    $this->saveRecord($action, $changes);
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 获取并列展示的对比信息。
     * Get appose diff.
     *
     * @param  array     $diffs
     * @access public
     * @return array
     */
    public function getApposeDiff(array $diffs): array
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
        return $diffs;
    }

    /**
     * 根据条件获取版本库列表。
     * Get repo list by condition.
     *
     * @param  string    $repoQuery
     * @param  int       $space
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getListByCondition(string $repoQuery, int $space = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $userSpaces = $this->loadModel('space')->getPairs($this->app->user->account);
        if(empty($userSpaces)) return array();

        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->andWhere('status')->ne('importing')
            ->beginIF($space)->andWhere('spaceID')->eq($space)->fi()
            ->beginIF(!empty($repoQuery))->andWhere($repoQuery)->fi()
            ->beginIF(!empty($userSpaces) && !$space)->andWhere('spaceID')->in(array_keys($userSpaces))->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /*
     * 保存对象和分支的关联关系。
     * Save object and branch relation.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $relation
     * @access public
     * @return bool
     */
    public function saveRelation(int $repoID, string $branch, int $objectID, string $objectType, string $relation = 'linkrepobranch'): bool
    {
        $relate = new stdclass();
        $relate->product  = 0;
        $relate->AType    = $objectType;
        $relate->AID      = $objectID;
        $relate->BType    = $branch;
        $relate->BID      = $repoID;
        $relate->relation = $relation;
        $this->dao->replace(TABLE_RELATION)->data($relate)->exec();

        return !dao::isError();
    }

    /**
     * 获取对象关联的代码分支。
     * Get linked branch of object.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getLinkedBranch(int $objectID = 0, string $objectType = '', int $repoID = 0): array
    {
        return $this->dao->select('BID,BType,AType')->from(TABLE_RELATION)
            ->where('relation')->eq('linkrepobranch')
            ->beginIF($objectType)->andWhere('AType')->eq($objectType)->fi()
            ->beginIF($repoID)->andWhere('BID')->eq($repoID)->fi()
            ->beginIF($objectID)->andWhere('AID')->eq($objectID)->fi()
            ->fetchAll();
    }

    /**
     * 移除对象关联的代码分支。
     * Get linked branch of object.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function unlinkObjectBranch(int $objectID, string $objectType, int $repoID, string $branch): bool
    {
        $this->dao->delete()->from(TABLE_RELATION)
            ->where('relation')->eq('linkrepobranch')
            ->beginIF($objectType)->andWhere('AType')->eq($objectType)->fi()
            ->beginIF($objectID)->andWhere('AID')->eq($objectID)->fi()
            ->andWhere('BID')->eq($repoID)
            ->andWhere('BType')->eq($branch)
            ->exec();
        return !dao::isError();
    }


    /**
     * 通过产品ID和代码库类型获取代码库列表。
     * Get repo list by product id.
     *
     * @param  int    $productID
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getListByProduct(int $productID, int $limit = 0): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->andWhere('status')->ne('importing')
            ->andWhere("FIND_IN_SET({$productID}, `product`)")
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取代码库服务器已经导入的项目/代码库。
     * Get the imported projects/repositories by service host id.
     *
     * @param  int   $hostID
     * @return array
     */
    public function getImportedProjects(int $hostID)
    {
        $importedProjects = $this->dao->select('serviceProject')->from(TABLE_REPO)
            ->where('serviceHost')->eq($hostID)
            ->andWhere('deleted')->eq('0')
            ->andWhere('status')->ne('importing')
            ->fetchAll('serviceProject');

        if(dao::isError()) return array();

        return array_keys($importedProjects);
    }

    /**
     * 隐藏DevOps菜单，执行和项目模块使用。
     * Hide DevOps menu.
     *
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function setHideMenu(int $objectID): int
    {
        $menuGroup = $this->app->tab == 'project' ? array('project', 'waterfall') : array('execution');
        $repoPairs = $this->loadModel('repo')->getRepoPairs($this->app->tab, $objectID);

        $showMR     = $repoPairs && common::hasPriv('ppm', 'browse');
        $showTag    = $repoPairs && common::hasPriv('repo', 'browsetag');
        $showBranch = $repoPairs && common::hasPriv('repo', 'browsebranch');
        $showReview = $repoPairs && common::hasPriv('repo', 'review');
        $showCommit = $repoPairs && common::hasPriv('repo', 'log');
        foreach($menuGroup as $module)
        {
            if(!isset($this->lang->{$module}->menu->devops['subMenu'])) continue;

            if(!$showMR)     unset($this->lang->{$module}->menu->devops['subMenu']->ppm);
            if(!$showTag)    unset($this->lang->{$module}->menu->devops['subMenu']->tag);
            if(!$showBranch) unset($this->lang->{$module}->menu->devops['subMenu']->branch);
            if(!$showReview) unset($this->lang->{$module}->menu->devops['subMenu']->review);
            if(!$showCommit) unset($this->lang->{$module}->menu->devops['subMenu']->commit);
            if(count((array)$this->lang->{$module}->menu->devops['subMenu']) < 2) unset($this->lang->{$module}->menu->devops['subMenu']);
        }
        return $objectID;
    }

    /**
     * Check repo name.
     *
     * @param  string $name
     * @access public
     * @return bool
     */
    public function checkName(string $name)
    {
        $pattern = "/^[a-z_]{1}[a-z0-9_\-\.]*$/i";
        return preg_match($pattern, $name);
    }

    /**
     * 获取指定代码库的所有成员。
     * Get all members.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getRepoUsers(int $repoID): array
    {
        return $this->dao->select('account')->from(TABLE_DEVOPSREPOUSER)
            ->where('repo')->eq($repoID)
            ->fetchPairs('account', 'account');
    }

    /**
     * 获取指定代码库的所有成员.
     * Get all members.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getRepoMembers(object $repo): array
    {
        if(empty($repo->members)) return array();

        $repoMembers = !empty($repo->members) ? $this->loadModel('user')->getListByAccounts(array_keys($repo->members)) : array();

        return !empty($repoMembers) ? array_column($repoMembers, 'realname', 'account') : array();
    }

    /**
     * 更新用户。
     * Update users.
     *
     * @param  int    $groupID
     * @access public
     * @return bool
     */
    public function updateMembers(int $repoID, array $members): bool
    {
        $groupUsers = $this->dao->select('account')->from(TABLE_DEVOPSREPOUSER)->where('`repo`')->eq($repoID)->fetchPairs('account');
        $newUsers   = array_diff($members, $groupUsers);
        $delUsers   = array_diff($groupUsers, $members);

        if(!empty($delUsers)) $this->dao->delete()->from(TABLE_DEVOPSREPOUSER)->where('`repo`')->eq($repoID)->andWhere('account')->in($delUsers)->exec();
        if(empty($newUsers)) return !dao::isError();

        $data = new stdclass();
        $data->repo = $repoID;
        foreach($newUsers as $account)
        {
            $data->account = $account;
            $this->dao->insert(TABLE_DEVOPSREPOUSER)->data($data)->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取指定空间下的所有代码库。
     * Get all repos by spaces.
     *
     * @param  array $spaceIdList
     * @access public
     * @return array
     */
    public function getListBySpaces(array $spaceIdList): array
    {
        return $this->dao->select('t1.*')->from(TABLE_REPO)->alias('t1')
            ->leftJoin(TABLE_SPACE)->alias('t2')->on('t1.spaceID = t2.id')
            ->where('t1.spaceID')->in($spaceIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->ne('importing')
            ->fetchAll('id');
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
            ->beginIF($entry)->andWhere('t1.entry')->eq($entry)->fi()
            ->beginIF($revision)->andWhere('t1.v2')->eq($revision)->fi()
            ->andWhere('t1.mr')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id', false);

        $comments = $this->getComments(array_keys($bugs));
        foreach($bugs as $bug)
        {
            if(common::hasPriv('bug', 'edit'))   $bug->edit   = true;
            if(common::hasPriv('bug', 'delete')) $bug->delete = true;
            $lines = explode(',', trim($bug->lines, ','));
            $line  = $lines[0];
            $reviews[$line]['bugs'][$bug->id] = $bug;

            if(isset($comments[$bug->id])) $reviews[$line]['comments'] = $comments;
        }

        return $reviews;
    }

    /**
     * Get review comments.
     *
     * @param  array  $bugIDList
     * @access public
     * @return array
     */
    public function getComments($bugIDList)
    {
        $users    = $this->dao->select('account,realname,nickname,avatar')->from(TABLE_USER)->fetchAll('account');
        $comments = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('objectID')->in($bugIDList)
            ->andWhere('action')->eq('commented')
            ->fetchGroup('objectID', 'id');

        foreach($bugIDList as $bugID)
        {
            if(!isset($comments[$bugID])) continue;

            foreach($comments[$bugID] as $comment)
            {
                $comment->user = zget($users, $comment->actor);
                $comment->realname = zget($users, $comment->actor, $comment->actor, $users[$comment->actor]->realname);
                $comment->edit = $comment->actor == $this->app->user->account ? true : false;
            }
        }
        return $comments;
    }

    /**
     * Get bugs by repo.
     *
     * @param  int    $repoID
     * @param  string $browseType
     * @param  int    $executionID
     * @param  array  $bugs
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugsByRepo($repoID = 0, $browseType = '', $executionID = 0, $bugs = array(), $orderBy = 'id_desc', $pager = null)
    {
        if($this->app->tab == 'project' && $executionID)
        {
            $executionIDList = $this->loadModel('execution')->fetchExecutionList($executionID, 'all');
            if(!empty($executionIDList)) $executionID = array_keys($executionIDList);
        }

        dao::$filterTpl = 'never';
        return $this->dao->select('t1.*, t2.name AS executionName, t3.name as productName')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id and t2.isTpl = '0'")
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.issueKey')->eq('')
            ->beginIF($repoID)->andWhere('t1.repo')->eq($repoID)->fi()
            ->beginIF($executionID)
            ->andWhere('t1.execution')->in($executionID)
            ->andWhere('t1.repo')->gt('0')
            ->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF($browseType == 'assigntome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'openedbyme')->andWhere('t1.openedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'resolvedbyme')->andWhere('t1.resolvedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assigntonull')->andWhere('t1.assignedTo')->eq('')->fi()
            ->beginIF($browseType == 'unresolved')->andWhere('t1.resolvedBy')->eq('')->fi()
            ->beginIF($browseType == 'unclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!empty($bugs))->andWhere('t1.id')->in($bugs)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Save bug.
     *
     * @param  int    $repoID
     * @param  object $bug
     * @access public
     * @return array
     */
    public function saveBug($repoID, $bug)
    {
        if($bug->execution)
        {
            $execution    = $this->loadModel('execution')->getByID($bug->execution);
            $bug->project = $execution->project;
        }

        $this->lang->bug->title = $this->lang->repo->title;
        $this->dao->insert(TABLE_BUG)->data($bug)
            ->batchCheck('title,product', 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $bugID = $this->dao->lastInsertID();
        $this->loadModel('file')->updateObjectID($this->post->uid, $bugID, 'bug');
        helper::setCookie("repoPairs[$repoID]", $bug->product);

        return array(
            'result'     => 'success',
            'id'         => $bugID,
            'realname'   => $this->app->user->realname,
            'openedDate' => substr($bug->openedDate, 5, 11),
            'edit'       => true,
            'delete'     => true,
            'lines'      => $bug->lines,
            'line'       => $this->post->begin,
            'steps'      => $bug->steps,
            'title'      => $bug->title,
            'file'       => $bug->entry,
            'revision'   => $bug->v2,
        );
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
     * Get linked object ids by comment.
     *
     * @param  int    $comment
     * @access public
     * @return array
     */
    public function getLinkedObjects($comment)
    {
        $rules   = $this->processRules();
        $stories = array();
        $tasks   = array();
        $bugs    = array();

        $storyReg = '/' . $rules['storyReg'] . '/i';
        $taskReg  = '/' . $rules['taskReg'] . '/i';
        $bugReg   = '/' . $rules['bugReg'] . '/i';

        if(preg_match_all($taskReg, $comment, $matches))
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
            }
            $tasks = $idMatches[0];
        }
        if(preg_match_all($bugReg, $comment, $matches))
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
            }
            $bugs = $idMatches[0];
        }
        if(preg_match_all($storyReg, $comment, $matches))
        {
            foreach($matches[3] as $idList)
            {
                preg_match_all('/\d+/', $idList, $idMatches);
            }
            $stories = $idMatches[0];
        }
        return array('stories' => $stories, 'tasks' => $tasks, 'bugs' => $bugs);
    }

    /**
     * Get diff file tree.
     *
     * @param  object $diffs
     * @access public
     * @return void
     */
    public function getDiffFileTree($diffs = null)
    {
        $files = array();
        foreach($diffs as $diff) $files[] = $diff->fileName;

        return $this->buildFileTree($files);
    }

    /**
     * 获取应用列表。
     * Get app list.
     *
     * @param  string $systemQuery
     * @param  int    $space
     * @access public
     * @return array
     */
    public function getSystemList(string $systemQuery = '', int $space = 0): array
    {
        $spaceProducts = $this->loadModel('space')->getProductsBySpace($space);
        return $this->dao->select('t1.`id` as id, t1.`name` as name, t1.`latestRelease` as latestRelease, t1.`product` as product, t1.`status` as status, t3.`status` as deployStatus, t3.`createdDate` as deployCreatedDate')->from(TABLE_SYSTEM)->alias('t1')
            ->leftJoin(TABLE_DEPLOYPRODUCT)->alias('t2')->on('t1.`latestRelease` = t2.`release`')
            ->leftJoin(TABLE_DEPLOY)->alias('t3')->on('t2.`deploy` = t3.`id` and t2.`release` > 0')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t3.deleted', true)->eq('0')
            ->orWhere('t3.id')->isNULL()
            ->markRight(true)
            ->beginIF(!empty($spaceProducts))->andWhere('t1.product')->in($spaceProducts)->fi()
            ->beginIF(!empty($systemQuery))->andWhere($systemQuery)->fi()
            ->orderBy('deployCreatedDate_asc')
            ->fetchAll('id');
    }

    /**
     * 获取GitFox代码库列表。
     * Get gitfox repo list.
     *
     * @access public
     * @return array
     */
    public function getGitFoxRepos(): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->where('status')->ne('importing')
            ->fetchAll('id');
    }

    /**
     * 构建应用搜索表单字段。
     * Build system search form field.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  bool   $cacheSearchFunc
     * @access public
     * @return void
     */
    public function buildSystemSearchForm(int $queryID, string $actionURL, bool $cacheSearchFunc = true)
    {
        $searchConfig = $this->config->repo->system->search;
        if($cacheSearchFunc)
        {
            $this->cacheSearchFunc('systemSearch', __METHOD__, func_get_args());
            return $searchConfig;
        }
        $searchConfig['params']['product']['values'] = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $searchConfig['queryID']   = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        $this->loadModel('search')->setSearchParams($searchConfig);
        return $searchConfig;
    }

    /**
     * 获取代码库键值对。
     * Get repo pairs.
     *
     * @access public
     * @return void
     * @return array
     */
    public function getPairs(): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('importing')->fetchPairs('id', 'name');
    }

    /**
     * 获取gitlab项目列表。
     * Get gitlab projects.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGitLabRepos(string $apiRoot): array
    {
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "/projects");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&simple=true&page={$page}&per_page=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 获取Gitea项目列表。
     * Get Gitea projects.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGiteaRepos(string $apiRoot): array
    {
        if(empty($apiRoot)) return array();

        $url = sprintf($apiRoot, "/repos/search");

        $page       = 1;
        $allResults = array();
        while(true)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(empty($results->data) || !is_array($results->data)) break;

            $allResults = array_merge($allResults, $results->data);
            if(count($results->data) < 50) break;

            $page ++;
        }

        return $allResults;
    }

    /**
     * 获取Gogs代码库列表。
     * Get gogs repo list.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGogsRepos(string $apiRoot): array
    {
        if(empty($apiRoot)) return array();

        $url = sprintf($apiRoot, "/user/repos");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 50) break;
        }

        return $allResults;
    }

    /**
     * 导入代码库。
     * Import repo.
     *
     * @param  object $formData
     * @access public
     * @return object|false
     */
    public function import(object $formData): object|false
    {
        if(empty($formData->providerID)) return false;

        $provider = $this->loadModel('provider')->fetchByID((int)$formData->providerID);
        if(empty($provider)) return false;

         /* 提取嵌套三元：仅在非 Subversion 时按 provider 类型拼仓库标识并调用接口取仓库，与原三元短路语义一致。 */
        $repo = array();
        if($provider->type != 'Subversion')
        {
            $repoIdentifier = $provider->type == 'GitLab' ? $formData->repo : $formData->organize . '/' . $formData->repo;
            $repo = $this->getProviderRepo($provider, $repoIdentifier);
        }
        $params = new stdClass();
        $params->acl      = $formData->acl;
        $params->name     = $formData->name;
        $params->desc     = $formData->desc;
        $params->product  = $formData->product;
        $params->spaceID  = (int)$formData->space;
        $params->mirror   = $formData->mirror != 'writable';
        $params->provider = $provider;
        $params->provider->host = $provider->url;
        if($provider->type == 'Subversion')
        {
            if(strpos($provider->url, 'file://') === 0)
            {
                $path = explode('///', $provider->url);
                $params->provider->host = 'file://';
                $params->provider->slug = isset($path[1]) ? $path[1] : '';
                if(!empty($formData->slug))
                {
                    if(PHP_OS == 'WINNT')
                    {
                        $params->provider->slug .= "\\" . ltrim($params->provider->slug, "\\");
                    }
                    else
                    {
                        $params->provider->slug .= "/" . ltrim($params->provider->slug, "/");
                    }
                }
            }
            elseif(strpos($provider->url, 'svn://') === 0)
            {
                $path = explode('//', $provider->url);
                $params->provider->host     = 'svn:/';
                $params->provider->slug     = isset($path[1]) ? $path[1] : '';
                if(!empty($formData->slug))
                {
                    $params->provider->slug .= '/' . ltrim($formData->slug, '/');
                }
                $params->provider->password = $formData->password;
                $params->provider->username = $formData->account;
            }
            else
            {
                $path = parse_url($provider->url);
                if(empty($path)) return false;
                $params->provider->host     = $path['scheme'] . '://' . $path['host'];
                $params->provider->password = $formData->password;
                $params->provider->username = $formData->account;
                $params->provider->slug     = $path['path'];
            }
        }
        else
        {
            $params->provider->projectID = (string)zget($repo, 'id', 0);
            $params->provider->slug      = $provider->type == 'GitLab' ? zget($repo, 'path_with_namespace', '') : zget($repo, 'full_name', '');
        }

        $result = $this->loadModel('gitfox')->request('/repos/import', 'POST', $params);
        if(dao::isError()) return false;

        $repoID = zget($result, 'id', 0);

        if($repoID && !empty($params->acl) && $params->acl === 'private')
        {
            $members = array_filter(explode(',', $formData->members ?? ''));
            if(!in_array($this->app->user->account, $members)) $members[] = $this->app->user->account;
            $this->updateMembers($repoID, $members);
            if(dao::isError()) return false;
        }

        return $result;
    }

    /**
     * 获取代码库详情。
     * Get repo detail.
     *
     * @param  object $provider
     * @param  string $repoID
     * @access public
     * @return object|false
     */
    public function getProviderRepo(object $provider, string $repoID): object|false
    {
        if(empty($provider->type)) return false;
        $apiRoot = $this->loadModel('provider')->getApiRoot($provider);
        if(empty($apiRoot)) return false;

        $url  = $provider->type == 'GitLab' ? sprintf($apiRoot, "/projects/{$repoID}") : sprintf($apiRoot, "/repos/{$repoID}");
        $repo = json_decode(commonModel::http($url));
        if(empty($repo) || isset($repo->message)) return false;

        return $repo;
    }

    /**
     * 迁移代码库数据。
     * Migrate repo data.
     *
     * @access public
     * @return bool
     */
    public function migrateRepoData(): bool
    {
        $company      = $this->loadModel('company')->getFirst();
        $admins       = !empty($company->admins) ? explode(',', $company->admins) : array();
        $admins       = array_filter($admins);
        $oldRepoTable = $this->config->db->prefix . 'repo';

        $oldRepos = $this->dao->select('*')->from($oldRepoTable)
            ->where('SCM')->in(array('Subversion', 'Gitlab', 'Gitea', 'Gogs'))
            ->fetchAll('', false);
        if(empty($oldRepos)) return true;

        $products = $this->dao->select('id, PO, QD, RD, whitelist')
            ->from(TABLE_PRODUCT)
            ->fetchAll('id');

        $productMembersMap = array();
        foreach($products as $productID => $product)
        {
            $productMembers = array();
            foreach(array('PO', 'QD', 'RD', 'whitelist') as $field)
            {
                $fieldMembers = array_filter(array_map('trim', explode(',', zget($product, $field, ''))), 'strlen');
                if(!empty($fieldMembers)) $productMembers = array_merge($productMembers, $fieldMembers);
            }
            if(!empty($productMembers)) $productMembersMap[$productID] = array_values(array_unique($productMembers));
        }

        $userGroup = $this->dao->select('`group` AS groupID, account')
            ->from(TABLE_USERGROUP)
            ->fetchAll();

        $groupAccountMap = array();
        foreach($userGroup as $groupUser)
        {
            if(empty($groupUser->groupID)) continue;
            if(!isset($groupAccountMap[$groupUser->groupID])) $groupAccountMap[$groupUser->groupID] = [];
            $groupAccountMap[$groupUser->groupID][] = $groupUser->account;
        }

        foreach($oldRepos as $oldRepo)
        {
            $oldRepo->groupAccounts = $groupAccountMap;
            $aclInfo = $this->parseRepoAcl($oldRepo);
            $repo    = $this->buildNewRepo($oldRepo, $aclInfo['acl'], zget($admins, 0, 'system'));

            $this->dao->insert(TABLE_REPO)->data($repo)->exec();
            if(dao::isError()) return false;

            if($aclInfo['acl'] === 'private')
            {
                $members    = array();
                $productIDs = array_filter(array_map('intval', explode(',', $oldRepo->product)));
                foreach($productIDs as $productID)
                {
                    if(empty($productMembersMap[$productID])) continue;
                    $members = array_merge($members, $productMembersMap[$productID]);
                }
                if(!empty($aclInfo['members'])) $members = array_merge($members, $aclInfo['members']);

                $members = array_filter(array_unique($members), 'strlen');
                if(!empty($members) && !$this->insertMembers($repo->id, $members)) return false;
            }
        }
        return true;
    }

    /**
     * 解析旧代码库的访问控制信息。
     * Parse the access control information of the old repo.
     *
     * @param  object $oldRepo
     * @access private
     * @return array
     */
    private function parseRepoAcl(object $oldRepo): array
    {
        $repoAcl = 'open';
        $members = array();
        $oldAcl  = trim($oldRepo->acl);
        if($oldAcl !== '')
        {
            $aclData = json_decode($oldAcl, true);
            if(is_array($aclData) && isset($aclData['acl']))
            {
                $repoAcl = $aclData['acl'];
                if(isset($aclData['users']))
                {
                    $members = array_filter(array_map('trim', $aclData['users']), 'strlen');
                }

                if(isset($aclData['groups']) && is_array($aclData['groups']))
                {
                    foreach($aclData['groups'] as $groupID)
                    {
                        if(empty($oldRepo->groupAccounts[$groupID]) || !is_array($oldRepo->groupAccounts[$groupID])) continue;
                        $members = array_merge($members, $oldRepo->groupAccounts[$groupID]);
                    }
                }
            }
        }
        $members = array_values(array_unique($members));
        return array('acl' => $repoAcl, 'members' => array_values(array_unique($members)));
    }

    /**
     * 构建新的代码库对象。
     * Build a new repo object.
     *
     * @param  object $oldRepo
     * @param  string $repoAcl
     * @param  string $admins
     * @access private
     * @return object
     */
    private function buildNewRepo(object $oldRepo, string $repoAcl, string $admins): object
    {
        $repo          = new stdClass();
        $scm           = isset($oldRepo->SCM) ? $oldRepo->SCM : '';
        $repo->scmType = '';
        $connector     = new stdClass();
        if($scm === 'Gitlab')
        {
            $repo->scmType        = 'git';
            $path                 = isset($oldRepo->path) ? $oldRepo->path : '';
            $connector->slug      = $this->extractPathSlug($path);
            $connector->projectID = isset($oldRepo->serviceProject) ? $oldRepo->serviceProject : '';
        }
        elseif($scm === 'Gitea' || $scm === 'Gogs')
        {
            $repo->scmType        = 'git';
            $connector->slug      = isset($oldRepo->serviceProject) ? $oldRepo->serviceProject : '';
            $connector->projectID = '';
        }
        elseif($scm === 'Subversion')
        {
            $repo->scmType       = 'svn';
            $path                = isset($oldRepo->path) ? $oldRepo->path : '';
            $connector->slug     = $this->extractPathSlug($path);
            $connector->user     = isset($oldRepo->account) ? $oldRepo->account : '';
            $connector->password = isset($oldRepo->password) ? $oldRepo->password : '';
        }

        $repo->id               = $oldRepo->id;
        $repo->spaceID          = 1;
        $repo->product          = $oldRepo->product;
        $repo->name             = $oldRepo->name;
        $repo->desc             = $oldRepo->desc;
        $repo->gitUID           = 'empty_gituid_'.$oldRepo->id;
        $repo->forkID           = 0;
        $repo->mirror           = 1;
        $repo->providerID       = $oldRepo->serviceHost;
        $repo->connector        = json_encode($connector, JSON_UNESCAPED_SLASHES);
        $repo->defaultBranch    = '';
        $repo->acl              = $repoAcl;
        $repo->status           = 'importing';
        $repo->synced           = 0;
        $repo->branchArchivable = 0;
        $repo->createdBy        = $admins;
        $repo->createdDate      = helper::now();
        $repo->editedBy         = $admins;
        $repo->editedDate       = helper::now();
        $repo->deleted          = $oldRepo->deleted;

        return $repo;
    }

    /**
     * 提取路径中的仓库标识。
     * Extract the repository identifier from the path.
     *
     * @param  string $path
     * @access private
     * @return string
     */
    private function extractPathSlug(string $path): string
    {
        $path = trim($path);
        if($path === '') return '';

        $parsed = parse_url($path);
        if(!empty($parsed['path']))
        {
            return ltrim($parsed['path'], '/');
        }
        return ltrim($path, '/');
    }

    /**
     * 插入代码库成员。
     * Insert repo members.
     *
     * @param  int $repoID
     * @param  array $members
     * @access private
     * @return bool
     */
    private function insertMembers(int $repoID, array $members): bool
    {
        $values = array();
        foreach($members as $account) $values[] = "('{$repoID}', '{$account}')";

        $sql = 'REPLACE INTO ' . TABLE_DEVOPSREPOUSER . ' (`repo`, `account`) VALUES ' . implode(', ', $values);
        $this->dao->exec($sql);
        return !dao::isError();
    }

}
