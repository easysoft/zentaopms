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
    public $subject   = '';
    public $content   = '';
    public $files     = '';
    public $headers   = '';
    public $Subject   = ''; //Compatible phpmailer.
    public $nickNames = '';

    /**
     * Set from.
     * 
     * @param  string $from 
     * @param  string $fromname 
     * @access public
     * @return void
     */
    public function setFrom($from, $fromname = '')
    {
        $this->from = $from;
        $this->fromname = empty($fromname) ? $from : $fromname;
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

        $this->nickNames .= $address . ';';
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

        $param['accessKey'] = $this->accessKey;
        $param['nickNames'] = preg_replace('/[^a-zA-z0-9@\._;]/', '_', $this->nickNames);
        $param['subject']   = $this->subject;
        $param['content']   = $this->content;

        $result = $this->querySendcloud($this->url, $param);
        if($result->result == false)
        {
            throw new Exception($result->message . "(code:{$result->statusCode})");
            return false;
        }
    }

    /**
     * Get member list.
     * 
     * @access public
     * @return array
     */
    public function memberList()
    {
        $url = 'http://api.notice.sendcloud.net/linkmanMember/list';
        $param['accessKey'] = $this->accessKey;
        $param['pageSize']  = 1000;

        $result = $this->querySendcloud($url, $param);
        if($result->result == false and $result->statusCode != '481')
        {
            throw new Exception($result->message . "(code:{$result->statusCode})");
            return false;
        }
        $members = array();
        foreach($result->info->linkmanMembers as $member) $members[$member->email] = $member;

        return $members;
    }

    /**
     * Add member 
     * 
     * @param  object $member 
     * @access public
     * @return object
     */
    public function addMember($member)
    {
        $url = 'http://api.notice.sendcloud.net/linkmanMember/add';
        $param['accessKey'] = $this->accessKey;
        $param['nickName']  = preg_replace('/[^a-zA-z0-9@\._]/', '_', $member->nickName);
        $param['email']     = $member->email;
        if(isset($member->userName)) $param['userName'] = $member->userName;
        if(isset($member->phone))    $param['phone']    = $member->phone;

        return $this->querySendcloud($url, $param);
    }

    /**
     * Delete member.
     * 
     * @param  string $nickName 
     * @access public
     * @return object
     */
    public function deleteMember($nickName)
    {
        $url = 'http://api.notice.sendcloud.net/linkmanMember/remove';
        $param['accessKey'] = $this->accessKey;
        $param['nickName']  = $nickName;

        return $this->querySendcloud($url, $param);
    }

    /**
     * Query Sendcloud 
     * 
     * @param  string $url 
     * @param  array  $param 
     * @access public
     * @return object
     */
    public function querySendcloud($url, $param)
    {
        if(!isset($param['signature'])) $param['signature'] = $this->getSignature($param);

        $data   = http_build_query($param);
        $result = file_get_contents($url . '?' . $data);

        return json_decode($result);
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
        $data = '';
        foreach($param as $key => $value) $data .= $key . '=' . $value . '&';
        return md5($this->secretKey . '&' . $data . $this->secretKey);
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
        return $this->AddAnAddress('to', $address, $name);
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
        return $this->AddAnAddress('cc', $address, $name);
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
        $this->content = $html;
    }

    /**
     * Clear all recipients.
     * 
     * @access public
     * @return void
     */
    public function clearAllRecipients()
    {
        $this->nickNames = '';
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
