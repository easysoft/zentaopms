<?php
/**
 * The model file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     mail
 * @version     $Id: model.php 4750 2013-05-05 00:22:53Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class mailModel extends model
{
    public static $instance;
    public $mta;
    public $mtaType;
    public $errors = array();

    /**
     * 初始化
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $mta = $this->config->mail->mta;
        $this->app->loadClass('phpmailer', $static = true);
        $this->setMTA();
    }

    /**
     * Auto detect email config.
     *
     * @param  string $email
     * @access public
     * @return object
     */
    public function autoDetect(string $email): object
    {
        $username = $domain = '';

        /* Split the email to username and domain. */
        if(str_contains($email, '@')) list($username, $domain) = explode('@', $email);
        $domain = strtolower($domain);

        /*
         * 1. try to find config from the providers.
         * 2. try to find the mx record to get the domain and then search it in providers.
         * 3. try smtp.$domain's 25 and 465 port, if can connect, use smtp.$domain.
         */
        $config = $this->getConfigFromProvider($domain, $username);
        if(!$config) $config = $this->getConfigByMXRR($domain, $username);
        if(!$config) $config = $this->getConfigByDetectingSMTP($domain, $username, 25);
        if(!$config) $config = $this->getConfigByDetectingSMTP($domain, $username, 465);
        if(!$config) $config = new stdclass();

        /* Set default values. */
        $config->mta      = 'smtp';
        $config->fromName = '';
        $config->password = '';
        $config->debug    = 1;
        $config->charset  = 'utf-8';
        if(!isset($config->secure))   $config->secure   = '';
        if(!isset($config->username)) $config->username = $username;
        if(!isset($config->host))     $config->host = '';
        if(!isset($config->auth))     $config->auth = 1;
        if(!isset($config->port))     $config->port = '25';

        return $config;
   }

    /**
     * Try get config from providers.
     *
     * @param  string $domain
     * @param  string $username
     * @access public
     * @return object|false
     */
    public function getConfigFromProvider(string $domain, string $username): object|false
    {
        if(!isset($this->config->mail->provider[$domain])) return false;

        $config = (object)$this->config->mail->provider[$domain];
        $config->mta      = 'smtp';
        $config->username = $username;
        $config->auth     = 1;
        if(!isset($config->port))   $config->port   = 25;
        if(!isset($config->secure)) $config->secure = '';
        return $config;
    }

    /**
     * Get config by MXRR.
     *
     * @param  string $domain
     * @param  string $username
     * @access public
     * @return object|false
     */
    public function getConfigByMXRR(string $domain, string $username): object|false
    {
        /* Try to get mx record, under linux, use getmxrr() directly, windows use nslookup. */
        $smtpHosts = array();
        if(function_exists('getmxrr'))
        {
            getmxrr($domain, $smtpHosts);
        }
        elseif(strpos(PHP_OS, 'WIN') !== false)
        {
            $result = `nslookup -q=mx {$domain} 2>nul`;
            $lines  = explode("\n", $result);
            foreach($lines as $line)
            {
                if(stripos($line, 'exchanger')) $smtpHosts[] = trim(substr($line, strrpos($line, '=') + 1));
            }
        }

        /* Cycle the smtpHosts and try to find it's config from the provider config. */
        foreach($smtpHosts as $smtpHost)
        {
            /* Get the domain name from the hosts, for example: imxbiz1.qq.com get qq.com. */
            $smtpDomain = explode('.', $smtpHost);
            array_shift($smtpDomain);
            $smtpDomain = strtolower(implode('.', $smtpDomain));

            $config = $this->getConfigFromProvider($smtpDomain, $username);
            if($config) $config->username = "$username@$domain";
            return $config;
        }

        return false;
    }

    /**
     * Try connect to smtp.$domain's 25 or 465 port and compute the config according to the connection result.
     *
     * @param  string $domain
     * @param  string $username
     * @param  int    $port
     * @access public
     * @return object|false
     */
    public function getConfigByDetectingSMTP(string $domain, string $username, int $port): object|false
    {
        ini_set('default_socket_timeout', 3);

        $host = 'smtp.' . $domain;
        if(gethostbynamel($host) == false) return false;

        $connection = @fsockopen($host, $port);
        if(!$connection) return false;
        fclose($connection);

        $config = new stdclass();
        $config->username = $username;
        $config->host     = $host;
        $config->auth     = 1;
        $config->port     = $port;
        $config->secure   = $port == 465 ? 'ssl' : '';

        return $config;
     }

    /**
     * Set MTA.
     *
     * @access public
     * @return void
     */
    public function setMTA(): object
    {
        if(static::$instance == null) static::$instance = new phpmailer(true);
        $this->mta = static::$instance;
        $this->mta->CharSet = $this->config->charset;

        $funcName = "set{$this->config->mail->mta}";
        if(!method_exists($this, $funcName)) $this->app->triggerError("The MTA {$this->config->mail->mta} not supported now.", __FILE__, __LINE__, $exit = true);
        $this->$funcName();

        return $this->mta;
    }

    /**
     * Set smtp.
     *
     * @access public
     * @return void
     */
    public function setSMTP(): void
    {
        $this->mta->isSMTP();
        $this->mta->SMTPDebug = $this->config->mail->smtp->debug;
        $this->mta->Host      = $this->config->mail->smtp->host;
        $this->mta->SMTPAuth  = $this->config->mail->smtp->auth;
        $this->mta->Username  = $this->config->mail->smtp->username;
        $this->mta->Password  = $this->config->mail->smtp->password;
        if(isset($this->config->mail->smtp->charset)) $this->mta->CharSet = $this->config->mail->smtp->charset;
        if(isset($this->config->mail->smtp->port)) $this->mta->Port = $this->config->mail->smtp->port;
        if(isset($this->config->mail->smtp->secure) and !empty($this->config->mail->smtp->secure))$this->mta->SMTPSecure = strtolower($this->config->mail->smtp->secure);
    }

    /**
     * Gmail.
     *
     * @access public
     * @return void
     */
    public function setGMail(): void
    {
        $this->mta->isSMTP();
        $this->mta->SMTPDebug  = $this->config->mail->gmail->debug;
        $this->mta->Host       = 'smtp.gmail.com';
        $this->mta->Port       = 465;
        $this->mta->SMTPSecure = "ssl";
        $this->mta->SMTPAuth   = true;
        $this->mta->Username   = $this->config->mail->gmail->username;
        $this->mta->Password   = $this->config->mail->gmail->password;
    }

    /**
     * Send email
     *
     * @param  string  $toList
     * @param  string  $subject
     * @param  string  $body
     * @param  array   $ccList
     * @param  bool    $includeMe
     * @param  array   $emails
     * @param  bool    $forceSync
     * @access public
     * @return void
     */
    public function send(string $toList, string $subject, string $body = '', string $ccList = '', bool $includeMe = false, array $emails = array(), bool $forceSync = false): int|bool
    {
        if(!$this->config->mail->turnon) return false;
        if(!empty($this->config->mail->async) and !$forceSync) return $this->addQueue($toList, $subject, $body, $ccList, $includeMe);

        ob_start();

        $body = $this->mailTao->replaceImageURL($body);
        list($toList, $ccList) = $this->mailTao->processToAndCC($toList, $ccList, $includeMe);
        /* Get realname and email of users. */
        if(empty($emails)) $emails = $this->loadModel('user')->getRealNameAndEmails($toList . ',' . $ccList);

        $this->clear();

        try
        {
            /* Add for task #5301. */
            if(function_exists('putenv')) putenv('RES_OPTIONS=retrans:1 retry:1 timeout:1 attempts:1');

            $this->mta->setFrom($this->config->mail->fromAddress, $this->convertCharset($this->config->mail->fromName));
            $this->setSubject($this->convertCharset($subject));
            $this->setTO(explode(',', $toList), $emails);
            $this->setCC(explode(',', $ccList), $emails);
            $this->setBody($this->convertCharset($body));
            $this->setErrorLang();
            $this->mta->send();
        }
        catch (phpmailerException $e)
        {
            $mailError = ob_get_contents();
            $encoding  = mb_detect_encoding($mailError, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
            if($encoding != 'UTF-8') $mailError = mb_convert_encoding($mailError, 'utf8', $encoding);

            $this->errors[] = nl2br(trim(strip_tags($e->errorMessage())));
            $this->errors[] = $mailError;
        }
        catch (Exception $e)
        {
            $this->errors[] = trim(strip_tags($e->getMessage()));
        }
        if($this->config->mail->mta == 'smtp') $this->mta->smtpClose();

        /* save errors. */
        if($this->isError()) $this->app->saveError(2, implode(' ', $this->errors), __FILE__, __LINE__, true);

        $message = ob_get_contents();
        ob_end_clean();

        return $message;
    }

    /**
     * Set to address
     *
     * @param  array  $toList
     * @param  array  $emails
     * @access public
     * @return void
     */
    public function setTO(array $toList, array $emails): void
    {
        if(empty($toList)) return;
        foreach($toList as $account)
        {
            if(!isset($emails[$account]) or isset($emails[$account]->sended) or strpos($emails[$account]->email, '@') == false) continue;

            $this->mta->addAddress($emails[$account]->email, $this->convertCharset($emails[$account]->realname));
            $emails[$account]->sended = true;
        }
    }

    /**
     * Set cc.
     *
     * @param  string   $ccList
     * @param  array    $emails
     * @access public
     * @return void
     */
    public function setCC(array $ccList, array $emails): void
    {
        if(empty($ccList)) return;
        foreach($ccList as $account)
        {
            if(!isset($emails[$account]) or isset($emails[$account]->sended) or strpos($emails[$account]->email, '@') == false) continue;

            $this->mta->addCC($emails[$account]->email, $this->convertCharset($emails[$account]->realname));
            $emails[$account]->sended = true;
        }
    }

    /**
     * Set subject
     *
     * @param  string    $subject
     * @access public
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->mta->Subject = stripslashes($subject);
    }

    /**
     * Set body.
     *
     * @param  string    $body
     * @access public
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->mta->msgHtml($body);
    }

    /**
     * Convert charset.
     *
     * @param  string    $string
     * @access public
     * @return string
     */
    public function convertCharset(string $string): string
    {
        if(!empty($this->config->mail->smtp->charset) and $this->config->mail->smtp->charset != strtolower($this->config->charset)) return helper::convertEncoding($string, $this->config->charset, $this->config->mail->smtp->charset);
        return $string;
    }

    /**
     * Set error lang.
     *
     * @access public
     * @return void
     */
    public function setErrorLang(): void
    {
        $this->mta->SetLanguage($this->app->getClientLang());
    }

    /**
     * Clear.
     *
     * @access public
     * @return void
     */
    public function clear(): void
    {
        $this->mta->clearAllRecipients();
        $this->mta->clearAttachments();
    }

    /**
     * Check system if there is a mail at least.
     *
     * @access public
     * @return object|false
     */
    public function mailExist(): object|false
    {
        return $this->dao->select('email')->from(TABLE_USER)->where('email')->ne('')->fetch();
    }

    /**
     * Is error?
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty($this->errors);
    }

    /**
     * Get errors.
     *
     * @access public
     * @return array
     */
    public function getError(): array
    {
        $errors = $this->errors;
        $this->errors = array();
        return $errors;
    }

    /**
     * Add queue.
     *
     * @param  string $toList
     * @param  string $subject
     * @param  string $body
     * @param  string $ccList
     * @param  bool   $includeMe
     * @access public
     * @return int|bool
     */
    public function addQueue(string $toList, string $subject, string $body = '', string $ccList = '', bool $includeMe = false): int|bool
    {
        list($toList, $ccList) = $this->mailTao->processToAndCC($toList, $ccList, $includeMe);
        if(empty($toList) and empty($ccList)) return false;
        if(empty($toList) or empty($subject)) return false;

        $data = new stdclass();
        $data->objectType  = 'mail';
        $data->toList      = $toList;
        $data->ccList      = $ccList;
        $data->subject     = $subject;
        $data->data        = $body;
        $data->createdBy   = $this->app->user->account;
        $data->createdDate = helper::now();
        $this->dao->insert(TABLE_NOTIFY)->data($data)->autocheck()->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Get queue.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getQueue(string $status = '', string $orderBy = 'id_desc', object|null $pager = null): array
    {
        $mails = $this->dao->select('*')->from(TABLE_NOTIFY)
            ->where('objectType')->eq('mail')
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Group mails by toList and ccList. */
        $groupMails = array();
        foreach($mails as $mail)
        {
            $users = $mail->toList . ',' . $mail->ccList;
            $groupMails[$users][] = $mail;
        }

        /* Merge the mails if a group has more than one mail. */
        $queue = array();
        foreach($groupMails as $groupMail)
        {
            if(count($groupMail) == 1) $queue[] = reset($groupMail);
            if(count($groupMail) > 1)  $queue[] = $this->mergeMails($groupMail);
        }

        return $queue;
    }

    public function getQueueById($queueID)
    {
        return $this->dao->select('*')->from(TABLE_NOTIFY)->where('id')->eq($queueID)->fetch();
    }

    /**
     * Merge mails.
     *
     * @param  array  $mails
     * @access public
     * @return object
     */
    public function mergeMails(array $mails = array()): object|null
    {
        if(count($mails) <= 1) return array_shift($mails);

        /* Get first and last mail. */
        $firstMail  = array_shift($mails);
        $lastMail   = array_pop($mails);
        $secondMail = empty($mails) ? '' : reset($mails);

        /* Set mail info.*/
        $mail = new stdClass();
        $mail->status  = 'wait';
        $mail->merge   = true;
        $mail->id      = $firstMail->id;
        $mail->toList  = $firstMail->toList;
        $mail->ccList  = $firstMail->ccList;
        $mail->subject = $firstMail->subject;
        $mail->data    = $firstMail->data;

        /* Remove html tail for first mail. */
        if(($endPos = strripos($firstMail->data, '</td>')) !== false) $mail->data = substr($firstMail->data, 0, $endPos);

        if(empty($mails))  $mail->subject .= '|' . $lastMail->subject;
        if(!empty($mails)) $mail->subject .= '|' . $secondMail->subject . '|' . $this->lang->mail->more;

        foreach($mails as $middleMail)
        {
            $mail->id .= ',' . $middleMail->id;

            /* Remove html head and tail for middle mails. */
            $mailBody = $middleMail->data;
            if(($beginPos = strpos($mailBody, '</table>')) !== false) $mailBody = substr($mailBody, $beginPos + 8);
            if(($endPos = strripos($mailBody, '</td>')) !== false)    $mailBody = substr($mailBody, 0, $endPos);
            $mail->data .= $mailBody;
        }

        /* Remove html head for last mail. */
        $mailBody = $lastMail->data;
        if(($beginPos = strpos($mailBody, '</table>')) !== false) $mailBody = substr($mailBody, $beginPos + 8);

        $mail->id   .= ',' . $lastMail->id;
        $mail->data .= $mailBody;
        return $mail;
    }

    /**
     * Sync to sendCloud
     *
     * @param  string $action
     * @param  string $email
     * @param  string $userName
     * @access public
     * @return object
     */
    public function syncSendCloud($action, $email, $userName = '')
    {
        $result = '';
        if($action == 'delete')
        {
            $result = $this->mta->deleteMember($email);
        }
        elseif($action == 'sync')
        {
            $member = new stdclass();
            $member->nickName = $email;
            $member->email    = $email;
            $member->userName = $userName;

            $result = $this->mta->addMember($member);
        }

        return $result;
    }

    /**
     * Send mail.
     *
     * @param  int $objectID
     * @param  int $actionID
     * @access public
     * @return void
     */
    public function sendmail($objectID, $actionID)
    {
        if(empty($objectID) or empty($actionID)) return;

        /* Load module and get vars. */
        $this->loadModel('action');
        $action     = $this->action->getById($actionID);
        $history    = $this->action->getHistory($actionID);
        $objectType = $action->objectType;
        $object     = $objectType == 'kanbancard' ? $this->loadModel('kanban')->getCardByID($objectID) : $this->loadModel($objectType)->getByID($objectID);
        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');
        $subject    = $this->getSubject($objectType, $object, $title, $action->action);
        $domain     = zget($this->config->mail, 'domain', common::getSysURL());

        if($objectType == 'review' and empty($object->auditedBy)) return;

        if($objectType == 'doc' && $object->contentType == 'markdown')
        {
            $object->content = commonModel::processMarkdown($object->content);
            $object->content = str_replace("<table>", "<table style='border-collapse: collapse;'>", $object->content);
            $object->content = str_replace("<th>", "<th style='word-break: break-word; border:1px solid #000;'>", $object->content);
            $object->content = str_replace("<td>", "<td style='word-break: break-word; border:1px solid #000;'>", $object->content);
        }

        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($title)
            {
                $action->appendLink = html::a($domain . helper::createLink($action->objectType, 'view', "id=$id", 'html'), "#$id " . $title);
            }
        }

        if($objectType == 'review') $this->app->loadLang('baseline');

        /* Get mail content. */
        if($objectType == 'kanbancard') $objectType = 'kanban';

        $modulePath = $this->app->getModulePath('', $objectType);
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        if($objectType != 'mr') include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        /* Get the sender. */
        if($objectType == 'story' or $objectType == 'meeting')
        {
            $sendUsers = $this->{$objectType}->getToAndCcList($object, $action->action);
        }
        elseif($objectType == 'review')
        {
            $sendUsers = array($object->auditedBy, '');
        }
        elseif($objectType == 'ticket')
        {
            $sendUsers = $this->{$objectType}->getToAndCcList($object, $action);
        }
        else
        {
            $sendUsers = $this->{$objectType}->getToAndCcList($object);
        }

        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;

        /* Send it. */
        if($objectType == 'mr')
        {
            $MRLink = common::getSysURL() . helper::createLink('mr', 'view', "id={$object->id}");
            if($action->action == 'compilepass')
            {
                $mailContent = sprintf($this->lang->mr->toCreatedMessage, $MRLink, $title);
                $this->send($toList, $subject, $mailContent);

                $mailContent = sprintf($this->lang->mr->toReviewerMessage, $MRLink, $title);
                $this->send($ccList, $subject, $mailContent);

                /* Create a todo item for this MR. */
                $this->loadModel('mr')->apiCreateMRTodo($object->gitlabID, $object->targetProject, $object->mriid);
            }
            elseif($action->action == 'compilefail')
            {
                $mailContent = sprintf($this->lang->mr->failMessage, $MRLink, $title);
                $this->send($toList, $subject, $mailContent, $ccList);
            }
        }
        else
        {
            if($objectType == 'ticket')
            {
                $emails = $this->loadModel('ticket')->getContactEmails($objectID, $toList, $ccList, $action->action == 'closed');
                $this->send($toList, $subject, $mailContent, $ccList, false, $emails);
            }
            else
            {
                $this->send($toList, $subject, $mailContent, $ccList);
            }
        }
        if($this->isError()) error_log(implode("\n", $this->getError()));
    }

    /**
     * Get subject.
     *
     * @param  string $objectType
     * @param  object $object
     * @param  string $title
     * @param  string $actionType
     * @access public
     * @return string
     */
    public function getSubject($objectType, $object, $title, $actionType)
    {
        $suffix    = '';
        $subject   = '';
        $titleType = 'edit';

        if($objectType == 'testtask')
        {
            $this->app->loadLang('testtask');

            if($actionType == 'opened') $titleType = 'create';
            if($actionType == 'closed') $titleType = 'close';

            $subject = sprintf($this->lang->testtask->mail->{$titleType}->title, $this->app->user->realname, $object->id, $object->name);
        }
        elseif($objectType == 'doc')
        {
            $this->app->loadLang('doc');

            if($actionType == 'created') $titleType = 'create';
            $subject = sprintf($this->lang->doc->mail->{$titleType}->title, $this->app->user->realname, $object->id, $object->title);
        }
        else
        {
            if($objectType == 'story' or $objectType == 'bug') $suffix = empty($object->product) ? '' : ' - ' . $this->loadModel('product')->getById($object->product)->name;
            if($objectType == 'task') $suffix = empty($object->execution) ? '' : ' - ' . $this->loadModel('execution')->getById($object->execution)->name;

            $subject = strtoupper($objectType) . ' #' . $object->id . ' ' . $title . $suffix;
        }
        return $subject;
    }

    /**
     * 校验传入项的操作列对应操作按钮是否可点击。
     * Check if the item is clickable.
     *
     * @param  object $queue
     * @param  string $method
     * @access public
     * @return bool
     */
    public function isClickable(object $item, string $method): bool
    {
        $hasPriv = common::hasPriv('mail', $method);

        if($method == 'resend' && $item->status == 'fail') return $hasPriv;
        if($method == 'delete') return $hasPriv;

        return false;
    }
}
