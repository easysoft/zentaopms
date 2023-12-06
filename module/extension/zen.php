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
        $return = $this->extension->checkExtensionPaths($extension);
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
            $return = $this->extension->checkFile($extension);
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
        if($preHookFile = $this->extension->getHookFile($extension, $hook)) include $preHookFile;

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
        if($postHookFile = $this->extension->getHookFile($extension, $hook)) include $postHookFile;

        return true;
    }
}
