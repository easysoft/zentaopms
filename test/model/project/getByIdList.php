#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getByIdList;
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
     * Get project by ID list.
     *
     * @param  array $IDList
     * @access public
     * @return void
     */
    public function getProjects($IDList)
    {
        $projects = $this->project->getByIdList($IDList);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        foreach($projects as $project)
        {
            if(!in_array($project->id, $IDList))
            {
                $result = array();
                $result['code']    = 'fail';
                $result['message'] = 'Error data.';

                return $result;
            }
        }

        return count($projects);
    }
}

$t = new Tester('admin');

r($t->getProjects(array(0,11,12,13))) && p() && e('3');        // 查找ID为0、11、12、13的项目
r($t->getProjects(array()))           && p() && e('No data.'); // 查找空项目
