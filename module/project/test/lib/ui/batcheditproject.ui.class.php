<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class batchEditProjectTester extends tester
{
    /**
     * 批量编辑项目时检查页面输入。
     * Check the page input when batch edit the project.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkInput($project = array())
    {
        $form = $this->initForm('project', 'browse', array(), 'appIframe-project');
        $form->dom->selectAllBtn->click();
        $form->dom->batchEditBtn->click();
        $firstID    = $form->dom->id_static_0->getText(); //获取第一行的ID
        $firstBegin = "begin[{$firstID}]";
        $firstEnd   = "end[{$firstID}]";
        $firstAcl   = "acl[{$firstID}]";
        if(isset($project['name']))  $form->dom->name_0->setValue($project['name']);
        if(isset($project['begin'])) $form->dom->$firstBegin->datePicker($project['begin']);
        if(isset($project['end']))   $form->dom->$firstEnd->datePicker($project['end']);
        if(isset($project['acl']))   $form->dom->$firstAcl->picker($project['acl']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        return $this->checkResult($project, $firstID);
    }

    /**
     * 批量编辑项目后结果检查。
     * Check the result after batch edit the project.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkResult($project = array(), $firstID)
    {
        /* 检查批量编辑页面提示信息 */
        $batchEditPage = $this->loadPage('project', 'batchEdit');
        $existName     = '敏捷项目2';
        if($this->response('method') != 'view')
        {
            $firstNameTipDom  = "name[{$firstID}]Tip"; //第一行的名称提示信息
            /* 检查项目名称不能为空 */
            if($project['name'] == '')
            {
                $nameTipText = $batchEditPage->dom->$firstNameTipDom->getText();
                $nameTip     = sprintf($this->lang->error->notempty, $this->lang->project->name);
                return ($nameTipText == $nameTip) ? $this->success('项目名称必填提示信息正确') : $this->failed('项目名称必填提示信息不正确');
            }
             /* 检查项目名称唯一 */
            if($project['name'] == $existName)
            {
                $nameTipText = $batchEditPage->dom->alertModal('text');
                $nameTip     = 'ID' . $firstID . sprintf($this->lang->error->repeat, $this->lang->project->name, $existName);
                return ($nameTipText == $nameTip) ? $this->success('项目名称唯一提示信息正确') : $this->failed('项目名称唯一提示信息不正确');
            }
            /* 检查计划完成日期不能大于计划开始日期 */
            if($project['begin'] > $project['end'])
            {
                $endTipText = $batchEditPage->dom->alertModal('text');
                $endTip     = 'ID' . $firstID . sprintf($this->lang->error->gt, $this->lang->project->end, $project['begin']);
                return ($endTipText == $endTip) ? $this->success('计划完成校验提示信息正确') : $this->failed('计划完成校验提示信息不正确');
            }
        }

        /* 跳转到项目列表页面，按照项目名称进行搜索 */
        $browsePage = $this->loadPage('project', 'browse');
        $browsePage->dom->search($searchList = array("项目名称,包含,{$project['name']}"));
        $browsePage->wait(2);
        $browsePage->dom->projectName->click();
        $browsePage->wait(2);
        /* 进入项目概况页面 */
        $browsePage->dom->settings->click();
        $viewPage = $this->loadPage('project', 'view');
        $viewPage->wait(2);

        /* 断言检查字段信息是否正确 */
        if($viewPage->dom->projectName->getText() != $project['name']) return $this->failed('名称错误');
        if($viewPage->dom->acl->getText()         != $this->lang->project->shortAclList->open) return $this->failed('权限错误');
        return $this->success('批量编辑项目成功');
    }
}
