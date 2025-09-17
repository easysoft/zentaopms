<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class projectExportForLiteTester extends tester
{
    /**
     * 运营界面项目导出。
     * Project exporti for lite.
     *
     * @param  array $project
     * @access public
     */
    public function projectExport($project = array())
    {
        $this->switchVision('lite');
        $form = $this->initForm('project', 'browse');
        $form->wait(2);
        $form->dom->all->click();
        $form->wait(2);
        $form->dom->selectBtnLite->click();
        $form->dom->exportBtnLite->click();
        if(isset($project['filename'])) $form->dom->fileName->setValue($project['filename']);
        if(isset($project['format']))   $form->dom->format->picker($project['format']);
        if(isset($project['encoding'])) $form->dom->encoding->picker($project['encoding']);
        if(isset($project['data']))     $form->dom->data->picker($project['data']);

        $form->wait(2);
        $form->dom->exportBtnAlert->click();
        $form->wait(2);
        /*添加断言，是否导出成功*/
        if($form->dom->exportBtnAlert) return $this->failed('项目导出失败');
        return $this->success('项目导出成功');
    }
}
