<?php
class gitlabTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->gitlab = $this->tester->loadModel('gitlab');
    }

    /**
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $gitlab = $this->gitlab->getByID($id);
        if(empty($gitlab)) return 0;
        return $gitlab;
    }

    /**
     * Get gitlab list.
     *
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getList($orderBy = 'id_desc')
    {
        $gitlab = $this->gitlab->getList($orderBy);
        if(empty($gitlab)) return 0;
        return $gitlab;
    }

    /**
     * Get gitlab pairs
     *
     * @return string
     */
    public function getPairs()
    {
        $pairs = $this->gitlab->getPairs();
        return $pairs;
    }

    public function getApiRootTest(int $gitlabID, bool $sudo = true)
    {
        return $this->gitlab->getApiRoot($gitlabID, $sudo);
    }

    public function getUserIdAccountPairsTest(int $gitlabID)
    {
        return $this->gitlab->getUserIdAccountPairs($gitlabID);
    }

    public function getUserAccountIdPairsTest(int $gitlabID)
    {
        return $this->gitlab->getUserAccountIdPairs($gitlabID);
    }

    public function getUserIDByZentaoAccountTest(int $gitlabID, string $zentaoAccount)
    {
        return $this->gitlab->getUserIDByZentaoAccount($gitlabID, $zentaoAccount);
    }

    public function getProjectPairsTest(int $gitlabID)
    {
        return $this->gitlab->getProjectPairs($gitlabID);
    }

    public function getMatchedUsersTest(int $gitlabID, array $gitlabUsers = array(), array $zentaoUsers = array())
    {
        return $this->gitlab->getMatchedUsers($gitlabID, $gitlabUsers, $zentaoUsers);
    }

    public function getRelationByObjectTest(string $objectType, int $objectID)
    {
        return $this->gitlab->getRelationByObject($objectType, $objectID);
    }

    public function getIssueListByObjectsTest(string $objectType, array $objects)
    {
        return $this->gitlab->getIssueListByObjects($objectType, $objects);
    }

    public function getProjectNameTest(int $gitlabID, int $projectID)
    {
        return $this->gitlab->getProjectName($gitlabID, $projectID);
    }

    public function getBranchesTest(int $gitlabID, int $projectID)
    {
        return $this->gitlab->getBranches($gitlabID, $projectID);
    }

    public function getReferenceOptionsTest(int $gitlabID, int $projectID)
    {
        return $this->gitlab->getReferenceOptions($gitlabID, $projectID);
    }

    public function setProjectTest(int $gitlabID, int $projectID, object $project)
    {
        $this->gitlab->setProject($gitlabID, $projectID, $project);
        return $this->gitlab->apiGetSingleProject($gitlabID, $projectID);
    }

    /**
     * Create a gitlab.
     *
     * @param  array $gitlab
     * @access public
     * @return object|string
     */
    public function create(array $gitlab)
    {
        $gitlabID = $this->gitlab->create((object)$gitlab);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gitlab->getById($gitlabID);
    }

    /**
     * Update a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return object|string
     */
    public function update($id)
    {
        $this->gitlab->update($id);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gitlab->getById($id);
    }

    /**
     * Manage branch privs.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  array  $hasAccessBranches
     * @access public
     * @return string
     */
    public function manageBranchPrivsTest($gitlabID, $projectID, $hasAccessBranches = array())
    {
        $result = $this->gitlab->manageBranchPrivs($gitlabID, $projectID, $hasAccessBranches);
        return empty($result) ? 'success' : 'fail';
    }

    /**
     * Manage tag privs.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  array  $hasAccessTags
     * @access public
     * @return string
     */
    public function manageTagPrivsTest($gitlabID, $projectID, $hasAccessTags = array())
    {
        $result = $this->gitlab->manageTagPrivs($gitlabID, $projectID, $hasAccessTags);
        return empty($result) ? 'success' : 'fail';
    }

    /**
     * Api get signle tag.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tag
     * @access public
     * @return object
     */
    public function apiGetSingleTagTest($gitlabID, $projectID, $tag)
    {
        return $this->gitlab->apiGetSingleTag($gitlabID, $projectID, $tag);
    }

    /**
     * Api get signle branch.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $branch
     * @access public
     * @return object
     */
    public function apiGetSingleBranchTest($gitlabID, $projectID, $branch)
    {
        return $this->gitlab->apiGetSingleBranch($gitlabID, $projectID, $branch);
    }

    /**
     * Api get signle user.
     *
     * @param  int    $gitlabID
     * @param  int    $userID
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleUserTest(int $gitlabID, int $userID): object|array|null
    {
        return $this->gitlab->apiGetSingleUser($gitlabID, $userID);
    }

    /**
     * Api get signle group.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleGroupTest(int $gitlabID, int $groupID): object|array|null
    {
        return $this->gitlab->apiGetSingleGroup($gitlabID, $groupID);
    }

    /**
     * Api get signle project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tag
     * @access public
     * @return object
     */
    public function apiGetSingleProjectTest(int $gitlabID, int $projectID, bool $useUser = true)
    {
        return $this->gitlab->apiGetSingleProject($gitlabID, $projectID, $useUser);
    }

    /**
     * Api get signle issue.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $issueID
     * @access public
     * @return object
     */
    public function apiGetSingleIssueTest(int $gitlabID, int $projectID, int $issueID)
    {
        return $this->gitlab->apiGetSingleIssue($gitlabID, $projectID, $issueID);
    }

    public function addPushWebhookTest(int $repoID, string $token, int $projectID = 0)
    {
        $repo = $this->tester->loadModel('repo')->getByID($repoID);
        if($projectID) $repo->project = $projectID;

        $result = $this->gitlab->addPushWebhook($repo, $token);
        if(is_array($result)) $result = false;
        return $result;
    }

    public function isWebhookExistsTest(int $repoID, string $url = '')
    {
        $repo = $this->tester->loadModel('repo')->getByID($repoID);
        return $this->gitlab->isWebhookExists($repo, $url);
    }

    public function getCommitsTest(int $repoID, string $entry = '', object $pager = null, string $begin = '', string $end = '')
    {
        $repo = $this->tester->loadModel('repo')->getByID($repoID);
        return $this->gitlab->getCommits($repo, $entry, $pager, $begin, $end);
    }

    public function deleteIssueTest(string $objectType, int $objectID, int $issueID)
    {
        $this->gitlab->deleteIssue($objectType, $objectID, $issueID);
        $relation = $this->gitlab->getRelationByObject($objectType, $objectID);
        return $relation ? false : true;
    }

    public function createZentaoObjectLabelTest(int $gitlabID, int $projectID, string $objectType, string $objectID)
    {
        return $this->gitlab->createZentaoObjectLabel($gitlabID, $projectID, $objectType, $objectID);
    }

    public function webhookCheckTokenTest()
    {
        ob_start();
        $this->gitlab->webhookCheckToken();
        return ob_get_clean();
    }

    public function saveIssueRelationTest(string $objectType, int $gitlabID, int $issueID, int $projectID)
    {
        $issue = $issueID ? $this->gitlab->apiGetSingleIssue($gitlabID, $projectID, $issueID) : new stdclass();

        $object = new stdclass();
        $object->id        = 18;
        $object->product   = 1;
        $object->execution = 1;

        $result = $this->gitlab->saveIssueRelation($objectType, $object, $gitlabID, $issue);
        return $result ? $this->gitlab->getRelationByObject($objectType, $object->id) : $result;
    }

    public function createUserTest(int $gitlabID, object $gitlabUser)
    {
        $result = $this->gitlab->createUser($gitlabID, $gitlabUser);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function editUserTest(int $gitlabID, object $gitlabUser)
    {
        $result = $this->gitlab->editUser($gitlabID, $gitlabUser);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function createProjectTest(int $gitlabID, object $project)
    {
        $result = $this->gitlab->createProject($gitlabID, $project);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function editProjectTest(int $gitlabID, object $project)
    {
        $result = $this->gitlab->editProject($gitlabID, $project);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function createGroupTest(int $gitlabID, object $project)
    {
        $result = $this->gitlab->createGroup($gitlabID, $project);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function editGroupTest(int $gitlabID, object $project)
    {
        $result = $this->gitlab->editGroup($gitlabID, $project);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function apiErrorHandlingTest(object $response)
    {
        $result = $this->gitlab->apiErrorHandling($response);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function updateCodePathTest(int $gitlabID, int $projectID, int $repoID)
    {
        $result = $this->gitlab->updateCodePath($gitlabID, $projectID, $repoID);

        if($result)
        {
            return $this->gitlab->loadModel('repo')->getByID($repoID);
        }

        return $result;
    }

    public function apiUpdateHookTest(int $gitlabID, int $projectID, int $hookID, object $hook)
    {
        $result = $this->gitlab->apiUpdateHook($gitlabID, $projectID, $hookID, $hook);

        return json_decode($result);
    }

    /**
     * Test apiGetByGraphql method.
     *
     * @param  string $query
     * @access public
     * @return object|null|array
     */
    public function apiGetByGraphqlTest(string $query): object|null|array
    {
        $repo   = $this->gitlab->loadModel('repo')->getByID(1);
        $result = $this->gitlab->apiGetByGraphql($repo, $query);
        if(!$result) return $result;
        if(isset($result->errors)) return array_column($result->errors, 'message');
        return $result->data->project->repository->tree;
    }

    /**
     * Test getFileLastCommit method.
     *
     * @param  string $path
     * @param  string $branch
     * @access public
     * @return object|null|array
     */
    public function getFileLastCommitTest(string $path, string $branch): object|array|null
    {
        $repo   = $this->gitlab->loadModel('repo')->getByID(1);
        $result = $this->gitlab->getFileLastCommit($repo, $path, $branch);
        if(!$result) return $result;
        if(isset($result->errors)) return array_column($result->errors, 'message');
        return $result;
    }

    /**
     * Test webhookSyncIssue method.
     *
     * @param  object  $issue
     * @access public
     * @return array|object|false
     */
    public function webhookSyncIssueTest(object $issue): array|object|false
    {
        $result = $this->gitlab->webhookSyncIssue($issue);
        if(dao::isError()) return dao::getError();
        return $result ? $this->gitlab->loadModel($issue->objectType)->getByID($issue->objectID) : false;
    }

    public function apiGetTest(int|string $host, string $api)
    {
        $result = $this->gitlab->apiGet($host, $api);

        if(is_null($result)) return 'return null';
        if(isset($result->id)) return 'success';
        return $result;
    }

    public function apiPostTest(int|string $host, string $api, array|object $data = array(), array $options = array())
    {
        $result = $this->gitlab->apiGet($host, $api, $data, $options);

        if(is_null($result)) return 'return null';
        if(isset($result->name)) return 'success';
        return $result;
    }

    /**
     * Test webhookAssignIssue method.
     *
     * @param  object  $issue
     * @access public
     * @return array|object|false
     */
    public function webhookAssignIssueTest(object $issue): array|object|false
    {
        $result = $this->gitlab->webhookAssignIssue($issue);
        if(dao::isError()) return dao::getError();
        return $result ? $this->gitlab->loadModel($issue->objectType)->getByID($issue->objectID) : false;
    }
}
