<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
     * check the stuts after close story
     * check the stuts of the childstory after close parent
     * @param string closeReason
     * @access public
     * @return object
     */
    public function closeStory($storyID, $closeReason)
    {
        $form = $this->openURL('story', 'view', array('id' => $storyID), 'appIframe-product');  //进入研发需求详情页
        $form = $this->loadPage('story', 'view');
        $form->dom->btn($this->lang->story->close)->click();  //点击关闭需求按钮

        $form->wait(1);

        $form->dom->closedReason->picker($closeReason); //选择关闭原因
        $form->dom->getElement("//*[@id='zin_story_close_{$storyID}_form']/div[4]/div/button")->click();
        $form->wait(1);

        $viewPage = $this->loadPage('story', 'view');
        if($viewPage->dom->status->getText() != '已关闭') return $this->failed('需求状态不正确');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if($viewPage->dom->closeReason->getText() != $closeReason) return $this->failed('需求关闭原因不正确');

        return $this->success('关闭需求成功');
    }
}