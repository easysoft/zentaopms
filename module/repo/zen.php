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
        return $repo;
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
        $this->commonAction($repoID, $objectID);

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
     * @access protected
     * @return array
     */
    protected function getFilesInfo(object $repo, string $path, string $branchID, int $refresh, string $revision, object $lastRevision): array
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

        foreach($infos as $info)
        {
            $info->originalComment = $info->comment;
            $info->comment         = $this->repo->replaceCommentLink($info->comment);
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
            $branchMenus[]  = array('text' => $branchName, 'id' => $branchName, 'keys' => zget(common::convert2Pinyin(array($branchName)), $branchName, ''), 'url' => 'javascript:;');
        }
        foreach($tags as $tagName)
        {
            $selected = ($tagName == $branchID) ? $tagName : $selected;
            $tagMenus[]  = array('text' => $tagName, 'id' => $tagName, 'keys' => zget(common::convert2Pinyin(array($tagName)), $tagName, ''), 'url' => 'javascript:;');
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
            if(!is_null($result['branches']->headers->offsetGet('x-total')))
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
            if(!is_null($result['tags']->headers->offsetGet('x-total')))
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
}

