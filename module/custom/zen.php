<?php
declare(strict_types=1);
/**
 * The zen file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
class customZen extends custom
{
    /**
     * 设置自定义字段列表。
     * Set the list of custom fields.
     *
     * @param  string    $module      todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field       priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @param  string    $lang        all|zh-cn|zh-tw|en|de|fr
     * @param  string    $currentLang all|zh-cn|zh-tw|en|de|fr
     * @access protected
     * @return void
     */
    protected function assignFieldListForSet(string $module = 'story', string $field = 'priList', string $lang = '', string $currentLang = ''): void
    {
        $this->app->loadLang($module);
        if($lang == 'all')
        {
            $fieldList = array();
            $items     = $this->custom->getItems("lang=all&module={$module}&section={$field}&vision={$this->config->vision}");
            foreach($items as $key => $item)
            {
                $fieldList[$key] = $item->value;
            }
        }
        else
        {
            $fieldList = zget($this->lang->$module, $field, array());
        }

        /* Check whether the current language has been customized. */
        $lang = str_replace('_', '-', $lang);
        $dbFields = $this->custom->getItems("lang={$lang}&module={$module}&section={$field}&vision={$this->config->vision}");
        if(empty($dbFields)) $dbFields = $this->custom->getItems("lang=" . ($lang == $currentLang ? 'all' : $currentLang) . "&module={$module}&section={$field}");
        if($dbFields)
        {
            $dbField = reset($dbFields);
            if($lang != $dbField->lang)
            {
                $lang = str_replace('-', "_", $dbField->lang);
                foreach($fieldList as $key => $value)
                {
                    if(isset($dbFields[$key]) && $value != $dbFields[$key]->value) $fieldList[$key] = $dbFields[$key]->value;
                }
            }
        }
        $this->view->fieldList = $fieldList;
        $this->view->dbFields  = $dbFields;
    }

    /**
     * 设置自定义列表变量。
     * Set the list of custom variables.
     *
     * @param  string    $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @param  string    $lang        all|zh-cn|zh-tw|en|de|fr
     * @param  string    $currentLang all|zh-cn|zh-tw|en|de|fr
     * @access protected
     * @return void
     */
    protected function assignVarsForSet(string $module = 'story', string $field = 'priList', string $lang = '', string $currentLang = ''): void
    {
        $this->assignFieldListForSet($module, $field, $lang, $currentLang);
        if($module == 'project' && $field == 'unitList')
        {
            $this->app->loadConfig($module);
            $unitList = zget($this->config->$module, 'unitList', '');
            $this->view->unitList        = explode(',', $unitList);
            $this->view->defaultCurrency = zget($this->config->$module, 'defaultCurrency', 'CNY');
        }
        if(in_array($module, array('story', 'demand')) && $field == 'reviewRules')
        {
            $this->app->loadConfig($module);
            $this->view->reviewRule     = zget($this->config->$module, 'reviewRules', 'allpass');
            $this->view->users          = $this->loadModel('user')->getPairs('noclosed|nodeleted');
            $this->view->superReviewers = zget($this->config->$module, 'superReviewers', '');
        }
        if(in_array($module, array('story', 'testcase', 'demand')) && $field == 'review')
        {
            $this->app->loadConfig($module);
            $this->loadModel('user');
            if(in_array($module, array('story', 'demand')))
            {
                $this->view->depts               = $this->loadModel('dept')->getDeptPairs();
                $this->view->forceReviewRoles    = zget($this->config->$module, 'forceReviewRoles', '');
                $this->view->forceReviewDepts    = zget($this->config->$module, 'forceReviewDepts', '');
                $this->view->forceNotReviewRoles = zget($this->config->$module, 'forceNotReviewRoles', '');
                $this->view->forceNotReviewDepts = zget($this->config->$module, 'forceNotReviewDepts', '');
            }
            $this->view->users          = $module == 'story' ? $this->user->getCanCreateStoryUsers() : $this->user->getPairs('noclosed|nodeleted');
            $this->view->needReview     = zget($this->config->$module, 'needReview', 1);
            $this->view->forceReview    = zget($this->config->$module, 'forceReview', '');
            $this->view->forceNotReview = zget($this->config->$module, 'forceNotReview', '');
        }
        if($module == 'bug' && $field == 'longlife')
        {
            $this->app->loadConfig('bug');
            $this->view->longlife = $this->config->bug->longlife;
        }
        if($module == 'block' && $field == 'closed')
        {
            $this->loadModel('block');
            $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
            $this->view->blockPairs  = $this->block->getClosedBlockPairs($closedBlock);
            $this->view->closedBlock = $closedBlock;
        }
        if($module == 'user' && $field == 'deleted')
        {
            $this->app->loadConfig('user');
            $this->view->showDeleted = isset($this->config->user->showDeleted) ? $this->config->user->showDeleted : '0';
        }
    }

    /**
     * 设置自定义字段的值。
     * Set the value of the custom field.
     *
     * @param  string      $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string      $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @access protected
     * @return string|bool
     */
    protected function setFieldListForSet(string $module = 'story', string $field = 'priList'): string|bool
    {
        $data = $_POST;
        if($module == 'project' && $field == 'unitList')
        {
            if(empty($data['unitList']))        return dao::$errors['message'] = $this->lang->custom->currencyNotEmpty;
            if(empty($data['defaultCurrency'])) return dao::$errors['message'] = $this->lang->custom->defaultNotEmpty;
            $data['unitList'] = implode(',', $data['unitList']);
            $this->loadModel('setting')->setItems("system.$module", $data);
        }
        elseif(($module == 'story' || $module == 'demand') && $field == 'review')
        {
            $this->setStoryReview($module, $data);
        }
        elseif(($module == 'story' || $module == 'demand') && $field == 'reviewRules')
        {
            if(!isset($data['superReviewers'])) $data['superReviewers'] = array();
            $data['superReviewers'] = implode(',', $data['superReviewers']);
            $this->loadModel('setting')->setItems("system.$module@{$this->config->vision}", $data);
        }
        elseif($module == 'testcase' && $field == 'review')
        {
            $this->setTestcaseReview($data);
        }
        elseif($module == 'bug' && $field == 'longlife')
        {
            $this->loadModel('setting')->setItems('system.bug', $data);
        }
        elseif($module == 'block' && $field == 'closed')
        {
            $data['closed'] = implode(',', $data['closed']);
            $this->loadModel('setting')->setItem('system.block.closed', zget($data, 'closed', ''));
        }
        elseif($module == 'user' && $field == 'contactField')
        {
            if(!isset($data['contactField'])) $data['contactField']= array();
            $data['contactField'] = implode(',', $data['contactField']);
            $this->loadModel('setting')->setItem('system.user.contactField', $data['contactField']);
        }
        elseif($module == 'user' && $field == 'deleted')
        {
            $this->loadModel('setting')->setItem('system.user.showDeleted', $data['showDeleted']);
        }
        else
        {
            if(!$this->post->keys) return dao::$errors['message'] = sprintf($this->lang->error->notempty, $this->lang->custom->key);
            $this->checkKeysForSet($module, $field);
        }

        return !dao::isError();
    }

    /**
     * 检查自定义key值是否合法。
     * Check whether the key of custom is valid.
     *
     * @param  string    $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @access protected
     * @return bool
     */
    protected function checkKeysForSet(string $module = 'story', string $field = 'priList'): bool
    {
        $this->checkDuplicateKeys($module, $field);
        if(dao::$errors) return false;

        $this->checkInvalidKeys($module, $field);
        if(dao::$errors) return false;

        $lang        = $_POST['lang'];
        $currentLang = $this->app->getClientLang();
        $this->custom->deleteItems("lang={$lang}&module={$module}&section={$field}&vision={$this->config->vision}");
        if($lang == 'all') $this->custom->deleteItems("lang={$currentLang}&module={$module}&section={$field}&vision={$this->config->vision}");

        $this->checkEmptyKeys($module, $field);
        return !dao::isError();
    }

    /**
     * 检查自定义key值是否重复。
     * Check whether the key of custom is duplicate.
     *
     * @param  string    $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @access protected
     * @return bool|string
     */
    protected function checkDuplicateKeys(string $module = 'story', string $field = 'priList'): bool|string
    {
        $keys = array();
        if(isset($_POST['keys']))
        {
            foreach($_POST['keys'] as $key)
            {
                if($module == 'testtask' && $field == 'typeList' && empty($key)) continue;
                if($key && in_array($key, $keys)) return dao::$errors['message'] = sprintf($this->lang->custom->notice->repeatKey, $key);
                $keys[] = $key;
            }
        }
        return true;
    }

    /**
     * 检查自定义key值是否正确。
     * Check whether the key of custom is valid.
     *
     * @param  string    $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @access protected
     * @return bool|string
     */
    protected function checkInvalidKeys(string $module = 'story', string $field = 'priList'): bool|string
    {
        $lang       = $_POST['lang'];
        $oldCustoms = $this->custom->getItems("lang={$lang}&module={$module}&section={$field}");
        foreach($_POST['keys'] as $index => $key)
        {
            if(!empty($key)) $key = trim($key);

            /* Invalid key. It should be numbers. (It includes severityList in bug module && priList in story, task, bug, testcasea, testtask && todo module.) */
            if(($field == 'priList' || $field == 'severityList') && (!is_numeric($key) || $key > 255)) return dao::$errors['message'] = $this->lang->custom->notice->invalidNumberKey;

            if(!empty($key) && !isset($oldCustoms[$key]) && $key != 'n/a' && !validater::checkREG($key, '/^[a-z_A-Z_0-9]+$/')) return dao::$errors['message'] = $this->lang->custom->notice->invalidStringKey;

            /* The length of roleList in user module is less than 10. check it when saved. */
            if($module == 'user' && $field == 'roleList' && strlen($key) > 10) return dao::$errors['message'] = $this->lang->custom->notice->invalidStrlen['ten'];

            /* The length of typeList in todo module is less than 15. check it when saved. */
            if($module == 'todo' && $field == 'typeList' && strlen($key) > 15) return dao::$errors['message'] = $this->lang->custom->notice->invalidStrlen['fifteen'];

            /* The length of sourceList in story module && typeList in task module is less than 20, check it when saved. */
            if((($module == 'story' && $field == 'sourceList') || ($module == 'task' && $field == 'typeList')) && strlen($key) > 20) return dao::$errors['message'] = $this->lang->custom->notice->invalidStrlen['twenty'];

            /* The length of field that in bug && testcase module && reasonList in story && task module is less than 30, check it when saved. */
            if((in_array($module, array('bug', 'testcase')) || (in_array($module, array('story', 'task')) && $field == 'reasonList')) && strlen($key) > 30) return dao::$errors['message'] = $this->lang->custom->notice->invalidStrlen['thirty'];
        }

        return true;
    }

    /**
     * 检查自定义key值是否为空。
     * Check whether the key of custom is empty.
     *
     * @param  string    $module todo|story|task|bug|testcase|testtask|user|project
     * @param  string    $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList
     * @access protected
     * @return bool|string
     */
    protected function checkEmptyKeys(string $module = 'story', string $field = 'priList'): bool|string
    {
        $lang     = $_POST['lang'];
        $emptyKey = false;
        $keys     = array();
        foreach($_POST['keys'] as $index => $key)
        {
            if(!$key && $emptyKey) continue;

            $value  = $_POST['values'][$index];
            $system = $_POST['systems'][$index];
            if($key && trim($value) === '') return dao::$errors['message'] = $this->lang->custom->notice->valueEmpty; // Fix bug #23538.

            $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}.{$system}", $value);

            if(!$key) $emptyKey = true;
        }
        return true;
    }

    /**
     * 设置需求评审规则。
     * Set the review rules of story.
     *
     * @param  string    $module
     * @param  array     $data
     * @access protected
     * @return bool
     */
    protected function setStoryReview(string $module, array $data): bool
    {
        $forceFields = array('forceReview', 'forceNotReview', 'forceReviewRoles', 'forceNotReviewRoles', 'forceReviewDepts', 'forceNotReviewDepts');
        foreach($forceFields as $forceField)
        {
            if(!isset($data[$forceField])) $data[$forceField] = array();
            $data[$forceField] = implode(',', $data[$forceField]);
        }

        foreach($data as $key => $value)
        {
            if($key == 'needReview') continue;
            if(strpos($key, 'Not')  && $data['needReview'] == 0) $data[$key] = '';
            if(!strpos($key, 'Not') && $data['needReview'] == 1) $data[$key] = '';
        }

        $this->loadModel('setting')->setItems("system.{$module}@{$this->config->vision}", $data);

        return !dao::isError();
    }

    /**
     * 设置用例评审规则。
     * Set testcase review rules.
     *
     * @param  array     $data
     * @access protected
     * @return bool
     */
    protected function setTestcaseReview(array $data): bool
    {
        if($data['needReview'])
        {
            unset($data['forceReview']);
            if(!isset($data['forceNotReview'])) $data['forceNotReview'] = array();
            $data['forceNotReview'] = implode(',', $data['forceNotReview']);
        }
        else
        {
            unset($data['forceNotReview']);
            if(!isset($data['forceReview'])) $data['forceReview'] = array();
            $data['forceReview'] = implode(',', $data['forceReview']);
        }
        $this->loadModel('setting')->setItems("system.testcase", $data);

        $reviewCase = isset($data['reviewCase']) ? $data['reviewCase'] : 0;
        if($data['needReview'] == 0 && $reviewCase)
        {
            $waitProductCases = $this->loadModel('testcase')->getByStatus(0, 0, 'all', 'wait');
            $waitLibCases     = $this->loadModel('caselib')->getLibCases(0, 'wait');
            $waitCaseIdList   = array_merge(array_keys($waitProductCases), array_keys($waitLibCases));
            $this->testcase->batchReview($waitCaseIdList, 'pass');
        }

        return !dao::isError();
    }
}
