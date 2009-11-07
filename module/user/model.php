<?php
/**
 * The model file of user module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class userModel extends model
{
    /* 获得某一个公司的用户列表。*/
    public function getList($companyID)
    {
        $sql = "SELECT * FROM " . TABLE_USER . " WHERE company = '$companyID' ORDER BY id";
        return $this->dbh->query($sql)->fetchAll();
    }

    /* 获得account=>realname的列表。*/
    function getPairs($companyID = 0)
    {
        if($companyID == 0) $companyID = $this->app->company->id;
        return $this->dao->select('account, realname')->from(TABLE_USER)->where('company')->eq((int)$companyID)->fetchPairs();
    }

    /* 通过id获取某一个用户的信息。*/
    public function getById($userID)
    {
        $where = $userID > 0 ? " WHERE id = '$userID'" : " WHERE account = '$userID'";
        $sql = "SELECT * FROM " . TABLE_USER .  $where;
        $user = $this->dbh->query($sql)->fetch();
        if($user) $user->last = date('Y-m-d H:i:s', $user->last);
        return $user;
    }

    /* 新增一个用户。*/
    function create($companyID)
    {
        $user = fixer::input('post')
            ->add('company', (int)$companyID)
            ->setDefault('join', '0000-00-00')
            ->setForce('password', md5($this->post->password))
            ->get();
        $this->dao->insert(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck('account, realname, password', 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->exec();
    }

    /* 更新一个用户。*/
    function update($userID)
    {
        $userID = (int)$userID;
        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setIF($this->post->password != '', 'password', md5($this->post->password))
            ->removeIF($this->post->password == '', 'password')
            ->get();
        $this->dao->update(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck('account, realname, password', 'notempty')
            ->check('account', 'unique', "id != '$userID'")
            ->check('account', 'account')
            ->where('id')->eq((int)$userID)
            ->exec();
    }
    
    /* 删除一个用户。*/
    function delete($userID)
    {
        $sql = "DELETE FROM " . TABLE_USER . " WHERE id = '$userID' LIMIT 1";
        return $this->dbh->exec($sql);
    }

    /**
     * 验证用户的身份。
     * 
     * @param   string $account     用户账号
     * @param   string $password    用户密码
     * @access  public
     * @return  object
     */
    public function identify($account, $password, $companyID)
    {
        $account  = filter_var($account,  FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        if(!$account or !$password) return false;

        $sql  = "SELECT * FROM " . TABLE_USER . " WHERE account  = '$account' AND password = md5('$password') AND company  = '$companyID' LIMIT 1";
        $user = $this->dbh->query($sql)->fetch();
        if($user)
        {
            $ip   = $_SERVER['REMOTE_ADDR'];
            $last = time();
            $sql  = "UPDATE " . TABLE_USER . " SET visits = visits + 1, ip = '$ip', last = '$last' WHERE account = '$account'";
            $this->dbh->exec($sql);
            $user->last = date('Y-m-d H:i:s', $user->last);
        }
        return $user;
    }

    /**
     * 取得对用户的授权。
     * 
     * @param   string $account   用户账号
     * @access  public
     * @return  array             包含用户权限的数组。
     */
    public function authorize($account)
    {
        $account = filter_var($account, FILTER_SANITIZE_STRING);
        if(!$account) return false;

        $rights = array();
        if($account == 'guest')
        {
            $sql = "SELECT module, method FROM " . TABLE_GROUP . " AS t1 LEFT JOIN " . TABLE_GROUPPRIV . " AS t2
                    ON t1.id = t2.group
                 WHERE t1.name = 'guest'";
        }
        else
        {
            $sql = "SELECT module, method FROM " . TABLE_USERGROUP . " AS t1 LEFT JOIN " . TABLE_GROUPPRIV . " AS t2
                ON t1.group = t2.group
                WHERE t1.account = '$account'";
        }
        $stmt = $this->dbh->query($sql);
        if(!$stmt) return $rights;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }
        return $rights;
    }

    /* 
    /**
     * 判断用户是否在线。
     * 
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        return (isset($_SESSION['user']) and !empty($_SESSION['user']) and $_SESSION['user']->account != 'guest');
    }

    /* 获得用户参与的项目列表。*/
    public function getProjects($account)
    {
        $sql = "SELECT T1.*, T2.* FROM " . TABLE_TEAM . " AS T1 LEFT JOIN " .TABLE_PROJECT . " AS T2 ON T1.project = T2.id WHERE T1.account = '$account'";
        return $this->dbh->query($sql)->fetchAll();
    }

    /* 获得用户的Bug列表。*/
    public function getBugs($account)
    {
        $sql = "SELECT * FROM " . TABLE_BUG . " WHERE assignedTO = '$account'";
        return $this->dbh->query($sql)->fetchAll();
    }

    /* 获得账户所对应的真实姓名。*/
    public function getRealNames($accounts)
    {
        $sql = "SELECT account, realname FROM " . TABLE_USER . " WHERE account " . helper::dbIN($accounts);
        return $this->fetchPairs($sql);
    }
}
