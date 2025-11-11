<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class taskZenTest extends baseTest
{
    protected $moduleName = 'task';
    protected $className  = 'zen';

    /**
     * Test getAssignedToOptions method.
     *
     * @param  string $manageLink
     * @access public
     * @return array
     */
    public function getAssignedToOptionsTest(string $manageLink): array
    {
        $result = $this->invokeArgs('getAssignedToOptions', [$manageLink]);
        if(dao::isError()) return dao::getError();

        // Convert toolbar arrays to count for easier testing
        if(isset($result['single']['toolbar']) && is_array($result['single']['toolbar']))
        {
            $result['single']['toolbarCount'] = count($result['single']['toolbar']);
        }
        if(isset($result['multiple']['toolbar']) && is_array($result['multiple']['toolbar']))
        {
            $result['multiple']['toolbarCount'] = count($result['multiple']['toolbar']);
            // Extract first toolbar key for testing
            if(isset($result['multiple']['toolbar'][0]['key']))
            {
                $result['multiple']['firstToolbarKey'] = $result['multiple']['toolbar'][0]['key'];
            }
            if(isset($result['multiple']['toolbar'][1]['key']))
            {
                $result['multiple']['secondToolbarKey'] = $result['multiple']['toolbar'][1]['key'];
            }
        }

        return $result;
    }

    /**
     * Test getCustomFields method.
     *
     * @param  object $execution
     * @param  string $action
     * @access public
     * @return array
     */
    public function getCustomFieldsTest(object $execution, string $action): array
    {
        $result = $this->invokeArgs('getCustomFields', [$execution, $action]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
