<?php
declare(strict_types=1);
/**
 * The tao file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

class repoTao extends repoModel
{
    /**
     * 获取最后一次提交信息。
     * Get last revision.
     *
     * @param  int       $repoID
     * @access protected
     * @return object|false
     */
    protected function getLastRevision(int $repoID): object|false
    {
        return $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('time_desc')->fetch();
    }
}

