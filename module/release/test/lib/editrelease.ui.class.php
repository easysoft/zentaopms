<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editReleaseTester extends tester
{
    /**
     * Edit release.
     * 编辑发布
     *
     * @param  array $release
     * @access public
     */
    public function editRelease($release)
    {
        $browseForm = $this->initForm('release', 'browse', array('product' => 1), 'appIframe-product');
        $browseForm->dom->editBtn->click();
        $form = $this->initForm('release', 'edit', array('releaseID' => 1), 'appIframe-product');

        if(isset($release['systemname'])) $form->dom->system->picker($release['systemname']);
        if(isset($release['name']))       $form->dom->name->setValue($release['name']);
        if(isset($release['status']))     $form->dom->status->picker($release['status']);
