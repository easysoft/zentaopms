<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * GitFox 代码库测试数据类。
 * GitFox repository test data class.
 *
 * 通过真实的 GitFox 接口创建空间和代码库，为代码库相关单测提供真实可用的测试数据，
 * 不再使用凭空造出来的代码库 ID。
 *
 * class gitfoxRepoData
 */
class gitfoxRepoData extends baseTest
{
    protected $moduleName = 'gitfox';
    protected $className  = 'model';

    /**
     * 已创建的空间，测试结束时通过 cleanup 删除。
     *
     * @var array
     * @access protected
     */
    protected $createdSpaces = array();

    /**
     * 已创建的代码库，测试结束时通过 cleanup 删除。
     *
     * @var array
     * @access protected
     */
    protected $createdRepos = array();

    /**
     * 创建一个真实的代码库。
     * Create a real repository through the GitFox API.
     *
     * @param  array $options  name、desc、acl、defaultBranch、readme、suffix 可覆盖
     * @access public
     * @return object|false
     */
    public function createRepo(array $options = array())
    {
        $suffix = zget($options, 'suffix', '');
        if($suffix === '') $suffix = substr(uniqid(), -6);

        $space = $this->createSpace(array('suffix' => $suffix));
        if(empty($space->id)) return false;

        $repo = $this->instance->apiCreateRepo((object)array(
            'name'          => zget($options, 'name', 'unittest-repo-' . $suffix),
            'space'         => (int)$space->id,
            'desc'          => zget($options, 'desc', 'unit test repo'),
            'acl'           => zget($options, 'acl', 'private'),
            'defaultBranch' => zget($options, 'defaultBranch', 'main'),
            'readme'        => zget($options, 'readme', true),
            'product'       => zget($options, 'product', '')
        ));
        if(empty($repo->id)) return false;

        /* 代码库 uid 的长度需要不少于 4 位，GitFox 依赖它拼装代码库的存储路径。 */
        if(empty($repo->gitUID) || strlen((string)$repo->gitUID) < 4) return false;

        $this->createdRepos[(int)$repo->id] = (int)$repo->id;
        return $repo;
    }

    /**
     * 创建一个真实的空间。
     * Create a real space through the GitFox API.
     *
     * @param  array $options  name、code、desc、acl、auth、suffix 可覆盖
     * @access public
     * @return object|false
     */
    public function createSpace(array $options = array())
    {
        $suffix = zget($options, 'suffix', '');
        if($suffix === '') $suffix = substr(uniqid(), -6);

        $space = $this->instance->apiCreateSpace((object)array(
            'name'      => zget($options, 'name', 'unittest-space-' . $suffix),
            'code'      => zget($options, 'code', 'unittest_space_' . $suffix),
            'desc'      => zget($options, 'desc', 'unit test space'),
            'acl'       => zget($options, 'acl', 'private'),
            'auth'      => zget($options, 'auth', 'extend'),
            'createdBy' => $this->instance->app->user->account
        ));
        if(empty($space->id)) return false;

        $this->createdSpaces[(int)$space->id] = (int)$space->id;
        return $space;
    }

    /**
     * 在真实代码库上创建分支。
     * Create a branch on the real repository.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @param  string $source
     * @access public
     * @return bool
     */
    public function createBranch(int $repoID, string $branch, string $source = 'main'): bool
    {
        $result = $this->instance->apiCreateBranch($repoID, (object)array('name' => $branch, 'source' => $source));

        return !empty($result->data->name);
    }

    /**
     * 在真实代码库上创建 webhook。
     * Create a webhook on the real repository.
     *
     * @param  int    $repoID
     * @param  string $url
     * @param  string $displayName
     * @access public
     * @return object|false
     */
    public function createHook(int $repoID, string $url, string $displayName = '')
    {
        if($displayName === '') $displayName = 'unittest-hook-' . substr(uniqid(), -6);

        return $this->instance->apiCreateHook($repoID, (object)array('url' => $url, 'displayName' => $displayName));
    }

    /**
     * 删除真实代码库。
     * Delete the real repository.
     *
     * @param  int  $repoID
     * @access public
     * @return bool
     */
    public function deleteRepo(int $repoID): bool
    {
        $apiRoot = $this->instance->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/");
        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));

        return !empty($result->code) && $result->code == 'success';
    }

    /**
     * 清理本次测试创建的空间（连同其中的代码库）。
     * Delete the spaces created by this test data class.
     *
     * @access public
     * @return void
     */
    public function cleanup(): void
    {
        foreach($this->createdRepos as $repoID) $this->deleteRepo($repoID);
        foreach($this->createdSpaces as $spaceID) $this->instance->apiDeleteSpace($spaceID);
        $this->createdRepos  = array();
        $this->createdSpaces = array();
    }
}
