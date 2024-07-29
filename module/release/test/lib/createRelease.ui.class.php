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
        if($releaseStatus == normal)
        {
            if($form->dom->date->getText()        != '计划发布日期') return $this->fail('状态选择已发布时，没有显示计划发布日期');
            if($form->dom->releaseDate->getText() != '实际发布日期') return $this->fail('状态选择已发布时，没有显示计划发布日期');
        }

        $form->dom->btn($this->lang->save)->click();
        $this->waitPageLoad();
        $this->checkError();

        /* 查看跳转页面*/
        $bowsePage = $this->loadPage('release', 'view');
        $bowsePage->dom->releaseInfo->click();

        /*查看对应状态下的计划时间显示*/
        if($form->dom->releasedStatus->getText() != $releaseStatus) return $this->fail('状态不符');
        if($releasedStatus == wait)
        {
            if($form->dom->planedDate->getText() != date('Y-m-d')) return $this->fail('状态选择未开始时，计划发布日期不正确');
        }
        if($releasedStatus == normal)
        {
            if($form->dom->planedDate->getText()   != date('Y-m-d')) return $this->fail('状态选择已发布时，计划发布日期不正确');
            if($form->dom->releasedDate->getText() != date('Y-m-d')) return $this->fail('状态选择已发布时，计划发布日期不正确');
        }

        return $this->success();
    }
}
