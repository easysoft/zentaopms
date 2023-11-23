<?php
/**
 * The model file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * 获取第一家公司。
     * Get the first company.
     *
     * @access public
     * @return object
     */
    public function getFirst(): object
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->orderBy('id')->limit(1)->fetch();
    }

    /**
     * 根据id获取公司信息。
     * Get company info by id.
     *
     * @param  int    $companyID
     * @access public
     * @return object
     */
    public function getByID(int $companyID)
    {
        return $this->dao->findById($companyID)->from(TABLE_COMPANY)->fetch();
    }

    /**
     * 获取用户。
     * Get users.
     *
     * @param  string     $browseType
     * @param  string     $type
     * @param  string|int $queryID
     * @param  int        $deptID
     * @param  string     $sort
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getUsers(string $browseType = 'inside', string $type = '', string|int $queryID = 0, int $deptID = 0, string $sort = '', object $pager = null): array
    {
        if($type == 'bydept')
        {
            $childDeptIds = $this->loadModel('dept')->getAllChildID($deptID);
            return $this->dept->getUsers($browseType, $childDeptIds, $pager, $sort);
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
            return $this->loadModel('user')->getByQuery($browseType, $this->session->userQuery, $pager, $sort);
        }
    }

    /**
     * 获取外部公司。
     * Get outside companies.
     *
     * @access public
     * @return array
     */
    public function getOutsideCompanies(): array
    {
        return $this->dao->select('id, name')->from(TABLE_COMPANY)->where('id')->ne(1)->fetchPairs();
    }

    /**
     * Get company-user pairs.
     *
     * @access public
     * @return array
     */
    public function getCompanyUserPairs()
    {
        $pairs = $this->dao->select("t1.account, CONCAT_WS('/', t2.name, t1.realname)")->from(TABLE_USER)->alias('t1')
            ->leftJoin(TABLE_COMPANY)->alias('t2')
            ->on('t1.company = t2.id')
            ->fetchPairs();

        return $pairs;
    }

    /**
     * 更新公司信息。
     * Update a company.
     *
     * @param  int    $companyID
     * @param  object $compnay
     * @access public
     * @return bool
     */
    public function update(int $companyID, object $company): bool
    {
        $this->dao->update(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->edit->requiredFields, 'notempty')
            ->batchCheck('name', 'unique', "id != '$companyID'")
            ->where('id')->eq($companyID)
            ->exec();

        return !dao::isError();
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
        $this->config->company->browse->search['params']['dept']['values']    = $this->loadModel('dept')->getOptionMenu();
        $this->config->company->browse->search['params']['visions']['values'] = $this->loadModel('user')->getVisionList();

        $this->loadModel('search')->setSearchParams($this->config->company->browse->search);
    }
}
