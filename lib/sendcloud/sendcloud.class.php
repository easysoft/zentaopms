<?php
class sendcloud
{
//    public $url       = 'http://sendcloud.sohu.com/webapi/mail.send.json';
    public $url       = 'http://api.notice.sendcloud.net/mailapi/send';
    public $weixinUrl = 'http://api.notice.sendcloud.net/weixinapi/send';
    public $accessKey = '';
    public $secretKey = '';
    public $from      = '';
    public $fromname  = '';
    public $to        = '';
    public $cc        = '';
    public $subject   = '';
    public $content   = '';
    public $files     = '';
    public $headers   = '';
    public $replyto   = '';
    public $Subject   = ''; //Compatible phpmailer.
    public $nickNames = '';

    public function setFrom($from, $fromname = '')
    {
        $this->from = $from;
        $this->fromname = empty($fromname) ? $from : $fromname;
    }

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

        $this->nickNames .= $address . ';';
        return true;
    }

    public function send()
    {
        if(!empty($this->Subject) and empty($this->subject)) $this->subject = $this->Subject;

        $param['accessKey'] = $this->accessKey;
        $param['nickNames'] = $this->nickNames;
        $param['subject']   = $this->subject;
        $param['content']   = $this->content;
        $param['signature'] = $this->getSignature($param);

        $data   = http_build_query($param);
        $result = file_get_contents($this->url . '?' . $data);

        $result = json_decode($result);
        if($result->result == false)
        {
            throw new Exception($result->message . "(code:{$result->statusCode})");
            return false;
        }
    }

    public function getSignature($param)
    {
        ksort($param);
        $data = '';
        foreach($param as $key => $value) $data .= $key . '=' . $value . '&';
        return md5($this->secretKey . '&' . $data . $this->secretKey);
    }

    public function addAddress($address, $name = '')
    {
        return $this->AddAnAddress('to', $address, $name);
    }

    public function addCC($address, $name = '')
    {
        return $this->AddAnAddress('cc', $address, $name);
    }

    public function msgHtml($html)
    {
        $this->content = $html;
    }

    public function clearAllRecipients()
    {
        $this->to       = '';
        $this->cc       = '';
        $this->replyto  = '';
    }

    public function clearAttachments()
    {
        $this->subject  = '';
        $this->html     = '';
        $this->files    = '';
        $this->headers  = '';
        $this->Subject  = '';
    }

    public function setLanguage($langcode = 'en', $lang_path = '../phpmailer/language/')
    {
        return true;
    }
}
