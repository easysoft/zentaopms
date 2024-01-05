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
        if(empty($libraries)) return 0;

        if($libID > 0) $this->session->set('caseLib', (int)$libID);
        if($libID == 0 && $this->cookie->lastCaseLib) $this->session->set('caseLib', $this->cookie->lastCaseLib);
        if($libID == 0 && !$this->session->caseLib) $this->session->set('caseLib', key($libraries));

        if(!isset($libraries[$this->session->caseLib])) $this->session->set('caseLib', key($libraries));
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
            foreach(explode(',', $this->config->caselib->createcase->requiredFields) as $field)
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

    /**
     * 获取导出模板的字段。
     * Get fields for export template.
     *
     * @access protected
     * @return array
     */
    protected function getFieldsForExportTemplate(): array
    {
        $fields = array();
        foreach($this->config->caselib->exportTemplateFields as $field) $fields[$field] = $this->lang->testcase->$field;

        $fields[''] = '';
        $fields['typeValue']  = $this->lang->testcase->lblTypeValue;
        $fields['stageValue'] = $this->lang->testcase->lblStageValue;

        return $fields;
    }

    /**
     * 获取导出模板的数据。
     * Get rows for export template.
     *
     * @param  int       $num
     * @param  array     $modules
     * @access protected
     * @return array
     */
    protected function getRowsForExportTemplate(int $num, array $modules): array
    {
        $rows = array();
        for($i = 0; $i < $num; $i++)
        {
            foreach($modules as $moduleID => $module)
            {
                $row = new stdclass();
                $row->module     = $module . "(#$moduleID)";
                $row->stepDesc   = "1. \n2. \n3.";
                $row->stepExpect = "1. \n2. \n3.";

                if(empty($rows))
                {
                    $row->typeValue  = join("\n", $this->lang->testcase->typeList);
                    $row->stageValue = join("\n", $this->lang->testcase->stageList);
                }

                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * 获取导入文件的表头字段和列。
     * Get header and columns from import file.
     *
     * @param  string    $fileName
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function getImportHeaderAndColumns(string $fileName, array $fields): array
    {
        $rows = $this->loadModel('file')->parseCSV($fileName);

        $header = array();
        if(!empty($rows[0]))
        {
            foreach($rows[0] as $i => $rowValue)
            {
                if(empty($rowValue)) break;
                $header[$i] = $rowValue;
            }
        }

        $columns = array();
        foreach($header as $title)
        {
            if(!isset($fields[$title])) continue;
            $columns[] = $fields[$title];
        }

        return array($header, $columns);
    }

    /**
     * 获得用例的步骤和预期结果。
     * Get steps and expects from import file.
     *
     * @param  string    $field
     * @param  int       $row
     * @param  string    $cellValue
     * @access protected
     * @return array
     */
    protected function getStepsAndExpectsFromImportFile(string $field, int $row, string $cellValue): array
    {
        $steps = array();
        if(strpos($cellValue, "\n")) $steps = explode("\n", $cellValue);
        if(strpos($cellValue, "\r")) $steps = explode("\r", $cellValue);

        $caseSteps = array();
        foreach($steps as $step)
        {
            $step = trim($step);
            if(empty($step)) continue;

            preg_match('/^((([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/U', $step, $out);
            if(!$out) preg_match('/^(([0-9]+)[.]([0-9]+))[.、](.*)$/U', $step, $out);
            if(!$out) preg_match('/^([0-9]+)[.、](.*)$/U', $step, $out);
            if($out)
            {
                $count   = count($out);
                $num     = $out[1];
                $parent  = $count > 4 ? $out[2] : '0';
                $grand   = $count > 6 ? $out[3] : '0';
                $step    = trim($out[2]);
                if($count > 4) $step = $count > 6 ? trim($out[6]) : trim($out[4]);

                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['number']  = $num;
                $caseSteps[$num]['type']    = $count > 4 ? 'item' : 'step';
                if(!empty($parent)) $caseSteps[$parent]['type'] = 'group';
                if(!empty($grand)) $caseSteps[$grand]['type']   = 'group';
            }
            elseif(isset($num))
            {
                $caseSteps[$num]['content'] = isset($caseSteps[$num]['content']) ? "{$caseSteps[$num]['content']}\n{$step}" : "\n{$step}";
            }
            elseif($field == 'stepDesc')
            {
                $num = 1;
                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['type']    = 'step';
                $caseSteps[$num]['number']  = $num;
            }
            elseif($field == 'stepExpect' && isset($stepData[$row]['desc']))
            {
                end($stepData[$row]['desc']);
                $num = key($stepData[$row]['desc']);
                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['number']  = $num;
            }
        }
        return $caseSteps;
    }

    /**
     * 获取导入用例的字段。
     * Get fields for import test case.
     *
     * @access protected
     * @return array
     */
    protected function getFieldsForImport(): array
    {
        $fields = explode(',', $this->config->testcase->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = zget($this->lang->testcase, $fieldName);
            unset($fields[$key]);
        }

        return array_flip($fields);
    }

    /**
     * 获取展示导入用例的列。
     * Get columns for show import.
     *
     * @param  array     $firstRow
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function getColumnsForShowImport(array $firstRow, array $fields): array
    {
        /* 获取表头和列。*/
        $header = array();
        foreach($firstRow as $i => $rowValue)
        {
            if(empty($rowValue)) break;
            $header[$i] = $rowValue;
        }

        $columns = array();
        foreach($header as $title)
        {
            if(!isset($fields[$title])) continue;
            $columns[] = $fields[$title];
        }

        return $columns;
    }

    /**
     * 获取导入用例的数据。
     * Get data for import testcase.
     *
     * @param  int       $maxImport
     * @param  string    $tmpFile
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function getDataForImport(int $maxImport, string $tmpFile, array $fields): array
    {
        $stepVars = 0;

        if(!empty($maxImport) && file_exists($tmpFile))
        {
            $data = unserialize(file_get_contents($tmpFile));
            return array($data['caseData'], $data['stepData'], $stepVars);
        }

        $rows    = $this->loadModel('file')->parseCSV($this->session->fileImport);
        $columns = $this->getColumnsForShowImport($rows[0], $fields);
        unset($rows[0]);

        $caseData = array();
        $stepData = array();
        foreach($rows as $row => $data)
        {
            $case = new stdclass();
            foreach($columns as $key => $field)
            {
                if(!isset($data[$key])) continue;

                $cellValue = $data[$key];
                $case->$field = $cellValue;

                if($field == 'module') $case->$field = strrpos($cellValue, '(#') !== false ? trim(substr($cellValue, strrpos($cellValue, '(#') + 2), ')') : 0;

                if(in_array($field, $this->config->testcase->export->listFields))
                {
                    if($field == 'stage')
                    {
                        $stages = explode("\n", $cellValue);

                        $case->stage = array();
                        foreach($stages as $stage) $case->stage[] = array_search($stage, $this->lang->testcase->stageList);
                        $case->stage = join(',', $case->stage);
                    }
                    else
                    {
                        $case->$field = array_search($cellValue, $this->lang->testcase->{$field . 'List'});
                    }
                }

                if($field == 'stepDesc' || $field == 'stepExpect')
                {
                    $caseStep = $this->getStepsAndExpectsFromImportFile($field, $row, $cellValue);
                    $stepKey  = str_replace('step', '', strtolower($field));
                    $stepData[$row][$stepKey] = array_values($caseStep);

                    $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);

                    unset($case->$field);
                }
            }

            if(empty($case->title)) continue;
            $caseData[$row] = $case;
        }

        $data = array('caseData' => $caseData, 'stepData' => $stepData);
        file_put_contents($tmpFile, serialize($data));

        return array($caseData, $stepData, $stepVars);
    }

    /**
     * 导入用例的返回。
     * Response after show import.
     *
     * @param  int       $libID
     * @param  array     $caseData
     * @param  int       $maxImport
     * @param  int       $pageID
     * @param  int       $stepVars
     * @access protected
     * @return bool
     */
    protected function responseAfterShowImport(int $libID, array $caseData, int $maxImport, int $pageID, int $stepVars): bool
    {
        /* 如果没有用例数据时返回。*/
        /* Response when there is no case. */
        if(empty($caseData))
        {
            unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);

            return $this->send(array('result'=>'fail', 'message' => $this->lang->error->noData, 'load' => $this->createLink('caselib', 'browse', "libID=$libID")));
        }

        /* 如果导入的用例多余设置的最大导入数。*/
        /* If the total amount of the import case is more than the max import. */
        $totalAmount = count($caseData);
        if($totalAmount > $this->config->file->maxImport)
        {
            /* 如果没有限制每页的导入数量，按照默认最大导入数分页导入。*/
            /* If there is no limit for import, limit as the max import in config. */
            if(empty($maxImport))
            {
                $this->view->totalAmount = $totalAmount;
                $this->view->maxImport   = $maxImport;
                $this->view->libID       = $libID;

                die($this->display());
            }

            $caseData = array_slice($caseData, ($pageID - 1) * $maxImport, $maxImport, true);
        }

        if(empty($caseData)) return $this->send(array('result'=>'success', 'load' => $this->createLink('caselib', 'browse', "libID=$libID")));

        $countInputVars  = count($caseData) * 9 + (isset($stepVars) ? $stepVars : 0);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);

        if($showSuhosinInfo)
        {
            $this->view->suhosinInfo = sprintf((extension_loaded('suhosin') ? $this->lang->suhosinInfo : $this->lang->maxVarsInfo), $countInputVars);
            die($this->display());
        }

        return true;
    }
}
