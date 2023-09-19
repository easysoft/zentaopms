<?php
declare(strict_types=1);
class caselibZen extends caselib
{
    /**
     * 设置用例库状态。
     * Save lib state.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @access public
     * @return int
     */
    public function saveLibState(int $libID = 0, array $libraries = array()): int
    {
        if($libID > 0) $this->session->set('caseLib', (int)$libID);
        if($libID == 0 and $this->cookie->lastCaseLib) $this->session->set('caseLib', $this->cookie->lastCaseLib);
        if($libID == 0 and $this->session->caseLib == '') $this->session->set('caseLib', key($libraries));
        if(!isset($libraries[$this->session->caseLib]))
        {
            $this->session->set('caseLib', key($libraries));
            $libID = $this->session->caseLib;
        }
        return $this->session->caseLib;
    }

    /**
     * 为浏览用例库的用例设置 session 和 cookie。
     * Set session and cookie for browse.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function setBrowseSessionAndCookie(int $libID = 0, string $browseType = 'all', int $param = 0): void
    {
        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        $this->session->set('caselibList', $this->app->getURI(true), 'qa');
        if($browseType != 'bymodule') $this->session->set('libBrowseType', $browseType);

        /* Save cookie. */
        helper::setcookie('preCaseLibID', (string)$libID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        if($this->cookie->preCaseLibID != $libID)
        {
            $_COOKIE['libCaseModule'] = 0;
            helper::setcookie('libCaseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }
        if($browseType == 'bymodule') helper::setcookie('libCaseModule', (string)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
    }

    /**
     * 构建查询表单。
     * Build search form.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm(int $libID, array $libraries, int $queryID, string $actionURL): void
    {
        /* Set lib for search. */
        $this->config->testcase->search['fields']['lib']              = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib']['values']    = array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->caselib->all);
        $this->config->testcase->search['params']['lib']['operator']  = '=';
        $this->config->testcase->search['params']['lib']['control']   = 'select';
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($libID, 'caselib');

        /* Unset fields for search. */
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        unset($this->config->testcase->search['fields']['lastRunner']);
        unset($this->config->testcase->search['params']['lastRunner']);
        unset($this->config->testcase->search['fields']['lastRunResult']);
        unset($this->config->testcase->search['params']['lastRunResult']);
        unset($this->config->testcase->search['fields']['lastRunDate']);
        unset($this->config->testcase->search['params']['lastRunDate']);

        /* Set search params. */
        $this->config->testcase->search['module']    = 'caselib';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * 为创建用例分配用例变量。
     * Assign case params for creating case.
     *
     * @param  int    $param
     * @access public
     * @return void
     */
    public function assignCaseParamsForCreateCase(int $param): void
    {
        $caseTitle    = '';
        $type         = 'feature';
        $stage        = '';
        $pri          = 3;
        $precondition = '';
        $keywords     = '';
        $steps        = array();

        $this->loadModel('testcase');
        if($param)
        {
            $testcase     = $this->testcase->getById($param);
            $type         = $testcase->type ? $testcase->type : 'feature';
            $stage        = $testcase->stage;
            $pri          = $testcase->pri;
            $caseTitle    = $testcase->title;
            $precondition = $testcase->precondition;
            $keywords     = $testcase->keywords;
            $steps        = $testcase->steps;
        }

        $this->view->caseTitle        = $caseTitle;
        $this->view->type             = $type;
        $this->view->stage            = $stage;
        $this->view->pri              = $pri;
        $this->view->precondition     = $precondition;
        $this->view->keywords         = $keywords;
        $this->view->steps            = $this->testcase->appendSteps($steps);
    }

    /**
     * 为批量创建用例提前处理用例数据。
     * Prepare cases for batch creating cases.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function prepareCasesForBathcCreate(int $libID): array
    {
        $this->loadModel('common');
        $this->loadModel('testcase');
        unset($this->config->testcase->form->batchCreate['review']);

        $now            = helper::now();
        $account        = $this->app->user->account;
        $forceNotReview = $this->loadModel('testcase')->forceNotReview();
        $testcases      = form::batchData($this->config->testcase->form->batchCreate)->get();
        foreach($testcases as $i => $testcase)
        {
            $result = $this->common->removeDuplicate('testcase', $testcase, "lib={$libID}");
            if(zget($result, 'stop', false) !== false)
            {
                unset($testcases[$i]);
                continue;
            }

            $testcase->lib        = $libID;
            $testcase->project    = $this->lang->navGroup->caselib != 'qa' && $this->session->project ? $this->session->project : 0;
            $testcase->openedBy   = $account;
            $testcase->openedDate = $now;
            $testcase->status     = $forceNotReview ? 'normal' : 'wait';
            $testcase->version    = 1;
            $testcase->steps      = array();
            $testcase->expects    = array();
            $testcase->stepType   = array();
        }

        $requiredErrors = array();
        foreach($testcases as $i => $testcase)
        {
            /* Check required fields. */
            foreach(explode(',', $this->config->testcase->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field && empty($testcase->{$field}))
                {
                    $fieldName = $this->config->testcase->form->batchCreate[$field]['type'] != 'array' ? "{$field}[{$i}]" : "{$field}[{$i}][]";
                    $requiredErrors[$fieldName] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
                }
            }
        }
        if(!empty($requiredErrors)) dao::$errors = $requiredErrors;

        return $testcases;
    }

    /**
     * 准备编辑用例库的数据。
     * Prepare data for edit caselib.
     *
     * @param  object    $formData
     * @param  int       $libID
     * @access protected
     * @return object
     */
    protected function prepareEditExtras(object $formData, int $libID): object
    {
        $lib = $formData->add('id', $libID)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->stripTags($this->config->caselib->editor->edit['id'], $this->config->allowedTags)
            ->get();

        return $this->loadModel('file')->processImgURL($lib, $this->config->caselib->editor->edit['id'], $lib->uid);
    }
}
