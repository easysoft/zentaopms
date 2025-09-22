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
         $this->dao         = $dao;
         $this->tester      = $tester;
         $this->objectModel = $tester->loadModel('mail');
         $this->objectTao   = $tester->loadTao('mail');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest()
    {
        $result = new stdClass();
        $result->isMailModel = $this->objectModel instanceof mailModel;
        $result->hasMTA = isset($this->objectModel->mta);
        $result->hasErrors = isset($this->objectModel->errors);
        $result->hasConfig = isset($this->objectModel->config);
        $result->mtaType = $this->objectModel->mta ? get_class($this->objectModel->mta) : '';
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * AutoDetect.
     *
     * @param  int    $email
     * @access public
     * @return object
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
     * @return object
     */
    public function getConfigFromProviderTest($domain, $username)
    {
        $object = $this->objectModel->getConfigFromProvider($domain, $username);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get config by MXRR.
     *
     * @param  int    $domain
     * @param  int    $username
     * @access public
     * @return object
     */
    public function getConfigByMXRRTest($domain, $username)
    {
        $object = $this->objectModel->getConfigByMXRR($domain, $username);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get config by detecting SMTP.
     *
     * @param  int    $domain
     * @param  int    $username
     * @param  int    $port
     * @access public
     * @return object
     */
    public function getConfigByDetectingSMTPTest($domain, $username, $port)
    {
        $object = $this->objectModel->getConfigByDetectingSMTP($domain, $username, $port);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Set MTA.
     *
     * @access public
     * @return object
     */
    public function setMTATest()
    {
        $object = $this->objectModel->setMTA();

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get MTA class name.
     *
     * @access public
     * @return string
     */
    public function getMTAClassNameTest()
    {
        $object = $this->objectModel->setMTA();

        if(dao::isError()) return dao::getError();

        return get_class($object);
    }

    /**
     * Get MTA type string.
     *
     * @access public
     * @return string
     */
    public function getMTATypeTest()
    {
        $object = $this->objectModel->setMTA();

        if(dao::isError()) return dao::getError();

        return is_object($object) ? 'object' : gettype($object);
    }

    /**
     * Test MTA singleton pattern.
     *
     * @access public
     * @return mixed
     */
    public function testMTASingletonTest()
    {
        $mta1 = $this->objectModel->setMTA();
        $mta2 = $this->objectModel->setMTA();

        if(dao::isError()) return dao::getError();

        return ($mta1 === $mta2) ? 1 : 0;
    }

    /**
     * Test MTA with different configurations.
     *
     * @param  string $mtaType
     * @access public
     * @return mixed
     */
    public function setMTAWithTypeTest($mtaType = 'smtp')
    {
        $originalMta = $this->objectModel->config->mail->mta;
        $this->objectModel->config->mail->mta = $mtaType;

        if($mtaType == 'gmail')
        {
            if(!isset($this->objectModel->config->mail->gmail))
            {
                $this->objectModel->config->mail->gmail = new stdClass();
                $this->objectModel->config->mail->gmail->debug = 0;
                $this->objectModel->config->mail->gmail->username = 'test@gmail.com';
                $this->objectModel->config->mail->gmail->password = 'testpass';
            }
        }

        try
        {
            $result = $this->objectModel->setMTA();
            $this->objectModel->config->mail->mta = $originalMta;

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            $this->objectModel->config->mail->mta = $originalMta;
            return $e->getMessage();
        }
    }

    /**
     * Set SMTP.
     *
     * @access public
     * @return mixed
     */
    public function setSMTPTest()
    {
        $this->objectModel->setSMTP();

        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->mta = $this->objectModel->mta;
        $result->host = isset($this->objectModel->mta->Host) ? $this->objectModel->mta->Host : '';
        $result->port = isset($this->objectModel->mta->Port) ? $this->objectModel->mta->Port : '';
        $result->username = isset($this->objectModel->mta->Username) ? $this->objectModel->mta->Username : '';
        $result->auth = isset($this->objectModel->mta->SMTPAuth) ? $this->objectModel->mta->SMTPAuth : '';
        $result->debug = isset($this->objectModel->mta->SMTPDebug) ? $this->objectModel->mta->SMTPDebug : '';
        $result->charset = isset($this->objectModel->mta->CharSet) ? $this->objectModel->mta->CharSet : '';
        $result->secure = isset($this->objectModel->mta->SMTPSecure) ? $this->objectModel->mta->SMTPSecure : '';

        return $result;
    }

    /**
     * Set sendmail.
     *
     * @access public
     * @return object
     */
    public function setSendMailTest()
    {
        $object = $this->objectModel->setSendMail();

        if(dao::isError()) return dao::getError();

        return $object;
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
     * @return object
     */
    public function sendTest($toList, $subject, $body = '', $ccList = '', $includeMe = false, $emails = array())
    {
        $object = $this->objectModel->send($toList, $subject, $body, $ccList, $includeMe, $emails);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test setCC method.
     *
     * @param  array $ccList
     * @param  array $emails
     * @access public
     * @return mixed
     */
    public function setCCTest($ccList, $emails)
    {
        $this->objectModel->setCC($ccList, $emails);
        if(dao::isError()) return dao::getError();

        return $emails;
    }

    /**
     * Set subject.
     *
     * @param  string $subject
     * @access public
     * @return object
     */
    public function setSubjectTest($subject)
    {
        $this->objectModel->setSubject($subject);

        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->Subject = $this->objectModel->mta->Subject;
        $result->original = $subject;
        $result->processed = stripslashes($subject);

        return $result;
    }

    /**
     * Set body.
     *
     * @param  int    $body
     * @access public
     * @return object
     */
    public function setBodyTest($body)
    {
        $this->objectModel->setBody($body);

        return $this->setMTATest();
    }

    /**
     * Mail exist.
     *
     * @access public
     * @return object
     */
    public function mailExistTest()
    {
        $object = $this->objectModel->mailExist();

        if(dao::isError()) return dao::getError();

        return $object;
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
     * @return object
     */
    public function addQueueTest($toList, $subject, $body = '', $ccList = '', $includeMe = false)
    {
        $notifyID = $this->objectModel->addQueue($toList, $subject, $body, $ccList, $includeMe);
        $object   = $this->dao->select('*')->from(TABLE_NOTIFY)->where('id')->eq($notifyID)->fetch();

        if(dao::isError()) return dao::getError();
        if(!$object)       return '没有数据提交';

        return $object;
    }

    /**
     * Get queue.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getQueueTest($status = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getQueue($status, $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get queue by id.
     *
     * @param  int    $queueID
     * @access public
     * @return object
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
     * @return object
     */
    public function mergeMailsTest($user)
    {
        $mails = $this->dao->select('*')->from(TABLE_NOTIFY)
            ->where('objectType')->eq('mail')
            ->andWhere('toList')->eq($user)
            ->orderBy('id')
            ->fetchAll('', false);

        $object = $this->objectModel->mergeMails($mails);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get subject.
     *
     * @param  int    $objectType
     * @param  int    $objectID
     * @param  int    $title
     * @param  int    $actionType
     * @access public
     * @return object
     */
    public function getSubjectTest($objectType, $objectID, $title, $actionType)
    {
        $object = $this->tester->loadModel($objectType)->fetchByID($objectID);
        $object = $this->objectModel->getSubject($objectType, $object, $title, $actionType);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test getAddressees method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $actionID
     * @access public
     * @return array|false
     */
    public function getAddresseesTest($objectType, $objectID, $actionID)
    {
        $object = $this->objectModel->getObjectForMail($objectType, $objectID);
        if(!$object) return false;
        $action = $this->objectModel->getActionForMail($actionID);
        if(!$action) return false;

        return $this->objectModel->getAddressees($objectType, $object, $action);
    }

    /**
     * Test getMailContent method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $actionID
     * @access public
     * @return array|false
     */
    public function getMailContentTest($objectType, $objectID, $actionID)
    {
        $object = $this->objectModel->getObjectForMail($objectType, $objectID);
        if(!$object) return false;
        $action = $this->objectModel->getActionForMail($actionID);
        if(!$action) return false;

        return $this->objectModel->getMailContent($objectType, $object, $action);
    }

    /**
     * Test getObjectTitle method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return string|false
     */
    public function getObjectTitleTest($objectType, $objectID)
    {
        $object = $this->objectModel->getObjectForMail($objectType, $objectID);
        if(!$object) return false;

        return $this->objectModel->getObjectTitle($object, $objectType);
    }

    /**
     * Test setImages method.
     *
     * @param  array $images
     * @access public
     * @return mixed
     */
    public function setImagesTest($images = array())
    {
        $this->objectModel->setImages($images);
        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->processed = true;
        $result->imageCount = count($images);
        $result->uniqueImageCount = count(array_filter(array_unique($images)));
        
        return $result;
    }

    /**
     * Test setErrorLang method.
     *
     * @access public
     * @return mixed
     */
    public function setErrorLangTest()
    {
        $this->objectModel->setErrorLang();
        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->processed = true;
        $result->mta = $this->objectModel->mta;
        $result->currentLang = $this->objectModel->app->getClientLang();
        
        return $result;
    }

    /**
     * Test clear method.
     *
     * @access public
     * @return mixed
     */
    public function clearTest()
    {
        $this->objectModel->clear();
        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->processed = true;
        $result->mta = $this->objectModel->mta;
        
        return $result;
    }

    /**
     * Test getImages method.
     *
     * @param  string $body
     * @access public
     * @return array
     */
    public function getImagesTest($body)
    {
        $result = $this->objectModel->getImages($body);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getImagesByFileID method.
     *
     * @param  array $matches
     * @access public
     * @return array
     */
    public function getImagesByFileIDTest($matches = array())
    {
        $result = $this->objectModel->getImagesByFileID($matches);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getImagesByPath method.
     *
     * @param  array $matches
     * @access public
     * @return array
     */
    public function getImagesByPathTest($matches = array())
    {
        $result = $this->objectModel->getImagesByPath($matches);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getConfigForEdit method.
     *
     * @access public
     * @return mixed
     */
    public function getConfigForEditTest()
    {
        $result = $this->objectZen->getConfigForEdit();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test convertCharset method.
     *
     * @param  string $string
     * @access public
     * @return string
     */
    public function convertCharsetTest($string)
    {
        $result = $this->objectModel->convertCharset($string);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getError method.
     *
     * @access public
     * @return array
     */
    public function getErrorTest()
    {
        $result = $this->objectModel->getError();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isError method.
     *
     * @access public
     * @return bool
     */
    public function isErrorTest()
    {
        $result = $this->objectModel->isError();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  object $item
     * @param  string $method
     * @access public
     * @return bool
     */
    public function isClickableTest($item, $method)
    {
        $result = $this->objectModel->isClickable($item, $method);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setTO method.
     *
     * @param  array $toList
     * @param  array $emails
     * @access public
     * @return mixed
     */
    public function setTOTest($toList = array(), $emails = array())
    {
        $this->objectModel->setTO($toList, $emails);
        if(dao::isError()) return dao::getError();

        return $emails;
    }

    /**
     * Test sendmail method.
     *
     * @param  int $objectID
     * @param  int $actionID
     * @access public
     * @return mixed
     */
    public function sendmailTest($objectID, $actionID)
    {
        try
        {
            ob_start();
            $this->objectModel->sendmail($objectID, $actionID);
            $output = ob_get_clean();

            if(dao::isError()) return dao::getError();

            $result = new stdClass();
            $result->processed = 1;
            $result->objectID = $objectID;
            $result->actionID = $actionID;
            $result->hasErrors = $this->objectModel->isError() ? 1 : 0;
            $result->errors = $this->objectModel->errors;
            $result->output = $output;

            return $result;
        }
        catch(Exception $e)
        {
            $result = new stdClass();
            $result->processed = 0;
            $result->objectID = $objectID;
            $result->actionID = $actionID;
            $result->error = $e->getMessage();
            $result->hasErrors = 1;

            return $result;
        }
        catch(TypeError $e)
        {
            $result = new stdClass();
            $result->processed = 0;
            $result->objectID = $objectID;
            $result->actionID = $actionID;
            $result->error = $e->getMessage();
            $result->hasErrors = 1;

            return $result;
        }
    }
}
