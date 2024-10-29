<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 关闭项目集。
     *
     * @param  string $programName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function closeProgram($programName)
    {
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadpage('program', 'browse');
        $browsePage->dom->fstStartBtn->click();
        $browsePage->wait(1);
