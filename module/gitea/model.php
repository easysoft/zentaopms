<?php
declare(strict_types=1);
/**
 * The model file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     gitea
 * @link        https://www.zentao.net
 */

class giteaModel extends model
{
    const HOOK_PUSH_EVENT = 'Push Hook';

    /* Gitlab access level. */
    public $noAccess         = 0;
    public $developerAccess  = 30;
    public $maintainerAccess = 40;

    /**
     * 获取Gitea请求地址信息。
     * Get gitea api base url by gitea id.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot(int $giteaID, bool $sudo = true): string
    {
        $gitea = $this->fetchByID($giteaID);
        if(!$gitea || $gitea->type != 'gitea') return '';

        $sudoParam = '';
        if($sudo && !$this->app->user->admin)
        {
            $openID = $this->loadModel('pipeline')->getOpenIdByAccount($giteaID, 'gitea', $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gitea->url, '/') . '/api/v1%s' . "?token={$gitea->token}" . $sudoParam;
    }

    /**
     * Gitea用户和禅道用户绑定。
     * Bind gitea user and zentao user.
     *
     * @param  int    $giteaID
     * @param  array  $users
     * @param  array  $giteaNames
     * @access public
     * @return bool
     */
    public function bindUser(int $giteaID, array $users, array $giteaNames): bool
    {
        $repeatUsers = array();
        $userPairs   = array();
        foreach($users as $openID => $account)
        {
            if(empty($account)) continue;

            if(in_array($account, $userPairs)) $repeatUsers[] = $account;
            $userPairs[$openID] = $account;
        }

        /* Check user repeat bind. */
        if($repeatUsers)
        {
            $userList    = $this->loadModel('user')->getRealNameAndEmails($repeatUsers);
            dao::$errors = sprintf($this->lang->gitea->bindUserError, join(',', helper::arrayColumn($userList, 'realname')));
            return false;
        }

        $bindedUsers = $this->dao->select('openID,account')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitea')
            ->andWhere('providerID')->eq($giteaID)
            ->fetchPairs();
        $this->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq('gitea')->andWhere('providerID')->eq($giteaID)->exec();

        $this->loadModel('action');
        foreach($userPairs as $openID => $account)
        {
            /* If user binded user is change, delete it. */
            if(isset($bindedUsers[$openID]) && $bindedUsers[$openID] != $account) $this->action->create('giteauser', $giteaID, 'unbind', '', $giteaNames[$openID]);

            /* Add zentao user and gitea user binded. */
            $user = new stdclass();
            $user->providerID   = $giteaID;
            $user->providerType = 'gitea';
            $user->account      = $account;
            $user->openID       = $giteaNames[$openID];
            $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
            $this->action->create('giteauser', $giteaID, 'bind', '', $giteaNames[$openID]);
        }
        return !dao::isError();
    }

    /**
     * 解析翻译接口返回的错误信息。
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
            dao::$errors[] = 'error';
            return false;
        }

        $errorMsg = array();
        if(is_string($response->message)) $errorMsg[] = $response->message;
        if(is_array($response->message))
        {
            foreach($response->message as $fieldErrors)
            {
                if(is_string($fieldErrors)) $fieldErrors = array($fieldErrors);
                foreach($fieldErrors as $error) $errorMsg[] = $error;
            }
        }
        foreach($errorMsg as $error) $this->parseApiError($error);

        return false;
    }

    /**
     * 解析api返回的错误信息。
     * Parse api error.
     *
     * @param  string $message
     * @access public
     * @return void
     */
    public function parseApiError(string $message)
    {
        $errorKey = array_search($message, $this->lang->gitea->apiError);
        if($errorKey === false)
        {
            dao::$errors[] = $message;
        }
        else
        {
            $field = $this->lang->gitea->errorKey[$errorKey];
            dao::$errors[$field] = zget($this->lang->gitea->errorLang, $errorKey);
        }
    }

    /**
     * 检测token是否有效。
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return bool
     */
    public function checkTokenAccess(string $url = '', string $token = ''): bool
    {
        $apiRoot = rtrim($url, '/') . '/api/v1%s' . "?token={$token}";
        $url     = sprintf($apiRoot, "/admin/users") . "&limit=1";

        $users = json_decode(commonModel::http($url));
        if(empty($users)) return false;
        if(isset($users->message) || isset($users->error)) return false;
        return true;
    }

    /**
     * 通过API获取Gitea项目信息。
     * Get project by api.
     *
     * @param  int    $giteaID
     * @param  string $projectID
     * @access public
     * @return object|null
     */
    public function apiGetSingleProject(int $giteaID, string $projectID): object|null
    {
        $apiRoot = $this->getApiRoot($giteaID);
        if(!$apiRoot) return null;

        $url     = sprintf($apiRoot, "/repos/$projectID");
        $project = json_decode(commonModel::http($url));
        if(isset($project->name))
        {
            $project->name_with_namespace = $project->full_name;
            $project->path_with_namespace = $project->full_name;
            $project->http_url_to_repo    = $project->html_url;
            $project->name_with_namespace = $project->full_name;

            $gitea = $this->fetchByID($giteaID);
            $oauth = "oauth2:{$gitea->token}@";
            $project->tokenCloneUrl = preg_replace('/(http(s)?:\/\/)/', "\$1$oauth", $project->html_url);
            $project->tokenCloneUrl = str_replace(array('https://', 'http://'), strstr($url, ':', true) . '://', $project->tokenCloneUrl);
        }

        return $project;
    }

    /**
     * 通过API获取Gitea项目列表。
     * Get projects by api.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetProjects(int $giteaID, bool $sudo = true): array
    {
        $apiRoot = $this->getApiRoot($giteaID, $sudo);
        if(!$apiRoot) return array();

        $url      = sprintf($apiRoot, "/repos/search");
        $page     = 1;
        $projects = array();
        while(true)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(empty($results->data) || !is_array($results->data)) break;

            $projects = array_merge($projects, $results->data);
            if(count($results->data) < 50) break;

            $page ++;
        }

        return $projects;
    }

    /**
     * 通过api获取组列表。
     * Get groups by api.
     *
     * @param  int    $giteaID
     * @param  int    $sudo
     * @access public
     * @return array
     */
    public function apiGetGroups(int $giteaID, bool $sudo = true): array
    {
         $apiRoot = $this->getApiRoot($giteaID, $sudo);
         if(!$apiRoot) return array();

         $url        = sprintf($apiRoot, "/orgs");
         $allResults = array();
         for($page = 1; true; $page++)
         {
             $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
             if(empty($results) || isset($results->message)) break;

             $allResults = array_merge($allResults, $results);
             if(count($results) < 50) break;
         }
         return $allResults;
    }

    /**
     * 通过API获取Gitea用户列表。
     * Get gitea user list.
     *
     * @param  int    $giteaID
     * @param  bool   $onlyLinked
     * @access public
     * @return array
     */
    public function apiGetUsers(int $giteaID, bool $onlyLinked = false): array
    {
        $apiRoot  = $this->getApiRoot($giteaID, strtolower($this->app->rawMethod) != 'binduser');
        if(empty($apiRoot)) return array();

        $page  = 1;
        $users = array();
        while(true)
        {
            $url    = sprintf($apiRoot, "/users/search") . "&page={$page}&limit=50";
            $result = json_decode(commonModel::http($url));
            if(empty($result->data)) break;

            $users = array_merge($users, $result->data);
            $page ++;
        }
        if(empty($users)) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->loadModel('pipeline')->getUserBindedPairs($giteaID, 'gitea', 'openID,account');

        $userList = array();
        foreach($users as $giteaUser)
        {
            if($onlyLinked && !isset($linkedUsers[$giteaUser->username])) continue;

            $user = new stdclass();
            $user->id             = $giteaUser->id;
            $user->realname       = $giteaUser->full_name ? $giteaUser->full_name : $giteaUser->username;
            $user->account        = $giteaUser->username;
            $user->email          = zget($giteaUser, 'email', '');
            $user->avatar         = $giteaUser->avatar_url;
            $user->createdAt      = zget($giteaUser, 'created', '');
            $user->lastActivityOn = zget($giteaUser, 'last_login', '');

            $userList[] = $user;
        }
        return $userList;
    }

    /**
     * 通过API获取Gitea项目分支列表。
     * Get project repository branches by api.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return array
     */
    public function apiGetBranches(int $giteaID, string $project): array
    {
        $url      = sprintf($this->getApiRoot($giteaID), "/repos/{$project}/branches");
        $branches = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;

            if(!empty($results)) $branches = array_merge($branches, $results);
            if(count($results) < 100) break;
        }

        return $branches;
    }

    /**
     * 通过API获取Gitea项目分支信息。
     * Get single branch by API.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @param  string $branchName
     * @access public
     * @return object|null
     */
    public function apiGetSingleBranch(int $giteaID, string $project, string $branchName): object|null
    {
        if(empty($branchName)) return null;

        $url    = sprintf($this->getApiRoot($giteaID), "/repos/$project/branches/$branchName");
        $branch = json_decode(commonModel::http($url));
        if(isset($branch->name))
        {
            $gitea = $this->fetchByID($giteaID);
            $branch->web_url = "{$gitea->url}/$project/src/branch/$branchName";
        }

        return $branch;
    }

    /**
     * 通过API获取Gitea项目分支保护信息。
     * Get protect branches of one project.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @param  string $keyword
     * @access public
     * @return array|null
     */
    public function apiGetBranchPrivs(int $giteaID, string $project, string $keyword = ''): array|null
    {
        $url      = sprintf($this->getApiRoot($giteaID), "/repos/$project/branch_protections");
        $branches = json_decode(commonModel::http($url));
        if(!is_array($branches)) return array();

        $keyword     = urlencode($keyword);
        $newBranches = array();
        foreach($branches as $branch)
        {
            $branch->name = $branch->branch_name;
            if(empty($keyword) || stristr($branch->name, $keyword)) $newBranches[] = $branch;
        }

        return $newBranches;
    }

    /**
     * API获取项目的合并请求列表。
     * Get Merge Requests by API.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return array
     */
    public function apiGetMergeRequests(int $giteaID,  string $project): array
    {
        $apiRoot  = $this->getApiRoot($giteaID, false);
        $apiPath  = "/repos/{$project}/pulls";
        $url      = sprintf($apiRoot, $apiPath);

        $mrList = json_decode(common::http($url));
        foreach($mrList as $mr)
        {
            $mr->web_url = $mr->url;
            $mr->iid     = $mr->number;
            $mr->state   = $mr->state == 'open' ? 'opened' : $mr->state;
            if($mr->merged) $mr->state = 'merged';

            $mr->merge_status      = $mr->mergeable ? 'can_be_merged' : 'cannot_be_merged';
            $mr->description       = $mr->body;
            $mr->target_branch     = $mr->base->ref;
            $mr->source_branch     = $mr->head->ref;
            $mr->source_project_id = $project;
            $mr->target_project_id = $project;
            $mr->has_conflicts     = empty($diff) ? true : false;
            $mr->is_draft          = strpos($mr->title, 'Draft:') === 0;
        }

        return $mrList;
    }
}
