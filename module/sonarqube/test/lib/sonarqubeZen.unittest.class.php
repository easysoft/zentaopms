<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class sonarqubeZenTest extends baseTest
{
    protected $moduleName = 'sonarqube';
    protected $className  = 'zen';

    /**
     * Test getIssueList method.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return mixed
     */
    public function getIssueListTest(int $sonarqubeID, string $projectKey = '')
    {
        $result = $this->invokeArgs('getIssueList', array($sonarqubeID, $projectKey));

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkTokenRequire method.
     *
     * @param  object $sonarqube
     * @access public
     * @return mixed
     */
    public function checkTokenRequireTest(object $sonarqube)
    {
        $this->invokeArgs('checkTokenRequire', array($sonarqube));

        if(dao::isError()) return dao::getError();
        return 'success';
    }
}