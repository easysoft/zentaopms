<?php
declare(strict_types=1);
class upgradeTest
{
    private $objectModel;

    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('upgrade');
    }

    /**
     * 测试获取升级版本。
     * Test get update version.
     *
     * @param  string $openVersion
     * @param  string $fromEdition
     * @access public
     * @return string
     */
    public function getVersionsToUpdateTest(string $openVersion, string $fromEdition): string
    {
        $versions = $this->objectModel->getVersionsToUpdate($openVersion, $fromEdition);
        $return   = '';
        foreach($versions[$openVersion] as $edition => $version)
        {
            if(!isset($version[0])) $version[0] = '0';
            $return .="{$edition}:{$version[0]};";
        }
        return trim($return, ';');
    }

    /**
     * __call魔术方法，如果比较简单的方法可以直接调用，不需要单独写方法。
     * __call magic method, if the method is simple, you can call it directly, no need to write a method.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        return $this->objectModel->$method($args);
    }

    /**
     * 测试通过版本号获取产品版本类型。
     * Test get edition by version.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getEditionByVersionTest(string $version): string
    {
        return $this->objectModel->getEditionByVersion($version);
    }

    /**
     * 测试获取开源版版本。
     * Test get open version.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getOpenVersionTest(string $version): string
    {
        return $this->objectModel->getOpenVersion($version);
    }
}
