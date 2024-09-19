<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 我收藏的文档。
     * Check my favorite docs.
     *
     * @param  string $docName
     * @access public
     * @return void
     */
    public function myFavorites($docName)
    {
        /*进入我的空间创建并收藏一个文档*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
    }
}
