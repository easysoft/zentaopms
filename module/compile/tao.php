<?php
declare(strict_types=1);
/**
 * The tao file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      XX <xx@easycorp.ltd>
 * @package     compile
 * @link        https://www.zentao.net
 */
class compileTao extends compileModel
{
    /**
     * 更新流水线最后同步时间。
     * Update job last sync date.
     *
     * @param  int       $jobID
     * @param  string    $date
     * @access protected
     * @return void
     */
    protected function updateJobLastSyncDate(int $jobID, string $date): void
    {
        $this->dao->update(TABLE_JOB)->set('lastSyncDate')->eq($date)->where('id')->eq($jobID)->exec();
    }

    /**
     * 获取jenkins api接口前缀地址。
     * Get jenkins api url prefix.
     *
     * @param  string    $url
     * @param  string    $pipeline
     * @access protected
     * @return string
     */
    protected function getJenkinsUrlPrefix(string $url, string $pipeline): string
    {
        if(strpos($pipeline, '/job/') !== false)
        {
            $urlPrefix = sprintf('%s%s', $url, $pipeline);
        }
        else
        {
            $urlPrefix = sprintf('%s/job/%s/', $url, $pipeline);
        }

        return $urlPrefix;
    }

    /**
     * 根据构建信息创建compile。
     * Create compile by build info.
     *
     * @param  string    $name
     * @param  int       $jobID
     * @param  object    $build
     * @param  string    $buildType
     * @access protected
     * @return bool
     */
    protected function createByBuildInfo(string $name, int $jobID, object $build, string $buildType): bool
    {
        if($buildType == 'jenkins' && empty($build->queueId)) return false;
        if($buildType == 'gitlab' && empty($build->id)) return false;

        $data = new stdclass();
        $data->name = $name;
        $data->job  = $jobID;

        if($buildType == 'jenkins')
        {
            $data->queue  = $build->queueId;
            $data->status = $build->result == 'SUCCESS' ? 'success' : 'running';
            if($build->result == 'FAILURE') $data->status = 'failure';

            $data->createdDate = date('Y-m-d H:i:s', (int)($build->timestamp / 1000));
        }
        else
        {
            $data->queue  = !empty($build->number) ? $build->number : $build->id;
            $data->status = isset($this->lang->compile->statusList[$build->status]) ? $build->status : 'failure';

            $date = isset($build->created_at) ? strtotime($build->created_at) : time();
            if(isset($build->created)) $date = $build->created;
            $data->createdDate = date('Y-m-d H:i:s', $date);
        }

        $data->createdBy  = $this->app->user ? $this->app->user->account : 'guest';
        $data->updateDate = $data->createdDate ?? date('Y-m-d H:i:s');

        $this->dao->insert(TABLE_COMPILE)->data($data)->exec();
        $this->dao->update(TABLE_JOB)->set('lastExec')->eq($data->createdDate)->set('lastStatus')->eq($data->status)->where('id')->eq($jobID)->exec();
        return !dao::isError();
    }
}
