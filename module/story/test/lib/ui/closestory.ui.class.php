<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
/**
    * The control file of example module of ZenTaoPMS.
    *
    * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
    * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
    * @author      lijie
    * @package     story
    * @link        http://www.zentao.net
    */
class closeStoryTester extends tester
{
    /**
     * Check the stuts and closedReason after close story.
     *
     * @param string closeReason
     * @access public
     * @return object
     */
    public function closeStory($storyID, $closeReason)
    {
        $form = $this->initForm('story', 'view', array('id' => $storyID), 'appIframe-product');  //进入研发需求详情页
        $form->dom->btn($this->lang->story->close)->click();  //点击关闭需求按钮

        $form->wait(1);
        $form->dom->closedReason->picker($closeReason); //选择关闭原因
        $form->dom->getElement("//*[@id='zin_story_close_{$storyID}_form']/div[4]/div/button")->click();
        $form->wait(3);

        $viewPage = $this->loadPage();
        if($viewPage->dom->status->getText() != '已关闭') return $this->failed('需求状态不正确');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if($viewPage->dom->closeReason->getText() != $closeReason) return $this->failed('需求关闭原因不正确');

        return $this->success('关闭研发需求成功');
    }

    /**
     * check the stuts and closedReason after batchclose story.
     *
     * @param string closeReason
     * @access public
     * @return object
     */
    public function batchCloseStory($closeReason)
    {
        /*列表页面点击批量关闭按钮进入批量关闭页面*/
        $browsePage = $this->initForm('product', 'browse', array('product' => '1'));
        $browsePage->dom->firstSelect->click();
        $browsePage->dom->batchMore->click();
        $browsePage->wait(1);
        $browsePage->dom->getElement("/html/body/div[2]/menu/menu/li[1]/a/div/div")->click();
        $browsePage->wait(3);

        $batchClose = $this->loadPage('story', 'batchClose');
        $batchClose->dom->batchClosedReason->picker($closeReason);
        $batchClose->dom->batchClosedSave->click();
        $batchClose->wait(1);

        /*检查需求详情页需求状态和关闭原因*/
        $viewPage = $this->initForm('story', 'view', array('storyID' => '2'), 'appIframe-product');
        if($viewPage->dom->status->getText() != '已关闭') return $this->failed('需求状态不正确');

        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if($viewPage->dom->closeReason->getText() != $closeReason) return $this->failed('需求关闭原因不正确');

        return $this->success('批量关闭研发需求成功');
    }
}
