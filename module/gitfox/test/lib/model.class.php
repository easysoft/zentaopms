<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gitfoxModelTest extends baseTest
{
    protected $moduleName = 'gitfox';
    protected $className  = 'model';

    /**
     * 通过对象属性灌入仓库缓存。
     * Inject repo cache into the model instance.
     *
     * @param  int|string $repoID
     * @param  object     $repo
     * @access public
     * @return void
     */
    public function setRepoCache($repoID, object $repo): void
    {
        $reflection = new ReflectionObject($this->instance);
        $property   = $reflection->getProperty('repos');
        $property->setAccessible(true);
        $cache              = $property->getValue($this->instance);
        $cache[$repoID]     = $repo;
        $property->setValue($this->instance, $cache);
    }
}
