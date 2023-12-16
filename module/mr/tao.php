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
class mrTao extends mrModel
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

    /**
     * 根据合并请求获取关联对象信息。
     * Get story,task,bug pairs which linked MR.
     *
     * @param  int    $MRID
     * @param  string $objectType story|task|bug
     * @access public
     * @return array
     */
    protected function getLinkedObjectPairs(int $MRID, string $objectType = 'story'): array
    {
        return $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('AType')->eq('mr')
            ->andWhere('BType')->eq($objectType)
            ->andWhere('AID')->eq($MRID)
            ->fetchPairs();
    }
}
