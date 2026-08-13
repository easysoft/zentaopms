<?php
declare(strict_types=1);
/**
 * The zen file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
class repoZen extends repo
{
    /**
     * 准备创建版本库的数据。
     * Prepare create repo data.
     *
     * @param  form      $formData
     * @param  bool      $isPipelineServer
     * @access protected
     * @return object|false
     */
    protected function prepareCreate(form $formData, bool $isPipelineServer): object|false
    {
        if($this->config->inContainer || $this->config->inQuickon)
        {
            $formData->data->client = $this->post->client = $this->post->SCM == 'Subversion' ? 'svn' : 'git';
        }
        else
        {
            if(!$this->checkClient()) return false;
        }
        if(!$this->checkConnection()) return false;

        $repo = $formData
            ->setIf($isPipelineServer, 'password', $this->post->serviceToken)
            ->setIf($this->post->SCM == 'Gitlab', 'path', '')
            ->setIf($this->post->SCM == 'Gitlab', 'client', '')
            ->setIf($this->post->SCM == 'Gitlab', 'extra', $this->post->serviceProject)
            ->setIf($isPipelineServer, 'prefix', '')
            ->setIf($this->post->SCM == 'Git', 'account', '')
            ->setIf($this->post->SCM == 'Git', 'password', '')
            ->setIf(in_array($this->post->SCM, array('Gitea', 'Gogs')), 'path', $_POST['path'])
            ->setIf($this->post->encrypt == 'base64', 'password', base64_encode($this->post->password))
            ->skipSpecial('path,client,account,password,desc')
            ->setDefault('product', '')->join('product', ',')
            ->setDefault('projects', '')->join('projects', ',')
            ->get();
        if(strpos($repo->client, ' ')) $repo->client = "\"{$repo->client}\"";

        $acl = $this->checkACL();
        if(!$acl) return false;
        $repo->acl = json_encode($acl);

        if($repo->SCM == 'Subversion')
        {
            $scmRepo = clone $repo;
            if($this->post->encrypt == 'base64') $scmRepo->password = $this->post->password;

            $scm = $this->app->loadClass('scm');
            $scm->setEngine($scmRepo);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);

            $path   = str_replace(array(':3690/',':80/'), '/', $repo->path);
            $prefix = str_replace('\\', '/', $path);

            $repo->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', $prefix), '/');
            if($repo->prefix) $repo->prefix = '/' . $repo->prefix;
        }

        if($isPipelineServer)
        {
            $serviceProject = $this->dao->select('*')->from(TABLE_REPO)
                ->where('`SCM`')->eq($repo->SCM)
                ->andWhere('`serviceHost`')->eq($repo->serviceHost)
                ->andWhere('`serviceProject`')->eq($repo->serviceProject)
                ->fetch();
            if($serviceProject)
            {
                dao::$errors['serviceProject'][] = $this->lang->repo->error->projectUnique;
                return false;
            }
        }
        return $repo;
    }

    /**
     * 检查权限数据。
     * Check acl.
     *
     * @access protected
     * @return array|false
     */
    protected function checkACL(): array|false
    {
        $acl = $this->post->acl;
        if($acl['acl'] == 'custom')
        {
            $aclGroups = array_filter($acl['groups']);
            $aclUsers  = array_filter($acl['users']);
            if(empty($aclGroups) && empty($aclUsers))
            {
                $this->app->loadLang('product');
                dao::$errors['acl'] = sprintf($this->lang->error->notempty, $this->lang->product->whitelist);
                return false;
            }
        }
        return $acl;
    }

    /**
     * 检查svn、git客户端。
     * Check svn/git client.
     *
     * @access public
     * @return bool
     */
    protected function checkClient(): bool
    {
        if(in_array($this->post->SCM, $this->config->repo->notSyncSCM)) return true;
        if(!$this->config->features->checkClient) return true;

        if(!$this->post->client)
        {
            dao::$errors['client'] = sprintf($this->lang->error->notempty, $this->lang->repo->client);
            return false;
        }

        $clientVersionFile = $this->session->clientVersionFile;
        if(empty($clientVersionFile))
        {
            $clientVersionFile = $this->app->getLogRoot() . uniqid('version_') . '.log';

            $this->session->set('clientVersionFile', $clientVersionFile);
        }

        if(file_exists($clientVersionFile)) return true;

        $cmd = $this->post->client . " --version > $clientVersionFile";
        dao::$errors['client'] = sprintf($this->lang->repo->error->safe, $clientVersionFile, $cmd);

        return false;
    }


    /**
     * 检查连接。
     * Check connection
     *
     * @access public
     * @return bool
     */
    protected function checkConnection(): bool
    {
        if(empty($_POST)) return false;

        $scm      = $this->post->SCM;
        $client   = $this->post->client;
        $account  = $this->post->account;
        $password = $this->post->password;
        $encoding = strtoupper($this->post->encoding ?: 'UTF-8');
        $path     = $this->post->path;
        if($encoding != 'UTF8' and $encoding != 'UTF-8') $path = helper::convertEncoding($path, 'utf-8', $encoding);

        if($scm == 'Subversion')
        {
            /* Get svn version. */
            $versionCommand = "$client --version --quiet 2>&1";
            exec($versionCommand, $versionOutput, $versionResult);
            if($versionResult)
            {
                $message = sprintf($this->lang->repo->error->output, $versionCommand, $versionResult, implode("\n", $versionOutput));
                dao::$errors['client'] = $this->lang->repo->error->cmd . "\n" . $message;
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
            elseif(stripos($path, 'file://') === 1)
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
                $message = sprintf($this->lang->repo->error->output, $command, $result, implode("\n", $output));
                if(stripos($message, 'Expected FS format between') !== false and strpos($message, 'found format') !== false)
                {
                    dao::$errors['client'] = $this->lang->repo->error->clientVersion;
                    return false;
                }
                if(preg_match('/[^\:\/A-Za-z0-9_\-\'\"\.]/', $path))
                {
                    dao::$errors['encoding'] = $this->lang->repo->error->encoding . "\n" . $message;
                    return false;
                }

                dao::$errors['submit'] = $this->lang->repo->error->connect . "\n" . $message;
                return false;
            }
        }
        elseif(in_array($scm, array('Gitea', 'Gogs')))
        {
            if($this->post->name != '' and $this->post->serviceProject != '')
            {
                $module  = strtolower($scm);
                $project = $this->loadModel($module)->apiGetSingleProject((int)$this->post->serviceHost, $this->post->serviceProject);
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

            if(!is_writable($path) || !is_executable($path))
            {
                dao::$errors['path'] = sprintf($this->lang->repo->error->noPriv, $path);
                return false;
            }

            if(!chdir($path))
            {
                dao::$errors['path'] = $this->lang->repo->error->path;
                return false;
            }

            $command = "$client tag 2>&1";
            exec($command, $output, $result);
            if($result)
            {
                dao::$errors['submit'] = $this->lang->repo->error->cmd . "\n" . sprintf($this->lang->repo->error->output, $command, $result, implode("\n", $output));
                return false;
            }
        }
        return true;
    }

    /**
     * 构建创建版本库页面数据。
     * Build form fields for create repo.
     *
     * @param  int       $objectID
     * @access protected
     * @return void
     */
    protected function buildCreateForm(int $objectID)
    {
        $this->repo->saveState(0, $objectID);

        $this->app->loadLang('action');
        $this->loadModel('product');
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $products = $this->loadModel('project')->getBranchesByProject($objectID);
            $products = $this->product->getProducts($objectID, 'all', '', false, array_keys($products));
        }
        else
        {
            $products = $this->product->getPairs('', 0, '', 'all');
        }

        $this->view->title        = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->create;
        $this->view->groups       = $this->loadModel('group')->getPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products     = $products;
        $this->view->objectID     = $objectID;
        $this->view->spaces       = $this->loadModel('space')->getPairs($this->app->user->account, true);
    }

    /**
     * 构建创建版本库页面数据。
     * Build form fields for create repo.
     *
     * @param  int       $objectID
     * @access protected
     * @return void
     */
    protected function buildCreateRepoForm(int $objectID): void
    {
        $this->repo->saveState(0, $objectID);

        $this->app->loadLang('action');

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $products = $this->loadModel('product')->getProductPairsByProject($objectID);
        }
        else
        {
            $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        }

        $repoGroups   = array();

        $this->view->title        = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->create;
        $this->view->groups       = $this->loadModel('group')->getPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products     = $products;
        $this->view->repoGroups   = $repoGroups;
        $this->view->objectID     = $objectID;
        $this->view->spaces       = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account, true);
    }

    /**
     * 构建编辑版本库页面数据。
     * Build form fields for edit repo.
     *
     * @param  int       $objectID
     * @access protected
     * @return void
     */
    protected function buildEditForm(int $repoID, int $objectID): void
    {
        $repo = $this->repo->getByID($repoID);
        $repo->client = trim($repo->client, '"');
        $this->app->loadLang('action');

        $products           = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $linkedProducts     = $this->product->getByIdList(explode(',', $repo->product));
        $linkedProductPairs = array_combine(array_keys($linkedProducts), helper::arrayColumn($linkedProducts, 'name'));
        $products           = $products + $linkedProductPairs;

        $this->view->title           = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->edit;
        $this->view->repo            = $repo;
        $this->view->repoID          = $repoID;
        $this->view->objectID        = $objectID;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->relatedProjects = $this->repo->filterProject(explode(',', $repo->product), array());
        $this->view->spaces          = $this->loadModel('space')->getPairs($this->app->user->account);
    }

    /**
     * 通过Graphql获取GitLab项目列表。
     * Get GitLab projects by Graphql.
     *
     * @param  object $server
     * @access public
     * @return array
     */
    protected function getGitlabProjectsByApi(object $server): array
    {
        $repoList    = array();
        $endCursor   = '';
        $hasNextPage = true;
        $url = rtrim($server->url, '/') . '/api/graphql' . "?private_token={$server->token}";
        while($hasNextPage)
        {
            $query    = 'query { projects(after: "' . $endCursor . '") {pageInfo {endCursor hasNextPage} nodes {nameWithNamespace id name}}}';
            $response = json_decode(commonModel::http($url, array('query' => $query), array(CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1)));
            if(!$endCursor && !isset($response->data->projects->nodes)) return array();

            foreach($response->data->projects->nodes as $project)
            {
                preg_match('/\d+/', $project->id, $projectID);
                $project->id                  = $projectID ? $projectID[0] : $project->id;
                $project->name_with_namespace = $project->nameWithNamespace;
                $repoList[]  = $project;
            }
            $hasNextPage = $response->data->projects->pageInfo->hasNextPage;
            $endCursor   = $response->data->projects->pageInfo->endCursor;
        }
        return $repoList;
    }

    /**
     * 获取版本库文件列表信息。
     * Get repo files info.
     *
     * @param  object    $repo
     * @param  string    $path
     * @param  string    $branchID
     * @param  string    $base64BranchID
     * @param  int       $objectID
     * @access protected
     * @return array
     */
    protected function getFilesInfo(object $repo, string $path, string $branchID, string $base64BranchID, int $objectID): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $infos = $scm->ls($path);

        foreach($infos as $info)
        {
            $info->originalComment = $info->comment;
            $info->comment         = $this->repo->replaceCommentLink($info->comment ? $info->comment : '');
            $info->revision        = substr($info->revision, 0, 10);

            $infoPath = trim(urldecode($path) . '/' . $info->name, '/');
            if($info->kind == 'dir')
            {
                $info->link = $this->repo->createLink('browse', "repoID={$repo->id}&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($infoPath));
            }
            else
            {
                $info->link = $this->repo->createLink('view', "repoID={$repo->id}&objectID=$objectID&entry=" . $this->repo->encodePath($infoPath));
            }
        }

        return $infos;
    }

    /**
     * 获取分支与tag下拉菜单组件配置。
     * Get items of branch and tags menu.
     *
     * @param  object    $repo
     * @param  string    $branchID
     * @access protected
     * @return array
     */
    protected function getBranchAndTagItems(object $repo, string $branchID): array
    {
        /* Set branch or tag for git. */
        $branches = $tags = array();

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $branches = $scm->branch();
        $initTags = $scm->tags('');
        foreach($initTags as $tag) $tags[$tag] = $tag;

        $selected    = '';
        $branchMenus = array();
        $tagMenus    = array();
        foreach($branches as $branchName)
        {
            $selected = ($branchName == $branchID) ? $branchName : $selected;
            $branchMenus[]  = array(
                'text'       => $branchName,
                'id'         => $branchName,
                'keys'       => zget(common::convert2Pinyin(array($branchName)), $branchName, ''),
                'url'        => 'javascript:;',
                'data-type'  => 'branch',
                'data-value' => $branchName,
            );
        }
        foreach($tags as $tagName)
        {
            $selected = ($tagName == $branchID) ? $tagName : $selected;
            $tagMenus[]  = array(
                'text'       => $tagName,
                'id'         => $tagName,
                'keys'       => zget(common::convert2Pinyin(array($tagName)), $tagName, ''),
                'url'        => 'javascript:;',
                'data-type'  => 'tag',
                'data-value' => $tagName,
            );
        }

        return array('branchMenus' => $branchMenus, 'tagMenus' => $tagMenus, 'selected' => $selected);
    }

    /**
     * 更新版本库最后提交时间。
     * Update repo last commited date.
     *
     * @param  object    $repo
     * @param  object    $lastRevision
     * @access protected
     * @return void
     */
    protected function updateLastCommit(object $repo, object $lastRevision): void
    {
        if(empty($lastRevision->committed_date)) return;
        $lastCommitDate = date('Y-m-d H:i:s', strtotime($lastRevision->committed_date));
        if(empty($repo->lastCommit) || $lastCommitDate > $repo->lastCommit) $this->dao->update(TABLE_REPO)->set('lastCommit')->eq($lastCommitDate)->where('id')->eq($repo->id)->exec();
    }

    /**
     * 为git类型版本库设置分支和tag。
     * Set branch or tag for git.
     *
     * @param  object    $repo
     * @param  string    $branchID
     * @access protected
     * @return array
     */
    protected function setBranchTag(object $repo, string $branchID): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $branches = isset($branchInfo) && $branchInfo !== false ? $branchInfo : $scm->branch();
        $initTags = isset($tagInfo) && $tagInfo !== false ? $tagInfo : $scm->tags('');
        $tags     = array();
        foreach($initTags as $tag) $tags[$tag] = $tag;

        if(empty($branchID) and $this->cookie->repoBranch && $this->session->repoID == $repo->id) $branchID = $this->cookie->repoBranch;
        if(!isset($branches[$branchID]) && !isset($tags[$branchID])) $branchID = (string)key($branches);
        if($branchID) $this->setRepoBranch($branchID);

        return array($branchID, $branches, $tags);
    }

    /**
     * 获取commits列表
     * Get commits.
     *
     * @param  object    $repo
     * @param  string    $path
     * @param  string    $revision
     * @param  string    $type
     * @param  object    $pager
     * @param  int       $objectID
     * @access protected
     * @return array
     */
    protected function getCommits(object $repo, string $path, string $revision, string $type, object $pager, int $objectID): array
    {
        $revisions = $this->repo->getCommits($repo, $path, $revision, $type, $pager);
        $pathInfo  = '&root=' . $this->repo->encodePath(empty($path) ? '/' : $path);
        foreach($revisions as $item)
        {
            $item->link     = $this->repo->createLink('revision', "repoID={$repo->id}&objectID=$objectID&revision={$item->revision}" . $pathInfo);
            $item->revision = $item->revision ? substr($item->revision, 0, 10) : $item->revision;
        }

        return $revisions;
    }

    /**
     * 设置session信息。
     * Set session.
     *
     * @param  object    $repo
     * @access protected
     * @return void
     */
    protected function setBrowseSession(?object $repo = null): void
    {
        $this->setBackSession('list', true);

        $this->session->set('revisionList', $this->app->getURI(true));
        $this->session->set('gitlabBranchList', $this->app->getURI(true));
        if(!empty($repo->space))
        {
            $spaceRepoMap = $this->session->spaceRepoMap ? json_decode($this->session->spaceRepoMap, true) : array();
            $spaceRepoMap[$repo->space] = $repo->id;
            $this->session->set('spaceRepoMap', json_encode($spaceRepoMap), 'devops');
        }
    }

    /**
     * 构建版本库搜索框。
     * Build repo search form.
     *
     * @param  int       $inSpace
     * @param  int       $space
     * @param  array     $products
     * @param  int       $objectID
     * @param  string    $orderBy
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function buildRepoSearchForm(int $inSpace, int $space, array $products, int $objectID, string $orderBy, int $recPerPage, int $pageID, int $param): void
    {
        $this->config->repo->search['params']['product']['values']  = $products;
        $this->config->repo->search['actionURL']   = $this->createLink('repo', 'maintain', "inSpace={$inSpace}&space={$space}&objectID={$objectID}&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}&type=bysearch&param=myQueryID");
        $this->config->repo->search['queryID']     = $param;
        $this->config->repo->search['onMenuBar']   = 'yes';
        $this->loadModel('search')->setSearchParams($this->config->repo->search);
    }

    /**
     * 获取关联产品模块。
     * Get link product modules.
     *
     * @param  array     $products
     * @param  string    $type story|task|bug
     * @access protected
     * @return array
     */
    protected function getLinkModules(array $products, string $type): array
    {
        $modules  = array();
        foreach($products as $productID => $product)
        {
            $productModules = $this->loadModel('tree')->getModulePairs($productID, $type);
            foreach($productModules as $key => $module) $modules[$key] = $product->name . ' / ' . $module;
        }

        return $modules;
    }

    /**
     * 获取关联产品分支。
     * Get link product branches.
     *
     * @param  array     $products
     * @access protected
     * @return array
     */
    protected function getLinkBranches(array $products): array
    {
        $productBranches = array();
        foreach($products as $product)
        {
            if($product->type != 'normal')
            {
                $branches = $this->loadModel('branch')->getPairs($product->id, 'noempty');
                foreach($branches as $branchID => $branchName)
                {
                    $branches[$branchID] = $product->name . ' / ' . $branchName;
                }

                $productBranches += $branches;
            }
        }

        return $productBranches;
    }

    /**
     * 获取产品关联执行列表。
     * Get link product executions.
     *
     * @param  array     $products
     * @access protected
     * @return array
     */
    protected function getLinkExecutions(array $products): array
    {
        $executions = array();
        foreach($products as $product)
        {
            $productExecutions = $this->loadModel('product')->getExecutionPairsByProduct($product->id);
            $executions       += $productExecutions;
        }

        return $executions;
    }

    /**
     * 构建需求搜索表格。
     * Build story search form.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $modules
     * @access protected
     * @return void
     */

    protected function buildStorySearchForm(int $repoID, string $revision, string $browseType, int $queryID, array $products, array $modules): void
    {
        unset($this->lang->story->statusList['closed']);
        $storyStatusList = $this->lang->story->statusList;

        $this->config->product->search['actionURL']                   = $this->createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['style']                       = 'simple';
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getForProducts(array_keys($products));
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);
        $this->config->product->search['params']['product']['values'] = helper::arrayColumn($products, 'name', 'id');

        unset($this->config->product->search['fields']['roadmap']);
        unset($this->config->product->search['params']['roadmap']);
        unset($this->config->product->search['fields']['grade']);
        unset($this->config->product->search['params']['grade']);

        $productBranches = $this->getLinkBranches($products);
        if(empty($productBranches))
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName['branch']);
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName['branch']);
            $this->config->product->search['params']['branch']['values'] = $productBranches;
        }

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 获取关联需求列表。
     * Get link stories list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $productIds
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access protected
     * @return array
     */
    protected function getLinkStories(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID): array
    {
        $linkedStories = $this->repo->getRelationByCommit($repoID, $revision, 'story');
        $linkedStories = empty($linkedStories) ? array() : array_column($linkedStories, 'id');
        $allStories    = array();
        if($browseType == 'bySearch')
        {
            foreach($products as $productID => $product)
            {
                $productStories = $this->loadModel('story')->getBySearch($productID, 'all', $queryID, $orderBy, 0, 'story', $linkedStories);
                $allStories     = array_merge($allStories, $productStories);
            }

            $allStories = array_filter($allStories, function($story) { return $story->isParent == '0'; });
        }
        else
        {
            foreach($products as $productID => $product)
            {
                $productStories = $this->loadModel('story')->getProductStories($productID, 'all', '0', 'draft,active,changed', 'story', $orderBy, true, $linkedStories);
                $allStories     = array_merge($allStories, $productStories);
            }
        }

        return $this->getDataPager($allStories, $pager);
    }

    /**
     * 获取数据根据分页。
     *  Get data by pager.
     *
     * @param  array     $data
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getDataPager(array $data, object $pager): array
    {
        $pager->setRecTotal(count($data));
        $pager->setPageTotal();

        $dataList = array_chunk($data, $pager->recPerPage);
        $pageData = empty($dataList) ? array() : $dataList[$pager->pageID - 1];

        return $pageData;
    }

    /**
     * 构建bug搜索表格。
     * Build bug search form.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $modules
     * @access protected
     * @return void
     */
    protected function buildBugSearchForm(int $repoID, string $revision, string $browseType, int $queryID, array $products, array $modules): void
    {
        $productIds = array_keys($products);
        $this->config->bug->search['params']['status']['values']        = array_slice($this->lang->bug->statusList, 1, 1);
        $this->config->bug->search['actionURL']                         = $this->createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=bysearch&queryID=myQueryID");
        $this->config->bug->search['queryID']                           = $queryID;
        $this->config->bug->search['style']                             = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts($productIds);
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->getLinkExecutions($products);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($productIds, 'all', '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productIds, 'all', '');
        $this->config->bug->search['params']['product']['values']       = helper::arrayColumn($products, 'name', 'id');

        $productBranches = $this->getLinkBranches($products);
        if(empty($productBranches))
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName['branch']);
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName['branch']);
            $this->config->bug->search['params']['branch']['values'] = $productBranches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 获取关联bug列表。
     * Get link bugs list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access protected
     * @return array
     */
    protected function getLinkBugs(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID): array
    {
        $this->loadModel('bug');
        $linkedBugs = $this->repo->getRelationByCommit($repoID, $revision, 'bug');
        $linkedBugs = empty($linkedBugs) ? array() : array_column($linkedBugs, 'id');
        $allBugs    = array();
        if($browseType == 'bysearch')
        {
                $allBugs = $this->bug->getBySearch('bug', array_keys($products), 0, 0, 0, $queryID, implode(',', $linkedBugs), $orderBy);
                foreach($allBugs as $bugID => $bug)
                {
                    if($bug->status != 'active') unset($allBugs[$bugID]);
                }
        }
        else
        {
            foreach($products as $product)
            {
                $productBugs = $this->bug->getActiveBugs($product->id, 0, '0', $linkedBugs, null, $orderBy);
                $allBugs     = array_merge($allBugs, $productBugs);
            }
        }

        $allBugs = $this->getDataPager($allBugs, $pager);
        foreach($allBugs as $bug) $bug->statusText = $this->processStatus('bug', $bug);
        return $allBugs;
    }

    /**
     * 构建任务搜索表格。
     * Build task search form.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $modules
     * @param  array     $executionPairs
     * @access protected
     * @return void
     */
    protected function buildTaskSearchForm(int $repoID, string $revision, string $browseType, int $queryID, array $modules, array $executionPairs): void
    {
        $this->config->execution->search['actionURL']                     = $this->createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=bysearch&queryID=myQueryID", '', true);
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['style']                         = 'simple';
        $this->config->execution->search['params']['module']['values']    = $modules;
        $this->config->execution->search['params']['execution']['values'] = array('' => '') + $executionPairs;

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
     * 获取关联任务列表。
     * Get link tasks list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @param  array     $executionPairs
     * @access protected
     * @return array
     */
    protected function getLinkTasks(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID, array $executionPairs): array
    {
        $allTasks = array();
        foreach($executionPairs as $executionID => $executionName)
        {
            $tasks     = $this->loadModel('execution')->getTasks(0, $executionID, array(), $browseType, $queryID, 0, $orderBy, null);
            $allTasks += $tasks;
        }

        if($browseType == 'bysearch')
        {
            foreach($allTasks as $key => $task)
            {
                if(!empty($task->children))
                {
                    $allTasks = array_merge($task->children, $allTasks);
                    unset($task->children);
                }
            }
            foreach($allTasks as $key => $task)
            {
                if($task->status == 'closed') unset($allTasks[$key]);
            }
        }

        /* Filter linked tasks. */
        $linkedTasks   = $this->repo->getRelationByCommit($repoID, $revision, 'task');
        $linkedTaskIDs = empty($linkedTasks) ? array() : array_column($linkedTasks, 'id');
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        return $this->getDataPager($allTasks, $pager);
    }

    /**
     * 关联对象。
     * Link object.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $type story|bug|task
     * @access protected
     * @return array
     */
    protected function linkObject(int $repoID, string $revision, string $type): array
    {
        $this->repo->link($repoID, $revision, $type);
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        return array('result' => 'success', 'callback' => "$('.tab-content .active iframe')[0].contentWindow.getRelation('$revision')", 'closeModal' => true);
    }

    /**
     * 检查删除版本库的错误。
     * Check repo delete error.
     *
     * @param  int $repoID
     * @access protected
     * @return string
     */
    protected function checkDeleteError(int $repoID): string
    {
        $relationIds = $this->dao->select('distinct `AID` as `AID`')->from(TABLE_RELATION)
            ->where('extra')->eq($repoID)
            ->andWhere('AType')->eq('design')
            ->fetchAll();
        $error = '';
        if($relationIds)
        {
            $tmpDesignLinks = [];
            foreach ($relationIds as $value)
            {
                array_push($tmpDesignLinks, html::a($this->createLink('design', 'view', 'designID=' . $value->AID), $value->AID, '_blank', '', false));
            }
            $error .= sprintf($this->lang->repo->error->deleted, implode(', ', $tmpDesignLinks));
        }
        $linkBranches = $this->repo->getLinkedBranch(0, '', $repoID);
        if(!empty($linkBranches))
        {
            $tmpLinkBranches = [];
            foreach($linkBranches as $value)
            {
                if(!array_key_exists($value->AType, $tmpLinkBranches)) $tmpLinkBranches[$value->AType] = [];

                if(!in_array($value->BType, $tmpLinkBranches[$value->AType])) array_push($tmpLinkBranches[$value->AType], $value->BType);
            }
            foreach($tmpLinkBranches as $type => $value)
            {
                $error .= sprintf($this->lang->repo->error->linkedBranch, $this->lang->$type->common, html::a(
                    $this->createLink('repo', 'browseBranch', 'repoID=' . $repoID),
                    implode(', ', $value), '_blank', '', false
                ));
            }
        }
        $pipeline = $this->dao->select('*')->from(TABLE_PIPELINE)->where('repoID')->eq($repoID)->andWhere('deleted')->eq('0')->fetchAll();
        if($pipeline) $error .= sprintf($this->lang->repo->error->linkedJob, html::a($this->createLink('pipeline', 'browse', 'space=0&repoID=' . $repoID . '&type=repo'), implode(', ', array_column($pipeline, 'id')), '_blank', '', false));

        $artifacts = $this->loadModel('artifact')->getByRepoID($repoID);
        if(!empty($artifacts)) $error .= sprintf($this->lang->repo->error->linkedArtifact, html::a($this->createLink('artifact', 'browse', 'spaceID=0&repoID=' . $repoID . '&type=repo'), implode(', ', array_keys($artifacts)), '_blank', '', false));

        return $error;
    }

    /**
     * 跳转到版本库的diff页面。
     * Redirect to diff page.
     *
     * @param  int       $repoID
     * @param  int       $objectID
     * @param  string    $arrange
     * @param  int       $isBranchOrTag
     * @param  string    $file
     * @access protected
     * @return void
     */
    protected function locateDiffPage(int $repoID, int $objectID, string $arrange, int $isBranchOrTag, string $file)
    {
        $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
        $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';
        $encoding    = '';

        if($this->post->encoding)      $encoding      = $this->post->encoding;
        if($this->post->isBranchOrTag) $isBranchOrTag = (int)$this->post->isBranchOrTag;

        if($this->post->arrange) $arrange = $this->post->arrange;
        helper::setcookie('arrange', $arrange);

        return $this->locate($this->repo->createLink('diff', "repoID={$repoID}&objectID={$objectID}&entry={$file}&oldrevision={$oldRevision}&newRevision={$newRevision}&showBug=0&encoding={$encoding}&isBranchOrTag={$isBranchOrTag}"));
    }

    /**
     * 设置对比信息的编码格式。
     * Set encoding for diff.
     *
     * @param  array     $diffs
     * @param  string    $encoding
     * @access protected
     * @return array
     */
    protected function encodingDiff(array $diffs, string $encoding): array
    {
        foreach($diffs as $diff)
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

        return $diffs;
    }

    /**
     * 获取代码同步本地的日志。
     * Get sync log.
     *
     * @param  object    $repo
     * @access protected
     * @return string
     */
    protected function syncLocalCommit(object $repo): string
    {
        $logFile = realPath($this->app->getTmpRoot() . $this->config->repo->repoSyncLog->logFilePrefix . strtolower($repo->scmType) . ".{$repo->name}.log");
        if($logFile && file_exists($logFile))
        {
            $content  = file($logFile);
            foreach($content as $line)
            {
                if($this->strposAry($line, $this->config->repo->repoSyncLog->fatal) !== false) return $line;
                if($this->strposAry($line, $this->config->repo->repoSyncLog->failed) !== false) return $line;
            }

            $lastLine = $content[count($content) - 1];
            if($this->strposAry($lastLine, $this->config->repo->repoSyncLog->done) === false)
            {
                if($this->strposAry($lastLine, $this->config->repo->repoSyncLog->emptyRepo) !== false)
                {
                    @unlink($logFile);
                }
                elseif($this->strposAry($lastLine, $this->config->repo->repoSyncLog->total) !== false)
                {
                    $logContent = file_get_contents($logFile);
                    $countFinished = $this->strposAry($logContent, $this->config->repo->repoSyncLog->finishCount) !== false || preg_match("/Counting objects:\s*(?:\d+%|\d+,\s*done\.)/i", $logContent);
                    $compressFinished = $this->strposAry($logContent, $this->config->repo->repoSyncLog->finishCompress) !== false;
                    if($countFinished and $compressFinished)
                    {
                        @unlink($logFile);
                    }
                    else
                    {
                        return $this->config->repo->repoSyncLog->one;
                    }
                }
                else
                {
                    return $this->config->repo->repoSyncLog->one;
                }
            }
            else
            {
                @unlink($logFile);
            }
        }

        return '';
    }

    /**
     * 获取需要同步的分支。
     * Get sync branches.
     *
     * @param  string    $branchID
     * @access protected
     * @return array
     */
    protected function getSyncBranches(string &$branchID = ''): array
    {
        $branches = array();
        $branches = $this->scm->branch();
        if(empty($branches)) return array();

        $tags = $this->scm->tags('');
        foreach($tags as $tag) $branches[$tag] = $tag;

        if($branches)
        {
            /* Init branchID. */
            if($this->cookie->syncBranch) $branchID = $this->cookie->syncBranch;
            if(!isset($branches[$branchID])) $branchID = '';
            if(empty($branchID)) $branchID = key($branches);

            /* Get unsynced branches. */
            foreach($branches as $branch)
            {
                unset($branches[$branch]);
                if($branch == $branchID) break;
            }

            $this->setRepoBranch($branchID);
            helper::setcookie("syncBranch", $branchID, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }

        return $branches;
    }

    /**
     * 获取同步结果。
     * Get sync result.
     *
     * @param  object    $repo
     * @param  array     $branches
     * @param  string    $branchID
     * @param  int       $commitCount
     * @param  string    $type
     * @access protected
     * @return string|int
     */
    protected function checkSyncResult(object $repo, array $branches, string $branchID, int $commitCount, string $type): string|int
    {
        if(empty($commitCount) && !$repo->synced)
        {
            if($branchID) $this->repo->saveExistCommits4Branch($repo->id, $branchID);
            if($branches)
            {
                $branchID = array_shift($branches);
                helper::setcookie("syncBranch", $branchID);
            }
            else
            {
                $branchID = '';
            }

            if($branchID) $this->repo->fixCommit($repo->id);

            helper::setcookie("syncBranch", '');

            $this->repo->markSynced($repo->id);
            return $this->config->repo->repoSyncLog->finish;
        }

        return $type == 'batch' ? $commitCount : $this->config->repo->repoSyncLog->finish;
    }

    /**
     * 设置返回链接。
     * Set back session.
     *
     * @param  string $type
     * @param  bool   $withOtherModule
     * @access public
     * @return void
     */
    public function setBackSession(string $type = 'list', bool $withOtherModule = false)
    {
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
    }

    /**
     * 设置代码库分支。
     * Set repo branch.
     *
     * @param  string $branch
     * @access public
     * @return void
     */
    public function setRepoBranch(string $branch)
    {
        helper::setcookie("repoBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        $_COOKIE['repoBranch'] = $branch;
    }

    /**
     * 检查是否是二进制文件。
     * Check content is binary.
     *
     * @param  string $content
     * @param  string $suffix
     * @access public
     * @return bool
     */
    public function isBinary(string $content, string $suffix = ''): bool
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
     * 检查字符串是否在数组元素中。
     * Check str in array.
     *
     * @param  string $str
     * @param  array  $checkAry
     * @access public
     * @return bool
     */
    public function strposAry(string $str, array $checkAry): bool
    {
        foreach($checkAry as $check)
        {
            if(mb_strpos($str, $check) !== false) return true;
        }

        return false;
    }

    /**
     * 获取详情页面目录树。
     * Get view tree.
     *
     * @param  object    $repo
     * @param  string    $entry
     * @param  string    $revision
     * @param  string    $selectFile
     * @access protected
     * @return array
     */
    protected function getViewTree(object $repo, string $entry, string $revision, string $selectFile = ''): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $tree = $scm->ls($entry, (string)$revision);

        foreach($tree as &$file)
        {
            $base64Name = $this->repo->encodePath($file->path);

            $file->path = trim($file->path, '/');
            if(!isset($file->id))    $file->id    = $base64Name;
            if(!isset($file->key))   $file->key   = $base64Name;
            if(!isset($file->text))  $file->text  = trim($file->name, '/');
            if($selectFile == $file->id) $file->className = 'selected';
            if($file->kind == 'dir') $file->items = array('url' => helper::createLink('repo', 'ajaxGetFiles', "repoID={$repo->id}&branch={$revision}&path=" . helper::safe64Encode($file->path) . "&selectFile={$selectFile}"));
        }

        return $tree;
    }

    /**
     * 检查代码库是否能正常访问。
     * Check repo connected.
     *
     * @param  object    $repo
     * @access protected
     * @return bool
     */
    protected function checkRepoInternet(object $repo): bool
    {
        if(!$repo) return false;

        $repoUrl = '';
        if(empty($repoUrl) && isset($repo->path)    && substr($repo->path, 0, 4) == 'http')    $repoUrl = $repo->path;
        if(empty($repoUrl) && isset($repo->client)  && substr($repo->client, 0, 4) == 'http')  $repoUrl = $repo->client;
        if(empty($repoUrl) && isset($repo->apiPath) && substr($repo->apiPath, 0, 4) == 'http') $repoUrl = $repo->apiPath;
        return $repoUrl && !$this->loadModel('admin')->checkInternet($repoUrl, 3);
    }

    /**
     * 翻译API返回错误信息。
     * Parse api log to client lang.
     *
     * @param  string $message
     * @access protected
     * @return string
     */
    protected function parseErrorContent(string $message): string
    {
        foreach($this->lang->repo->apiError as $key => $pattern)
        {
            if(preg_match("/$pattern/i", $message))
            {
                $message = zget($this->lang->repo->errorLang, $key);
                break;
            }
        }

        return $message;
    }

    /**
     * 构建代码库路径。
     * Build repo paths.
     *
     * @param  array $repos
     * @access protected
     * @return array
     */
    protected function buildRepoPaths(array $repos): array
    {
        $pathList = array();
        foreach($repos as $repoID => $path)
        {
            $paths  = explode('/', $path);
            $parent = '';
            foreach($paths as $path)
            {
                $path = trim($path);
                if($path === '') continue;

                $parentID = $parent == '' ? '0' : $pathList[$parent]['path'];
                $parent  .= $parent == '' ? $path : '/' . $path;
                if(!isset($pathList[$parent]))
                {
                    $pathList[$parent] = array(
                        'value'  => $repoID,
                        'parent' => $parentID,
                        'path'   => $parent,
                        'text'   => $path,
                    );
                }
            }
        }

        ksort($pathList);
        return $this->buildRepoTree($pathList, '0');
    }

    /**
     * 组装代码库生成父子结构。
     * Assemble repo path to generate parent-child structure.
     *
     * @param  array $pathList
     * @param  string $parent
     * @access protected
     * @return array
     */
    protected function buildRepoTree(array $pathList = array(), string $parent = '0'): array
    {
        $treeList = array();
        $key      = 0;
        $pathName = array();
        $repoName = array();

        foreach($pathList as $path)
        {
            if ($path['parent'] == $parent)
            {
                $treeList[$key] = $path;
                $repoName[$key] = $path['text'];
                /* Default value is '~', because his ascii code is large in string. */
                $pathName[$key] = '~';

                $children = $this->buildRepoTree($pathList, $path['path']);

                if($children)
                {
                    unset($treeList[$key]['value']);
                    $treeList[$key]['disabled'] = true;
                    $treeList[$key]['items'] = $children;
                    $repoName[$key]          = '';
                    $pathName[$key]          = $path['path'];
                }
            }

            $key++;
        }

        array_multisort($pathName, SORT_ASC, $repoName, SORT_ASC, $treeList);
        return $treeList;
    }

    /**
     * 获取分支和标签列表的picker数据。
     * Get branch and tag picker data.
     *
     * @param  object    $scm
     * @access protected
     * @return array
     */
    protected function getBranchAndTagOptions(object $scm): array
    {
        $options = array(
            array('text' => $this->lang->repo->branch, 'items' => array(), 'disabled' => true),
            array('text' => $this->lang->repo->tag,    'items' => array(), 'disabled' => true)
        );

        $branches = $scm->branch();
        foreach($branches as $branch) $options[0]['items'][] = array('text' => $branch, 'value' => $branch, 'key' => $branch);

        $tags = $scm->tags();
        foreach($tags as $tag) $options[1]['items'][] = array('text' => $tag, 'value' => $tag, 'key' => $tag);

        if(empty($tags))     unset($options[1]);
        if(empty($branches)) unset($options[0]);
        return $options;
    }

    /**
     * 获取代码库ID，并且设置页面的代码库数据。
     * Process repoID and set page repo data.
     *
     * @param  int       $repoID
     * @param  int       $objectID
     * @param  array     $scmList
     * @access protected
     * @return int
     */
    protected function processRepoID(int $repoID, int $objectID, array $scmList = array()): int
    {
        if(!$repoID) $repoID = (int)$this->session->repoID;

        $repoPairs = array();
        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            if(!$scmList) $scmList = $this->config->repo->notSyncSCM;
            $repoList = $this->repo->getList($objectID);
            foreach($repoList as $repo)
            {
                $repoPairs[$repo->id] = $repo->name;
            }
            if(!isset($repoPairs[$repoID])) $this->locate(inLink('browse', "repoID=$repoID&objectID=$objectID"));
        }

        $this->view->repoID    = $repoID;
        $this->view->repoPairs = $repoPairs;
        $repoID = $this->repo->saveState($repoID, $objectID);

        return $repoID;
    }

    /**
     * 构建 webhook 数据。
     * Build webhook payload.
     *
     * @param  object      $webhookFormData
     * @param  object      $repo
     * @param  object|null $editWebhook
     * @access public
     * @return object
     */
    public function buildWebhook(object $webhookFormData, object $repo, ?object $editWebhook = null): object
    {
        $name        = zget($webhookFormData, 'name');
        $webhookList = $this->loadModel('gitfox')->apiGetHooks((int)$repo->id);
        if(!empty($webhookList))
        {
            foreach($webhookList as $hook)
            {
                if($hook->displayName == $name && (!$editWebhook || $editWebhook->displayName != $name))
                {
                    dao::$errors['name'][] = sprintf($this->lang->repo->webhook->nameExists, $name);
                }
            }
        }

        $url = zget($webhookFormData, 'targetURL');
        if(!filter_var($url, FILTER_VALIDATE_URL)) dao::$errors['targetURL'][] = $this->lang->repo->webhook->urlError;

        if(mb_strlen((string)zget($webhookFormData, 'desc', '')) > 100) dao::$errors['desc'][] = sprintf($this->lang->repo->webhook->lengthError, $this->lang->repo->webhook->desc, 100);

        $triggerEvent = zget($webhookFormData, 'triggerEvent');
        $customEvent  = zget($webhookFormData, 'customEvent');
        if(!$triggerEvent) $customEvent = array();
        if($triggerEvent && empty($customEvent)) dao::$errors['customEvent'][] = $this->lang->repo->webhook->customEventError;

        $webhook              = new stdClass();
        $webhook->displayName = zget($webhookFormData, 'name');
        $webhook->url         = $url;
        $webhook->description = zget($webhookFormData, 'desc');
        $webhook->insecure    = empty($webhookFormData->SSL);
        $webhook->triggers    = $customEvent;
        $webhook->enabled     = zget($webhookFormData, 'enable', true);
        if(isset($webhookFormData->key)) $webhook->secret = zget($webhookFormData, 'key');

        if(!empty($editWebhook))
        {
            foreach($editWebhook as $key => $value)
            {
                if(!isset($webhook->$key)) continue;

                if($key == 'triggers')
                {
                    $value = $value ? $value : array();
                    if(empty(array_diff($customEvent, $value)) && empty(array_diff($value, $customEvent))) unset($webhook->triggers);
                    continue;
                }

                if($webhook->$key == $value) unset($webhook->$key);
            }
        }

        return $webhook;
    }

    /**
     * 构建提交页面搜索表单。
     * Build commit search form.
     *
     * @param  int       $queryID
     * @param  string    $actionURL
     * @access protected
     * @return void
     */
    protected function buildSearchForm(int $queryID, string $actionURL)
    {
        $this->config->repo->search = $this->config->repo->searchCommits;
        $this->config->repo->search['actionURL'] = $actionURL;
        $this->config->repo->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->repo->search);
    }

    /**
     * 获取搜索表单字段。
     * Get search form field.
     *
     * @param  int       $queryID
     * @param  bool      $getSql
     * @access protected
     * @return object|string
     */
    protected function getSearchForm(int $queryID = 0, bool $getSql = false): object|string
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('repoCommitsQuery', $query->sql);
                $this->session->set('repoCommitsForm', $query->form);
            }
            else
            {
                $this->session->set('repoCommitsQuery', ' 1 = 1');
            }
        }

        if($getSql)
        {
            $query = $this->session->repoCommitsQuery;
            $query = str_replace("`date`", 't1.`time`', $query);
            $query = str_replace("`committer`", 't1.`committer`', $query);
            $query = str_replace("`commit`", 't1.`revision`', $query);
        }
        else
        {
            $query = $this->getSearchFormQuery();
        }

        return $query;
    }

    /**
     * 获取搜索表单查询字段。
     * Get search form query field.
     *
     * @access protected
     * @return object
     */
    protected function getSearchFormQuery(): object
    {
        $query = new stdclass();
        $query->begin     = '';
        $query->end       = '';
        $query->committer = '';
        $query->commit    = '';
        if(!$this->session->repoCommitsForm) return $query;

        $this->app->loadClass('date');
        $lastWeek  = date::getLastWeek();
        $thisWeek  = date::getThisWeek();
        $lastMonth = date::getLastMonth();
        $thisMonth = date::getThisMonth();
        $yesterday = date::yesterday();
        $today     = date(DT_DATE1);
        foreach($this->session->repoCommitsForm as $field)
        {
            if(empty($field['value'])) continue;

            if(strpos($field['value'], '$') !== false)
            {
                $dateField = substr($field['value'], 1);
                $query->begin = substr(${$dateField}['begin'], 0, 10) . ' 00:00:00';
                $query->end   = substr(${$dateField}['end'], 0, 10) . ' 23:59:59';
            }
            elseif($field['field'] == 'date')
            {
                if($field['operator'] == '>=' || $field['operator'] == '=') $query->begin = $field['value'];
                if($field['operator'] == '>')  $query->begin = date('Y-m-d', strtotime("{$field['value']} +1 day"));
                if($field['operator'] == '<=') $query->end   = $field['value'];
                if($field['operator'] == '<')  $query->end   = date('Y-m-d', strtotime("{$field['value']} -1 day"));
                if($field['operator'] == '=')  $query->end   = $field['value'] . ' 23:59:59';
            }
            elseif(in_array($field['field'], array('committer', 'commit')) && $field['value'])
            {
                $query->{$field['field']} = $field['value'];
            }
        }

        return $query;
    }

    /**
     * 构建应用搜索表单字段。
     * Build system search form field.
     *
     * @param  int $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSystemSearchForm(int $queryID, string $actionURL, bool $cacheSearchFunc = true)
    {
        $searchConfig = $this->config->repo->system->search;
        if($cacheSearchFunc)
        {
            $this->repo->cacheSearchFunc($module, __METHOD__, func_get_args());
            return $searchConfig;
        }
        $this->config->repo->system->search['params']['product']['values'] = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $this->config->repo->system->search['queryID']   = (int)$queryID;
        $this->config->repo->system->search['actionURL'] = $actionURL;

        $this->loadModel('search')->setSearchParams($this->config->repo->system->search);
        return $searchConfig;
    }

    /**
     * 获取应用列表的搜索条件。
     * getSystemSearchQuery
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getSystemSearchQuery(int $queryID): string
    {
        $queryName = 'systemSearchQuery';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set('systemSearchForm', $query->form);
            }
        }
        if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');
        $systemQuery = $this->session->$queryName;
        $systemQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $systemQuery);
        if(strpos($systemQuery, 't1.`deployStatus`') !== false)
        {
            $systemQuery = str_replace('t1.`deployStatus`', 't3.`status`', $systemQuery);
            if(strpos($systemQuery, "t3.`status` = ''") !== false) $systemQuery = str_replace("t3.`status` = ''", "t3.`status` is null", $systemQuery);
        }

        return $systemQuery;
    }

    /**
     * 构造导入表单。
     * Build import form.
     *
     * @param  int $providerID
     * @param  string $groupID
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildImportForm(int $providerID, string $groupID, string $type)
    {
        $providers = $this->loadModel('provider')->getPairs($type);
        if((!$providerID && !empty($providers)) || !isset($providers[$providerID])) $providerID = empty($providers) ? 0 : key($providers);
        $provider = $this->provider->getByID($providerID);

        $repos   = !$provider ? array() : $this->repo->getProviderRepos($provider);
        $groupID = urldecode($groupID);
        $groups  = $groupRepos = array();
        foreach($repos as $repo)
        {
            if($type == 'GitLab')
            {
                if(empty($repo->namespace)) continue;
                $groups[$repo->namespace->name] = $repo->namespace->name;
            }
            elseIf(in_array($type, array('Gitea', 'Gogs')))
            {
                if(empty($repo->owner)) continue;
                $groups[$repo->owner->username] = $repo->owner->username;
            }
        }

        foreach($repos as $repo)
        {
            if($type == 'GitLab')
            {
                if((!$groupID && !empty($repos)) || !isset($groups[$groupID])) $groupID = current($repos)->namespace->name;
                if($groupID == $repo->namespace->name)
                {
                    $groupRepos[$repo->id] = $repo->name;
                }
            }
            elseIf(in_array($type, array('Gitea', 'Gogs')))
            {
                if((!$groupID && !empty($repos)) || !isset($groups[$groupID])) $groupID = current($repos)->owner->username;
                if($groupID == $repo->owner->username)
                {
                    $groupRepos[$repo->name] = $repo->name;
                }
            }
        }

        $this->view->providers = $providers;
        $this->view->provider  = $provider ? $provider : array();
        $this->view->groups    = $groups;
        $this->view->repos     = $groupRepos;
    }

    /**
     * 设置导入表单的配置。
     * Set import form config.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function setImportFormConfig(string $type, int $providerID = 0, string $acl = 'open'): array
    {
        if($type == 'Subversion')
        {
            $provider         = $this->loadModel('provider')->fetchByID($providerID);
            $isFileSubversion = !empty($provider->type) && $provider->type == 'Subversion' && strpos($provider->url, 'file://') === 0;
            $this->config->repo->form->import['account']['required']  = !$isFileSubversion;
            $this->config->repo->form->import['password']['required'] = !$isFileSubversion;
        }
        else
        {
            $this->config->repo->form->import['organize']['required'] = true;
            $this->config->repo->form->import['repo']['required']     = true;
        }

        if($acl == 'private') $this->config->repo->form->import['members']['required'] = true;

        return $this->config->repo->form->import;
    }
}
