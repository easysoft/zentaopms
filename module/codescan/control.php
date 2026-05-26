<?php
declare(strict_types=1);
/**
 * The control file of codescan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
class codescan extends control
{
    public function __construct()
    {
        parent::__construct();
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "devopsIntroduction"));

        $this->loadModel('space')->setMenu(0);
    }
    /**
     * 规则列表。
     * Rules list.
     *
     * @param  string $type
     * @param  string $language
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function browse(string $type = 'all', string $language = '', string $queryID = '', string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $this->codescanZen->commonData();

        $pager     = $this->codescanZen->setPager($recPerPage, $pageID);
        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('codescan', 'browse', "type=bySearch&language={$language}&queryID=myQueryID&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->search, $queryID, $actionURL);

        $params   = $this->codescanZen->buildParams($type, "lang=$language", (int)$queryID, $orderBy, $recPerPage, $pageID);
        $ruleList = $this->codescan->getScanRules($params);
        $pager->recTotal = $ruleList->pager->total ?? 0;

        $ruleList = zget($ruleList, 'data', array());
        foreach($ruleList as &$rule)
        {
            $rule = $this->codescanZen->processRuleData($rule);
            $rule->description = isset($rule->description) ? strip_tags(htmlspecialchars_decode($rule->description)) : '';
        }

        $this->view->title    = $this->lang->codescan->browse;
        $this->view->rules    = $ruleList;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->type     = $type;
        $this->view->language = $language;
        $this->view->queryID  = $queryID;
        $this->display();
    }

    /**
     * 规则集列表。
     * Ruleset list.
     *
     * @param  string $type
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function ruleset(string $type = 'all', string $queryID = '', string $orderBy = 'langID_desc',int $recPerPage = 20, int $pageID = 1)
    {
        $this->codescanZen->commonData();

        $pager     = $this->codescanZen->setPager($recPerPage, $pageID);
        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('codescan', 'ruleset', "type=bySearch&queryID=myQueryID&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->ruleset->search, $queryID, $actionURL);

        $params      = $this->codescanZen->buildParams($type, '', (int)$queryID, $orderBy, $recPerPage, $pageID);
        $rulesetList = $this->codescan->getScanRulesets($params);

        $this->app->loadClass('pager', true);
        $pager->recTotal = $rulesetList->pager->total ?? 0;

        $rulesetList = zget($rulesetList, 'data', array());
        foreach($rulesetList as &$ruleset)
        {
            $ruleset->description = common::checkNotCN() && isset($ruleset->descEn) ? $ruleset->descEn : $ruleset->desc;
            $ruleset->description = isset($ruleset->desc) ? strip_tags(htmlspecialchars_decode($ruleset->desc)) : '';
            $ruleset->createdBy   = $ruleset->createdBy;
            $ruleset->updatedBy   = $ruleset->editedBy;
            $ruleset->rulesCount  = $ruleset->rulesCount;
            $ruleset->createdDate = date('Y-m-d H:i:s', strtotime($ruleset->createdDate));
            $ruleset->updatedDate = date('Y-m-d H:i:s', strtotime($ruleset->editedDate));
        }

        $this->view->title    = $this->lang->codescan->ruleset;
        $this->view->rulesets = $rulesetList;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->type     = $type;
        $this->view->queryID  = $queryID;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 规则详情。
     * View rule.
     *
     * @param  int    $ruleID
     * @access public
     * @return void
     */
    public function view(int $ruleID)
    {
        $this->codescanZen->commonData('lang|plugin');
        $rule = $this->codescan->getScanRule($ruleID);
        if(empty($rule)) $this->codescanZen->responseError($this->lang->notFound, inLink('browse'));

        $this->view->title   = $this->lang->codescan->view;
        $this->view->rule    = $this->codescanZen->processRuleData($rule);
        $this->view->actions = $rule ? $this->loadModel('action')->getList('codescanrule', $ruleID) : array();
        $this->display();
    }

    /**
     * 修改规则状态。
     * Change rule status.
     *
     * @param  int $ruleID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeState(int $ruleID, string $status)
    {
        $result = $this->codescan->updateScanRuleStatus($ruleID);
        $status = $status == '1' ? 'enable' : 'disable';

        if(!$result) $this->codescanZen->responseError($this->lang->codescan->notice->changeStateFailed, inLink('browse'));

        $rule = $this->codescan->getScanRule($ruleID);

        $this->loadModel('action')->create('codescanrule', $ruleID, $status . 'ScanRule', '', $rule->name);
        $this->sendSuccess(array('message' => $this->lang->codescan->notice->{$status . 'Success'}, 'load' => true));
    }

    /**
     * 创建规则集。
     * Create ruleset.
     *
     * @access public
     * @return void
     */
    public function createRuleset()
    {
        if($_POST)
        {
            $formData = form::data($this->config->codescan->form->createRuleset)
                ->add('isCustom', true)
                ->skipSpecial('name,description,type')
                ->get();
            if(dao::isError()) return $this->codescanZen->responseError();

            $setID = $this->codescan->createRuleset($formData);
            if(dao::isError()) return $this->codescanZen->responseError();

            $this->loadModel('action')->create('codescanruleset', $setID, 'createruleset', '', $formData->name);
            return $this->sendSuccess(array('load' => true));
        }

        $langList = array_column($this->codescan->getScanRulesConfig('langs'), 'lang', 'id');
        $typeList = array_column($this->codescan->getScanRulesConfig('types'), 'type', 'id');
        $this->view->title    = $this->lang->codescan->createRuleset;
        $this->view->langList = array_combine($langList, $langList);
        $this->view->typeList = array_combine($typeList, $typeList);
        $this->display();
    }

    /**
     * 编辑规则集。
     * Edit ruleset.
     *
     * @access public
     * @return void
     */
    public function editRuleset(int $setID)
    {
        $this->codescanZen->commonData('lang');
        if($_POST)
        {
            $oldRuleset = $this->codescan->getRuleset($setID);

            $formData = form::data($this->config->codescan->form->editRuleset)
                ->skipSpecial('name,desc,type')
                ->get();
            if(dao::isError()) return $this->codescanZen->responseError();

            $changes = common::createChanges($oldRuleset, $formData);
            if(empty($changes)) return $this->sendSuccess(array('load' => true));

            $this->codescan->editRuleset($setID, $formData);
            if(dao::isError()) return $this->codescanZen->responseError();

            $actionID = $this->loadModel('action')->create('codescanruleset', $setID, 'editruleset', '', $formData->name);
            if($actionID)
            {
                foreach($changes as &$change)
                {
                    if($change['field'] == 'lang')
                    {
                        $change['old'] = zget($this->view->langList, $change['old']);
                        $change['new'] = zget($this->view->langList, $change['new']);
                    }
                    if($change['field'] == 'type')
                    {
                        $change['old'] = zget($this->view->typeList, $change['old']);
                        $change['new'] = zget($this->view->typeList, $change['new']);
                    }
                }
                $this->loadModel('action')->logHistory($actionID, $changes);
            }
            return $this->sendSuccess(array('load' => true));
        }

        $langList = array_column($this->codescan->getScanRulesConfig('langs'), 'lang', 'id');
        $typeList = array_column($this->codescan->getScanRulesConfig('types'), 'type', 'id');
        $this->view->title   = $this->lang->codescan->editRuleset;
        $this->view->ruleSet = $this->codescan->getRuleset($setID);
        $this->view->langList = array_combine($langList, $langList);
        $this->view->typeList = array_combine($typeList, $typeList);
        $this->display();
    }

    /**
     * 删除规则集。
     * Delete ruleset.
     *
     * @param  int $setID
     * @access public
     * @return void
     */
    public function deleteRuleset(int $setID)
    {
        $ruleSet = $this->codescan->getRuleset($setID);

        $this->codescan->deleteRuleset($setID);
        if(dao::isError()) return $this->codescanZen->responseError();

        $this->loadModel('action')->create('codescanruleset', $setID, 'deleteruleset', '', $ruleSet->name);
        $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 修改规则集状态。
     * Change ruleset status.
     *
     * @param  int    $rulesetID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeRulesetState(int $rulesetID, string $status)
    {
        $result = $this->codescan->updateScanRulesetStatus($rulesetID, $status);
        if(!$result) $this->sendError($this->lang->codescan->notice->changeStateFailed, inLink('ruleset'));

        $status  = $status == '1' ? 'enable' : 'disable';
        $ruleset = $this->codescan->getRuleset($rulesetID);

        $this->loadModel('action')->create('codescanruleset', $rulesetID, $status . 'ScanRuleSet', '', $ruleset->name);
        $this->sendSuccess(array('message' => $this->lang->codescan->notice->{$status . 'Success'}, 'load' => true));
    }

    /*
     * 规则集详情。
     * View ruleset.
     *
     * @param  int    $setID
     * @param  string $type
     * @param  string $language
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function rulesetView(int $setID, string $type = 'rule', string $language = '', string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $this->codescanZen->commonData('lang|plugin');

        $this->config->codescan->actionList = $this->config->codescan->ruleset->actionList;

        $ruleSet = $this->codescan->getRuleset($setID);
        $ruleSet->createdDate = date('Y-m-d H:i:s', strtotime($ruleSet->createdDate));
        $ruleSet->editedDate  = date('Y-m-d H:i:s', strtotime($ruleSet->editedDate));
        if($type == 'rule')
        {
            $pager    = $this->codescanZen->setPager($recPerPage, $pageID);
            $params   = $this->codescanZen->buildParams('all', "lang=$language", 0, $orderBy, $recPerPage, $pageID);
            $ruleList = $this->codescan->getScanRulesetRules($setID, $params);

            $this->app->loadClass('pager', true);
            $pager->recTotal = $ruleList->pager->total ?? 0;

            $ruleList = zget($ruleList, 'data', array());
            foreach($ruleList as &$rule)
            {
                $rule = $this->codescanZen->processRuleData($rule);
                $rule->description    = isset($rule->description) ? strip_tags(htmlspecialchars_decode($rule->description)) : '';
                $rule->defaultRuleSet = $ruleSet->isCustom;
            }

            $this->view->pager    = $pager;
            $this->view->ruleList = $ruleList;
        }
        else
        {
            $this->view->actions = $this->loadModel('action')->getList('codescanruleset', $setID);
        }

        $this->view->ruleSet    = $ruleSet;
        $this->view->title      = $this->lang->codescan->viewRuleset;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->setID      = $setID;
        $this->view->type       = $type;
        $this->view->language   = $language;
        $this->view->orderBy    = $orderBy;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->display();
    }

    /**
     * 取消关联规则。
     * Unlink rule.
     *
     * @param  int    $setID
     * @param  int    $ruleID
     * @access public
     * @return void
     */
    public function unlinkRule(int $setID, int $ruleID)
    {
        $ruleSet = $this->codescan->getRuleset($setID);

        $result = $this->codescan->unlinkRules($setID, array($ruleID));
        if(!$result) return $this->codescanZen->responseError($this->lang->codescan->notice->unlinkFailed);

        $this->loadModel('action')->create('codescanruleset', $setID, 'unlinkrule', '', $ruleSet->name);
        $this->sendSuccess(array('message' => $this->lang->codescan->notice->unlinkSuccess, 'load' => true));
    }

    /**
     * 批量取消关联规则。
     * Batch unlink rule.
     *
     * @param  int    $setID
     * @access public
     * @return void
     */
    public function batchUnlinkRule(int $setID)
    {
        if($_POST)
        {
            $unlinkRules = $this->post->rules;

            $ruleSet = $this->codescan->getRuleset($setID);
            $result  = $this->codescan->unlinkRules($setID, $unlinkRules);
            if(!$result) return $this->codescanZen->responseError($this->lang->codescan->notice->unlinkFailed);

            $this->loadModel('action')->create('codescanruleset', $setID, 'unlinkrule', '', $ruleSet->name);
            $this->sendSuccess(array('message' => $this->lang->codescan->notice->unlinkSuccess, 'load' => true));
        }
    }

    /**
     * 关联规则。
     * Link rule.
     *
     * @param  int    $setID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkRule(int $setID, $type = 'all', string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $ruleSet = $this->codescan->getRuleset($setID);
        if($_POST)
        {
            $linkRules = $this->post->rules;
            $result    = $this->codescan->linkRulesInRuleset($setID, $linkRules);
            if(!$result) return $this->codescanZen->responseError($this->lang->codescan->notice->linkFailed);

            $this->loadModel('action')->create('codescanruleset', $setID, 'linkrule', '', $ruleSet->name);
            $this->sendSuccess(array('message' => $this->lang->codescan->notice->linkSuccess, 'load' => true));
        }

        $this->codescanZen->commonData();

        $pager     = $this->codescanZen->setPager($recPerPage, $pageID);
        $actionURL = inLink('linkRule', "setID={$setID}&type=bySearch&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->search, 0, $actionURL);

        $params   = $this->codescanZen->buildParams($type, '', 0, $orderBy, $recPerPage, $pageID);
        $ruleList = $this->codescan->getScanRulesetUnlinkRules($setID, $params);

        $this->app->loadClass('pager', true);
        $pager->recTotal = $ruleList->pager->total ?? 0;

        $ruleList = zget($ruleList, 'data', array());
        foreach($ruleList as &$rule)
        {
            $rule->desc = common::checkNotCN() && isset($rule->desc_en) ? $rule->desc_en : $rule->desc;
            $rule->desc = isset($rule->desc) ? strip_tags(htmlspecialchars_decode($rule->desc)) : '';
        }

        $this->view->title      = $this->lang->codescan->linkRule;
        $this->view->type       = $type;
        $this->view->setID      = $setID;
        $this->view->pager      = $pager;
        $this->view->ruleSet    = $ruleSet;
        $this->view->ruleList   = $ruleList;
        $this->view->orderBy    = $orderBy;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->display();
    }

    /**
     * 规则集列表。
     * Solution list.
     *
     * @param  string $type
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function solution(string $type = 'all', string $queryID = '', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->codescanZen->commonData();

        $pager     = $this->codescanZen->setPager($recPerPage, $pageID);
        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('codescan', 'solution', "type=bySearch&queryID=myQueryID&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->solution->search, $queryID, $actionURL);

        $params       = $this->codescanZen->buildParams($type, '', (int)$queryID, $orderBy, $recPerPage, $pageID);
        $solutionList = $this->codescan->getScanSolutions((array)$params);

        $this->app->loadClass('pager', true);
        $pager->recTotal = empty($solutionList->pager) ? 0 : zget($solutionList->pager, 'total', 0);

        $solutionList = zget($solutionList, 'data', array());
        foreach($solutionList as $solution)
        {
            $solution->lang   = empty($solution->langs) ? array() : $solution->langs;
            $solution->plugin = zget($solution, 'plugins', array());

            $solution->setCount  = empty($solution->rulesetsCount) ? 0 : $solution->rulesetsCount;
            $solution->ruleCount = empty($solution->rulesCount) ? 0 : $solution->rulesCount;

            if(common::checkNotCN() && isset($solution->descEn)) $solution->desc = $solution->descEn;
            $solution->desc = strip_tags(htmlspecialchars_decode($solution->desc));
        }

        $this->view->title     = $this->lang->codescan->solution;
        $this->view->solutions = $solutionList;
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->view->type      = $type;
        $this->view->queryID   = $queryID;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 修改扫描方案状态。
     * Change solution status.
     *
     * @param  int    $solutionID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeSolutionState(int $solutionID, string $status)
    {
        $result = $this->codescan->updateSolutionStatus($solutionID);
        if(!$result) $this->codescanZen->responseError($this->lang->codescan->notice->changeStateFailed);

        $status  = $status == '1' ? 'enable' : 'disable';
        $solution = $this->codescan->getSolution($solutionID);

        $this->loadModel('action')->create('codescansolution', $solutionID, $status . 'ScanSolution', '', $solution->name);
        $this->sendSuccess(array('message' => $this->lang->codescan->notice->{$status . 'Success'}, 'load' => true));
    }

    /**
     * 创建扫描方案。
     * Create solution.
     *
     * @access public
     * @return void
     */
    public function createSolution()
    {
        if($_POST)
        {
            $formData = form::data($this->config->codescan->form->createSolution)
                ->add('createdBy', $this->app->user->account)
                ->skipSpecial('name,description')
                ->get();
            if(dao::isError()) return $this->codescanZen->responseError();

            $setID = $this->codescan->createSolution($formData);
            if(dao::isError()) return $this->codescanZen->responseError();

            $this->loadModel('action')->create('codescansolution', $setID, 'createsolution', '', $formData->name);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title   = $this->lang->codescan->createSolution;
        $this->view->setList = $this->codescanZen->getListByQuery('ruleset', 0, 0, 'enabled');
        $this->display();
    }

    /**
     * 删除扫描方案。
     * Delete solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return void
     */
    public function deleteSolution(int $solutionID)
    {
        $solution = $this->codescan->getSolution($solutionID);

        $this->codescan->deleteSolution($solutionID);
        if(dao::isError()) return $this->codescanZen->responseError();

        $this->loadModel('action')->create('codescansolution', $solutionID, 'deletesolution', '', $solution->name);
        $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 编辑扫描方案。
     * Edit solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return void
     */
    public function editSolution(int $solutionID)
    {
        $solution = $this->codescan->getSolution($solutionID);
        $solution->rulesets = empty($solution->ruleSets) ? array() : array_column($solution->ruleSets, 'id');
        if($_POST)
        {
            $formData = form::data($this->config->codescan->form->editSolution)
                ->add('editedBy', $this->app->user->account)
                ->get();
            if(dao::isError()) return $this->codescanZen->responseError();

            $unbindSets = array_diff($solution->rulesets, $formData->rulesets);

            $solution->rulesets = implode(',', $solution->rulesets);
            $formData->rulesets = implode(',', $formData->rulesets);
            $changes = common::createChanges($solution, $formData);
            if(empty($changes)) return $this->sendSuccess(array('load' => true));

            $this->codescan->editSolution($solutionID, $formData);
            if(dao::isError()) return $this->codescanZen->responseError();

            if($unbindSets) $this->codescan->unbindRulesets($solutionID, array_values($unbindSets));

            $actionID = $this->loadModel('action')->create('codescansolution', $solutionID, 'editsolution', '', $formData->name);
            if($actionID) $this->loadModel('action')->logHistory($actionID, $changes);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title    = $this->lang->codescan->editSolution;
        $this->view->solution = $solution;
        $this->view->setList  = $this->codescanZen->getListByQuery();
        $this->display();
    }

    /**
     * 关联规则集。
     * Link ruleset.
     *
     * @param  int    $solutionID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkSet(int $solutionID, $type = 'all', string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $solution = $this->codescan->getSolution($solutionID);

        if($_POST)
        {
            $linkRulesets = $this->post->rulesets;
            $result       = $this->codescan->linkRulesetInSolution($solutionID, $linkRulesets);
            if(!$result) return $this->codescanZen->responseError($this->lang->codescan->notice->linkFailed);

            $this->loadModel('action')->create('codescansolution', $solutionID, 'linkruleset', '', $solution->name);
            $this->sendSuccess(array('message' => $this->lang->codescan->notice->linkSuccess, 'load' => true));
        }

        $this->codescanZen->commonData();
        $pager     = $this->codescanZen->setPager($recPerPage, $pageID);
        $actionURL = inLink('linkSet', "solutionID={$solutionID}&type=bySearch&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->ruleset->search, 0, $actionURL);

        $params      = $this->codescanZen->buildParams($type, '', 0, $orderBy, $recPerPage, $pageID);
        $rulesetList = $this->codescan->getSolutionUnlinkRulesets($solutionID, $params);

        $this->app->loadClass('pager', true);
        $pager->recTotal = empty($rulesetList->listArgs) ? 0 : zget($rulesetList->listArgs, 'total', 0);

        $rulesetList = zget($rulesetList, 'data', array());
        foreach($rulesetList as &$ruleset)
        {
            $ruleset->desc        = common::checkNotCN() && isset($ruleset->descEn) ? $ruleset->descEn : $ruleset->desc;
            $ruleset->desc        = isset($ruleset->desc) ? strip_tags(htmlspecialchars_decode($ruleset->desc)) : '';
            $ruleset->createdDate = date('Y-m-d H:i:s', strtotime($ruleset->createdDate));
            $ruleset->updatedDate = date('Y-m-d H:i:s', strtotime($ruleset->editedDate));
        }

        $this->view->title       = $this->lang->codescan->linkSet;
        $this->view->type        = $type;
        $this->view->solutionID  = $solutionID;
        $this->view->pager       = $pager;
        $this->view->solution    = $solution;
        $this->view->rulesetList = $rulesetList;
        $this->view->orderBy     = $orderBy;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->display();
    }

    /*
     * 扫描方案详情。
     * View solution.
     *
     * @param  int    $setID
     * @param  int    $onlyRuleSet
     * @access public
     * @return void
     */
    public function solutionView(int $solutionID, int $onlyRuleSet = 0)
    {
        $this->codescanZen->commonData('lang|plugin');

        $this->view->title      = $this->lang->codescan->viewSolution;
        $this->view->solution   = $this->codescan->getSolution($solutionID);
        $this->view->solutionID = $solutionID;
        if($onlyRuleSet) $this->display('codescan', 'solutionruleset');

        $this->config->codescan->actionList = $this->config->codescan->solution->actionList;

        $this->view->actions = $this->loadModel('action')->getList('codescansolution', $solutionID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->display();
    }

    /**
     * 解除关联规则集。
     * Unlink ruleset.
     *
     * @param  int    $solutionID
     * @param  int    $setID
     * @access public
     * @return void
     */
    public function unlinkSet(int $solutionID, int $setID)
    {
        $this->codescan->unbindRulesets($solutionID, array($setID));
        if(dao::isError()) return $this->codescanZen->responseError();

        $ruleSet = $this->codescan->getRuleset($setID);
        $this->loadModel('action')->create('codescansolution', $solutionID, 'unlinkruleset', '', $ruleSet->name);
        $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 添加扫描计划。
     * Create plan.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createPlan(int $repoID = 0)
    {
        if($_POST)
        {
            $formData = form::data($this->config->codescan->form->createPlan)
                ->add('created_by', $this->app->user->account)
                ->get();

            if(!$this->post->branchReg)
            {
                $branches = $this->post->branch ? array_filter($this->post->branch) : array();
                if(empty($branches)) dao::$errors[] = $this->lang->codescan->notice->branchEmpty;
            }
            if(dao::isError()) return $this->codescanZen->responseError();

            $plan = $this->codescanZen->processPlanData($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $conditions    = $plan->conditions;
            $serviceRepoID = $plan->repoID;
            unset($plan->conditions);

            $planID = $this->codescan->createPlan($plan);
            if(dao::isError()) return $this->codescanZen->responseError();

            if(!empty($conditions))
            {
                $this->codescan->batchCreateConditions((int)$serviceRepoID, $planID, $conditions);
                if(dao::isError()) return $this->codescanZen->responseError();
            }

            $this->loadModel('action')->create('codescanplan', $planID, 'createplan', '', $formData->name . "|serviceRepoID={$serviceRepoID}&planID={$planID}&repoID={$serviceRepoID}&type=view");
            return $this->sendSuccess(array('load' => inLink('tips', "planID=$planID&repoID=$repoID&serviceRepoID=$serviceRepoID")));
        }

        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $this->view->title        = $this->lang->codescan->createPlan;
        $this->view->repoID       = $repoID;
        $this->view->repoList     = $this->loadModel('repo')->getRepoPairs();
        $this->view->solutionList = $this->codescanZen->getListByQuery('solution', 0, 0, 'enabled');
        $this->display();
    }

    /**
     * 编辑扫描计划。
     * Edit plan.
     *
     * @param  int    $planID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function editPlan(int $planID, int $serviceRepoID, int $repoID = 0)
    {
        $oldPlan       = $this->codescan->getScanPlan($planID, $serviceRepoID);
        $oldConditions = $this->codescan->getPlanConditions($serviceRepoID, $planID);
        if($_POST)
        {
            $formData = form::data($this->config->codescan->form->editPlan)
                ->add('editedBy', $this->app->user->account)
                ->get();

            if(!$this->post->branchReg)
            {
                $branches = $this->post->branch ? array_filter($this->post->branch) : array();
                if(empty($branches)) dao::$errors[] = $this->lang->codescan->notice->branchEmpty;
            }
            if(dao::isError()) return $this->codescanZen->responseError();

            $plan = $this->codescanZen->processPlanData($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $conditions      = $plan->conditions;
            $unbindSolutions = array_diff(empty($oldPlan->solutionIDs) ? array() : $oldPlan->solutionIDs, $plan->solutionIDs);

            $this->codescan->editPlan($serviceRepoID, $planID, $plan);
            if(dao::isError()) return $this->codescanZen->responseError();

            if(!empty($unbindSolutions)) $this->codescan->bindOrUnbindSolutions($serviceRepoID, $planID, $unbindSolutions, false);
            if(dao::isError()) return $this->codescanZen->responseError();

            if(!empty($oldConditions))
            {
                $this->codescan->batchDeletePlanConditions((int)$serviceRepoID, $planID, array_column($oldConditions, 'id'));
                if(dao::isError()) return $this->codescanZen->responseError();
            }

            if(!empty($conditions))
            {
                $this->codescan->batchCreateConditions((int)$serviceRepoID, $planID, $conditions);
                if(dao::isError()) return $this->codescanZen->responseError();
            }

            $this->loadModel('action')->create('codescanplan', $planID, 'editplan', '', $formData->name . "|serviceRepoID={$serviceRepoID}&planID={$planID}&repoID={$repoID}&type=view");
            return $this->sendSuccess(array('load' => inLink('plan', "repoID=$repoID")));
        }

        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $this->view->title        = $this->lang->codescan->editPlan;
        $this->view->repoID       = $repoID;
        $this->view->plan         = $oldPlan;
        $this->view->repoList     = $this->loadModel('repo')->getGitFoxRepos();
        $this->view->conditions   = $oldConditions;
        $this->view->solutionList = $this->codescanZen->getListByQuery('solution');;
        $this->display();
    }

    /**
     * 规则集列表。
     * Solution list.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function plan(int $repoID = 0, string $status = 'all', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('repo');
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
        }

        $pager  = $this->codescanZen->setPager($recPerPage, $pageID);
        $params = $this->codescanZen->buildParams('all', '', 0, $orderBy, $pager->recPerPage, $pager->pageID);
        if($status != 'all') $params['latestTaskStatus'] = $status;

        $planList = $this->codescan->getScanPlans($repoID, (array)$params);
        $repoList = $this->repo->getPairs();

        $pager->recTotal = empty($planList->pager) ? 0 : (int)zget($planList->pager, 'total', 0);
        $planList = zget($planList, 'data', array());

        $solutionList = $this->codescanZen->getListByQuery('solution');
        foreach($planList as &$plan) $plan = $this->codescanZen->buildPlanData($plan);

        $this->view->title        = $this->lang->codescan->plan;
        $this->view->repoID       = $repoID;
        $this->view->plans        = $planList;
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->repoList     = $repoList;
        $this->view->status       = $status;
        $this->view->solutionList = array_column($solutionList, 'name', 'id');
        $this->display();
    }

    /**
     * 删除扫描计划。
     * Delete plan.
     *
     * @param  int    $serviceRepoID
     * @param  int    $planID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function deletePlan(int $serviceRepoID, int $planID, int $repoID = 0)
    {
        $plan = $this->codescan->getScanPlan($planID, $serviceRepoID);

        $this->codescan->deleteScanPlan($serviceRepoID, $planID);
        if(dao::isError()) return $this->codescanZen->responseError();

        $this->loadModel('action')->create('codescanplan', $planID, 'deleteplan', '', $plan->name);
        $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => inLink('plan', "repoID=$repoID")));
    }

    /*
     * 计划详情。
     * View plan.
     *
     * @param  int    $planID
     * @param  int    $repoID
     * @param  string $type
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function planView(int $serviceRepoID, int $planID, int $repoID = 0, string $type = '', string $orderBy = 'createAt_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->codescanZen->commonData('lang');

        if($repoID)  $this->loadModel('ci')->setMenu($repoID);

        $this->config->codescan->actionList = $this->config->codescan->plan->actionList;

        if($type == 'task')
        {
            if(isset($this->lang->devops->homeMenu->codescan))
            {
                $this->lang->devops->homeMenu->codescan['subMenu']->scanTask['exclude'] .= ',codescan-task';
                $this->lang->devops->homeMenu->codescan['subMenu']->scanPlan['alias']   .= ',task';
            }
            if(isset($this->lang->devops->menu->repoCodeScan))
            {
                $this->lang->devops->menu->repoCodeScan['subMenu']->scanTask['exclude'] .= ',codescan-task';
                $this->lang->devops->menu->repoCodeScan['subMenu']->scanPlan['alias']   .= ',task';
            }
            echo $this->fetch('codescan', 'task', "repoID={$repoID}&serviceRepoID={$serviceRepoID}&planID={$planID}&type={$type}&queryID=0&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}");
            return;
        }
        else
        {
            $solutions = $this->codescanZen->getListByQuery('solution');
            $plan      = $this->codescan->getScanPlan($planID, $serviceRepoID);
            if(!empty($plan)) $plan = $this->codescanZen->buildPlanData($plan);

            $solutionList = array();
            if(!empty($solutions) && !empty($plan->solutions))
            {
                $linkSolutions = $plan->solutionIDs;
                foreach($solutions as $solution)
                {
                    if(in_array($solution->id, $linkSolutions)) $solutionList[$solution->id] = $solution;
                }
            }

            $this->view->title         = $this->lang->codescan->planView;
            $this->view->type          = $type;
            $this->view->serviceRepoID = $serviceRepoID;
            $this->view->planID        = $planID;
            $this->view->repoID        = $repoID;
            $this->view->plan          = $plan;
            $this->view->solutionList  = $solutionList;
            $this->view->repoList      = $this->loadModel('repo')->getRepoPairs();
            $this->view->actions       = $this->loadModel('action')->getList('codescanplan', $planID);
            $this->display();
        }
    }

    /**
     * 解除关联规则集。
     * Unlink ruleset.
     *
     * @param  int    $solutionID
     * @param  int    $setID
     * @access public
     * @return void
     */
    public function unlinkSolution(int $serviceRepoID, int $planID, int $solutionID)
    {
        $this->codescan->bindOrUnbindSolutions($serviceRepoID, $planID, array($solutionID), false);
        if(dao::isError()) return $this->codescanZen->responseError();

        $plan = $this->codescan->getScanPlan($planID, $serviceRepoID);
        $this->loadModel('action')->create('codescanplan', $planID, 'unlinksolution', '', $plan->name);
        $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 扫描任务列表。
     * Scan task list.
     *
     * @param  int    $repoID
     * @param  int    $planID
     * @param  string $type
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task(int $repoID = 0, int $serviceRepoID = 0, int $planID = 0, string $type = 'all', string $queryID = '', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('repo');
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
            $repo = $this->repo->fetchByID($repoID);
        }

        $pager = $this->codescanZen->setPager($recPerPage, $pageID);
        $this->view->repoList = $this->repo->getPairs();
        $this->view->planList = array_column($this->codescanZen->getListByQuery('plan'), 'name', 'id');

        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('codescan', 'task', "repoID={$repoID}&serviceRepoID={$serviceRepoID}&planID={$planID}&type=bySearch&queryID=myQueryID&orderBy={$orderBy}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
        $this->codescanZen->buildSearchForm($this->config->codescan->task->search, $queryID, $actionURL);

        $params   = $this->codescanZen->buildParams($type, '', (int)$queryID, $orderBy, $pager->recPerPage, $pager->pageID);
        $taskList = $this->codescan->getScanTasks(!empty($repo) ? (int)$repo->id : 0, (int)$planID, (array)$params);

        $pager->recTotal = empty($taskList->pager) ? 0 : zget($taskList->pager, 'total', 0);

        $taskList = zget($taskList, 'data', array());
        foreach($taskList as $task) $task = $this->codescanZen->processTaskData($task, $this->view->repoList);

        $this->view->title    = $this->lang->codescan->task;
        $this->view->repoID   = $repoID;
        $this->view->plan     = $this->codescan->getScanPlan($planID, $serviceRepoID);
        $this->view->planID   = $planID;
        $this->view->tasks    = empty($taskList) ? array() : $taskList;
        $this->view->type     = $type;
        $this->view->queryID  = $queryID;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;

        $this->view->serviceRepoID = $serviceRepoID;
        $this->display();
    }

    /**
     * 获取扫描任务日志。
     * Get scan task log.
     *
     * @param  int    $repoID
     * @param  string $pipelineName
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetTaskLog(int $repoID, string $pipelineName, int $executionID)
    {
        $pipeline = new stdclass();
        $pipeline->name   = str_replace('*', '-', $pipelineName);
        $pipeline->number = $executionID;
        $logs = $this->loadModel('gitfox')->apiGetPipelineLogs($repoID, $pipeline);
        $logs = !empty($logs) ? nl2br(strip_tags($logs)) : '';
        return $this->send(array('status' => 'success', 'data' => $logs));
    }

    /*
     * 任务详情。
     * View task.
     *
     * @param  int    $serviceRepoID
     * @param  int    $taskID
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function taskView(int $serviceRepoID, int $taskID, int $repoID = 0, string $type = 'issue')
    {
        if($repoID) $this->loadModel('ci')->setMenu($repoID);
        $this->config->codescan->actionList = $this->config->codescan->task->actionList;

        if($type == 'issue')
        {
            $params = "repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}";
            if($this->cookie->scanIssueUrlParams)
            {
                if(strpos($this->cookie->scanIssueUrlParams, "{$taskID}-") !== 0)
                {
                    helper::setCookie('scanIssueUrlParams', '');
                }
                else
                {
                    $params = substr($this->cookie->scanIssueUrlParams, strlen("{$taskID}-"));
                }
            }

            echo $this->fetch('codescan', 'issue', $params);
            return;
        }
        elseif($type == 'log')
        {
            echo $this->fetch('codescan', 'taskLog', "serviceRepoID={$serviceRepoID}&taskID={$taskID}&repoID={$repoID}");
            return;
        }

        $gitfoxRepos = $this->loadModel('repo')->getGitFoxRepos();
        $repoList    = array_column($gitfoxRepos, 'name', 'serviceProject');

        $task = $this->codescan->getScanTask($taskID, $serviceRepoID);
        $task = $this->codescanZen->processTaskData($task, $repoList);

        $plan    = $this->codescan->getScanPlan(empty($task->plan_id) ? 0 : $task->plan_id, $serviceRepoID);
        $trigger = $this->codescan->getScanTrigger($serviceRepoID, empty($task->plan_id) ? 0 : $task->plan_id, empty($task->triggerID) ? 0 : $task->triggerID);

        $metrics = $this->codescan->getRepoMetrics($serviceRepoID, $taskID);
        $ranking = zget($metrics, 'top', array());
        $this->codescanZen->assignRepoTopRanking(zget($ranking, 'rules', array()), 'rule');
        $this->codescanZen->assignRepoTopRanking(zget($ranking, 'files', array()), 'file');

        $this->view->title           = $this->lang->codescan->taskView;
        $this->view->type            = $type;
        $this->view->serviceRepoID   = $serviceRepoID;
        $this->view->taskID          = $taskID;
        $this->view->repoID          = $repoID;
        $this->view->task            = $task;
        $this->view->plan            = $plan;
        $this->view->trigger         = $trigger;
        $this->view->repoList        = $repoList;
        $this->view->distribution    = $this->codescanZen->getIssueDistribution(zget($metrics, 'distribution', array()));
        $this->view->issueStatistics = zget($metrics, 'overview', array());
        $this->display();
    }

    /**
     * 获取扫描任务日志。
     * Get scan task log.
     *
     * @param  int $serviceRepoID
     * @param  int $taskID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function taskLog(int $serviceRepoID, int $taskID, int $repoID = 0)
    {
        $gitfoxRepos = $this->loadModel('repo')->getGitFoxRepos();
        $repoList    = array_column($gitfoxRepos, 'name', 'serviceProject');
        $task        = $this->codescan->getScanTask($taskID, $serviceRepoID);

        $this->view->title         = $this->lang->codescan->taskView;
        $this->view->type          = 'log';
        $this->view->serviceRepoID = $serviceRepoID;
        $this->view->taskID        = $taskID;
        $this->view->repoID        = $repoID;
        $this->view->task          = $this->codescanZen->processTaskData($task, $repoList);
        $this->display();
    }

    /**
     * 手动执行扫描计划。
     * Execute scan plan.
     *
     * @param  int $planID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function exec(int $planID, int $repoID)
    {
        $repo = $this->loadModel('repo')->fetchByID($repoID);
        $planList = $this->codescanZen->getListByQuery('plan', $repoID);

        if($_POST)
        {
            if(!$this->post->plan) $this->sendError(array('plan' => sprintf($this->lang->error->notempty, $this->lang->codescan->plan)));
            if(!$this->post->branch) $this->sendError(array('branch' => sprintf($this->lang->error->notempty, $this->lang->codescan->branch)));

            $branch = $this->post->branch;
            $planID = $this->post->plan;

            $planList = array_column($planList, null, 'id');
            $plan     = isset($planList[$planID]) ? $planList[$planID] : new stdClass();

            $this->codescan->execScanTask($plan, $branch);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('codescanplan', $plan->id, 'exec', '', $plan->name . "|serviceRepoID={$repoID}&planID={$planID}&repoID={$repoID}&type=view");
            $this->sendSuccess(array('message' => $this->lang->codescan->notice->execSuccess, 'load' => true));
        }

        $this->view->title  = $this->lang->codescan->scanSetting;
        $this->view->planID = $planID;
        $this->view->repoID = $repoID;
        $this->view->plans  = array_column($planList, 'name', 'id');;
        $this->display();
    }

    /**
     * 根据计划获取分支列表。
     * Get branch list by plan.
     *
     * @param  int $planID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function ajaxGetBranchByPlan(int $planID, int $repoID)
    {
        $branches = $this->codescanZen->processExecBranch($planID, $repoID);

        $branchList = array();
        foreach($branches as $branch) $branchList[] = array('text' => $branch, 'value' => $branch);
        $this->send(array('result' => 'success', 'data' => $branchList));
    }

    /**
     * 规则列表。
     * Issues list.
     *
     * @param  int    $repoID
     * @param  int    $taskID
     * @param  int    $serviceRepoID
     * @param  string $type
     * @param  string $queryID
     * @param  string $severity
     * @param  string $orderBy
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function issue(int $repoID = 0, int $taskID = 0, int $serviceRepoID = 0, string $type = 'wait', string $queryID = '', string  $severity = '', string $extras = '', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $decodeExtras = str_replace(array(',', ' ', '*'), array('&', '', '-'), trim($extras, ','));
        parse_str($decodeExtras, $params);

        if($repoID)
        {
            if(!$taskID)
            {
                global $lang;
                $lang->devops->menu->review['subModule'] = 'codescan';
                $lang->devops->menu->repoCodeScan['subModule'] = '';
            }

            $this->loadModel('ci')->setMenu($repoID);
        }

        $repoList = $this->loadModel('repo')->getRepoPairs();

        if($taskID)
        {
            $task = $this->codescan->getScanTask((int)$taskID, $serviceRepoID);
            $this->view->task = $this->codescanZen->processTaskData($task, $repoList);

            unset($this->config->codescan->issue->search['fields']['plan']);
            unset($this->config->codescan->issue->search['params']['plan']);

            unset($this->config->codescan->issue->search['fields']['scanBranch']);
            unset($this->config->codescan->issue->search['params']['scanBranch']);
        }

        $originTaskID = $taskID;
        $taskID       = empty($params['taskID']) ? $taskID : (int)$params['taskID'];

        $this->codescanZen->commonData('plugin');

        $pager    = $this->codescanZen->setPager($recPerPage, $pageID);
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!empty($repo))
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $this->config->codescan->issue->search['params']['scanBranch']['values'] = $scm->branch();

            if(empty($serviceRepoID)) $serviceRepoID = $repo->id;

            $urlTpl   = inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type=" . ($type == 'bySearch' ? 'wait' : $type) . "&queryID=0&severity={$severity}&extras=%s&orderBy={$orderBy}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
            $fileTree = $this->codescan->getIssueTreeList($repoID, $taskID);
            $ruleTree = $this->codescan->getIssueTreeList($repoID, $taskID, 'rule');

            $this->view->fileTree = $this->codescanZen->processIssueFileTree($fileTree, $urlTpl, $params);
            $this->view->ruleTree = $this->codescanZen->processIssueRuleTree($ruleTree, $urlTpl, $params);
        }

        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('codescan', 'issue', "repoID={$repoID}&taskID={$originTaskID}&serviceRepoID={$serviceRepoID}&type=bySearch&queryID=myQueryID&severity=&extras={$extras}&orderBy={$orderBy}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

        $this->view->planList = array_column($this->codescanZen->getListByQuery('plan'), 'name', 'id');
        $this->codescanZen->buildSearchForm($this->config->codescan->issue->search, $queryID, $actionURL);

        $condition = $repoID ? "repoID=$serviceRepoID" : "taskID=$taskID";
        if($severity) $condition .= "&priority=$severity";
        if(!empty($params['ruleID']) && $params['ruleID'] != 'all') $condition .= "&ruleID={$params['ruleID']}";
        if(!empty($params['branch']) && $this->cookie->issueFile) $condition .= "&branch={$params['branch']}&file={$this->cookie->issueFile}";

        $conditions = $this->codescanZen->buildParams($type, $condition, (int)$queryID, $orderBy, $pager->recPerPage, $pager->pageID);
        $conditions = $type == 'bySearch' && $repoID ? array_merge($conditions, array('repoID' => $serviceRepoID)) : array_merge($conditions, array('taskID' => $taskID));
        $issueList  = $this->codescan->getScanIssueList((int)$taskID, $conditions);
        $pager->recTotal = zget(zget($issueList, 'pager', array()), 'total', 0);

        $taskList = $this->codescanZen->getListByQuery('task', (int)$serviceRepoID);
        foreach($taskList as &$repoTask) $repoTask = $this->codescanZen->processTaskData($repoTask, $repoList);
        if(isset($issueList->data) && is_array($issueList->data)) foreach($issueList->data as &$issue) $issue = $this->codescanZen->processIssueData($issue);

        $this->view->title         = $this->lang->codescan->issue;
        $this->view->type          = 'issue';
        $this->view->issues        = zget($issueList, 'data', array());
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $type;
        $this->view->repoID        = $repoID;
        $this->view->realRepoID    = $repoID;
        $this->view->productID     = empty($repo) || empty($repo->product) ? 0 : explode(',', $repo->product)[0];
        $this->view->taskID        = $originTaskID;
        $this->view->queryID       = $queryID;
        $this->view->severity      = $severity;
        $this->view->serviceRepoID = $serviceRepoID;
        $this->view->taskList      = empty($taskList) ? array() : array_column($taskList, 'name', 'id');
        $this->view->extras        = $extras;
        $this->view->params        = $params;
        $this->display();
    }

    /**
     * 问题详情。
     * Issue view.
     *
     * @param  int $issueID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function issueView(int $issueID, int $repoID = 0)
    {
        if($repoID)
        {
            global $lang;
            $lang->devops->menu->review['subModule'] = 'codescan';
            $lang->devops->menu->repoCodeScan['subModule'] = '';

            $this->loadModel('ci')->setMenu($repoID);
        }
        if(isset($this->config->codescan->issue->actionList)) $this->config->codescan->actionList = $this->config->codescan->issue->actionList;

        $issue = $this->codescan->getScanIssue($issueID);
        if($issue) $issue = $this->codescanZen->processIssueData($issue);
        $repo     = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) $this->sendError($this->lang->codescan->notice->repoNotFound, true);

        $task = $this->codescan->getScanTask($issue->createdByTaskID);
        if(!empty($task)) $task = $this->codescanZen->processTaskData($task, array());

        $gitFoxRepos = $this->loadModel('repo')->getGitFoxRepos();

        $this->view->title         = $this->lang->codescan->issueView;
        $this->view->issueID       = $issueID;
        $this->view->issue         = $issue;
        $this->view->rule          = empty($issue->ruleID) ? array() : $this->codescan->getScanRule($issue->ruleID);
        $this->view->task          = empty($task) ? array() : $task;
        $this->view->repoID        = $repoID;
        $this->view->productID     = empty($repo) || empty($repo->product) ? 0 : explode(',', $repo->product)[0];
        $this->view->fileIssueList = empty($issue) ? array() : $this->codescanZen->getFileIssueList($issue->path, $issue->repoID, $issue->createdByTaskID);
        $this->view->repoPair      = array_column($gitFoxRepos, 'id', 'serviceProject');
        $this->view->gitFoxRepos   = $gitFoxRepos;
        $this->view->actions       = $issue ? $this->loadModel('action')->getList('codescanissue', $issueID) : array();
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 刷新概况数据。
     * Refresh overview data.
     *
     * @access public
     * @return void
     */
    public function refresh()
    {
        if(!commonModel::hasPriv('codescan', 'overview')) $this->loadModel('common')->deny('codescan', 'overview', false);

        $this->codescan->refreshOverview();
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 扫描概况。
     * Scan overview.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function overview(int $repoID = 0)
    {
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
        }

        $metrics = $this->codescan->getRepoMetrics($repoID);
        $ranking = zget($metrics, 'top', array());
        if($repoID)
        {
            $this->codescanZen->assignTopIssueInjection(zget($ranking, 'committers', array()));
            $this->codescanZen->assignRepoTopRanking(zget($ranking, 'rules', array()), 'rule');
            $this->codescanZen->assignRepoTopRanking(zget($ranking, 'files', array()), 'file');
        }
        else
        {
            $this->codescanZen->repoIssueTopRanking(zget($ranking, 'repo_issue_total', array()), 'total');
            $this->codescanZen->repoIssueTopRanking(zget($ranking, 'repo_issue_unresolved', array()), 'unresolved');
            $this->codescanZen->assignRepoStatistics(zget($metrics, 'repos', array()));
        }

        $overview = zget($metrics, 'overview', array());

        $issueStatistics = array();
        $scanStatistics  = array();
        foreach(zget($overview, 'issue', array()) as $issueKey => $issueCount) $issueStatistics[$issueKey] = $this->codescan->formatNumberToW($issueCount);
        foreach(zget($overview, 'scan', array()) as $scanKey => $scanCount)    $scanStatistics[$scanKey]   = $this->codescan->formatNumberToW($scanCount);

        $this->view->title           = $this->lang->codescan->overview;
        $this->view->repoID          = $repoID;
        $this->view->metrics         = $metrics;
        $this->view->overview        = $overview;
        $this->view->distribution    = $this->codescanZen->getIssueDistribution(zget($metrics, 'distribution', array()));
        $this->view->issueStatistics = $issueStatistics;
        $this->view->scanStatistics  = $scanStatistics;
        $this->view->serviceRepoID   = $repoID;
        $this->view->lastExecuteTime = $this->codescan->getLastExecuteTime();
        $this->display();
    }

    /**
     * 代码库问题统计。
     * Code repo issue statistics.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetRepoIssueStatistics(int $repoID = 0)
    {
        $addedList = array();
        $fixedList = array();
        $closedNum = 0;
        $totalNum  = 0;
        $repo      = $this->loadModel('repo')->fetchByID($repoID);
        if($repo)
        {
            $metrics   = $this->codescan->getRepoMetrics((int)$repo->serviceProject);
            $closedNum = empty($metrics->distribution->status->closed) ? 0 : $metrics->distribution->status->closed;
            $totalNum  = empty($metrics->overview->issue->total) ? 0 : $metrics->overview->issue->total;

            $beginDate = strtotime(date('Y-m-d', strtotime('-1 year'))) * 1000;
            $metrics   = $this->codescan->getIssueTrendsByRepo((int)$repo->serviceProject, $beginDate, 'month');
            list($addedList, $fixedList) = $this->codescanZen->processIssueTrends($metrics, 'month');
        }

        $this->view->monthList = array_keys($addedList);
        $this->view->addList   = array_values($addedList);
        $this->view->fixedList = array_values($fixedList);
        $this->view->closedNum = $closedNum;
        $this->view->totalNum  = $totalNum;
        $this->display();
    }

    /**
     * 获取问题解决人统计。
     * Get issue resolved by statistics.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function ajaxGetIssueResolvedByTop(int $repoID)
    {
        $this->view->title = $this->lang->codescan->resolvedByTop;
        $this->view->data  = $this->codescan->getIssueResolvedByTop($repoID);
        $this->display('codescan', 'rankingchart');
    }

    /**
     * 问题趋势图。
     * Issue trend chart.
     *
     * @param  int    $repoID
     * @param  int    $day
     * @access public
     * @return void
     */
    public function ajaxGetIssueTrends(int $repoID, int $day = 7)
    {
        $addedList = array();
        $fixedList = array();
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
            $repo    = $this->loadModel('repo')->fetchByID($repoID);

            if($repo)
            {
                $beginDate = strtotime(date('Y-m-d', strtotime('-' . $day . ' days'))) * 1000;
                $metrics   = $this->codescan->getIssueTrendsByRepo((int)$repo->serviceProject, $beginDate);
                list($addedList, $fixedList) = $this->codescanZen->processIssueTrends($metrics);
            }
        }

        $this->view->day    = $day;
        $this->view->repoID = $repoID;
        $this->view->added  = $addedList;
        $this->view->fixed  = $fixedList;
        $this->display();
    }

    /**
     * 扫描计划引导页。
     * Scan plan guide page.
     *
     * @param  int $planID
     * @param  int $repoID
     * @param  int $serviceRepoID
     * @access public
     * @return void
     */
    public function tips(int $planID, int $repoID, int $serviceRepoID)
    {
        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $this->view->title         = $this->lang->codescan->tips;
        $this->view->planID        = $planID;
        $this->view->repoID        = $repoID;
        $this->view->serviceRepoID = $serviceRepoID;
        $this->display();
    }

    /**
     * 重试扫描任务。
     * Retry scan task.
     *
     * @param  int $taskID
     * @access public
     * @return void
     */
    public function resend(int $taskID)
    {
        $result = $this->codescan->resendTask($taskID);
        if(!$result) return $this->sendError($this->lang->codescan->notice->resendFailed);

        $repoList = $this->loadModel('repo')->getRepoPairs();

        $task = $this->codescan->getScanTask($taskID);
        $task = $this->codescanZen->processTaskData($task, $repoList);
        $this->loadModel('action')->create('codescantask', $taskID, 'resend', '', $task->name . "|serviceRepoID={$task->repoID}&taskID={$taskID}&repoID={$task->repoID}&type=issue");
        return $this->sendSuccess(array('load' => true, 'message' => $this->lang->codescan->notice->resendSuccess));
    }
}
