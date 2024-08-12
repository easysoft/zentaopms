#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';

class repo extends tester
{
    public function createMaintain($maintain = array())
    {
        $this->login();
        $form = $this->initForm('repo', 'create', '', 'appIframe-devops');
        $form->dom->SCM->picker($maintain['SCM']);
        $form->dom->serviceHost->picker($maintain['serviceHost']);
        $form->dom->serviceProject->picker($maintain['serviceProject']);
        $form->dom->{'product[]'}->multiPicker($maintain['product']);
        $form->dom->desc->setValue($maintain['desc']);
    }
}
