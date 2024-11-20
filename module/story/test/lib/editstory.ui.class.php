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
include dirname(__FILE__, 5).'/test/lib/ui.php';
class editStoryTester extends tester
{
    /**
     * Edit a story.
     * @param   string $storyID, $storyType, $storyFrom
     * @access  public
     * @return  object
     */
    public function editStory($storyID, $storyType, $storyFrom)
    {
        $editStoryParam = array(
            'storyID'     => $storyID,
            'kanbanGroup' => 'default',
            'storyType'   => $storyType,
        );
        /* 提交表单 */
        $form = $this->initForm($storyType, 'edit', $editStoryParam, 'appIframe-product');
        $form->dom->source->picker($storyFrom);
        $form->dom->assignedTo->picker('admin');
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $browsePage = $this->loadPage('story', 'view', $storyID);

        $viewPage = $this->loadPage('story', 'view');
        if($viewPage->dom->storyFrom->getText() != $storyFrom) return $this->failed('需求来源不正确');

        if($storyType == 'requirement')
        {
            return $this->success('编辑用户需求成功');
        }
        if($storyType == 'story')
        {
            return $this->success('编辑研发需求成功');
        }
        else
        {
            return $this->success('编辑业务需求成功');
        }
    }

    /**
     * batch edit a story.
     * @param   string $storyFrom
     * @access  public
     * @return  object
     */
    public function batchEditStory($storyFrom)
    {
        $browsePage = $this->initForm('product', 'browse', '1');
        $browsePage->dom->firstSelect->click();
        $browsePage->dom->batchEdit->click();
        sleep(1);
        $batchEdit = $this->loadPage('story', 'batchEdit');
        $batchEdit->dom->batchSource->picker($storyFrom);
        $batchEdit->dom->batchEditSave->click();
        $batchEdit->wait(1);

        $viewPage = $this->initForm('story', 'view', array('storyID' => '1'), 'appIframe-product');
        if($viewPage->dom->storyFrom->getText() != '客户') return $this->failed('需求来源不正确');

        return $this->success('批量编辑研发需求成功');
    }
}
