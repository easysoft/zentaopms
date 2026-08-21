<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class aiZenTest extends baseTest
{
    protected $moduleName = 'ai';
    protected $className  = 'zen';

    /**
     * Test getPostData method.
     *
     * @param  array  $post
     * @access public
     * @return array
     */
    public function getPostDataTest(array $post = array())
    {
        $oldPost = $_POST;
        $_POST   = $post;

        $error  = '';
        $result = $this->invokeArgs('getPostData', array(&$error));

        $_POST = $oldPost;

        if(dao::isError()) return dao::getError();

        $fieldCount = 0;
        if(is_object($result) && isset($result->fields) && is_array($result->fields)) $fieldCount = count($result->fields);
        if(is_array($result) && isset($result['fields']) && is_array($result['fields'])) $fieldCount = count($result['fields']);

        return array(
            'data'       => $result,
            'error'      => $error,
            'fieldCount' => $fieldCount,
            'hasError'   => empty($error) ? 0 : 1,
            'isNull'     => is_null($result) ? 1 : 0
        );
    }
}
