<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class groupTester extends tester
{
    /**
     * Create project group.
     * 创建项目权限分组
     *
     * @param  array $project
     * @access public
     */
    public function createGroup($project = array())
    {
        $form = $this->initForm('project', 'group', array('projectID' => '1'), 'appIframe-project');
        $form->dom->createGroupBtn->click();
        if(isset($project['groupname'])) $form->dom->groupName->setValue($project['groupname']);
        if(isset($project['groupdesc'])) $form->dom->groupDesc->setValue($project['groupdesc']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        return $this->checkResult($project,$form);
    }

    /**
     * Check the result after creating the project group.
     *
     * @param  array $project
     * @access public
     * @return object
     */
    public function checkResult($project,$form)
    {
        /*检查创建分组弹窗中的提示信息是否正确*/
        if($form->dom->nameTip)
        {
        $nameTipform = $form->dom->nameTip->getText();
        $nameTip     = sprintf($this->lang->error->notempty, $this->lang->group->name);
        return ($nameTipform == $nameTip) ? $this->success('项目创建分组提示信息正确') : $this->failed('项目创建分组提示信息不正确');
        }
        /*创建项目分组后检查列表页的名称和描述是否正确*/
        else if($form->dom->nameTip === false)
        {
            $groupPage = $this->loadPage('project', 'group');
            if($groupPage->dom->groupNameList->getText() != $project['groupname']) return $this->failed('分组名称错误');
            if($groupPage->dom->groupDescList->getText() != $project['groupdesc']) return $this->failed('分组描述错误');

            return $this->success('项目分组创建成功');
        }
    }
}
