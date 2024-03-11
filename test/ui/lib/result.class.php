<?php
class result
{
    public $result;

    public $caseTitle;
    public $caseCode;
    public $reportFile;
    public $reportURL;
    public $errors = array();


    public function create($caseTitle, $caseCode)
    {
        $this->caseTitle = $caseTitle;
        $this->caseCode  = $caseCode;
    }

    /**
     * Create and init report.
     *
     * @access public
     * @return void
     */
    public function initReport($caseTitle, $caseCode)
    {
        global $config;
        $reportType     = $config->reportType;
        $reportTemplate = $config->reportTemplate[$reportType];

        $reportPath = $config->reportRoot . DS . $caseCode . DS;
        $suffix     = ($reportType == 'markdown') ? 'md' : $reportType;
        $this->reportFile = $reportPath . date('ymdhis') . '.' . $suffix;
        $this->reportURL  = str_replace($config->reportRoot, $config->reportWebRoot, $this->reportFile);

        if(!is_dir($reportPath)) mkdir($reportPath, 0777, true);

        $reportContent = str_replace(array('{TITLE}', '{CODE}'), array($caseTitle, $caseCode), $reportTemplate);

        file_put_contents($this->reportFile, $reportContent);
    }

    /**
     * Export test report of a page.
     *
     * @param  array  $alertsMessage
     * @access public
     * @return void
     */
    public function saveReport($message)
    {
        $message .= "\n";
        file_put_contents($this->reportFile, $message, FILE_APPEND);
    }

    /**
     * Print close tag.
     *
     * @access public
     * @return void
     */
    public function endReport()
    {
        $message = '</div></body></html>';
        $this->saveReport($message);
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Get results of the case.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        global $driver;
        $results = array();
        $results['caseTitle']     = $this->caseTitle;
        $results['caseCode']      = $this->caseCode;
        $results['reportFile']    = $this->reportFile;
        $results['reportWebRoot'] = $this->reportURL;
        $results['errors']        = $this->errors;
        $results['driver']        = $driver;

        if(!empty($results['errors']))
        {
            foreach($results['errors'] as $error) echo str_replace("\n", '', $error) . PHP_EOL;

            return array();
        }

        return $results;
    }
}

$results = new result();
