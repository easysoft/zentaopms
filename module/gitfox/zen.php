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
     * @param  string $action  install|upgrade
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return string
     */
    public function buildGitFoxScript(string $action, string $os = PHP_OS, string $machine = ''): string
    {
        $action = strtolower($action);

        /* Windows 使用 win 安装包，Linux、macOS 等系统使用 linux 安装包。 */
        $type = strtoupper(substr($os, 0, 3)) === 'WIN' ? 'win' : 'linux';

        /* arm64、aarch64、armv7l 等 ARM 架构使用 arm 安装包，x86_64、amd64、i686 等架构使用 amd 安装包。 */
        $machine = strtolower($machine === '' ? php_uname('m') : $machine);
        $arch    = strpos($machine, 'arm') !== false || strpos($machine, 'aarch') !== false ? 'arm' : 'amd';

        $gitfoxDir   = $this->app->getAppRoot() . 'gitfox';
        $downloadURL = $this->config->gitfox->downloadGitfoxURL[$type][$arch];

        $isUpgrade  = $action == 'upgrade';
        $configKey  = $isUpgrade ? 'upgradeGitfox' : 'installGitfox';
        $scriptName = $isUpgrade ? 'upgradegitfox' : 'installgitfox';
        $command    = str_replace(array('{{INSTALL_DIR}}', '{{GITFOX_URL}}'), array($gitfoxDir, $downloadURL), $this->config->gitfox->{$configKey}[$type]);
        $script     = $type == 'linux' ? $this->app->getTmpRoot() . $scriptName . '.sh' : $this->app->getTmpRoot() . $scriptName . '.bat';

        file_put_contents($script, $command);
        if(file_exists($script)) chmod($script, 0755);

        return $script;
    }
}
