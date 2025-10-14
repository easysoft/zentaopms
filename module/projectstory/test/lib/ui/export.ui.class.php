<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class exportTester extends tester
{
    /**
     * 项目需求导出。
     * Project story export.
     *
     * @param  array $projectStory
     * @access public
     */
    public function export($projectStory = array())
    {
        $form = $this->initForm('projectstory', 'story', array('project' => '1'), 'appIframe-project');
        $form->dom->exportBtn->click();
        if(isset($projectStory['filename'])) $form->dom->fileName->setValue($projectStory['filename']);
        if(isset($projectStory['format']))   $form->dom->format->picker($projectStory['format']);
        if(isset($projectStory['encoding'])) $form->dom->encoding->picker($projectStory['encoding']);
        if(isset($projectStory['data']))     $form->dom->data->picker($projectStory['data']);
        $form->dom->exportBtnAlert->click();
        $form->wait(2);

        /*添加断言，是否导出成功*/
        if($form->dom->exportBtnAlert) return $this->failed('项目需求导出失败');
        return $this->success('项目需求导出成功');
    }
}
