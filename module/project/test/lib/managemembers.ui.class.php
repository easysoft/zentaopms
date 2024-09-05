<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageMembersTester extends tester
{
    /**
     * Check the page input when managing members.
     * 增加项目团队成员
     *
     * @param  array $members
     * @access public
     */
    public function addMembers(array $members)
    {
        $form = $this->initForm('project', 'manageMembers', array('projectID' => '1'), 'appIframe-project');
        if(isset($members['account'])) $form->dom->account->picker($members['account']);
        if(isset($members['role']))    $form->dom->role1->setValue($members['role']);
        if(isset($members['day']))     $form->dom->days1->setValue($members['day']);
        if(isset($members['hours']))   $form->dom->hours1->setValue($members['hours']);

        $form->dom->btn($this->lang->save)->click();
