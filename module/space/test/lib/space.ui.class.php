#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class space extends tester
{
    public function createApplication($url = array(), $application = array())
    {
        $this->login();
        $form = $this->initForm('space', 'createApplication',$url, 'appIframe-devops');
    }
}
