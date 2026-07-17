<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pipelineZenTest extends baseTest
{
    protected $moduleName = 'pipeline';
    protected $className  = 'zen';

    /**
     * Test getJenkinsPipelineList method.
     *
     * @param  int $providerID
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getJenkinsPipelineListTest(int $providerID, int $repoID = 0): array
    {
        $result = $this->invokeArgs('getJenkinsPipelineList', [$providerID, $repoID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildImportForm method.
     *
     * @param  int $repoID
     * @param  int $providerID
     * @access public
     * @return array
     */
    public function buildImportFormTest(int $repoID, int $providerID = 0): array
    {
        ob_start();
        $this->invokeArgs('buildImportForm', [$repoID, $providerID]);
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        return array(
            'repoID'            => isset($view->repoID) ? (int)$view->repoID : 0,
            'defaultProviderID' => isset($view->defaultProviderID) ? (int)$view->defaultProviderID : 0,
            'isJenkins'         => isset($view->isJenkins) ? ($view->isJenkins ? 1 : 0) : 0,
            'hidePipeline'      => isset($view->hidePipeline) ? ($view->hidePipeline ? 1 : 0) : 0,
            'defaultName'       => isset($view->defaultName) ? (string)$view->defaultName : '',
            'providersCount'    => isset($view->providers) ? count($view->providers) : 0,
            'pipelinesCount'    => isset($view->pipelines) ? count($view->pipelines) : 0,
        );
    }

    public function checkRepoEmptyTest(): void
    {
        $oldLevel = error_reporting(0);
        ob_start();
        try { $this->invokeArgs('checkRepoEmpty', []); } catch(Throwable $e) {}
        ob_end_clean();
        error_reporting($oldLevel);
    }

    public function buildSearchFormTest(array $searchConfig, string|int $queryID, string $actionURL)
    {
        $oldLevel = error_reporting(0);
        ob_start();
        try { $result = $this->invokeArgs('buildSearchForm', [$searchConfig, $queryID, $actionURL]); } catch(Throwable $e) { $result = ''; }
        ob_end_clean();
        error_reporting($oldLevel);
        return $result;
    }

    public function getPipelineSearchQueryTest(int $queryID): string
    {
        $result = $this->invokeArgs('getPipelineSearchQuery', [$queryID]);
        if(dao::isError()) return false;
        return $result;
    }

    public function getExistPipelinesTest(int $repoID = 0)
    {
        $result = $this->invokeArgs('getExistPipelines', [$repoID]);
        if(dao::isError()) return false;
        return $result;
    }
}
