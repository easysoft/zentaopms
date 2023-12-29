<?php
declare(strict_types=1);
/**
 * The model file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        https://www.zentao.net
 */

class gitlabModel extends model
{

    const HOOK_PUSH_EVENT = 'Push Hook';

    /* Gitlab access level. */
    public $noAccess         = 0;
    public $developerAccess  = 30;
    public $maintainerAccess = 40;

    protected $projects = array();

    /**
     * 获取gitlab根据id。
     * Get a gitlab by id.
     *
     * @param  int $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * 获取gitlab列表。
     * Get gitlab list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', object $pager = null): array
    {
        $gitlabList = $this->loadModel('pipeline')->getList('gitlab', $orderBy, $pager);

        return $gitlabList;
    }

    /**
     * 获取gitlab id name 键值对。
     * Get gitlab pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        return $this->loadModel('pipeline')->getPairs('gitlab');
    }

    /**
     * 获取gitlab api 基础url 根据gitlab id。
     * Get gitlab api base url by gitlab id.
     *
     * @param  int    $gitlabID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot(int $gitlabID, bool $sudo = true): string
    {
        $gitlab = $this->getByID($gitlabID);
        if(!$gitlab) return '';

        $sudoParam = '';
        if($sudo == true and !$this->app->user->admin)
        {
            $openID = $this->loadModel('pipeline')->getOpenIdByAccount($gitlabID, 'gitlab', $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gitlab->url, '/') . '/api/v4%s' . "?private_token={$gitlab->token}" . $sudoParam;
    }

    /**
     * 获取gitlab的用户id和真实名字的键值对。
     * Get gitlab user id and realname pairs of one gitlab.
     *
     * @param  int    $gitlabID
     * @access public
     * @return array
     */
    public function getUserIdRealnamePairs(int $gitlabID): array
    {
        return $this->dao->select('oauth.openID as openID,user.realname as realname')
            ->from(TABLE_OAUTH)->alias('oauth')
            ->leftJoin(TABLE_USER)->alias('user')
            ->on("oauth.account = user.account")
            ->where('providerType')->eq('gitlab')
            ->andWhere('providerID')->eq($gitlabID)
            ->fetchPairs();
    }

    /**
     * 获取gitlab的 项目id和名称 键值对。
     * Get project pairs of one gitlab.
     *
     * @param  int    $gitlabID
     * @access public
     * @return array
     */
    public function getProjectPairs(int $gitlabID): array
    {
        $projects = $this->apiGetProjects($gitlabID);

        $projectPairs = array();
        foreach($projects as $project) $projectPairs[$project->id] = $project->name_with_namespace;

        return $projectPairs;
    }

    /**
     * 获取匹配的gitlab用户列表。
     * Get matched gitlab users.
     *
     * @param  int    $gitlabID
     * @param  array  $gitlabUsers
     * @param  array  $zentaoUsers
     * @access public
     * @return array
     */
    public function getMatchedUsers(int $gitlabID, array $gitlabUsers, array $zentaoUsers): array
    {
        $matches = new stdclass;
        foreach($gitlabUsers as $gitlabUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($gitlabUser->email == $zentaoUser->email)       $matches->emails[$gitlabUser->email][]     = $zentaoUser->account;
                if($gitlabUser->account == $zentaoUser->account)   $matches->accounts[$gitlabUser->account][] = $zentaoUser->account;
                if($gitlabUser->realname == $zentaoUser->realname) $matches->names[$gitlabUser->realname][]   = $zentaoUser->account;
            }
        }

        $bindedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitlabID, 'gitlab', 'openID,account');

        $matchedUsers = array();
        foreach($gitlabUsers as $gitlabUser)
        {
            if(isset($bindedUsers[$gitlabUser->id]))
            {
                $gitlabUser->zentaoAccount     = $bindedUsers[$gitlabUser->id];
                $matchedUsers[$gitlabUser->id] = $gitlabUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->emails[$gitlabUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gitlabUser->email]);
            if(isset($matches->names[$gitlabUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gitlabUser->realname]);
            if(isset($matches->accounts[$gitlabUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gitlabUser->account]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gitlabUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$gitlabUser->id] = $gitlabUser;
            }
        }

        return $matchedUsers;
    }

    /**
     * 根据关联信息获取gitlab id和gitlab项目id。
     * Get gitlabID and projectID.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return object|false
     */
    public function getRelationByObject(string $objectType, int $objectID): object|false
    {
        return $this->dao->select('*, extra as gitlabID, BVersion as projectID, BID as issueID')->from(TABLE_RELATION)
            ->where('relation')->eq('gitlab')
            ->andWhere('Atype')->eq($objectType)
            ->andWhere('AID')->eq($objectID)
            ->fetch();
    }

    /**
     * 获取问题id列表根据关联信息。
     * Get issue id list group by object.
     *
     * @param  string $objectType
     * @param  array  $objects
     * @access public
     * @return array
     */
    public function getIssueListByObjects(string $objectType, array $objects): array
    {
        return $this->dao->select('*, extra as gitlabID, BVersion as projectID, BID as issueID')->from(TABLE_RELATION)
            ->where('relation')->eq('gitlab')
            ->andWhere('Atype')->eq($objectType)
            ->andWhere('AID')->in($objects)
            ->fetchAll('AID');
    }

    /**
     * 获取gitlab项目名称根据项目id。
     * Get gitlab project name of one gitlab project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return string|false
     */
    public function getProjectName(int $gitlabID, int $projectID): string|false
    {
        $project = $this->apiGetSingleProject($gitlabID, $projectID);
        if(is_object($project) and isset($project->name)) return $project->name;
        return false;
    }

    /**
     * 获取分支和标签列表。
     * Get reference option menus.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getReferenceOptions(int $gitlabID, int $projectID): array
    {
        $refList = array();

        $branches = $this->apiGetBranches($gitlabID, $projectID);
        if(is_array($branches))
        {
            foreach($branches as $branch) $refList[$branch->name] = "Branch::" . $branch->name;
        }

        $tags = $this->apiGetTags($gitlabID, $projectID);

        if(is_array($tags))
        {
            foreach($tags as $tag) $refList[$tag->name] = "Tag::" . $tag->name;
        }
        return $refList;
    }

    /**
     * 获取分支名称列表。
     * Get branches.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getBranches(int $gitlabID, int $projectID): array
    {
        $rawBranches = $this->apiGetBranches($gitlabID, $projectID);

        $branches = array();
        foreach($rawBranches as $branch)
        {
            $branches[] = $branch->name;
        }
        return $branches;
    }

    /**
     * 获取gitlab 提交列表。
     * Get Gitlab commits.
     *
     * @param  object $repo
     * @param  string $entry
     * @param  string $type
     * @param  object $pager
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getCommits(object $repo, string $entry, string $type = 'dir', object $pager = null, string $begin = '', string $end = ''): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $comments = $scm->engine->getCommitsByPath($entry, '', '', isset($pager->recPerPage) ? $pager->recPerPage : 10, isset($pager->pageID) ? $pager->pageID : 1, false, $begin, $end);
        if(!is_array($comments)) return array();

        if(isset($pager->recTotal)) $pager->recTotal = count($comments) < $pager->recPerPage ? $pager->recPerPage * $pager->pageID : $pager->recPerPage * ($pager->pageID + 1);

        $designNames = $this->dao->select("commit, name")->from(TABLE_DESIGN)->where('deleted')->eq(0)->fetchPairs();
        $designIds   = $this->dao->select("commit, id")->from(TABLE_DESIGN)->where('deleted')->eq(0)->fetchPairs();
        $commitIds   = array();
        foreach($comments as $comment)
        {
            $comment->revision        = $comment->id;
            $comment->originalComment = $comment->title;
            $comment->comment         = $this->loadModel('repo')->replaceCommentLink($comment->title);
            $comment->committer       = $comment->committer_name;
            $comment->time            = date("Y-m-d H:i:s", strtotime($comment->committed_date));
            $comment->designName      = zget($designNames, $comment->revision, '');
            $comment->designID        = zget($designIds, $comment->revision, '');
            $commitIds[]              = $comment->id;
        }
        $commitCounts = $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in($commitIds)->fetchPairs();
        foreach($comments as $comment) $comment->commit = !empty($commitCounts[$comment->id]) ? $commitCounts[$comment->id] : '';

        return $comments;
    }

    /**
     * 设置项目信息。
     * Set project data.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return void
     */
    public function setProject(int $gitlabID, int $projectID, object $project): void
    {
        $this->projects[$gitlabID][$projectID] = $project;
    }

    /**
     * 发送一个get api请求。
     * Send an api get request.
     *
     * @param  int|string $host gitlab server ID | gitlab host url.
     * @param  string     $api
     * @param  array      $data
     * @param  array      $options
     * @access public
     * @return object|array|null|false
     */
    public function apiGet(int|string $host, string $api, array $data = array(), array $options = array()): object|array|null|false
    {
        if(is_numeric($host)) $host = $this->getApiRoot($host);
        if(strpos($host, 'http://') !== 0 and strpos($host, 'https://') !== 0) return false;

        $url = sprintf($host, $api);
        return json_decode(commonModel::http($url, $data, $options, $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false));
    }

    /**
     * 发送一个post api请求。
     * Send an api post request.
     *
     * @param  int|string $host gitlab server ID | gitlab host url.
     * @param  string     $api
     * @param  array      $data
     * @param  array      $options
     * @access public
     * @return object|array|null
     */
    public function apiPost(int|string $host, string $api, array $data = array(), array $options = array()): object|array|null
    {
        if(is_numeric($host)) $host = $this->getApiRoot($host);
        if(strpos($host, 'http://') !== 0 and strpos($host, 'https://') !== 0) return false;

        $url = sprintf($host, $api);
        return json_decode(commonModel::http($url, $data, $options));
    }

    /**
     * 获取代表列表通过api。
     * Get a list of to-do items by API.
     *
     * @link   https://docs.gitlab.com/ee/api/todos.html
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $sudo
     * @access public
     * @return object|array|null|string
     */
    public function apiGetTodoList(int $gitlabID, int $projectID, int $sudo): object|array|null|string
    {
        $gitlab = $this->loadModel('gitlab')->getByID($gitlabID);
        if(!$gitlab) return '';
        $url = rtrim($gitlab->url, '/') . "/api/v4/todos?project_id=$projectID&type=MergeRequest&state=pending&private_token={$gitlab->token}&sudo={$sudo}";
        return json_decode(commonModel::http($url, $data = null, $optionsi = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false));
    }

    /**
     * 获取最新用户。
     * Get current user.
     *
     * @param  string $host
     * @param  string $token
     * @access public
     * @return object|array|null|false
     */
    public function apiGetCurrentUser(string $host, string $token): object|array|null|false
    {
        $host = rtrim($host, '/') . "/api/v4%s?private_token=$token";
        return $this->apiGet($host, '/user');
    }

    /**
     * 获取gitlab用户列表。
     * Get gitlab user list.
     *
     * @param  int    $gitlabID
     * @param  bool   $onlyLinked
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function apiGetUsers(int $gitlabID, bool $onlyLinked = false, string $orderBy = 'id_desc'): array
    {
        /* GitLab API '/users' can only return 20 users per page in default, so we use a loop to fetch all users. */
        $page     = 1;
        $perPage  = 100;
        $response = array();
        $apiRoot  = $this->getApiRoot($gitlabID);

        /* Get order data. */
        $orders = explode('_', $orderBy);
        $sort   = array_pop($orders);
        $order  = join('_', $orders);

        while(true)
        {
            /* Also use `per_page=20` to fetch users in API. Fetch active users only. */
            $url      = sprintf($apiRoot, "/users") . "&order_by={$order}&sort={$sort}&page={$page}&per_page={$perPage}&active=true";
            $httpData = commonModel::http($url, null, array(), array(), 'data', 'GET', 30, true, false);
            if(empty($httpData['body'])) break;

            $result   = json_decode($httpData['body']);
            if(!empty($result) && is_array($result))
            {
                $response = array_merge($response, $result);
                $page += 1;
                if($httpData['header']['X-Page'] == $httpData['header']['X-Total-Pages']) break;
            }
            else
            {
                break;
            }
        }

        if(!$response) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitlabID, 'gitlab', 'openID,account');

        $users = array();
        foreach($response as $gitlabUser)
        {
            if($onlyLinked and !isset($linkedUsers[$gitlabUser->id])) continue;

            $user = new stdclass;
            $user->id             = $gitlabUser->id;
            $user->realname       = $gitlabUser->name;
            $user->account        = $gitlabUser->username;
            $user->email          = zget($gitlabUser, 'email', '');
            $user->avatar         = $gitlabUser->avatar_url;
            $user->createdAt      = zget($gitlabUser, 'created_at', '');
            $user->lastActivityOn = zget($gitlabUser, 'last_activity_on', '');

            $users[] = $user;
        }

        return $users;
    }

    /**
     * 获取gitlab群组成员列表。
     * Get group members of one gitlab.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function apiGetGroupMembers(int $gitlabID, int $groupID, int $userID = 0): array
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/$groupID/members/all");
        if($userID) $url .= "&user_ids=$userID";

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&&page={$page}&per_page=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 获取gitlab命名空间列表。
     * Get namespaces of one gitlab.
     *
     * @param  int    $gitlabID
     * @access public
     * @return object
     */
    public function apiGetNamespaces(int $gitlabID): array
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/namespaces");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&&page={$page}&per_page=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 获取gitlab的群组列表。
     * Get groups of one gitlab.
     *
     * @param  int     $gitlabID
     * @param  string  $orderBy
     * @param  string  $minRole
     * @param  string  $keyword
     * @access public
     * @return array
     */
    public function apiGetGroups(int $gitlabID, string $orderBy = 'id_desc', string $minRole = '', string $keyword = ''): array
    {
        $apiRoot = $this->getApiRoot($gitlabID, $minRole == 'owner' ? true : false);
        $url     = sprintf($apiRoot, "/groups");
        if($minRole == 'owner')
        {
            $url .= '&owned=true';
        }
        elseif(!empty($minRole) and isset($this->config->gitlab->accessLevel[$minRole]))
        {
            $url .= '&min_access_level=' . $this->config->gitlab->accessLevel[$minRole];
        }

        if($keyword) $url .= '&search=' . urlencode($keyword);

        $order = 'id';
        $sort  = 'desc';
        if(strpos($orderBy, '_') !== false) list($order, $sort) = explode('_', $orderBy);

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $pageUrl = $url . "&statistics=true&order_by={$order}&sort={$sort}&page={$page}&per_page=100&all_available=true";
            $results = json_decode(commonModel::http($pageUrl));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 通过api创建gitlab群组。
     * Create a gitab group by api.
     *
     * @param  int    $gitlabID
     * @param  object $group
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateGroup(int $gitlabID, object $group): object|array|null|false
    {
        if(empty($group->name) or empty($group->path)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups");
        return json_decode(commonModel::http($url, $group));
    }

    /**
     * 获取gitlab项目列表根据分页。
     * Get projects of one gitlab.
     *
     * @param  int    $gitlabID
     * @param  string $keyword
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function apiGetProjectsPager(int $gitlabID, string $keyword = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "/projects");

        /* Parse order string. */
        $order = explode('_', $orderBy);

        $keyword = urlencode($keyword);
        $result  = commonModel::http($url . "&per_page={$pager->recPerPage}&order_by={$order[0]}&sort={$order[1]}&page={$pager->pageID}&search={$keyword}&search_namespaces=true", null, array(), array(), 'data', 'GET', 30, true, false);

        $header     = $result['header'];
        $recTotal   = $header['X-Total'];
        $recPerPage = $header['X-Per-Page'];

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pager->pageID);

        return array('pager' => $pager, 'projects' => json_decode($result['body']));
    }


    /**
     * 获取gitlab的项目列表。
     * Get projects of one gitlab.
     *
     * @param  int    $gitlabID
     * @param  string $simple
     * @param  int    $minID
     * @param  int    $maxID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetProjects(int $gitlabID, string $simple = 'true', int $minID = 0, int $maxID = 0, bool $sudo = true): array
    {
        $apiRoot = $this->getApiRoot($gitlabID, $sudo);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "/projects");
        if($minID > 0) $url .= '&id_after=' . (intval($minID) - 1);
        if($maxID > 0) $url .= '&id_before=' . (intval($maxID) + 1);

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&simple={$simple}&page={$page}&per_page=100", $data = null, $optionsi = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }


    /**
     * 通过api创建gitlab项目。
     * Create a gitab project by api.
     *
     * @param  int    $gitlabID
     * @param  object $project
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateProject(int $gitlabID, object $project): object|array|null|false
    {
        if(empty($project->name) || empty($project->path)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects");
        return json_decode(commonModel::http($url, $project));
    }

    /**
     * 通过api创建项目成员。
     * Add a gitab project member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $member
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateProjectMember(int $gitlabID, int $projectID, object $member): object|array|null|false
    {
        if(empty($member->user_id) or empty($member->access_level)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/members");
        return json_decode(commonModel::http($url, $member));
    }

    /**
     * 通过api更新一个项目成员。
     * Update a gitab project member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $member
     * @access public
     * @return object|array|null|false
     */
    public function apiUpdateProjectMember(int $gitlabID, int $projectID, object $member): object|array|null|false
    {
        if(empty($member->user_id) or empty($member->access_level)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/members/{$member->user_id}");
        return json_decode(commonModel::http($url, $member, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * 通过api删除一个项目成员
     * Delete a gitab project member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $memberID
     * @access public
     * @return object|array|null
     */
    public function apiDeleteProjectMember(int $gitlabID, int $projectID, int $memberID): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/members/{$memberID}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api创建一个群组成员。
     * Add a gitab group member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @param  object $member
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateGroupMember(int $gitlabID, int $groupID, object $member): object|array|null|false
    {
        if(empty($member->user_id) or empty($member->access_level)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/{$groupID}/members");
        return json_decode(commonModel::http($url, $member));
    }

    /**
     * 通过api更新一个gitlab群组成员。
     * Update a gitab group member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @param  object $member
     * @access public
     * @return object|array|null|false
     */
    public function apiUpdateGroupMember(int $gitlabID, int $groupID, object $member): object|array|null|false
    {
        if(empty($member->user_id) or empty($member->access_level)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/{$groupID}/members/{$member->user_id}");
        return json_decode(commonModel::http($url, $member, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * 通过api删除一个gitlab群组成员。
     * Delete a gitab group member by api.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @param  int    $memberID
     * @access public
     * @return object|array|null
     */
    public function apiDeleteGroupMember(int $gitlabID, int $groupID, int $memberID): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/{$groupID}/members/{$memberID}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api创建一个gitlab用户.
     * Create a gitab user by api.
     *
     * @param  int    $gitlabID
     * @param  object $user
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateUser(int $gitlabID, object $user): object|array|null|false
    {
        if(empty($user->name) or empty($user->username) or empty($user->email) or empty($user->password)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/users");
        return json_decode(commonModel::http($url, $user));
    }

    /**
     * 通过api更新一个gitlab用户。
     * Update a gitab user by api.
     *
     * @param  int    $gitlabID
     * @param  object $user
     * @access public
     * @return object|array|null|false
     */
    public function apiUpdateUser(int $gitlabID, object $user): object|array|null|false
    {
        if(empty($user->id)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/users/{$user->id}");
        return json_decode(commonModel::http($url, $user, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * 通过api更新一个gitlab群组。
     * Update a gitab group by api.
     *
     * @param  int    $gitlabID
     * @param  object $group
     * @access public
     * @return object|array|null|false
     */
    public function apiUpdateGroup(int $gitlabID, object $group): object|array|null|false
    {
        if(empty($group->id)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/{$group->id}");
        return json_decode(commonModel::http($url, $group, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * 通过api删除一个gitlab群组。
     * Delete a gitab group by api.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteGroup(int $gitlabID, int $groupID): object|array|null|false
    {
        if(empty($groupID)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/groups/{$groupID}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api更新一个gitlab项目。
     * Update a gitab project by api.
     *
     * @param  int    $gitlabID
     * @param  object $project
     * @access public
     * @return object|array|null|false
     */
    public function apiUpdateProject(int $gitlabID, object $project): object|array|null|false
    {
        if(empty($project->id)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$project->id}");
        return json_decode(commonModel::http($url, $project, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * 通过api删除一个gitlab项目。
     * Delete a gitab project by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteProject(int $gitlabID, int $projectID): object|array|null|false
    {
        if(empty($projectID)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api删除一个gitlab用户。
     * Delete a gitab user by api.
     *
     * @param  int    $gitlabID
     * @param  int    $userID
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteUser(int $gitlabID, int $userID): object|array|null|false
    {
        if(empty($userID)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/users/{$userID}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api创建一个gitlab用户。
     * Create a gitab user by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $branch
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateBranch(int $gitlabID, int $projectID, object $branch): object|array|null|false
    {
        if(empty($branch->branch) or empty($branch->ref)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/repository/branches");
        return json_decode(commonModel::http($url, $branch));
    }

    /**
     * 通过api获取一个项目信息。
     * Get single project by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  bool   $useUser
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleProject(int $gitlabID, int $projectID, bool $useUser = true): object|array|null
    {
        if(isset($this->projects[$gitlabID][$projectID])) return $this->projects[$gitlabID][$projectID];

        $url = sprintf($this->getApiRoot($gitlabID, $useUser), "/projects/$projectID");
        $this->projects[$gitlabID][$projectID] = json_decode(commonModel::http($url, $data = null, $optionsi = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false));
        return $this->projects[$gitlabID][$projectID];
    }

    /**
     * 通过api获取一个用户。
     * Get single user by API.
     *
     * @param  int    $gitlabID
     * @param  int    $userID
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleUser(int $gitlabID, int $userID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID, false), "/users/$userID");
        return json_decode(commonModel::http($url));
    }

     /**
      * 通过api获取一个gitlab群组。
     * Get single group by API.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleGroup(int $gitlabID, int $groupID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/groups/$groupID");
        return json_decode(commonModel::http($url));
    }

    /**
     * 获取gitlab项目最新成员。
     * Get project all members(users and users in groups).
     *
     * @param  int   $gitlabID
     * @param  int   $projectID
     * @param  int   $userID
     * @access public
     * @return object|array|null
     */
    public function apiGetProjectMembers(int $gitlabID, int $projectID, int $userID = 0): object|array|null
    {
        $path = "/projects/{$projectID}/members/all" . ($userID ? "/{$userID}" : '');
        $url  = sprintf($this->getApiRoot($gitlabID), $path);
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取项目一个分支。
     * Get single branch by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $branch
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleBranch(int $gitlabID, int $projectID, string $branch): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/repository/branches/$branch");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取项目 hooks。
     * Get hooks.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @link   https://docs.gitlab.com/ee/api/projects.html#list-project-hooks
     * @return object|array|null
     */
    public function apiGetHooks(int $gitlabID, int $projectID, int $hookID = 0): object|array|null
    {
        $apiRoot  = $this->getApiRoot($gitlabID, false);
        $apiPath  = "/projects/{$projectID}/hooks" . ($hookID ? "/{$hookID}" : '');
        $url      = sprintf($apiRoot, $apiPath);

        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api创建hook。
     * Create hook by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $hook
     * @access public
     * @link   https://docs.gitlab.com/ee/api/projects.html#add-project-hook
     * @return object|array|null|false
     */
    public function apiCreateHook(int $gitlabID, int $projectID, object $hook): object|array|null|false
    {
        if(!isset($hook->url)) return false;

        $newHook = new stdclass;
        $newHook->enable_ssl_verification = "false"; /* Disable ssl verification for every hook. */

        foreach($hook as $index => $item) $newHook->$index= $item;

        $apiRoot = $this->getApiRoot($gitlabID, false);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/hooks");

        return json_decode(commonModel::http($url, $newHook));
    }

    /**
     * 通过api删除hook。
     * Delete hook by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $hookID
     * @access public
     * @link   https://docs.gitlab.com/ee/api/projects.html#delete-project-hook
     * @return object|array|null
     */
    public function apiDeleteHook(int $gitlabID, int $projectID, int $hookID): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID, false);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/hooks/{$hookID}");

        return json_decode(commonModel::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 添加一个推送和合并请求事件的webhook到gitlab项目。
     * Add webhook with push and merge request events to GitLab project.
     *
     * @param  object $repo
     * @param  string $token
     * @access public
     * @return bool|array
     */
    public function addPushWebhook(object $repo, string $token = ''): bool|array
    {
        $systemURL = dirname(common::getSysURL() . $_SERVER['REQUEST_URI']);

        $hook = new stdClass;
        $hook->url = $systemURL . '/api.php/v1/gitlab/webhook?repoID='. $repo->id;
        $hook->push_events           = true;
        $hook->merge_requests_events = true;
        if($token) $hook->token = $token;

        /* Return an empty array if where is one existing webhook. */
        if($this->isWebhookExists($repo, $hook->url)) return array();

        $result = $this->apiCreateHook($repo->gitService, $repo->project, $hook);

        if(!empty($result->id)) return true;
        return false;
    }

    /**
     * 检查webhook是否存在。
     * Check if Webhook exists.
     *
     * @param  object $repo
     * @param  string $url
     * @return bool
     */
    public function isWebhookExists(object $repo, string $url = ''): bool
    {
        $hookList = $this->apiGetHooks($repo->gitService, $repo->project);
        foreach($hookList as $hook)
        {
            if(empty($hook->url)) continue;
            if($hook->url == $url) return true;
        }
        return false;
    }

    /**
     * 通过api更新hook。
     * Update hook by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $hookID
     * @param  object $hook
     * @access public
     * @link   https://docs.gitlab.com/ee/api/projects.html#edit-project-hook
     * @return string|false
     */
    public function apiUpdateHook(int $gitlabID, int $projectID, int $hookID, object $hook): string|false
    {
        $apiRoot = $this->getApiRoot($gitlabID, false);

        if(!isset($hook->url)) return false;

        $newHook = new stdclass;
        $newHook->enable_ssl_verification = "false"; /* Disable ssl verification for every hook. */

        foreach($hook as $index => $item) $newHook->$index= $item;

        $url = sprintf($apiRoot, "/projects/{$projectID}/hooks/{$hookID}");
        return commonModel::http($url, $newHook, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT'));
    }

    /**
     * 创建gitlab项目的项目标签。
     * Create Label for gitlab project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $label
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateLabel(int $gitlabID, int $projectID, object $label): object|array|null|false
    {
        if(empty($label->name) or empty($label->color)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/labels/");
        return json_decode(commonModel::http($url, $label));
    }

    /**
     * 获取项目的项目标签。
     * Get labels of project by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object|array|null
     */
    public function apiGetLabels(int $gitlabID, int $projectID): object|array|null
    {
        $apiRoot  = $this->getApiRoot($gitlabID);
        $url      = sprintf($apiRoot, "/projects/{$projectID}/labels/");
        $response = commonModel::http($url);

        return json_decode($response);
    }

    /**
     * 通过api删除一个项目标签。
     * Delete a Label with labelName by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $labelName
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteLabel(int $gitlabID, int $projectID, string $labelName): object|array|null|false
    {
        $labels = $this->apiGetLabels($gitlabID, $projectID);
        if(!$labels) return false;

        foreach($labels as $label)
        {
            if(isset($label->name) && $label->name == $labelName) $labelID = $label->id;
        }
        if(empty($labelID)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/labels/{$labelID}");

        return json_decode(commonModel::http($url, null, $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api获取一个issue。
     * Get single issue by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $issueID
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleIssue(int $gitlabID, int $projectID, int $issueID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/issues/{$issueID}");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取issue列表。
     * Get gitlab issues by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $options
     * @access public
     * @return object|array|null
     */
    public function apiGetIssues(int $gitlabID, int $projectID, string $options = ''): object|array|null
    {
        if($options)
        {
            $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/issues") . '&per_page=20' . $options;
        }
        else
        {
            $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/issues") . '&per_page=20';
        }

        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api删除一个issue。
     * Delete an issue by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $issueID
     * @access public
     * @return object|array|null
     */
    public function apiDeleteIssue(int $gitlabID, int $projectID, int $issueID): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/issues/{$issueID}");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过api创建一个流水线。
     * Create a new pipeline by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $params
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#create-a-new-pipeline
     */
    public function apiCreatePipeline(int $gitlabID, int $projectID, object $params): object|array|null
    {
        if(!is_string($params)) $params = json_encode($params);
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/pipeline");
        return json_decode(commonModel::http($url, $params, array(), array("Content-Type: application/json")));
    }

    /**
     * 通过api获取一个流水线。
     * Get single pipline by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $pipelineID
     * @access public
     * @return object|array|null
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#get-a-single-pipeline
     */
    public function apiGetSinglePipeline(int $gitlabID, int $projectID, int $pipelineID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取流水线jobs。
     * List pipeline jobs by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $pipelineID
     * @access public
     * @return object|array|null
     * @docment https://docs.gitlab.com/ee/api/jobs.html#list-pipeline-jobs
     */
    public function apiGetJobs(int $gitlabID, int $projectID, int $pipelineID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/pipelines/{$pipelineID}/jobs");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取一个job。
     * Get a single job by api.
     *
     * @param  int $gitlabID
     * @param  int $projectID
     * @param  int $jobID
     * @return object|array|null
     * @docment https://docs.gitlab.com/ee/api/jobs.html#get-a-single-job
     */
    public function apiGetSingleJob(int $gitlabID, int $projectID, int $jobID): object|array|null
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/jobs/{$jobID}");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过api获取日志。
     * Get a log file by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $jobID
     * @return string
     * @docment https://docs.gitlab.com/ee/api/jobs.html#get-a-log-file
     */
    public function apiGetJobLog(int $gitlabID, int $projectID, int $jobID): string
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/jobs/{$jobID}/trace");
        return commonModel::http($url);
    }

    /**
     * 通过api获取代码库分支列表。
     * Get project repository branches by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function apiGetBranches(int $gitlabID, int $projectID, object $pager = null): array
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/{$projectID}/repository/branches");
        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&&page={$page}&per_page=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 通过api获取代码库标签列表。
     * Get project repository tags by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $keyword
     * @param  object $pager
     * @access public
     * @return object|array|null
     */
    public function apiGetTags(int $gitlabID, int $projectID, string $orderBy = '', string $keyword = '', object $pager = null): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID);

        /* Parse order string. */
        if($orderBy)
        {
            list($order, $sort) = explode('_', $orderBy);
            $apiRoot .= "&order_by={$order}&sort={$sort}";
        }

        if($keyword) $apiRoot .= "&search={$keyword}";

        if(!$pager)
        {
            $allResults = array();
            $url        = sprintf($apiRoot, "/projects/{$projectID}/repository/tags");
            for($page = 1; true; $page++)
            {
                $results = json_decode(commonModel::http($url . "&&page={$page}&per_page=100"));
                if(!is_array($results)) break;
                if(!empty($results)) $allResults = array_merge($allResults, $results);
                if(count($results) < 100) break;
            }

            return $allResults;
        }
        else
        {
            $apiRoot .= "&per_page={$pager->recPerPage}&page={$pager->pageID}";
            $url      = sprintf($apiRoot, "/projects/{$projectID}/repository/tags");
            $result   = commonModel::http($url, null, array(), array(), 'data', 'GET', 30, true, false);

            $header = $result['header'];
            $pager->setRecTotal($header['X-Total']);
            $pager->setPageTotal();
            if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);

            return json_decode($result['body']);
        }
    }

    /**
     * 通过api删除一个标签。
     * Delete a gitab tag by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tagName
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteTag(int $gitlabID, int $projectID, string $tagName = ''): object|array|null|false
    {
        if(!(int)$gitlabID or !(int)$projectID or empty($tagName)) return false;

        $apiRoot = $this->getApiRoot($gitlabID);
        $tagName = urlencode($tagName);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/repository/tags/{$tagName}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 获取一个项目的标签策略。
     * Get protect tags of one project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function apiGetTagPrivs(int $gitlabID, int $projectID): array
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/protected_tags");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&&page={$page}&per_page=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        $tags = array();
        foreach($allResults as $tag) $tags[$tag->name] = $tag;

        return $tags;
    }

    /**
     * 通过api删除一个gitlab项目tag。
     * Delete a gitab protect tag by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tag
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteTagPriv(int $gitlabID, int $projectID, string $tag): object|array|null|false
    {
        if(empty($gitlabID)) return false;
        $apiRoot = $this->getApiRoot($gitlabID);
        $tag     = urlencode($tag);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/protected_tags/{$tag}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 通过HTTP_X_GITLAB_TOKEN检查webhook token。
     * Check webhook token by HTTP_X_GITLAB_TOKEN.
     *
     * @access public
     * @return void
     */
    public function webhookCheckToken(): void
    {
        $gitlab = $this->getByID($this->get->gitlab);
        if($gitlab->private != $_SERVER["HTTP_X_GITLAB_TOKEN"]) echo 'Token error.';
    }

    /**
     * 解析webhook事件。
     * Parse webhook body function.
     *
     * @param  object $body
     * @param  int    $gitlabID
     * @access public
     * @return object
     */
    public function webhookParseBody(object $body, int $gitlabID): object
    {
        $type = zget($body, 'object_kind', '');
        if(!$type or !is_callable(array($this, "webhookParse{$type}"))) return false;
        // fix php 8.0 bug. link: https://www.php.net/manual/zh/function.call-user-func-array.php#125953

        return call_user_func_array(array($this, "webhookParse{$type}"), array($body, $gitlabID));
    }

    /**
     * 解析webhook触发的issue。
     * Parse Webhook issue.
     *
     * @param  object $body
     * @param  int    $gitlabID
     * @access public
     * @return object
     */
    public function webhookParseIssue(object $body, int $gitlabID): object
    {
        $object = $this->webhookParseObject($body->labels);
        if(empty($object)) return null;

        $issue             = new stdclass;
        $issue->action     = $body->object_attributes->action . $body->object_kind;
        $issue->issue      = $body->object_attributes;
        $issue->changes    = $body->changes;
        $issue->objectType = $object->type;
        $issue->objectID   = $object->id;

        $issue->issue->objectType = $object->type;
        $issue->issue->objectID   = $object->id;

        /* Parse markdown description to html. */
        $issue->issue->description = commonModel::processMarkdown($issue->issue->description);

        if(!isset($this->config->gitlab->maps->{$object->type})) return false;
        $issue->object = $this->issueToZentaoObject($issue->issue, $gitlabID, $body->changes);
        return $issue;
    }

    /**
     * 通过webhook同步issue。
     * Webhook sync issue.
     *
     * @param  int    $gitlabID
     * @param  object $issue
     * @access public
     * @return void
     */
    public function webhookSyncIssue(int $gitlabID, object $issue): bool
    {
        $tableName = zget($this->config->gitlab->objectTables, $issue->objectType, '');
        if($tableName) $this->dao->update($tableName)->data($issue->object)->where('id')->eq($issue->objectID)->exec();
        return !dao::isError();
    }

    /**
     * 通过标签解析禅道对象。
     * Parse zentao object from labels.
     *
     * @param  array $labels
     * @access public
     * @return object
     */
    public function webhookParseObject(array $labels): object
    {
        $object     = null;
        $objectType = '';
        foreach($labels as $label)
        {
            if(preg_match($this->config->gitlab->labelPattern->story, $label->title)) $objectType = 'story';
            if(preg_match($this->config->gitlab->labelPattern->task, $label->title)) $objectType = 'task';
            if(preg_match($this->config->gitlab->labelPattern->bug, $label->title)) $objectType = 'bug';

            if($objectType)
            {
                list($prefix, $id) = explode('/', $label->title);
                $object       = new stdclass;
                $object->id   = $id;
                $object->type = $objectType;
            }
        }

        return $object;
    }

    /**
     * 通过issue webhook指派禅道任务、需求、bug。
     * Process webhook issue assign option.
     *
     * @param  object $issue
     * @access public
     * @return bool
     */
    public function webhookAssignIssue(object $issue): bool
    {
        $tableName = zget($this->config->gitlab->objectTables, $issue->objectType, '');
        if(!$tableName) return false;

        $data               = $issue->object;
        $data->assignedDate = $issue->object->lastEditedDate;
        $data->assignedTo   = $issue->object->assignedTo;

        $this->dao->update($tableName)->data($data)->where('id')->eq($issue->objectID)->exec();
        if(dao::isError()) return false;

        $oldObject = $this->dao->findById($issue->objectID)->from($tableName)->fetch();
        $changes   = common::createChanges($oldObject, $data);
        $actionID  = $this->loadModel('action')->create($issue->objectType, $issue->objectID, 'Assigned', "Assigned by webhook by gitlab issue : {$issue->issue->url}", $data->assignedTo);
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 通过issue webhook关闭禅道任务、需求、bug。
     * Process issue close option.
     *
     * @param  object $issue
     * @access public
     * @return bool
     */
    public function webhookCloseIssue(object $issue): bool
    {
        $tableName = zget($this->config->gitlab->objectTables, $issue->objectType, '');
        if(!$tableName) return false;

        $data             = $issue->object;
        $data->assignedTo = 'closed';
        $data->status     = 'closed';
        $data->closedBy   = $issue->object->lastEditedBy;
        $data->closedDate = $issue->object->lastEditedDate;

        $this->dao->update($tableName)->data($data)->where('id')->eq($issue->objectID)->exec();
        if(dao::isError()) return false;

        $oldObject = $this->dao->findById($issue->objectID)->from($tableName)->fetch();
        $changes   = common::createChanges($oldObject, $data);
        $actionID  = $this->loadModel('action')->create($issue->objectType, $issue->objectID, 'Closed', "Closed by gitlab issue: {$issue->issue->url}.");
        $this->action->logHistory($actionID, $changes);
        return true;
    }


    /**
     * 创建一个gitlab项目标签。
     * Create zentao object label for gitlab project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $objectType
     * @param  string $objectID
     * @access public
     * @return object|array|null|false
     */
    public function createZentaoObjectLabel(int $gitlabID, int $projectID, string $objectType, string $objectID): object|array|null|false
    {
        $label              = new stdclass;
        $label->name        = sprintf($this->config->gitlab->zentaoObjectLabel->name, $objectType, $objectID);
        $label->color       = $this->config->gitlab->zentaoObjectLabel->color->$objectType;
        $label->description = common::getSysURL() . helper::createLink($objectType, 'view', "id={$objectID}");

        return $this->apiCreateLabel($gitlabID, $projectID, $label);
    }

    /**
     * 从禅道和gtilab上删除一个issue。
     * Delete an issue from zentao and gitlab.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function deleteIssue(string $objectType, int $objectID, int $issueID): void
    {
        $relation = $this->getRelationByObject($objectType, $objectID);
        if(!empty($relation)) $this->dao->delete()->from(TABLE_RELATION)->where('id')->eq($relation->id)->exec();
        $this->apiDeleteIssue((int)$relation->gitlabID, (int)$relation->projectID, $issueID);
    }

    /**
     * 保存同步的issut到关联表。
     * Save synced issue to relation table.
     *
     * @param  string $objectType
     * @param  object $object
     * @param  int    $gitlabID
     * @param  object $issue
     * @access public
     * @return bool
     */
    public function saveIssueRelation(string $objectType, object $object, int $gitlabID, object $issue): bool
    {
        if(empty($issue->iid) or empty($issue->project_id)) return false;

        $relation            = new stdclass;
        $relation->product   = zget($object, 'product', 0);
        $relation->execution = zget($object, 'execution', 0);
        $relation->AType     = $objectType;
        $relation->AID       = $object->id;
        $relation->AVersion  = '';
        $relation->relation  = 'gitlab';
        $relation->BType     = 'issue';
        $relation->BID       = $issue->iid;
        $relation->BVersion  = $issue->project_id;
        $relation->extra     = $gitlabID;
        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

        return true;
    }

    /**
     * 保存导入issue。
     * Save imported issue.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $issue
     * @access public
     * @return void
     */
    public function saveImportedIssue(int $gitlabID, int $projectID, string $objectType, int $objectID, object $issue, object $object): void
    {
        $label = $this->createZentaoObjectLabel($gitlabID, $projectID, $objectType, (string)$objectID);
        $data  = new stdclass;
        if(isset($label->name)) $data->labels = $label->name;

        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/issues/{$issue->iid}");
        commonModel::http($url, $data, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT'));
        $this->saveIssueRelation($objectType, $object, $gitlabID, $issue);
    }

    /**
     * 解析禅道任务、bug、需求为gitlab issue。
     * Parse zentao object to issue. object can be task, bug and story.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $objectType
     * @param  object $object
     * @access public
     * @return object
     */
    public function parseObjectToIssue(int $gitlabID, int $projectID, string $objectType, object $object): object
    {
        $gitlabUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitlabID, 'gitlab', 'account,openID');
        if(empty($gitlabUsers)) return false;
        $issue = new stdclass;
        $map   = $this->config->gitlab->maps->$objectType;
        foreach($map as $objectField => $config)
        {
            $value = '';
            list($field, $optionType, $options) = explode('|', $config);
            if($optionType == 'field') $value = $object->$objectField;
            if($optionType == 'fields') $value = $object->$objectField . "\n\n" . $object->$options;
            if($optionType == 'userPairs')
            {
                $value = zget($gitlabUsers, $object->$objectField);
            }
            if($optionType == 'configItems')
            {
                $value = zget($this->config->gitlab->$options, $object->$objectField, '');
            }
            if($value) $issue->$field = $value;
        }
        if(isset($issue->assignee_id) and $issue->assignee_id == 'closed') unset($issue->assignee_id);

        /* issue->state is null when creating it, we should put status_event when updating it. */
        if(isset($issue->state) and $issue->state == 'closed') $issue->state_event = 'close';
        if(isset($issue->state) and $issue->state == 'opened') $issue->state_event = 'reopen';

        /* Append this object link in zentao to gitlab issue description */
        $zentaoLink = common::getSysURL() . helper::createLink($objectType, 'view', "id={$object->id}");
        if(strpos($issue->description, $zentaoLink) == false) $issue->description = $issue->description . "\n\n" . $zentaoLink;

        return $issue;
    }

    /**
     * 解析gitlab issue为禅道任务、bug、需求。
     * Parse issue to zentao object.
     *
     * @param  object $issue
     * @param  int    $gitlabID
     * @param  object $changes
     * @access public
     * @return object
     */
    public function issueToZentaoObject(object $issue, int $gitlabID, object $changes = null): object
    {
        if(!isset($this->config->gitlab->maps->{$issue->objectType})) return null;

        if(isset($changes->assignees)) $changes->assignee_id = true;
        $maps        = $this->config->gitlab->maps->{$issue->objectType};
        $gitlabUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitlabID, 'gitlab', 'openID,account');

        $object     = new stdclass;
        $object->id = $issue->objectID;
        foreach($maps as $zentaoField => $config)
        {
            $value = '';
            list($gitlabField, $optionType, $options) = explode('|', $config);
            if(!isset($changes->$gitlabField) and $object->id != 0) continue;
            if($optionType == 'field' or $optionType == 'fields') $value = $issue->$gitlabField;
            if($options == 'date') $value = $value ? date('Y-m-d', strtotime($value)) : '0000-00-00';
            if($options == 'datetime') $value = $value ? date('Y-m-d H:i:s', strtotime($value)) : '0000-00-00 00:00:00';
            if($optionType == 'userPairs' and isset($issue->$gitlabField)) $value = zget($gitlabUsers, $issue->$gitlabField);
            if($optionType == 'configItems' and isset($issue->$gitlabField)) $value = array_search($issue->$gitlabField, $this->config->gitlab->$options);

            /* Execute this line even `$value == ""`, such as `$issue->description == ""`. */
            if($value or $value == "") $object->$zentaoField = $value;

            if($gitlabField == "description") $object->$zentaoField .= "<br><br><a href=\"{$issue->web_url}\" target=\"_blank\">{$issue->web_url}</a>";
        }
        return $object;
    }

    /**
     * 创建一个gitlab项目。
     * Create gitlab project.
     *
     * @param  int    $gitlabID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function createProject(int $gitlabID, object $project): bool
    {
        if(empty($project->name)) dao::$errors['name'][] = $this->lang->gitlab->project->emptyNameError;
        if(empty($project->path)) dao::$errors['path'][] = $this->lang->gitlab->project->emptyPathError;
        if(dao::isError()) return false;

        $response = $this->apiCreateProject($gitlabID, $project);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabproject', $response->id, 'created', '', $response->name);
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 编辑一个gitlab项目。
     * Edit gitlab project.
     *
     * @param  int    $gitlabID
     * @access public
     * @return bool
     */
    public function editProject(int $gitlabID, object $project): bool
    {
        if(empty($project->name)) dao::$errors['name'][] = $this->lang->gitlab->project->emptyNameError;
        if(dao::isError()) return false;

        $response = $this->apiUpdateProject($gitlabID, $project);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabproject', $project->id, 'edited', '', $project->name);
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 创建一个gitlab用户。
     * Create a gitlab user.
     *
     * @param  int    $gitlabID
     * @param  object $gitlabUser
     * @access public
     * @return bool
     */
    public function createUser(int $gitlabID, object $gitlabUser): bool
    {
        if(empty($gitlabUser->account))  dao::$errors['account'][] = $this->lang->gitlab->user->bind . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->name))     dao::$errors['name'][] = $this->lang->gitlab->user->name . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->username)) dao::$errors['username'][] = $this->lang->gitlab->user->username . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->email))    dao::$errors['email'][] = $this->lang->gitlab->user->email . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->password)) dao::$errors['password'][] = $this->lang->gitlab->user->password . $this->lang->gitlab->user->emptyError;
        if(dao::isError()) return false;
        if($gitlabUser->password != $gitlabUser->password_repeat)
        {
            dao::$errors[] = $this->lang->gitlab->user->passwordError;
            return false;
        }
        /* Check whether the user has been bind. */
        if($gitlabUser->account)
        {
            $zentaoBindUser = $this->dao->select('account')->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq($gitlabID)->andWhere('account')->eq($gitlabUser->account)->fetch();
            if($zentaoBindUser)
            {
                dao::$errors['account'][] = $this->lang->gitlab->user->bindError;
                return false;
            }
        }

        $response = $this->apiCreateUser($gitlabID, $gitlabUser);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabuser', $response->id, 'created', '', $response->name);

            /* Bind user. */
            if($gitlabUser->account)
            {
                $gitlabUserBind = new stdclass;
                $gitlabUserBind->providerID   = $gitlabID;
                $gitlabUserBind->providerType = 'gitlab';
                $gitlabUserBind->account      = $gitlabUser->account;
                $gitlabUserBind->openID       = $response->id;
                $this->dao->insert(TABLE_OAUTH)->data($gitlabUserBind)->exec();
            }
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 编辑一个gitlab用户。
     * Edit a gitlab user.
     *
     * @param  int    $gitlabID
     * @access public
     * @return bool
     */
    public function editUser(int $gitlabID, object $gitlabUser): bool
    {
        if(empty($gitlabUser->account))  dao::$errors['account'][] = $this->lang->gitlab->user->bind . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->name))     dao::$errors['name'][] = $this->lang->gitlab->user->name . $this->lang->gitlab->user->emptyError;
        if(empty($gitlabUser->email))    dao::$errors['email'][] = $this->lang->gitlab->user->email . $this->lang->gitlab->user->emptyError;
        if(dao::isError()) return false;
        if(!empty($gitlabUser->password) and $gitlabUser->password != $gitlabUser->password_repeat)
        {
            dao::$errors[] = $this->lang->gitlab->user->passwordError;
            return false;
        }
        /* Check whether the user has been bind. */
        if($gitlabUser->account)
        {
            $zentaoBindUser = $this->dao->select('account,openID')->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq($gitlabID)->andWhere('account')->eq($gitlabUser->account)->fetch();
            $changeBind = (!$zentaoBindUser or $zentaoBindUser->openID != $gitlabUser->id) ? true : false;
            if($zentaoBindUser && $changeBind)
            {
                dao::$errors['bind'][] = $this->lang->gitlab->user->bindError;
                return false;
            }
        }

        $response = $this->apiUpdateUser($gitlabID, $gitlabUser);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabuser', $response->id, 'edited', '', $response->name);

            /* Delete old bind. */
            $this->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq($gitlabID)->andWhere('openID')->eq($response->id)->andWhere('account')->ne($gitlabUser->account)->exec();
            /* Bind user. */
            if($gitlabUser->account && $changeBind)
            {
                $gitlabUserBind = new stdclass;
                $gitlabUserBind->providerID   = $gitlabID;
                $gitlabUserBind->providerType = 'gitlab';
                $gitlabUserBind->account      = $gitlabUser->account;
                $gitlabUserBind->openID       = $response->id;
                $this->dao->replace(TABLE_OAUTH)->data($gitlabUserBind)->exec();
            }
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 创建一个gitlab群组。
     * Create a gitlab group.
     *
     * @param  int    $gitlabID
     * @param  object $group
     * @access public
     * @return bool
     */
    public function createGroup(int $gitlabID, object $group): bool
    {
        if(empty($group->name)) dao::$errors['name'][] = $this->lang->gitlab->group->name . $this->lang->gitlab->group->emptyError;
        if(empty($group->path)) dao::$errors['path'][] = $this->lang->gitlab->group->path . $this->lang->gitlab->group->emptyError;
        if(dao::isError()) return false;

        $response = $this->apiCreateGroup($gitlabID, $group);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabgroup', $response->id, 'created', '', $response->name);
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 编辑一个gitlab群组。
     * Edit a gitlab group.
     *
     * @param  int    $gitlabID
     * @param  object $group
     * @access public
     * @return bool
     */
    public function editGroup(int $gitlabID, object $group): bool
    {
        if(empty($group->name)) dao::$errors['name'][] = $this->lang->gitlab->group->name . $this->lang->gitlab->group->emptyError;
        if(dao::isError()) return false;

        $response = $this->apiUpdateGroup($gitlabID, $group);

        if(!empty($response->id))
        {
            $this->loadModel('action')->create('gitlabgroup', $response->id, 'edited', '', $response->name);
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 创建一个项目分支。
     * Create a gitlab branch.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $branch
     * @access public
     * @return bool
     */
    public function createBranch(int $gitlabID, int $projectID, object $branch): bool
    {
        if(empty($branch->branch)) dao::$errors['branch'][] = $this->lang->gitlab->branch->name . $this->lang->gitlab->emptyError;
        if(empty($branch->ref))    dao::$errors['ref'][]    = $this->lang->gitlab->branch->from . $this->lang->gitlab->emptyError;
        if(dao::isError()) return false;

        $response = $this->apiCreateBranch($gitlabID, $projectID, $branch);

        if(!empty($response->name))
        {
            $this->loadModel('action')->create('gitlabbranch', 0, 'created', '', $response->name);
            return true;
        }

        if(!is_object($response)) return false;
        return $this->apiErrorHandling($response);
    }

    /**
     * 错误处理。
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
        if(!empty($response->message))
        {
            if(is_string($response->message))
            {
                $errorKey = array_search($response->message, $this->lang->gitlab->apiError);
                dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gitlab->errorLang, $errorKey);
            }
            else
            {
                foreach($response->message as $field => $fieldErrors)
                {
                    if(empty($fieldErrors)) continue;

                    if(is_string($fieldErrors))
                    {
                        $errorKey = array_search($fieldErrors, $this->lang->gitlab->apiError);
                        if($fieldErrors) dao::$errors[$field][] = $errorKey === false ? $fieldErrors : zget($this->lang->gitlab->errorLang, $errorKey);
                    }
                    else
                    {
                        foreach($fieldErrors as $error)
                        {
                            $errorKey = array_search($error, $this->lang->gitlab->apiError);
                            if($error) dao::$errors[$field][] = $errorKey === false ? $error : zget($this->lang->gitlab->errorLang, $errorKey);
                        }
                    }
                }
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * 获取项目类型是gitlab的产品。
     * Get products which scm is GitLab by projects.
     *
     * @param  array $projectIDs
     * @return array
     */
    public function getProductsByProjects(array $projectIDs): array
    {
        return $this->dao->select('path,product')->from(TABLE_REPO)->where('deleted')->eq('0')
            ->andWhere('SCM')->eq('Gitlab')
            ->andWhere('path')->in($projectIDs)
            ->fetchPairs('path', 'product');
    }

    /**
     * 获取项目保护分支。
     * Get protect branches of one project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $keyword
     * @param  string $orderBy
     * @access public
     * @return array|object|null
     */
    public function apiGetBranchPrivs(int $gitlabID, int $projectID, string $keyword = '', string $orderBy = 'id_desc'): array|object|null
    {
        $keyword  = urlencode($keyword);
        $url      = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/protected_branches");
        $branches = json_decode(commonModel::http($url));

        if(!is_array($branches)) return $branches;
        /* Parse order string. */
        $order = explode('_', $orderBy);

        $newBranches = array();
        foreach($branches as $branch)
        {
            if(empty($keyword) || stristr($branch->name, $keyword)) $newBranches[$branch->{$order[0]}] = $branch;
        }

        if($order[1] == 'asc')  ksort($newBranches);
        if($order[1] == 'desc') krsort($newBranches);

        return $newBranches;
    }

    /**
     * 管理分支策略。
     * Manage branch privs.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  array  $protected
     * @access public
     * @return array
     */
    public function manageBranchPrivs(int $gitlabID, int $projectID, array $protected = array()): array
    {
        $data        = fixer::input('post')->get();
        $branches    = $data->name;
        $pushLevels  = $data->pushAccess;
        $mergeLevels = $data->mergeAccess;
        $failure     = array();

        /* Remove privs. */
        foreach($protected as $name => $branch)
        {
            if(!in_array($name, $branches))
            {
                $result = $this->apiDeleteBranchPriv($gitlabID, $projectID, $name);
                if($result and substr($result->message, 0, 2) != '20') $failure[] = $name;
            }
        }

        $priv = new stdClass();
        foreach($branches as $key => $name)
        {
            /* Process exists data. */
            if(isset($protected[$name]))
            {
                if($protected[$name]->pushAccess == $pushLevels[$key] and $protected[$name]->mergeAccess == $mergeLevels[$key]) continue;

                $result = $this->apiDeleteBranchPriv($gitlabID, $projectID, $name);
                if(isset($result->message) and substr($result->message, 0, 2) != '20') $failure[] = $name;
            }

            $priv->name               = $name;
            $priv->push_access_level  = $pushLevels[$key];
            $priv->merge_access_level = $mergeLevels[$key];
            $response = $this->apiCreateBranchPriv($gitlabID, $projectID, $priv);
            if(isset($response->message) and substr($response->message, 0, 2) != '20') $failure[] = $name;
        }
        return array_unique($failure);
    }

    /**
     * 通过api创建一个分支策略。
     * Create a gitab protect branch by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $priv
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateBranchPriv(int $gitlabID, int $projectID, object $priv): object|array|null|false
    {
        if(empty($gitlabID))   return false;
        if(empty($projectID))  return false;
        if(empty($priv->name)) return false;
        $priv->name = html_entity_decode($priv->name, ENT_QUOTES);
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/" . $projectID . '/protected_branches');
        return json_decode(commonModel::http($url, $priv));
    }

    /**
     * 通过api删除一个保护分支。
     * Delete a gitab protect branch by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $branch
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteBranchPriv(int $gitlabID, int $projectID, string $branch): object|array|null|false
    {
        if(empty($gitlabID)) return false;
        $branch  = urlencode($branch);
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/{$projectID}/protected_branches/{$branch}");
        return json_decode(commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * 管理标签策略。
     * Manage tag privs.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  array  $protected
     * @access public
     * @return array
     */
    public function manageTagPrivs(int $gitlabID, int $projectID, array $protected = array()): array
    {
        $data         = fixer::input('post')->get();
        $tags         = $data->name;
        $createLevels = $data->createAccess;

        $failure = array();

        /* Remove privs. */
        foreach($protected as $name => $tag)
        {
            if(!in_array($name, $tags))
            {
                $result = $this->apiDeleteTagPriv($gitlabID, $projectID, $name);
                if($result and substr($result->message, 0, 2) != '20') $failure[] = $name;
            }
        }

        $priv = new stdClass();
        foreach($tags as $key => $name)
        {
            /* Process exists data. */
            if(isset($protected[$name]))
            {
                if($protected[$name]->createAccess == $createLevels[$key]) continue;

                $result = $this->apiDeleteTagPriv($gitlabID, $projectID, $name);
                if(isset($result->message) and substr($result->message, 0, 2) != '20') $failure[] = $name;
            }

            $priv->name                = $name;
            $priv->create_access_level = $createLevels[$key];
            $response = $this->apiCreateTagPriv($gitlabID, $projectID, $priv);
            if(isset($response->message))
            {
                if(is_array($response->message) or (is_string($response->message) and substr($response->message, 0, 2) != '20')) $failure[] = $name;
            }
        }
        return array_unique($failure);
    }

    /**
     * 通过api创建一个保护分支。
     * Create a gitab protect tag by api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $priv
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateTagPriv(int $gitlabID, int $projectID, object $priv): object|array|null|false
    {
        if(empty($gitlabID) or empty($projectID)) return false;
        if(empty($priv->name)) return false;
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/" . $projectID . '/protected_tags');
        return json_decode(commonModel::http($url, $priv));
    }

    /**
     * 检查权限等级。
     * Check access level.
     *
     * @param  array $accessLevels
     * @access public
     * @return int
     */
    public function checkAccessLevel(array $accessLevels): int
    {
        if(is_array($accessLevels))
        {
            $levels = array();
            foreach($accessLevels as $level)
            {
                if(is_array($level)) $level = (object)$level;
                $levels[] = isset($level->access_level) ? (int)$level->access_level : $this->maintainerAccess;
            }
            if(in_array($this->noAccess, $levels)) return $this->noAccess;
            if(in_array($this->developerAccess, $levels)) return $this->developerAccess;
        }
        return $this->maintainerAccess;
    }

    /**
     * Get single branch by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tag
     * @access public
     * @return object|array|null|false
     */
    public function apiGetSingleTag(int $gitlabID, int $projectID, string $tag): object|array|null|false
    {
        if(empty($gitlabID)) return false;
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/repository/tags/$tag");
        return json_decode(commonModel::http($url));
    }

    /**
     * 创建一个gitlab标签。
     * Create gitlab tag.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $tag
     * @access public
     * @return bool
     */
    public function createTag(int $gitlabID, int $projectID, object $tag): bool
    {
        if(empty($gitlabID)) return false;

        if(empty($tag->tag_name)) dao::$errors['tag_name'][] = $this->lang->gitlab->tag->emptyNameError;
        if(empty($tag->ref))  dao::$errors['ref'][] = $this->lang->gitlab->tag->emptyRefError;
        if(dao::isError()) return false;

        $singleBranch = $this->apiGetSingleTag($gitlabID, $projectID, $tag->tag_name);
        if(!empty($singleBranch->name)) dao::$errors['tag_name'][] = $this->lang->gitlab->tag->issetNameError;
        if(dao::isError()) return false;

        $url      = sprintf($this->getApiRoot($gitlabID), "/projects/" . $projectID . '/repository/tags');
        $response = json_decode(commonModel::http($url, $tag));

        if(!empty($response->name))
        {
            $this->loadModel('action')->create('gitlabtag', 0, 'created', '', $response->name);
            return true;
        }

        return $this->apiErrorHandling($response);
    }

    /**
     * 检查用户权限。
     * Check user access.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $project
     * @param  array  $groupIDList
     * @param  string $maxRole
     * @access public
     * @return bool
     */
    public function checkUserAccess(int $gitlabID, int $projectID = 0, object $project = null, array $groupIDList = array(), string $maxRole = 'maintainer'): bool
    {
        if($this->app->user->admin) return true;

        if($project == null) $project = $this->apiGetSingleProject($gitlabID, $projectID);
        if(!isset($project->id)) return false;

        $accessLevel = $this->config->gitlab->accessLevel[$maxRole];

        if(isset($project->permissions->project_access->access_level) and $project->permissions->project_access->access_level >= $accessLevel) return true;
        if(isset($project->permissions->group_access->access_level) and $project->permissions->group_access->access_level >= $accessLevel) return true;
        if(!empty($project->shared_with_groups))
        {
            if(empty($groupIDList))
            {
                $groups = $this->apiGetGroups($gitlabID, 'name_asc', $maxRole);
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
     * 获取gitlab版本。
     * Get gitlab version.
     *
     * @param  string $host
     * @param  string $token
     * @access public
     * @return object|array|null
     */
    public function getVersion(string $host, string $token): object|array|null
    {
        $host = rtrim($host, '/') . "/api/v4%s?private_token=$token";
        return $this->apiGet($host, '/version');
    }

    /**
     * 检查token。
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return object|array|null|false
     */
    public function checkTokenAccess(string $url = '', string $token = ''): object|array|null|false
    {
        $apiRoot  = rtrim($url, '/') . '/api/v4%s' . "?private_token={$token}";
        $url      = sprintf($apiRoot, "/users") . "&per_page=5&active=true";
        $response = commonModel::http($url);
        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        $apiRoot .= '&sudo=' . $users[0]->id;
        return $this->apiGet($apiRoot, '/user');
    }

    /**
     * 通过api获取流水线。
     * Get pipeline with api.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $branch
     * @access public
     * @return object|array|null
     */
    public function apiGetPipeline(int $gitlabID, int $projectID, string $branch): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitlabID);
        $url     = sprintf($apiRoot, "/projects/$projectID/pipelines") . "&ref=$branch";
        return json_decode(commonModel::http($url));
    }

    /**
     * 更新版本库的代码地址。
     * Update repo code path.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function updateCodePath(int $gitlabID, int $projectID, int $repoID): bool
    {
        $project = $this->apiGetSingleProject($gitlabID, $projectID);
        if(is_object($project) and !empty($project->web_url))
        {
            $this->dao->update(TABLE_REPO)->set('path')->eq($project->web_url)->where('id')->eq($repoID)->exec();
            return true;
        }

        return false;
    }

    /**
     * 判断按钮是否可点击。
     * Adjust the action clickable.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $object, string $action): bool
    {
        if(!commonModel::hasPriv('space', 'browse')) return false;

        if($action == 'browseBranch') return $object->isDeveloper;
        if($action == 'browseTag') return $object->isDeveloper;
        if($action == 'manageProjectMembers') return $object->hasRepo;
        if($action == 'createWebhook') return $object->hasRepo;
        if($action == 'manageBranchPriv') return $object->defaultBranch;
        if($action == 'manageTagPriv') return $object->defaultBranch;
        if($action == 'editProject') return $object->defaultBranch;
        if($action == 'deleteProject') return $object->defaultBranch;

        if($action == 'editGroup') return $object->isAdmin;
        if($action == 'deleteGroup') return $object->isAdmin;

        if($action == 'editUser') return $object->isAdmin;
        if($action == 'deleteUser') return $object->isAdmin;

        if($action == 'deleteTag') return !$object->protected;

        return true;
    }

    /**
     * 判断按钮是否显示在列表页。
     * Judge an action is displayed in browse page.
     *
     * @param  object $sonarqube
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isDisplay(object $sonarqube, string $action): bool
    {
        $action = strtolower($action);

        if(!commonModel::hasPriv('space', 'browse')) return false;

        if(!in_array(strtolower(strtolower($action)), array('browseproject', 'browsegroup', 'browseuser', 'browsebranch', 'browsetag')))
        {
            if(!commonModel::hasPriv('instance', 'manage')) return false;
        }

        return true;
    }

    /**
     * 通过graphql的api获取数据。
     * Get data by api graphql.
     *
     * @param  object $repo
     * @param  string $query
     * @access public
     * @return object|array|null
     */
    public function apiGetByGraphql(object $repo, string $query): object|array|null
    {
        static $gitlab;
        if(empty($gitlab)) $gitlab = $this->getByID((int)$repo->serviceHost);
        if(!$gitlab) return array();

        /* Compatible with HTTP and HTTPS domain. */
        $repo->client   = str_replace('https://', 'http://', $repo->client);
        $repo->codePath = str_replace('https://', 'http://', $repo->codePath);

        $fullPath = trim(str_replace($repo->client, '', $repo->codePath), '/');
        $query    = sprintf($query, $fullPath);

        $url = rtrim($gitlab->url, '/') . '/api/graphql' . "?private_token={$gitlab->token}";
        return json_decode(commonModel::http($url, array('query' => $query), array(CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1)));
    }

    /**
     * 获取文件最后一次提交信息。
     * Get file last commit info.
     *
     * @param  int    $repo
     * @param  int    $path
     * @param  string $branch
     * @access public
     * @return object|array|null
     */
    public function getFileLastCommit(object $repo, string $path, string $branch = 'HEAD'): object|array|null
    {
        $query    = 'query {project(fullPath: "%s") {repository {tree(path: "' . trim($path, '/') . '", ref: "' . $branch . '") {lastCommit {sha message author {name username} authorName authoredDate}}}}}';
        $response = $this->apiGetByGraphql($repo, $query);
        if(!isset($response->data->project->repository->tree->lastCommit)) return null;

        return $response->data->project->repository->tree->lastCommit;
    }
}
