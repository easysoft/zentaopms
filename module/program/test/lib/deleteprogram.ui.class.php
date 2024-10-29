<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 删除项目集。
     *
     * @param  string $programName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function deleteProgram($programName)
    {
        /*删除列表第一个项目集*/
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->fstdeleteBtn->click();
