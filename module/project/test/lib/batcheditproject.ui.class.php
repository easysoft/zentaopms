<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $firstID = $form->dom->id_static_0->getText(); //获取第一行的ID
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
        $form = $this->loadPage('project', 'batchEdit');
        if($this->response('method') != 'view')
        {   $firstNameTipDom  = "name[{$firstID}]Tip"; //第一行的名称提示信息
            /* 检查项目名称不能为空 */
            if($form->dom->$firstNameTipDom && $project['name'] == '')
            {
                $nameTipText = $form->dom->$firstNameTipDom->getText();
                $nameTip     = sprintf($this->lang->error->notempty, $this->lang->project->name);
