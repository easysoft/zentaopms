<?php
/**
 * The model file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        https://www.zentao.net
 */

class gogsModel extends model
{
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
     * @access public
     * @return string
     */
    public function getApiRoot($gogsID)
    {
        $gogs = $this->getByID($gogsID);
        if(!$gogs) return '';

        return rtrim($gogs->url, '/') . '/api/v1%s' . "?token={$gogs->token}";
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
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return void
     */
    public function checkTokenAccess($url = '', $token = '')
    {
        $apiRoot = rtrim($url, '/') . '/api/v1%s' . "?token={$token}";
        $url     = sprintf($apiRoot, "/user");
        $user    = json_decode(commonModel::http($url));
        if(empty($user)) return null;

        /* Check whether the token belongs to the administrator by edit user. */
        $editUserUrl = sprintf($apiRoot, "/admin/users/" . $user->username);
        $data        = new stdclass();
        $data->login_name = $user->login;
        $data->email      = $user->email;

        $result = commonModel::http($editUserUrl, $data, array(), array(), 'data', 'PATCH');
        $user   = json_decode($result);
        if(empty($user)) return null;

        return true;
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

        $bindedUsers  = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs', 'openID,account');
        $matchedUsers = array();
        foreach($gogsUsers as $gogsUser)
        {
            if(isset($bindedUsers[$gogsUser->id]))
            {
                $gogsUser->zentaoAccount     = $bindedUsers[$gogsUser->id];
                $matchedUsers[$gogsUser->id] = $gogsUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$gogsUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gogsUser->account]);
            if(isset($matches->emails[$gogsUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gogsUser->email]);
            if(isset($matches->names[$gogsUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gogsUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gogsUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$gogsUser->id] = $gogsUser;
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
            $oauth = "{$gogs->token}@";
            $project->tokenCloneUrl = preg_replace('/(http(s)?:\/\/)/', '${1}' . $gogs->token . '@', $project->html_url);
        }

        return $project;
    }

    /**
     * Get projects by api.
     *
     * @param  int    $gogsID
     * @access public
     * @return array
     */
    public function apiGetProjects($gogsID)
    {
        $apiRoot = $this->getApiRoot($gogsID);
        if(!$apiRoot) return array();

        $user = $this->apiGetAdminer($gogsID);
        if(!$user) return array();

        $url        = sprintf($apiRoot, "/users/{$user->username}/repos");
        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&page={$page}&limit=50"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 50) break;
        }

        return $allResults;
    }

    /**
     * Api get adminer.
     *
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function apiGetAdminer($gogsID)
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
        $response = array();
        $apiRoot  = $this->getApiRoot($gogsID);

        for($page = 1; true; $page++)
        {
            $url    = sprintf($apiRoot, "/admin/users") . "&page={$page}&limit=20";
            $result = json_decode(commonModel::http($url));
            if(empty($result->data)) break;
            $response = array_merge($response, $result->data);
            $page += 1;
        }

        if(empty($response)) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs', 'openID,account');

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
            $user->lastActivityOn = zget($gogsUser, 'login', '');

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
     * Get upstream project by API.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return void
     */
    public function apiGetUpstream($gogsID, $project)
    {
        $currentProject = $this->apiGetSingleProject($gogsID, $project);
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
            $branch->web_url = "{$gogs->url}/$project/src/$branchName";
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
     * Api delete branch.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @param  string $branch
     * @access public
     * @return void
     */
    public function apiDeleteBranch($gogsID, $project, $branch)
    {
        $url = sprintf($this->getApiRoot($gogsID), "/repos/$project/branches/$branch");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }
}
