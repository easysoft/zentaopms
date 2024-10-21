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
class reviewStoryTester extends tester
{
    /**
     * check the reviewer and status after review story.
     * @param string
     * @access public
     * @return object
     */
    public function reviewStory($result, $status)
    {
        $form = $this->initForm('story', 'review', array('id' => 1), 'appIframe-product');  //进入研发评审页面

        $form->dom->result->picker($result); //选择研发评审结果
        $form->dom->assignedTo->picker('admin'); //指派人选择admin
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $viewPage = $this->loadPage('story', 'view');
        $viewPage->wait(1);

        if($viewPage->dom->status->getText() != $status) return $this->fail('需求状态错误');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if($viewPage->dom->reviewer->getText() != 'admin') return $this->fail('需求评审人错误');

        return $this->success('评审研发需求成功');
    }

    /**
     * check the story status after submitreview.
     * @access public
     * @return object
     */
    public function submitReview()
    {
        /*进入需求详情页点击提交评审按钮*/
        $form = $this->initForm('story', 'view', array('id' => 1), 'appIframe-product');
        $form->dom->btn($this->lang->story->submitReview)->click();
        $form->wait(1);

        $form->dom->subReviewerBtn->click();
        $form->dom->subReviewer->clickByMouse();
        $form->dom->submitReviewSave->click();
        $form->wait(1);

        /*需求详情页检查需求状态*/
        $viewPage = $this->loadPage('story', 'view');
        $viewPage->wait(1);

        if($viewPage->dom->status->getText() != '评审中') return $this->fail('需求状态错误');

        return $this->success('提交评审成功');
    }

}
