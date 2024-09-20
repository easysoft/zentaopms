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
     *
     * @param   string $storyFrom
     * @access  public
     * @return  object
     */
    public function editStory($storyFrom)
    {
        $editStoryParam = array(
            'storyID'     => '1',
            'kanbanGroup' => 'default',
            'storyType'   => 'story'
        );
        /* 提交表单 */
        $form = $this->initForm('story', 'edit', $editStoryParam, 'appIframe-product');
        $form->dom->source->picker($storyFrom);
        $form->dom->assignedTo->picker('admin');
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $browsePage = $this->loadPage('story', 'view', '1');

        $viewPafe = $this->loadPage('story', 'view');
        if($viewPafe->dom->storyFrom->getText() != '客户') return $this->failed('需求来源不正确');

        return $this->success('编辑研发需求成功');
    }
}
