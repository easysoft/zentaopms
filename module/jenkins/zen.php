<?php
declare(strict_types=1);
/**
 * The zen file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     jenkins
 * @link        https://www.zentao.net
 */
class jenkinsZen extends jenkins
{
    /**
     * 构建流水线下拉菜单树。
     * Build pipeline dropmenu tree.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    protected function buildTree(array $tasks): array
    {
        $result = array();
        foreach($tasks as $groupName => $task)
        {
            if(empty($task)) continue;

            $itemArray = array
            (
                'id'    => is_array($task) ? '' : $groupName,
                'text'  => is_array($task) ? urldecode($groupName) : urldecode($task),
                'keys'  => urldecode(zget(common::convert2Pinyin(array($groupName)), $groupName, '')),
            );
            if(is_array($task))
            {
                $itemArray['items'] = $this->buildTree($task);
                $itemArray['type']  = 'folder';
            }

            $result[] = $itemArray;
        }
        return $result;
    }

    /**
     * 检查Jenkins账号信息是否正确。
     * Check jenkins account and password.
     *
     * @param  string $url
     * @param  string $account
     * @param  string $password
     * @param  string $token
     * @access protected
     * @return bool
     */
    protected function checkTokenAccess(string $url, string $account, string $password, string $token): bool
    {
        $password = $token ? $token : $password;
        $response = json_decode(common::http("{$url}/api/json", '', array(CURLOPT_USERPWD => "{$account}:{$password}")));
        if(empty($response) || empty($response->_class)) dao::$errors['account'] = $this->lang->jenkins->error->unauthorized;
        return dao::isError();
    }
}
