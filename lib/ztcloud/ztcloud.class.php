<?php
class ztcloud
{
    public $url       = 'http://smtp.zentao.cn/index.php?m=mail&f=apiSend';
    public $account   = '';
    public $secretKey = '';
    public $from      = '';
    public $fromName  = '';
    public $toList    = '';
    public $ccList    = '';
    public $subject   = '';
    public $body      = '';
    public $files     = '';
    public $headers   = '';
    public $Subject   = ''; //Compatible phpmailer.

    /**
     * Set from.
     * 
     * @param  string $from 
     * @param  string $fromName 
     * @access public
     * @return void
     */
    public function setFrom($from, $fromName = '')
    {
        $this->from = $from;
        $this->fromName = empty($fromName) ? $from : $fromName;
    }

    /**
     * Add an address 
     * 
     * @param  string $kind 
     * @param  string $address 
     * @param  string $name 
     * @access public
     * @return bool
     */
    public function addAnAddress($kind, $address, $name = '')
    {
        $kind = strtolower($kind);
        if(!preg_match('/^(to|cc|bcc|replyto)$/', $kind))
        {
            $error = 'Invalid recipient array: ' . $kind;
            throw new Exception($error);
            echo $error;
            return false;
        }

        $address = trim($address);
        if(empty($address)) return true;

        $key = $kind . 'List';
        $this->$key .= $address . ';';
        return true;
    }

    /**
     * Send  mail
     * 
     * @access public
     * @return bool
     */
    public function send()
    {
        if(!empty($this->Subject) and empty($this->subject)) $this->subject = $this->Subject;

        $param['account']   = $this->account;
        $param['from']      = $this->from;
        $param['fromName']  = $this->fromName;
        $param['toList']    = $this->toList;
        $param['ccList']    = $this->ccList;
        $param['subject']   = $this->subject;
        $param['body']      = $this->body;

        $result = $this->queryZtcloud($this->url, $param);
        if(!isset($result->result))
        {
            throw new Exception($result);
            return false;
        }

        if($result->result == 'fail')
        {
            throw new Exception($result->message . "(code:{$result->code})");
            return false;
        }

        return true;
    }

    /**
     * Query Ztcloud 
     * 
     * @param  string $url 
     * @param  array  $param 
     * @access public
     * @return object
     */
    public function queryZtcloud($url, $param)
    {
        if(!isset($param['signature'])) $param['signature'] = $this->getSignature($param);

        $data = http_build_query($param);
        $options['http'] = array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $data
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, FILE_TEXT, $context);

        $parsedResult = json_decode($result);
        return empty($parsedResult) ? $result : $parsedResult;
    }

    /**
     * Compute Signature.
     * 
     * @param  array    $param 
     * @access public
     * @return string
     */
    public function getSignature($param)
    {
        ksort($param);
        $data = http_build_query($param);
        return md5($this->secretKey . '&' . $data . '&' . $this->secretKey);
    }

    /**
     * Add address 
     * 
     * @param  string $address 
     * @param  string $name 
     * @access public
     * @return bool
     */
    public function addAddress($address, $name = '')
    {
        return $this->addAnAddress('to', $address, $name);
    }

    /**
     * Add cc. 
     * 
     * @param  string $address 
     * @param  string $name 
     * @access public
     * @return bool
     */
    public function addCC($address, $name = '')
    {
        return $this->addAnAddress('cc', $address, $name);
    }

    /**
     * MsgHtml 
     * 
     * @param  string $html 
     * @access public
     * @return void
     */
    public function msgHtml($html)
    {
        $this->body = $html;
    }

    /**
     * Clear all recipients.
     * 
     * @access public
     * @return void
     */
    public function clearAllRecipients()
    {
        $this->toList = '';
        $this->ccList = '';
    }

    /**
     * Clear attachments.
     * 
     * @access public
     * @return void
     */
    public function clearAttachments()
    {
        $this->subject  = '';
        $this->html     = '';
        $this->files    = '';
        $this->headers  = '';
        $this->Subject  = '';
    }

    /**
     * Set language.
     * 
     * @param  string $langcode 
     * @param  string $lang_path 
     * @access public
     * @return bool
     */
    public function setLanguage($langcode = 'en', $lang_path = '../phpmailer/language/')
    {
        return true;
    }
}
