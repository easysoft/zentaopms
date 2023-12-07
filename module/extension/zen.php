<?php
declare(strict_types=1);
/**
 * The zen file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
class extensionZen extends extension
{
    /**
     * 安全性校验。
     * Check safe.
     *
     * @access public
     * @return void
     */
    protected function checkSafe()
    {
        /* 判断是否要跳转到安全校验页面。 */
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile) die($this->fetch('extension', 'safe', "statusFile=$statusFile"));
    }

    /**
     * 插件安装前的合规校验。
     * Check before installation.
     *
     * @param  string    $extension
     * @param  string    $ignoreCompatible
     * @param  string    $ignoreLink
     * @param  string    $overrideFile
     * @param  string    $overrideLink
     * @param  string    $installType
     * @access protected
     * @return bool
     */
    protected function checkExtension(string $extension, string $ignoreCompatible, string $ignoreLink, string $overrideFile, string $overrideLink, string $installType): bool
    {
        /* 安全性校验。 */
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
            return false;
        }

        /* Check the package file exists or not. */
        $packageFile = $this->extension->getPackageFile($extension);
        if(!file_exists($packageFile))
        {
            $this->view->error = sprintf($this->lang->extension->errorPackageNotFound, $packageFile);
            return false;
        }

        /* Checking the extension paths. */
        $return = $this->checkExtensionPaths($extension);
        if($this->session->dirs2Created == false) $this->session->set('dirs2Created', $return->dirs2Created, 'admin');    // Save the dirs to be created.
        if($return->result != 'ok')
        {
            $this->view->error = $return->errors;
            return false;
        }

        /* Extract the package. */
        $return = $this->extension->extractPackage($extension);
        if($return->result != 'ok')
        {
            $this->view->error = sprintf($this->lang->extension->errorExtracted, $packageFile, $return->error);
            return false;
        }

        /* Get condition. e.g. zentao|depends|conflicts. */
        $condition     = $this->extension->getCondition($extension);
        $installedExts = $this->extension->getLocalExtensions('installed');

        if(!$this->checkCompatible($extension, $condition, $ignoreCompatible, $ignoreLink, $installType)) return false;
        if(!$this->checkConflicts($condition, $installedExts))                                            return false;
        if(!$this->checkDepends($condition, $installedExts))                                              return false;
        if(!$this->checkFile($extension, $overrideFile, $overrideLink))                                   return false;

        return true;
    }

    /**
     * 插件兼容性检查。
     * Extension compatibility check.
     *
     * @param  string  $extension
     * @param  object  $condition
     * @param  string  $ignoreCompatible
     * @param  string  $ignoreLink
     * @param  string  $installType
     * @access private
     * @return bool
     */
    private function checkCompatible(string $extension, object $condition, string $ignoreCompatible, string $ignoreLink, string $installType): bool
    {
        /* 不兼容版本检查。 */
        /* Check version incompatible */
        $incompatible = $condition->zentao['incompatible'];
        if($this->extension->checkVersion($incompatible))
        {
            $this->view->error = $this->lang->extension->errorIncompatible;
            return false;
        }

        /* 兼容版本检查。 */
        /* Check version compatible. */
        $zentaoCompatible = $condition->zentao['compatible'];
        if(!$this->extension->checkVersion($zentaoCompatible) && $ignoreCompatible == 'no')
        {
            $this->view->error = sprintf($this->lang->extension->errorCheckIncompatible, $installType, $ignoreLink, $installType, inlink('obtain'));
            return false;
        }

        return true;
    }

    /**
     * 插件与插件之间的冲突检查。
     * Check conflicts.
     *
     * @param  object  $condition
     * @param  array   $installedExts
     * @access private
     * @return bool
     */
    private function checkConflicts(object $condition, array $installedExts): bool
    {
        $conflicts = $condition->conflicts;
        if($conflicts)
        {
            $conflictsExt = '';
            foreach($conflicts as $code => $limit)
            {
                if(isset($installedExts[$code]))
                {
                    if($this->extension->compare4Limit($installedExts[$code]->version, $limit)) $conflictsExt .= $installedExts[$code]->name . " ";
                }
            }

            if($conflictsExt)
            {
                $this->view->error = sprintf($this->lang->extension->errorConflicts, $conflictsExt);
                return false;
            }
        }
        return true;
    }

    /**
     * 相关依赖插件检查。
     * Check depends.
     *
     * @param  object  $condition
     * @param  array   $installedExts
     * @access private
     * @return bool
     */
    private function checkDepends(object $condition, array $installedExts): bool
    {
        $depends = $condition->depends;
        if($depends)
        {
            $dependsExt = '';
            foreach($depends as $code => $limit)
            {
                $noDepends = false;
                if(isset($installedExts[$code]))
                {
                    if($this->extension->compare4Limit($installedExts[$code]->version, $limit, 'noBetween')) $noDepends = true;
                }
                else
                {
                    $noDepends = true;
                }

                $extVersion = '';
                if($limit != 'all')
                {
                    $extVersion .= '(';
                    if(!empty($limit['min'])) $extVersion .= '>=v' . $limit['min'];
                    if(!empty($limit['max'])) $extVersion .= ' <=v' . $limit['max'];
                    $extVersion .=')';
                }
                if($noDepends) $dependsExt .= $code . $extVersion . ' ' . html::a(inlink('obtain', 'type=bycode&param=' . helper::safe64Encode($code)), $this->lang->extension->installExt, '_blank') . '<br />';
            }

            if($noDepends)
            {
                $this->view->error = sprintf($this->lang->extension->errorDepends, $dependsExt);
                return false;
            }
        }
        return true;
    }

    /**
     * 插件文件和禅道已有文件的冲突检查。
     * Check files in the package conflicts with exists files or not.
     *
     * @param  string  $extension
     * @param  string  $overrideFile
     * @param  string  $overrideLink
     * @access private
     * @return bool
     */
    private function checkFile(string $extension, string $overrideFile, string $overrideLink): bool
    {
        if($overrideFile == 'no')
        {
            $return = $this->checkFileConflict($extension);
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorFileConflicted, $return->error, $overrideLink, inlink('obtain'));
                return false;
            }
        }

        return true;
    }

    /**
     * 执行插件安装程序。
     * Install extension.
     *
     * @param  string    $extension
     * @param  string    $type
     * @param  string    $upgrade
     * @access protected
     * @return void
     */
    protected function installExtension(string $extension, string $type, string $upgrade): bool
    {
        /* The preInstall hook file. */
        $hook = $upgrade == 'yes' ? 'preupgrade' : 'preinstall';
        if($preHookFile = $this->getHookFile($extension, $hook)) include $preHookFile;

        /* Save to database. */
        $this->extension->saveExtension($extension, $type);

        /* Copy files to target directory. */
        $this->view->files = $this->extension->copyPackageFiles($extension);

        /* Judge need execute db install or not. */
        $data = new stdclass();
        $data->status = 'installed';
        $data->dirs   = $this->session->dirs2Created;
        $data->files  = $this->view->files;
        $data->installedTime = helper::now();
        $this->session->set('dirs2Created', array(), 'admin');   // clean the session.

        /* Execute the install.sql. */
        if($upgrade == 'no' && $this->extension->needExecuteDB($extension, 'install'))
        {
            $return = $this->extension->executeDB($extension, 'install');
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorInstallDB, $return->error);
                return false;
            }
        }

        /* Update status, dirs, files and installed time. */
        $this->extension->updateExtension($extension, $data);
        $this->view->downloadedPackage = false;

        /* The postInstall hook file. */
        $hook = $upgrade == 'yes' ? 'postupgrade' : 'postinstall';
        if($postHookFile = $this->getHookFile($extension, $hook)) include $postHookFile;

        return true;
    }

    /**
     * 根据插件代号获取指定的钩子文件地址。
     * Get hook file for install or uninstall.
     *
     * @param  string       $extension
     * @param  string       $hook      preinstall|postinstall|preuninstall|postuninstall
     * @access public
     * @return string|false
     */
    public function getHookFile(string $extension, string $hook): string|false
    {
        $hookFile = $this->extension->pkgRoot . "$extension/hook/$hook.php";
        if(file_exists($hookFile)) return $hookFile;
        return false;
    }

    /**
     * 检查安装前的文件夹权限。
     * Check extension files.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function checkExtensionPaths(string $extension): object
    {
        $checkResult = new stdclass();
        $checkResult->result        = 'ok';
        $checkResult->errors        = '';
        $checkResult->mkdirCommands = '';
        $checkResult->chmodCommands = '';
        $checkResult->dirs2Created  = array();

        /* 如果extension目录没有创建pkg文件夹并且创建pkg文件夹失败。 */
        if(!is_dir($this->extension->pkgRoot) && !mkdir($this->extension->pkgRoot))
        {
            $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotExists, $this->extension->pkgRoot) . '<br />';
            $checkResult->mkdirCommands .= "sudo mkdir -p {$this->extension->pkgRoot}<br />";
            $checkResult->chmodCommands .= "sudo chmod -R 777 {$this->pkgRoot}<br />";
        }

        /* 如果extension目录有pkg文件夹但是pkg文件夹不可写。 */
        if(is_dir($this->extension->pkgRoot) && !is_writable($this->extension->pkgRoot))
        {
            $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotWritable, $this->extension->pkgRoot) . '<br />';
            $checkResult->chmodCommands .= "sudo chmod -R 777 {$this->extension->pkgRoot}<br />";
        }

        /* 检查插件目录对应的禅道目录权限。 */
        $checkResult = $this->checkExtensionPath($extension, $checkResult);

        if($checkResult->errors) $checkResult->result = 'fail';

        $checkResult->mkdirCommands = empty($checkResult->mkdirCommands) ? '' : '<code>' . str_replace('/', DIRECTORY_SEPARATOR, $checkResult->mkdirCommands) . '</code>';
        $checkResult->errors       .= $this->lang->extension->executeCommands . $checkResult->mkdirCommands;
        if(PHP_OS == 'Linux') $checkResult->errors .= empty($checkResult->chmodCommands) ? '' : '<code>' . $checkResult->chmodCommands . '</code>';

        return $checkResult;
    }

    /**
     * 检查安装插件时对应的禅道目录权限。
     * Check extension path read-write permission.
     *
     * @param  string  $extension
     * @param  object  $checkResult
     * @access private
     * @return object
     */
    private function checkExtensionPath(string $extension, object $checkResult): object
    {
        $appRoot = $this->app->getAppRoot();
        $paths   = $this->extension->getPathsFromPackage($extension);
        foreach($paths as $path)
        {
            if($path == 'db' || $path == 'doc' || $path == 'hook') continue;

            $path = rtrim($appRoot . $path, '/');
            if(is_dir($path))
            {
                /* 检查插件包里的代码文件夹对应禅道目录是否可写。 */
                if(!is_writable($path))
                {
                    $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotWritable, $path) . '<br />';
                    $checkResult->chmodCommands .= "sudo chmod -R 777 $path<br />";
                }
            }
            else
            {
                /* 检查插件包里的代码文件的父目录对应禅道目录是否可写。 */
                $parentDir = mb_substr($path, 0, strripos($path, '/'));
                if(is_dir($parentDir) && !is_writable($parentDir))
                {
                    $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotWritable, $path) . '<br />';
                    $checkResult->chmodCommands .= "sudo chmod -R 777 $path<br />";
                    $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotExists, $path) . '<br />';
                    $checkResult->mkdirCommands .= "sudo mkdir -p $path<br />";
                }
                else if(!mkdir($path, 0777, true))
                {
                    /* 如果目录不存在并且创建目录失败。 */
                    $checkResult->errors        .= sprintf($this->lang->extension->errorTargetPathNotExists, $path) . '<br />';
                    $checkResult->mkdirCommands .= "sudo mkdir -p $path<br />";
                }
                if(file_exists($path) && realpath($path) != $this->extension->pkgRoot) $checkResult->dirs2Created[] = $path;
            }
        }

        return $checkResult;
    }

    /**
     * 检查插件包的目录结构是否禅道目录结构冲突。
     * Check files in the package conflicts with exists files or not.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function checkFileConflict(string $extension): object
    {
        $return = new stdclass();
        $return->result = 'ok';
        $return->error  = '';

        $appRoot        = $this->app->getAppRoot();
        $extensionFiles = $this->extension->getFilesFromPackage($extension);
        foreach($extensionFiles as $extensionFile)
        {
            $compareFile = $appRoot . str_replace($this->extension->pkgRoot . $extension . DS, '', $extensionFile);
            if(!file_exists($compareFile)) continue;

            if(md5_file($extensionFile) != md5_file($compareFile)) $return->error .= $compareFile . '<br />';
        }

        if($return->error != '') $return->result = 'fail';
        return $return;
    }
}
