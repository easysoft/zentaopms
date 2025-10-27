<?php
declare(strict_types = 1);
class sonarqubeZenTest
{
    public $sonarqubeZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('sonarqube');
        $tester->app->setMethodName('test');

        $this->objectModel = $tester->loadModel('sonarqube');
        $this->sonarqubeZenTest = initReference('sonarqube');
    }

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
        $result = callZenMethod('sonarqube', 'getIssueList', array($sonarqubeID, $projectKey));

        if(dao::isError()) return dao::getError();
        return $result;
    }
}