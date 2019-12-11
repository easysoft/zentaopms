<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: control.php 5144 2019-12-11 06:37:03Z chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class ciModel extends model
{
    /**
     * Get a credential by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getCredentialByID($id)
    {
        $credential = $this->dao->select('*')->from(TABLE_CREDENTIAL)->where('id')->eq($id)->fetch();
        return $credential;
    }

    /**
     * Get credential list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listCredential($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $credentials = $this->dao->select('*')->from(TABLE_CREDENTIAL)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $credentials;
    }

    /**
     * Create a credential.
     *
     * @access public
     * @return bool
     */
    public function createCredential()
    {
        $credential = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
//            ->remove('')
            ->get();

        $this->dao->insert(TABLE_CREDENTIAL)->data($credential)
            ->batchCheck($this->config->credential->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a credential.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function updateCredential($id)
    {
        $credential = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_CREDENTIAL)->data($credential)
            ->batchCheck($this->config->credential->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }


    /**
     * Get a jenkins by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getJenkinsByID($id)
    {
        $jenkins = $this->dao->select('*')->from(TABLE_JENKINS)->where('id')->eq($id)->fetch();
        return $jenkins;
    }

    /**
     * Get jenkins list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listJenkins($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $jenkinsList = $this->dao->select('*')->from(TABLE_JENKINS)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $jenkinsList;
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return bool
     */
    public function createJenkins()
    {
        $jenkins = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_JENKINS)->data($jenkins)
            ->batchCheck($this->config->jenkins->create->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function updateJenkins($id)
    {
        $jenkins = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_JENKINS)->data($jenkins)
            ->batchCheck($this->config->jenkins->edit->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }
}
