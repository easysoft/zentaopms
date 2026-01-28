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
        unset($this->config->testcase->search['fields']['scene']);
        unset($this->config->testcase->search['params']['scene']);
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
            $testcase->lib        = $libID;
            $testcase->project    = 0;
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
                    unset($this->lang->testcase->typeList['unit']);

                    $row->typeValue  = implode("\n", $this->lang->testcase->typeList);
                    $row->stageValue = implode("\n", $this->lang->testcase->stageList);
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
        if(empty($rows[0])) return array(array(), array());

        $header = array();
        foreach($rows[0] as $i => $rowValue)
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
        $steps = explode("\n", $cellValue);
        if(strpos($cellValue, "\r")) $steps = explode("\r", $cellValue);

        $caseSteps = array();
        foreach($steps as $step)
        {
            $step = trim($step);
            if(empty($step)) continue;

            preg_match('/^((([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^(([0-9]+)[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^([0-9]+)[.、](.*)$/Uu', $step, $out);
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
        $fields    = explode(',', $this->config->testcase->exportFields);
        $libFields = array_flip($this->config->caselib->exportTemplateFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            if(isset($libFields[$fieldName])) $fields[$fieldName] = zget($this->lang->testcase, $fieldName);
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
        foreach($header as $i => $title)
        {
            if(!isset($fields[$title])) continue;
            $columns[$i] = $fields[$title];
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
            return array($data['caseData'], $stepVars);
        }

        $rows    = $this->loadModel('file')->parseCSV($this->session->fileImport);
        $columns = $this->getColumnsForShowImport($rows[0], $fields);
        unset($rows[0]);

        $caseData = array();
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
                        $case->stage = implode(',', $case->stage);
                    }
                    else
                    {
                        $case->$field = array_search($cellValue, $this->lang->testcase->{$field . 'List'});
                    }
                }
                if($field == 'stepDesc')   $case->steps   = $cellValue;
                if($field == 'stepExpect') $case->expects = $cellValue;
            }

            if(empty($case->title)) continue;
            $caseData[$row] = $case;
        }

        $data = array('caseData' => $caseData);
        file_put_contents($tmpFile, serialize($data));

        return array($caseData, $stepVars);
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

            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->error->noData, 'locate' => $this->createLink('caselib', 'browse', "libID=$libID"))));
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

    /**
     * 获取导出的字段列表。
     * Get the export fields.
     *
     * @access protected
     * @return array
     */
    protected function getExportCasesFields(): array
    {
        $this->app->loadLang('testcase');

        $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $this->config->caselib->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = zget($this->lang->testcase, $fieldName);

            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * 处理导出的用例数据。
     * Process export cases.
     *
     * @param  array     $cases
     * @param  int       $libID
     * @access protected
     * @return array
     */
    protected function processCasesForExport(array $cases, int $libID): array
    {
        $users          = $this->loadModel('user')->getPairs('noletter');
        $relatedModules = $this->loadModel('tree')->getModulePairs($libID, 'caselib');
        $relatedSteps   = $this->loadModel('testcase')->getRelatedSteps(array_keys($cases));
        $relatedFiles   = $this->testcase->getRelatedFiles(array_keys($cases));

        $cases = $this->testcase->appendData($cases);
        foreach($cases as $case)
        {
            $case->stepDesc       = '';
            $case->stepExpect     = '';
            $case->openedDate     = !helper::isZeroDate($case->openedDate)     ? substr($case->openedDate, 0, 10)     : '';
            $case->lastRunDate    = !helper::isZeroDate($case->lastRunDate)    ? $case->lastRunDate                   : '';
            $case->module         = isset($relatedModules[$case->module])? $relatedModules[$case->module] . "(#$case->module)" : '';

            $case->pri           = zget($this->lang->testcase->priList, $case->pri);
            $case->type          = zget($this->lang->testcase->typeList, $case->type);
            $case->status        = $this->processStatus('testcase', $case);
            $case->openedBy      = zget($users, $case->openedBy);
            $case->lastEditedBy  = zget($users, $case->lastEditedBy);

            $this->processStepForExport($case, $relatedSteps);
            $this->processStageForExport($case);
            $this->processFileForExport($case, $relatedFiles);
            if($case->linkCase) $this->processLinkCaseForExport($case);
        }

        return $cases;
    }

    /**
     * 处理导出的用例的步骤。
     * Process step of case for export.
     *
     * @param  object    $case
     * @param  array     $relatedSteps
     * @access protected
     * @return void
     */
    protected function processStepForExport(object $case, array $relatedSteps): void
    {
        if(isset($relatedSteps[$case->id]))
        {
            $preGrade      = 1;
            $parentSteps   = array();
            $key           = array(0, 0, 0);
            foreach($relatedSteps[$case->id] as $step)
            {
                $grade = 1;
                $parentSteps[$step->id] = $step->parent;
                if(isset($parentSteps[$step->parent])) $grade = isset($parentSteps[$parentSteps[$step->parent]]) ? 3 : 2;

                if($grade > $preGrade)
                {
                    $key[$grade - 1] = 1;
                }
                else
                {
                    if($grade < $preGrade)
                    {
                        if($grade < 2) $key[1] = 0;
                        if($grade < 3) $key[2] = 0;
                    }
                    $key[$grade - 1] ++;
                }

                $stepID = implode('.', $key);
                $stepID = str_replace('.0', '', $stepID);
                $stepID = str_replace('.0', '', $stepID);

                $sign = (in_array($this->post->fileType, array('html', 'xml'))) ? '<br />' : "\n";
                $case->stepDesc   .= $stepID . ". " . htmlspecialchars_decode($step->desc) . $sign;
                $case->stepExpect .= $stepID . ". " . htmlspecialchars_decode($step->expect) . $sign;

                $preGrade = $grade;
            }
        }
        $case->stepDesc   = trim($case->stepDesc);
        $case->stepExpect = trim($case->stepExpect);

        if($this->post->fileType == 'csv')
        {
            $case->stepDesc   = str_replace('"', '""', $case->stepDesc);
            $case->stepExpect = str_replace('"', '""', $case->stepExpect);
        }
    }

    /**
     * 处理导出的用例的适用阶段。
     * Process stage of case for export.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function processStageForExport(object $case): void
    {
        $case->stage = explode(',', $case->stage);
        foreach($case->stage as $key => $stage) $case->stage[$key] = isset($this->lang->testcase->stageList[$stage]) ? $this->lang->testcase->stageList[$stage] : $stage;
        $case->stage = implode("\n", $case->stage);
    }

    /**
     * 处理导出用例的附件。
     * Process file of case for export.
     *
     * @param  object    $case
     * @param  array     $relatedFiles
     * @access protected
     * @return void
     */
    protected function processFileForExport(object $case, array $relatedFiles): void
    {
        $case->files = '';
        if(isset($relatedFiles[$case->id]))
        {
            foreach($relatedFiles[$case->id] as $file)
            {
                $fileURL = common::getSysURL() . $this->createLink('file', 'download', "fileID={$file->id}");
                $case->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
            }
        }
    }

    /**
     * 处理导出用例的相关用例。
     * Process link case of the case for export.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function processLinkCaseForExport(object $case): void
    {
        $tmpLinkCases   = array();
        $linkCaseIdList = explode(',', $case->linkCase);
        foreach($linkCaseIdList as $linkCaseID)
        {
            $linkCaseID = trim($linkCaseID);
            $tmpLinkCases[] = isset($relatedCases[$linkCaseID]) ? $relatedCases[$linkCaseID] . "(#$linkCaseID)" : $linkCaseID;
        }
        $case->linkCase = implode("; \n", $tmpLinkCases);
    }
}
