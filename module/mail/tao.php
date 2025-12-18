<?php
declare(strict_types=1);
/**
 * The tao file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
class mailTao extends mailModel
{
    /**
     * Exclude me from toList and ccList.
     *
     * @param  array     $toList
     * @param  array     $ccList
     * @access protected
     * @return array
     */
    protected function excludeMe(array $toList, array $ccList): array
    {
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        $toList = array_unique(array_filter(array_map(function($to) use($account){$to = trim($to); return $to == $account ? '' : $to;}, $toList)));
        $ccList = array_unique(array_filter(array_map(function($cc) use($account){$cc = trim($cc); return $cc == $account ? '' : $cc;}, $ccList)));

        return array($toList, $ccList);
    }

    /**
     * Process toList and ccList. Exclude me and remove deleted users.
     *
     * @param  string    $toList
     * @param  string    $ccList
     * @param  bool      $includeMe
     * @access protected
     * @return array
     */
    protected function processToAndCC(string $toList, string $ccList, bool $includeMe = false): array
    {
        $toList  = $toList ? explode(',', str_replace(' ', '', $toList)) : array();
        $ccList  = $ccList ? explode(',', str_replace(' ', '', $ccList)) : array();

        /* Process toList and ccList, remove current user from them. If toList is empty, use the first cc as to. */
        if(!$includeMe) list($toList, $ccList) = $this->excludeMe($toList, $ccList);

        /* Remove deleted users. */
        $this->app->loadConfig('message');
        $users      = $this->loadModel('user')->getPairs('nodeleted|all');
        $blockUsers = isset($this->config->message->blockUser) ? explode(',', $this->config->message->blockUser) : array();
        $toList = array_unique(array_filter(array_map(function($to) use($users, $blockUsers) {$to = trim($to); return (isset($users[$to]) && !in_array($to, $blockUsers)) ? $to : '';}, $toList)));
        $ccList = array_unique(array_filter(array_map(function($cc) use($users, $blockUsers) {$cc = trim($cc); return (isset($users[$cc]) && !in_array($cc, $blockUsers)) ? $cc : '';}, $ccList)));

        if(empty($toList) and $ccList) $toList = array(array_shift($ccList));

        $toList = implode(',', $toList);
        $ccList = implode(',', $ccList);

        return array($toList, $ccList);
    }

    /**
     * 获取邮件内容中的图片 url 和物理文件的键值对。
     * Get key-value pairs of image URL and physical file in mail content.
     *
     * @param  string    $body
     * @access protected
     * @return array
     */
    public function getImages(string $body): array
    {
        $images = array();

        /* 匹配形如 src="/file-read-1.jpg" 或 scr="/index.php?m=file&f=read&fileID=1" 的图片。 Match images like src="/file-read-1.jpg" or scr="/index.php?m=file&f=read&fileID=1". */
        $readLinkReg = str_replace(array('%fileID%', '/', '.', '?'), array('[0-9]+', '\/', '\.', '\?'), helper::createLink('file', 'read', 'fileID=(%fileID%)', '\w+'));
        preg_match_all('/ src="(' . $readLinkReg . ')" /', $body, $matches);
        $images += $this->getImagesByFileID($matches);

        /* 匹配形如 src="{1.jpg}" 的图片。 Match images like src="{1.jpg}". */
        preg_match_all('/ src="({([0-9]+)\.\w+?})" /', $body, $matches);
        $images += $this->getImagesByFileID($matches);

        /* 匹配形如 src="/data/upload/1.jpg" 的图片。 Match images like src="/data/upload/1.jpg". */
        preg_match_all('/ src="(\/?data\/upload\/[\/\w+]*)"/', $body, $matches);
        $images += $this->getImagesByPath($matches);

        return $images;
    }

    /**
     * 根据文件 ID 获取图片 url 和物理文件的键值对。
     * Get key-value pairs of image URL and physical file by file ID.
     *
     * @param  array  $matches
     * @access public
     * @return array
     */
    public function getImagesByFileID(array $matches): array
    {
        if(!isset($matches[2])) return array();

        $this->loadModel('file');

        $images = array();
        foreach($matches[2] as $key => $fileID)
        {
            if(!$fileID) continue;

            $file = $this->file->getById((int)$fileID);
            if(!$file) continue;
            if(!in_array($file->extension, $this->config->file->imageExtensions)) continue;

            $images[$matches[1][$key]] = $file->realPath;
        }
        return $images;
    }

    /**
     * 根据路径获取图片 url 和物理文件的键值对。
     * Get key-value pairs of image URL and physical file by path.
     *
     * @param  array  $matches
     * @access public
     * @return array
     */
    public function getImagesByPath(array $matches): array
    {
        if(!isset($matches[1])) return array();

        $images = array();
        foreach($matches[1] as $key => $path)
        {
            if(!$path) continue;

            $images[$path] = $path;
        }
        return $images;
    }

    /**
     * Replace image URL for mail content.
     *
     * @param  string    $body
     * @param  array     $images
     * @access protected
     * @return string
     */
    protected function replaceImageURL(string $body, array $images): string
    {
        foreach($images as $url => $file)
        {
            if(!$file) continue;
            $body = str_replace($url, 'cid:' . basename($file), $body);
        }

        return $body;
    }

    /**
     * Get action for mail.
     *
     * @param  int       $actionID
     * @access protected
     * @return object|false
     */
    protected function getActionForMail(int $actionID): object|false
    {
        $this->loadModel('action');
        $action  = $this->action->getById($actionID);
        $history = $this->action->getHistory($actionID);
        if(!$action) return false;

        $action->history    = zget($history, $actionID, array());
        $action->appendLink = '';
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id)   = explode(':', $action->extra);
            $action->extra      = $extra;
            $action->appendLink = $id;
        }

        return $action;
    }

    /**
     * Get object for mail by objectType.
     *
     * @param  string    $objectType  kanbancard|testtask|doc|bug|story|task|release
     * @param  int       $objectID
     * @access protected
     * @return object
     */
    protected function getObjectForMail(string $objectType, int $objectID): object|false
    {
        if(empty($objectType) || empty($objectID)) return false;

        $objectModel = $objectType == 'kanbancard' ? $this->loadModel('kanban') : $this->loadModel($objectType);
        if(!$objectModel) return false;

        $getObjectMethod = $objectType == 'kanbancard' ? 'getCardByID' : 'getByID';
        if($objectType == 'task') $getObjectMethod = 'fetchByID';
        if(method_exists($objectModel, $getObjectMethod))
        {
            $object = call_user_func(array($objectModel, $getObjectMethod), $objectID);
        }
        else
        {
            $object = $this->fetchByID($objectID, $objectType);
        }

        if(!$object) return false;

        if($objectType == 'doc' && $object->contentType == 'markdown')
        {
            $object->content = commonModel::processMarkdown($object->content);
            $object->content = str_replace("<table>", "<table style='border-collapse: collapse;'>", $object->content);
            $object->content = str_replace("<th>", "<th style='word-break: break-word; border:1px solid #000;'>", $object->content);
            $object->content = str_replace("<td>", "<td style='word-break: break-word; border:1px solid #000;'>", $object->content);
        }

        return $object;
    }

    /**
     * Get addressees by objectType.
     *
     * @param  string    $objectType
     * @param  object    $action
     * @param  object    $object
     * @access protected
     * @return array|bool
     */
    protected function getAddressees(string $objectType, object $object, object $action): array|bool
    {
        if(empty($objectType) || empty($object) || empty($action) || empty($action->action)) return false;

        $objectModel = $this->loadModel($objectType);
        if(!$objectModel) return false;

        if(in_array($objectType, array('epic', 'requirement', 'story', 'task', 'meeting', 'review', 'deploy', 'reviewissue'))) return $objectModel->getToAndCcList($object, $action->action);
        if(in_array($objectType, array('ticket', 'rule'))) return $objectModel->getToAndCcList($object, $action);

        return $objectModel->getToAndCcList($object);
    }

    /**
     * Get mail content by objectType.
     *
     * @param  string    $objectType
     * @param  object    $action
     * @param  object    $object
     * @access protected
     * @return string
     */
    protected function getMailContent(string $objectType, object $object, object $action): string
    {
        if(empty($objectType) || empty($object) || empty($action)) return '';
        if($objectType == 'mr') return '';

        $domain     = zget($this->config->mail, 'domain', common::getSysURL());
        $domain     = rtrim($domain, '/');
        $mailTitle  = strtoupper($objectType) . ' #' . $object->id;
        $modulePath = $this->app->getModulePath('', $objectType);
        if(!file_exists($modulePath)) return '';

        $oldcwd   = getcwd();
        $viewFile = $modulePath . 'view/sendmail.html.php';

        $viewExtPath  = $this->app->getModuleExtPath($objectType, 'view');
        $extHookFiles = array();
        if(!empty($viewExtPath))
        {
            $commonExtViewFile = $viewExtPath['common'] . "sendmail.html.php";
            if(file_exists($commonExtViewFile)) $viewFile = $commonExtViewFile;

            $extHookFiles = glob($viewExtPath['common'] . "sendmail.*.html.hook.php");
        }
        if(!file_exists($viewFile)) return '';

        chdir(dirname($viewFile, 2));
        ob_start();
        include $viewFile;
        foreach($extHookFiles as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        return $mailContent;
    }

    /**
     * Get MR mail content.
     *
     * @param  object     $object
     * @param  string     $action
     * @param  string     $role     to|cc
     * @access protected
     * @return string
     */
    protected function getMRMailContent(object $object, string $action, string $role = 'to'): string
    {
        $this->app->loadLang('mr');
        $title  = $this->getObjectTitle($object, 'mr');
        $domain = zget($this->config->mail, 'domain', common::getSysURL());
        $domain = rtrim($domain, '/');
        $MRLink = $domain . helper::createLink('mr', 'view', "id={$object->id}");
        if($action == 'compilefail') return sprintf($this->lang->mr->failMessage, $MRLink, $title);
        if($action == 'compilepass')
        {
            $message = $this->lang->mr->toCreatedMessage;
            if($role == 'cc') $message = $this->lang->mr->toReviewerMessage;
            return sprintf($message, $MRLink, $title);
        }
        return '';
    }

    /**
     * Get object title for mail.
     *
     * @param  object    $object
     * @param  string    $objectType
     * @access protected
     * @return string
     */
    protected function getObjectTitle(object $object, string $objectType): string
    {
        $this->loadModel('action');
        if($objectType == 'auditplan') return $this->lang->auditplan->common . ' #' . $object->id;

        $nameFields = zget($this->config->action->objectNameFields, $objectType, array());
        return zget($object, $nameFields, '');
    }
}
