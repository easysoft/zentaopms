<?php
/*~ class.smtp.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer - PHP email class                                    |
|   Version: 5.1                                                            |
|   Contact: via sourceforge.net support pages (also www.codeworxtech.com)  |
|      Info: http://phpmailer.sourceforge.net                               |
|   Support: http://sourceforge.net/projects/phpmailer/                     |
| ------------------------------------------------------------------------- |
|     Admin: Andy Prevost (project admininistrator)                         |
|   Authors: Andy Prevost (codeworxtech) codeworxtech@users.sourceforge.net |
|          : Marcus Bointon (coolbru) coolbru@users.sourceforge.net         |
|   Founder: Brent R. Matzelle (original founder)                           |
| Copyright (c) 2004-2009, Andy Prevost. All Rights Reserved.               |
| Copyright (c) 2001-2003, Brent R. Matzelle                                |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services (www.codeworxtech.com):                |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
 */

/**
 * PHPMailer - PHP SMTP email transport class
 * NOTE: Designed for use with PHP version 5 and up
 * @package PHPMailer
 * @author Andy Prevost
 * @author Marcus Bointon
 * @copyright 2004 - 2008 Andy Prevost
 * @license http://www.gnu.org/copyleft/lesser.html Distributed under the Lesser General Public License (LGPL)
 * @version $Id: class.smtp.php 444 2009-05-05 11:22:26Z coolbru $
 */

/**
 * SMTP is rfc 821 compliant and implements all the rfc 821 SMTP
 * commands except TURN which will always return a not implemented
 * error. SMTP also provides some utility methods for sending mail
 * to an SMTP server.
 * original author: Chris Ryan
 */

class SMTP {
    /**
     *  SMTP server port
     *  @var int
     */
    public $SMTP_PORT = 25;

    /**
     *  SMTP reply line ending
     *  @var string
     */
    public $CRLF = "\r\n";

    /**
     *  Sets whether debugging is turned on
     *  @var bool
     */
    public $do_debug;       // the level of debug to perform

    /**
     *  Sets VERP use on/off (default is off)
     *  @var bool
     */
    public $do_verp = false;

    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE AND PROTECTED
    /////////////////////////////////////////////////

    private $smtp_conn; // the socket to the server
    private $error;     // error if any on the last call
    private $helo_rply; // the reply the server sent to us for HELO
    protected $server_caps = null;

    /**
     * Initialize the class so that the data is in a known state.
     * @access public
     * @return void
     */
    public function __construct() {
        $this->smtp_conn = 0;
        $this->error = null;
        $this->helo_rply = null;

        $this->do_debug = 0;
    }

    /////////////////////////////////////////////////
    // CONNECTION FUNCTIONS
    /////////////////////////////////////////////////

    /**
     * Connect to the server specified on the port specified.
     * If the port is not specified use the default SMTP_PORT.
     * If tval is specified then a connection will try and be
     * established with the server for that number of seconds.
     * If tval is not specified the default is 30 seconds to
     * try on the connection.
     *
     * SMTP CODE SUCCESS: 220
     * SMTP CODE FAILURE: 421
     * @access public
     * @return bool
     */
    public function Connect($host, $port = 0, $tval = 30) {
        // set the error val to null so there is no confusion
        $this->error = null;

        // make sure we are __not__ connected
        if($this->connected()) {
            // already connected, generate error
            $this->error = array("error" => "Already connected to a server");
            return false;
        }

        if(empty($port)) {
            $port = $this->SMTP_PORT;
        }

        // connect to the smtp server
        // Replace fsockopen for don't validate remote hosts
        $contextOptions['ssl']['verify_host']      = false;
        $contextOptions['ssl']['verify_peer']      = false;
        $contextOptions['ssl']['verify_peer_name'] = false;
        $context         = stream_context_create($contextOptions);
        $this->smtp_conn = stream_socket_client($host . ':' . $port, $errno, $errstr, $tval, STREAM_CLIENT_CONNECT, $context);
        // verify we connected properly
        if(empty($this->smtp_conn)) {
            $this->error = array("error" => "Failed to connect to server",
                "errno" => $errno,
                "errstr" => $errstr);
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": $errstr ($errno)" . $this->CRLF . '<br />';
            }
            return false;
        }

        // SMTP server can take longer to respond, give longer timeout for first read
        // Windows does not have support for this timeout function
        if(substr(PHP_OS, 0, 3) != "WIN")
            socket_set_timeout($this->smtp_conn, $tval, 0);

        // get any announcement
        $announce = $this->get_lines();

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $announce . $this->CRLF . '<br />';
        }

        return true;
    }

    /**
     * Initiate a TLS communication with the server.
     *
     * SMTP CODE 220 Ready to start TLS
     * SMTP CODE 501 Syntax error (no parameters allowed)
     * SMTP CODE 454 TLS not available due to temporary reason
     * @access public
     * @return bool success
     */
    public function StartTLS() {
        $this->error = null; # to avoid confusion

        if(!$this->connected()) {
            $this->error = array("error" => "Called StartTLS() without being connected");
            return false;
        }

        fputs($this->smtp_conn,"STARTTLS" . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 220) {
            $this->error =
                array("error"     => "STARTTLS not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg"  => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        // Begin encrypted connection
        if(!stream_socket_enable_crypto($this->smtp_conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            return false;
        }

        return true;
    }

    /**
     * Performs SMTP authentication.  Must be run after running the
     * Hello() method.  Returns true if successfully authenticated.
     * @access public
     * @return bool
     */
    public function Authenticate($username, $password)
    {
        $authtype = '';
        if (!$this->server_caps) {
            $this->error = array("error" => "Authentication is not allowed before HELO/EHLO");
            return false;
        }

        if (array_key_exists('EHLO', $this->server_caps)) {
            // SMTP extensions are available; try to find a proper authentication method
            if (!array_key_exists('AUTH', $this->server_caps)) {
                $this->error = array("error" => "Authentication is not allowed at this stage");
                // 'at this stage' means that auth may be allowed after the stage changes
                // e.g. after STARTTLS

                return false;
            }


            //If we have requested a specific auth type, check the server supports it before trying others
            if (null !== $authtype and !in_array($authtype, $this->server_caps['AUTH'])) {
                $authtype = null;
            }

            if (empty($authtype)) {
                //If no auth mechanism is specified, attempt to use these, in this order
                //Try CRAM-MD5 first as it's more secure than the others
                foreach (array('CRAM-MD5', 'LOGIN', 'PLAIN', 'XOAUTH2', 'NTLM') as $method) {
                    if (in_array($method, $this->server_caps['AUTH'])) {
                        $authtype = $method;
                        break;
                    }
                }
                if (empty($authtype)) {
                    $this->error = array("error" => "No supported authentication methods found");
                    return false;
                }
            }

            if (!in_array($authtype, $this->server_caps['AUTH'])) {
                $this->error = array("error" => "The requested authentication method \"$authtype\" is not supported by the server");
                return false;
            }
        } elseif (empty($authtype)) {
            $authtype = 'LOGIN';
        }
        switch ($authtype) {
        case 'PLAIN':
            // Start authentication
            if (!$this->sendCommand('AUTH', 'AUTH PLAIN', 334)) {
                return false;
            }
            // Send encoded username and password
            if (!$this->sendCommand(
                'User & Password',
                base64_encode("\0" . $username . "\0" . $password),
                235
            )
            ) {
                return false;
            }
            break;
        case 'LOGIN':
            // Start authentication
            if (!$this->sendCommand('AUTH', 'AUTH LOGIN', 334)) {
                return false;
            }
            if (!$this->sendCommand('Username', base64_encode($username), 334)) {
                return false;
            }
            if (!$this->sendCommand('Password', base64_encode($password), 235)) {
                return false;
            }
            break;
        case 'NTLM':
            // Start authentication
            helper::import(dirname(__FILE__) . '/ntlm_sasl_client.php');
            $temp = new stdClass();
            $ntlmClient = new ntlm_sasl_client_class();
            if(!$ntlmClient->Initialize($temp)) //let's test if every function its available
			{
                $this->error = array("error" => $temp->error);
                if($this->do_debug >= 1) {
                    echo "You need to enable some modules in your php.ini file: " . $this->error["error"] . $this->CRLF;
                }
                return false;
            }
			$msg1 = $ntlmClient->TypeMsg1();
			if(!$this->sendCommand('AUTH', "AUTH NTLM " . base64_encode($msg1), 334)) return false;
            $challange = trim(substr($this->last_reply, 3));//though 0 based, there is a white space after the 3 digit number....//msg2
            $challange = base64_decode($challange);
            $ntlmRes = $ntlmClient->NTLMResponse(substr($challange, 24, 8), $password);
            $msg3 = $ntlmClient->TypeMsg3($ntlmRes, $username, '', '');//msg3
            // Send encoded username
			if (!$this->sendCommand('Password', base64_encode($msg3), 235)) return false;
            break;
        case 'CRAM-MD5':
            // Start authentication
            if (!$this->sendCommand('AUTH CRAM-MD5', 'AUTH CRAM-MD5', 334)) {
                return false;
            }
            // Get the challenge
            $challenge = base64_decode(substr($this->last_reply, 4));

            // Build the response
            $response = $username . ' ' . $this->hmac($challenge, $password);

            // send encoded credentials
            return $this->sendCommand('Username', base64_encode($response), 235);
        default:
            $this->error = array("error" => "Authentication method \"$authtype\" is not supported");
            return false;
        }

        return true;
	}

	/**
	 * Calculate an MD5 HMAC hash.
	 * Works like hash_hmac('md5', $data, $key)
	 * in case that function is not available.
	 *
	 * @param string $data The data to hash
	 * @param string $key  The key to hash with
	 *
	 * @return string
	 */
	public function hmac($data, $key)
	{
		if(function_exists('hash_hmac')) return hash_hmac('md5', $data, $key);

		// The following borrowed from
		// http://php.net/manual/en/function.mhash.php#27225
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// by Lance Rushing
		$bytelen = 64; // byte length for md5
		if(strlen($key) > $bytelen) $key = pack('H*', md5($key));
		$key = str_pad($key, $bytelen, chr(0x00));
		$ipad = str_pad('', $bytelen, chr(0x36));
		$opad = str_pad('', $bytelen, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;

		return md5($k_opad . pack('H*', md5($k_ipad . $data)));
	}

    /**
     * Returns true if connected to a server otherwise false
     * @access public
     * @return bool
     */
    public function Connected()
    {
        if(!empty($this->smtp_conn)) {
            $sock_status = socket_get_status($this->smtp_conn);
            if($sock_status["eof"]) {
                // the socket is valid but we are not connected
                if($this->do_debug >= 1) {
                    echo "SMTP -> NOTICE:" . $this->CRLF . "EOF caught while checking if connected";
                }
                $this->Close();
                return false;
            }
            return true; // everything looks good
        }
        return false;
    }

    /**
     * Closes the socket and cleans up the state of the class.
     * It is not considered good to use this function without
     * first trying to use QUIT.
     * @access public
     * @return void
     */
    public function Close() {
        $this->error = null; // so there is no confusion
        $this->helo_rply = null;
        if(!empty($this->smtp_conn)) {
            // close the connection and cleanup
            fclose($this->smtp_conn);
            $this->smtp_conn = 0;
        }
    }

    /////////////////////////////////////////////////
    // SMTP COMMANDS
    /////////////////////////////////////////////////

    /**
     * Issues a data command and sends the msg_data to the server
     * finializing the mail transaction. $msg_data is the message
     * that is to be send with the headers. Each header needs to be
     * on a single line followed by a <CRLF> with the message headers
     * and the message body being seperated by and additional <CRLF>.
     *
     * Implements rfc 821: DATA <CRLF>
     *
     * SMTP CODE INTERMEDIATE: 354
     *     [data]
     *     <CRLF>.<CRLF>
     *     SMTP CODE SUCCESS: 250
     *     SMTP CODE FAILURE: 552,554,451,452
     * SMTP CODE FAILURE: 451,554
     * SMTP CODE ERROR  : 500,501,503,421
     * @access public
     * @return bool
     */
    public function Data($msg_data) {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Data() without being connected");
            return false;
        }

        fputs($this->smtp_conn,"DATA" . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 354) {
            $this->error =
                array("error" => "DATA command not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        /* the server is ready to accept data!
         * according to rfc 821 we should not send more than 1000
         * including the CRLF
         * characters on a single line so we will break the data up
         * into lines by \r and/or \n then if needed we will break
         * each of those into smaller lines to fit within the limit.
         * in addition we will be looking for lines that start with
         * a period '.' and append and additional period '.' to that
         * line. NOTE: this does not count towards limit.
         */

        // normalize the line breaks so we know the explode works
        $msg_data = str_replace("\r\n","\n",$msg_data);
        $msg_data = str_replace("\r","\n",$msg_data);
        $lines = explode("\n",$msg_data);

        /* we need to find a good way to determine is headers are
         * in the msg_data or if it is a straight msg body
         * currently I am assuming rfc 822 definitions of msg headers
         * and if the first field of the first line (':' sperated)
         * does not contain a space then it _should_ be a header
         * and we can process all lines before a blank "" line as
         * headers.
         */

        $field = substr($lines[0],0,strpos($lines[0],":"));
        $in_headers = false;
        if(!empty($field) && !strstr($field," ")) {
            $in_headers = true;
        }

        $max_line_length = 998; // used below; set here for ease in change

        foreach($lines as $line) {
            $lines_out = null;
            if($line == "" && $in_headers) {
                $in_headers = false;
            }
            // ok we need to break this line up into several smaller lines
            while(strlen($line) > $max_line_length) {
                $pos = strrpos(substr($line,0,$max_line_length)," ");

                // Patch to fix DOS attack
                if(!$pos) {
                    $pos = $max_line_length - 1;
                    $lines_out[] = substr($line,0,$pos);
                    $line = substr($line,$pos);
                } else {
                    $lines_out[] = substr($line,0,$pos);
                    $line = substr($line,$pos + 1);
                }

                /* if processing headers add a LWSP-char to the front of new line
                 * rfc 822 on long msg headers
                 */
                if($in_headers) {
                    $line = "\t" . $line;
                }
            }
            $lines_out[] = $line;

            // send the lines to the server
            foreach($lines_out as $line_out) {
                if(strlen($line_out) > 0)
                {
                    if(substr($line_out, 0, 1) == ".") {
                        $line_out = "." . $line_out;
                    }
                }
                fputs($this->smtp_conn,$line_out . $this->CRLF);
            }
        }

        // message data has been sent
        fputs($this->smtp_conn, $this->CRLF . "." . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 250) {
            $this->error =
                array("error" => "DATA not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    /**
     * Sends the HELO command to the smtp server.
     * This makes sure that we and the server are in
     * the same known state.
     *
     * Implements from rfc 821: HELO <SP> <domain> <CRLF>
     *
     * SMTP CODE SUCCESS: 250
     * SMTP CODE ERROR  : 500, 501, 504, 421
     * @access public
     * @return bool
     */
    public function Hello($host = '') {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Hello() without being connected");
            return false;
        }

        // if hostname for HELO was not specified send default
        if(empty($host)) {
            // determine appropriate default to send to server
            $host = "localhost";
        }

        // Send extended hello first (RFC 2821)
        if(!$this->SendHello("EHLO", $host)) {
            if(!$this->SendHello("HELO", $host)) {
                return false;
            }
        }

        return true;
    }

    protected function sendHello($hello, $host)
    {
        $noerror = $this->sendCommand($hello, $hello . ' ' . $host, 250);
        $this->helo_rply = $this->last_reply;
        if ($noerror) {
            $this->parseHelloFields($hello);
        } else {
            $this->server_caps = null;
        }

        return $noerror;
    }

    protected function parseHelloFields($type)
    {
        $this->server_caps = array();
        $lines = explode("\n", $this->helo_rply);

        foreach ($lines as $n => $s) {
            //First 4 chars contain response code followed by - or space
            $s = trim(substr($s, 4));
            if (empty($s)) {
                continue;
            }
            $fields = explode(' ', $s);
            if (!empty($fields)) {
                if (!$n) {
                    $name = $type;
                    $fields = $fields[0];
                } else {
                    $name = array_shift($fields);
                    switch ($name) {
                    case 'SIZE':
                        $fields = ($fields ? $fields[0] : 0);
                        break;
                    case 'AUTH':
                        if (!is_array($fields)) {
                            $fields = array();
                        }
                        break;
                    default:
                        $fields = true;
                    }
                }
                $this->server_caps[$name] = $fields;
            }
        }
    }

    protected function sendCommand($command, $commandstring, $expect)
    {
        if (!$this->connected()) {
            $this->error = array("error" => "Called $command without being connected");
            return false;
        }
        //Reject line breaks in all commands
        if (strpos($commandstring, "\n") !== false or strpos($commandstring, "\r") !== false) {
            $this->error = array("error" => "Command '$command' contained line breaks");
            return false;
        }
        $this->client_send($commandstring . $this->CRLF, $command);

        $this->last_reply = $this->get_lines();
        // Fetch SMTP code and possible error code explanation
        $matches = array();
        if (preg_match('/^([0-9]{3})[ -](?:([0-9]\\.[0-9]\\.[0-9]) )?/', $this->last_reply, $matches)) {
            $code = $matches[1];
            $code_ex = (count($matches) > 2 ? $matches[2] : null);
            // Cut off error code from each response line
            $detail = preg_replace(
                "/{$code}[ -]" .
                ($code_ex ? str_replace('.', '\\.', $code_ex) . ' ' : '') . '/m',
                    '',
                    $this->last_reply
                );
        } else {
            // Fall back to simple parsing if regex fails
            $code = substr($this->last_reply, 0, 3);
            $code_ex = null;
            $detail = substr($this->last_reply, 4);
        }

        if (!in_array($code, (array) $expect)) {
            $this->error = array("error" => "$command command failed $detail $code $code_ex");
            return false;
        }

        $this->error = array();
        return true;
    }

    public function client_send($data, $command = '')
    {
        //If SMTP transcripts are left enabled, or debug output is posted online
        //it can leak credentials, so hide credentials in all but lowest level
        $result = fwrite($this->smtp_conn, $data);
        restore_error_handler();

        return $result;
    }

    /**
     * Starts a mail transaction from the email address specified in
     * $from. Returns true if successful or false otherwise. If True
     * the mail transaction is started and then one or more Recipient
     * commands may be called followed by a Data command.
     *
     * Implements rfc 821: MAIL <SP> FROM:<reverse-path> <CRLF>
     *
     * SMTP CODE SUCCESS: 250
     * SMTP CODE SUCCESS: 552,451,452
     * SMTP CODE SUCCESS: 500,501,421
     * @access public
     * @return bool
     */
    public function Mail($from) {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Mail() without being connected");
            return false;
        }

        $useVerp = ($this->do_verp ? "XVERP" : "");
        fputs($this->smtp_conn,"MAIL FROM:<" . $from . ">" . $useVerp . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 250) {
            $this->error =
                array("error" => "MAIL not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    /**
     * Sends the quit command to the server and then closes the socket
     * if there is no error or the $close_on_error argument is true.
     *
     * Implements from rfc 821: QUIT <CRLF>
     *
     * SMTP CODE SUCCESS: 221
     * SMTP CODE ERROR  : 500
     * @access public
     * @return bool
     */
    public function Quit($close_on_error = true) {
        $this->error = null; // so there is no confusion

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Quit() without being connected");
            return false;
        }

        // send the quit command to the server
        fputs($this->smtp_conn,"quit" . $this->CRLF);

        // get any good-bye messages
        $byemsg = $this->get_lines();

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $byemsg . $this->CRLF . '<br />';
        }

        $rval = true;
        $e = null;

        $code = substr($byemsg,0,3);
        if($code != 221) {
            // use e as a tmp var cause Close will overwrite $this->error
            $e = array("error" => "SMTP server rejected quit command",
                "smtp_code" => $code,
                "smtp_rply" => substr($byemsg,4));
            $rval = false;
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $e["error"] . ": " . $byemsg . $this->CRLF . '<br />';
            }
        }

        if(empty($e) || $close_on_error) {
            $this->Close();
        }

        return $rval;
    }

    /**
     * Sends the command RCPT to the SMTP server with the TO: argument of $to.
     * Returns true if the recipient was accepted false if it was rejected.
     *
     * Implements from rfc 821: RCPT <SP> TO:<forward-path> <CRLF>
     *
     * SMTP CODE SUCCESS: 250,251
     * SMTP CODE FAILURE: 550,551,552,553,450,451,452
     * SMTP CODE ERROR  : 500,501,503,421
     * @access public
     * @return bool
     */
    public function Recipient($to) {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Recipient() without being connected");
            return false;
        }

        fputs($this->smtp_conn,"RCPT TO:<" . $to . ">" . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 250 && $code != 251) {
            $this->error =
                array("error" => "RCPT not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    /**
     * Sends the RSET command to abort and transaction that is
     * currently in progress. Returns true if successful false
     * otherwise.
     *
     * Implements rfc 821: RSET <CRLF>
     *
     * SMTP CODE SUCCESS: 250
     * SMTP CODE ERROR  : 500,501,504,421
     * @access public
     * @return bool
     */
    public function Reset() {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called Reset() without being connected");
            return false;
        }

        fputs($this->smtp_conn,"RSET" . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 250) {
            $this->error =
                array("error" => "RSET failed",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        return true;
    }

    /**
     * Starts a mail transaction from the email address specified in
     * $from. Returns true if successful or false otherwise. If True
     * the mail transaction is started and then one or more Recipient
     * commands may be called followed by a Data command. This command
     * will send the message to the users terminal if they are logged
     * in and send them an email.
     *
     * Implements rfc 821: SAML <SP> FROM:<reverse-path> <CRLF>
     *
     * SMTP CODE SUCCESS: 250
     * SMTP CODE SUCCESS: 552,451,452
     * SMTP CODE SUCCESS: 500,501,502,421
     * @access public
     * @return bool
     */
    public function SendAndMail($from) {
        $this->error = null; // so no confusion is caused

        if(!$this->connected()) {
            $this->error = array(
                "error" => "Called SendAndMail() without being connected");
            return false;
        }

        fputs($this->smtp_conn,"SAML FROM:" . $from . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply,0,3);

        if($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if($code != 250) {
            $this->error =
                array("error" => "SAML not accepted from server",
                    "smtp_code" => $code,
                    "smtp_msg" => substr($rply,4));
            if($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    /**
     * This is an optional command for SMTP that this class does not
     * support. This method is here to make the RFC821 Definition
     * complete for this class and __may__ be implimented in the future
     *
     * Implements from rfc 821: TURN <CRLF>
     *
     * SMTP CODE SUCCESS: 250
     * SMTP CODE FAILURE: 502
     * SMTP CODE ERROR  : 500, 503
     * @access public
     * @return bool
     */
    public function Turn() {
        $this->error = array("error" => "This method, TURN, of the SMTP ".
            "is not implemented");
        if($this->do_debug >= 1) {
            echo "SMTP -> NOTICE: " . $this->error["error"] . $this->CRLF . '<br />';
        }
        return false;
    }

    /**
     * Get the current error
     * @access public
     * @return array
     */
    public function getError() {
        return $this->error;
    }

    /////////////////////////////////////////////////
    // INTERNAL FUNCTIONS
    /////////////////////////////////////////////////

    /**
     * Read in as many lines as possible
     * either before eof or socket timeout occurs on the operation.
     * With SMTP we can tell if we have more lines to read if the
     * 4th character is '-' symbol. If it is a space then we don't
     * need to read anything else.
     * @access private
     * @return string
     */
    private function get_lines() {
        $data = "";
        while($str = fgets($this->smtp_conn,515)) {
            if($this->do_debug >= 4) {
                echo "SMTP -> get_lines(): \$data was \"$data\"" . $this->CRLF . '<br />';
                echo "SMTP -> get_lines(): \$str is \"$str\"" . $this->CRLF . '<br />';
            }
            $data .= $str;
            if($this->do_debug >= 4) {
                echo "SMTP -> get_lines(): \$data is \"$data\"" . $this->CRLF . '<br />';
            }
            // if 4th character is a space, we are done reading, break the loop
            if(substr($str,3,1) == " ") { break; }
        }
        return $data;
    }
}
?>
