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
     * getExecutionData 
     * 
     * @param  int    $projectID 
     * @param  int    $type 
     * @access public
     * @return array
     */
    public function getExecutionData($projectID, $type) 
    {
        $result = $this->project->getDataByProject(TABLE_EXECUTION, $projectID, $type);
        if(empty($result)) return false;
        return $result;
    }        

    /**
     * getBuildData 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getBuildData($projectID)
    {
        $result = $this->project->getDataByProject(TABLE_BUILD, $projectID);
        if(empty($result)) return false;
        return $result;
    }      

    /**
     * getRealseData 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getRealseData($projectID)
    {
        $result = $this->project->getDataByProject(TABLE_RELEASE, $projectID);
        if(empty($result)) return false;
        return $result;
    } 
}

/* 还有case bug testtask doc表 */
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

/* GetDataByProject(TABLE_EXECUTION, $projectID, $type). */
r($t->getRealseData(131))             && p('id') && e('1'); //获取parent为132的首个发布的id
r($t->getRealseData(132))             && p('id') && e('6'); //获取parent为132的首个发布的id
r($t->getRealseData(10000))           && p('id') && e('0'); //获取parent为10000的首个发布的id


