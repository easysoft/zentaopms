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
        $form = $this->openURL('story', 'review', array('id' => 1), 'appIframe-product');  //进入研发评审页面
        $form = $this->loadPage('story', 'review');

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
}
