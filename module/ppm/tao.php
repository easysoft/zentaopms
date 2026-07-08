<?php
declare(strict_types=1);
/**
 * The tao file of ppm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
class ppmTao extends ppmModel
{
    /**
     * 根据合并请求获取关联对象信息。
     * Get story,task,bug pairs which linked MR.
     *
     * @param  int    $ppmID
     * @param  string $objectType story|task|bug
     * @access public
     * @return array
     */
    protected function getLinkedObjectPairs(int $ppmID, string $objectType = 'story'): array
    {
        return $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('AType')->eq($this->app->rawModule)
            ->andWhere('BType')->eq($objectType)
            ->andWhere('AID')->eq($ppmID)
            ->fetchPairs();
    }
}
