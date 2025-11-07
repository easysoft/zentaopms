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
class assignToStoryTester extends tester
{
    /**
     * check the assignedTo after assignto story.
     * @param string $storyType
     * @param int $storyID
     * @access public
     * @return object
     */
    public function assignToStory($storyType, $storyID)
    {
        $form = $this->openURL($storyType, 'view', array('id' => $storyID), 'appIframe-product');  //进入需求详情页
        $form = $this->loadPage($storyType, 'view');
        $form->dom->btn($this->lang->story->assignTo)->click();  //点击指派按钮
        $form->wait(1);

        $form->dom->assignedTo->picker('admin'); //指派人选择admin
        $form->dom->assignToBtn->click();
        $form->wait(3);

        $viewPage = $this->loadPage($storyType, 'view');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();
        if(strpos($viewPage->dom->assignToONE->getText(), 'admin') === false)  return $this->failed('指派人不正确'); //检查需求详情页指派人是否正确

        if($storyType == 'story')
        {
            return $this->success('指派研发需求成功');
        }
        elseif($storyType == 'requirement')
        {
            return $this->success('指派用户需求成功');
        }
        else
        {
            return $this->success('指派业务需求成功');
        }
    }

    /**
     * check the info after batchassignto stories.
     * @param string $storyType
     * @param int $storyID
     * @access public
     * @return object
     */
    public function batchAssignStory($storyType, $storyID)
    {
        $browseParam = array(
            'productID'  => '1',
            'branch'     => '',
            'browseType' => 'unclosed',
            'parm'       => '0',
            'storyType'  => $storyType
        );

        $browsePage = $this->initForm('product', 'browse', $browseParam, 'appIframe-product');
        $browsePage->dom->firstSelect->click();
        $browsePage->dom->batchAssign->click();
        $browsePage->dom->batchAssignSearch->setValue('admin');
        $browsePage->wait(2);
        $browsePage->dom->batchAssignAdmin->click();
        $browsePage->wait(3);

        $viewPage = $this->initForm($storyType, 'view', array('id' => $storyID), 'appIframe-product');
        $viewPage->dom->btn($this->lang->story->legendLifeTime)->click();

        if(strpos($viewPage->dom->assignToONE->getText(), 'admin') === false)  return $this->failed('指派人不正确'); //检查需求详情页指派人是否正确

        if($storyType == 'story')
        {
            return $this->success('批量指派研发需求成功');
        }
        elseif($storyType == 'requirement')
        {
            return $this->success('批量指派用户需求成功');
        }
        else
        {
            return $this->success('批量指派业务需求成功');
        }
    }
}
