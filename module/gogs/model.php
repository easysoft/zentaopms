<?php
/**
 * The model file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class gogsModel extends model
{

    const HOOK_PUSH_EVENT = 'Push Hook';

    /* Gitlab access level. */
    public $noAccess         = 0;
    public $developerAccess  = 30;
    public $maintainerAccess = 40;

    /**
     * Get a gogs by id.
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
     * Get gogs list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $gogsList = $this->loadModel('pipeline')->getList('gogs', $orderBy, $pager);

        return $gogsList;
    }

    /**
     * Get gogs pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('gogs');
    }

    /**
     * Get gogs api base url by gogs id.
     *
     * @param  int    $gogsID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot($gogsID, $sudo = true)
    {
        $gogs = $this->getByID($gogsID);
        if(!$gogs) return '';

        $sudoParam = '';
        if($sudo == true and !$this->app->user->admin)
        {
            $openID = $this->getUserIDByZentaoAccount($gogsID, $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gogs->url, '/') . '/api/v1%s' . "?token={$gogs->token}" . $sudoParam;
    }

    /**
     * Create a gogs.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        return $this->loadModel('pipeline')->create('gogs');
    }

    /**
     * Update a gogs.
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
     * @param  int    $gogsID
     * @access public
     * @return array
     */
    public function bindUser($gogsID)
    {
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $users       = $this->post->zentaoUsers;
        $gogsNames   = $this->post->gogsUserNames;
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
            dao::$errors[] = sprintf($this->lang->gogs->bindUserError, join(',', $repeatUsers));
            return false;
        }

        $user = new stdclass;
        $user->providerID   = $gogsID;
        $user->providerType = 'gogs';

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
                $this->loadModel('action')->create('gogsuser', $gogsID, 'unbind', '', sprintf($this->lang->gogs->bindDynamic, $gogsNames[$openID], $zentaoUsers[$existAccount->account]->realname));
            }
            if(!$existAccount or $existAccount->account != $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;
                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
                $this->loadModel('action')->create('gogsuser', $gogsID, 'bind', '', sprintf($this->lang->gogs->bindDynamic, $gogsNames[$openID], $zentaoUsers[$account]->realname));
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
                $errorKey = array_search($response->message, $this->lang->gogs->apiError);
                dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gogs->errorLang, $errorKey);
            }
            else
            {
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
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * Check user access.
     *
     * @param  int    $gogsID
     * @param  int    $projectID
     * @param  object $project
     * @param  string $maxRole
     * @access public
     * @return bool
     */
    public function checkUserAccess($gogsID, $projectID = 0, $project = null, $groupIDList = array(), $maxRole = 'maintainer')
    {
        if($this->app->user->admin) return true;

        if($project == null) $project = $this->apiGetSingleProject($gogsID, $projectID);
        if(!isset($project->id)) return false;

        $accessLevel = $this->config->gogs->accessLevel[$maxRole];

        if(isset($project->permissions->project_access->access_level) and $project->permissions->project_access->access_level >= $accessLevel) return true;
        if(isset($project->permissions->group_access->access_level) and $project->permissions->group_access->access_level >= $accessLevel) return true;
        if(!empty($project->shared_with_groups))
        {
            if(empty($groupIDList))
            {
                $groups = $this->apiGetGroups($gogsID, 'name_asc', $maxRole);
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
        $url      = sprintf($apiRoot, "/user");
        $httpData = commonModel::httpWithHeader($url);
        $user     = json_decode($httpData['body']);
        if(empty($user)) return false;
        if(isset($users->message) or isset($users->error)) return null;
        return true;
    }

    /**
     * Get Gogs id list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getGogsListByAccount($account = '')
    {
        if(!$account) $account = $this->app->user->account;

        return $this->dao->select('providerID,openID')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gogs')
            ->andWhere('account')->eq($account)
            ->fetchPairs('providerID');
    }

    /**
     * Get zentao account gogs user id pairs of one gogs.
     *
     * @param  int $gogsID
     * @access public
     * @return array
     */
    public function getUserAccountIdPairs($gogsID, $fields = 'account,openID')
    {
        return $this->dao->select($fields)->from(TABLE_OAUTH)
            ->where('providerType')->eq('gogs')
            ->andWhere('providerID')->eq($gogsID)
            ->fetchPairs();
    }

    /**
     * Get gogs user id by zentao account.
     *
     * @param  int    $gogsID
     * @param  string $zentaoAccount
     * @access public
     * @return array
     */
    public function getUserIDByZentaoAccount($gogsID, $zentaoAccount)
    {
        return $this->dao->select('openID')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gogs')
            ->andWhere('providerID')->eq($gogsID)
            ->andWhere('account')->eq($zentaoAccount)
            ->fetch('openID');
    }

    /**
     * Get matched gogs users.
     *
     * @param  int   $gogsID
     * @param  array $gogsUsers
     * @param  array $zentaoUsers
     * @access public
     * @return array
     */
    public function getMatchedUsers($gogsID, $gogsUsers, $zentaoUsers)
    {
        $matches = new stdclass;
        foreach($gogsUsers as $gogsUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($gogsUser->account == $zentaoUser->account)   $matches->accounts[$gogsUser->account][] = $zentaoUser->account;
                if($gogsUser->realname == $zentaoUser->realname) $matches->names[$gogsUser->realname][]   = $zentaoUser->account;
                if($gogsUser->email == $zentaoUser->email)       $matches->emails[$gogsUser->email][]     = $zentaoUser->account;
            }
        }

        $bindedUsers  = $this->getUserAccountIdPairs($gogsID, 'openID,account');
        $matchedUsers = array();
        foreach($gogsUsers as $gogsUser)
        {
            if(isset($bindedUsers[$gogsUser->account]))
            {
                $gogsUser->zentaoAccount = $bindedUsers[$gogsUser->account];
                $matchedUsers[]          = $gogsUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$gogsUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gogsUser->account]);
            if(isset($matches->emails[$gogsUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gogsUser->email]);
            if(isset($matches->names[$gogsUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gogsUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gogsUser->zentaoAccount = current($matchedZentaoUsers);
                $matchedUsers[]          = $gogsUser;
            }
        }

        return $matchedUsers;
    }

    /**
     * Get project by api.
     *
     * @param  int    $gogsID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function apiGetSingleProject($gogsID, $projectID)
    {
        $apiRoot = $this->getApiRoot($gogsID);
        if(!$apiRoot) return array();

        $url     = sprintf($apiRoot, "/repos/$projectID");
        $project = json_decode(commonModel::http($url));
        if(isset($project->name))
        {
            $project->name_with_namespace = $project->full_name;
            $project->path_with_namespace = $project->full_name;
            $project->http_url_to_repo    = $project->html_url;
            $project->name_with_namespace = $project->full_name;

            $gogs  = $this->getByID($gogsID);
            $oauth = "oauth2:{$gogs->token}@";
            $project->tokenCloneUrl = preg_replace('/(http(s)?:\/\/)/', "\$1$oauth", $project->html_url);
        }

        return $project;
    }

    /**
     * Get projects by api.
     *
     * @param  int    $gogsID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetProjects($gogsID, $sudo = true)
    {
        $apiRoot = $this->getApiRoot($gogsID, $sudo);
        if(!$apiRoot) return array();

        $url        = sprintf($apiRoot, "/repos/search");
        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results->data)) break;
            if(!empty($results->data)) $allResults = array_merge($allResults, $results->data);
            if(count($results->data) < 50) break;
        }

        return $allResults;
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
        $response = array();
        $apiRoot  = $this->getApiRoot($gogsID);

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
        if($onlyLinked) $linkedUsers = $this->getUserAccountIdPairs($gogsID, 'openID,account');

        $users = array();
        foreach($response as $gogsUser)
        {
            if($onlyLinked and !isset($linkedUsers[$gogsUser->id])) continue;

            $user = new stdclass;
            $user->id             = $gogsUser->id;
            $user->realname       = $gogsUser->full_name ? $gogsUser->full_name : $gogsUser->username;
            $user->account        = $gogsUser->username;
            $user->email          = zget($gogsUser, 'email', '');
            $user->avatar         = $gogsUser->avatar_url;
            $user->createdAt      = zget($gogsUser, 'created', '');
            $user->lastActivityOn = zget($gogsUser, 'last_login', '');

            $users[] = $user;
        }

        return $users;
    }

    /**
     * Get project repository branches by api.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return object
     */
    public function apiGetBranches($gogsID, $project, $pager = null)
    {
        $url = sprintf($this->getApiRoot($gogsID), "/repos/{$project}/branches");
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
     * @param  int    $gogsID
     * @param  string $projectID
     * @access public
     * @return object
     */
    public function apiGetForks($gogsID, $projectID)
    {
        $url = sprintf($this->getApiRoot($gogsID), "/repos/$projectID/forks");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get upstream project by API.
     *
     * @param  int    $gogsID
     * @param  string $projectID
     * @access public
     * @return void
     */
    public function apiGetUpstream($gogsID, $projectID)
    {
        $currentProject = $this->apiGetSingleProject($gogsID, $projectID);
        if(isset($currentProject->parent->full_name)) return $currentProject->parent->full_name;
        return array();
    }

    /**
     * Get branches.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return array
     */
    public function getBranches($gogsID, $project)
    {
        $rawBranches = $this->apiGetBranches($gogsID, $project);

        $branches = array();
        foreach($rawBranches as $branch) $branches[] = $branch->name;

        return $branches;
    }

    /**
     * Get gogs user id and realname pairs of one gogs.
     *
     * @param  int $gogsID
     * @access public
     * @return array
     */
    public function getUserIdRealnamePairs($gogsID)
    {
        return $this->dao->select('oauth.openID as openID,user.realname as realname')
            ->from(TABLE_OAUTH)->alias('oauth')
            ->leftJoin(TABLE_USER)->alias('user')
            ->on("oauth.account = user.account")
            ->where('providerType')->eq('gogs')
            ->andWhere('providerID')->eq($gogsID)
            ->fetchPairs();
    }

    /**
     * Get single branch by API.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $branchName
     * @access public
     * @return object
     */
    public function apiGetSingleBranch($gogsID, $project, $branchName)
    {
        $url    = sprintf($this->getApiRoot($gogsID), "/repos/$project/branches/$branchName");
        $branch = json_decode(commonModel::http($url));
        if($branch)
        {
            $gogs = $this->getByID($gogsID);
            $branch->web_url = "{$gogs->url}/$project/src/branch/$branchName";
        }

        return $branch;
    }

    /**
     * Get protect branches of one project.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function apiGetBranchPrivs($gogsID, $project, $keyword = '')
    {
        $keyword  = urlencode($keyword);
        $url      = sprintf($this->getApiRoot($gogsID), "/repos/$project/branch_protections");
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
}
