<?php
declare(strict_types=1);
/**
 * The tao file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<yidong@easycorp.ltd>
 * @package     common
 * @link        https://www.zentao.net
 */
class commonTao extends commonModel
{
    /**
     * Get SQL for preAndNext.
     *
     * @param  string    $type
     * @access protected
     * @return string
     */
    protected function getPreAndNextSQL(string $type): string
    {
        $queryCondition    = $type . 'QueryCondition';
        $typeOnlyCondition = $type . 'OnlyCondition';
        $queryCondition    = $this->session->$queryCondition;
        $table             = zget($this->config->objectTables, $type, '');
        if(empty($table)) return '';

        $orderBy = $type . 'OrderBy';
        $orderBy = $this->session->$orderBy;
        $select  = '';
        if($this->session->$typeOnlyCondition)
        {
            if($orderBy and str_contains($orderBy, 'priOrder'))      $select .= ", IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder";
            if($orderBy and str_contains($orderBy, 'severityOrder')) $select .= ", IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder";
            $queryCondition = str_replace('t4.status', 'status', $queryCondition);

            $sql = $this->dao->select("*$select")->from($table)
                ->where($queryCondition)
                ->beginIF($orderBy != false)->orderBy($orderBy)->fi()
                ->get();
        }
        else
        {
            $sql = $queryCondition . (empty($orderBy) ? '' : " ORDER BY $orderBy");
        }

        return $sql;
    }

    /**
     * Query list for preAndNext.
     *
     * @param  string    $type
     * @param  string    $sql
     * @access protected
     * @return array
     */
    protected function queryListForPreAndNext(string $type, string $sql): array
    {
        $objectIdListKey   = $type . 'BrowseList';
        $existsObjectList  = $this->session->$objectIdListKey;
        $typeOnlyCondition = $type . 'OnlyCondition';
        if(empty($existsObjectList) or trim($existsObjectList['sql']) != trim($sql))
        {
            $queryObjects = $this->dao->query($sql);
            $objectList   = array();
            $key          = 'id';
            if($queryObjects)
            {
                while($object = $queryObjects->fetch())
                {
                    if(!$this->session->$typeOnlyCondition and $type == 'testcase' and isset($object->case)) $key = 'case';
                    $id = $object->$key;
                    $objectList[$id] = $id;
                }
            }

            $this->session->set($objectIdListKey, array('sql' => $sql, 'idkey' => $key, 'objectList' => $objectList), $this->app->tab);
            $existsObjectList = $this->session->$objectIdListKey;
        }

        return $existsObjectList;
    }

    /**
     * Search preAndNext from list.
     *
     * @param  int       $objectID
     * @param  array     $objectList
     * @access protected
     * @return object
     */
    protected function searchPreAndNextFromList(int $objectID, array $objectList): object
    {
        $preAndNextObject       = new stdClass();
        $preAndNextObject->pre  = '';
        $preAndNextObject->next = '';
        if(!isset($objectList['objectList'])) return $preAndNextObject;

        $preObj = false;
        foreach($objectList['objectList'] as $id)
        {
            /* Get next object. */
            if($preObj === true)
            {
                $preAndNextObject->next = $id;
                break;
            }

            /* Get pre object. */
            if($id == $objectID)
            {
                if($preObj) $preAndNextObject->pre = $preObj;
                $preObj = true;
            }
            if($preObj !== true) $preObj = $id;
        }
        return $preAndNextObject;
    }

    /**
     * Fetch preAndNextObject.
     *
     * @param  string    $type
     * @param  int       $objectID
     * @param  object    $preAndNextObject
     * @access protected
     * @return object
     */
    protected function fetchPreAndNextObject(string $type, int $objectID, object $preAndNextObject): object
    {
        $queryCondition    = $type . 'QueryCondition';
        $typeOnlyCondition = $type . 'OnlyCondition';
        $objectIdListKey   = $type . 'BrowseList';
        $queryCondition    = $this->session->$queryCondition;
        $existsObjectList  = $this->session->$objectIdListKey;
        $table             = zget($this->config->objectTables, $type, '');

        if(empty($table)) return $preAndNextObject;
        if(empty($preAndNextObject->pre) and empty($preAndNextObject->next)) return $preAndNextObject;
        if(empty($queryCondition) or $this->session->$typeOnlyCondition)
        {
            if(!empty($preAndNextObject->pre))  $preAndNextObject->pre  = $this->dao->select('*')->from($table)->where('id')->eq($preAndNextObject->pre)->fetch();
            if(!empty($preAndNextObject->next)) $preAndNextObject->next = $this->dao->select('*')->from($table)->where('id')->eq($preAndNextObject->next)->fetch();
            return $preAndNextObject;
        }

        $searched     = false;
        $objects      = array();
        $key          = $existsObjectList['idkey'];
        $queryObjects = $this->dao->query($existsObjectList['sql']);
        while($object = $queryObjects->fetch())
        {
            $objects[$object->$key] = $object;
            if(!empty($preAndNextObject->pre)  and is_numeric($preAndNextObject->pre)  and $object->$key == $preAndNextObject->pre)  $preAndNextObject->pre  = $object;
            if(!empty($preAndNextObject->next) and is_numeric($preAndNextObject->next) and $object->$key == $preAndNextObject->next) $preAndNextObject->next = $object;
            if((empty($preAndNextObject->pre) or is_object($preAndNextObject->pre)) and (empty($preAndNextObject->next) or is_object($preAndNextObject->next)))
            {
                $searched = true;
                break;
            }
        }

        /* If the pre object or next object is number type, then continue to find the pre or next. */
        if(!$searched)
        {
            $objectIdList  = array_keys($objects);
            $objectIdIndex = (int)array_search($objectID, $objectIdList);
            if(is_numeric($preAndNextObject->pre))  $preAndNextObject->pre  = $objectIdIndex - 1 >= 0 ? $objects[$objectIdList[$objectIdIndex - 1]] : '';
            if(is_numeric($preAndNextObject->next)) $preAndNextObject->next = $objectIdIndex + 1 < count($objectIdList) ? $objects[$objectIdList[$objectIdIndex + 1]] : '';
        }

        return $preAndNextObject;
    }
}

