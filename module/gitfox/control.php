<?php
declare(strict_types=1);
/**
 * The control file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfox extends control
{
    /**
     * 创建一个gitfox。
     * Create a gitfox.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gitfox = form::data($this->config->gitfox->form->create)
                ->add('createdBy', $this->app->user->account)
                ->get();
            $this->checkToken($gitfox);
            $gitfoxID = $this->loadModel('pipeline')->create($gitfox);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action');
            $this->action->create('gitfox', $gitfoxID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitfox->common . $this->lang->hyphen . $this->lang->gitfox->lblCreate;

        $this->display();
    }

    /**
     * 编辑gitfox。
     * Edit a gitfox.
     *
     * @param  int    $gitfoxID
     * @access public
     * @return void
     */
    public function edit(int $gitfoxID)
    {
        $oldGitFox = $this->gitfox->fetchByID($gitfoxID);

        if($_POST)
        {
            $gitfox = form::data($this->config->gitfox->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->get();
            $this->checkToken($gitfox, $gitfoxID);
            $this->loadModel('pipeline')->update($gitfoxID, $gitfox);
            $gitFox = $this->gitfox->fetchByID($gitfoxID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitfox', $gitfoxID, 'edited');
            $changes  = common::createChanges($oldGitFox, $gitFox);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'loadCurrentPage()', 'closeModal' => true));
        }

        $this->view->title  = $this->lang->gitfox->common . $this->lang->hyphen . $this->lang->gitfox->edit;
        $this->view->gitfox = $oldGitFox;

        $this->display();
    }

    /**
     * 删除一条gitfox记录。
     * Delete a gitfox.
     *
     * @param  int    $gitfoxID
     * @access public
     * @return void
     */
    public function delete(int $gitfoxID)
    {
        $oldGitFox = $this->gitfox->fetchByID($gitfoxID);
        $actionID  = $this->loadModel('pipeline')->deleteByObject($gitfoxID, 'gitfox');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);

            return $this->send($response);
        }

        $gitFox   = $this->gitfox->fetchByID($gitfoxID);
        $changes  = common::createChanges($oldGitFox, $gitFox);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';
        return $this->send($response);
    }

    /**
     * 检查post的token是否有管理员权限。
     * Check post token has admin permissions.
     *
     * @param  object    $gitfox
     * @param  int       $gitfoxID
     * @access protected
     * @return void
     */
    protected function checkToken(object $gitfox, int $gitfoxID = 0)
    {
        $this->dao->update('gitfox')->data($gitfox)->batchCheck($gitfoxID ? $this->config->gitfox->edit->requiredFields : $this->config->gitfox->create->requiredFields, 'notempty');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(strpos($gitfox->url, 'http') !== 0) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitfox->serverFail))));
        if(!$gitfox->token) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitfox->tokenError))));

        $user = $this->gitfox->checkTokenAccess($gitfox->url, $gitfox->token);

        if(is_bool($user)) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitfox->serverFail))));
        if(!isset($user[0]->uid)) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitfox->tokenError))));
    }

    /**
     * Bind gitfox user to zentao users.
     *
     * @param  int     $gitfoxID
     * @param  string  $type
     * @access public
     * @return void
     */
    public function bindUser(int $gitfoxID, string $type = 'all')
    {
        $userPairs = $this->loadModel('user')->getPairs('noclosed|noletter');

        $user = $this->gitfox->apiGetCurrentUser($gitfoxID);
        if(empty($user->admin)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->gitfox->tokenLimit, 'modal' => $this->createLink('gitfox', 'edit', array('gitfoxID' => $gitfoxID)))));

        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');

        if($_POST)
        {
            $users       = $this->post->zentaoUsers;
            $gitfoxNames = $this->post->gitfoxUserNames;

            $result = $this->gitfoxZen->checkUserRepeat($users, $userPairs);
            if($result['result'] != 'success') return $this->send($result);

            $this->gitfoxZen->bindUsers($gitfoxID, $users, $gitfoxNames, $zentaoUsers);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $gitfoxUsers   = $this->gitfox->apiGetUsers($gitfoxID);
        $bindedUsers   = $this->loadModel('pipeline')->getUserBindedPairs($gitfoxID, 'gitfox', 'account,openID');
        $matchedResult = $this->gitfox->getMatchedUsers($gitfoxID, $gitfoxUsers, $zentaoUsers);

        foreach($gitfoxUsers as $gitfoxUser)
        {
            $user = new stdclass();
            $user->email            = '';
            $user->status           = 'notBind';
            $user->gitfoxID         = $gitfoxUser->id;
            $user->gitfoxEmail      = $gitfoxUser->email;
            $user->gitfoxUser       = $gitfoxUser->realname . '@' . $gitfoxUser->account;

            $user->zentaoUsers = isset($matchedResult[$gitfoxUser->id]) ? $matchedResult[$gitfoxUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $gitfoxUser->id)
                {
                    $user->status = 'binded';
                    if(!isset($bindedUsers[$user->zentaoUsers])) $user->status = 'bindedError';
                }
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gitfox->bindUser;
        $this->view->type        = $type;
        $this->view->gitfoxID    = $gitfoxID;
        $this->view->recTotal    = count($userList);
        $this->view->userList    = $userList;
        $this->view->userPairs   = $userPairs;
        $this->view->zentaoUsers = $zentaoUsers;
        $this->display();
    }

    /**
     * Ajax方式获取项目分支。
     * AJAX: Get project branches.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches(int $repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) return print(array());

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $branches = $scm->branch();

        $options = array();
        $options[] = array('text' => '', 'value' => '');
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch, 'value' => $branch);
        }
        return print(json_encode($options));
    }

    /**
     * AJAX方式获取项目的制品包。
     * AJAX: Get project artifacts.
     *
     * @param  int    $gitfoxID
     * @access public
     * @return void
     */
    public function ajaxGetArtifacts(int $gitfoxID)
    {
        $gitfox = $this->gitfox->fetchByID($gitfoxID);
        $files  = $this->gitfox->apiGetArtifacts($gitfoxID, $this->post->project, $this->post->format, $this->post->package, $this->post->version, (string)$this->post->group);

        foreach($files as $key => $file) $files[$key]->editTime = empty($file->updated) ? '' : date('Y-m-d H:i:s', intVal($file->updated / 1000));
        return $this->send(array('files' => $files, 'type' => $this->post->format, 'serverUrl' => $gitfox->url));
    }

    /**
     * DevOps 介绍页面。
     * DevOps introduction page.
     *
     * @access public
     * @param  int $isInstall
     * @return void
     */
    public function devopsIntroduction(int $isInstall = 0)
    {
        $adminRegisterLink = $this->app->cookie->lang == 'zh-cn' ? helper::createLink('admin', 'register') : helper::createLink('index');
        $devopsLink        = helper::createLink('gitfox', 'installGitFox', 'isInstall=' . $isInstall);

        if($isInstall)
        {
            $adminRegisterLink .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
            $devopsLink        .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
        }

        $this->view->adminRegisterLink = $adminRegisterLink;
        $this->view->title             = $this->lang->gitfox->devopsIntroduction;
        $this->view->devopsLink        = $devopsLink;
        $this->view->isInstall         = $isInstall;
        $this->display();
    }

    /**
     * 安装GitFox.
     * Install GitFox.
     *
     * @access public
     * @param  int $isInstall
     * @return void
     */
    public function installGitFox(int $isInstall = 0)
    {
        $nextLink = $this->app->cookie->lang == 'zh-cn' ? helper::createLink('admin', 'register') : helper::createLink('index');

        if($isInstall)
        {
            $nextLink .= $this->config->requestType == 'GET' ? '&_single=1' : '?_single=1';
        }
        else
        {
            list($devopsModule, $devopsMethod) = explode('-', $this->config->devopsLink);
            $nextLink = helper::createLink($devopsModule, $devopsMethod);
        }

        if(strpos(PHP_OS, 'WIN'))
        {
            $os = 'win';
        }
        elseif (PHP_OS === 'Linux')
        {
            $os = 'linux';
        }
        elseif (PHP_OS === 'Darwin')
        {
            $os = 'mac';
        }
        else
        {
            $os = 'linux';
        }

        $uname = php_uname('m');
        $arch  = strtolower($uname);
        if(strpos($arch, 'arm') === 0 || strpos($arch, 'aarch') != false)
        {
            $arch = 'arm';
        }
        elseif(strpos($arch, 'x86') === 0 || strpos($arch, 'i686') != false || strpos($arch, 'amd') != false)
        {
            $arch = 'amd';
        }
        else
        {
            $arch = 'amd';
        }

        $gitfoxDir = $this->app->getAppRoot() . 'gitfox';

        $type        = $os == 'mac' ? 'linux' : $os;
        $downloadURL = $this->config->gitfox->downloadGitfoxURL[$type][$arch];
        $command     = sprintf($this->config->gitfox->installGitfox[$type], $gitfoxDir, $downloadURL);
        $script      = $type == 'linux' ? $this->app->getTmpRoot() . 'installgitfox.sh' : $this->app->getTmpRoot() . 'installgitfox.bat';
        file_put_contents($script, $command);
        if(file_exists($script)) chmod($script, 0755);

        $this->view->title     = $this->lang->gitfox->installGitFox;
        $this->view->script    = $script;
        $this->view->nextLink  = $nextLink;
        $this->view->isInstall = $isInstall;
        $this->display();
    }

    /**
     * 检查 GitFox 服务器是否可用。
     * Check if GitFox server is available.
     *
     * @access public
     * @return void
     */
    public function ajaxCheckGitFoxHealth()
    {
        $result = $this->gitfox->checkHealth();
        if(!$result || dao::isError()) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitfox->serverFail));

        return $this->send(array('result' => 'success'));
    }
}
