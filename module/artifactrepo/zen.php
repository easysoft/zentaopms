<?php
declare(strict_types=1);
/**
 * The zen file of artifactrepo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng gang<zenggang@easycorp.ltd>
 * @package     artifactrepo
 * @link        https://www.zentao.net
 */
class artifactrepoZen extends artifactrepo
{
    /**
     * 更新版本库状态。
     * Update artifact repo status.
     *
     * @param  int       $artifactRepoID
     * @param  string    $status
     * @access protected
     * @return bool
     */
    protected function updateStatus(int $artifactRepoID, string $status): bool
    {
        $this->dao->update(TABLE_ARTIFACTREPO)->set('status')->eq($status)->where('id')->eq($artifactRepoID)->exec();

        return !dao::isError();
    }
}

