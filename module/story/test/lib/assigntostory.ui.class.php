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
class assignToStoryTester extends tester
{
    /**
     * check the assignedTo after assignto story.
     * @param string
     * @access public
     * @return object
     */
    public function assignToStory()
    {
        $form = $this->openURL('story', 'view', array('id' => 1), 'appIframe-product');  //进入研发需求列表
        $form = $this->loadPage('story', 'view');
        $form->dom->btn($this->lang->story->assignTo)->click();  //点击指派按钮
        $form->wait(1);

        $form->dom->assignedTo->picker('admin'); //指派人选择admin
        $form->dom->assignToBtn->click();
        $form->wait(1);

        $viewPage = $this->loadPage('story', 'view');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if(strpos($viewPage->dom->assignToONE->getText(), 'admin') === false)  return $this->failed('指派人不正确'); //检查需求详情页指派人是否正确

        return $this->success('指派需求成功');
    }
}
