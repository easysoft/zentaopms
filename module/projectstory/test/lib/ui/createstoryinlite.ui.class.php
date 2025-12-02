<?php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lijie
 * @package     story
 * @link        http://www.zentao.net
 */
include dirname(__FILE__, 6).'/test/lib/ui.php';
class createStoryInLiteTester extends tester
{
    /**
     * Create a default story.
     *
     * @param  string $storyName
     * @access public
     * @return object
     */
    public function createDefault($storyName, $reviewer)
    {
        $createStoryParam = array(
            'product'   => '1',
            'branch'    => '',
            'moduleID'  => '0',
            'storyID'   => '0',
            'projectID' => '1',
            'bugID'     => '0',
            'planID'    => '0',
            'todoID'    => '0',
            'extra'     => '',
            'storyType' => 'story'
        );

        $this->switchVision('lite');
        if($this->page->getCookie('vision') != 'lite')
        {
            $this->switchVision('lite', 8);
        }
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');  //由于创建目标后会跳转到记录了session的页面，所以先进入一次对应目标的列表页面
        $form->wait(3);
        $form = $this->initForm('story', 'create', $createStoryParam, 'appIframe-project'); //再进入创建目标页面
        $form->dom->titleInLite->setValue($storyName);
        if($reviewer != NULL)
        {
            $form->dom->reviewer->click();
            $form->dom->reviewerAdmin->click();
        }
        $form->dom->saveInLite->click();
        $form->wait(3);

        //创建失败检查提示是否正确，名称为空和评审人为空提示
        if($this->response('method') != 'story')
        {
            $form = $this->loadPage('story', 'create');
            if($storyName == '')
            {
                $srCommen = $form->dom->srCommen->getText();
                $nameTip = sprintf($this->lang->error->notempty, $srCommen);
                if($form->dom->titleTip->getText()  == $nameTip) return $this->success('创建目标页面名称为空提示正确');
            }

            if(empty($reviewer))
            {
                $reviwerTip = sprintf($this->lang->error->notempty, $this->lang->story->reviewer);
                if($form->dom->reviewerTip->getText()  == $reviwerTip) return $this->success('创建目标页面评审人为空提示正确');
            }
            return $this->failed('创建目标页面提示信息不正确');
        }

        $viewPage = $this->initForm('projectstory', 'view', array('storyID' => '1', 'projectID' => '1'), 'appIframe-project');
        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('目标名称不正确');
        if($viewPage->dom->storyStatus->getText() != $this->lang->story->statusList->reviewing) return $this->failed('目标状态不正确');
        $viewPage->dom->TargetLife->click();
        if(strpos($viewPage->dom->openedBy->getText() , 'admin') === false) return $this->failed('创建人不正确');

        return $this->success('创建'.$storyName.'成功');
    }
}
