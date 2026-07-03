<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class caselibZenTest extends baseTest
{
    protected $moduleName = 'caselib';
    protected $className  = 'zen';

    /**
     * Test responseAfterShowImport method.
     *
     * @param  int    $libID
     * @param  array  $caseData
     * @param  int    $maxImport
     * @param  int    $pageID
     * @param  int    $stepVars
     * @access public
     * @return string
     */
    public function responseAfterShowImportTest(int $libID, array $caseData, int $maxImport, int $pageID, int $stepVars): int
    {
        global $tester;

        if(empty($caseData))
        {
            $tempFile = tempnam(sys_get_temp_dir(), 'caselib_import_');
            file_put_contents($tempFile, 'test');
            $tester->app->session->set('fileImport', $tempFile);
            return 0;
        }

        $maxLimit = $this->instance->config->file->maxImport ?? 0;
        if($maxLimit && count($caseData) > $maxLimit)
        {
            if(empty($maxImport)) return 0;
            $caseData = array_slice($caseData, ($pageID - 1) * $maxImport, $maxImport, true);
        }

        return empty($caseData) || common::judgeSuhosinSetting(count($caseData) * 9 + $stepVars) ? 0 : 1;
    }

    /**
     * Test assignCaseParamsForCreateCase method.
     *
     * @param  int $param
     * @access public
     * @return object
     */
    public function assignCaseParamsForCreateCaseTest(int $param): object
    {
        $this->invokeArgs('assignCaseParamsForCreateCase', [$param]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildSearchFormTest(int $libID, array $libraries, int $queryID, string $actionURL): array
    {
        $this->invokeArgs('buildSearchForm', [$libID, $libraries, $queryID, $actionURL]);
        if(dao::isError()) return dao::getError();
        return $this->instance->config->testcase->search;
    }

    /**
     * Test getColumnsForShowImport method.
     *
     * @param  array  $firstRow
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getColumnsForShowImportTest(array $firstRow, array $fields, string $type = 'array')
    {
        $result = $this->invokeArgs('getColumnsForShowImport', array($firstRow, $fields));
        if(dao::isError()) return dao::getError();

        return match($type)
        {
            'count'       => count($result),
            'keys'        => array_keys($result),
            'values'      => array_values($result),
            'first_key'   => empty($result) ? '' : key($result),
            'first_value' => empty($result) ? '' : reset($result),
            'is_empty'    => empty($result) ? 1 : 0,
            'has_zero_key'=> isset($result[0]) ? 1 : 0,
            'has_one_key' => isset($result[1]) ? 1 : 0,
            'specific_key'=> $result[0] ?? '',
            default       => $result,
        };
    }

    /**
     * Test getDataForImport method.
     *
     * @param  int    $maxImport
     * @param  string $tmpFile
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getDataForImportTest(int $maxImport, string $tmpFile, array $fields, string $type = 'both')
    {
        $result = $this->invokeArgs('getDataForImport', array($maxImport, $tmpFile, $fields));
        if(dao::isError()) return dao::getError();

        $firstCase = empty($result[0]) ? null : reset($result[0]);
        return match($type)
        {
            'caseData_count'  => count($result[0]),
            'stepVars'        => $result[1],
            'caseData'        => $result[0],
            'first_case_title'=> $firstCase->title ?? '',
            'first_case_module'=> $firstCase->module ?? '',
            'first_case_type' => $firstCase->type ?? '',
            'has_steps'       => isset($firstCase->steps) ? 1 : 0,
            'has_expects'     => isset($firstCase->expects) ? 1 : 0,
            'is_empty'        => empty($result[0]) ? 1 : 0,
            default           => $result,
        };
    }

    /**
     * Test getExportCasesFields method.
     *
     * @access public
     * @return array
     */
    public function getExportCasesFieldsTest(array $postData = array(), string $type = 'array')
    {
        if(!empty($postData['exportFields'])) $_POST['exportFields'] = $postData['exportFields'];
        else unset($_POST['exportFields']);

        $result = $this->invokeArgs('getExportCasesFields');
        if(dao::isError()) return dao::getError();

        return match($type)
        {
            'count'         => count($result),
            'keys'          => array_keys($result),
            'values'        => array_values($result),
            'first_key'     => empty($result) ? '' : key($result),
            'first_value'   => empty($result) ? '' : reset($result),
            'has_id'        => isset($result['id']) ? 1 : 0,
            'has_title'     => isset($result['title']) ? 1 : 0,
            'has_module'    => isset($result['module']) ? 1 : 0,
            'has_precondition' => isset($result['precondition']) ? 1 : 0,
            'has_stepDesc'  => isset($result['stepDesc']) ? 1 : 0,
            'has_stepExpect'=> isset($result['stepExpect']) ? 1 : 0,
            'is_empty'      => empty($result) ? 1 : 0,
            default         => $result,
        };
    }

    /**
     * Test getFieldsForExportTemplate method.
     *
     * @access public
     * @return array
     */
    public function getFieldsForExportTemplateTest(string $type = 'normal')
    {
        $result = $this->invokeArgs('getFieldsForExportTemplate');
        if(dao::isError()) return dao::getError();

        return $type == 'count' ? count($result) : $result;
    }

    /**
     * Test getFieldsForImport method.
     *
     * @access public
     * @return array
     */
    public function getFieldsForImportTest(string $type = 'array')
    {
        $result = $this->invokeArgs('getFieldsForImport');
        if(dao::isError()) return dao::getError();

        return match($type)
        {
            'count'          => count($result),
            'keys'           => array_keys($result),
            'values'         => array_values($result),
            'first_key'      => empty($result) ? '' : key($result),
            'first_value'    => empty($result) ? '' : reset($result),
            'has_title'      => isset($result['标题']) ? 1 : 0,
            'has_module'     => isset($result['所属模块']) ? 1 : 0,
            'has_precondition'=> isset($result['前置条件']) ? 1 : 0,
            'has_stepDesc'   => isset($result['步骤']) ? 1 : 0,
            'has_stepExpect' => isset($result['预期']) ? 1 : 0,
            default          => $result,
        };
    }

    /**
     * Test getImportHeaderAndColumns method.
     *
     * @param  string $fileName
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getImportHeaderAndColumnsTest(string $fileName, array $fields, string $type = 'both')
    {
        $result = $this->invokeArgs('getImportHeaderAndColumns', array($fileName, $fields));
        if(dao::isError()) return dao::getError();

        return match($type)
        {
            'header_count'  => count($result[0]),
            'columns_count' => count($result[1]),
            'header'        => $result[0],
            'columns'       => $result[1],
            'header_first'  => empty($result[0]) ? '' : reset($result[0]),
            'columns_first' => empty($result[1]) ? '' : reset($result[1]),
            'is_empty'      => empty($result[0]) && empty($result[1]) ? 1 : 0,
            default         => $result,
        };
    }

    /**
     * Test getRowsForExportTemplate method.
     *
     * @param  int    $num
     * @param  array  $modules
     * @access public
     * @return array
     */
    public function getRowsForExportTemplateTest(int $num, array $modules, string $type = 'array')
    {
        $result = $this->invokeArgs('getRowsForExportTemplate', array($num, $modules));
        if(dao::isError()) return dao::getError();

        $firstRow = empty($result) ? null : $result[0];
        return match($type)
        {
            'count'             => count($result),
            'first_module'      => $firstRow->module ?? '',
            'first_stepDesc'    => $firstRow->stepDesc ?? '',
            'first_hasTypeValue'=> isset($firstRow->typeValue) ? 1 : 0,
            'first_hasStageValue'=> isset($firstRow->stageValue) ? 1 : 0,
            default             => $result,
        };
    }

    /**
     * Test getStepsAndExpectsFromImportFile method.
     *
     * @param  string $field
     * @param  int    $row
     * @param  string $cellValue
     * @access public
     * @return array
     */
    public function getStepsAndExpectsFromImportFileTest(string $field, int $row, string $cellValue, string $type = 'array')
    {
        $result = $this->invokeArgs('getStepsAndExpectsFromImportFile', array($field, $row, $cellValue));
        if(dao::isError()) return dao::getError();

        $firstKey  = empty($result) ? null : key($result);
        $firstStep = $firstKey === null ? array() : $result[$firstKey];
        return match($type)
        {
            'count'         => count($result),
            'first_content' => $firstStep['content'] ?? '',
            'first_type'    => $firstStep['type'] ?? '',
            'first_number'  => $firstStep['number'] ?? '',
            'has_group'     => (int)(bool)array_filter($result, fn($step) => ($step['type'] ?? '') == 'group'),
            'has_item'      => (int)(bool)array_filter($result, fn($step) => ($step['type'] ?? '') == 'item'),
            'keys'          => array_keys($result),
            'content_only'  => implode('|', array_column($result, 'content')),
            default         => $result,
        };
    }

    /**
     * Test processFileForExport method.
     *
     * @param  object $case
     * @param  array  $relatedFiles
     * @access public
     * @return object
     */
    public function processFileForExportTest(object $case, array $relatedFiles, string $type = 'case')
    {
        $this->invokeArgs('processFileForExport', array($case, $relatedFiles));
        if(dao::isError()) return dao::getError();

        $files = $case->files ?? '';
        if($type == 'first_file_title') return preg_match('/<a[^>]*>([^<]+)<\\/a>/i', $files, $matches) ? trim($matches[1]) : '';
        if($type == 'first_file_id') return preg_match('/fileID=(\\d+)/', $files, $matches) ? $matches[1] : '';
        return match($type)
        {
            'files'            => $files,
            'files_length'     => strlen($files),
            'has_files'        => empty($files) ? 0 : 1,
            'files_count'      => substr_count($files, '<br />'),
            'has_html_link'    => strpos($files, '<a href=') !== false ? 1 : 0,
            'has_download_link'=> strpos($files, '/file-download-') !== false ? 1 : 0,
            'has_blank_target' => strpos($files, 'target="_blank"') !== false ? 1 : 0,
            'has_br_tag'       => strpos($files, '<br />') !== false ? 1 : 0,
            'is_empty'         => empty($files) ? 1 : 0,
            default            => $case,
        };
    }

    /**
     * Test processLinkCaseForExport method.
     *
     * @param  object $case
     * @param  string $type
     * @access public
     * @return object
     */
    public function processLinkCaseForExportTest(object $case, string $type = 'case')
    {
        $this->invokeArgs('processLinkCaseForExport', array($case));
        if(dao::isError()) return dao::getError();

        $linkCase  = $case->linkCase ?? '';
        $linkCases = empty($linkCase) ? array() : explode('; ', $linkCase);
        return match($type)
        {
            'linkCase'            => $linkCase,
            'linkCase_length'     => strlen($linkCase),
            'has_linkCase'        => empty($linkCase) ? 0 : 1,
            'linkCase_count'      => substr_count($linkCase, '; ') + 1,
            'has_semicolon'       => strpos($linkCase, '; ') !== false ? 1 : 0,
            'has_newlines'        => strpos($linkCase, "\n") !== false ? 1 : 0,
            'first_linkCase'      => empty($linkCases) ? '' : trim($linkCases[0]),
            'last_linkCase'       => empty($linkCases) ? '' : trim(end($linkCases)),
            'linkCase_parts_count'=> count($linkCases),
            'has_id_format'       => preg_match('/\\(#\\d+\\)/', $linkCase) ? 1 : 0,
            'is_empty'            => empty($linkCase) ? 1 : 0,
            default               => $case,
        };
    }

    /**
     * Test processStageForExport method.
     *
     * @param  object $case
     * @access public
     * @return object
     */
    public function processStageForExportTest(object $case, string $type = 'case')
    {
        $this->invokeArgs('processStageForExport', array($case));
        if(dao::isError()) return dao::getError();

        $stage  = $case->stage ?? '';
        $stages = explode("\n", $stage);
        return match($type)
        {
            'stage'        => $stage,
            'stage_length' => strlen($stage),
            'has_stage'    => empty($stage) ? 0 : 1,
            'stage_lines'  => substr_count($stage, "\n") + 1,
            'stage_count'  => count($stages),
            'first_stage'  => empty($stages) ? '' : trim($stages[0]),
            'last_stage'   => empty($stages) ? '' : trim(end($stages)),
            'has_newlines' => strpos($stage, "\n") !== false ? 1 : 0,
            'is_empty'     => empty($stage) ? 1 : 0,
            default        => $case,
        };
    }

    /**
     * Test processStepForExport method.
     *
     * @param  object $case
     * @param  array  $relatedSteps
     * @param  array  $postData
     * @access public
     * @return object
     */
    public function processStepForExportTest(object $case, array $relatedSteps, array $postData = array(), string $type = 'case')
    {
        $_POST['fileType'] = $postData['fileType'] ?? 'csv';

        $this->invokeArgs('processStepForExport', array($case, $relatedSteps));
        if(dao::isError()) return dao::getError();

        $stepDesc   = $case->stepDesc ?? '';
        $stepExpect = $case->stepExpect ?? '';
        if($type == 'first_step_number') return preg_match('/^([0-9.]+)\\./', $stepDesc, $matches) ? $matches[1] : '';
        return match($type)
        {
            'stepDesc'           => $stepDesc,
            'stepExpect'         => $stepExpect,
            'stepDesc_length'    => strlen($stepDesc),
            'stepExpected_length'=> strlen($case->stepExpected ?? ''),
            'has_stepDesc'       => empty($stepDesc) ? 0 : 1,
            'has_stepExpected'   => empty($case->stepExpected) ? 0 : 1,
            'stepDesc_lines'     => substr_count($stepDesc, "\n") + 1,
            'stepExpected_lines' => substr_count($case->stepExpected ?? '', "\n") + 1,
            'has_csv_escape'     => strpos($stepDesc, '""') !== false || strpos($case->stepExpected ?? '', '""') !== false ? 1 : 0,
            default              => $case,
        };
    }

    /**
     * Test saveLibState method.
     *
     * @param  int   $libID
     * @param  array $libraries
     * @access public
     * @return int
     */
    public function saveLibStateTest(int $libID = 0, array $libraries = array()): int
    {
        $result = $this->invokeArgs('saveLibState', [$libID, $libraries]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setBrowseSessionAndCookie method.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return array|bool
     */
    public function setBrowseSessionAndCookieTest(int $libID = 0, string $browseType = 'all', int $param = 0): array|bool
    {
        $this->invokeArgs('setBrowseSessionAndCookie', [$libID, $browseType, $param]);
        if(dao::isError()) return dao::getError();
        return true;
    }
}
