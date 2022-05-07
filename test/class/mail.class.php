<?php
class mailTest
{
    /**
     * __construct. 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
         global $tester;
         global $dao;
         $this->dao = $dao;
         $this->tester = $tester;
         $this->objectModel = $tester->loadModel('mail');
    }

    /**
     * AutoDetect. 
     * 
     * @param  int    $email 
     * @access public
     * @return void
     */
    public function autoDetectTest($email)
    {
        $objects = $this->objectModel->autoDetect($email);

        if(dao::isError())  return dao::getError();
        if(!$objects->host) return '没有检测到相关信息';
        
        return $objects;
    }

    /**
     * Get config from provider. 
     * 
     * @param  int    $domain 
     * @param  int    $username 
     * @access public
     * @return void
     */
    public function getConfigFromProviderTest($domain, $username)
    {
        $objects = $this->objectModel->getConfigFromProvider($domain, $username);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get config by MXRR. 
     * 
     * @param  int    $domain 
     * @param  int    $username 
     * @access public
     * @return void
     */
    public function getConfigByMXRRTest($domain, $username)
    {
        $objects = $this->objectModel->getConfigByMXRR($domain, $username);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get config by detecting SMTP. 
     * 
     * @param  int    $domain 
     * @param  int    $username 
     * @param  int    $port 
     * @access public
     * @return void
     */
    public function getConfigByDetectingSMTPTest($domain, $username, $port)
    {
        $objects = $this->objectModel->getConfigByDetectingSMTP($domain, $username, $port);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set MTA. 
     * 
     * @access public
     * @return void
     */
    public function setMTATest()
    {
        $objects = $this->objectModel->setMTA();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set SMTP. 
     * 
     * @access public
     * @return void
     */
    public function setSMTPTest()
    {
        $objects = $this->objectModel->setSMTP();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set PhpMail. 
     * 
     * @access public
     * @return void
     */
    public function setPhpMailTest()
    {
        $objects = $this->objectModel->setPhpMail();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set sendmail. 
     * 
     * @access public
     * @return void
     */
    public function setSendMailTest()
    {
        $objects = $this->objectModel->setSendMail();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set GMail. 
     * 
     * @access public
     * @return void
     */
    public function setGMailTest()
    {
        $this->objectModel->setGMail();

        return $this->setMTATest();
    }

    /**
     * Send. 
     * 
     * @param  int    $toList 
     * @param  int    $subject 
     * @param  string $body 
     * @param  string $ccList 
     * @param  int    $includeMe 
     * @param  array  $emails 
     * @access public
     * @return void
     */
    public function sendTest($toList, $subject, $body = '', $ccList = '', $includeMe = false, $emails = array())
    {
        $objects = $this->objectModel->send($toList, $subject, $body, $ccList, $includeMe, $emails);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set TO. 
     * 
     * @param  int    $toList 
     * @param  int    $emails 
     * @access public
     * @return void
     */
    public function setTOTest($toList, $emails)
    {
        $objects = $this->objectModel->setTO($toList, $emails);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set CC. 
     * 
     * @param  int    $ccList 
     * @param  int    $emails 
     * @access public
     * @return void
     */
    public function setCCTest($ccList, $emails)
    {
        $this->objectModel->setCC($ccList, $emails);

        return $this->setMTATest();
    }

    /**
     * Set subject. 
     * 
     * @param  int    $subject 
     * @access public
     * @return void
     */
    public function setSubjectTest($subject)
    {
        $this->objectModel->setSubject($subject);

        return $this->setMTATest();
    }

    /**
     * Set body. 
     * 
     * @param  int    $body 
     * @access public
     * @return void
     */
    public function setBodyTest($body)
    {
        $this->objectModel->setBody($body);

        return $this->setMTATest();
    }

    /**
     * Set errorlang.
     * 
     * @access public
     * @return void
     */
    public function setErrorLangTest()
    {
        $objects = $this->objectModel->setErrorLang();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * clear. 
     * 
     * @access public
     * @return void
     */
    public function clearTest()
    {
        $objects = $this->objectModel->clear();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Mail exist. 
     * 
     * @access public
     * @return void
     */
    public function mailExistTest()
    {
        $object = $this->objectModel->mailExist();

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Is error. 
     * 
     * @access public
     * @return void
     */
    public function isErrorTest()
    {
        $object = $this->objectModel->isError();

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get error. 
     * 
     * @access public
     * @return void
     */
    public function getErrorTest()
    {
        $objects = $this->objectModel->getError();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Add queue. 
     * 
     * @param  int    $toList 
     * @param  int    $subject 
     * @param  string $body 
     * @param  string $ccList 
     * @param  int    $includeMe 
     * @access public
     * @return void
     */
    public function addQueueTest($toList, $subject, $body = '', $ccList = '', $includeMe = false)
    {
        $this->objectModel->addQueue($toList, $subject, $body, $ccList, $includeMe);

        if($toList and $subject)
        {
            $id      = $this->dao->lastInsertID();
            $object = $this->dao->select('*')->from(TABLE_NOTIFY)->where('id')->eq($id)->fetch(); 
        }

        if(!$object)    return '没有数据提交';
        if(dao::isError()) return dao::getError();
        
        return $object; 
    }

    /**
     * Get queue. 
     * 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getQueueTest($status = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getQueue($status = '', $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get queue by id. 
     * 
     * @param  int    $queueID 
     * @access public
     * @return void
     */
    public function getQueueByIdTest($queueID)
    {
        $object = $this->objectModel->getQueueById($queueID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Merge mails. 
     * 
     * @param  int    $user 
     * @access public
     * @return void
     */
    public function mergeMailsTest($user)
    {
        $mails = $this->dao->select('*')->from(TABLE_NOTIFY)
            ->where('objectType')->eq('mail')
            ->andWhere('toList')->eq($user)
            ->fetchAll();
        
        $object = $this->objectModel->mergeMails($mails);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Sendmail. 
     * 
     * @param  int    $objectID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmailTest($objectID, $actionID)
    {
        $object = $this->objectModel->sendmail($objectID, $actionID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get subject. 
     * 
     * @param  int    $objectType 
     * @param  int    $object 
     * @param  int    $title 
     * @param  int    $actionType 
     * @access public
     * @return void
     */
    public function getSubjectTest($objectType, $object, $title, $actionType)
    {
        $object = $this->tester->loadModel($objectType)->getByID($object);
        $object = $this->objectModel->getSubject($objectType, $object, $title, $actionType);

        if(dao::isError()) return dao::getError();

        return $object;
    }
}
