<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class assignStoryTester extends tester
{
    /**
     * 需求指派。
     * Assign story.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function assignTo($user)
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');
        $form->dom->firstAssignTo->click();
        $form->wait(2);
        $form->dom->assignedTo->picker($user);
        $form->wait(2);
        $form->dom->assignBtn->click();
