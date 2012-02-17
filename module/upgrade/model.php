<?php
/**
 * The model file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class upgradeModel extends model
{
    static $errors = array();

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
    }

    /**
     * The execute method. According to the $fromVersion call related methods.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        if($fromVersion == '0_3beta')
        {
            $this->upgradeFrom0_3To0_4();
            $this->upgradeFrom0_4To0_5();
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '0_4beta')
        {
            $this->upgradeFrom0_4To0_5();
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '0_5beta')
        {
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '0_6beta')
        {
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_0beta')
        {
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_0rc1')
        {
            $this->upgradeFrom1_0rc1To1_0rc2();
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_0rc2')
        {
            $this->upgradeFrom1_0rc2To1_0stable();
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_0')
        {
            $this->upgradeFrom1_0stableTo1_0_1();
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_0_1')
        {
            $this->upgradeFrom1_0_1To1_1();
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_1')
        {
            $this->upgradeFrom1_1To1_2();
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_2')
        {
            $this->upgradeFrom1_2To1_3();
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_3')
        {
            $this->upgradeFrom1_3To1_4();
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_4')
        {
            $this->upgradeFrom1_4To1_5();
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '1_5')
        {
            $this->upgradeFrom1_5To2_0();
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '2_0')
        {
            $this->upgradeFrom2_0To2_1();
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '2_1')
        {
            $this->upgradeFrom2_1To2_2();
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '2_2')
        {
            $this->upgradeFrom2_2To2_3();
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '2_3')
        {
            $this->upgradeFrom2_3To2_4();
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '2_4')
        {
            $this->upgradeFrom2_4To3_0_beta1();
            $this->upgradeFrom3_0_beta1To3_0();
        }
        elseif($fromVersion == '3_0_beta1')
        {
            $this->upgradeFrom3_0_beta1To3_0();
        }

        $this->deletePatch();
        $this->setting->setSN();
    }

    /**
     * Create the confirm contents.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return string
     */
    public function getConfirm($fromVersion)
    {
        $confirmContent = '';
        if($fromVersion == '0_3beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '0_4beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '0_5beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '0_6beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_0beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_0rc1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_0rc2' || $fromVersion == '1_0' || $fromVersion == '1_0_1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_2')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_3')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_4')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '1_5')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '2_0')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '2_1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '2_2')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '2_3')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '2_4')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }
        elseif($fromVersion == '3_0_beta1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        }

        return str_replace('zt_', $this->config->db->prefix, $confirmContent);
    }

    /**
     * Upgrade from 0.3 to 0.4
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom0_3To0_4()
    {
        $this->execSQL($this->getUpgradeFile('0.3'));
        if(!$this->isError()) $this->setting->updateVersion('0.4 beta');
    }

    /**
     * Upgrade from 0.4 to 0.5
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom0_4To0_5()
    {
        $this->execSQL($this->getUpgradeFile('0.4'));
        if(!$this->isError()) $this->setting->updateVersion('0.5 beta');
    }

    /**
     * Upgrade from 0.5 to 0.6.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom0_5To0_6()
    {
        $this->execSQL($this->getUpgradeFile('0.5'));
        if(!$this->isError()) $this->setting->updateVersion('0.6 beta');
    }

    /**
     * Upgrade from 0.6 to 1.0 beta.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom0_6To1_0_B()
    {
        $this->execSQL($this->getUpgradeFile('0.6'));
        if(!$this->isError()) $this->setting->updateVersion('1.0beta');
    }

    /**
     * Upgrade from 1.0 beta to 1.0 rc1.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_0betaTo1_0rc1()
    {
        $this->execSQL($this->getUpgradeFile('1.0.beta'));
        $this->updateCompany();
        if(!$this->isError()) $this->setting->updateVersion('1.0rc1');
    }

    /**
     * Upgrade from 1.0 rc1 to 1.0 rc2.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_0rc1To1_0rc2()
    {
        $this->execSQL($this->getUpgradeFile('1.0.rc1'));
        if(!$this->isError()) $this->setting->updateVersion('1.0rc2');
    }

    /**
     * Upgrade from 1.0 rc2 to 1.0 stable.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_0rc2To1_0stable()
    {
        $this->setting->updateVersion('1.0');
    }

    /**
     * Upgrade from 1.0 stable to 1.0.1.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_0stableTo1_0_1()
    {
        $this->setting->updateVersion('1.0.1');
    }

    /**
     * Upgrade from 1.0.1 to 1.1.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_0_1To1_1()
    {
        $this->execSQL($this->getUpgradeFile('1.0.1'));
        if(!$this->isError()) $this->setting->updateVersion('1.1');
    }

    /**
     * Upgrade from 1.1 to 1.2.
     *
     * @access public
     * @return void
     */
    public function upgradeFrom1_1To1_2()
    {
        $this->execSQL($this->getUpgradeFile('1.1'));
        if(!$this->isError()) $this->setting->updateVersion('1.2');
    }

    /**
     * Upgrade from 1.2 to 1.3.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_2To1_3()
    {
        $this->execSQL($this->getUpgradeFile('1.2'));
        $this->updateUBB();
        $this->updateNL1_2();
        if(!$this->isError()) $this->setting->updateVersion('1.3');
    }

    /**
     * Upgrade from 1.3 to 1.4.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_3To1_4()
    {
        $this->execSQL($this->getUpgradeFile('1.3'));
        $this->updateNL1_3();
        $this->updateTasks();
        if(!$this->isError()) $this->setting->updateVersion('1.4');
    }

    /**
     * Upgrade from 1.4 to 1.5.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_4To1_5()
    {
        $this->execSQL($this->getUpgradeFile('1.4'));
        if(!$this->isError()) $this->setting->updateVersion('1.5');
    }

    /**
     * Upgrade from 1.5 to 2.0.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom1_5To2_0()
    {
        $this->execSQL($this->getUpgradeFile('1.5'));
        if(!$this->isError()) $this->setting->updateVersion('2.0');
    }

    /**
     * Upgrade from 2.0 to 2.1.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom2_0To2_1()
    {
        $this->execSQL($this->getUpgradeFile('2.0'));
        if(!$this->isError()) $this->setting->updateVersion('2.1');
    }

    /**
     * Upgrade from 2.1 to 2.2.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom2_1To2_2()
    {
        $this->execSQL($this->getUpgradeFile('2.1'));
        if(!$this->isError()) $this->setting->updateVersion('2.2');
    }

    /**
     * Upgrade from 2.2 to 2.3.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom2_2To2_3()
    {
        $this->execSQL($this->getUpgradeFile('2.2'));
        $this->updateCases();
        $this->updateActivatedCountOfBug();
        if(!$this->isError()) $this->setting->updateVersion('2.3');
    }

    /**
     * Upgrade from 2.3 to 2.4.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom2_3To2_4()
    {
        $this->execSQL($this->getUpgradeFile('2.3'));
        if(!$this->isError()) $this->setting->updateVersion('2.4');
    }

    /**
     * Upgrade from 2.4 to 3.0.beta1.
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom2_4To3_0_beta1()
    {
        $this->execSQL($this->getUpgradeFile('2.4'));
        if(!$this->isError()) $this->setting->updateVersion('3.0.beta1');
    }

    /**
     * Upgrade from 3_0_beta1To3_0 
     * 
     * @access public
     * @return void
     */
    public function upgradeFrom3_0_beta1To3_0()
    {
        $this->execSQL($this->getUpgradeFile('3.0.beta1'));
        $this->updateTableAction();
        if(!$this->isError()) $this->setting->updateVersion('3.0');
    }

    /**
     * Update company field.
     *
     * This method is used to update since 1.0 beta. Any new tables added after 1.0 beta should skip.
     *
     * @access public
     * @return void
     */
    public function updateCompany()
    {
        /* Get user defined constants. */
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* Update tables. */
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;

            $table  = $value;
            $result = $this->dbh->query("SHOW TABLES LIKE '$table'");
            if($result->rowCount() > 0)
            {
                $this->dbh->query("UPDATE $table SET company = '{$this->app->company->id}'");
            }
        }
    }

    /**
     * Update ubb code in bug table and user Templates table to html.
     * 
     * @access public
     * @return void
     */
    public function updateUBB()
    {
        $this->app->loadClass('ubb', true);

        $bugs = $this->dao->select('id, steps')->from(TABLE_BUG)->fetchAll();
        $userTemplates = $this->dao->select('id, content')->from(TABLE_USERTPL)->fetchAll();
            
        foreach($bugs as $id => $bug)
        {
            $bug->steps = ubb::parseUBB($bug->steps);
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
        }
        foreach($userTemplates as $template)
        {
            $template->content = ubb::parseUBB($template->content);
            $this->dao->update(TABLE_USERTPL)->data($template)->where('id')->eq($template->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.2 version.
     * 
     * @access public
     * @return void
     */
    public function updateNL1_2()
    {
        $tasks     = $this->dao->select('id, `desc`')->from(TABLE_TASK)->fetchAll();
        $stories   = $this->dao->select('story, version, spec')->from(TABLE_STORYSPEC)->fetchAll();
        $todos     = $this->dao->select('id, `desc`')->from(TABLE_TODO)->fetchAll();
        $testTasks = $this->dao->select('id, `desc`')->from(TABLE_TESTTASK)->fetchAll();

        foreach($tasks as $task)
        {
            $task->desc = nl2br($task->desc);
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();
        }
        foreach($stories as $story)
        {
            $story->spec = nl2br($story->spec);
            $this->dao->update(TABLE_STORYSPEC)->data($story)->where('story')->eq($story->story)->andWhere('version')->eq($story->version)->exec();
        }

        foreach($todos as $todo)
        {
            $todo->desc = nl2br($todo->desc);
            $this->dao->update(TABLE_TODO)->data($todo)->where('id')->eq($todo->id)->exec();
        }

        foreach($testTasks as $testtask)
        {
            $testtask->desc = nl2br($testtask->desc);
            $this->dao->update(TABLE_TESTTASK)->data($testtask)->where('id')->eq($testtask->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.3 version.
     * 
     * @access public
     * @return void
     */
    public function updateNL1_3()
    {
        $products = $this->dao->select('id, `desc`')->from(TABLE_PRODUCT)->fetchAll();
        $plans    = $this->dao->select('id, `desc`')->from(TABLE_PRODUCTPLAN)->fetchAll();
        $releases = $this->dao->select('id, `desc`')->from(TABLE_RELEASE)->fetchAll();
        $projects = $this->dao->select('id, `desc`, goal')->from(TABLE_PROJECT)->fetchAll();
        $builds   = $this->dao->select('id, `desc`')->from(TABLE_BUILD)->fetchAll();

        foreach($products as $product)
        {
            $product->desc = nl2br($product->desc);
            $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($product->id)->exec();
        }

        foreach($plans as $plan)
        {
            $plan->desc = nl2br($plan->desc);
            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->where('id')->eq($plan->id)->exec();
        }

        foreach($releases as $release)
        {
            $release->desc = nl2br($release->desc);
            $this->dao->update(TABLE_RELEASE)->data($release)->where('id')->eq($release->id)->exec();
        }

        foreach($projects as $project)
        {
            $project->desc = nl2br($project->desc);
            $project->goal = nl2br($project->goal);
            $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq($project->id)->exec();
        }

        foreach($builds as $build)
        {
            $build->desc = nl2br($build->desc);
            $this->dao->update(TABLE_BUILD)->data($build)->where('id')->eq($build->id)->exec();
        }
    }

    /**
     * Update task fields.
     * 
     * @access public
     * @return void
     */
    public function updateTasks()
    {
        /* Get all actions of tasks. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('task')
            ->orderBy('id')
            ->fetchAll('id');

        /* Get histories about status field. */
        $histories = $this->dao->select()->from(TABLE_HISTORY)
            ->where('action')->in(array_keys($actions))
            ->andWhere('field')->eq('status')
            ->orderBy('id')
            ->fetchGroup('action');

        $tasks = array();
        foreach($actions as $action)
        {
            if(!isset($tasks[$action->objectID]))
            {
                $tasks[$action->objectID] = new stdclass;
            }
            $task = $tasks[$action->objectID];

            $task->id   = $action->objectID;
            $actionType = strtolower($action->action);

            /* Set the openedBy info. */
            if($actionType == 'opened')
            {
                $task->openedBy   = $action->actor;
                $task->openedDate = $action->date;
            }
            else
            {
                if(!isset($histories[$action->id])) continue;

                $actionHistories = $histories[$action->id];
                foreach($actionHistories as $history)
                {
                    /* Finished by. */
                    if($history->new == 'done')
                    {
                        $task->finishedBy   = $action->actor;
                        $task->finishedDate = $action->date;
                        $action->action     = 'finished';
                    }
                    /* Canceled By. */
                    elseif($history->new == 'cancel')
                    {
                        $task->canceledBy   = $action->actor;
                        $task->canceledDate = $action->date;
                        $action->action     = 'canceled';
                    }
                }

                /* Last edited by .*/
                $task->lastEditedBy   = $action->actor;
                $task->lastEditedDate = $action->date;

                /* Update action type. */
                $this->dao->update(TABLE_ACTION)->set('action')->eq($action->action)->where('id')->eq($action->id)->exec(false);
            }
        }

        /* Update db. */
        foreach($tasks as $task)
        {
            $this->dao->update(TABLE_TASK)->data($task, false)->where('id')->eq($task->id)->exec(false);
        }

        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = finishedDate')->where('status')->eq('done')->exec(false);
        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = canceledDate')->where('status')->eq('cancel')->exec(false);

        /* Update action name. */
    }

    /**
     * Update activated count of Bug. 
     * 
     * @access public
     * @return void
     */
    public function updateActivatedCountOfBug()
    {
        $bugActivatedActions = $this->dao->select('*')->from(TABLE_ACTION)->where('action')->eq('activated')->andWhere('objectType')->eq('bug')->fetchAll();
        if(!empty($bugActivatedActions))
        {
            foreach($bugActivatedActions as $action)
            {
                if(!isset($counts[$action->objectID]))  $counts[$action->objectID] = 0;
                $counts[$action->objectID] ++;
            }
            foreach($counts as $key => $count)
            {
                $this->dao->update(TABLE_BUG)->set('activatedCount')->eq($count)->where('id')->eq($key)->exec();
            }
        }
    }

    /**
     * Update lastRun and lastResult field in zt_case
     * 
     * @access public
     * @return void
     */
    public function updateCases()
    {
        $results = $this->dao->select('`case`, date, caseResult')->from(TABLE_TESTRESULT)->orderBy('id desc')->fetchGroup('case');
        foreach($results as $result)
        {
            $this->dao->update(TABLE_CASE)
                ->set('lastRun')->eq($result[0]->date)
                ->set('lastResult')->eq($result[0]->caseResult)
                ->where('id')->eq($result[0]->case)
                ->exec();
        }
    }

    /**
     * Update the data of action. 
     * 
     * @access public
     * @return void
     */
    public function updateTableAction()
    {
        $projectActions = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('project')->fetchPairs('objectID');
        $taskActions    = $this->dao->select('objectID,project')->from(TABLE_ACTION)->where('objectType')->eq('task')->fetchPairs('objectID');

        foreach($projectActions as $key => $projectID)
        {
            $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product');
            $productList = ',' . join(',', array_keys($products)) . ',';
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('project')->andWhere('objectID')->eq($projectID)->exec();
        }

        foreach($taskActions as $taskID => $projectID)
        {
            $task = $this->dao->select('id,story')->from(TABLE_TASK)->where('id')->eq($taskID)->fetchPairs('id');
            if($task[$taskID] != 0)
            {
                $product     = $this->dao->select('product')->from(TABLE_STORY)->where('id')->eq($task[$taskID])->fetchPairs('product');
                $productList = ',' . join(',', array_keys($product)) . ',';
            }
            else
            {
                $products    = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product');
                $productList = ',' . join(',', array_keys($products)) . ',';
            }
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('task')->andWhere('objectID')->eq($taskID)->andWhere('project')->eq($projectID)->exec();
        }
    }

    /**
     * Delete the patch record.
     * 
     * @access public
     * @return void
     */
    public function deletePatch()
    {
        $this->dao->delete()->from(TABLE_EXTENSION)->where('type')->eq('patch')->exec(false);
        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->in('zentaopatch,patch')->exec(false);
    }

    /**
     * Get the upgrade sql file.
     * 
     * @param  string $version 
     * @access public
     * @return string
     */
    public function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . $this->app->getPathFix() . 'update' . $version . '.sql';
    }

    /**
     * Execute a sql.
     * 
     * @param  string  $sqlFile 
     * @access public
     * @return void
     */
    public function execSQL($sqlFile)
    {
        $mysqlVersion = $this->loadModel('install')->getMysqlVersion();

        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line) 
        {
            $line       = trim($line);
            $sqls[$key] = $line;
            if(strpos($line, '--') !== false or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
            }

            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            try
            {
                $this->dbh->exec($sql);
            }
            catch (PDOException $e) 
            {
                self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /**
     * Judge any error occers.
     * 
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty(self::$errors);
    }

    /**
     * Get errors during the upgrading.
     * 
     * @access public
     * @return array
     */
    public function getError()
    {
        $errors = self::$errors;
        self::$errors = array();
        return $errors;
    }
}
