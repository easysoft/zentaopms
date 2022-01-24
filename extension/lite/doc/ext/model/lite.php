<?php
public function getLibIdListByProject($projectID = 0)
{
    $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'noclosed');

    $executionLibs = array();
    if($executions) $executionLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('execution')->in(array_keys($executions))->fetchPairs();
    $productLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('product')->eq('0')->fetchPairs();
    $customLibs  = $this->dao->select('id')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetchPairs();

    $libIdList = array_merge($customLibs, $executionLibs, $productLibs);
    return $libIdList;
}
