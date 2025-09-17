#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class space extends tester
{
    public function createApplication($url = array(), $application = array())
    {
        $this->login();
        $form = $this->initForm('space', 'browse', '', 'appIframe-devops');
        $form->dom->btn($this->lang->space->install)->click();
        $form->dom->typeExternal->click();
        $form->dom->appType->picker($application['appType']);
        $form->dom->name->setValue($application['name']);
        $form->dom->url->setValue($application['url']);
        $form->dom->token->setValue($application['token']);
        $form->dom->saveButton->click();
    }
}
