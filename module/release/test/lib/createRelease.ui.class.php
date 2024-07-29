<?php
include dirname(__FILE__, 5) . '/test/lib/ui.ph';
class createReleaseTeaster extends Tester
{
    public function createRelease($releaseName, $releaseStatus)
    {
        /* 提交表单*/
        $form = $this->iniForm('release', 'create', 1, 'all');
        $form->dom->name->setValue($releaseName);
        $form->dom->ststus->picker($releaseStatus);

        if($releaseStatus == wait)
        {
            if($form->dom->date->getText() != '计划发布日期') return $this->fail('状态选择未开始时，没有显示计划发布日期');
        }
    }
}
