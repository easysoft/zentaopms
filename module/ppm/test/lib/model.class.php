<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

abstract class ppmBaseTest extends baseTest
{
    protected function invokeMethod(string $method, array $args = array(), string $rawModule = '')
    {
        global $app;

        dao::$errors = array();

        $oldRawModule  = $app->rawModule;
        $oldModuleName = $app->getModuleName();
        $oldMethodName = (string)$app->getMethodName();

        if($rawModule)
        {
            $app->rawModule = $rawModule;
            $app->setModuleName($rawModule);
        }

        try
        {
            $result = $this->invokeArgs($method, $args);
        }
        catch(Throwable $error)
        {
            $result = $error->getMessage();
        }

        if($rawModule)
        {
            $app->rawModule = $oldRawModule;
            $app->setModuleName($oldModuleName);
            $app->setMethodName($oldMethodName ?: 'browse');
        }

        return dao::isError() ? dao::getError() : $result;
    }
}

class ppmModelTest extends ppmBaseTest
{
    protected $moduleName = 'ppm';
    protected $className  = 'model';

    public function __constructTest(string $rawModule = 'ppm'): string
    {
        global $app;

        $oldRawModule  = $app->rawModule;
        $oldModuleName = $app->getModuleName();
        $oldMethodName = (string)$app->getMethodName();

        $app->rawModule = $rawModule;
        $app->setModuleName($rawModule);

        $className = get_class($this->instance);
        $model     = new $className();

        $app->rawModule = $oldRawModule;
        $app->setModuleName($oldModuleName);
        $app->setMethodName($oldMethodName ?: 'browse');

        return $model->moduleName;
    }

    public function getListTest(string $mode = 'all', string $param = 'all', string $orderBy = 'id_desc', array $filterProjects = array(), int $repoID = 0, int $objectID = 0, ?object $pager = null)
    {
        return $this->invokeMethod('getList', array($mode, $param, $orderBy, $filterProjects, $repoID, $objectID, $pager));
    }

    public function getPairsTest(int $repoID)
    {
        return $this->invokeMethod('getPairs', array($repoID));
    }

    public function createTest(object $ppm)
    {
        return $this->invokeMethod('create', array($ppm));
    }

    public function updateTest(int $id, object $ppm)
    {
        return $this->invokeMethod('update', array($id, $ppm));
    }

    public function apiGetMRCommitsTest(int $targetRepoID, int $ppmID, ?object $pager = null)
    {
        return $this->invokeMethod('apiGetMRCommits', array($targetRepoID, $ppmID, $pager));
    }

    public function getDiffsTest(object $ppm)
    {
        return $this->invokeMethod('getDiffs', array($ppm));
    }

    public function reviewTest(int $ppmID, object $formData, string $account = '')
    {
        return $this->invokeMethod('review', array($ppmID, $formData, $account));
    }

    public function closeTest(int $ppmID)
    {
        return $this->invokeMethod('close', array($ppmID));
    }

    public function reopenTest(int $ppmID)
    {
        return $this->invokeMethod('reopen', array($ppmID));
    }

    public function getLinkListTest(int $ppmID, string $type, string $orderBy = 'id_desc', ?object $pager = null)
    {
        return $this->invokeMethod('getLinkList', array($ppmID, $type, $orderBy, $pager));
    }

    public function getLinkedMRPairsTest(int $objectID, string $objectType = 'story', string $module = '')
    {
        return $this->invokeMethod('getLinkedMRPairs', array($objectID, $objectType, $module));
    }

    public function linkTest(int $ppmID, string $type, array $objects)
    {
        return $this->invokeMethod('link', array($ppmID, $type, $objects));
    }

    public function linkObjectsTest(object $ppm)
    {
        return $this->invokeMethod('linkObjects', array($ppm));
    }

    public function unlinkTest(int $ppmID, string $type, int $objectID)
    {
        return $this->invokeMethod('unlink', array($ppmID, $type, $objectID));
    }

    public function getMRProductTest(object $mr)
    {
        return $this->invokeMethod('getMRProduct', array($mr));
    }

    public function getToAndCcListTest(object $mr)
    {
        return $this->invokeMethod('getToAndCcList', array($mr));
    }

    public function logMergedActionTest(object $mr)
    {
        return $this->invokeMethod('logMergedAction', array($mr));
    }

    public function checkSameOpenedTest(int $repoID, int $sourceRepoID, string $sourceBranch, int $targetRepoID, string $targetBranch)
    {
        return $this->invokeMethod('checkSameOpened', array($repoID, $sourceRepoID, $sourceBranch, $targetRepoID, $targetBranch));
    }

    public function convertApiErrorTest(array|string $message)
    {
        return $this->invokeMethod('convertApiError', array($message));
    }

    public function isClickableTest(object $ppm, string $action): bool
    {
        return ppmModel::isClickable($ppm, $action);
    }

    public function insertMrTest(object $ppm)
    {
        return $this->invokeMethod('insertMr', array($ppm));
    }

    public function getCommitListByBranchTest(object $repo, string $sourceBranch, string $targetBranch, ?object $pager = null)
    {
        return $this->invokeMethod('getCommitListByBranch', array($repo, $sourceBranch, $targetBranch, $pager));
    }

    public function getRelationByBranchTest(object $repo, string $sourceBranch, string $targetBranch, string $type = '', ?object $pager = null)
    {
        return $this->invokeMethod('getRelationByBranch', array($repo, $sourceBranch, $targetBranch, $type, $pager));
    }

    public function getReviewersTest(int $ppmID)
    {
        return $this->invokeMethod('getReviewers', array($ppmID));
    }

    public function addReviewersTest(object $ppm, array $reviewers)
    {
        return $this->invokeMethod('addReviewers', array($ppm, $reviewers));
    }

    public function deleteReviewerTest(int $ppmID, string $reviewer)
    {
        return $this->invokeMethod('deleteReviewer', array($ppmID, $reviewer));
    }

    public function getReviewResultTest(array|object $reviewers, array|object $flow)
    {
        return $this->invokeMethod('getReviewResult', array($reviewers, $flow));
    }

    public function getReviewResultsTest(array $ppmList, int $repoID)
    {
        return $this->invokeMethod('getReviewResults', array($ppmList, $repoID));
    }

    public function mergeTest(int $ppmID, string $mergeType, bool $dryRun = false, bool $byPass = false)
    {
        return $this->invokeMethod('merge', array($ppmID, $mergeType, $dryRun, $byPass));
    }

    public function checkMergeRuleTest(int $repoID, string $sourceBranch, string $targetBranch)
    {
        return $this->invokeMethod('checkMergeRule', array($repoID, $sourceBranch, $targetBranch));
    }

    public function apiTriggerEventTest(int $repoID, int $ppmID, string $type)
    {
        return $this->invokeMethod('apiTriggerEvent', array($repoID, $ppmID, $type));
    }

    public function getPipelinesByPPMTest(object $ppm)
    {
        return $this->invokeMethod('getPipelinesByPPM', array($ppm));
    }

    public function getBugsByCommitsTest(int $repoID, int $ppmID, ?object $pager = null)
    {
        return $this->invokeMethod('getBugsByCommits', array($repoID, $ppmID, $pager));
    }

    public function createMRLinkedActionTest(int $id, string $action, string $actionDate = '')
    {
        return $this->invokeMethod('createMRLinkedAction', array($id, $action, $actionDate));
    }

    public function execJobTest(int $ppmID, int $jobID)
    {
        return $this->invokeMethod('execJob', array($ppmID, $jobID));
    }
}
