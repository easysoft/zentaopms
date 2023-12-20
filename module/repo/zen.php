<?php
declare(strict_types=1);
/**
 * The zen file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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
            $formData->data->client = $_POST['client'] = $this->post->SCM == 'Subversion' ? 'svn' : 'git';
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

        $acl = $this->checkACL();
        if(!$acl) return false;
        $repo->acl = json_encode($acl);

        if($repo->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);
            $repo->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', str_replace('\\', '/', $repo->path)), '/');
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
     * 准备创建版本库的数据。
     * Prepare create repo data.
     *
     * @param  form      $formData
     * @param  bool      $isPipelineServer
     * @access protected
     * @return object|false
     */
    protected function prepareCreateRepo(form $formData, bool $isPipelineServer): object|false
    {
        $serviceHost = $_POST['serviceHost'];
        $namespace   = $_POST['namespace'];

        $group  = $this->repo->getGroups($serviceHost, $namespace);
        $server = $this->loadModel('pipeline')->getByID($serviceHost);

        $_POST['path']     = "{$server->url}/{$group}/{$_POST['name']}";
        $_POST['encoding'] = 'utf-8';
        $_POST['encrypt']  = 'plain';
        $_POST['SCM']      = $this->getSCM($serviceHost);

        if($this->config->inContainer || $this->config->inQuickon)
        {
            $formData->data->client = $_POST['client'] = $this->post->SCM == 'Subversion' ? 'svn' : 'git';
        }
        else
        {
            if(!$this->checkClient()) return false;
        }
        if(!$this->checkConnection()) return false;

        $repo = $formData
            ->setIf($isPipelineServer, 'password', $this->post->serviceToken)
            ->setIf($isPipelineServer, 'prefix', '')
            ->skipSpecial('path,client,account,password,desc')
            ->setDefault('path', $this->post->path)
            ->setDefault('encoding', $this->post->encoding)
            ->setDefault('encrypt', $this->post->encrypt)
            ->setDefault('SCM', $this->post->SCM)
            ->setDefault('product', '')->join('product', ',')
            ->setDefault('projects', '')->join('projects', ',')
            ->remove('namespace')
            ->get();

        $acl = $this->checkACL();
        if(!$acl) return false;
        $repo->acl = json_encode($acl);

        if($repo->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);
            $repo->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', str_replace('\\', '/', $repo->path)), '/');
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
     * 准备编辑版本库的数据。
     * Prepare edit repo data.
     *
     * @param  form      $formData
     * @param  object    $oldRepo
     * @param  bool      $isPipelineServer
     * @access protected
     * @return object|false
     */
    protected function prepareEdit(form $formData, object $oldRepo, bool $isPipelineServer): object|false
    {
        if($oldRepo->client != $this->post->client and !$this->checkClient()) return false;
        if(!$this->checkConnection()) return false;

        $repo = $formData
            ->setIf($isPipelineServer, 'password', $this->post->serviceToken)
            ->setDefault('client', 'svn')
            ->setIf($this->post->SCM == 'Gitlab', 'client', '')
            ->setIf($this->post->SCM == 'Gitlab', 'extra', $this->post->serviceProject)
            ->setIf($this->post->SCM == 'Gitlab', 'prefix', '')
            ->setDefault('product', '')
            ->skipSpecial('path,client,account,password,desc')
            ->join('product', ',')
            ->setDefault('projects', '')->join('projects', ',')
            ->get();

        if($repo->path != $oldRepo->path) $repo->synced = 0;

        $acl = $this->checkACL();
        if(!$acl) return false;
        $repo->acl = json_encode($acl);

        if($repo->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $info     = $scm->info('');
            $infoRoot = urldecode($info->root);
            $repo->prefix = empty($infoRoot) ? '' : trim(str_ireplace($infoRoot, '', str_replace('\\', '/', $repo->path)), '/');
            if($repo->prefix) $repo->prefix = '/' . $repo->prefix;
        }
        elseif($repo->SCM != $oldRepo->SCM and $repo->SCM == 'Git')
        {
            $repo->prefix = '';
        }

        if($isPipelineServer)
        {
            $serviceProject = $this->dao->select('*')->from(TABLE_REPO)
                ->where('`SCM`')->eq($repo->SCM)
                ->andWhere('`serviceHost`')->eq($repo->serviceHost)
                ->andWhere('`serviceProject`')->eq($repo->serviceProject)
                ->andWhere('id')->ne($oldRepo->id)
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
                dao::$errors['submit'] = $this->lang->repo->error->connect . "\n" . sprintf($this->lang->repo->error->output, $command, $result, implode("\n", $output));
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
    protected function buildCreateForm(int $objectID): void
    {
        $repoID = $this->repo->saveState(0, $objectID);

        $this->app->loadLang('action');

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $products = $this->loadModel('product')->getProductPairsByProject($objectID);
        }
        else
        {
            $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        }

        $this->view->title           = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->create;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->projects        = $this->loadModel('product')->getProjectPairsByProductIDList(array_keys($products));
        $this->view->relatedProjects = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? array($objectID) : array();
        $this->view->serviceHosts    = $this->loadModel('gitlab')->getPairs();
        $this->view->objectID        = $objectID;

        $this->display();
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
        $repoID = $this->repo->saveState(0, $objectID);

        $this->app->loadLang('action');

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $products = $this->loadModel('product')->getProductPairsByProject($objectID);
        }
        else
        {
            $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        }

        $serviceHosts = $this->loadModel('gitlab')->getPairs();
        $repoGroups   = array();

        if(!empty($serviceHosts))
        {
            $serverID   = array_keys($serviceHosts)[0];
            $repoGroups = $this->repo->getGroups($serverID);
            $server     = $this->loadModel('pipeline')->getByID($serverID);
        }

        $this->view->title           = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->create;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->projects        = $this->loadModel('product')->getProjectPairsByProductIDList(array_keys($products));
        $this->view->relatedProjects = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? array($objectID) : array();
        $this->view->serviceHosts    = $serviceHosts;
        $this->view->repoGroups      = $repoGroups;
        $this->view->objectID        = $objectID;
        $this->view->server          = $server;

        $this->display();
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
        $this->app->loadLang('action');

        $scm = strtolower($repo->SCM);
        if(in_array($scm, $this->config->repo->gitServiceList))
        {
            $serviceID = isset($repo->gitService) ? $repo->gitService : 0;
            $projects  = $this->loadModel($scm)->apiGetProjects($serviceID);
            $options   = array();
            foreach($projects as $project)
            {
                if($scm == 'gitlab') $options[$project->id] = $project->name_with_namespace;
                if($scm == 'gitea')  $options[$project->full_name] = $project->full_name;
                if($scm == 'gogs')   $options[$project->full_name] = $project->full_name;
            }

            $this->view->projects = $options;
        }

        $products           = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $linkedProducts     = $this->loadModel('product')->getByIdList(explode(',', $repo->product));
        $linkedProductPairs = array_combine(array_keys($linkedProducts), helper::arrayColumn($linkedProducts, 'name'));
        $products           = $products + $linkedProductPairs;

        $this->view->title           = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->edit;
        $this->view->repo            = $repo;
        $this->view->repoID          = $repoID;
        $this->view->objectID        = $objectID;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->relatedProjects = $this->repo->filterProject(explode(',', $repo->product), explode(',', $repo->projects));
        $this->view->serviceHosts    = $this->loadModel('pipeline')->getPairs($repo->SCM);

        $this->display();
    }

    /**
     * 准备批量创建版本库的数据。
     * Prepare batch create repo data.
     *
     * @access protected
     * @return array|false
     */
    protected function prepareBatchCreate(): array|false
    {
        if(!$this->post->serviceProject) return false;

        $this->app->loadLang('testcase');

        $data = array();
        foreach($this->post->serviceProject as $i => $project)
        {
            $products = array_filter($this->post->product[$i]);
            if(empty($products)) continue;
            if($this->post->name[$i] == '') dao::$errors['name_' . ($i -1)][] = sprintf($this->lang->error->notempty, $this->lang->repo->name);
            if(dao::isError()) continue;

            $data[] = array('serviceProject' => $project, 'product' => implode(',', $this->post->product[$i]), 'name' => $this->post->name[$i], 'projects' => empty($_POST['projects'][$i]) ? '' : implode(',', $this->post->projects[$i]));
        }
        if(dao::isError()) return false;

        return $data;
    }

    /**
     * 获取gitlab还没存在禅道的项目列表。
     * Get gitlab not exist repos.
     *
     * @param  object    $gitlab
     * @access protected
     * @return array
     */
    protected function getGitlabNotExistRepos(object $gitlab): array
    {
        $repoList = array();
        if(!empty($gitlab))
        {
            $repoList      = $this->loadModel('gitlab')->apiGetProjects($gitlab->id);
            $existRepoList = $this->dao->select('serviceProject,name')->from(TABLE_REPO)
                ->where('SCM')->eq(ucfirst($gitlab->type))
                ->andWhere('serviceHost')->eq($gitlab->id)
                ->fetchPairs();
            foreach($repoList as $key => $repo)
            {
                if(isset($existRepoList[$repo->id])) unset($repoList[$key]);
            }
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
     * @param  int       $refresh
     * @param  string    $revision
     * @param  object    $lastRevision
     * @param  string    $base64BranchID
     * @param  int       $objectID
     * @access protected
     * @return array
     */
    protected function getFilesInfo(object $repo, string $path, string $branchID, int $refresh, string $revision, object $lastRevision, string $base64BranchID, int $objectID): array
    {
        if($repo->SCM == 'Gitlab')
        {
            $cacheFile        = $this->repo->getCacheFile($repo->id, $path, $branchID);
            $cacheRefreshTime = isset($lastRevision->time) ? date('Y-m-d H:i', strtotime($lastRevision->time)) : date('Y-m-d H:i');
            $this->scm->setEngine($repo);
            if($refresh or !$cacheFile or !file_exists($cacheFile) or filemtime($cacheFile) < strtotime($cacheRefreshTime))
            {
                $infos = $this->repo->getFileList($repo, $branchID, $path);

                if($cacheFile && !empty($infos))
                {
                    if(!file_exists($cacheFile . '.lock'))
                    {
                        touch($cacheFile . '.lock');
                        file_put_contents($cacheFile, serialize($infos));
                        unlink($cacheFile . '.lock');
                    }
                }
            }
            else
            {
                $infos = unserialize(file_get_contents($cacheFile));
                if(empty($infos)) unlink($cacheFile);
            }
        }
        else
        {
            $infos = $this->repo->getFileCommits($repo, $branchID, $path);
        }

        $filePath = $path;
        if($repo->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $info = $scm->info('', $revision);
            if(!empty($info->root))
            {
                $prefixPath = str_replace($info->root . '/', '', $repo->path);
                $filePath   = trim(str_replace($prefixPath, '', $path), '/');
            }
        }

        foreach($infos as $info)
        {
            $info->originalComment = $info->comment;
            $info->comment         = $this->repo->replaceCommentLink($info->comment);
            if($repo->SCM != 'Subversion') $info->revision = substr($info->revision, 0, 10);

            $infoPath = trim(urldecode($path) . '/' . $info->name, '/');
            if($info->kind == 'dir')
            {
                $info->link = $this->repo->createLink('browse', "repoID={$repo->id}&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($infoPath));
            }
            else
            {
                if($repo->SCM == 'Subversion') $infoPath = $filePath . '/' . $info->name;
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
        if(!in_array($repo->SCM, $this->config->repo->gitTypeList)) return array();

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
     * 获取browse方法项目、分支、tags信息。
     * Get project、branches、tags info for browse method.
     *
     * @param  object    $repo
     * @access protected
     * @return array
     */
    protected function getBrowseInfo(object $repo): array
    {
        if($repo->SCM == 'Gitlab')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $urls['project']['url']  = $scm->engine->getApiUrl("project");
            $urls['branches']['url'] = $scm->engine->getApiUrl('branches');
            $urls['tags']['url']     = $scm->engine->getApiUrl('tags');

            $this->app->loadClass('requests', true);
            $result = requests::request_multiple($urls);

            if($result['project']->status_code == 200)
            {
                $project = json_decode($result['project']->body);
                if(!is_object($project)) $project = new stdclass();

                $this->loadModel('gitlab')->setProject((int)$repo->gitService, (int)$repo->project, $project);
            }
            if(!empty($result['branches']->headers) && !is_null($result['branches']->headers->offsetGet('x-total')))
            {
                $branchList = json_decode($result['branches']->body);
                $totalPages = $result['branches']->headers->offsetGet('x-total-pages');
                if($totalPages > 1)
                {
                    $requests = array();
                    for($page = 2; $page <= $totalPages; $page++)
                    {
                        $requests[$page]['url'] = str_replace('page=1', "page={$page}", $urls['branches']['url']);
                    }

                    $reponses = requests::request_multiple($requests, array('timeout' => 10));
                    foreach($reponses as $reponse)
                    {
                        $data = json_decode($reponse->body);
                        if(!is_array($data)) continue;
                        $branchList = array_merge($branchList, $data);
                    }
                }

                $branches = array();
                $default  = array();
                if(!empty($branchList) && is_array($branchList))
                {
                    foreach($branchList as $branch)
                    {
                        if(!isset($branch->name)) continue;
                        if($branch->default)
                        {
                            $default[$branch->name] = $branch->name;
                        }
                        else
                        {
                            $branches[$branch->name] = $branch->name;
                        }
                    }

                    if(empty($branches) and empty($default)) $branches['master'] = 'master';
                    asort($branches);
                    $branches = $default + $branches;
                }
            }
            if(!empty($result['branches']->headers) && !is_null($result['tags']->headers->offsetGet('x-total')))
            {
                $tagList    = json_decode($result['tags']->body);
                $totalPages = $result['tags']->headers->offsetGet('x-total-pages');
                if($totalPages > 1)
                {
                    $requests = array();
                    for($page = 2; $page <= $totalPages; $page++)
                    {
                        $requests[$page]['url'] = str_replace('page=1', "page={$page}", $urls['tags']['url']);
                    }

                    $reponses = requests::request_multiple($requests, array('timeout' => 10));
                    foreach($reponses as $reponse)
                    {
                        $data = json_decode($reponse->body);
                        if(!is_array($data)) continue;
                        $tagList = array_merge($tagList, $data);
                    }
                }

                $tags = array();
                if(!empty($tagList) && is_array($tagList))
                {
                    foreach($tagList as $tag) $tags[] = $tag->name;
                }
            }

            return array(isset($branches) ? $branches : false, isset($tags) ? $tags : false);
        }
    }

    /**
     * 为git类型版本库设置分支和tag。
     * Set branch or tag for git.
     *
     * @param  object     $repo
     * @param  string     $branchID
     * @param  array|bool $branchInfo
     * @param  array|bool $tagInfo
     * @access protected
     * @return array
     */
    protected function setBranchTag(object $repo, string $branchID, array|bool $branchInfo = false, array|bool $tagInfo = false): array
    {
        $repoID   = $repo->id;
        $branches = $tags = $branchesAndTags = array();
        if(in_array($repo->SCM, $this->config->repo->gitTypeList))
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $branches = isset($branchInfo) && $branchInfo !== false ? $branchInfo : $scm->branch();
            $initTags = isset($tagInfo) && $tagInfo !== false ? $tagInfo : $scm->tags('');
            foreach($initTags as $tag) $tags[$tag] = $tag;
            $branchesAndTags = $branches + $tags;

            if(empty($branchID) and $this->cookie->repoBranch and $this->session->repoID == $repoID) $branchID = $this->cookie->repoBranch;
            if($branchID) $this->repo->setRepoBranch($branchID);
            if(!isset($branchesAndTags[$branchID]))
            {
                $branchID = (string)key($branches);
                $this->repo->setRepoBranch($branchID);
            }

            return array($branchID, $branches, $tags, $branchesAndTags);
        }
        else
        {
            $this->repo->setRepoBranch('');
            return array('', array(), array(), array());
        }
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
            $item->revision = ($repo->SCM != 'Subversion' && $item->revision) ? substr($item->revision, 0, 10) : $item->revision;
        }

        return $revisions;
    }

    /**
     * 设置session信息。
     * Set session.
     *
     * @access protected
     * @return void
     */
    protected function setBrowseSession(): void
    {
        $this->repo->setBackSession('list', true);

        session_start();
        $this->session->set('revisionList', $this->app->getURI(true));
        $this->session->set('gitlabBranchList', $this->app->getURI(true));
        session_write_close();
    }

    /**
     * 构建版本库搜索框。
     * Build repo search form.
     *
     * @param  array     $products
     * @param  array     $projects
     * @param  int       $objectID
     * @param  string    $orderBy
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function buildRepoSearchForm(array $products, array $projects, int $objectID, string $orderBy, int $recPerPage, int $pageID, int $param): void
    {
        session_start();
        $this->config->repo->search['params']['product']['values']  = $products;
        $this->config->repo->search['params']['projects']['values'] = $projects;
        $this->config->repo->search['actionURL']   = $this->createLink('repo', 'maintain', "objectID={$objectID}&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}&type=bySearch&param=myQueryID");
        $this->config->repo->search['queryID']     = $param;
        $this->config->repo->search['onMenuBar']   = 'yes';
        $this->loadModel('search')->setSearchParams($this->config->repo->search);
        session_write_close();
    }

    /**
     * 构建需求搜索表格。
     * Build story search form.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  object    $product
     * @param  array     $modules
     * @access protected
     * @return void
     */
    protected function buildStorySearchForm(int $repoID, string $revision, string $browseType, int $queryID, object $product, array $modules): void
    {
        unset($this->lang->story->statusList['closed']);
        $storyStatusList = $this->lang->story->statusList;

        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL']                   = $this->createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['style']                       = 'simple';
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->product->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }

        session_start();
        $this->loadModel('search')->setSearchParams($this->config->product->search);
        session_write_close();
    }

    /**
     * 获取关联需求列表。
     * Get link stories list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  object    $product
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access protected
     * @return array
     */
    protected function getLinkStories(int $repoID, string $revision, string $browseType, object $product, string $orderBy, object $pager, int $queryID): array
    {
        $linkedStories = $this->repo->getRelationByCommit($repoID, $revision, 'story');
        if($browseType == 'bySearch')
        {
            $allStories = $this->loadModel('story')->getBySearch($product->id, 0, $queryID, $orderBy, 0, 'story', array_keys($linkedStories), '', $pager);
        }
        else
        {
            $allStories = $this->loadModel('story')->getProductStories($product->id, 0, '0', 'draft,active,changed', 'story', $orderBy, false, array_keys($linkedStories), $pager);
        }

        return $allStories;
    }

    /**
     * 构建bug搜索表格。
     * Build bug search form.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  object    $product
     * @param  array     $modules
     * @access protected
     * @return void
     */
    protected function buildBugSearchForm(int $repoID, string $revision, string $browseType, int $queryID, object $product, array $modules): void
    {

        $this->config->bug->search['actionURL']                         = $this->createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['queryID']                           = $queryID;
        $this->config->bug->search['style']                             = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($product->id);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs(array($product->id), 'all', '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->loadModel('build')->getBuildPairs(array($product->id), 'all', '');

        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['params']['product']);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }
        session_start();
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
        session_write_close();
    }

    /**
     * 获取关联bug列表。
     * Get link bugs list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  object    $product
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access protected
     * @return array
     */
    protected function getLinkBugs(int $repoID, string $revision, string $browseType, object $product, string $orderBy, object $pager, int $queryID): array
    {
        $linkedBugs = $this->repo->getRelationByCommit($repoID, $revision, 'bug');
        if($browseType == 'bySearch')
        {
            $allBugs = $this->loadModel('bug')->getBySearch($product->id, 0, $queryID, $orderBy, array_keys($linkedBugs), $pager);
        }
        else
        {
            $allBugs = $this->loadModel('bug')->getActiveBugs($product->id, 0, '0', array_keys($linkedBugs), $pager, $orderBy);
        }

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
     * @param  object    $product
     * @param  array     $modules
     * @param  array     $productExecutions
     * @access protected
     * @return void
     */
    protected function buildTaskSearchForm(int $repoID, string $revision, string $browseType, int $queryID, object $product, array $modules, array $productExecutions): void
    {
        $this->config->execution->search['actionURL']                     = $this->createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID", '', true);
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['style']                         = 'simple';
        $this->config->execution->search['params']['module']['values']    = $modules;
        $this->config->execution->search['params']['execution']['values'] = $this->loadModel('product')->getExecutionPairsByProduct($product->id);
        $this->config->execution->search['params']['execution']['values'] = array_filter($productExecutions);

        session_start();
        $this->loadModel('search')->setSearchParams($this->config->execution->search);
        session_write_close();
    }

    /**
     * 获取关联任务列表。
     * Get link tasks list.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @param  array     $productExecutionIDs
     * @access protected
     * @return array
     */
    protected function getLinkTasks(int $repoID, string $revision, string $browseType, string $orderBy, object $pager, int $queryID, array $productExecutionIDs): array
    {
        $allTasks = array();
        if($browseType == 'bysearch')
        {
            $allTasks = $this->loadModel('execution')->getTasks(0, 0, array(), $browseType, $queryID, 0, $orderBy, null);
        }
        else
        {
            foreach($productExecutionIDs as $productExecutionID)
            {
                $tasks    = $this->loadModel('execution')->getTasks(0, $productExecutionID, array(), $browseType, $queryID, 0, $orderBy, null);
                $allTasks = array_merge($tasks, $allTasks);
            }
        }

        /* Filter linked tasks. */
        $linkedTasks   = $this->repo->getRelationByCommit($repoID, $revision, 'task');
        $linkedTaskIDs = array_keys($linkedTasks);
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        /* Page the records. */
        $pager->setRecTotal(count($allTasks));
        $pager->setPageTotal();
        if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);
        $count    = 1;
        $limitMin = ($pager->pageID - 1) * $pager->recPerPage;
        $limitMax = $pager->pageID * $pager->recPerPage;
        foreach($allTasks as $key => $task)
        {
            if($count <= $limitMin or $count > $limitMax) unset($allTasks[$key]);
            $count ++;
        }

        return $allTasks;
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
     * Get SCM by service host.
     *
     * @param  int    $serviceHost
     * @access protected
     * @return string
     */
    protected function getSCM(int|string $serviceHost)
    {
        $server = $this->loadModel('pipeline')->getByID($serviceHost);

        foreach($this->lang->repo->scmList as $scmKey => $scmLang)
        {
            if($server->type == strtolower($scmKey)) return $scmKey;
        }

        return '';
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
        $relationID = $this->dao->select('id')->from(TABLE_RELATION)
            ->where('extra')->eq($repoID)
            ->andWhere('AType')->eq('design')
            ->fetch();
        $error = $relationID ? $this->lang->repo->error->deleted : '';

        $jobs = $this->dao->select('*')->from(TABLE_JOB)->where('repo')->eq($repoID)->andWhere('deleted')->eq('0')->fetchAll();
        if($jobs) $error .= ($error ? '\n' : '') . $this->lang->repo->error->linkedJob;

        return $error;
    }

    /**
     * 获取详情页面目录树。
     * Get view tree.
     *
     * @param  object    $repo
     * @param  string    $entry
     * @param  string    $revision
     * @access protected
     * @return array
     */
    protected function getViewTree(object $repo, string $entry, string $revision): array
    {
        if($repo->SCM == 'Gitlab') return $this->repo->getGitlabFilesByPath($repo, '', (string)$this->cookie->repoBranch);

        if($repo->SCM != 'Subversion') return $this->repo->getFileTree($repo);

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $tree = $scm->ls($entry, (string)$revision);
        foreach($tree as &$file)
        {
            $base64Name = base64_encode($file->path);

            $file->path = trim($file->path, '/');
            if(!isset($file->id))    $file->id    = $base64Name;
            if(!isset($file->key))   $file->key   = $base64Name;
            if(!isset($file->text))  $file->text  = trim($file->name, '/');
            if($file->kind == 'dir') $file->items = array('url' => helper::createLink('repo', 'ajaxGetFiles', "repoID={$repo->id}&branch={$revision}&path=" . helper::safe64Encode($file->path)));
        }

        return $tree;
    }
}
