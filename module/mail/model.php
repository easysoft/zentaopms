<?php
/**
 * The model file of mail module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class mailModel extends model
{
    private $mta;
    private $mtaType;


    public function __construct()
    {
        parent::__construct();
        helper::import('phpmailer');
    }

    private function factory($mta, $param = array())
    {
        $funcName = "set$mta";
        $this->mta = new phpmailer(true);
        $this->$funcName($param);
    }

    private function setSMTP($param)
    {
        $this->mta->isSMTP();
        $this->mta->Host     = $param->host;
        $this->mta->SMTPAuth = $param->auth;
        $this->mta->Username = $param->username;
        $this->mta->Password = $param->password;
    }

    private function setPhpMail($param)
    {
        $this->mta->isMail();
    }

    private function setSendMail($param)
    {
        $this->mta->isSendmail();
    }

    public function send($subject, $body, $to, $cc)
    {
        $this->factory($this->config->mailer->mta);
        // Define From Address.
        $this->From     = $BugConfig["this"]["FromAddress"];
        $this->FromName = $BugConfig["this"]["FromName"];

        // Add To Address.
        foreach($ToList as $To)
        {
            $this->addAddress($To);
        }
        if(is_array($CCList))
        {
            foreach($CCList as $CC)
            {
                $this->addCC($CC);
            }
        }
        // Add Subject.
        $this->Subject  =  stripslashes($Subject);

        // Set Body.
        $this->IsHTML(true);
        $this->CharSet = $BugConfig["Charset"];
        $this->Body    = stripslashes($Message);
        if(!$this->Send())
        {
            $MyJS->alert($this->ErrorInfo);
        }
    }
}
