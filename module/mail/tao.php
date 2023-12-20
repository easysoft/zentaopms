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
     * Replace image URL for mail content.
     *
     * @param  string    $body
     * @access protected
     * @return string
     */
    protected function replaceImageURL(string $body): string
    {
        /* Replace full webPath image for mail. */
        $sysURL      = zget($this->config->mail, 'domain', common::getSysURL());
        $readLinkReg = str_replace(array('%fileID%', '/', '.', '?'), array('[0-9]+', '\/', '\.', '\?'), helper::createLink('file', 'read', 'fileID=(%fileID%)', '\w+'));

        $body = preg_replace('/ src="(' . $readLinkReg . ')" /', ' src="' . $sysURL . '$1" ', $body);
        $body = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . $sysURL . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $body);
        $body = preg_replace('/<img (.*)src="\/?data\/upload/', '<img $1 src="' . $sysURL . $this->config->webRoot . 'data/upload', $body);

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
    protected function getObjectForMail(string$objectType, int $objectID): object|false
    {
        if(empty($objectType) || empty($objectID)) return false;

        $objectModel = $objectType == 'kanbancard' ? $this->loadModel('kanban') : $this->loadModel($objectType);
        if(!$objectModel) return false;

        $getObjectMethod = $objectType == 'kanbancard' ? 'getCardByID' : 'getByID';
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
        if(empty($objectType) || empty($object) || empty($action)) return false;
        if($objectType == 'review') return array($object->auditedBy, '');

        $objectModel = $this->loadModel($objectType);
        if(!$objectModel) return false;

        if($objectType == 'story' or $objectType == 'meeting') return $objectModel->getToAndCcList($object, $action->action);
        if($objectType == 'ticket') return $objectModel->getToAndCcList($object, $action);
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
     * Get object title for mail.
     *
     * @param  object    $object
     * @param  string    $objectType
     * @access protected
     * @return string
     */
    protected function getObjectTitle(object $object, string $objectType): string
    {
        $nameFields = zget($this->config->action->objectNameFields, $objectType, array());
        return zget($object, $nameFields, '');
    }

    /**
     * Send mail based n objectType.
     *
     * @param  string    $objectType
     * @param  object    $object
     * @param  object    $action
     * @access protected
     * @return string|false
     */
    protected function sendBasedOnType(string $objectType, object $object, object $action): string|false
    {
        $domain  = zget($this->config->mail, 'domain', common::getSysURL());
        $title   = $this->getObjectTitle($object, $objectType);
        $subject = $this->getSubject($objectType, $object, $title, $action->action);

        if($objectType == 'kanbancard') $objectType = 'kanban';
        $addressees = $this->getAddressees($objectType, $object, $action);
        if(!$addressees) return false;

        list($toList, $ccList) = $addressees;
        if($objectType == 'mr')
        {
            $this->loadModel('mr');

            $MRLink = $domain . helper::createLink('mr', 'view', "id={$object->id}");
            if($action->action == 'compilepass')
            {
                $mailContent = sprintf($this->lang->mr->toCreatedMessage, $MRLink, $title);
                $this->send($toList, $subject, $mailContent);

                $mailContent = sprintf($this->lang->mr->toReviewerMessage, $MRLink, $title);
                $this->send($ccList, $subject, $mailContent);

                /* Create a todo item for this MR. */
                $this->mr->apiCreateMRTodo($object->hostID, $object->targetProject, $object->mriid);
            }
            elseif($action->action == 'compilefail')
            {
                $mailContent = sprintf($this->lang->mr->failMessage, $MRLink, $title);
                $this->send($toList, $subject, $mailContent, $ccList);
            }
        }
        else
        {
            $mailContent = $this->getMailContent($objectType, $object, $action);
            if($objectType == 'ticket')
            {
                $emails = $this->loadModel('ticket')->getContactEmails($object->id, $toList, $ccList, $action->action == 'closed');
                $this->send($toList, $subject, $mailContent, $ccList, false, $emails);
            }
            else
            {
                if($objectType == 'review') $this->app->loadLang('baseline');
                $this->send($toList, $subject, $mailContent, $ccList);
            }
        }

        if($this->isError()) return implode("\n", $this->getError());
        return '';
    }
}
