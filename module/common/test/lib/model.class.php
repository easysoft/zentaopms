<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class commonModelTest extends baseTest
{
    protected $moduleName = 'common';
    protected $className  = 'model';

    /**
     * Test apiError method.
     *
     * @param  object|null $result
     * @access public
     * @return object
     */
    public function apiErrorTest($result = null)
    {
        $result = $this->invokeArgs('apiError', [$result]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formConfig method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function formConfigTest(string $module, string $method, int $objectID = 0)
    {
        $result = commonModel::formConfig($module, $method, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test canOperateEffort method.
     *
     * @param  object $effort
     * @access public
     * @return mixed
     */
    public function canOperateEffortTest($effort = null)
    {
        $result = $this->instance->canOperateEffort($effort);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkIP method.
     *
     * @param  string $ipWhiteList
     * @access public
     * @return mixed
     */
    public function checkIPTest($ipWhiteList = '')
    {
        $result = $this->instance->checkIP($ipWhiteList);
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test checkPrivByObject method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function checkPrivByObjectTest(string $objectType, int $objectID): bool
    {
        $result = $this->instance->checkPrivByObject($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 检查详情页操作按钮的权限。
     * Check the privilege of the operate action.
     *
     * @param  string     $moduleName
     * @param  object     $data
     * @param  object     $menu
     * @access protected
     * @return array|bool
     */
    public function checkPrivForOperateActionTest(string $moduleName, string $action, array $actionData)
    {
        $object = $this->instance->dao->select('*')->from($this->instance->config->objectTables[$moduleName])->where('id')->eq(1)->fetch();
        $result = $this->instance->checkPrivForOperateAction($actionData, $action, $moduleName, $object, 'mainActions');
        return is_array($result) ? !empty($result['url']) : $result;
    }

    /**
     * Test createMenuLink method.
     *
     * @param  object $menuItem
     * @access public
     * @return mixed
     */
    public function createMenuLinkTest($menuItem)
    {
        $result = $this->instance->createMenuLink($menuItem);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDotStyle method.
     *
     * @param  bool $showCount
     * @param  int  $unreadCount
     * @access public
     * @return array
     */
    public function getDotStyleTest(bool $showCount, int $unreadCount): array
    {
        $result = $this->invokeArgs('getDotStyle', [$showCount, $unreadCount]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getMainNavList method.
     *
     * @param  string $moduleName
     * @param  bool   $useDefault
     * @access public
     * @return mixed
     */
    public function getMainNavListTest($moduleName, $useDefault = false)
    {
        $result = $this->invokeArgs('getMainNavList', [$moduleName, $useDefault]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isOpenMethod method.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return bool
     */
    public function isOpenMethodTest($module, $method)
    {
        $result = $this->invokeArgs('isOpenMethod', [$module, $method]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isTutorialMode method.
     *
     * @access public
     * @return mixed
     */
    public function isTutorialModeTest()
    {
        $result = $this->invokeArgs('isTutorialMode');
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test setUser method.
     *
     * @param  string $mode 测试模式：'guest'测试访客，'session'测试已登录用户
     * @access public
     * @return mixed
     */
    public function setUserTest($mode = '')
    {
        // 如果指定测试guest模式，先清除session
        if($mode === 'guest') {
            if(isset($_SESSION['user'])) unset($_SESSION['user']);
            if(isset($this->instance->app->user)) unset($app->user);
        }

        $this->invokeArgs('setUser');
        if(dao::isError()) return dao::getError();
        return $this->instance->app->user;
    }

    /**
     * Test strEndsWith method.
     *
     * @param  string $haystack
     * @param  string $needle
     * @access public
     * @return bool
     */
    public function strEndsWithTest($haystack, $needle)
    {
        $result = $this->invokeArgs('strEndsWith', [$haystack, $needle]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printDuration method.
     *
     * @param  int    $seconds
     * @param  string $format
     * @access public
     * @return string
     */
    public function printDurationTest(int $seconds, string $format = 'y-m-d-h-i-s'): string
    {
        $result = commonModel::printDuration($seconds, $format);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printPreAndNext method.
     *
     * @param  mixed  $preAndNext
     * @param  string $linkTemplate
     * @param  bool   $onlyBody
     * @access public
     * @return mixed
     */
    public function printPreAndNextTest($preAndNext = '', string $linkTemplate = '', bool $onlyBody = false)
    {
        global $app, $lang;

        if(!isset($lang)) $lang = new stdclass();

        $hasOnlyBody          = array_key_exists('onlybody', $_GET);
        $originalOnlyBody     = $_GET['onlybody'] ?? '';
        $hasPreShortcutKey    = isset($lang->preShortcutKey);
        $originalPreShortcut  = $lang->preShortcutKey ?? '';
        $hasNextShortcutKey   = isset($lang->nextShortcutKey);
        $originalNextShortcut = $lang->nextShortcutKey ?? '';
        $originalModuleName   = $app->moduleName ?? null;
        $originalMethodName   = $app->methodName ?? null;
        $originalRawModule    = $app->rawModule;
        $originalRawMethod    = $app->rawMethod;
        $originalTab          = $app->tab;
        $obLevel              = ob_get_level();
        $result               = null;
        $output               = '';

        // 设置onlybody模式
        if($onlyBody)
        {
            $_GET['onlybody'] = 'yes';
        }
        else
        {
            unset($_GET['onlybody']);
        }

        // 设置语言
        if(!isset($lang->preShortcutKey)) $lang->preShortcutKey = '(←)';
        if(!isset($lang->nextShortcutKey)) $lang->nextShortcutKey = '(→)';
        $app->setModuleName('task');
        $app->setMethodName('view');
        $app->rawModule = 'task';
        $app->rawMethod = 'view';
        $app->tab       = 'my';

        try
        {
            ob_start();
            $result = commonModel::printPreAndNext($preAndNext, $linkTemplate);
            $output = (string)ob_get_clean();
        }
        finally
        {
            while(ob_get_level() > $obLevel) $output .= (string)ob_get_clean();

            if($hasOnlyBody)
            {
                $_GET['onlybody'] = $originalOnlyBody;
            }
            else
            {
                unset($_GET['onlybody']);
            }

            if($hasPreShortcutKey)
            {
                $lang->preShortcutKey = $originalPreShortcut;
            }
            else
            {
                unset($lang->preShortcutKey);
            }

            if($hasNextShortcutKey)
            {
                $lang->nextShortcutKey = $originalNextShortcut;
            }
            else
            {
                unset($lang->nextShortcutKey);
            }

            if(is_string($originalModuleName) && $originalModuleName !== '')
            {
                $app->setModuleName($originalModuleName);
            }
            else
            {
                $app->moduleName = $originalModuleName;
            }

            if(is_string($originalMethodName) && $originalMethodName !== '')
            {
                $app->setMethodName($originalMethodName);
            }
            else
            {
                $app->methodName = $originalMethodName;
            }

            $app->rawModule = $originalRawModule;
            $app->rawMethod = $originalRawMethod;
            $app->tab       = $originalTab;
        }

        if(dao::isError()) return dao::getError();

        // 如果是onlybody模式，返回false
        if($onlyBody) return $result === false ? '0' : '1';

        // 否则返回输出内容
        return $output;
    }

    /**
     * Test processMarkdown method.
     *
     * @param  string $markdown
     * @access public
     * @return string|bool
     */
    public function processMarkdownTest(string $markdown)
    {
        $result = $this->invokeArgs('processMarkdown', [$markdown]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkSafeFile method.
     *
     * @param string $scenario 测试场景
     * @access public
     * @return mixed
     */
    public function checkSafeFileTest($scenario = '')
    {
        // 备份原始配置和状态
        $originalInContainer = $this->instance->config->inContainer;
        $originalModuleName  = $this->instance->app->getModuleName();
        $originalUpgrading   = $this->instance->app->upgrading ?? false;
        $originalSafeFileEnv = getenv('ZT_CHECK_SAFE_FILE');

        try {
            // 根据场景设置不同的测试环境
            switch($scenario) {
                case 'inContainer':
                    $this->instance->config->inContainer = true;
                    break;

                case 'validSafeFile':
                    $this->instance->config->inContainer = false;
                    // 设置环境变量模拟有效的安全文件状态
                    putenv('ZT_CHECK_SAFE_FILE=true');
                    break;

                case 'upgradeModule':
                    $this->instance->config->inContainer = false;
                    if(isset($_SESSION)) $_SESSION['upgrading'] = true;
                    $this->instance->app->setModuleName('upgrade');
                    break;

                case 'noSafeFile':
                case 'expiredSafeFile':
                default:
                    $this->instance->config->inContainer = false;
                    // 确保没有有效的安全文件
                    putenv('ZT_CHECK_SAFE_FILE=false');
                    if(isset($_SESSION)) $_SESSION['upgrading'] = false;
                    break;
            }

            $result = $this->invokeArgs('checkSafeFile');
            if(dao::isError()) return dao::getError();
            return $result;
        } finally {
            // 恢复原始配置和状态
            $this->instance->config->inContainer = $originalInContainer;
            if(isset($_SESSION)) $_SESSION['upgrading'] = $originalUpgrading;
            $this->instance->app->setModuleName($originalModuleName);
            if($originalSafeFileEnv !== false) {
                putenv("ZT_CHECK_SAFE_FILE=$originalSafeFileEnv");
            } else {
                putenv('ZT_CHECK_SAFE_FILE');
            }
        }
    }

    /**
     * Test getWorkspaceInfo method.
     *
     * @access public
     * @return array
     */
    public function getWorkspaceInfoTest()
    {
        $result = commonModel::getWorkspaceInfo();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test apiDelete method.
     *
     * @access public
     * @return mixed
     */
    public function apiDeleteTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('apiDelete', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test apiGet method.
     *
     * @access public
     * @return mixed
     */
    public function apiGetTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('apiGet', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test apiPost method.
     *
     * @access public
     * @return mixed
     */
    public function apiPostTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('apiPost', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test apiPut method.
     *
     * @access public
     * @return mixed
     */
    public function apiPutTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('apiPut', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test buildActionItem method.
     *
     * @access public
     * @return mixed
     */
    public function buildActionItemTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildActionItem', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test buildIconButton method.
     *
     * @access public
     * @return mixed
     */
    public function buildIconButtonTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildIconButton', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test buildMoreButton method.
     *
     * @access public
     * @return mixed
     */
    public function buildMoreButtonTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildMoreButton', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test buildOperateMenu method.
     *
     * @access public
     * @return mixed
     */
    public function buildOperateMenuTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildOperateMenu', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test checkEntry method.
     *
     * @access public
     * @return mixed
     */
    public function checkEntryTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkEntry', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test checkNotCN method.
     *
     * @access public
     * @return mixed
     */
    public function checkNotCNTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkNotCN', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test getActiveMainMenu method.
     *
     * @access public
     * @return mixed
     */
    public function getActiveMainMenuTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getActiveMainMenu', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test getHasPrivLink method.
     *
     * @access public
     * @return mixed
     */
    public function getHasPrivLinkTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getHasPrivLink', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test getSysURL method.
     *
     * @access public
     * @return mixed
     */
    public function getSysURLTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getSysURL', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test http method.
     *
     * @access public
     * @return mixed
     */
    public function httpTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('http', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test initAuthorize method.
     *
     * @access public
     * @return mixed
     */
    public function initAuthorizeTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('initAuthorize', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printAboutBar method.
     *
     * @access public
     * @return mixed
     */
    public function printAboutBarTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printAboutBar', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printBack method.
     *
     * @access public
     * @return mixed
     */
    public function printBackTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printBack', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printClientLink method.
     *
     * @access public
     * @return mixed
     */
    public function printClientLinkTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printClientLink', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printCreateList method.
     *
     * @access public
     * @return mixed
     */
    public function printCreateListTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printCreateList', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printIcon method.
     *
     * @access public
     * @return mixed
     */
    public function printIconTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printIcon', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printLink method.
     *
     * @access public
     * @return mixed
     */
    public function printLinkTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printLink', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printOrderLink method.
     *
     * @access public
     * @return mixed
     */
    public function printOrderLinkTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printOrderLink', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test printUserBar method.
     *
     * @access public
     * @return mixed
     */
    public function printUserBarTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('printUserBar', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test replaceMenuLang method.
     *
     * @access public
     * @return mixed
     */
    public function replaceMenuLangTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('replaceMenuLang', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test sendHeader method.
     *
     * @access public
     * @return mixed
     */
    public function sendHeaderTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('sendHeader', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test setMainMenu method.
     *
     * @access public
     * @return mixed
     */
    public function setMainMenuTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('setMainMenu', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test setMenuVars method.
     *
     * @access public
     * @return mixed
     */
    public function setMenuVarsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('setMenuVars', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test setMenuVarsEx method.
     *
     * @access public
     * @return mixed
     */
    public function setMenuVarsExTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('setMenuVarsEx', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test sortFeatureMenu method.
     *
     * @access public
     * @return mixed
     */
    public function sortFeatureMenuTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('sortFeatureMenu', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }

}
