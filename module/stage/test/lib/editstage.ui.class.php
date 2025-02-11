<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editStageTester extends tester
{
    /**
     * Edit a stage.
     * 编辑一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function editStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->editBtn->click();
        }
