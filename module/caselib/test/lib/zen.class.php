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
    public function responseAfterShowImportTest(int $libID, array $caseData, int $maxImport, int $pageID, int $stepVars): string
    {
        ob_start();
        $this->invokeArgs('responseAfterShowImport', [$libID, $caseData, $maxImport, $pageID, $stepVars]);
        $output = ob_get_clean
        return $output;
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
    public function getColumnsForShowImportTest(array $firstRow, array $fields): array
    {
        $result = $this->invokeArgs('getColumnsForShowImport', [$firstRow, $fields]);
        if(dao::isError()) return dao::getError();
        return $result;
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
    public function getDataForImportTest(int $maxImport, string $tmpFile, array $fields): array
    {
        $result = $this->invokeArgs('getDataForImport', [$maxImport, $tmpFile, $fields]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getExportCasesFields method.
     *
     * @access public
     * @return array
     */
    public function getExportCasesFieldsTest(): array
    {
        $result = $this->invokeArgs('getExportCasesFields');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFieldsForExportTemplate method.
     *
     * @access public
     * @return array
     */
    public function getFieldsForExportTemplateTest(): array
    {
        $result = $this->invokeArgs('getFieldsForExportTemplate');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFieldsForImport method.
     *
     * @access public
     * @return array
     */
    public function getFieldsForImportTest(): array
    {
        $result = $this->invokeArgs('getFieldsForImport');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getImportHeaderAndColumns method.
     *
     * @param  string $fileName
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getImportHeaderAndColumnsTest(string $fileName, array $fields): array
    {
        $result = $this->invokeArgs('getImportHeaderAndColumns', [$fileName, $fields]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getRowsForExportTemplate method.
     *
     * @param  int    $num
     * @param  array  $modules
     * @access public
     * @return array
     */
    public function getRowsForExportTemplateTest(int $num, array $modules): array
    {
        $result = $this->invokeArgs('getRowsForExportTemplate', [$num, $modules]);
        if(dao::isError()) return dao::getError();
        return $result;
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
    public function getStepsAndExpectsFromImportFileTest(string $field, int $row, string $cellValue): array
    {
        $result = $this->invokeArgs('getStepsAndExpectsFromImportFile', [$field, $row, $cellValue]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processFileForExport method.
     *
     * @param  object $case
     * @param  array  $relatedFiles
     * @access public
     * @return object
     */
    public function processFileForExportTest(object $case, array $relatedFiles): object
    {
        $this->invokeArgs('processFileForExport', [$case, $relatedFiles]);
        if(dao::isError()) return dao::getError();
        return $case;
    }

    /**
     * Test processLinkCaseForExport method.
     *
     * @param  object $case
     * @param  string $type
     * @access public
     * @return object
     */
    public function processLinkCaseForExportTest(object $case): object
    {
        $this->invokeArgs('processLinkCaseForExport', [$case]);
        if(dao::isError()) return dao::getError();
        return $case;
    }

    /**
     * Test processStageForExport method.
     *
     * @param  object $case
     * @access public
     * @return object
     */
    public function processStageForExportTest(object $case): object
    {
        $this->invokeArgs('processStageForExport', [$case]);
        if(dao::isError()) return dao::getError();
        return $case;
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
    public function processStepForExportTest(object $case, array $relatedSteps): object
    {
        $this->invokeArgs('processStepForExport', [$case, $relatedSteps]);
        if(dao::isError()) return dao::getError();
        return $case;
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
