<?php
define('PHPAES_ROOT', dirname(__FILE__));

include PHPAES_ROOT . '/phpseclib/Crypt/AES.php';
class phpAES
{
    CONST MODE_CTR    = -1;
    CONST MODE_ECB    = 1;
    CONST MODE_CBC    = 2;
    const MODE_CFB    = 3;
    const MODE_OFB    = 4;
    const MODE_STREAM = 5;

    const ENGINE_INTERNAL = 1;
    const ENGINE_EVAL     = 2;
    const ENGINE_MCRYPT   = 3;
    const ENGINE_OPENSSL  = 4;

    public function init($key, $iv)
    {
        $this->aes = new AES(self::MODE_CBC);
        $this->aes->setKey($key);
        $this->aes->setIV($iv);
    }

    public function encrypt($output)
    {
        return $this->aes->encrypt($output);
    }

    public function decrypt($input)
    {
        return $this->aes->decrypt($input);
    }

    public function getEngine()
    {
        switch($this->aes->getEngine())
        {
        case self::ENGINE_INTERNAL : return 'ENGINE_INTERNAL';
        case self::ENGINE_EVAL     : return 'ENGINE_EVAL';
        case self::ENGINE_MCRYPT   : return 'ENGINE_MCRYPT';
        case self::ENGINE_OPENSSL  : return 'ENGINE_OPENSSL';
        default: return 'error';
        }
    }
}
