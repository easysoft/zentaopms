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
     * 更新一个服务。
     * Update a provider.
     *
     * @param  int $id
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function update(int $id, object $formData): bool
    {
        $this->dao->update(TABLE_PROVIDER)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->provider->edit->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        return !dao::isError();
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

    /**
     * 根据ID获取服务。
     * Get provider by id.
     *
     * @param  int    $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        $provider = $this->fetchByID($id);
        if(empty($provider)) return false;

        if($provider->type == 'Jenkins')
        {
            list($account, $token) = explode(':', base64_decode($provider->token));
            $provider->account = $account;
            $provider->token   = $token;
        }
        else
        {
            $provider->account = '';
        }

        return $provider;
    }

    /**
     * 获取服务键值对。
     * Get provider pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairs(string $type = ''): array
    {
        return $this->dao->select('*')->from(TABLE_PROVIDER)
            ->where('deleted')->eq(0)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->orderBy('id_desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据ID获取服务接口地址。
     * Get api root by id.
     *
     * @param  object $provider
     * @access public
     * @return string|object
     */
    public function getApiRoot(object $provider): string|object
    {
        if(empty($provider->type)) return '';

        if($provider->type == 'GitLab')
        {
            return rtrim($provider->url, '/') . '/api/v4%s' . "?private_token={$provider->token}";
        }
        elseIf($provider->type == 'Gitea')
        {
            return rtrim($provider->url, '/') . '/api/v1%s' . "?token={$provider->token}";
        }
        elseIf($provider->type == 'Gogs')
        {
            return rtrim($provider->url, '/') . '/api/v1%s' . "?token={$provider->token}";
        }
        elseIf($provider->type == 'GitHub')
        {
            $apiRoot = new stdClass();
            $apiRoot->url    = rtrim($provider->url, '/') . '%s';
            $apiRoot->header = array('Authorization: Bearer ' . $provider->token);
            return $apiRoot;
        }
        else
        {
            return '';
        }
    }

    /**
     * 根据ID获取代码库。
     * Get repos by id.
     *
     * @param  int  $providerID
     * @param  bool $showMirrors
     * @access public
     * @return array
     */
    public function getReposByProviderID(int $providerID, bool $showMirrors = false): array
    {
        return $this->dao->select('*')->from(TABLE_REPO)
            ->where('providerID')->eq($providerID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($showMirrors)->andWhere('mirror')->eq(1)->fi()
            ->fetchAll('id');
    }
}
