<?php
class repobranchruleTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('repobranchrule');
    }

    /**
     * Test checkPrivToCreateBranch method.
     *
     * @param  int    $repoID
     * @param  string $branchName
     * @param  string $operator
     * @access public
     * @return bool
     */
    public function checkPrivToCreateBranchTest(int $repoID, string $branchName, string $operator): bool
    {
        $result = $this->objectModel->checkPrivToCreateBranch($repoID, $branchName, $operator);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkPrivToDeleteBranch method.
     *
     * @param  int    $repoID
     * @param  string $branchName
     * @param  string $operator
     * @access public
     * @return bool
     */
    public function checkPrivToDeleteBranchTest(int $repoID, string $branchName, string $operator): bool
    {
        $result = $this->objectModel->checkPrivToDeleteBranch($repoID, $branchName, $operator);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBranchRulePairs method.
     *
     * @param  int    $repoID
     * @param  string $key
     * @param  string $operate
     * @access public
     * @return string
     */
    public function getBranchRulePairsTest(int $repoID, string $key, string $operate): string
    {
        $result = $this->objectModel->getBranchRulePairs($repoID, $key, $operate);
        if(dao::isError()) return dao::getError();

        return empty($result) ? '0' : implode(',', $result);
    }

        /**
     * Test getBranchRule method.
     *
     * @param  int    $typeID
     * @param  int    $repoID
     * @param  string $branchName
     * @access public
     * @return mixed
     */
    public function getBranchRuleTest(int $typeID = 0, int $repoID = 0, string $branchName = '')
    {
        $result = $this->objectModel->getBranchRule($typeID, $repoID, $branchName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createBranchRule method.
     *
     * @param  object $rule
     * @access public
     * @return mixed
     */
    public function createBranchRuleTest(object $rule)
    {
        $result = $this->objectModel->createBranchRule($rule);
        if(dao::isError()) return dao::getError();

        if($result)
        {
            $lastID = $this->objectModel->dao->lastInsertID();
            return $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($lastID)->fetch();
        }

        return false;
    }

    /**
     * Test updateBranchRule method.
     *
     * @param  int    $id
     * @param  object $rule
     * @access public
     * @return mixed
     */
    public function updateBranchRuleTest(int $id, object $rule)
    {
        $beforeUpdate = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
        if(!$beforeUpdate) return '0';

        $result = $this->objectModel->updateBranchRule($id, $rule);
        if(dao::isError()) return dao::getError();

        if($result)
        {
            $afterUpdate = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
            return $afterUpdate ? $afterUpdate : '0';
        }

        return '0';
    }

    /**
     * Test deleteBranchRule method.
     *
     * @param  int $id
     * @access public
     * @return mixed
     */
    public function deleteBranchRuleTest(int $id)
    {
        $beforeDelete = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
        if(!$beforeDelete) return '0';

        $result = $this->objectModel->deleteBranchRule($id);
        if(dao::isError()) return dao::getError();

        if($result)
        {
            $afterDelete = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
            return $afterDelete ? '0' : '1';
        }

        return '0';
    }
}