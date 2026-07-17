<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 2) . '/lib/model.class.php';

class ppmZenTest extends ppmBaseTest
{
    protected $moduleName = 'ppm';
    protected $className  = 'zen';

    public function getAllProjectsTest(object $repo, string $rawModule = 'ppm')
    {
        return $this->invokeMethod('getAllProjects', array($repo), $rawModule);
    }

    public function buildLinkStorySearchFormTest(int $ppmID, int $repoID, string $orderBy, int $queryID = 0, string $rawModule = 'ppm')
    {
        global $config;

        $result = $this->invokeMethod('buildLinkStorySearchForm', array($ppmID, $repoID, $orderBy, $queryID), $rawModule);
        if(is_array($result) || is_string($result)) return $result;

        return $config->product->search;
    }

    public function buildLinkBugSearchFormTest(int $ppmID, int $repoID, string $orderBy, int $queryID = 0, string $rawModule = 'ppm')
    {
        global $config;

        $result = $this->invokeMethod('buildLinkBugSearchForm', array($ppmID, $repoID, $orderBy, $queryID), $rawModule);
        if(is_array($result) || is_string($result)) return $result;

        return $config->bug->search;
    }

    public function buildLinkTaskSearchFormTest(int $ppmID, int $repoID, string $orderBy, int $queryID, array $productExecutions, string $rawModule = 'ppm')
    {
        global $config;

        $result = $this->invokeMethod('buildLinkTaskSearchForm', array($ppmID, $repoID, $orderBy, $queryID, $productExecutions), $rawModule);
        if(is_array($result) || is_string($result)) return $result;

        return $config->execution->search;
    }

    public function processLinkTaskPagerTest(int $recTotal, int $recPerPage, int $pageID, array $allTasks, string $rawModule = 'ppm')
    {
        $result = $this->invokeMethod('processLinkTaskPager', array($recTotal, $recPerPage, $pageID, $allTasks), $rawModule);
        if(is_array($result) || is_string($result)) return $result;

        return $this->instance->view;
    }

    public function parseCreateCheckMsgTest(object|bool $mergeCheckMessage, array $mergeRuleResult, string $sourceBranch, string $targetBranch)
    {
        return $this->invokeMethod('parseCreateCheckMsg', array($mergeCheckMessage, $mergeRuleResult, $sourceBranch, $targetBranch));
    }

    public function getCheckResultTest(object $ppm, string $reviewResult, array $issues = array(), string $rawModule = 'ppm')
    {
        return $this->invokeMethod('getCheckResult', array($ppm, $reviewResult, $issues), $rawModule);
    }
}
