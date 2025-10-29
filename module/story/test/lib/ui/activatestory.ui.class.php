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
class activateStoryTester extends tester
{
    /**
     * Check the status of story after activate it.
     * @param string $storyType $storyID $status
     * @access public
     * @return object
     */
    public function activateStory($storyType, $storyID, $status)
    {
        $form = $this->initform($storyType, 'view', array('id' => $storyID), 'appIframe-product');  //进入业务需求详情页
        $form->dom->btn($this->lang->story->activate)->click();  //点击激活需求按钮
        $form->wait(1);

        $form->dom->assignedTo->picker('admin'); //选择指派人
        $form->dom->activate->click();           //点击激活按钮
        $form->wait(3);

        $viewPage = $this->loadPage($storyType, 'view');   //进入需求详情页查看状态是否与关闭前一致
        if($viewPage->dom->status->getText() != $status) return $this->failed('激活需求后状态不正确');

		if($storyType == 'story'){
            return $this->success('激活研发需求成功');
        }
        elseif($storyType == 'requirement'){
            return $this->success('激活用户需求成功');
        }
        else{
            return $this->failed('激活业务需求成功');
        }
    }
}
