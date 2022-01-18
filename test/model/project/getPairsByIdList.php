#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getPairsByIdList;
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Get project pairs by ID list.
     *
     * @param  array $IDList
     * @access public
     * @return int
     */
    public function getByIdList($IDList)
    {
        $projects = $this->project->getPairsByIdList($IDList);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        foreach($projects as $projectID => $projectName)
        {
            if(!empty($IDList) and !in_array($projectID, $IDList))
            {
                $result = array();
                $result['code']    = 'fail';
                $result['message'] = 'Error Data.';

                return $result;
            }
        }

        return count($projects);
    }
}

$t = new Tester('admin');

r($t->getByIdList(array(0,11,12,13))) && p() && e('3');  //查找ID为0、11、12、13的项目
r($t->getByIdList(array()))           && p() && e('90'); //查找所有项目
