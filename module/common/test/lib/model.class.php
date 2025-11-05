<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class commonModelTest extends baseTest
{
    protected $moduleName = 'common';
    protected $className  = 'model';

    /**
     * Test apiError method.
     *
     * @param  object|null $result
     * @access public
     * @return object
     */
    public function apiErrorTest($result = null)
    {
        $reflection = new ReflectionClass('commonModel');
        $method = $reflection->getMethod('apiError');
        $method->setAccessible(true);

        $result = $method->invoke(null, $result);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formConfig method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function formConfigTest(string $module, string $method, int $objectID = 0)
    {
        $result = commonModel::formConfig($module, $method, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printBack method metadata.
     *
     * @param  string $checkType
     * @access public
     * @return mixed
     */
    public function printBackMetaTest(string $checkType)
    {
        if($checkType == 'exists')
        {
            return method_exists('commonModel', 'printBack') ? '1' : '0';
        }

        $reflection = new ReflectionMethod('commonModel', 'printBack');

        if($checkType == 'static')
        {
            return $reflection->isStatic() ? '1' : '0';
        }

        if($checkType == 'public')
        {
            return $reflection->isPublic() ? '1' : '0';
        }

        if($checkType == 'paramCount')
        {
            return (string)$reflection->getNumberOfParameters();
        }

        return '0';
    }

    /**
     * Test printBack method.
     *
     * @param  string $backLink
     * @param  string $class
     * @param  string $misc
     * @param  bool   $onlyBody
     * @access public
     * @return mixed
     */
    public function printBackTest(string $backLink, string $class = '', string $misc = '', bool $onlyBody = false)
    {
        if($onlyBody)
        {
            $_GET['onlybody'] = 'yes';
        }
        else
        {
            unset($_GET['onlybody']);
        }

        ob_start();
        $result = commonModel::printBack($backLink, $class, $misc);
        $output = ob_get_clean();

        if(dao::isError()) return dao::getError();

        // Return different data based on what we're testing
        if($onlyBody) return $result;
        if(!empty($output)) return $output;
        return $result;
    }
}
