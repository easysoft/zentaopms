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
            $gitfox = form::data($this->config->gitfox->form->create)->get();
            $this->checkToken($gitfox);
            $gitfoxID = $this->loadModel('pipeline')->create($gitfox);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action');
            $this->action->create('gitfox', $gitfoxID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitfox->common . $this->lang->colon . $this->lang->gitfox->lblCreate;

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
        $oldGitFox = $this->gitfox->getByID($gitfoxID);

        if($_POST)
        {
            $gitfox = form::data($this->config->gitfox->form->edit)->get();
            $this->checkToken($gitfox, $gitfoxID);
            $this->loadModel('pipeline')->update($gitfoxID, $gitfox);
            $gitFox = $this->gitfox->getByID($gitfoxID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitfox', $gitfoxID, 'edited');
            $changes  = common::createChanges($oldGitFox, $gitFox);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title  = $this->lang->gitfox->common . $this->lang->colon . $this->lang->gitfox->edit;
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
        $oldGitFox = $this->loadModel('pipeline')->getByID($gitfoxID);
        $actionID  = $this->pipeline->deleteByObject($gitfoxID, 'gitfox');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);

            return $this->send($response);
        }

        $gitFox   = $this->gitfox->getByID($gitfoxID);
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

        $user   = $this->gitfox->apiGetCurrentUser($gitfoxID);
        if(!isset($user->admin) or !$user->admin) return $this->sendError($this->lang->gitfox->tokenLimit, $this->createLink('gitfox', 'edit', array('gitfoxID' => $gitfoxID)));

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
        $options[] = array('text' => '', 'value' => '');;
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch, 'value' => $branch);
        }
        return print(json_encode($options));
    }
}
