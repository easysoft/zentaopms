<?php
declare(strict_types = 1);
require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';
class repobranchruleZenTest extends baseTest
{
    protected $moduleName = 'repobranchrule';
    protected $className  = 'zen';

    public function checkRulesTest(object $formData)
    {
        $result = $this->invokeArgs('checkRules', [$formData]);
        if(dao::isError()) return false;
        return $result;
    }
}
