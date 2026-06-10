<?php
declare(strict_types=1);
/**
 * The model file of provider module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
class providerModel extends model
{
    /**
     * 创建一个服务。
     * Create a provider.
     *
     * @param  object $formData
     * @access public
     * @return int
     */
    public function create(object $formData): int|false
    {
        $this->dao->insert(TABLE_PROVIDER)->data($formData)
            ->batchCheck($this->config->provider->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 获取服务列表。
     * Get provider list.
     *
     * @param  string $orderBy
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_PROVIDER)
            ->where('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }
}
