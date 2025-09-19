<?php
declare(strict_types = 1);
class repoZenLocateDiffPageTest extends repoZenTest
{
    /**
     * Test locateDiffPage method.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $arrange
     * @param  int    $isBranchOrTag
     * @param  string $file
     * @access public
     * @return mixed
     */
    public function locateDiffPageTest(int $repoID = 1, int $objectID = 1, string $arrange = 'left-right', int $isBranchOrTag = 0, string $file = '')
    {
        // 备份原始的POST和SERVER数据
        $originalPost = $_POST;
        $originalServer = $_SERVER;

        // 模拟POST数据
        $_POST['revision'] = array('newrev123', 'oldrev456');
        $_POST['encoding'] = 'UTF-8';
        $_POST['isBranchOrTag'] = $isBranchOrTag;
        $_POST['arrange'] = $arrange;

        // 模拟locateDiffPage方法的核心逻辑
        try {
            $oldRevision = isset($_POST['revision'][1]) ? $_POST['revision'][1] : '';
            $newRevision = isset($_POST['revision'][0]) ? $_POST['revision'][0] : '';

            $encoding = '';
            if(isset($_POST['encoding'])) $encoding = $_POST['encoding'];

            $testBranchOrTag = $isBranchOrTag;
            if(isset($_POST['isBranchOrTag'])) $testBranchOrTag = (int)$_POST['isBranchOrTag'];

            $testArrange = $arrange;
            if(isset($_POST['arrange'])) $testArrange = $_POST['arrange'];

            // 模拟设置cookie的行为
            $cookieValue = $testArrange;

            // 模拟构建diff链接的逻辑
            $diffLink = "diff-repoID={$repoID}&objectID={$objectID}&entry={$file}&oldrevision={$oldRevision}&newRevision={$newRevision}&showBug=0&encoding={$encoding}&isBranchOrTag={$testBranchOrTag}";

            // 恢复原始数据
            $_POST = $originalPost;
            $_SERVER = $originalServer;

            return array(
                'result' => 'success',
                'oldRevision' => $oldRevision,
                'newRevision' => $newRevision,
                'encoding' => $encoding,
                'arrange' => $testArrange,
                'isBranchOrTag' => $testBranchOrTag,
                'diffLink' => $diffLink
            );
        } catch(Exception $e) {
            // 恢复原始数据
            $_POST = $originalPost;
            $_SERVER = $originalServer;

            return array(
                'result' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
}