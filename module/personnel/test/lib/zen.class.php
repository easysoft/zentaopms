<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class personnelZenTest extends baseTest
{
    protected $moduleName = 'personnel';
    protected $className  = 'zen';

    /**
     * Test setSelectObjectTips method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $module
     * @access public
     * @return array
     */
    public function setSelectObjectTipsTest(int $objectID, string $objectType, string $module): array
    {
        global $tester;
        $tester->app->loadLang('personnel');
        $tester->lang->personnel->selectObjectTips = '请选择一个%s白名单';

        $this->invokeArgs('setSelectObjectTips', [$objectID, $objectType, $module]);

        $view = $this->getProperty('view');
        $lang = $this->getProperty('lang');

        if(dao::isError()) return dao::getError();

        return array(
            'tips' => $lang->personnel->selectObjectTips,
            'objectName' => isset($view->objectName) ? $view->objectName : ''
        );
    }
}
