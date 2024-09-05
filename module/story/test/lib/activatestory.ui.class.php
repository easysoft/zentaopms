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
class activateStoryTester extends tester
{
    /**
     * check the stuts after activate a  story
     * check the stuts of the childstory after close parent
     * @param string $storyID
     * @access public
     * @return object
     */
    public function activateStory($storyID, $status)
    {
        $form = $this->openURL('story', 'view', array('id' => $storyID), 'appIframe-product');  //进入研发需求详情页
        $form = $this->loadPage('story', 'view');
        $form->dom->btn($this->lang->story->activate)->click();  //点击激活需求按钮

        $form->wait(1);

        $form->dom->assignedTo->picker('admin'); //选择指派人
        $form->dom->activate->click();           //点击激活按钮
        $form->wait(1);

        $viewPage = $this->loadPage('story', 'view');   //进入需求详情页查看状态是否与关闭前一致
        if($viewPage->dom->status->getText() != $status) return $this->failed('激活需求后状态不正确');

        return $this->success('激活需求成功');
    }
}
