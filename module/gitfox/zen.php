<?php
declare(strict_types=1);
/**
 * The zen file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfoxZen extends gitfox
{
    /**
     * 生成 GitFox 安装或升级脚本。
     * Build GitFox install or upgrade script.
     *
     * @param  string $action install|upgrade
     * @access public
     * @return string
     */
    public function buildGitFoxScript(string $action): string
    {
        $action = strtolower($action);

        if(strpos(PHP_OS, 'WIN'))
        {
            $os = 'win';
        }
        elseif(PHP_OS === 'Linux')
        {
            $os = 'linux';
        }
        elseif(PHP_OS === 'Darwin')
        {
            $os = 'mac';
        }
        else
        {
            $os = 'linux';
        }

        $uname = php_uname('m');
        $arch  = strtolower($uname);
        if(strpos($arch, 'arm') === 0 || strpos($arch, 'aarch') != false)
        {
            $arch = 'arm';
        }
        elseif(strpos($arch, 'x86') === 0 || strpos($arch, 'i686') != false || strpos($arch, 'amd') != false)
        {
            $arch = 'amd';
        }
        else
        {
            $arch = 'amd';
        }

        $gitfoxDir   = $this->app->getAppRoot() . 'gitfox';
        $type        = $os == 'mac' ? 'linux' : $os;
        $downloadURL = $this->config->gitfox->downloadGitfoxURL[$type][$arch];

        $isUpgrade  = $action == 'upgrade';
        $configKey  = $isUpgrade ? 'upgradeGitfox' : 'installGitfox';
        $scriptName = $isUpgrade ? 'upgradegitfox' : 'installgitfox';
        $command    = sprintf($this->config->gitfox->{$configKey}[$type], $gitfoxDir, $downloadURL);
        $script     = $type == 'linux' ? $this->app->getTmpRoot() . $scriptName . '.sh' : $this->app->getTmpRoot() . $scriptName . '.bat';

        file_put_contents($script, $command);
        if(file_exists($script)) chmod($script, 0755);

        return $script;
    }
}
