<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditProjectTester extends tester
{
    /**
     * Batch edit a project.
     *
     * @param  array  $project
     * @access public
     * @return object
     */
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
    public function checkBatchEdit(object $form, string $firstID, array $project)
    {
        if($this->response('method') != 'browse')
        {
            $firstBeginTip  = "begin[{$firstID}]Tip";
            $firstEndTip    = "end[{$firstID}]Tip";
            $firstNameTip   = "name[{$firstID}]Tip";
            if($form->dom->$firstNameTip)
            {
                //检查项目名称不能为空
                $nameTipText = $form->dom->$firstNameTip->getText();
                $nameTip     = sprintf($this->lang->error->notempty, $this->lang->project->name);
                return ($nameTipText == $nameTip) ? $this->success('批量编辑项目表单页提示信息正确') : $this->failed('批量编辑项目表单页提示信息不正确');
            }
            if($form->dom->$firstBeginTip)
            {
                //检查计划开始不能为空
                $beginTipText = $form->dom->$firstBeginTip->getText();
                $beginTip     = sprintf($this->lang->error->notempty, $this->lang->project->begin);
                return ($beginTipText == $beginTip) ? $this->success('批量编辑项目表单页提示信息正确') : $this->failed('批量编辑项目表单页提示信息不正确');
            }
            if($form->dom->$firstEndTip)
            {
                //检查计划完成不能为空
                $endTipText = $form->dom->$firstEndTip->getText();
                $endTip     = sprintf($this->lang->project->copyProject->endTips,'');
                return ($endTipText == $endTip) ? $this->success('批量编辑项目表单页提示信息正确') : $this->failed('批量编辑项目表单页提示信息不正确');
            }
        }
}
