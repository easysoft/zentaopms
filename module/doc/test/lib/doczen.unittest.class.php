<?php
class docZenTest
{
    public $docZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('doc');

        $this->objectModel  = $tester->loadModel('doc');
        $this->docZenTest = initReference('doc');
    }

    /**
     * Test processReleaseListData method.
     *
     * @param  array $releaseList
     * @param  array $childReleases
     * @access public
     * @return array
     */
    public function processReleaseListDataTest(array $releaseList, array $childReleases): array
    {
        $result = callZenMethod('doc', 'processReleaseListData', array($releaseList, $childReleases));
        
        if(dao::isError()) return dao::getError();
        return $result;
    }
}