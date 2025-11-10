<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class mrZenTest extends baseTest
{
    protected $moduleName = 'mr';
    protected $className  = 'zen';

    public function buildLinkTaskSearchFormTest(int $MRID, int $repoID, string $orderBy, int $queryID, array $productExecutions)
    {
        $this->invokeArgs('buildLinkTaskSearchForm', [$MRID, $repoID, $orderBy, $queryID, $productExecutions]);
        return empty($_SESSION['mrTasksearchParams']) ? [] : $_SESSION['mrTasksearchParams'];
    }

    /**
     * Test assignEditData method.
     *
     * @param  object $MR
     * @param  string $scm
     * @access public
     * @return object
     */
    public function assignEditDataTest(object $MR, string $scm): object
    {
        // 调用被测方法,捕获输出和异常
        $bufferLevel = ob_get_level();
        ob_start();
        try
        {
            $this->invokeArgs('assignEditData', [$MR, $scm]);
        }
        catch(Throwable $e)
        {
            // display() 方法可能抛出异常,这是正常的
        }
        while(ob_get_level() > $bufferLevel) ob_end_clean();

        // 返回view对象用于验证
        $result = new stdclass();
        $result->title = $this->instance->view->title ?? '';
        $result->MR = $this->instance->view->MR ?? null;
        $result->users = $this->instance->view->users ?? [];
        $result->jobList = $this->instance->view->jobList ?? [];
        $result->branches = $this->instance->view->branches ?? [];
        $result->sourceProject = $this->instance->view->sourceProject ?? '';
        $result->targetProject = $this->instance->view->targetProject ?? '';
        $result->repo = $this->instance->view->repo ?? null;

        return $result;
    }

    /**
     * Test buildLinkBugSearchForm method.
     *
     * @param  int    $MRID
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function buildLinkBugSearchFormTest(int $MRID, int $repoID, string $orderBy, int $queryID = 0): array
    {
        $this->invokeArgs('buildLinkBugSearchForm', [$MRID, $repoID, $orderBy, $queryID]);

        global $config;
        $result = array();
        $result['actionURL'] = $config->bug->search['actionURL'] ?? '';
        $result['queryID']   = $config->bug->search['queryID'] ?? 0;
        $result['style']     = $config->bug->search['style'] ?? '';
        $result['hasProduct']        = isset($config->bug->search['fields']['product']) ? 1 : 0;
        $result['hasPlan']           = isset($config->bug->search['fields']['plan']) ? 1 : 0;
        $result['hasModule']         = isset($config->bug->search['fields']['module']) ? 1 : 0;
        $result['hasExecution']      = isset($config->bug->search['fields']['execution']) ? 1 : 0;
        $result['hasOpenedBuild']    = isset($config->bug->search['fields']['openedBuild']) ? 1 : 0;
        $result['hasResolvedBuild']  = isset($config->bug->search['fields']['resolvedBuild']) ? 1 : 0;
        $result['hasBranch']         = isset($config->bug->search['fields']['branch']) ? 1 : 0;

        return $result;
    }

    /**
     * Test buildLinkStorySearchForm method.
     *
     * @param  int    $MRID
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function buildLinkStorySearchFormTest(int $MRID, int $repoID, string $orderBy, int $queryID = 0): array
    {
        $this->invokeArgs('buildLinkStorySearchForm', [$MRID, $repoID, $orderBy, $queryID]);

        global $config;
        $result = array();
        $result['actionURL']  = $config->product->search['actionURL'] ?? '';
        $result['queryID']    = $config->product->search['queryID'] ?? 0;
        $result['style']      = $config->product->search['style'] ?? '';
        $result['hasProduct'] = isset($config->product->search['fields']['product']) ? 1 : 0;
        $result['hasPlan']    = isset($config->product->search['fields']['plan']) ? 1 : 0;
        $result['hasModule']  = isset($config->product->search['fields']['module']) ? 1 : 0;
        $result['hasBranch']  = isset($config->product->search['fields']['branch']) ? 1 : 0;
        $result['hasGrade']   = isset($config->product->search['fields']['grade']) ? 1 : 0;
        $statusValues = $config->product->search['params']['status']['values'] ?? array();
        $result['hasClosed']  = isset($statusValues['closed']) ? 1 : 0;

        return $result;
    }

    /**
     * Test buildLinkTaskSearchForm method.
     *
     * @param  int    $MRID
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $queryID
     * @param  array  $productExecutions
     * @access public
     * @return array
     */
    public function buildLinkTaskSearchFormTest(int $MRID, int $repoID, string $orderBy, int $queryID, array $productExecutions): array
    {
        $this->invokeArgs('buildLinkTaskSearchForm', [$MRID, $repoID, $orderBy, $queryID, $productExecutions]);

        global $config;
        $result = array();
        $result['actionURL']  = $config->execution->search['actionURL'] ?? '';
        $result['queryID']    = $config->execution->search['queryID'] ?? 0;
        $result['hasModule']  = isset($config->execution->search['fields']['module']) ? 1 : 0;
        $result['executionValues'] = $config->execution->search['params']['execution']['values'] ?? array();

        return $result;
    }
}
