<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editProjectLiteTester extends tester
{
    /**
     * 编辑项目时检查页面输入。
     * Check the page input when editing the project.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkInput($project = array())
    {
        $form = $this->loadPage('project', 'browse');
        $this->switchVision('lite');
        $form = $this->initForm('project', 'edit', array('projecID' => 1), 'appIframe-project');
        if(isset($project['name']))  $form->dom->name->setValue($project['name']);
        if(isset($project['begin'])) $form->dom->begin->datePicker($project['begin']);
        if(isset($project['end']))   $form->dom->end->datePicker($project['end']);
        if(isset($project['PM']))    $form->dom->PM->picker($project['PM']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        return $this->checkResult($project);
    }

    /**
     * 编辑项目后结果检查。
     * Check the result after editing the project.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkResult($project = array())
    {
        /* 检查编辑页面提示信息 */
        $form = $this->loadPage('project', 'edit');
        if($this->response('method') != 'view')
        {
            if($form->dom->nameTip)
            {
                /* 检查项目名称不能为空 */
                if($project['name'] == '')
                {
                    $nameTipText = $form->dom->nameTip->getText();
                    $nameTip     = sprintf($this->lang->error->notempty, $this->lang->project->name);
                    return ($nameTipText == $nameTip) ? $this->success('编辑项目表单页提示信息正确') : $this->failed('编辑项目表单页提示信息不正确');
                }
                /* 检查项目名称唯一 */
                else
                {
                    $existName = '运营界面项目2';
                    $nameTipText = $form->dom->nameTip->getText();
                    $nameTip     = sprintf($this->lang->error->unique, $this->lang->project->name, $existName);
                    return ($nameTipText == $nameTip) ? $this->success('编辑项目表单页提示信息正确') : $this->failed('编辑项目表单页提示信息不正确');
                }
            }
            if($form->dom->endTip)
            {
                /* 检查结束日期不能为空 */
                if($project['end'] == '')
                {
                    $endTipText = $form->dom->endTip->getText();
                    $endTip     = sprintf($this->lang->copyProject->endTip, '');
                    return ($endTipText == $endTip) ? $this->success('编辑项目表单页提示信息正确') : $this->failed('编辑项目表单页提示信息不正确');
                }
                /* 检查结束日期不能小于开始日期 */
                if($project['begin'] > $project['end'])
                {
                    $endTipText = $form->dom->endTip->getText();
                    $endTip     = sprintf($this->lang->error->gt, $this->lang->project->end, $project['begin']);
                    return ($endTipText == $endTip) ? $this->success('编辑项目表单页提示信息正确') : $this->failed('编辑项目表单页提示信息不正确');
                }
            }
        }

        /* 跳转到项目列表页面，按照项目名称进行搜索 */
        $browsePage = $this->initForm('project', 'browse');
