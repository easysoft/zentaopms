<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class instanceZenTest extends baseTest
{
    protected $moduleName = 'instance';
    protected $className  = 'zen';

    /**
     * Test checkForInstall method.
     *
     * @param  object $customData
     * @access public
     * @return mixed
     */
    public function checkForInstallTest(object $customData)
    {
        /* Set viewType to json to get json response. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = 'json';

        try
        {
            $result = $this->invokeArgs('checkForInstall', [$customData]);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content, true);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        if(is_array($result))
        {
            /* Convert message array to object for easier access. */
            if(isset($result['message']) && is_array($result['message']))
            {
                $result['message'] = (object)$result['message'];
            }
            return $result;
        }
        return 0;
    }
}
