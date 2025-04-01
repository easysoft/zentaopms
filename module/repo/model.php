<?php
declare(strict_types=1);
/**
 * The model file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@cnezsoft.com>
 * @package     repo
 * @property    repoTao $repoTao
 * @link        https://www.zentao.net
 */
class repoModel extends model
{
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
        $acl     = !empty($repo->acl->acl) ? $repo->acl->acl : 'custom';
        if(empty($repo->acl))         $repo->acl = new stdclass();
        if(empty($repo->acl->users))  $repo->acl->users  = array();
        if(empty($repo->acl->groups)) $repo->acl->groups = array();

        if(strpos(",{$this->app->company->admins},", ",$account,") !== false || $acl == 'open') return true;
        if($acl == 'custom' && empty(array_filter($repo->acl->groups)) && empty(array_filter($repo->acl->users))) return true;

        if($acl == 'private')
        {
            $userProducts = explode(',', $this->app->user->view->products);
            $repoProducts = explode(',', $repo->product);
            if(array_intersect($userProducts, $repoProducts)) return true;
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
     * 设置菜单链接信息。
     * Set menu.
     *
     * @param  array  $repos
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function setMenu(array $repos, int $repoID = 0)
    {
        if(empty($repoID)) $repoID = $this->session->repoID ? $this->session->repoID : key($repos);
        if(!isset($repos[$repoID])) $repoID = key($repos);

        $repoID = (int)$repoID;

        /* Check the privilege. */
        if($repoID)
        {
            $repo = $this->getByID($repoID);
            if(!$repo || !$this->checkPriv($repo)) $repoID = 0;
            if(!$repo || !in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) unset($this->lang->devops->menu->mr);
            if(!$repo || !in_array($repo->SCM, $this->config->repo->notSyncSCM))
            {
                unset($this->lang->devops->menu->tag);
                unset($this->lang->devops->menu->branch);
            }
        }

        if(!in_array($this->app->methodName, array('maintain', 'create', 'createrepo', 'edit','import'))) common::setMenuVars('devops', $repoID);
        if(!session_id()) session_start();
        $this->session->set('repoID', $repoID);
        session_write_close();
    }

    /**
     * 获取代码库列表。
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
        $repos     = $this->getListByCondition($repoQuery, $SCM, $orderBy, $pager);

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
     * 根据SCM和权限获取代码库列表。
     * Get list by SCM.
     *
     * @param  string $scm
     * @param  string $type  all|haspriv
     * @access public
     * @return array
     */
    public function getListBySCM(string $scm, string $type = 'all')
    {
        $repos = $this->dao->select('*,acl')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('SCM')->in($scm)
            ->andWhere('synced')->eq(1)
            ->fetchAll('id', false);

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
    public function create(object $repo, bool $isPipelineServer): int|false
    {
        $this->dao->insert(TABLE_REPO)->data($repo, 'serviceToken')
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->batchCheckIF(!in_array($repo->SCM, $this->config->repo->notSyncSCM), 'path,client', 'notempty')
            ->batchCheckIF($isPipelineServer, 'serviceHost,serviceProject', 'notempty')
            ->batchCheckIF($repo->SCM == 'Subversion', $this->config->repo->svn->requiredFields, 'notempty')
            ->check('name', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM))
            ->checkIF(!$isPipelineServer, 'path', 'unique', "`SCM` = " . $this->dao->sqlobj->quote($repo->SCM) . " and `serviceHost` = " . $this->dao->sqlobj->quote($repo->serviceHost))
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        $repoID = $this->dao->lastInsertID();

        $repo = $this->getByID($repoID);
        if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
        {
            $token = uniqid();
            $res   = $this->loadModel($repo->SCM)->addPushWebhook($repo, $token);
            if($res !== true)
            {
                $this->dao->delete()->from(TABLE_REPO)->where('id')->eq($repoID)->exec();
                dao::$errors['webhook'][] = isset($res['message']) ? $res['message'] : $this->lang->gitlab->failCreateWebhook;
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

        $response = $this->createGitlabRepo($repo, $repo->namespace);

        $this->loadModel('gitlab');
        if(!empty($response->id))
        {
            $repo->path           = $response->path;
            $repo->serviceProject = $response->serviceProject;
            $repo->extra          = $response->extra;
            $repo->SCM            = 'Gitlab';

            unset($repo->namespace);
            $repoID = $this->create($repo, false);
            if(dao::isError())
            {
                $this->gitlab->apiDeleteProject($repo->serviceHost, $response->id);
                return false;
            }
            return $repoID;
        }

        return $this->gitlab->apiErrorHandling($response);
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
            if(empty($repo->product)) continue;
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
            if(method_exists($this->instance, 'saveWaitSyncData')) $this->instance->saveWaitSyncData('repo', $repoID, 'add', false);
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

            $token = uniqid();
            $res   = $this->loadModel('gitlab')->addPushWebhook($repo, $token);
            if($res !== true)
            {
                dao::$errors['webhook'][] = isset($res['message']) ? $res['message'] : $this->lang->gitlab->failCreateWebhook;
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

        if($data->encrypt == 'base64') $data->password = base64_encode((string)$data->password);
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

        if(in_array($data->SCM, $this->config->repo->notSyncSCM))
        {
            $this->loadModel($data->SCM)->updateCodePath($data->serviceHost, (int)$data->serviceProject, $repo->id);
            $data->path = $this->getByID($repo->id)->path;
            $this->updateCommitDate($repo->id);
        }

        if(($repo->serviceHost != $data->serviceHost || $repo->serviceProject != $data->serviceProject || $repo->SCM == 'Subversion') && $repo->path != $data->path)
        {
            $this->repoTao->deleteInfoByID($repo->id);
            return false;
        }

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
            if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
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
        if(session_id()) session_write_close();

        if(!defined('RUN_MODE') || RUN_MODE != 'test') session_start();
        if($repoID > 0) $this->session->set('repoID', (int)$repoID);

        $repos = $this->getRepoPairs($this->app->tab, $objectID);
        if($repoID == 0 && $this->session->repoID == '') $this->session->set('repoID', key($repos));

        if(!isset($repos[$this->session->repoID])) $this->session->set('repoID', key($repos));

        $repoID = (int)$this->session->repoID;
        session_write_close();

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
    public function getRepoPairs(string $type, int $projectID = 0, bool $showScm = true): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRepoPairs();

        $repos = $this->dao->select('*,acl')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->fetchAll('id', false);

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
     * 根据应用获取代码库分组。
     * Get repos group by repo type.
     *
     * @param  string $type
     * @param  int    $projectID
     * @param  array  $scmList
     * @access public
     * @return array
     */
    public function getRepoGroup(string $type, int $projectID = 0, array $scmList = array()): array
    {
        $repos      = $this->getList(0, implode(',', $scmList));
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
            $repo->acl = is_string($repo->acl) ? json_decode($repo->acl) : $repo->acl;
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
        $repo = $this->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return false;

        /* Update repo table for old version. */
        if(empty($repo->serviceHost) && in_array($repo->SCM, $this->config->repo->gitServiceTypeList))
        {
            $repo->serviceHost    = $repo->client;
            $repo->serviceProject = $repo->extra;
            $this->dao->update(TABLE_REPO)->data(array('serviceHost' => $repo->serviceHost, 'serviceProject' => $repo->serviceProject))->where('id')->eq($repoID)->exec();

            /* Add webhook. */
            if($repo->SCM == 'Gitlab') $this->loadModel('gitlab')->updateCodePath((int)$repo->serviceHost, (int)$repo->serviceProject, $repo->id);
        }

        if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
        $repo->codePath = $repo->path;
        if(in_array(strtolower($repo->SCM), $this->config->repo->gitServiceList)) $repo = $this->processGitService($repo);
        $repo->acl = json_decode($repo->acl);
        if(empty($repo->acl)) $repo->acl = new stdclass();
        if(empty($repo->acl->acl)) $repo->acl->acl = 'custom';

        $repo->serviceHost    = (int)$repo->serviceHost;
        $repo->gitService     = $repo->serviceHost;
        $repo->serviceProject = $repo->SCM == 'Gitlab' ? (int)$repo->serviceProject : $repo->serviceProject;
        return $repo;
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

        $matchedRepos = $this->getListByCondition('(' . implode(' OR ', $conditions). ')', 'Gitlab');
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
        if(empty($matchedRepo->job)) return array('result' => 'fail', 'message' => 'No linked job.');

        $job = $this->dao->select('*')->from(TABLE_JOB)->where('id')->eq($matchedRepo->job)->andWhere('deleted')->eq(0)->fetch();
        if(empty($job)) return array('result' => 'fail', 'message' => 'Linked job is not exists.');

        $matchedRepo->job = $job;
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
        $repos = $this->dao->select('*')->from(TABLE_REPO)->where('deleted')->eq(0)->andWhere('id')->in($idList)->fetchAll('id', false);
        foreach($repos as $repo)
        {
            if($repo->encrypt == 'base64') $repo->password = base64_decode($repo->password);
            $repo->acl = json_decode($repo->acl);
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
    public function getCommits(object $repo, string $entry, string $revision = 'HEAD', string $type = 'dir', object|null $pager = null, string $begin = '', string $end = '', object|string|null $query = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getCommits();

        if(!isset($repo->id)) return array();
        if(in_array($repo->SCM, $this->config->repo->notSyncSCM)) return $this->loadModel('gitlab')->getCommits($repo, $entry, $pager, $begin, $end, $query);

        $entry         = ltrim($entry, '/');
        $entry         = empty($entry) ? '' : '/' . $entry;
        $revisionTime  = $this->repoTao->getLatestCommitTime($repo->id, $revision, $repo->SCM == 'Subversion' ? '' : (string)$this->cookie->repoBranch);
        $hasBranch     = $repo->SCM != 'Subversion' && $this->cookie->repoBranch;
        $historyIdList = array();
        if($entry != '/' && !empty($entry))
        {
            $historyIdList = $this->dao->select('DISTINCT t2.id,t2.`time`')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->beginIF($hasBranch)->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')->fi()
                ->where('t1.repo')->eq($repo->id)
                ->beginIF($begin)->andWhere('t2.`time`')->ge($begin)->fi()
                ->beginIF($end)->andWhere('t2.`time`')->le($end)->fi()
                ->beginIF($revisionTime)->andWhere('t2.`time`')->le($revisionTime)->fi()
                ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
                ->beginIF($hasBranch)->andWhere('t3.branch')->eq($this->cookie->repoBranch)->fi()
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
            ->beginIF($hasBranch)->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')->fi()
            ->where('t1.repo')->eq($repo->id)
            ->andWhere('left(t1.`comment`, 12)')->ne('Merge branch')
            ->beginIF($revisionTime)->andWhere('t1.`time`')->le($revisionTime)->fi()
            ->beginIF($begin)->andWhere('t1.`time`')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.`time`')->le($end)->fi()
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($hasBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->beginIF($entry != '/' && !empty($entry))->andWhere('t1.id')->in($historyIdList)->fi()
            ->beginIF($begin)->andWhere('t1.time')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t1.time')->le($end)->fi()
            ->orderBy('time desc');
        if($entry == '/' || empty($entry)) $comments->page($pager, 't1.id');
        $comments = $comments->fetchAll('revision');

        foreach($comments as $repoComment)
        {
            $repoComment->originalComment = $repoComment->comment;
            $repoComment->comment         = $this->replaceCommentLink($repoComment->comment);
        }

        return $comments;
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
            ->beginIF($repo->SCM != 'Subversion' && $branchID)->andWhere('t2.branch')->eq($branchID)->fi()
            ->beginIF($repo->SCM == 'Subversion')->andWhere('t1.time')->ne('1970-01-01 08:00:00')->fi()
            ->orderBy('t1.`time` desc')
            ->fetch();
        if(empty($lastComment)) return false;

        $lastComment->svnRevision = intval($lastComment->revision);
        if(!$checkCount) return $lastComment;

        $count = $this->dao->select('count(DISTINCT t1.id) as count')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($repo->SCM != 'Subversion' && $branchID)->andWhere('t2.branch')->eq($branchID)->fi()
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
    public function createLink(string $method, string $params = '', string $viewType = '')
    {
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
            if(!session_id()) session_start();
            $this->session->set('clientVersionFile', '');
            session_write_close();

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
                $catLink  = trim(html::a($this->buildURL('cat',  $repoRoot . $file, (string) $log->revision, $scm), 'view', '', "data-toggle='modal' data-size='{\"width\": 800, \"height\": 500}'"));
                $diffLink = trim(html::a($this->buildURL('diff', $repoRoot . $file, (string) $log->revision, $scm), 'diff', '', "data-toggle='modal' data-size='{\"width\": 800, \"height\": 500}'"));

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
     * @param  bool   $getCodePath
     * @access public
     * @return object
     */
    public function processGitService(object $repo, bool $getCodePath = false): object
    {
        $service = $this->loadModel('pipeline')->getByID((int)$repo->serviceHost);
        if(!$service) return $repo;

        if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
        {
            if($getCodePath)
            {
                if($repo->SCM == 'Gitlab') $repo->serviceProject = (int)$repo->serviceProject;
                $project = $this->loadModel($repo->SCM)->apiGetSingleProject((int)$repo->serviceHost, $repo->serviceProject);
                if(isset($project->web_url) && $repo->path != $project->web_url)
                {
                    $repo->path = $project->web_url;
                    $this->dao->update(TABLE_REPO)->set('path')->eq($repo->path)->where('id')->eq($repo->id)->exec();
                }
            }

            $repo->path     = (!$repo->path && $service) ? sprintf($this->config->repo->{$service->type}->apiPath, $service->url, $repo->serviceProject) : $repo->path;
            $repo->apiPath  = sprintf($this->config->repo->{$service->type}->apiPath, $service->url, $repo->serviceProject);
            $repo->client   = $service ? $service->url : '';
            $repo->password = $service ? $service->token : '';
            $repo->codePath = isset($project->web_url) ? $project->web_url : $repo->path;
        }
        else
        {
            if(!is_dir($repo->path) && !is_writable(dirname($repo->path)))
            {
                $path = $this->app->getAppRoot() . "www/data/repo/{$repo->name}_{$repo->SCM}";
                $repo->path = $path;

                $this->dao->update(TABLE_REPO)->set('path')->eq($repo->path)->where('id')->eq($repo->id)->exec();
            }

            $repo->codePath = $service ? "{$service->url}/{$repo->serviceProject}" : $repo->path;
        }

        $repo->gitService = (int)$repo->serviceHost;
        return $repo;
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

        /* Update code commit history. */
        $commentGroup = $this->loadModel('job')->getTriggerGroup('commit', array($repo->id));
        if(!in_array($repo->SCM, $this->config->repo->notSyncSCM)) return $this->loadModel('git')->updateCommit($repo, $commentGroup, false);

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $jobs = zget($commentGroup, $repo->id, array());

        $accountPairs  = array();
        $userList      = $this->loadModel($repo->SCM)->apiGetUsers($repo->gitService);
        $accountIDPairs = $this->loadModel('pipeline')->getUserBindedPairs($repo->gitService, strtolower($repo->SCM), 'openID,account');
        foreach($userList as $gitlabUser) $accountPairs[$gitlabUser->realname] = zget($accountIDPairs, $gitlabUser->id, '');

        foreach($data->commits as $commit)
        {
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
            $this->saveAction2PMS($objects, $log, $repo->path, $repo->encoding, 'git', $accountPairs);

            foreach($jobs as $job)
            {
                foreach(explode(',', $job->comment) as $comment)
                {
                    if(strpos($log->msg, $comment) !== false)
                    {
                        $this->loadModel('job')->exec($job->id, array(), 'commit');
                        continue 2;
                    }
                }
            }
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
        if($repo->SCM == 'Subversion')
        {
            $url->svn = $repo->path;
        }
        elseif($repo->SCM == 'Gitlab')
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($repo->gitService, (int)$repo->serviceProject);
            if(isset($project->id))
            {
                $url->http = $project->http_url_to_repo ?? '';
                $url->ssh  = $project->ssh_url_to_repo ?? '';
            }
        }
        elseif($repo->SCM == 'Gitea')
        {
            $project = $this->loadModel('gitea')->apiGetSingleProject($repo->gitService, (string)$repo->serviceProject);
            if(isset($project->id))
            {
                $url->http = $project->clone_url;
                $url->ssh  = $project->ssh_url;
            }
        }
        elseif($repo->SCM == 'Gogs')
        {
            $project = $this->loadModel('gogs')->apiGetSingleProject($repo->gitService, (string)$repo->serviceProject);
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
        if($repo->SCM != 'Subversion' && empty($branch)) $branch = $this->cookie->repoBranch;

        $parent      = '/' . ltrim($parent, '/');
        $fileCommits = $this->dao->select('t1.id,t1.path,t1.type,t1.action,t1.oldPath,t1.parent,t2.revision,t2.comment,t2.committer,t2.time')->from(TABLE_REPOFILES)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
            ->beginIF($repo->SCM != 'Subversion' && $branch)->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')->fi()
            ->where('t1.repo')->eq($repo->id)
            ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
            ->beginIF($repo->SCM != 'Subversion' && $branch)->andWhere('t3.branch')->eq($branch)->fi()
            ->beginIF($repo->SCM == 'Subversion')->andWhere('t1.parent')->eq("$parent")->fi()
            ->beginIF($repo->SCM != 'Subversion')->andWhere('t1.parent')->like("$parent%")->fi()
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
    public function getFileTree(object $repo, string $branch = '', array $diffs = null): array
    {
        set_time_limit(0);
        $allFiles = array();
        if(is_null($diffs))
        {
            if($repo->SCM != 'Subversion' && empty($branch)) $branch = $this->cookie->repoBranch;
            $files = $this->dao->select('t1.path,t2.time,t1.action')->from(TABLE_REPOFILES)->alias('t1')
                ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision=t2.id')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t3')->on('t2.id=t3.revision')
                ->where('t1.repo')->eq($repo->id)
                ->andWhere('t1.type')->eq('file')
                ->andWhere('left(t2.`comment`, 12)')->ne('Merge branch')
                ->beginIF($repo->SCM != 'Subversion' && $branch)->andWhere('t3.branch')->eq($branch)->fi()
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
        $relationList = $this->dao->select('t1.BID as id, t1.BType as type')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.AID = t2.id')
            ->where('t2.revision')->eq($commit)
            ->andWhere('t2.repo')->eq($repoID)
            ->andWhere('t1.AType')->eq('revision')
            ->beginIF($type)->andWhere('t1.BType')->eq($type)->fi()
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
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.AID = t2.id')
            ->where('t1.BID')->eq($objectID)
            ->andWhere('t1.BType')->eq($objectType)
            ->andWhere('t1.AType')->eq('revision')
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

        if($action == 'execjob')    return common::hasPriv('sonarqube', $action) && !$repo->exec;
        if($action == 'reportview') return common::hasPriv('sonarqube', $action) && !$repo->report;
        if(!commonModel::hasPriv('repo', $action)) return false;

        return true;
    }

    /**
     * 获取gitlab项目列表。
     * Get gitlab projects.
     *
     * @param  int    $gitlabID
     * @param  string $projectFilter
     * @access public
     * @return array
     */
    public function getGitlabProjects(int $gitlabID, string $projectFilter = ''): array
    {
        if($this->app->user->admin || ($projectFilter == 'ALL' && common::hasPriv('repo', 'create')))
        {
            $projects = $this->loadModel('gitlab')->apiGetProjects($gitlabID, 'true', 0, 0, false);
        }
        else
        {
            $gitlabUser = $this->loadModel('pipeline')->getOpenIdByAccount($gitlabID, 'gitlab', $this->app->user->account);
            if(!$gitlabUser) return array();

            $projects    = $this->loadModel('gitlab')->apiGetProjects($gitlabID, $projectFilter ? 'false' : 'true');
            $groupIDList = array(0 => 0);
            $groups      = $this->gitlab->apiGetGroups($gitlabID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;
            if($projectFilter == 'IS_DEVELOPER')
            {
                foreach($projects as $key => $project)
                {
                    if(!$this->gitlab->checkUserAccess($gitlabID, 0, $project, $groupIDList, 'developer')) unset($projects[$key]);
                }
            }
        }

        $importedProjects = $this->getImportedProjects($gitlabID);
        $projects         =  array_filter($projects, function($project) use ($importedProjects) { return !in_array($project->id, $importedProjects); });

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
    public function getGroups(int $serverID, int|string $groupID = 0): string|array|false
    {
        $server = $this->loadModel('pipeline')->getByID($serverID);
        if(empty($server->type)) return false;

        $getGroupFunc = 'get' . $server->type . 'Groups';
        $groups       = $this->$getGroupFunc($serverID);

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
    public function checkGiteaConnection(string $scm, string $name, int|string $serviceHost, int|string $serviceProject): string|false
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

        return false;
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
                        $action->product    = $objectType == 'stories' ? $objectList[$objectID]->product : $objectList[$objectID]['product'];
                        $action->execution  = $objectType == 'stories' ? 0 : $objectList[$objectID]['execution'];
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
     * @param  string    $SCM
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getListByCondition(string $repoQuery, string $SCM, string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($repoQuery))->andWhere($repoQuery)->fi()
            ->beginIF($SCM)->andWhere('SCM')->in($SCM)->fi()
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
            ->where('AType')->eq($objectType)
            ->andWhere('relation')->eq('linkrepobranch')
            ->andWhere('AID')->eq($objectID)
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
     * @param  string $scm
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getListByProduct(int $productID, string $scm = '', int $limit = 0): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq('0')
            ->andWhere("FIND_IN_SET({$productID}, `product`)")
            ->beginIF($scm)->andWhere('SCM')->in($scm)->fi()
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

        $showMR     = false;
        $showTag    = false;
        $showBranch = false;
        $showCommit = false;
        $hasTagSCM  = array_map('strtolower', $this->config->repo->notSyncSCM);
        foreach($repoPairs as $repoID => $repoName)
        {
            preg_match('/^\[(\w+)\]/', $repoName, $matches);

            $result = isset($matches[1]) ? $matches[1] : '';
            if($repoID == $this->session->repoID && in_array($result, $hasTagSCM))
            {
                $showTag    = true;
                $showBranch = true;
            }
            if(in_array($result, $this->config->repo->gitServiceList)) $showMR = true;
        }

        $showMR     = $showMR     && common::hasPriv('mr', 'browse');
        $showTag    = $showTag    && common::hasPriv('repo', 'browsetag');
        $showBranch = $showBranch && common::hasPriv('repo', 'browsebranch');
        $showReview = $repoPairs  && common::hasPriv('repo', 'review');
        $showCommit = $repoPairs  && common::hasPriv('repo', 'log');
        foreach($menuGroup as $module)
        {
            if(!isset($this->lang->{$module}->menu->devops['subMenu'])) continue;

            if(!$showMR)     unset($this->lang->{$module}->menu->devops['subMenu']->mr);
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
}
