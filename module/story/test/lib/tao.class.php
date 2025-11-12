<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class storyTaoTest extends baseTest
{
    protected $moduleName = 'story';
    protected $className  = 'tao';

    /**
     * Test doSaveUploadImage method.
     *
     * @param  int    $storyID
     * @param  string $fileName
     * @param  string $testType
     * @access public
     * @return object
     */
    public function doSaveUploadImageTest(int $storyID, string $fileName, string $testType): object
    {
        global $app;

        // 创建spec对象
        $spec = new stdClass();
        $spec->spec = '原始内容';
        $spec->files = '';

        // 确保file save路径存在
        $storyTao = $this->getInstance($this->moduleName, $this->className);
        $storyTao->loadModel('file');
        if(!is_dir($storyTao->file->savePath)) mkdir($storyTao->file->savePath, 0777, true);

        // 根据测试类型设置不同的session数据
        switch($testType) {
            case 'image':
                // 模拟图片文件上传
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'jpg',
                        'size' => 1024,
                        'realpath' => '/tmp/zentao_test/test_image.jpg'
                    )
                );
                break;
            case 'file':
                // 模拟文档文件上传
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'pdf',
                        'size' => 2048,
                        'realpath' => '/tmp/zentao_test/test_doc.pdf'
                    )
                );
                break;
            case 'empty_session':
                // 清空session
                $app->session->storyImagesFile = array();
                break;
            case 'missing_file':
                // 文件不存在的情况
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'jpg',
                        'size' => 1024,
                        'realpath' => '/tmp/zentao_test/nonexistent.jpg'
                    )
                );
                break;
            case 'empty_name':
                // 空文件名情况
                $app->session->storyImagesFile = array();
                break;
        }

        $result = $this->invokeArgs('doSaveUploadImage', [$storyID, $fileName, $spec], $this->moduleName, $this->className, $storyTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductReviewers method.
     *
     * @param  int   $productID
     * @param  array $storyReviewers
     * @access public
     * @return array|bool
     */
    public function getProductReviewersTest(int $productID, array $storyReviewers = array()): array|bool
    {
        $result = $this->invokeArgs('getProductReviewers', [$productID, $storyReviewers]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
