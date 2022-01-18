#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getDataByProject();
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
     * Get executions from getDataByProject.
     * 
     * @param  int    $projectID 
     * @param  int    $type 
     * @access public
     * @return array
     */
    public function getExecutionData($projectID, $type) 
    {
        $executions = $this->project->getDataByProject(TABLE_EXECUTION, $projectID, $type);
        if(empty($executions)) return false;
        return $executions;
    }        

    /**
     * Get builds from getDataByProject.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getBuildData($projectID)
    {
        $builds = $this->project->getDataByProject(TABLE_BUILD, $projectID);
        if(empty($builds)) return false;
        return $builds;
    }      

    /**
     * Get releases from getDataByProject.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getReleaseData($projectID)
    {
        $releases = $this->project->getDataByProject(TABLE_RELEASE, $projectID);
        if(empty($releases)) return false;
        return $releases;
    } 
}

/* 还有case bug testtask doc表要写，暂时表中没数据 */
$t = new Tester();

/* GetDataByProject(TABLE_EXECUTION, $projectID, $type). */
r($t->getExecutionData(39))           && p('id') && e('129'); //获取parent为11首个项目的id
r($t->getExecutionData(40, 'sprint')) && p('id') && e('130'); //获取parent为11的首个项目的id
r($t->getExecutionData(41, 'stage'))  && p('id') && e('131'); //获取parent为11的首个项目的id
r($t->getExecutionData(10000))        && p('id') && e('0');   //获取parent为10000的首个项目的id

/* GetDataByProject(TABLE_BUILD, $projectID, $type). */
r($t->getBuildData(131))              && p('id') && e('1'); //获取parent为131的首个版本的id
r($t->getBuildData(132))              && p('id') && e('6'); //获取parent为132的首个版本的id
r($t->getBuildData(10000))            && p('id') && e('0'); //获取parent为10000的首个版本的id

/* GetDataByProject(TABLE_RELEASE, $projectID, $type). */
r($t->getReleaseData(131))             && p('id') && e('1'); //获取parent为132的首个发布的id
r($t->getReleaseData(132))             && p('id') && e('6'); //获取parent为132的首个发布的id
r($t->getReleaseData(10000))           && p('id') && e('0'); //获取parent为10000的首个发布的id


