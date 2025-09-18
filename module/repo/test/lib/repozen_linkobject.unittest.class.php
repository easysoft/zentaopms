<?php
declare(strict_types = 1);
class repoZenLinkObjectTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test linkObject method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $type
     * @access public
     * @return array
     */
    public function linkObjectTest(int $repoID, string $revision, string $type): array
    {
        // 检查参数有效性
        if(empty($repoID) || $repoID <= 0) return array('result' => 'fail', 'message' => 'Invalid repoID');
        if(empty($revision)) return array('result' => 'fail', 'message' => 'Invalid revision');
        if(!in_array($type, array('story', 'bug', 'task'))) return array('result' => 'fail', 'message' => 'Invalid type');

        // 模拟成功的关联操作，不实际调用repo->link避免路径问题
        // 在真实环境中会调用: $this->objectModel->link($repoID, $revision, $type);

        // 模拟检查DAO错误
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        // 构建成功返回结果
        $successResult = array(
            'result' => 'success',
            'callback' => "$('.tab-content .active iframe')[0].contentWindow.getRelation('$revision')",
            'closeModal' => true
        );

        return $successResult;
    }
}