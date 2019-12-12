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
        $jenkins = $this->dao->select('*')->from(TABLE_REPO_CI)->where('id')->eq($id)->fetch();
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
        $jenkinsList = $this->dao->select('*')->from(TABLE_REPO_CI)
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

        $this->dao->insert(TABLE_REPO_CI)->data($jenkins)
            ->batchCheck($this->config->jenkins->create->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->batchCheckIF($jenkins->type === 'credential', "credential", 'notempty')
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

        $this->dao->update(TABLE_REPO_CI)->data($jenkins)
            ->batchCheck($this->config->jenkins->edit->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->batchCheckIF($jenkins->type === 'credential', "credential", 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    public function listCredentialForSelection($whr)
    {
        $credentials = $this->dao->select('id, name')->from(TABLE_CREDENTIAL)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere($whr)->fi()
            ->orderBy(id)
            ->fetchPairs();
        $credentials[''] = '';
        return $credentials;
    }

    /**
     * Get a repo by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getRepoByID($id)
    {
        $repo = $this->dao->select('*')->from(TABLE_REPO_CI)->where('id')->eq($id)->fetch();
        return $repo;
    }

    /**
     * Get repo list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listRepo($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $repoList = $this->dao->select('*')->from(TABLE_REPO_CI)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $repoList;
    }

    /**
     * Create a repo.
     *
     * @access public
     * @return bool
     */
    public function createRepo()
    {
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();
        if ($data->SCM === 'Subversion') {
            $credential = $this->getCredentialByID($data->credential);
            if ($credential->type != 'account') {
                dao::$errors['credential'][] = $this->repo->svnCredentialLimt;

                return;
            }
        }

        $this->checkRepoConnection();
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();

        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);
        if(empty($data->client)) $data->client = 'svn';

        if($data->SCM == 'Subversion')
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info = $scm->info('');
            $data->prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }

        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->insert(TABLE_REPO_CI)->data($data)
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Update a repo.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function updateRepo($id)
    {
        $this->checkRepoConnection();
        $data = fixer::input('post')->skipSpecial('path,client,account,password')->get();
        $data->acl = empty($data->acl) ? '' : json_encode($data->acl);

        if(empty($data->client)) $data->client = 'svn';
        $repo = $this->getRepoByID($repoID);
        $data->prefix = $repo->prefix;
        if($data->SCM == 'Subversion' and $data->path != $repo->path)
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($data);
            $info = $scm->info('');
            $data->prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $data->path)), '/');
            if($data->prefix) $data->prefix = '/' . $data->prefix;
        }
        elseif($data->SCM != $repo->SCM and $data->SCM == 'Git')
        {
            $data->prefix = '';
        }

        if($data->path != $repo->path) $data->synced = 0;
        if($data->encrypt == 'base64') $data->password = base64_encode($data->password);
        $this->dao->update(TABLE_REPO_CI)->data($data)
            ->batchCheck($this->config->repo->create->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($repoID)->exec();
        if($repo->path != $data->path)
        {
            $this->dao->delete()->from(TABLE_REPO_CIHISTORY)->where('repo')->eq($repoID)->exec();
            $this->dao->delete()->from(TABLE_REPO_CIFILES)->where('repo')->eq($repoID)->exec();
            return false;
        }
        return true;
    }

    /**
     * Check repo connection
     *
     * @access public
     * @return void
     */
    public function checkRepoConnection()
    {
        if(empty($_POST)) return false;
        $scm      = $this->post->SCM;
        $client   = $this->post->client;
        $encoding = strtoupper($this->post->encoding);
        $path     = $this->post->path;
        if($encoding != 'UTF8' and $encoding != 'UTF-8') $path = helper::convertEncoding($path, 'utf-8', $encoding);

        $account  = "";
        $password = "";
        $privateKey = "";
        $passphrase = "";

        $credential = $this->getCredentialByID($this->post->credential);
        if ($credential->type === 'account') {
            $account = $credential->username;
            $password = $credential->password;

            $_POST['account'] = $account;
            $_POST['password'] = $password;
        } else {
            $privateKey = $credential->privateKey;
            $passphrase = $credential->passphrase;

            $_POST['privateKey'] = $privateKey;
            $_POST['passphrase'] = $passphrase;
        }

        if($scm == 'Subversion')
        {
            $path = '"' . $path . '"';
            if(stripos($path, 'https://') === 1 or stripos($path, 'svn://') === 1)
            {
                $ssh     = true;
                $remote  = true;
                $command = "$client info --username $account --password $password --non-interactive --trust-server-cert-failures=cn-mismatch --trust-server-cert --no-auth-cache $path 2>&1";
            }
            else if(stripos($path, 'file://') === 1)
            {
                $ssh     = false;
                $remote  = false;
                $command = "$client info --non-interactive --no-auth-cache $path 2>&1";
            }
            else
            {
                $ssh     = false;
                $remote  = true;
                $command = "$client info --username $account --password $password --non-interactive --no-auth-cache $path 2>&1";
            }
            exec($command, $output, $result);
            if($result)
            {
                $versionCommand = "$client --version --quiet 2>&1";
                exec($versionCommand, $versionOutput, $versionResult);
                if($versionResult)
                {
                    $message = sprintf($this->lang->repo->error->output, $versionCommand, $versionResult, join("\n", $versionOutput));
                    echo $message;
                    die(js::alert($this->lang->repo->error->cmd . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
                }
                if($ssh and version_compare(end($versionOutput), '1.6', '<')) die(js::alert($this->lang->repo->error->version));
                $message = sprintf($this->lang->repo->error->output, $command, $result, join("\n", $output));
                echo $message;
                if(stripos($message, 'Expected FS format between') !== false and strpos($message, 'found format') !== false) die(js::alert($this->lang->repo->error->clientVersion));
                if(preg_match('/[^\:\/\\A-Za-z0-9_\-\'\"]/', $path)) die(js::alert($this->lang->repo->error->encoding . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
                die(js::alert($this->lang->repo->error->connect . '\n' . str_replace(array("\n", "'"), array('\n', '"'), $message)));
            }
        }
        elseif($scm == 'Git')
        {
            if(!chdir($path))
            {
                if(!is_dir($path)) die(js::alert(sprintf($this->lang->repo->error->noFile, $path)));
                if(!is_executable($path)) die(js::alert(sprintf($this->lang->repo->error->noPriv, $path)));
                die(js::alert($this->lang->repo->error->path));
            }

            $command = "$client tag 2>&1";
            exec($command, $output, $result);
            if($result)
            {
                echo sprintf($this->lang->repo->error->output, $command, $result, join("\n", $output));
                die(js::alert($this->lang->repo->error->connect));
            }
        }
        return true;
    }

    /**
     * Create link for repo
     *
     * @param  string $method
     * @param  string $params
     * @param  string $pathParams
     * @param  string $viewType
     * @param  bool   $onlybody
     * @access public
     * @return string
     */
    public function createLink($method, $params = '', $pathParams = '', $viewType = '', $onlybody = false)
    {
        $link  = helper::createLink('repo', $method, $params, $viewType, $onlybody);
        if(empty($pathParams)) return $link;

        $link .= strpos($link, '?') === false ? '?' : '&';
        $link .= $pathParams;
        return $link;
    }
}
