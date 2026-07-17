<?php
class repobranchruleTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('repobranchrule');
    }

    public function checkPrivToCreateBranchTest(int $repoID, string $branchName, string $operator)
    {
        $result = $this->objectModel->checkPrivToCreateBranch($repoID, $branchName, $operator);
        if(dao::isError()) return false;
        return $result;
    }

    public function checkPrivToDeleteBranchTest(int $repoID, string $branchName, string $operator)
    {
        $result = $this->objectModel->checkPrivToDeleteBranch($repoID, $branchName, $operator);
        if(dao::isError()) return false;
        return $result;
    }

    public function getBranchRulePairsTest(int $repoID, string $key, string $operate)
    {
        $result = $this->objectModel->getBranchRulePairs($repoID, $key, $operate);
        if(dao::isError()) return '';
        return empty($result) ? '0' : implode(',', $result);
    }

    public function getBranchRuleTest(int $typeID = 0, int $repoID = 0, string $branchName = '')
    {
        $result = $this->objectModel->getBranchRule($typeID, $repoID, $branchName);
        if(dao::isError()) return false;
        return $result;
    }

    public function createBranchRuleTest(object $rule)
    {
        $result = $this->objectModel->createBranchRule($rule);
        if(dao::isError()) return false;
        if($result)
        {
            $lastID = $this->objectModel->dao->lastInsertID();
            return $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($lastID)->fetch();
        }
        return false;
    }

    public function updateBranchRuleTest(int $id, object $rule)
    {
        $beforeUpdate = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
        if(!$beforeUpdate) return '0';
        $result = $this->objectModel->updateBranchRule($id, $rule);
        if(dao::isError()) return false;
        if($result)
        {
            $afterUpdate = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
            return $afterUpdate ? $afterUpdate : '0';
        }
        return '0';
    }

    public function deleteBranchRuleTest(int $id)
    {
        $beforeDelete = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
        if(!$beforeDelete) return '0';
        $result = $this->objectModel->deleteBranchRule($id);
        if(dao::isError()) return false;
        if($result)
        {
            $afterDelete = $this->objectModel->dao->select('*')->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->fetch();
            return $afterDelete ? '0' : '1';
        }
        return '0';
    }

    public function getListTest(int $repoID, array $branchTypes = [], array $branchNames = [])
    {
        $result = $this->objectModel->getList($repoID, $branchTypes, $branchNames);
        if(dao::isError()) return false;
        return $result;
    }

    public function getRuleByBranchNameTest(int $repoID, string $branchName)
    {
        $result = $this->objectModel->getRuleByBranchName($repoID, $branchName);
        if(dao::isError()) return false;
        return $result;
    }
}
