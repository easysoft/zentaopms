<?php
/**
 * Get project pair array [name,id]
 * @param $status
 * @param $orderBy
 * @param $pager
 * @param $involved
 * @return array
 */
public function getProjectPairList($status = 'all', $orderBy = 'order_desc', $pager = null, $involved = 0)
{
    $projectList =  $this->loadExtension('manhour')->getProjectPairList($status , $orderBy, $pager, $involved);
    return $projectList;
}
/**
 * Get Project Consume Information by Project ID
 * @param $projectId
 * @param $beginDate
 * @param $endDate
 * @return array
 */
public function getProjectConsumeInfoById($projectId,$beginDate,$endDate)
{
    $projectList =  $this->loadExtension('manhour')->getProjectConsumeInfoById($projectId,$beginDate,$endDate);
    return $projectList;
}
/**
 * Get Project Consume Information by Project ID List
 * @param $projectIdList
 * @param $beginDate
 * @param $endDate
 * @return array
 */
public function getProjectConsumeInfoByIdList($projectIdList,$beginDate,$endDate)
{
    $projectList =  $this->loadExtension('manhour')->getProjectConsumeInfoByIdList($projectIdList,$beginDate,$endDate);
    return $projectList;
}
/**
 * Get Project Consume Information for all the Projects
 * @param $beginDate
 * @param $endDate
 * @return array
 */
public function getAllProjectConsumeInfo($beginDate,$endDate)
{
    $projectList =  $this->loadExtension('manhour')->getAllProjectConsumeInfo($beginDate,$endDate);
    return $projectList;
}
/**
 * Extract Users Set from Consume Information
 * @param $consumeInfo
 * @return array
 */
public function getUsers($consumeInfo)
{
    $projectList =  $this->loadExtension('manhour')->getUsers($consumeInfo);
    return $projectList;
}