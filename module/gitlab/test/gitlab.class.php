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

    public function getUserIdRealnamePairsTest(int $gitlabID)
    {
        return $this->gitlab->getUserIdRealnamePairs($gitlabID);
    }

    public function getUserIdAccountPairsTest(int $gitlabID)
    {
        return $this->gitlab->getUserIdAccountPairs($gitlabID);
    }

    public function getUserAccountIdPairsTest(int $gitlabID)
    {
        return $this->gitlab->getUserAccountIdPairs($gitlabID);
    }

    public function getUserIDByZentaoAccountTest(int $gitlab, string $zentaoAccount)
    {
        return $this->gitlab->getUserIDByZentaoAccount($gitlab, $zentaoAccount);
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
     * Get gitlab menu.
     *
     * @param  int    $gitlabID
     * @param  string $type
     * @access public
     * @return void
     */
    public function getGitlabMenuTest($gitlabID, $type = 'project')
    {
        $html = $this->gitlab->getGitlabMenu($gitlabID, $type);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return substr($html, 0, 4);
    }
}
