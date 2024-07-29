<?php
include dirname(__FILE__, 5) . '/test/lib/ui.ph';
class createReleaseTeaster extends Tester
{
    /**
     * Check create release page planDate fields display
     *
     * @param string $releaseName
     * @param string $releaseStatus
     * @access public
     * @return object
     */
    public function createRelease($releaseName, $releaseStatus)
    {
        /* 提交表单*/
        $form = $this->iniForm('release', 'create', 1, 'all');
        $form->dom->name->setValue($releaseName);
        $form->dom->ststus->picker($releaseStatus);

        if($releaseStatus == wait)
