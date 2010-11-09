<?php
/**
 * The model file of convert module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class convertModel extends model
{
    /* 连接到数据库。*/
    public function connectDB()
    {
        $dsn = "mysql:host={$this->post->dbHost}; port={$this->post->dbPort};dbname={$this->post->dbName}";
        try 
        {
            $dbh = new PDO($dsn, $this->post->dbUser, $this->post->dbPassword);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET NAMES {$this->post->dbCharset}");
            $this->sourceDBH = $dbh;
            return $dbh;
        }
        catch (PDOException $exception)
        {
            return $exception->getMessage();
        }
    }
 
    /* 判断数据库是否存在。*/
    public function dbExists()
    {
        $sql = "SHOW DATABASES like '{$this->post->db->name}'";
        return $this->dbh->query($sql)->fetch();
    }

    /* 记录当前数据库中的每个表最大id。*/
    public function saveState()
    {
        /* 获得用户级别的常量定义列表。*/
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* 去掉不需要保存状态的表。*/
        unset($userConstants['TABLE_BURN']);
        unset($userConstants['TABLE_GROUPPRIV']);
        unset($userConstants['TABLE_PROJECTPRODUCT']);
        unset($userConstants['TABLE_PROJECTSTORY']);
        unset($userConstants['TABLE_STORYSPEC']);
        unset($userConstants['TABLE_TEAM']);
        unset($userConstants['TABLE_USERGROUP']);

        /* 查找每个表的id字段的最大值。*/
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;
            $state[$value] = (int)$this->dao->select('MAX(id) AS id')->from($value)->fetch('id');
        }
        $this->session->set('state', $state);
    }
}
