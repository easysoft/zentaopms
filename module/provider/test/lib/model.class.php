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

    /**
     * Test getByID method.
     *
     * @param  int               $id
     * @access public
     * @return object|array|bool
     */
    public function getByIDTest(int $id): object|array|bool
    {
        $provider = $this->invokeArgs('getByID', array($id));
        if(dao::isError()) return dao::getError();

        return $provider;
    }

    /**
     * Test update method.
     *
     * @param  int               $id
     * @param  array             $params
     * @access public
     * @return object|array|bool
     */
    public function updateTest(int $id, array $params = array()): object|array|bool
    {
        $currentProvider = $this->instance->dao->select('*')->from(TABLE_PROVIDER)->where('id')->eq($id)->fetch();

        $defaults = array(
            'name'       => $currentProvider->name ?? '',
            'url'        => $currentProvider->url ?? '',
            'token'      => $currentProvider->token ?? '',
            'editedBy'   => 'admin',
            'editedDate' => helper::now()
        );

        $provider = new stdclass();
        foreach($defaults as $field => $value) $provider->{$field} = $value;
        foreach($params as $field => $value) $provider->{$field} = $value;

        $updated = $this->invokeArgs('update', array($id, $provider));
        if(dao::isError()) return dao::getError();
        if(!$updated) return false;

        return $this->instance->dao->select('*')->from(TABLE_PROVIDER)->where('id')->eq($id)->fetch();
    }

    /**
     * Test getPairs method.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairsTest(string $type = ''): array
    {
        $providerPairs = $this->invokeArgs('getPairs', array($type));
        if(dao::isError()) return dao::getError();

        return $providerPairs;
    }
}
