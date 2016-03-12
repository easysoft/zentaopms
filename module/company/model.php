<?php
/**
 * The model file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class companyModel extends model
{
    /**
     * Set menu.
     * 
     * @param  int    $dept 
     * @access public
     * @return void
     */
    public function setMenu($dept = 0)
    {
        common::setMenuVars($this->lang->company->menu, 'name', array($this->app->company->name));
        common::setMenuVars($this->lang->company->menu, 'addUser', array($dept));
        common::setMenuVars($this->lang->company->menu, 'batchAddUser', array($dept));
    }

    /**
     * Get company list.
     * 
     * @access public
     * @return void
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
    }

    /**
     * Get the first company.
     * 
     * @access public
     * @return void
     */
    public function getFirst()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->orderBy('id')->limit(1)->fetch();
    }
    
    /**
     * Get company info by id.
     * 
     * @param  int    $companyID 
     * @access public
     * @return object
     */
    public function getByID($companyID = '')
    {
        return $this->dao->findById((int)$companyID)->from(TABLE_COMPANY)->fetch();
    }

    /**
     * Get users.
     *
     * @param  string $type
     * @param  int    $queryID
     * @param  int    $deptID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUsers($type, $queryID, $deptID, $sort, $pager)
    {
        /* Get users. */
        if($type == 'bydept')
        {
            $childDeptIds = $this->loadModel('dept')->getAllChildID($deptID);
            return $this->dept->getUsers($childDeptIds, $pager, $sort);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('userQuery', $query->sql);
                    $this->session->set('userForm', $query->form);
                }
                else
                {
                    $this->session->set('userQuery', ' 1 = 1');
                }
            }
            return $this->loadModel('user')->getByQuery($this->session->userQuery, $pager, $sort);
        }
    }

    /**
     * Update a company.
     * 
     * @access public
     * @return void
     */
    public function update()
    {
        $company   = fixer::input('post')->get();        
        if($company->website  == 'http://') $company->website  = '';
        if($company->backyard == 'http://') $company->backyard = '';
        $companyID = $this->app->company->id;
        $this->dao->update(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->edit->requiredFields, 'notempty')
            ->batchCheck('name', 'unique', "id != '$companyID'")
            ->where('id')->eq($companyID)
            ->exec();
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->company->browse->search['actionURL'] = $actionURL;
        $this->config->company->browse->search['queryID']   = $queryID;
        $this->config->company->browse->search['params']['dept']['values'] = array('' => '') + $this->loadModel('dept')->getOptionMenu();

        $this->loadModel('search')->setSearchParams($this->config->company->browse->search);
    }
}
