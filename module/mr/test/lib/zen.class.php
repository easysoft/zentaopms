<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class mrZenTest extends baseTest
{
    protected $moduleName = 'mr';
    protected $className  = 'zen';

    public function buildLinkTaskSearchFormTest(int $MRID, int $repoID, string $orderBy, int $queryID, array $productExecutions)
    {
        $this->invokeArgs('buildLinkTaskSearchForm', [$MRID, $repoID, $orderBy, $queryID, $productExecutions]);
        return empty($_SESSION['mrTasksearchParams']) ? [] : $_SESSION['mrTasksearchParams'];
    }
}
