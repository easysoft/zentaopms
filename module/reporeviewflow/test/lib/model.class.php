<?php
class reporeviewflowTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('reporeviewflow');
    }

    /**
     * Test isClickable method.
     *
     * @param  object $reviewFlow
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest($reviewFlow, $action)
    {
        $result = $this->objectModel->isClickable($reviewFlow, $action);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getReviewFlowList method.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function getListTest(int $repoID): array
    {
        $method = new ReflectionMethod($this->objectModel, 'getList');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $repoID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createReviewFlow method.
     *
     * @param  int    $repoID
     * @param  object $formData
     * @access public
     * @return object|array
     */
    public function createTest(int $repoID, object $formData): array|object
    {
        $formData->definition = '{"ai":{"enable":false,"approvals":{"score":0}},"reviewFlow":{"approvals":{"defaultReviewers":["test112801","xmjl01"],"specifiedReviewers":["xmjl01","test112801"],"minReviewers":2,"approvalID":0},"issues":{"addressOption":"specificMustBeSolved","mandatoryType":["codeerror","config"],"mergeOptions":"merge,squash,rebase,fast"},"newCommits":{"addressOption":"requireReReview"},"merge":{"options":["merge","squash","rebase","fast"],"autoArchive":true}}}';
        $method = new ReflectionMethod($this->objectModel, 'create');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $repoID, $formData);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_REVIEWFLOW)->where('id')->eq($result)->fetch();
    }

    /**
     * Test update method.
     *
     * @param  int    $flowID
     * @param  object $formData
     * @access public
     * @return object|array
     */
    public function updateTest(int $flowID, object $formData): array|object
    {
        $formData->definition = '{"ai":{"enable":false,"approvals":{"score":0}},"reviewFlow":{"approvals":{"defaultReviewers":["test112801","xmjl01"],"specifiedReviewers":["xmjl01","test112801"],"minReviewers":2,"approvalID":0},"issues":{"addressOption":"specificMustBeSolved","mandatoryType":["codeerror","config"],"mergeOptions":"merge,squash,rebase,fast"},"newCommits":{"addressOption":"requireReReview"},"merge":{"options":["merge","squash","rebase","fast"],"autoArchive":true}}}';
        $flow   = $this->objectModel->getByID($flowID);
        if(!$flow) return array();

        $method = new ReflectionMethod($this->objectModel, 'update');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $flow, $formData);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_REVIEWFLOW)->where('id')->eq($result)->fetch();
    }

    /**
     * Test getByID method.
     *
     * @param  int $flowID
     * @access public
     * @return object|array
     */
    public function getByID(int $flowID): array|object|false
    {
        $method = new ReflectionMethod($this->objectModel, 'getByID');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $flowID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateStatus method.
     *
     * @param  int    $flowID
     * @param  string $status
     * @access public
     * @return object|false
     */
    public function updateStatusTest($flowID, $status): object|false
    {
        $result = $this->objectModel->updateStatus($flowID, $status);
        if(!$result) return false;

        return $this->objectModel->dao->select('*')->from(TABLE_REVIEWFLOW)->where('id')->eq($flowID)->fetch();
    }

    public function getByBranchNameTest(int $repoID, string $branchName)
    {
        $result = $this->objectModel->getByBranchName($repoID, $branchName);
        if(dao::isError()) return false;
        return $result;
    }

    public function getPairsTest(int $repoID = 0, string $status = '')
    {
        $result = $this->objectModel->getPairs($repoID, $status);
        if(dao::isError()) return false;
        return $result;
    }
}
