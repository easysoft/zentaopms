<?php
declare(strict_types = 1);
require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockZenTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'zen';

    public function processBlockForRenderTest(array $blocks, int $projectID): array
    {
        $result = $this->invokeArgs('processBlockForRender', [$blocks, $projectID]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}