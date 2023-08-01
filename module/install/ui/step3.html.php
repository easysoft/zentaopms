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
$configContent = <<<EOT
<?php
\$config->installed       = true;
\$config->debug           = false;
\$config->requestType     = '$requestType';
\$config->timezone        = '$timezone';
\$config->db->driver      = '$dbDriver';
\$config->db->host        = '$dbHost';
\$config->db->port        = '$dbPort';
\$config->db->name        = '$dbName';
\$config->db->user        = '$dbUser';
\$config->db->encoding    = '$dbEncoding';
\$config->db->password    = '$dbPassword';
\$config->db->prefix      = '$dbPrefix';
\$config->webRoot         = getWebRoot();
\$config->default->lang   = '$defaultLang';
EOT;
if($customSession) $configContent .= "\n\$config->customSession = true;";

$configRoot   = $this->app->getConfigRoot();
$myConfigFile = $configRoot . 'my.php';
$saveTip      = '';
if(is_writable($configRoot))
{
    if(@file_put_contents($myConfigFile, $configContent))
    {
        $saveTip = sprintf($lang->install->saved2File, $myConfigFile);
    }
    else
    {
        $saveTip = sprintf($lang->install->save2File, $myConfigFile);
    }
}
else
{
    $saveTip = sprintf($lang->install->save2File, $myConfigFile);
}

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
                html($configContent),
            ),
            cell
            (
                setClass('text-center p-2'),
                html($saveTip),
            ),
            cell
            (
                setClass('text-center'),
                btn
                (
                    setClass('px-6'),
                    set::url(inlink('step4')),
                    set::type('primary'),
                    $lang->install->next,
                ),
            ),
        ),
    ),
);

render('pagebase');
