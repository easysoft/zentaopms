<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditProjectTester extends tester
{
    public function batchEditProject(array $project)
    {
        $form = $this->initForm('project', 'browse', 'appIframe-project');
        $form->dom->selectBtn->click();
        $form->dom->batchEditBtn->click();
        $firstID = $form->dom->id_static_0->getText(); //获取第一行的项目id
        $beginInput = "begin[{$firstID}]";
        $endInput   = "end[{$firstID}]";
        if(isset($project['name']))  $form->dom->name_0->setValue($project['name']);
        if(isset($project['begin'])) $form->dom->$beginInput->datePicker($project['begin']);
        if(isset($project['end']))   $form->dom->$endInput->datePicker($project['end']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkBatchEdit($form, $firstID, $project);
}
