<?php declare(strict_types=1);
/**
 * The zen file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectZen extends project
{
    /**
     * Append extras data to post data.
     * @param  object $postData
     * @access protected 
     * @return int|object 
     */
    protected function prepareStartExtras(object $postData):object
    {
        $postData->status         = 'doing';
        $postData->lastEditedBy   = $this->app->user->account;
        $postData->lastEditedDate = helper::now();

        return $postData;
    }

    /**
     * Send variables to view page.
     * @param  object $project
     * @access protected 
     * @return int|object 
     */
    protected function buildStartForm(object $project)
    {
        $this->view->title      = $this->lang->project->start;
        $this->view->position[] = $this->lang->project->start;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $project->id);
        $this->display();
    }

    /**
     * After starting the project, do other operations.
     * @param  object $project
     * @param  array  $changes
     * @param  object $postData
     * @param  string $comment
     * @access protected 
     * @return int|object 
     */
    protected function responseAfterStart(object $project, array $changes, object $postData, string $comment) :int|object
    {
        if($comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $project->id, 'Started', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        /* Start all superior projects. */
        if($project->parent)
        {
            $path = explode(',', $project->path);
            unset($path[$project->id]);

            $parentList = $this->project->getByIdList($path);

            foreach($parentList as $parent)
            {
                if($parent->status == 'wait' || $parent->status == 'suspended')
                {
                    $changes = $this->project->start($parent->id, $postData);
                    if(dao::isError()) return print(js::error(dao::getError()));

                    if($comment != '' or !empty($changes))
                    {
                        $actionID = $this->loadModel('action')->create('project', $parent->id, 'Started', $comment);
                        $this->action->logHistory($actionID, $changes);
                    }
                }
            }
        }

        $this->loadModel('common')->syncPPEStatus($project->id);

        $this->executeHooks($project->id);
        return print(js::reload('parent.parent'));
    }
}
