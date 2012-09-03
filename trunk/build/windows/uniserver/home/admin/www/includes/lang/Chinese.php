<?php
/*
####################################################################
# Name: The Uniform Server Language System Array 1.1
# Developed By: The Uniform Server Development Team
# Modified Last By: Olajide Olaolorun (empirex) 
# Web: http://www.uniformserver.com
####################################################################
*/

# Beta Feature, Currently For Debugging Only
#require_once 'array.php';

$US = array(
    	'title' => 'Uniform Server',
	'apanel' => '管理面板',
	'dev-team' => 'The Uniform Server Development Team',

	//--------------------------------------------------------------------------------------------
	// Source Code
	//--------------------------------------------------------------------------------------------

	'code-show' => '查看源代码',
	'code-source' => '查看源代码',
	'code-back' => '关闭查看',
	
	//--------------------------------------------------------------------------------------------
	// Navigation
	//--------------------------------------------------------------------------------------------
	
	// Basic
	'nav-home' => '主页',
	'nav-web' => 'Uniform Server Website',
	'nav-secure' => '服务器安全性',
	'nav-phpinfo' => 'PHP信息',
	'nav-cgienv' => 'Perl环境',
	'nav-status' => 'Apache运行状态',
	'nav-info' => 'Apache信息',
	'nav-update' => '检查更新',
	// Server Control
	'nav-start' => '服务器控制面板',
	// Server Control - Service
	'nav-uservers' => '卸载服务',
	'nav-rapaches' => '重新启动Apache服务',
	'nav-rmysqls' => '重新启动MySQL服务',
	// Server Control - Standard Program
	'nav-sserver' => '关闭所有服务',
	'nav-rmysql' => '运行MySQL',
	'nav-smysql' => '关闭MySQL',
	// 配置s
	'nav-config' => '配置',
	'nav-aconfig' => 'Apache 配置',
	'nav-pconfig' => 'PHP 配置',
	'nav-vhost' => '管理虚拟机',
	'nav-apsetup' => '管理面板配置',
	'nav-psetup' => 'Private Server 配置',
	'nav-sslpsetup' => 'Private Secure Server Config',
	'nav-mqsetup' => 'MySQL Server 配置',

	// Tools Navigation
	'nav-tools' => '工具',
	'nav-pma' => 'phpMyAdmin',
	'nav-elog' => '错误日志查看器',
	'nav-u2w' => 'windows到unix转换器',
	'nav-smig' => 'Server Migration',
	'nav-key' => '服务器私钥和证书生成',
	'nav-mysqlrestore' => '重置MySQL密码',

	// Plugins Navigation
	'nav-plugins' => '插件管理',
	'nav-pear' => '安装Pear',
	'nav-eaccelerator' => 'eAccelerator控制面板',
	// Misc Navigation
	'nav-misc' => '其他杂项',
	'nav-sup' => '在线支持',
	// Documentation
	'nav-docs1' => '文档',
	'nav-sdoc' => '本机文档',
	'nav-docs2' => '在线文档',
	'nav-udoc' => '用户指南',
	'nav-wiki' => 'WIKI',
	'nav-phdoc' => 'PHP文档',
	'nav-mydoc' => 'MySQL文档',
	'nav-pedoc' => 'Perl文档',
	// Languages
	'nav-langs' => 'Languages',

	//--------------------------------------------------------------------------------------------
	// Home
	//--------------------------------------------------------------------------------------------
	
	'main-head' => '管理面板'. $us_apanel_version .'',
	'main-text' => '
    欢迎使用 Uniform Server '. $us_version .'!. <br  />
    您现在访问的是管理面板，通过它你可以控制你的Apache、PHP、MySQL服务。虽然我们一直持续的为它增加新的功能，改进，并修复Bug，但它是稳定并且功能完整的，使用起来也非常的简单友好。
	<br />
    如果发现了Bug，请反馈给我们：<a href="http://forum.uniformserver.com/" target="_forum">forum</a>.
 	<br />
	<br />
	鸣谢： 
 	<br />
	<a href="http://www.uniformserver.com/" target="_home">Uniform Server开发团队</a>',
	'main-secure' => '系统安全检查列表',
	'main-text-0' => '修改管理面板的用户名和密码<a href="apsetup.php">点击这里</a>',
	'main-text-1' => 'Change the username/password for the server <a href="psetup.php">点击这里</a>',
	'main-text-2' => 'Change the root password for mysql by editing <a href="mqsetup.php">点击这里</a>',
	'main-text-3' => 'Run the <a href="security.php">Security Console</a> and see if everything is OK.',
	'main-text-4' => 'Change the username/password for the SSL server <a href="sslpsetup.php">点击这里</a>',
	
	//--------------------------------------------------------------------------------------------
	// Update
	//--------------------------------------------------------------------------------------------
	
	'update-head' => 'Uniform Server Version Check',
	'update-check' => 'Checking Version...',
	'update-notfound' => '
         Version file could not be found on the Uniform Server!
         <br />
         Or
         <br />
         The Unifrom Server is off-line!
         <br />
         Or
         <br />
         You are not connected to the Internet!
         <br />',

	'update-true' => '
	Installed version of the Uniform Server is the latest one.
	<br />
	You don\'t need to update it.
	<br />',
	'update-false' => 'A Newer version of the Uniform Server is available!',
	'update-new' => 'New Version',
	'update-yours' => 'Installed Version',
	'update-get' => 'You can get the newer version from our website by clicking the link below.',
	
	//--------------------------------------------------------------------------------------------
	// Server Control - Standard program
	//--------------------------------------------------------------------------------------------
	
	'server-stop-head1' => 'Stop Servers',
	'server-stop-head2' => 'Stopping servers',
	'server-stop-txt1'  => 'This script will stop Apache and MySQL servers',
	'server-stop-txt2'  => 'The Servers are stopping please wait for a beep to confirm!',
	'server-stop-txt3'  => 'Thank you for using <a href="http://www.uniformserver.com/">The Uniform Server</a>.',
	'server-confirm-button'  => 'Confirm',

	'start-mysql-head1'  => 'Start MySQL Server',
	'start-mysql-head2'  => 'Starting the MySQL server.',
	'start-mysql-txt1'   => 'This script will start the MySQL server.',
	'start-mysql-txt2'   => 'MySQL server already running.',
	'start-mysql-txt3'   => 'The MySQL server was started you can continue using the server.',
        'start-mysql-button' => 'Start MySQL Server',

	'stop-mysql-head1'  => 'Stop MySQL Server',
	'stop-mysql-head2'  => 'Stopping the MySQL server.',
	'stop-mysql-txt1'   => 'This script will stop the MySQL server.',
	'stop-mysql-txt2'   => 'The MySQL server was stopped.',
	'stop-mysql-txt3'   => 'MySQL server not running.',
        'stop-mysql-button' => 'Stop MySQL Server',

	//--------------------------------------------------------------------------------------------
	// Server Control - Services
	//--------------------------------------------------------------------------------------------

	'service-apache-head1' => 'Restart Apache Service',
	'service-apache-head2' => 'Restarting Apache service',
	'service-apache-txt1'  => 'This script will restart the Apache service.',
	'service-apache-txt2'  => 'It will take a some time',
	'service-apache-txt3'  => 'The Apache service is restarting please wait <br /> Between 2-10 seconds!' ,
	'service-apache-txt4'  => 'Apanel will reload to reflect any server configuration changes.',

	'service-mysql-head1' => 'Restart MySQL Service',
	'service-mysql-head2' => 'The MySQL service was restarted.',
	'service-mysql-txt1'  => 'This script will restart the MySQL service.',
	'service-mysql-txt2'  => 'It will take some time',
	'service-mysql-txt3'  => 'The MySQL service was restarted you can continue using the server.',

        'service-confirm-button'  => 'Confirm',
	
	//--------------------------------------------------------------------------------------------
	// Apache 配置
	//--------------------------------------------------------------------------------------------
	
	'aconfig-head' => 'Apache 配置',
	'aconfig-conf' => 'Configure Apache',
	'aconfig-sname' => 'Server Name',
	'aconfig-wemail' => 'Server Admin Email',
	'aconfig-difiles' => 'Directory Index Files',
	'aconfig-ssi' => 'Server Side Includes',
	'aconfig-ssig' => 'Server Signature',
        'aconfig-listen' => 'Listen',
	'aconfig-text-0' => 'something',
	'aconfig-text-1' => '
	The changes have been successfully saved. <br /> Changes will take effect after server restart!',
	'aconfig-save' => 'Save',
	'aconfig-module' => 'At the moment PHP is loaded as Apache module.',
	'aconfig-cgi' => 'At the moment PHP scripts are executed though Apache CGI interface.',
	'aconfig-help' => '?',	

	//--------------------------------------------------------------------------------------------
	// PHP 配置
	//--------------------------------------------------------------------------------------------
	
	'pconfig-head' => 'PHP 配置',
	'pconfig-conf' => 'Configure PHP',
	'pconfig-smode' => 'Safe Mode',
	'pconfig-rg' => 'Register Globals',
	'pconfig-mtexec' => 'Maximum Script Execute Time (s.)',
	'pconfig-mmexec' => 'Maximum Memory Amount (MB)',
	'pconfig-ssig' => 'Show PHP In Server Signature',
	'pconfig-perror' => 'Print Errors',
	'pconfig-mpsize' => 'Maximum Post Size',
	'pconfig-musize' => 'Maximum Upload Size',
	'pconfig-text-0' => 'something',
	'pconfig-text-1' => '
	The changes have been successfully saved. <br /> Changes will take effect after server restart!',
	'pconfig-save' => 'Save',
	'pconfig-module' => 'At the moment PHP is loaded as Apache module.',
	'pconfig-cgi' => 'At the moment PHP scripts are executed though Apache CGI interface.',
	'pconfig-help' => '?',	

	//--------------------------------------------------------------------------------------------
	// VHost Manager
	//--------------------------------------------------------------------------------------------

	'vhost-head' => 'Virtual Host',
	'vhost-setup' => 'Virtual Host Setup',
	'vhost-settings' => 'Virtual Host Settings',
	'vhost-text-0' => 'You have',
	'vhost-text-1' => 'hosts in your httpd.conf file:',
	'vhost-text-2' => 'Error in hosts file:',
	'vhost-text-3' => 'All hostnames exist in hosts file!',
	'vhost-new' => '
	Use this new and cool tool to add more virtual hosts to your server without having to edit
	the httpd.conf file yourself.',
	'vhost-new-ex' => '(ex. newhost.localhost)',
	'vhost-name' => 'Name:',
	'vhost-path' => 'Path to DocumentRoot:',
	'vhost-path-ex' => '(ex. c:/www/newhost)',
	'vhost-opt' => 'Optional additions:',
	'vhost-opt-ex' => '(ex. error_log etc.)',
	'vhost-dne' => 'does not exist',
	'vhost-make' => 'Create VHost',
	'vhost-error-1' => 'Error in path to your hosts-file!',
	'vhost-error-2' => 'Error in path to your httpd.conf!',
	'vhost-text-4' => 'Safe_mode is On, so restart Apache manually!',
	'vhost-credit' => 'Script By Sukos',

	//--------------------------------------------------------------------------------------------
	// Error Log Viewer
	//--------------------------------------------------------------------------------------------

	'elog-viewer-head1' => 'Error Log Viewer',
	'elog-viewer-head2' => 'Viewing Error Log File',

	//--------------------------------------------------------------------------------------------
	// Win to Unix Converter
	//--------------------------------------------------------------------------------------------

	'w2u-head1' => 'Windows to Unix Converter',
	'w2u-head2' => 'Convert Windows Perl Files',
	'w2u-head3' => 'Converted Windows Perl Files',

	'w2u-txt1' => 'If you have problems executing your cgi scripts on Unix.<br />
         This program will convert cgi scripts from Windows to Unix format Dec(#10#13=>#13).<br>Hex 0D0A to 0A',

	'w2u-txt2' => 'Instruction: <br />After execution you can pick up scripts ready for execution
        on a Unix machine from the \\cgi-bin-unix\\ directory.<br />',

	'w2u-txt3' => 'Files converted: <br />They are located in folder \\cgi-bin-unix\\ ',

        'w2u-convert-button'  => 'Convert',


	//--------------------------------------------------------------------------------------------
	// Server Certificate and Key Generation
	//--------------------------------------------------------------------------------------------
	
	'cert-head1' => 'Server Certificate and Key Generation',
	'cert-head2' => 'Verify Generation',
	'cert-head3' => 'Unable to run Certificate and Key Generation.',
	'cert-head4' => 'Certificate and Key Generation Complete.',

	'cert-txt1' => 'Click on Generate! and follow instructions.',
	'cert-txt2' => 'Services are not allowed to interact with the desktop. <br />You need to run this script manually:',
        'cert-txt3' => 'Alternatively use UniTray.',
	'cert-txt4' => 'Cirtificate location:',
	'cert-txt5' => 'Key location:',

        'cert-confirm-button'  => 'Generate',
	
	//--------------------------------------------------------------------------------------------
	// MySQL restore password
	//--------------------------------------------------------------------------------------------
	
	'mysql-head1' => 'MySQL restore password',
	'mysql-head2' => 'Verify Restore',
	'mysql-head3' => 'MySQL password restored.',

	'mysql-txt1' => 'Click on Restore! Restore will take several seconds.',
	'mysql-txt2' => 'Password restored you can continue using the server..',

        'mysql-confirm-button'  => 'Restore MySQL Password',

	//--------------------------------------------------------------------------------------------
	// Server Security Console
	//--------------------------------------------------------------------------------------------

	'secure1-head' => 'Security Alert!',
	'secure1-sub' => 'Possible Attack',
	'secure1-text-0' => 'IP ADDRESS is not 127.0.0.1, but',
	'secure1-text-1' => 'Note: HTTP_REFERER is',
	'secure1-text-2' => 'To disable this warning set $unisecure to 0 in: /home/admin/www/includes/config.inc.php',

	//--------------------------------------------------------------------------------------------
	// Admin Panel Setup
	//--------------------------------------------------------------------------------------------

	'apsetup-head' => 'Admin Panel 配置',
	'apsetup-sub-0' => 'User Management',
	'apsetup-text-0' => 'Setup the username and password for the Admin Panel here. Please note that you might have 
	to activate this feature in the /home/admin/www/.htaccess file.',
	'apsetup-user' => 'Username',
	'apsetup-pass' => 'Password',
	'apsetup-change' => 'Change',
	'apsetup-success' => 'The Admin Panel username/password has been changed to the new values:',

	//--------------------------------------------------------------------------------------------
	// Private Server Setup
	//--------------------------------------------------------------------------------------------

	'psetup-head' => 'Private Server 配置',
	'psetup-sub-0' => 'User Management',
	'psetup-text-0' => 'Setup the username and password for your Private Server here. Please note that you might have 
	to activate this feature in the /www/.htaccess file.',
	'psetup-user' => 'Username',
	'psetup-pass' => 'Password',
	'psetup-change' => 'Change',
	'psetup-success' => 'Your Private Server username/password has been changed to the new values:',

	//--------------------------------------------------------------------------------------------
	// Private Secure Server Setup (SSL)
	//--------------------------------------------------------------------------------------------

	'sslpsetup-head' => 'Private Secure Server 配置 (SSL)',
	'sslpsetup-sub-0' => 'User Management',
	'sslpsetup-text-0' => 'Setup the username and password for your Private Secure Server here. Please note that you might have 
	to activate this feature in the /ssl/.htaccess file.',
	'sslpsetup-user' => 'Username',
	'sslpsetup-pass' => 'Password',
	'sslpsetup-change' => 'Change',
	'sslpsetup-success' => 'Your Private Secure Server username/password has been changed to the new values:',

	//--------------------------------------------------------------------------------------------
	// MySQL Setup
	//--------------------------------------------------------------------------------------------

	'mqsetup-head' => 'MySQL Server 配置',
	'mqsetup-sub-0' => 'User Management',
	'mqsetup-text-0' => 'Setup the MySQL password here. After changing the MySQL password, please note that you <b>
	must shutdown the server using the Stop.bat</b> file and then start the server over again.',
	'mqsetup-pass' => 'MySQL Password',
	'mqsetup-change' => 'Change',
	'mqsetup-success' => 'Your MySQL password has been changed to the new value:',

	//--------------------------------------------------------------------------------------------
	// Server Security Center
	//--------------------------------------------------------------------------------------------

	'secure-head' => 'Security Center',
	'secure-sub-0' => 'User Management Security',
	'secure-sub-1' => 'Server Security',
	'secure-text-0' => 'This part of the security center will check all user management settings to make sure that 
	everything is set. It will tell you if something needs to be changed.',
	'secure-text-1' => 'SECURITY MSG',
	'secure-text-2' => 'STATUS',
	'secure-text-3' => 'Admin Panel',
	'secure-text-X' => 'If the username/password is still set to root, then you probably need to change this 
	by clicking the UNSECURE link.',
	'secure-text-sslX' => 'Unsecure indicates you do not have a server certificate or key. Create new ones by clicking the UNSECURE link.',
	'secure-secure' => 'SECURE',
	'secure-unsecure' => 'UNSECURE',
	'secure-text-7' => 'If the password is still set to root, then you probably need to change this by clicking the UNSECURE link.',
	'secure-text-8' => 'This part of the security center will check and make sure the server settings are appropriate and set corectly.',
	'secure-text-9' => 'PHP Safe Mode',
	'secure-text-10' => 'This checks to see if PHP is running in SAFE MODE. Now, PHP does not have to run in SAFE MODE, but 
	if you want the extra security, you can set it by clicking on the UNSECURE link.',
	'secure-text-p' => 'Personal Server',
	'secure-text-sslp' => 'Personal Secure Server (SSL)',
	'secure-text-sslcertp' => 'Server Certificate and Key (SSL)',
	'secure-text-s' => 'MySQL Server',
	'secure-text-11' => 'Admin Panel Access',
	'secure-text-12' => 'Server Access',
	'secure-text-12ssl' => 'Server Access (SSL)',
	'secure-text-13' => 'While this is another feature that is not throughly important as other features are in place against
	outside access to the Admin Panel, this checks to see if your Admin Panel is secured using the Auth method. Please change this 
	by editing the '.$us_apanel.'/.htaccess file.',
	'secure-text-14' => 'If you are running your server in Production Mode, Skip this one. If not and you would like to
	add more security to the server by blocking it using the Auth method, then change this in by editing the '.$us_www.'/.htaccess file.',
	'secure-text-14ssl' => 'If you are running your server in Production Mode, Skip this one. If not and you would like to
	add more security to the server by blocking it using the Auth method, then change this in by editing the '.$us_ssl.'/.htaccess file.',
	'secure-view' => 'Local View',
	'secure-look' => 'Due to the fact that some PC\'s have a different hostname set rather than localhost, we use the IP method here. This
	checks to make sure that you are viewing the Admin Panel (this) from local.',
);  

# Beta Feature, Currently For Debugging Only
#array2table($US, true);
?>
