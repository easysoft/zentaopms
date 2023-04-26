<?php declare(strict_types=1);
/**
 * The tao file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectTao extends projectModel 
{
    /**
     * Update project table when start a project.
     * @param  int    $projectID
     * @param  object $project
     * @access protected 
     * @return void
     */
    protected function doStart(int $projectID, object $project):void
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->start->requiredFields, 'notempty')
            ->checkIF($project->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq((int)$projectID)
            ->exec();
    }
}
