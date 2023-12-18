<?php
declare(strict_types=1);
/**
 * The model file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     gogs
 * @link        https://www.zentao.net
 */

class gogsModel extends model
{
    /**
     * 获取请求地址信息。
     * Get gogs api base url by gogs id.
     *
     * @param  int    $gogsID
     * @access public
     * @return string
     */
    public function getApiRoot(int $gogsID): string
    {
        $gogs = $this->fetchByID($gogsID);
        if(!$gogs) return '';

        return rtrim($gogs->url, '/') . '/api/v1%s' . "?token={$gogs->token}";
    }

    /**
     * Gogs用户和禅道用户绑定。
     * Bind gogs user and zentao user.
     *
     * @param  int    $gogsID
     * @param  array  $users
     * @param  array  $gogsNames
     * @access public
     * @return array
     */
    public function bindUser(int $gogsID, array $users, array $gogsNames): bool
    {
        $accountList = array();
        $repeatUsers = array();
        foreach($users as $openID => $user)
        {
            if(empty($user)) continue;

            if(isset($accountList[$user])) $repeatUsers[] = zget($userPairs, $user);
            $accountList[$user] = $openID;
        }

        $userList = $this->loadModel('user')->getRealNameAndEmails($repeatUsers);
        if(count($repeatUsers))
        {
            dao::$errors[] = sprintf($this->lang->gogs->bindUserError, join(',', helper::arrayColumn($userList, 'realname')));
            return false;
        }

        $user = new stdclass();
        $user->providerID   = $gogsID;
        $user->providerType = 'gogs';

        $oldUsers = $this->dao->select('*')->from(TABLE_OAUTH)->where('providerType')->eq($user->providerType)->andWhere('providerID')->eq($user->providerID)->fetchAll('openID');
        foreach($users as $openID => $account)
        {
            $existAccount = isset($oldUsers[$openID]) ? $oldUsers[$openID] : '';
            /* If user binded user is change, delete it. */
            if($existAccount and $existAccount->account != $account)
            {
                $this->dao->delete()
                    ->from(TABLE_OAUTH)
                    ->where('openID')->eq($openID)
                    ->andWhere('providerType')->eq($user->providerType)
                    ->andWhere('providerID')->eq($user->providerID)
                    ->exec();
                $this->loadModel('action')->create('gogsuser', $gogsID, 'unbind', '', sprintf($this->lang->gogs->bindDynamic, $gogsNames[$openID], $zentaoUsers[$existAccount->account]->realname));
            }

            /* Add zentao user and gitea user binded. */
            if(!$existAccount or $existAccount->account != $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;
                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
                $this->loadModel('action')->create('gogsuser', $gogsID, 'bind', '', sprintf($this->lang->gogs->bindDynamic, $gogsNames[$openID], $zentaoUsers[$account]->realname));
            }
        }
        return !dao::isError();
    }

    /**
     * Api error handling.
     *
     * @param  object $response
     * @access public
     * @return bool
     */
    public function apiErrorHandling(object $response): bool
    {
        if(!empty($response->error))
        {
            dao::$errors[] = $response->error;
            return false;
        }

        if(empty($response->message))
        {
            dao::$errors[] = false;
            return false;
        }

        if(is_string($response->message))
        {
            $errorKey = array_search($response->message, $this->lang->gogs->apiError);
            dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gogs->errorLang, $errorKey);
            return false;
        }

        foreach($response->message as $field => $fieldErrors)
        {
            if(is_string($fieldErrors))
            {
                $errorKey = array_search($fieldErrors, $this->lang->gogs->apiError);
                if($fieldErrors) dao::$errors[$field][] = $errorKey === false ? $fieldErrors : zget($this->lang->gogs->errorLang, $errorKey);
            }
            else
            {
                foreach($fieldErrors as $error)
                {
                    $errorKey = array_search($error, $this->lang->gogs->apiError);
                    if($error) dao::$errors[$field][] = $errorKey === false ? $error : zget($this->lang->gogs->errorLang, $errorKey);
                }
            }
        }
        return false;
    }

    /**
     * 检测token是否有效。
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return void
     */
    public function checkTokenAccess(string $url = '', string $token = ''): bool
    {
        $apiRoot = rtrim($url, '/') . '/api/v1%s' . "?token={$token}";
        $url     = sprintf($apiRoot, "/user");
        $user    = json_decode(commonModel::http($url));
        if(empty($user)) return false;

        /* Check whether the token belongs to the administrator by edit user. */
        $editUserUrl = sprintf($apiRoot, "/admin/users/" . $user->username);
        $data        = new stdclass();
        $data->login_name = $user->login;
        $data->email      = $user->email;

        $result = commonModel::http($editUserUrl, $data, array(), array(), 'data', 'PATCH');
        $user   = json_decode($result);
        if(empty($user)) return false;

        return true;
    }

    /**
     * 通过API获取Gogs项目信息。
     * Get project by api.
     *
     * @param  int    $gogsID
     * @param  string $projectID
     * @access public
     * @return object|false
     */
    public function apiGetSingleProject(int $gogsID, string $projectID): object|false
    {
        $apiRoot = $this->getApiRoot($gogsID);
        if(!$apiRoot) return false;

        $url     = sprintf($apiRoot, "/repos/$projectID");
        $project = json_decode(commonModel::http($url));
        if(isset($project->name))
        {
            $project->name_with_namespace = $project->full_name;
            $project->path_with_namespace = $project->full_name;
            $project->http_url_to_repo    = $project->html_url;
            $project->name_with_namespace = $project->full_name;

            $gogs = $this->fetchByID($gogsID);
            $project->tokenCloneUrl = preg_replace('/(http(s)?:\/\/)/', '${1}' . $gogs->token . '@', $project->html_url);
        }

        return $project;
    }

    /**
     * 通过API获取Gogs项目列表。
     * Get projects by api.
     *
     * @param  int    $gogsID
     * @access public
     * @return array
     */
    public function apiGetProjects(int $gogsID): array
    {
        $apiRoot = $this->getApiRoot($gogsID);
        if(!$apiRoot) return array();

        $user = $this->apiGetAdminer($gogsID);
        if(!$user) return array();

        $url      = sprintf($apiRoot, "/users/{$user->username}/repos");
        $projects = array();
        $page     = 1;
        while(true)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;

            if(!empty($results)) $projects = array_merge($projects, $results);
            if(count($results) < 50) break;
            $page ++;
        }

        return $projects;
    }

    /**
     * 判断当前用户是否是Gogs管理员。
     * Api get adminer.
     *
     * @param  int    $gogsID
     * @access public
     * @return object|null
     */
    public function apiGetAdminer(int $gogsID): object|null
    {
        $apiRoot = $this->getApiRoot($gogsID);
        if(!$apiRoot) return array();

        $url  = sprintf($apiRoot, "/user");
        $user = json_decode(commonModel::http($url));

        return isset($user->username) ? $user : null;
    }

    /**
     * Get gogs user list.
     *
     * @param  int    $gogsID
     * @param  bool   $onlyLinked
     * @access public
     * @return array
     */
    public function apiGetUsers($gogsID, $onlyLinked = false)
    {
        $users   = array();
        $apiRoot = $this->getApiRoot($gogsID);
        $page    = 1;
        while(true)
        {
            $url    = sprintf($apiRoot, "/admin/users") . "&page={$page}&limit=20";
            $result = json_decode(commonModel::http($url));
            if(empty($result->data)) break;

            $users = array_merge($users, $result->data);
            $page ++;
        }

        if(empty($users)) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs', 'openID,account');

        $userList = array();
        foreach($users as $gogsUser)
        {
            if($onlyLinked and !isset($linkedUsers[$gogsUser->id])) continue;

            $user = new stdclass;
            $user->id             = $gogsUser->id;
            $user->realname       = $gogsUser->full_name ? $gogsUser->full_name : $gogsUser->username;
            $user->account        = $gogsUser->username;
            $user->email          = zget($gogsUser, 'email', '');
            $user->avatar         = $gogsUser->avatar_url;
            $user->createdAt      = zget($gogsUser, 'created', '');
            $user->lastActivityOn = zget($gogsUser, 'login', '');

            $userList[] = $user;
        }

        return $userList;
    }

    /**
     * 通过API获取Gogs项目分支列表。
     * Get project repository branches by api.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return array
     */
    public function apiGetBranches(int $gogsID, string $project): array
    {
        $url      = sprintf($this->getApiRoot($gogsID), "/repos/{$project}/branches");
        $branches = array();
        $page     = 1;
        while(true)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;

            if(!empty($results)) $branches = array_merge($branches, $results);
            if(count($results) < 100) break;

            $page ++;
        }

        return $branches;
    }

    /**
     * 通过API获取Gogs项目分支信息。
     * Get single branch by API.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $branchName
     * @access public
     * @return object
     */
    public function apiGetSingleBranch(int $gogsID, string $project, string $branchName): object
    {
        $url    = sprintf($this->getApiRoot($gogsID), "/repos/$project/branches/$branchName");
        $branch = json_decode(commonModel::http($url));
        if($branch)
        {
            $gogs = $this->fetchByID($gogsID);
            $branch->web_url = "{$gogs->url}/$project/src/$branchName";
        }

        return $branch;
    }

    /**
     * 通过API获取Gogs项目分支保护信息。
     * Get protect branches of one project.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function apiGetBranchPrivs(int $gogsID, string $project, string $keyword = ''): array
    {
        $keyword  = urlencode($keyword);
        $url      = sprintf($this->getApiRoot($gogsID), "/repos/$project/branch_protections");
        $branches = json_decode(commonModel::http($url));
        if(!is_array($branches)) return array();

        $newBranches = array();
        foreach($branches as $branch)
        {
            $branch->name = $branch->Name;
            if(empty($keyword) || stristr($branch->name, $keyword)) $newBranches[] = $branch;
        }

        return $newBranches;
    }

    /**
     * 通过API删除Gogs分支。
     * Api delete branch.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $branch
     * @access public
     * @return void
     */
    public function apiDeleteBranch(int $gogsID, string $project, string $branch): object
    {
        $url = sprintf($this->getApiRoot($gogsID), "/repos/$project/branches/$branch");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }
}
