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
class changeStoryInLiteTester extends tester
{
    /**
     * Create a default story.
     *
     * @param  string $storyName
     * @access public
     * @return object
     */
    public function changeStoryInLite($storyName, $reviewer)
    {
        $this->switchVision('lite');
        if($this->page->getCookie('vision') != 'lite')
        {
            $this->switchVision('lite', 8);
        }
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project'); //变更目标前需要进入列表页面，获取项目ID
        $form->wait(3);
        $form = $this->initForm('story', 'change', array('storyID' => '1', 'from' => 'project'), 'appIframe-project');
        $form->dom->title->setValue($storyName);
        if($reviewer != NULL)
        {
            $form->dom->reviewer->click();
            $form->dom->reviewerAdmin->click();
        }
        $form->dom->btn($this->lang->save)->click();
        $form->wait(3);

        //创建失败检查提示是否正确，名称为空和评审人为空提示
        if($this->response('method') != 'view')
        {
            $form = $this->loadPage('story', 'change');
            if($storyName == '')
            {
                $srCommon = $form->dom->srCommon->getText();
                $nameTip = sprintf($this->lang->error->notempty, $srCommon);
                if($form->dom->alertModal('text')  == $nameTip) return $this->success('变更目标页面名称为空提示正确');
            }

            if(empty($reviewer))
            {
                $reviwerTip = sprintf($this->lang->error->notempty, $this->lang->story->reviewers);
                if($form->dom->alertModal('text')  == $reviwerTip) return $this->success('变更目标页面评审人为空提示正确');
            }
            return $this->failed('变更目标页面提示信息不正确');
        }

        $viewPage = $this->loadPage('projectstory', 'view');
        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('目标名称不正确');
        if($viewPage->dom->storyStatus->getText() != $this->lang->story->statusList->reviewing) return $this->failed('目标状态不正确');

        return $this->success('变更目标成功');
    }
}
