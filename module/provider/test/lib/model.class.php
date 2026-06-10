<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class providerModelTest extends baseTest
{
    protected $moduleName = 'provider';
    protected $className  = 'model';

    /**
     * Test create method.
     *
     * @param  array $params
     * @access public
     * @return object|array|bool
     */
    public function createTest(array $params = array()): object|array|bool
    {
        $defaults = array(
            'type'        => 'GitLab',
            'name'        => '',
            'url'         => '',
            'token'       => '',
            'createdBy'   => 'admin',
            'createdDate' => helper::now()
        );

        $provider = new stdclass();
        foreach($defaults as $field => $value) $provider->{$field} = $value;
        foreach($params as $field => $value) $provider->{$field} = $value;

        $providerID = $this->invokeArgs('create', array($provider));
        if(dao::isError()) return dao::getError();
        if(!$providerID) return false;

        return $this->instance->dao->select('*')->from(TABLE_PROVIDER)->where('id')->eq($providerID)->fetch();
    }

    /**
     * Test getList method.
     *
     * @param  string  $orderBy
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getListTest(string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $providerList = $this->invokeArgs('getList', array($orderBy, $pager));
        if(dao::isError()) return dao::getError();

        return $providerList;
    }
}
