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

    /**
     * Test buildJenkinsTree method.
     *
     * @param  array $tasks
     * @access public
     * @return array
     */
    public function buildJenkinsTreeTest(array $tasks): array
    {
        $result = $this->invokeArgs('buildJenkinsTree', [$tasks]);
        if(dao::isError()) return dao::getError();

        return array(
            'count'         => count($result),
            'firstText'     => $result[0]['text'] ?? '',
            'firstValue'    => $result[0]['value'] ?? '',
            'firstType'     => $result[0]['type'] ?? '',
            'firstChildren' => isset($result[0]['items']) ? count($result[0]['items']) : 0,
        );
    }
}

class jobZenTest extends baseTest
{
    protected $moduleName = 'job';
    protected $className  = 'zen';

    /**
     * Test buildSearchForm method.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest($searchConfig = array(), $queryID = '', $actionURL = '')
    {
        $result = $this->invokeArgs('buildSearchForm', [$searchConfig, $queryID, $actionURL]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCompileData method.
     *
     * @param  object $compile
     * @access public
     * @return array
     */
    public function getCompileDataTest($compile)
    {
        $this->invokeArgs('getCompileData', [$compile]);
        if(dao::isError()) return array('error' => dao::getError());

        $view = $this->getProperty('view');
        if(!$view) return array('error' => 'View not found');

        $result = array(
            'groupCases' => isset($view->groupCases) ? count($view->groupCases) : 0,
            'suites'     => isset($view->suites) ? count($view->suites) : 0,
            'summary'    => isset($view->summary) ? count($view->summary) : 0,
            'taskID'     => isset($view->taskID) ? $view->taskID : 0
        );

        return $result;
    }

    /**
     * Test getJobList method.
     *
     * @param  int    $repoID
     * @param  string $jobQuery
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getJobListTest($spaceID = 0, $repoID = 0, $jobQuery = '', $orderBy = 'id_desc', $pager = null)
    {
        if($pager === null) $pager = new pager(0, 20, 1);
        $result = $this->invokeArgs('getJobList', [$spaceID, $repoID, $jobQuery, $orderBy, $pager]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getJobSearchQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getJobSearchQueryTest($queryID = 0)
    {
        $result = $this->invokeArgs('getJobSearchQuery', [$queryID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getSubversionDir method.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getSubversionDirTest($repo)
    {
        $this->invokeArgs('getSubversionDir', [$repo]);
        if(dao::isError()) return array('error' => dao::getError());

        $view = $this->getProperty('view');
        if(!$view) return array('error' => 'View not found');

        $result = array(
            'dirs'            => isset($view->dirs) ? $view->dirs : array(),
            'triggerTypeList' => $this->getProperty('lang')->job->triggerTypeList
        );

        return $result;
    }

    /**
     * Test reponseAfterCreateEdit method.
     *
     * @param  int    $repoID
     * @param  array  $errors
     * @param  string $engine
     * @param  int    $repo
     * @access public
     * @return array
     */
    public function reponseAfterCreateEditTest($repoID = 0, $errors = array(), $engine = '', $repo = 0)
    {
        global $tester;

        if(!empty($errors)) dao::$errors = $errors;
        if($engine) $_POST['engine'] = $engine;
        if($repo) $_POST['repo'] = $repo;

        $result = $this->invokeArgs('reponseAfterCreateEdit', [$repoID]);

        dao::$errors = array();
        unset($_POST['engine']);
        unset($_POST['repo']);

        return $result;
    }
}
