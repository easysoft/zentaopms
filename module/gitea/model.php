<?php
/**
 * The model file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class giteaModel extends model
{

    const HOOK_PUSH_EVENT = 'Push Hook';

    /* Gitlab access level. */
    public $noAccess         = 0;
    public $developerAccess  = 30;
    public $maintainerAccess = 40;

    /**
     * Get a gitea by id.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * Get gitea list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $giteaList = $this->loadModel('pipeline')->getList('gitea', $orderBy, $pager);

        return $giteaList;
    }

    /**
     * Get gitea pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('gitea');
    }

    /**
     * Get gitea api base url by gitea id.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot($giteaID, $sudo = true)
    {
        $gitea = $this->getByID($giteaID);
        if(!$gitea) return '';

        $sudoParam = '';
        if($sudo == true and !$this->app->user->admin)
        {
            $openID = $this->getUserIDByZentaoAccount($giteaID, $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gitea->url, '/') . '/api/v1%s' . "?token={$gitea->token}" . $sudoParam;
    }

    /**
     * Update a gitea.
     *
     * @param  int $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        return $this->loadModel('pipeline')->update($id);
    }

    /**
     * Bind users.
     *
     * @param  int    $giteaID
     * @access public
     * @return array
     */
    public function bindUser($giteaID)
    {
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $users       = $this->post->zentaoUsers;
        $giteaNames  = $this->post->giteaUserNames;
        $accountList = array();
        $repeatUsers = array();
        foreach($users as $openID => $user)
        {
            if(empty($user)) continue;
            if(isset($accountList[$user])) $repeatUsers[] = zget($userPairs, $user);
            $accountList[$user] = $openID;
        }

        if(count($repeatUsers))
        {
            dao::$errors = sprintf($this->lang->gitea->bindUserError, join(',', $repeatUsers));
            return false;
        }

        $user = new stdclass;
        $user->providerID   = $giteaID;
        $user->providerType = 'gitea';

        $oldUsers = $this->dao->select('*')->from(TABLE_OAUTH)->where('providerType')->eq($user->providerType)->andWhere('providerID')->eq($user->providerID)->fetchAll('openID');
        foreach($users as $openID => $account)
        {
            $existAccount = isset($oldUsers[$openID]) ? $oldUsers[$openID] : '';

            if($existAccount and $existAccount->account != $account)
            {
                $this->dao->delete()
                    ->from(TABLE_OAUTH)
                    ->where('openID')->eq($openID)
                    ->andWhere('providerType')->eq($user->providerType)
                    ->andWhere('providerID')->eq($user->providerID)
                    ->exec();
                $this->loadModel('action')->create('giteauser', $giteaID, 'unbind', '', sprintf($this->lang->gitea->bindDynamic, $giteaNames[$openID], $zentaoUsers[$existAccount->account]->realname));
            }
            if(!$existAccount or $existAccount->account != $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;
                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
                $this->loadModel('action')->create('giteauser', $giteaID, 'bind', '', sprintf($this->lang->gitea->bindDynamic, $giteaNames[$openID], $zentaoUsers[$account]->realname));
            }
        }
    }

    /**
     * Api error handling.
     *
     * @param  object $response
     * @access public
     * @return bool
     */
    public function apiErrorHandling($response)
    {
        if(!empty($response->error))
        {
            dao::$errors[] = $response->error;
            return false;
        }
        if(!empty($response->message))
        {
            if(is_string($response->message))
            {
                $this->parseApiError($response->message);
            }
            else
            {
                foreach($response->message as $field => $fieldErrors)
                {
                    if(is_string($fieldErrors))
                    {
                        $this->parseApiError($response->message);
                    }
                    else
                    {
                        foreach($fieldErrors as $error)
                        {
                            $this->parseApiError($response->message);
                        }
                    }
                }
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * 解析api返回的错误信息。
     *
     * @param  string $message
     * @access public
     * @return void
     */
    public function parseApiError($message)
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
     * Check user access.
     *
     * @param  int    $giteaID
     * @param  int    $projectID
     * @param  object $project
     * @param  string $maxRole
     * @access public
     * @return bool
     */
    public function checkUserAccess($giteaID, $projectID = 0, $project = null, $groupIDList = array(), $maxRole = 'maintainer')
    {
        if($this->app->user->admin) return true;

        if($project == null) $project = $this->apiGetSingleProject($giteaID, $projectID);
        if(!isset($project->id)) return false;

        $accessLevel = $this->config->gitea->accessLevel[$maxRole];

        if(isset($project->permissions->project_access->access_level) and $project->permissions->project_access->access_level >= $accessLevel) return true;
        if(isset($project->permissions->group_access->access_level) and $project->permissions->group_access->access_level >= $accessLevel) return true;
        if(!empty($project->shared_with_groups))
        {
            if(empty($groupIDList))
            {
                $groups = $this->apiGetGroups($giteaID, 'name_asc', $maxRole);
                foreach($groups as $group) $groupIDList[] = $group->id;
            }

            foreach($project->shared_with_groups as $group)
            {
                if($group->group_access_level < $accessLevel) continue;
                if(in_array($group->group_id, $groupIDList)) return true;
            }
        }

        return false;
    }

    /**
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return void
     */
    public function checkTokenAccess($url = '', $token = '')
    {
        $apiRoot  = rtrim($url, '/') . '/api/v1%s' . "?token={$token}";
        $url      = sprintf($apiRoot, "/admin/users") . "&limit=1";
        $response = commonModel::http($url);
        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;
        return true;
    }

    /**
     * Get Gitea id list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getListByAccount($account = '')
    {
        if(!$account) $account = $this->app->user->account;

        return $this->dao->select('providerID,openID')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitea')
            ->andWhere('account')->eq($account)
            ->fetchPairs('providerID');
    }

    /**
     * Get zentao account gitea user id pairs of one gitea.
     *
     * @param  int $giteaID
     * @access public
     * @return array
     */
    public function getUserAccountIdPairs($giteaID, $fields = 'account,openID')
    {
        return $this->dao->select($fields)->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitea')
            ->andWhere('providerID')->eq($giteaID)
            ->fetchPairs();
    }

    /**
     * Get gitea user id by zentao account.
     *
     * @param  int    $giteaID
     * @param  string $zentaoAccount
     * @access public
     * @return array
     */
    public function getUserIDByZentaoAccount($giteaID, $zentaoAccount)
    {
        return $this->dao->select('openID')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitea')
            ->andWhere('providerID')->eq($giteaID)
            ->andWhere('account')->eq($zentaoAccount)
            ->fetch('openID');
    }

    /**
     * Get matched gitea users.
     *
     * @param  int   $giteaID
     * @param  array $giteaUsers
     * @param  array $zentaoUsers
     * @access public
     * @return array
     */
    public function getMatchedUsers($giteaID, $giteaUsers, $zentaoUsers)
    {
        $matches = new stdclass;
        foreach($giteaUsers as $giteaUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($giteaUser->account == $zentaoUser->account)   $matches->accounts[$giteaUser->account][] = $zentaoUser->account;
                if($giteaUser->realname == $zentaoUser->realname) $matches->names[$giteaUser->realname][]   = $zentaoUser->account;
                if($giteaUser->email == $zentaoUser->email)       $matches->emails[$giteaUser->email][]     = $zentaoUser->account;
            }
        }

        $bindedUsers  = $this->getUserAccountIdPairs($giteaID, 'openID,account');
        $matchedUsers = array();
        foreach($giteaUsers as $giteaUser)
        {
            if(isset($bindedUsers[$giteaUser->id]))
            {
                $giteaUser->zentaoAccount     = $bindedUsers[$giteaUser->id];
                $matchedUsers[$giteaUser->id] = $giteaUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$giteaUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$giteaUser->account]);
            if(isset($matches->emails[$giteaUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$giteaUser->email]);
            if(isset($matches->names[$giteaUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$giteaUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $giteaUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$giteaUser->id] = $giteaUser;
            }
        }

        return $matchedUsers;
    }

    /**
     * Get project by api.
     *
     * @param  int    $giteaID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function apiGetSingleProject($giteaID, $projectID)
    {
        $apiRoot = $this->getApiRoot($giteaID);
        if(!$apiRoot) return array();

        $url     = sprintf($apiRoot, "/repos/$projectID");
        $project = json_decode(commonModel::http($url));
        if(isset($project->name))
        {
            $project->name_with_namespace = $project->full_name;
            $project->path_with_namespace = $project->full_name;
            $project->http_url_to_repo    = $project->html_url;
            $project->name_with_namespace = $project->full_name;

            $gitea = $this->getByID($giteaID);
            $oauth = "oauth2:{$gitea->token}@";
            $project->tokenCloneUrl = preg_replace('/(http(s)?:\/\/)/', "\$1$oauth", $project->html_url);
            $project->tokenCloneUrl = str_replace(array('https://', 'http://'), strstr($url, ':', true) . '://', $project->tokenCloneUrl);
        }

        return $project;
    }

    /**
     * Get projects by api.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetProjects($giteaID, $sudo = true)
    {
        $apiRoot = $this->getApiRoot($giteaID, $sudo);
        if(!$apiRoot) return array();

        $url        = sprintf($apiRoot, "/repos/search");
        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(empty($results) || empty($results->data) || !is_array($results->data)) break;
            $allResults = array_merge($allResults, $results->data);
            if(count($results->data) < 50) break;
        }

        return $allResults;
    }

    /**
     * Get groups by api.
     *
     * @param  int    $giteaID
     * @param  int    $sudo
     * @access public
     * @return array
     */
    public function apiGetGroups($giteaID, $sudo = true)
    {
         $apiRoot = $this->getApiRoot($giteaID, $sudo);
         if(!$apiRoot) return array();

         $url        = sprintf($apiRoot, "/orgs");
         $allResults = array();
         for($page = 1; true; $page++)
         {
             $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
             if(empty($results)) break;
             $allResults = array_merge($allResults, $results);
             if(count($results) < 50) break;
         }
         return $allResults;
    }

    /**
     * Get gitea user list.
     *
     * @param  int    $giteaID
     * @param  bool   $onlyLinked
     * @access public
     * @return array
     */
    public function apiGetUsers($giteaID, $onlyLinked = false)
    {
        $response = array();
        $apiRoot  = $this->getApiRoot($giteaID);

        for($page = 1; true; $page++)
        {
            $url    = sprintf($apiRoot, "/users/search") . "&page={$page}&limit=50";
            $result = json_decode(commonModel::http($url));
            if(empty($result->data)) break;

            $response = array_merge($response, $result->data);
            $page += 1;
        }

        if(empty($response)) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->getUserAccountIdPairs($giteaID, 'openID,account');

        $users = array();
        foreach($response as $giteaUser)
        {
            if($onlyLinked and !isset($linkedUsers[$giteaUser->id])) continue;

            $user = new stdclass;
            $user->id             = $giteaUser->id;
            $user->realname       = $giteaUser->full_name ? $giteaUser->full_name : $giteaUser->username;
            $user->account        = $giteaUser->username;
            $user->email          = zget($giteaUser, 'email', '');
            $user->avatar         = $giteaUser->avatar_url;
            $user->createdAt      = zget($giteaUser, 'created', '');
            $user->lastActivityOn = zget($giteaUser, 'last_login', '');

            $users[] = $user;
        }

        return $users;
    }

    /**
     * Get project repository branches by api.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return object
     */
    public function apiGetBranches($giteaID, $project, $pager = null)
    {
        $url = sprintf($this->getApiRoot($giteaID), "/repos/{$project}/branches");
        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * Get Forks of a project by API.
     *
     * @param  int    $giteaID
     * @param  string $projectID
     * @access public
     * @return object
     */
    public function apiGetForks($giteaID, $projectID)
    {
        $url = sprintf($this->getApiRoot($giteaID), "/repos/$projectID/forks");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get upstream project by API.
     *
     * @param  int    $giteaID
     * @param  string $projectID
     * @access public
     * @return void
     */
    public function apiGetUpstream($giteaID, $projectID)
    {
        $currentProject = $this->apiGetSingleProject($giteaID, $projectID);
        if(isset($currentProject->parent->full_name)) return $currentProject->parent->full_name;
        return array();
    }

    /**
     * Get branches.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return array
     */
    public function getBranches($giteaID, $project)
    {
        $rawBranches = $this->apiGetBranches($giteaID, $project);

        $branches = array();
        foreach($rawBranches as $branch) $branches[] = $branch->name;

        return $branches;
    }

    /**
     * Get gitea user id and realname pairs of one gitea.
     *
     * @param  int $giteaID
     * @access public
     * @return array
     */
    public function getUserIdRealnamePairs($giteaID)
    {
        return $this->dao->select('oauth.openID as openID,user.realname as realname')
            ->from(TABLE_OAUTH)->alias('oauth')
            ->leftJoin(TABLE_USER)->alias('user')
            ->on("oauth.account = user.account")
            ->where('providerType')->eq('gitea')
            ->andWhere('providerID')->eq($giteaID)
            ->fetchPairs();
    }

    /**
     * Get single branch by API.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @param  string $branchName
     * @access public
     * @return object
     */
    public function apiGetSingleBranch($giteaID, $project, $branchName)
    {
        $url    = sprintf($this->getApiRoot($giteaID), "/repos/$project/branches/$branchName");
        $branch = json_decode(commonModel::http($url));
        if($branch)
        {
            $gitea = $this->getByID($giteaID);
            $branch->web_url = "{$gitea->url}/$project/src/branch/$branchName";
        }

        return $branch;
    }

    /**
     * Get protect branches of one project.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function apiGetBranchPrivs($giteaID, $project, $keyword = '')
    {
        $keyword  = urlencode($keyword);
        $url      = sprintf($this->getApiRoot($giteaID), "/repos/$project/branch_protections");
        $branches = json_decode(commonModel::http($url));

        if(!is_array($branches)) return $branches;

        $newBranches = array();
        foreach($branches as $branch)
        {
            $branch->name = $branch->branch_name;
            if(empty($keyword) || stristr($branch->name, $keyword)) $newBranches[] = $branch;
        }

        return $newBranches;
    }

    /**
     * 创建代码库。
     *
     * @param  int    $giteaID
     * @param  string $name
     * @param  string $org
     * @param  string $desc
     * @access public
     * @return void
     */
    public function apiCreateRepository($giteaID, $name, $org, $desc)
    {
        $data = new stdclass();
        $data->name        = $name;
        $data->description = $desc;
        $data->auto_init   = true;
        $data->template    = false;

        $url    = sprintf($this->getApiRoot($giteaID), "/orgs/{$org}/repos");
        $result = json_decode(commonModel::http($url, $data));

        return $result;
    }
}
