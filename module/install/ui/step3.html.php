<?php
declare(strict_types=1);
/**
 * The step3 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

if(empty($myConfig))
{
    h::js("zui.Modal.alert({size: '480', message: '{$lang->install->errorNotInitConfig}'}).then((res) => {openUrl('" . inlink('step2') . "')});");
    render('pagebase');
    return;
}

$configContent = <<<EOT
<?php
\$config->installed       = true;
\$config->debug           = false;
\$config->requestType     = '$config->requestType';
\$config->timezone        = '$config->timezone';
\$config->db->driver      = '{$myConfig['dbDriver']}';
\$config->db->host        = '{$myConfig['dbHost']}';
\$config->db->port        = '{$myConfig['dbPort']}';
\$config->db->name        = '{$myConfig['dbName']}';
\$config->db->user        = '{$myConfig['dbUser']}';
\$config->db->encoding    = '{$myConfig['dbEncoding']}';
\$config->db->password    = '{$myConfig['dbPassword']}';
\$config->db->prefix      = '{$myConfig['dbPrefix']}';
\$config->webRoot         = getWebRoot();
\$config->default->lang   = '{$myConfig['defaultLang']}';
EOT;
if($customSession) $configContent .= "\n\$config->customSession = true;";

$configRoot   = $this->app->getConfigRoot();
$myConfigFile = $configRoot . 'my.php';
$saveTip      = sprintf($lang->install->save2File, $myConfigFile);
if(is_writable($configRoot) && @file_put_contents($myConfigFile, $configContent)) $saveTip = sprintf($lang->install->saved2File, $myConfigFile);

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        setClass('px-1 mt-2'),
        panel
        (
            set::title($lang->install->saveConfig),
            set::titleClass('pt-3'),
            textarea
            (
                set::name('config'),
                set::rows(15),
                html($configContent)
            ),
            cell
            (
                setClass('text-center p-2'),
                html($saveTip)
            ),
            cell
            (
                setClass('text-center'),
                form
                (
                    set::actions(array()),
                    input
                    (
                        set::name('hidden'),
                        set::type('hidden')
                    ),
                    btn
                    (
                        setClass('px-6'),
                        set::type('primary'),
                        set::btnType('submit'),
                        $lang->install->next
                    )
                )
            )
        )
    )
);

render('pagebase');
