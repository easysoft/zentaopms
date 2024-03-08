<?php
class result
{
    public $result;

    public $caseTitle;
    public $caseCode;
    public $reportFile;
    public $reportURL;


    public static function create($caseTitle, $caseCode)
    {
        $self = new self;
        $self->caseTitle = $caseTitle;
        $self->caseCode  = $caseCode;
    }

    /**
     * Create and init report.
     *
     * @access public
     * @return void
     */
    public function initReport($caseTitle, $caseCode)
    {
        $reportType     = $this->config->reportType;
        $reportTemplate = $this->config->reportTemplate[$reportType];

        $reportPath = $this->config->reportRoot . DS . $caseCode . DS;
        $suffix     = ($reportType == 'markdown') ? 'md' : $reportType;
        $this->reportFile = $reportPath . date('ymdhis') . '.' . $suffix;
        $this->reportURL  = str_replace($this->config->reportRoot, $this->config->reportWebRoot, $this->reportFile);

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
    public static function saveReport($message)
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
    public static function endReport()
    {
        $message = '</div></body></html>';
        self::saveReport($message);
    }

    public static function setResult($result)
    {
        $self = new self;
        $self->result = $result;
    }

    /**
     * Get result of the case.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $result = json_decode($this->driver->__toString(), true);
        $this->driver->errors = array();

        if($result['errors'])
        {
            foreach($result['errors'] as $error) echo str_replace("\n", '', $error) . PHP_EOL;

            return array();
        }

        return $result;
    }
}
