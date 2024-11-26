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
class closeEpicTester extends tester
{
    /**
     * Check the stuts and closedReason after close an epic.
     *
     * @param string closeReason
     * @access public
     * @return object
     */
    public function closeEpic($storyID, $closeReason)
    {
        $form = $this->initForm('epic', 'view', array('id' => $storyID), 'appIframe-product');  //进入业务需求详情页
        $form->dom->btn($this->lang->story->close)->click();  //点击关闭需求按钮

        $form->wait(1);
        $form->dom->closedReason->picker($closeReason); //选择关闭原因
        $form->dom->closedButton->click();
        $form->wait(1);

        $viewPage = $this->loadPage();
        if($viewPage->dom->status->getText() != '已关闭') return $this->failed('需求状态不正确');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if($viewPage->dom->closeReason->getText() != $closeReason) return $this->failed('需求关闭原因不正确');

        return $this->success('关闭业务需求成功');
    }

    /**
