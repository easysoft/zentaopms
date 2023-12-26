<?php
class artifactrepoTest
{
    public function __construct(string $account = 'admin')
    {
        su($account);

        global $tester, $app;
        $this->objectModel = $tester->loadModel('artifactrepo');

        $app->rawModule = 'artifactrepo';
        $app->rawMethod = 'browse';
        $app->setModuleName('artifactrepo');
        $app->setMethodName('browse');
    }

    /**
     * 获取制品库列表。
     * Get artifactrepo repo list.
     *
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function getListTest(string $orderBy, int $recPerPage, int $pageID): array
    {
        $this->objectModel->app->loadClass('pager', true);
        $pager = new pager($recPerPage, $pageID);

        $artifactrepoList = $this->objectModel->getList($orderBy, $pager);

        if(dao::isError()) return dao::getError();
        return $artifactrepoList;
    }
}
