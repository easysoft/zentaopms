<?php
declare(strict_types = 1);

/**
 * errorlog zen test class.
 */
class errorlogZenTest
{
    /**
     * Test buildSearchForm method.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access public
     * @return array
     */
    public function buildSearchFormTest(array $searchConfig, string|int $queryID = 0, string $actionURL = ''): array
    {
        global $tester, $app;

        helper::import($app->getModulePath('', 'errorlog') . 'control.php');
        helper::import($app->getModulePath('', 'errorlog') . 'zen.php');

        $zenTest = $app->loadTarget('errorlog', '', 'zen');
        $zenTest->errorlog = $tester->loadModel('errorlog');

        $reflection = new ReflectionClass($zenTest);
        $method     = $reflection->getMethod('buildSearchForm');
        $method->setAccessible(true);
        $method->invokeArgs($zenTest, array($searchConfig, $queryID, $actionURL));
        if(dao::isError()) return dao::getError();

        return isset($_SESSION['errorlogsearchParams']) ? $_SESSION['errorlogsearchParams'] : array();
    }

    /**
     * Test getErrorLogQuery method.
     *
     * @param  int    $queryID
     * @param  string $queryName
     * @access public
     * @return string|array
     */
    public function getErrorLogQueryTest(int $queryID = 0, string $queryName = 'errorlogQuery'): string|array
    {
        $result = callZenMethod('errorlog', 'getErrorLogQuery', array($queryID, $queryName));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
