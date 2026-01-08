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
        if($this->config->edition != 'open' && empty($table))
        {
            $flow = $this->loadModel('workflow')->getByModule($type);
            if(empty($flow->table)) return '';

            $table = $flow->table;
        }

        $orderBy = $type . 'OrderBy';
        $orderBy = $this->session->$orderBy;
        $select  = '';
        if($this->session->$typeOnlyCondition)
        {
            if($orderBy and str_contains($orderBy, 'priOrder'))          $select .= ", IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder";
            if($orderBy and str_contains($orderBy, 'severityOrder'))     $select .= ", IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder";
            if($type == 'task' && str_contains($orderBy, 'statusOrder')) $select .= ", INSTR('wait,doing,done,pause,cancel,closed,', status) as statusOrder";
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

        if($this->config->edition != 'open' && empty($table))
        {
            $flow = $this->loadModel('workflow')->getByModule($type);
            if(empty($flow->table)) return $preAndNextObject;

            $table = $flow->table;
        }

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

        $preAndNextObject->idKey = $key;

        return $preAndNextObject;
    }

    /**
     * 查看当前用户是否有资产库下其他方法的权限。
     * Check if current user has other methods permissions under the asset library.
     *
     * @param  bool      $display
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @access protected
     * @return array
     */
    protected static function setAssetLibMenu(bool $display, string $currentModule, string $currentMethod): array
    {
        $methodList = array('caselib', 'issuelib', 'risklib', 'opportunitylib', 'practicelib', 'componentlib');
        foreach($methodList as $method)
        {
            if(common::hasPriv($currentModule, $method))
            {
                $display       = true;
                $currentMethod = $method;
                break;
            }
        }

        return array($display, $currentMethod);
    }

    /**
     * 根据后台维护分组的视图设置，判断用户是否有权限。
     * According to the view maintained by the background, determine whether the user has permission.
     *
     * @param  string    $module
     * @param  string    $method
     * @param  array     $acls
     * @param  mixed     $object
     * @access protected
     * @return bool
     */
    protected static function checkPrivByRights(string $module, string $method, array $acls, mixed $object): bool
    {
        global $lang, $app;
        if(!commonModel::hasDBPriv($object, $module, $method)) return false;

        if(empty($acls['views'])) return true;
        $menu = isset($lang->navGroup->$module) ? $lang->navGroup->$module : $module;
        if($module == 'my' and $method == 'team') $menu = 'system'; // Fix bug #18642.
        $menu = strtolower($menu);
        if($menu == 'product' && $app->tab != 'product' && in_array($module, array('story', 'requirement', 'epic'))) return true;
        if($menu != 'qa' and $menu != 'project' and !isset($lang->$menu->menu)) return true;
        if(($menu == 'my' and $method != 'team') or $menu == 'index' or $module == 'tree') return true;
        if($module == 'company' and $method == 'dynamic') return true;
        if($module == 'action' and $method == 'editcomment') return true;
        if($module == 'action' and $method == 'comment') return true;
        if($module == 'report' and $method == 'export') return true;
        if(!isset($acls['views'][$menu])) return false;

        return true;
    }

    /**
     * 查看当前用户是否有其他个性化设置导航的权限。
     * Check if current user has other methods permissions under the preference menu.
     *
     * @param  bool      $display
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @access protected
     * @return array
     */
    protected static function setPreferenceMenu(bool $display, string $currentModule, string $currentMethod): array
    {
        global $app;
        global $lang;
        $app->loadLang('my');

        $moduleLinkList = $currentModule . 'LinkList';

        foreach($lang->my->$moduleLinkList as $key => $linkList)
        {
            $moduleMethodList = explode('-', $key);
            $method           = $moduleMethodList[1];
            if(common::hasPriv($currentModule, $method))
            {
                $display       = true;
                $currentMethod = $method;
                break;
            }
        }

        return array($display, $currentMethod);
    }

    /**
     * 非个性化设置的导航，查看当前用户是否有该应用下其他方法的权限。
     * For non-personalized settings navigation, check if current user has other methods permissions under the application.
     *
     * @param  bool      $display
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @access protected
     * @return array
     */
    protected static function setOtherMenu(bool $display, string $currentModule, string $currentMethod): array
    {
        global $lang;

        foreach($lang->$currentModule->menu as $menu)
        {
            if(!isset($menu['link'])) continue;

            $linkPart = explode('|', $menu['link']);
            if(!isset($linkPart[2])) continue;
            $method = $linkPart[2];

            /* Skip some pages that do not require permissions.*/
            if($currentModule == 'report' and $method == 'annualData') continue;
            if($currentModule == 'my' and $currentMethod == 'team') continue;

            if(common::hasPriv($currentModule, $method))
            {
                $display       = true;
                $currentMethod = $method;
                if(!isset($menu['target'])) break; // Try to jump to the method without opening a new window.
            }
        }

        return array($display, $currentMethod);
    }

    /**
     * 基于导航的所属应用，查看当前用户是否有该应用下其他方法的权限。
     * Based on the navigation of the application, check if current user has other methods permissions under the application.
     *
     * @param  string    $group
     * @param  bool      $display
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @access protected
     * @return array
     */
    protected static function setMenuByGroup(string $group, bool $display, string $currentModule, string $currentMethod): array
    {
        global $lang;

        foreach($lang->$group->menu as $menu)
        {
            if(!isset($menu['link'])) continue;

            $linkPart = explode('|', $menu['link']);
            if(count($linkPart) < 3) continue;
            list(, $module, $method) = $linkPart;

            if(common::hasPriv($module, $method))
            {
                $display       = true;
                $currentModule = $module;
                $currentMethod = $method;
                if(!isset($menu['target'])) break; // Try to jump to the method without opening a new window.
            }
        }

        return array($display, $currentModule, $currentMethod);
    }

    /**
     * 判断用户是否在项目、产品、计划、执行的管理员中。
     * Check if the user is in the administrator of the project, product, plan, and execution.
     *
     * @param  string    $module
     * @param  mixed     $object
     * @access protected
     * @return bool
     */
    protected static function isProjectAdmin(string $module, mixed $object): bool
    {
        global $app, $lang;

        $inProject = ((isset($lang->navGroup->$module) and $lang->navGroup->$module == 'project') || (!empty($object->type) and $object->type == 'project'));
        $projectID = $app->session->project;
        if(!empty($object->type) && $object->type == 'project') $projectID = $object->id;
        if($inProject and $projectID and (strpos(",{$app->user->rights['projects']},", ",{$projectID},") !== false or strpos(",{$app->user->rights['projects']},", ',all,') !== false)) return true;

        $inProduct = (isset($lang->navGroup->$module) and $lang->navGroup->$module == 'product');
        $productID = empty($object->id) ? $app->session->product : $object->id;
        if($inProduct and $productID and (strpos(",{$app->user->rights['products']},", ",{$productID},") !== false or strpos(",{$app->user->rights['products']},", ',all,') !== false)) return true;

        $inProgram = (isset($lang->navGroup->$module) and $lang->navGroup->$module == 'program');
        $programID = empty($object->id) ? $app->session->program : $object->id;
        if($inProgram and $programID and (strpos(",{$app->user->rights['programs']},", ",{$programID},") !== false or strpos(",{$app->user->rights['programs']},", ',all,') !== false)) return true;

        $inExecution = (isset($lang->navGroup->$module) and $lang->navGroup->$module == 'execution');
        $executionID = empty($object->id) ? $app->session->execution : $object->id;
        if($inExecution and $executionID and (strpos(",{$app->user->rights['executions']},", ",{$executionID},") !== false or strpos(",{$app->user->rights['executions']},", ',all,') !== false)) return true;

        return false;
    }

    /**
     * 获取需求模块下，真实请求的模块和方法。
     * Get the real module and method of the request under the requirement module.
     *
     * @param  string    $module
     * @param  string    $method
     * @param  array     $params
     * @access protected
     * @return array
     */
    protected static function getStoryModuleAndMethod(string $module, string $method, array $params): array
    {
        if($module == 'story' && $method == 'processstorychange') return array($module, $method);

        global $app;
        if(empty($params['storyType']) and $module == 'story' and !empty($app->params['storyType']) and strpos(",story,requirement,", ",{$app->params['storyType']},") !== false) $module = $app->params['storyType'];
        if($module == 'story' and !empty($params['storyType']) and strpos(",story,requirement,", ",{$params['storyType']},") !== false) $module = $params['storyType'];
        if($module == 'product' and $method == 'browse' and !empty($app->params['storyType']) and $app->params['storyType'] == 'requirement') $method = 'requirement';
        if($module == 'product' and $method == 'browse' and !empty($params['storyType']) and $params['storyType'] == 'requirement') $method = 'requirement';
        if($module == 'story' and $method == 'linkrequirements') $module = 'requirement';

        return array($module, $method);
    }

    /**
     * 获取白板模块下，真实请求的模块和方法。
     * Get the real module and method of the request under the board module.
     *
     * @param string $module
     * @param string $method
     * @param array $params
     * @access protected
     * @return string[]
     */
    protected static function getBoardModuleAndMethod(string $module, string $method, array $params): array
    {
        if($module != 'board') return array($module, $method);

        if(strpos(',view,open,bind,canvas,autoupdate,quit,importstory,', ",{$method},") !== false) $method = 'creation';
        if($method == 'createbytemplate') $method = 'createboard';

        return array($module, $method);
    }

    /**
     * Check and update webRoot config in DB.
     *
     * @param  object    $dbConfig
     * @access public
     * @return void
     */
    public function updateDBWebRoot($dbConfig)
    {
        if(PHP_SAPI == 'cli') return;
        if(!empty($this->app->installing) || !empty($this->app->upgrading)) return;

        global $config;
        /* Check config webRoot right or not. */
        if($config->webRoot[0] != '/') return;
        if($config->webRoot[strlen($config->webRoot) - 1] != '/') return;

        /* Get webRoot config in db. */
        $webRootConfig = null;
        foreach($dbConfig->common as $commonConfig)
        {
            if($commonConfig->key == 'webRoot')
            {
                $webRootConfig = $commonConfig;
                break;
            }
        }

        /* Init db webRoot config. */
        if(empty($webRootConfig)) return $this->loadModel('setting')->setItem('system.common.webRoot', $config->webRoot);
        if($config->webRoot == $webRootConfig->value) return;

        /* Update db webRoot. */
        $webRootConfig->value = $config->webRoot;
        $this->loadModel('setting')->updateItem('system.common.webRoot', $config->webRoot);
    }
}
