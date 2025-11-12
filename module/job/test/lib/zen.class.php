<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

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
    public function getJobListTest($repoID = 0, $jobQuery = '', $orderBy = 'id_desc', $pager = null)
    {
        if($pager === null) $pager = new pager(0, 20, 1);
        $result = $this->invokeArgs('getJobList', [$repoID, $jobQuery, $orderBy, $pager]);
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
