<?php
declare(strict_types=1);
/**
 * The tao file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mrTao extends mr
{
    /**
     * 创建合并请求。
     * Insert a merge request.
     *
     * @param  object    $MR
     * @access protected
     * @return bool
     */
    protected function insertMr(object $MR): bool
    {
        $this->dao->insert(TABLE_MR)->data($MR, $this->config->mr->create->skippedFields)
            ->batchCheck($this->config->mr->create->requiredFields, 'notempty')
            ->checkIF($MR->needCI, 'jobID',  'notempty')
            ->exec();

        return !dao::isError();
    }
}
