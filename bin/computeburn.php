#!/usr/bin/env php
<?php
error_reporting(E_ERROR);
$pmsRoot = dirname(dirname(__FILE__));
include $pmsRoot . '/config/my.php';
include $pmsRoot . '/lib/api/api.class.php';

$domain = $config->default->domain;
unset($config);

$config->zentao->root     = "'http://' . $domain . '/'";    // 禅道访问的完整路径，包括后面的斜线。比如http://pms.zentao.net/
$config->zentao->account  = "";    // 可以访问禅道的帐号，需要有超级model调用接口的访问权限。
$config->zentao->password = "";    // 密码。

if($config->zentao->account == '' and $config->zentao->password == '') die("Must set account and password in " . __FILE__ . ".\n");

class computeburn
{
    public $config;    // the config var.
    public $zentao;    // the zentao client.
    
    public function __construct($config)
    {
        $this->initConfig($config);
        $this->initZenTao();
    }

    /* run. */
    public function run()
    {
        $result = $this->zentao->fetchModel('project', 'computeburn');
        if(empty($result)) die("Nothing to compute.");
        foreach($result as $burns)
        {
            echo $burns->project  . "\t";
            echo $burns->projectName . "\t";
            echo $burns->date . "\t";
            echo $burns->left . "\n";
        }
    }
    
    /* Init the config. */
    private function initConfig($config)
    {
        $this->config = $config;
    }

    /* Init the client of zentao api. */
    private function initZenTao()
    {
        $this->zentao = new ztclient($this->config->zentao->root, $this->config->zentao->account, $this->config->zentao->password);
    }
}

$computeburn = new computeburn($config);
$computeburn->run();
?>
