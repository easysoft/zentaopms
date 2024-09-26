<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class projectExportTester extends tester
{
    /**
     * 项目导出。
     * Project export.
     *
     * @param  array $project
     * @access public
     */
    public function projectExport($project = array())
    {
        $form = $this->initForm('project', 'browse');
        $form->dom->exportBtn->click();
        if(isset($project['filename'])) $form->dom->fileName->setValue($project['filename']);
        if(isset($project['format']))   $form->dom->format->picker($project['format']);
