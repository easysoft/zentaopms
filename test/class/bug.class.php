<?php
class bugTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('bug');
    }

    /**
     * Test create a bug.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createObject($param = array())
    {
        $createFields['title']       = '测试创建bug';
        $createFields['type']        = 'codeerror';
        $createFields['product']     = 1;
        $createFields['execution']   = 101;
        $createFields['openedBuild'] = 'trunk';
        $createFields['pri']         = 3;
        $createFields['severity']    = 3;
        $createFields['status']      = 'active';
        $createFields['deadline']    = '2021-03-19';
        $createFields['openedBy']    = 'admin';
        $createFields['openedDate']  = '2021-03-19';

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object   = $this->objectModel->create();
        $objectID = $object['id'];
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getByID($objectID);
            return $object;
        }
    }

    public function batchCreateObject($productID, $param = array())
    {
        $modules      = array('0', '0', '0');
        $executions   = array('101', '101', '0');
        $openedBuilds = array('', '', '');
        $title        = array('', '', '');
        $deadlines    = array('0000-00-00', '0000-00-00', '0000-00-00');
        $stepses      = array('', '', '');
        $types        = array('', '', '');
        $severities   = array(3, 3, 3);
        $oses         = array('', '', '');
        $browsers     = array('', '', '');
        $pris         = array(3, 3, 3);
        $color        = array('', '', '');
        $keywords     = array('', '', '');

        $createFields['modules']      = $modules;
        $createFields['executions']   = $executions;
        $createFields['openedBuilds'] = $openedBuilds;
        $createFields['title']        = $title;
        $createFields['deadlines']    = $deadlines;
        $createFields['stepses']      = $stepses;
        $createFields['types']        = $types;
        $createFields['severities']   = $severities;
        $createFields['oses']         = $oses;
        $createFields['browsers']     = $browsers;
        $createFields['pris']         = $pris;
        $createFields['color']        = $color;
        $createFields['keywords']     = $keywords;

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->batchCreate($productID);

        $bug = array();
        if(is_array($object))
        {
            foreach($object as $bugID => $actionID)
            {
                $bug[] = $this->objectModel->getByID($bugID);
            }
        }

        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return empty($bug) ? $object : $bug;
        }
    }

    /**
     * Test create bug from gitlab issue.
     *
     * @param  array  $bug
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function createBugFromGitlabIssueTest($bug, $executionID)
    {
        $objectID = $this->objectModel->createBugFromGitlabIssue($bug, $executionID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $objectID ? $this->objectModel->getById($objectID) : 0;
            return $object;
        }
    }

    /**
     * Test get bugs.
     *
     * @access public
     * @return int
     */
    public function getBugsTest($product, $branch, $browseType, $module)
    {
        global $tester;

        /* Load pager. */
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager(0, 20 ,1);

        $projectID  = 0;
        $sort       = 'id_desc';
        $queryID    = 0;
        $executions = $tester->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');

        $bugs = $this->objectModel->getBugs($product, $executions, $branch, $browseType, $module, $queryID, $sort, $pager, $projectID);

        $title = '';
        foreach($bugs as $bug) $title .= ',' . $bug->title;
        $title = trim($title, ',');

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
    }
}
