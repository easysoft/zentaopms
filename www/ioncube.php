<?php 
include '../config/config.php';
session_name($config->sessionVar);
session_start();
if(!isset($_SESSION['user']) or $_SESSION['user']->account == 'guest' or $_SESSION['company']->supers != $_SESSION['user']->account) die(header('location:index.php'));

/** 
 * ionCube Loader install Wizard
 *
 * ionCube is a registered trademark of ionCube Ltd. 
 *
 * Copyright (c) ionCube Ltd. 2002-2011
 */
define ('ERROR_UNKNOWN_OS',1);
define ('ERROR_UNSUPPORTED_OS',2);
define ('ERROR_UNKNOWN_ARCH',3);
define ('ERROR_UNSUPPORTED_ARCH',4);
define ('ERROR_UNSUPPORTED_ARCH_OS',5);
define ('ERROR_WINDOWS_64_BIT',6);
define ('ERROR_PHP_UNSUPPORTED',7);
define ('ERROR_PHP_DEBUG_BUILD',8);
define ('ERROR_RUNTIME_EXT_DIR_NOT_FOUND',101);
define ('ERROR_RUNTIME_LOADER_FILE_NOT_FOUND',102);
define ('ERROR_INI_NOT_FIRST_ZE',201);
define ('ERROR_INI_WRONG_ZE_START',202);
define ('ERROR_INI_ZE_LINE_NOT_FOUND',203);
define ('ERROR_INI_LOADER_FILE_NOT_FOUND',204);
define ('ERROR_INI_NOT_FULL_PATH',205);
define ('ERROR_INI_NO_PATH',206);
define ('ERROR_INI_NOT_FOUND',207);
define ('ERROR_INI_NOT_READABLE',208);
define ('ERROR_INI_MULTIPLE_IC_LOADER_LINES',209);
define ('ERROR_INI_USER_INI_NOT_FOUND',210);
define ('ERROR_INI_USER_CANNOT_CREATE',211);
define ('ERROR_LOADER_UNEXPECTED_NAME',301);
define ('ERROR_LOADER_NOT_READABLE',302);
define ('ERROR_LOADER_PHP_MISMATCH',303);
define ('ERROR_LOADER_NONTS_PHP_TS',304);
define ('ERROR_LOADER_TS_PHP_NONTS',305);
define ('ERROR_LOADER_WRONG_OS',306);
define ('ERROR_LOADER_WRONG_ARCH',307);
define ('ERROR_LOADER_WRONG_GENERAL',308);
define ('ERROR_LOADER_WIN_SERVER_NONWIN',321);
define ('ERROR_LOADER_WIN_NONTS_PHP_TS',322);
define ('ERROR_LOADER_WIN_TS_PHP_NONTS',323);
define ('ERROR_LOADER_WIN_PHP_MISMATCH',324);
define ('ERROR_LOADER_WIN_COMPILER_MISMATCH',325);
define ('ERROR_LOADER_NOT_FOUND',380);
define ('ERROR_LOADER_PHP_VERSION_UNKNOWN',390);


define ('SERVER_UNKNOWN',0);
define ('HAS_PHP_INI',1);
define ('SERVER_SHARED',2); 
define ('SERVER_VPS',5); 
define ('SERVER_DEDICATED',7); 
define ('SERVER_LOCAL',9);

define ('LOADERS_PAGE',
            'http://loaders.ioncube.com/');                                 
define ('SUPPORT_SITE',
            'http://support.ioncube.com/');                                 
define ('LOADER_FORUM_URL',
            'http://forum.ioncube.com/viewforum.php?f=4');                  
define ('LOADERS_FAQ_URL',
            'http://www.ioncube.com/faqs/loaders.php');                     
define ('UNIX_ERRORS_URL',
            'http://www.ioncube.com/loaders/unix_startup_errors.php');      
define ('LOADER_WIZARD_URL',
            LOADERS_PAGE);                                                  
define ('ENCODER_URL',
            'http://www.ioncube.com/sa_encoder.php');                       
define ('LOADER_VERSION_URL',
            'http://www.ioncube.com/feeds/product_info/versions.php');    
define ('WIZARD_LATEST_VERSION_URL',
            LOADER_VERSION_URL . '?item=loader-wizard'); 
define ('PHP_COMPILERS_URL',
            LOADER_VERSION_URL . '?item=php-compilers');
define ('LOADER_PLATFORM_URL',
            LOADER_VERSION_URL . '?item=loader-platforms');   
define ('LOADER_LATEST_VERSIONS_URL',
            LOADER_VERSION_URL . '?item=loader-versions'); 
define ('WIZARD_STATS_URL',
            'http://www.ioncube.com/feeds/stats/wizard.php');    
define ('IONCUBE_DOWNLOADS_SERVER',
            'http://downloads2.ioncube.com/loader_downloads');          
define ('IONCUBE_CONNECT_TIMEOUT',4);

define ('DEFAULT_SELF','/ioncube/loader-wizard.php');
define ('LOADER_NAME_CHECK',true);
define ('LOADER_EXTENSION_NAME','ionCube Loader');
define ('LOADER_SUBDIR','ioncube');
define ('WINDOWS_IIS_LOADER_DIR', 'system32');
define ('ADDITIONAL_INI_FILE_NAME','20ioncube.ini');
define ('UNIX_SYSTEM_LOADER_DIR','/usr/local/ioncube');
define ('RECENT_LOADER_VERSION','3.1.24');
define ('LATEST_LOADER_MAJOR_VERSION',4);
define ('LOADERS_PACKAGE_PREFIX','ioncube_loaders_');
define ('SESSION_LIFETIME_MINUTES',360);
define ('WIZARD_EXPIRY_MINUTES',10080);
define ('MIN_INITIALISE_TIME',4);

    run();


function php4_http_build_query($formdata, $numeric_prefix = null, $key = null ) {
    $res = array();
    foreach ((array)$formdata as $k=>$v) {
        $tmp_key = urlencode(is_int($k) ? $numeric_prefix.$k : $k);
        if ($key) $tmp_key = $key.'['.$tmp_key.']';
        if ( is_array($v) || is_object($v) ) {
            $res[] = php4_http_build_query($v, null , $tmp_key);
        } else {
            $res[] = $tmp_key."=".urlencode($v);
        }
   }
   $separator = ini_get('arg_separator.output');
   return implode($separator, $res);
}


function script_version()
{
    return "2.36";
}

function retrieve_latest_wizard_version()
{
    $v = false;

    $s = trim(remote_file_contents(WIZARD_LATEST_VERSION_URL));
    if (preg_match('/^\d+([.]\d+)*$/', $s)) {
        $v = $s;
    }

    return $v;
}

function latest_wizard_version()
{
    if (!isset($_SESSION['latest_wizard_version'])) {
        $_SESSION['latest_wizard_version'] = retrieve_latest_wizard_version();
    } 
    return $_SESSION['latest_wizard_version'];
}

function update_is_available($lv)
{
    if (is_numeric($lv)) {
        $lv_parts = explode('.',$lv);
        $script_parts = explode('.',script_version());
        return ($lv_parts[0] > $script_parts[0] || ($lv_parts[0] == $script_parts[0] && $lv_parts[1] > $script_parts[1]));
    } else {
        return null;
    }
}

function check_for_wizard_update($echo_message = false)
{
    $latest_version = latest_wizard_version();
    $update_available = update_is_available($latest_version);

    if ($update_available) {
        if ($echo_message) {
            echo '<p class="alert">该文件有一个新的版本，点击<a href="' . LOADER_WIZARD_URL . '">这里</a>查看.</p>';
        }
        return $latest_version;
    } else {
        return $update_available;
    }
}


function remote_file_contents($url)
{
    $remote_file_opening = ini_get('allow_url_fopen');
    $contents = false;
    if (isset($_SESSION['timing_out']) && $_SESSION['timing_out']) {
        return false;
    }
    @session_write_close();
    $timing_out = 0;
    if ($remote_file_opening) {
        $fh = @fopen($url,'rb');
        if ($fh) {
            stream_set_blocking($fh,0);
            stream_set_timeout($fh,IONCUBE_CONNECT_TIMEOUT);
            while (!feof($fh)) {
                $result = fgets($fh, 4096);
                $info = stream_get_meta_data($fh);
                $timing_out = $info['timed_out']?1:0;
                if ($timing_out) {
                    break;
                }
                if ($result !== false) {
                    $contents .= $result;
                } else {
                    break;
                }
            }
            fclose($fh);
        } else {
            $timing_out = 1;
        }
    } elseif (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,IONCUBE_CONNECT_TIMEOUT);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $timing_out = ($info['http_code'] >= 400)?1:0;
            curl_close($ch);

            if (is_string($output)) {
                $contents = $output;
            }
    } else {
        $timing_out = 1;
    }
    @session_start();
    $_SESSION['timing_out'] = $timing_out;
    return $contents;
}

function php_version()
{
    $v = explode('.',PHP_VERSION);

    return array(
           'major'      =>  $v[0],
           'minor'      =>  $v[1],
           'release'    =>  $v[2]);
}

function php_version_maj_min()
{
    $vprts = php_version();
    return ($vprts['major'] . '.' . $vprts['minor']);
}

function is_supported_php_version()
{
    $v = php_version(); 

    return ((($v['major'] == 4) && ($v['minor'] >= 1)) ||
      (($v['major'] == 5) && (($v['minor'] >= 1) || ($v['release'] >= 3))));
}

function is_php_version_or_greater($major,$minor,$release = 0)
{
    $version = php_version();
    return ($version['major'] > $major || 
            ($version['major'] == $major && $version['minor'] > $minor) ||
            ($version['major'] == $major && $version['minor'] == $minor && $version['release'] >= $release));
}

function ini_file_name()
{
    $sysinfo = get_sysinfo();
    return (!empty($sysinfo['PHP_INI'])?$sysinfo['PHP_INI_BASENAME']:'php.ini');
}

function get_remote_session_value($session_var,$remote_url,$default_function)
{
    if (!isset($_SESSION[$session_var])) {
        $serialised_res = remote_file_contents($remote_url);
        $unserialised_res = @unserialize($serialised_res);
        if (empty($unserialised_res)) {
            $unserialised_res = call_user_func($default_function);
        }
        if (false === $unserialised_res) {
            $unserialised_res = '';
        }
        $_SESSION[$session_var] = $unserialised_res;
    }
    return $_SESSION[$session_var];
}

function get_file_contents($file)
{
    if (function_exists('file_get_contents')) {
        $strs = @file_get_contents($file);
    } else {
        $lines = @file($file);
        $strs = join(' ',$lines);
    }
    return $strs;
}

function default_platform_list()
{
    $platforms = array();


    $platforms[] = array('os'=>'win', 'os_human'=>'Windows VC6',        'os_mod' => '_vc6',     'arch'=>'x86',  'dirname'=>'win32', 'us1-dir'=>'windows_vc6/x86' );
    $platforms[] = array('os'=>'win', 'os_human'=>'Windows VC6 (Non-TS)',   'os_mod' => '_nonts_vc6',   'arch'=>'x86',  'dirname'=>'win32-nonts', 'us1-dir'=>'windows_vc6/x86-nonts' );

    $platforms[] = array('os'=>'win', 'os_human'=>'Windows VC9',        'os_mod' => '_vc9',     'arch'=>'x86',  'dirname'=>'win32_vc9', 'us1-dir'=>'windows_vc9/x86' );
    $platforms[] = array('os'=>'win', 'os_human'=>'Windows VC9 (Non-TS)',   'os_mod' => '_nonts_vc9',   'arch'=>'x86',  'dirname'=>'win32-nonts_vc9', 'us1-dir'=>'windows_vc9/x86-nonts' );

    $platforms[] = array('os'=>'lin', 'os_human'=>'Linux',              'arch'=>'x86',      'dirname'=>'linux_i686-glibc2.1.3', 'us1-dir'=>'linux/x86');
    $platforms[] = array('os'=>'lin', 'os_human'=>'Linux',              'arch'=>'x86-64',   'dirname'=>'linux_x86_64-glibc2.3.4', 'us1-dir'=>'linux/x86_64');
$platforms[] = array('os'=>'lin','os_human'=>'Linux',               'arch'=>'ppc',      'dirname'=>'linux_ppc-glibc2.3.4','us1-dir'=>'linux/ppc');
            $platforms[] = array('os'=>'lin','os_human'=>'Linux',               'arch'=>'ppc64',    'dirname'=>'linux_ppc64-glibc2.5','us1-dir'=>'linux/ppc64');
    

$platforms[] = array('os'=>'dra', 'os_human'=>'DragonFly', 
    'arch'=>'x86',      'dirname'=>'dragonfly_i386-1.7', 'us1-dir'=>'Dragonfly/x86');

$platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 4', 'os_mod'=>'_4',  'arch'=>'x86',      'dirname'=>'freebsd_i386-4.8', 'us1-dir'=>'FreeBSD/v4');

    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 6', 'os_mod'=>'_6',  'arch'=>'x86',      'dirname'=>'freebsd_i386-6.2', 'us1-dir'=>'FreeBSD/v6/x86');

    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 6', 'os_mod'=>'_6',  'arch'=>'x86-64',   'dirname'=>'freebsd_amd64-6.2', 'us1-dir'=>'FreeBSD/v6/AMD64');


    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 7', 'os_mod'=>'_7',  'arch'=>'x86',      'dirname'=>'freebsd_i386-7.3', 'us1-dir'=>'FreeBSD/v7/x86');
    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 7', 'os_mod'=>'_7',  'arch'=>'x86-64',   'dirname'=>'freebsd_amd64-7.3', 'us1-dir'=>'FreeBSD/v7/AMD64');


    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 8', 'os_mod'=>'_8',  'arch'=>'x86',      'dirname'=>'freebsd_i386-8.0', 'us1-dir'=>'FreeBSD/v8/x86');
    $platforms[] = array('os'=>'fre', 'os_human'=>'FreeBSD 8', 'os_mod'=>'_8',  'arch'=>'x86-64',   'dirname'=>'freebsd_amd64-8.0', 'us1-dir'=>'FreeBSD/v8/AMD64');
    
    $platforms[] = array('os'=>'bsd', 'os_human'=>'BSDi',               'arch'=>'x86',      'dirname'=>'bsdi_i386-4.3.1');
    $platforms[] = array('os'=>'net', 'os_human'=>'NetBSD',             'arch'=>'x86',      'dirname'=>'netbsd_i386-2.1','us1-dir'=>'NetBSD/x86');
    $platforms[] = array('os'=>'net', 'os_human'=>'NetBSD',             'arch'=>'x86-64',   'dirname'=>'netbsd_amd64-2.0','us1-dir'=>'NetBSD/x86_64');
    $platforms[] = array('os'=>'ope', 'os_human'=>'OpenBSD 4.2', 'os_mod'=>'_4.2',  'arch'=>'x86',  'dirname'=>'openbsd_i386-4.2', 'us1-dir'=>'OpenBSD/x86');

    $platforms[] = array('os'=>'ope', 'os_human'=>'OpenBSD 4.5', 'os_mod'=>'_4.5',  'arch'=>'x86',  'dirname'=>'openbsd_i386-4.5', 'us1-dir'=>'OpenBSD/x86');
    $platforms[] = array('os'=>'ope', 'os_human'=>'OpenBSD 4.6', 'os_mod'=>'_4.6',  'arch'=>'x86',  'dirname'=>'openbsd_i386-4.6', 'us1-dir'=>'OpenBSD/x86');

    $platforms[] = array('os'=>'ope', 'os_human'=>'OpenBSD 4.7', 'os_mod'=>'_4.7',  'arch'=>'x86-64', 'dirname'=>'openbsd_amd64-4.7', 'us1-dir' => 'OpenBSD/x86_64');

    $platforms[] = array('os'=>'dar', 'os_human'=>'OS X',               'arch'=>'ppc',      'dirname'=>'osx_powerpc-8.5','us1-dir'=>'OSX/ppc');

    $platforms[] = array('os'=>'dar', 'os_human'=>'OS X',               'arch'=>'x86',      'dirname'=>'osx_i386-8.11','us1-dir'=>'OSX/x86');

    $platforms[] = array('os'=>'dar', 'os_human'=>'OS X',               'arch'=>'x86-64',       'dirname'=>'osx_x86-64-10.2','us1-dir'=>'OSX/x86_64');

    $platforms[] = array('os'=>'sun', 'os_human'=>'Solaris',            'arch'=>'sparc',    'dirname'=>'solaris_sparc-5.9', 'us1-dir'=>'Solaris/sparc');

    $platforms[] = array('os'=>'sun', 'os_human'=>'Solaris',            'arch'=>'x86',      'dirname'=>'solaris_i386-5.10','us1-dir'=>'Solaris/x86');

    return $platforms;
}

function get_loader_platforms()
{
    return get_remote_session_value('loader_platform_info',LOADER_PLATFORM_URL,'default_platform_list');
}

function get_platforminfo()
{
    static $platforminfo;

    if (empty($platforminfo)) {
        $platforminfo = get_loader_platforms();
    }
    return $platforminfo;
}

function supported_os_variants($os_code,$arch_code)
{
    if (empty($os_code)) {
        return ERROR_UNKNOWN_OS;
    }
    if (empty($arch_code)) {
        return ERROR_UNKNOWN_ARCH;
    }

    $os_found = false;
    $arch_found = false;
    $os_arch_matches = array();
    $pinfo = get_platforminfo();

    foreach ($pinfo as $p) {
        if ($p['os'] == $os_code && $p['arch'] == $arch_code) {
            $os_arch_matches[$p['os_human']] = (isset($p['os_mod']))?(0 + str_replace('_','',$p['os_mod'])):'';
        } 
        if ($p['os'] == $os_code) {
            $os_found = true;
        } elseif ($p['arch'] == $arch_code) {
            $arch_found = true;
        }
    }
    if (!empty($os_arch_matches)) {
        asort($os_arch_matches);
        return $os_arch_matches;
    } elseif (!$os_found) {
        return ERROR_UNSUPPORTED_OS;
    } elseif (!$arch_found) {
        return ERROR_UNSUPPORTED_ARCH;
    } else {
        return ERROR_UNSUPPORTED_ARCH_OS;
    }
}

function default_win_compilers()
{
    return array('VC6','VC9');
}

function supported_win_compilers()
{
    static $win_compilers;

    if (empty($win_compilers)) {
        $win_compilers = find_win_compilers();
    }
    return $win_compilers;
}

function find_win_compilers()
{
    return get_remote_session_value('php_compilers_info',PHP_COMPILERS_URL,'default_win_compilers');
}

function server_software_info()
{
    $ss = array('full' => '','short' => '');
    $ss['full'] = $_SERVER['SERVER_SOFTWARE'];

    if (preg_match('/apache/i', $ss['full'])) {
        $ss['short'] = 'Apache';
    } else if (preg_match('/IIS/',$ss['full'])) {
        $ss['short'] = 'IIS';
    } else {
        $ss['short'] = '';
    }
    return $ss;
}

function match_arch_pattern($str)
{
    $arch = null;
    $arch_patterns = array(
             'i.?86'        => 'x86',
             'x86[-_]64'    => 'x86',
             'x86'          => 'x86',
             'amd64'        => 'x86',
             'ppc64'        => 'ppc',
             'ppc'          => 'ppc',
             'powerpc'      => 'ppc',
             'sparc'        => 'sparc',
             'sun'          => 'sparc'
         );

    foreach ($arch_patterns as $token => $a) {
        if (preg_match("/$token/i", $str)) {
          $arch = $a;
          break;
        }
    }
    return $arch;
}

function required_loader_arch($mach_info,$os_code,$wordsize)
{
    if ($os_code == 'win') {
        $arch = ($wordsize == 32)?'x86':'x86-64';
        if ($wordsize != 32) {
            $arch = ERROR_WINDOWS_64_BIT;
        }
    } elseif (!empty($os_code)) {
        $arch = match_arch_pattern($mach_info);
        if ($wordsize == 64) {
            if ($arch == 'x86') {
                $arch = 'x86-64';
            } elseif ($arch == 'ppc') {
                $arch = 'ppc64';
            }
        }
    } else {
        $arch = ERROR_UNKNOWN_ARCH;
    }
    return $arch;
}

function uname($part = 'a')
{
    $result = '';
    if (!function_is_disabled('php_uname')) {
        $result = @php_uname($part);
    } elseif (function_exists('posix_uname') && !function_is_disabled('posix_uname')) {
        $posix_equivs = array(
                     'm' => 'machine',
                     'n' => 'nodename',
                     'r' => 'release',
                     's' => 'sysname'
                 );
        $puname = @posix_uname();
        if ($part == 'a' || !array_key_exists($part,$posix_equivs)) {
           $result = join(' ',$puname);
        } else {
           $result = $puname[$posix_equivs[$part]];
        }
    } else {
        if (!function_is_disabled('phpinfo')) {
            ob_start();
            phpinfo(INFO_GENERAL);
            $pinfo = ob_get_contents();
            ob_end_clean();
            if (preg_match('~System.*?(</B></td><TD ALIGN="left">| => |v">)([^<]*)~i',$pinfo,$match)) {
                $uname = $match[2];
                if ($part == 'r') {
                    if (!empty($uname) && preg_match('/\S+\s+\S+\s+([0-9.]+)/',$uname,$matchver)) {
                        $result = $matchver[1];
                    } else {
                        $result = '';
                    }
                } else {
                    $result = $uname;
                }
            }
        } else {
            $result = '';
        }
    }
    return $result;
}

function calc_word_size($os_code)
{
    $wordsize = null;
    if ('win' === $os_code) {
        ob_start();
        phpinfo(INFO_GENERAL);
        $pinfo = ob_get_contents();
        ob_end_clean();
        if (preg_match('~Compiler.*?(</B></td><TD ALIGN="left">| => |v">)([^<]*)~i',$pinfo,$compmatch)) {
            if (preg_match("/(VC[0-9]+)/i",$compmatch[2],$vcmatch)) {
                $compiler = strtoupper($vcmatch[1]);
            } else {
                $compiler = 'VC6';
            }
        } else {
            $compiler = 'VC6';
        }
        if ($compiler === 'VC9') {
            if (isset($_ENV['PROCESSOR_ARCHITECTURE']) && preg_match('~(amd64|x86-64|x86_64)~i',$_ENV['PROCESSOR_ARCHITECTURE'])) {
                if (preg_match('~Configure Command.*?(</B></td><TD ALIGN="left">| => |v">)([^<]*)~i',$pinfo,$confmatch)) {
                    if (preg_match('~(x64|lib64|system64)~i',$confmatch[2])) {
                        $wordsize = 64;
                    }
                }
            }
        }
    }
    if (empty($wordsize)) {
        $wordsize = ((-1^0xffffffff)?64:32);
    }
    return $wordsize;
}

function required_loader($unamestr = '')
{
    $un = empty($unamestr)?uname():$unamestr;

    $php_major_version = substr(PHP_VERSION,0,3);

    $os_name = substr($un,0,strpos($un,' '));
    $os_code = empty($os_name)?'':strtolower(substr($os_name,0,3));

    $wordsize = calc_word_size($os_code);

    $arch = required_loader_arch($un,$os_code,$wordsize);
    if (!is_string($arch)) {
        return $arch;
    }
    $os_variants = supported_os_variants($os_code,$arch);
    if (!is_array($os_variants)) {
        return $os_variants;
    }

    $os_ver = '';
    if (preg_match('/([0-9.]+)/',uname('r'),$match)) {
        $os_ver = $match[1];
    }
    $os_ver_parts = preg_split('@\.@',$os_ver);

    $loader_sfix = (($os_code == 'win') ? 'dll' : 'so');
    $file = "ioncube_loader_${os_code}_${php_major_version}.${loader_sfix}";

    if ($os_code == 'win') {
        $os_name = 'Windows';
        $file_ts = $file;
        $os_name_qual = 'Windows';
    } else {
        $os_names = array_keys($os_variants);
        if (count($os_variants) > 1) {
            $parts = explode(" ",$os_names[0]); 
            $os_name = $parts[0];
            $os_name_qual = $os_name . ' ' . $os_ver_parts[0] . '.' . $os_ver_parts[1];
        } else {
            $os_name = $os_names[0];
            $os_name_qual = $os_name;
        }
        $file_ts = "ioncube_loader_${os_code}_${php_major_version}_ts.${loader_sfix}";
    }

    return array(
           'uname'      =>  $un,
           'arch'       =>  $arch,
           'oscode'     =>  $os_code,
           'osname'     =>  $os_name,
           'osnamequal' =>  $os_name_qual,
           'osvariants' =>  $os_variants,
           'osver'      =>  $os_ver,
           'osver2'     =>  $os_ver_parts,
           'file'       =>  $file,
           'file_ts'    =>  $file_ts,
           'wordsize'   =>  $wordsize
       );
}

function ic_system_info()
{
    $thread_safe = null;
    $debug_build = null;
    $cgi_cli = false;
    $is_cgi = false;
    $is_cli = false;
    $php_ini_path = '';
    $php_ini_dir = '';
    $php_ini_add = '';
    $is_supported_compiler = true;
    $php_compiler = is_ms_windows()?'VC6':'';

    ob_start();
    phpinfo(INFO_GENERAL);
    $php_info = ob_get_contents();
    ob_end_clean();

    $breaker = (php_sapi_name() == 'cli')?'\n':'</tr>';
    $lines = explode($breaker,$php_info);
    foreach ($lines as $line) {
        if (preg_match('/command/i',$line)) {
          continue;
        }

        if (preg_match('/thread safety/i', $line)) {
          $thread_safe = (preg_match('/(enabled|yes)/i', $line) != 0);
        }

        if (preg_match('/debug build/i', $line)) {
          $debug_build = (preg_match('/(enabled|yes)/i', $line) != 0);
        }

        if (preg_match('~configuration file.*(</B></td><TD ALIGN="left">| => |v">)([^ <]*)~i',$line,$match)) {
          $php_ini_path = $match[2];

          if (!@file_exists($php_ini_path)) {
                $php_ini_path = '';
          }
        }
        if (preg_match('~dir for additional \.ini files.*(</B></td><TD ALIGN="left">| => |v">)([^ <]*)~i',$line,$match)) {
            $php_ini_dir = $match[2];
            if (!@file_exists($php_ini_dir)) {
                $php_ini_dir = '';
            }
        }
        if (preg_match('~additional \.ini files parsed.*(</B></td><TD ALIGN="left">| => |v">)([^ <]*)~i',$line,$match)) {
            $php_ini_add = $match[2];
        }
        if (preg_match('/compiler/i',$line)) {
            $supported_match = join('|',supported_win_compilers());
            $is_supported_compiler = preg_match("/($supported_match)/i",$line);
            if (preg_match("/(VC[0-9]+)/i",$line,$match)) {
                $php_compiler = strtoupper($match[1]);
            } else {
                $php_compiler = '';
            }
        }
    }
    $is_cgi = strpos(php_sapi_name(),'cgi') !== false;
    $is_cli = strpos(php_sapi_name(),'cli') !== false;
    $cgi_cli = $is_cgi || $is_cli;

    $ss = server_software_info();

    if (!$php_ini_path && function_exists('php_ini_loaded_file')) {
        $php_ini_path = php_ini_loaded_file();
        if ($php_ini_path === false) {
            $php_ini_path = '';
        }
    }
    if (!empty($php_ini_path)) {
        $real_path = @realpath($php_ini_path);
        if (false !== $real_path) {
            $php_ini_path = $real_path;
        }
    }

    $php_ini_basename = basename($php_ini_path);

    return array(
           'THREAD_SAFE'        => $thread_safe,
           'DEBUG_BUILD'        => $debug_build,
           'PHP_INI'            => $php_ini_path,
           'PHP_INI_BASENAME'   => $php_ini_basename,
           'PHP_INI_DIR'        => $php_ini_dir,
           'PHP_INI_ADDITIONAL' => $php_ini_add,
           'PHPRC'              => getenv('PHPRC'),
           'CGI_CLI'            => $cgi_cli,
           'IS_CGI'             => $is_cgi,
           'IS_CLI'             => $is_cli,
           'PHP_COMPILER'       => $php_compiler,
           'SUPPORTED_COMPILER' => $is_supported_compiler,
           'FULL_SS'            => $ss['full'],
           'SS'                 => $ss['short']);
}

function is_possibly_dedicated_or_local()
{
    $sys = get_sysinfo();

    return (empty($sys['PHP_INI']) || !@file_exists($sys['PHP_INI']) || (is_readable($sys['PHP_INI']) && (0 !== strpos($sys['PHP_INI'],$_SERVER['DOCUMENT_ROOT']))));
}

function is_local()
{
    $ret = false;
    if ($_SERVER["SERVER_NAME"] == 'localhost') {
        $ret = true;
    } else {
        $ip_address = strtolower($_SERVER["REMOTE_ADDR"]);
        if (strpos(':',$ip_address) === false) {
            $ip_parts = explode('.',$ip_address);
            $ret = (($ip_parts[0] == 10) || 
                    ($ip_parts[0] == 172 && $ip_parts[1] >= 16 &&  $ip_parts[1] <= 31) ||
                    ($ip_parts[0] == 192 && $ip_parts[1] == 168));
        } else {
            $ret = ($ip_address == '::1') || (($ip_address[0] == 'f') && ($ip_address[1] >= 'c' && $ip_address[1] <= 'f'));
        }
    }
    return $ret;
}

function is_shared()
{
    return !is_local() && !is_possibly_dedicated_or_local();
}

function find_server_type($chosen_type = '',$type_must_be_chosen = false,$set_session = false)
{
    $server_type = SERVER_UNKNOWN;
    if (empty($chosen_type)) {
        if ($type_must_be_chosen) {
            $server_type = SERVER_UNKNOWN;
        } else {
            if (isset($_SESSION['server_type']) && $_SESSION['server_type'] != SERVER_UNKNOWN) {
                $server_type = $_SESSION['server_type'];
            } elseif (is_local()) {
                $server_type = SERVER_LOCAL;
            } elseif (!is_possibly_dedicated_or_local()) {
                $server_type = SERVER_SHARED;
            } else {
                $server_type = SERVER_UNKNOWN;
            } 
        }
    } else {
        switch ($chosen_type)  {
            case 's':
                $server_type = SERVER_SHARED;
                break;
            case 'd':
                $server_type = SERVER_DEDICATED;
                break;
            case 'l':
                $server_type = SERVER_LOCAL;
                break;
            default:
                $server_type = SERVER_UNKNOWN;
                break;
        }
    }
    if ($set_session) {
        $_SESSION['server_type'] = $server_type;
    }
    return $server_type;
}

function server_type_string()
{
    $server_code = find_server_type();
    switch ($server_code) {
        case SERVER_SHARED:
            $server_string = 'SHARED';
            break;
        case SERVER_LOCAL:
            $server_string = 'LOCAL';
            break;
        case SERVER_DEDICATED:
            $server_string = 'DEDICATED';
            break;
        default:
            $server_string = 'UNKNOWN';
            break;
    }
    return $server_string;
}

function server_type_code()
{
    $server_code = find_server_type();
    switch ($server_code) {
        case SERVER_SHARED:
            $server_char = 's';
            break;
        case SERVER_LOCAL:
            $server_char = 'l';
            break;
        case SERVER_DEDICATED:
            $server_char = 'd';
            break;
        default:
            $server_char = '';
            break;
    }
    return $server_char;
}

function get_sysinfo()
{
    static $sysinfo;

    if (empty($sysinfo)) {
        $sysinfo = ic_system_info();
    }
    return $sysinfo;
}

function get_loaderinfo()
{
    static $loader;

    if (empty($loader)) {
        $loader = required_loader();
    }
    return $loader;
}

function is_ms_windows()
{
    $loader_info = get_loaderinfo();
    return ($loader_info['oscode'] == 'win');
}

function function_is_disabled($fn_name)
{
    $disabled_functions=explode(',',ini_get('disable_functions'));
    return in_array($fn_name, $disabled_functions);
}

function selinux_is_enabled()
{
    $se_enabled = false;

    if (!is_ms_windows()) {
        $cmd = @shell_exec('sestatus');
        $se_enabled = preg_match('/enabled/i',$cmd);
    }

    return $se_enabled;
}

function grsecurity_is_enabled()
{
    $gr_enabled = false;

    if (!is_ms_windows()) {
        $cmd = @shell_exec('gradm -S');
        $gr_enabled = preg_match('/enabled/i',$cmd);
    }

    return $gr_enabled;
}

function threaded_and_not_cgi()
{
    $sys = get_sysinfo();
    return($sys['THREAD_SAFE'] && !$sys['IS_CGI']);
}

function is_restricted_server($only_safe_mode = false)
{
    $disable_functions = ini_get('disable_functions');
    $open_basedir = ini_get('open_basedir');
    $php_restrictions = !empty($disable_functions) || !empty($open_basedir);
    $system_restrictions = selinux_is_enabled() || grsecurity_is_enabled();
    $non_safe_mode_restrictions = $php_restrictions || $system_restrictions;
    return (ini_get('safe_mode') || (!$only_safe_mode && $non_safe_mode_restrictions));
}

function server_restriction_warnings()
{
    $warnings = array();

    if (find_server_type() == SERVER_SHARED) {
        if (is_restricted_server()) {
            $warnings[] = "服务器的限制可能影响向导的进行和加密程序的安装。";
        }
    } else {
        $warning_suffix = "这可能会对加密向导产生影响。";
        if (ini_get('safe_mode')) {
            $warnings[] = "服务器已经开启安全模式(php已设置safe_mode)，" . $warning_suffix;
        } 
        $disabled_functions = ini_get('disable_functions');
        if (!empty($disabled_functions)) {
            $warnings[] = "一些php方法不可用(php已设置disable_functions)，" . $warning_suffix;
        }
        $open_basedir = ini_get('open_basedir');
        if (!empty($open_basedir)) {
            $warnings[] = "php的open_basedir不为空，" . $warning_suffix;
        }
    }
    return $warnings;
}

function own_php_ini_possible($only_safe_mode = false)
{
    $sysinfo = get_sysinfo();
    return ($sysinfo['CGI_CLI'] && !is_ms_windows() && !is_restricted_server($only_safe_mode));
}

function extension_dir()
{
    $extdir = ini_get('extension_dir');
    if ($extdir == './' || ($extdir == '.\\' && is_ms_windows())) {
        $extdir = '.';
    }
    return $extdir;
}

function possibly_selinux()
{
    $loaderinfo = get_loaderinfo();
    $se_env = (getenv("SELINUX_INIT"));
    return (strtolower($loaderinfo['osname']) == 'linux' && $se_env && ($se_env == 'Yes' || $se_env == '1'));
}

function ini_same_dir_as_wizard()
{
    $sys = get_sysinfo();
    return dirname($sys['PHP_INI']) == dirname(__FILE__); 
}

function extension_dir_path()
{
    $ext_dir = extension_dir();
    if ($ext_dir == '.' || (dirname($ext_dir) == '.')) {
        $ext_dir_path = @realpath($ext_dir);
    } else {
        $ext_dir_path = $ext_dir;
    }
    return $ext_dir_path;
}

function get_loader_name()
{
    $u = uname();
    $sys = get_sysinfo();
    $os = substr($u,0,strpos($u,' '));
    $os_key = strtolower(substr($u,0,3));

    $php_version = phpversion();
    $php_family = substr($php_version,0,3);

    $loader_sfix = (($os_key == 'win') ? '.dll' : (($sys['THREAD_SAFE'])?'_ts.so':'.so'));
    $loader_name="ioncube_loader_${os_key}_${php_family}${loader_sfix}";

    return $loader_name;
}

function get_reqd_version($variants)
{
    $exact_match = false;
    $nearest_version = 0;
    $loader_info = get_loaderinfo();
    $os_version = $loader_info['osver2'][0] . '.' . $loader_info['osver2'][1];
    $os_version_major = $loader_info['osver2'][0];
    foreach ($variants as $v) {
        if ($v == $os_version || (is_int($v) && $v == $os_version_major)) {
            $exact_match = true;
            $nearest_version = $v;
            break;
        } elseif ($v > $os_version) {
            break;
        } else {
            $nearest_version = $v;
        }
    }
    return (array($nearest_version,$exact_match));
}

function get_default_loader_dir_webspace()
{
    return ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . LOADER_SUBDIR);
}

function get_loader_location($loader_dir = '')
{
    if (empty($loader_dir)) {
        $loader_dir = get_default_loader_dir_webspace();
    }
    $loader_name = get_loader_name(); 
    return ($loader_dir . DIRECTORY_SEPARATOR . $loader_name);
}

function get_loader_location_from_ini($php_ini = '')
{
    $errors = array();
    if (empty($php_ini)) {
        $sysinfo = get_sysinfo();
        $php_ini = $sysinfo['PHP_INI'];
    }
    if (!@file_exists($php_ini)) {
        if (empty($php_ini)) {
            $errors[ERROR_INI_NOT_FOUND] = "配置文件没有找到。";
        } else {
            $errors[ERROR_INI_NOT_FOUND] = "$php_ini 没有找到。";
        }
    } elseif (!is_readable($php_ini)) {
        $errors[ERROR_INI_NOT_READABLE] = "$php_ini 没有权限读取。";
    }
    if (!empty($errors)) {
        return array('location' => '', 'errors' => $errors);
    } 
    $lines = file($php_ini);
    $ext_start = zend_extension_line_start();
    $wrong_ext_start = ($ext_start == 'zend_extension')?'zend_extension_ts':'zend_extension';
    $loader_path = '';
    $loader_name_match = "ioncube_loader";
    foreach ($lines as $l) {
        if (preg_match("/^\s*$ext_start\s*=\s*\"?([^\"]+)\"?/i",$l,$corr_matches)) {
            if (preg_match("/$loader_name_match/i",$corr_matches[1])) {
                if (!empty($loader_path)) {
                    $errors[ERROR_INI_MULTIPLE_IC_LOADER_LINES] = "配置文件($php_ini)中可能有多行存在$ext_start。";
                }
                $loader_path = $corr_matches[1];
            } else {
                if (empty($loader_path)) {
                    $errors[ERROR_INI_NOT_FIRST_ZE] = "$php_ini 中的ionCube加密程序配置必须放在Zend扩展之前";
                }
            }
        }
        if (empty($loader_path)) {
            if (preg_match("/^\s*$wrong_ext_start\s*=\s*\"?([^\"]+)\"?/i",$l,$bad_start_matches)) {
                if (preg_match("/$loader_name_match/i",$bad_start_matches[1])) {
                    $bad_zend_ext_msg = "配置文件$php_ini中的ionCube加密程序所在的行，应该以 $ext_start 开头，<b>而不是</b> $wrong_ext_start.";
                    $errors[ERROR_INI_WRONG_ZE_START] = $bad_zend_ext_msg;
                    $loader_path = $bad_start_matches[1];
                }
            }
        }
    }
    $loader_path = trim($loader_path);
    if ($loader_path === '') {
        $errors[ERROR_INI_ZE_LINE_NOT_FOUND] = "配置文件($php_ini)没有Zend扩展。";
    } elseif (!@file_exists($loader_path)) {
        $errors[ERROR_INI_LOADER_FILE_NOT_FOUND] = "配置文件($php_ini)中的加密程序路径($loader_path)错误或不能访问。";
    } elseif (basename($loader_path) == $loader_path) {
        $errors[ERROR_INI_NOT_FULL_PATH] = "配置文件($php_ini)中的加密程序路径必须是完整路径。";
    }
    return array('location' => $loader_path, 'errors' => $errors);
}

function zend_extension_line_missing($ini_path)
{
    $loader_loc = get_loader_location_from_ini($ini_path);
    return (!empty($loader_loc['errors']) && array_key_exists(ERROR_INI_ZE_LINE_NOT_FOUND,$loader_loc['errors']));
}

function find_additional_ioncube_ini()
{
    $sys = get_sysinfo();
    $ioncube_ini = '';

    if (!empty($sys['PHP_INI_ADDITIONAL']) && !preg_match('/(none)/i',$sys['PHP_INI_ADDITIONAL'])) {
        $ini_files = explode(',',$sys['PHP_INI_ADDITIONAL']);
        foreach ($ini_files as $f) {
            $fn = trim($f);
            $bfn = basename($fn);
            if (preg_match('/ioncube/i',$bfn)) {
                $ioncube_ini = $fn;
                break;
            }
        }
    }
    return $ioncube_ini;
}

function get_additional_ini_files()
{
    $sys = get_sysinfo();
    $ini_files = array();
    if (!empty($sys['PHP_INI_ADDITIONAL']) && !preg_match('/(none)/i',$sys['PHP_INI_ADDITIONAL'])) {
        $ini_files = explode(',',$sys['PHP_INI_ADDITIONAL']);
    }
    return (array_map('trim',$ini_files));
}

function all_ini_contents()
{
    $sys = get_sysinfo();
    $output = '';

    $output .= ";;; *MAIN INI FILE AT ${sys['PHP_INI']}* ;;;" . PHP_EOL;
    $output .= get_file_contents($sys['PHP_INI']);
    $other_inis = get_additional_ini_files();
    foreach ($other_inis as $inif) {
        $output .= ";;; *Additional ini file at $inif* ;;;" . PHP_EOL;
        $output .= get_file_contents($inif);
    }
    $here = unix_path_dir();
    $unrec_ini_files = unrecognised_inis_webspace($here);
    foreach ($unrec_ini_files as $urinif) {
        $output .= ";;; *UNRECOGNISED INI FILE at $urinif* ;;;" . PHP_EOL;
        $output .= get_file_contents($urinif);
    }
    return $output;
}

function scan_inis_for_loader()
{
    $ldloc = '';
    $sysinfo = get_sysinfo();
    if (empty($sysinfo['PHP_INI'])) {
        $ini_files_not_found = array("Main ini file");
        $ini_file_list = get_additional_ini_files();
    } else {
        $ini_files_not_found = array();
        $ini_file_list = array_merge(array($sysinfo['PHP_INI']),get_additional_ini_files());
    }
    $server_type = find_server_type();
    $shared_server = SERVER_SHARED == $server_type;
    foreach ($ini_file_list as $f) {
        $ldloc = get_loader_location_from_ini($f);
        if (array_key_exists(ERROR_INI_ZE_LINE_NOT_FOUND,$ldloc['errors'])) {
            unset($ldloc['errors'][ERROR_INI_ZE_LINE_NOT_FOUND]);
        } 
        if ($shared_server && array_key_exists(ERROR_INI_NOT_FOUND,$ldloc['errors'])) {
            if (false == user_ini_space_path($f)) {
                $ldloc['errors'][ERROR_INI_NOT_FOUND] = "安装向导不能找到或读取系统的ini文件，在该共享服务器上不能继续进行安装。";
            } else {
                $ldloc['errors'][ERROR_INI_USER_INI_NOT_FOUND] = $ldloc['errors'][ERROR_INI_NOT_FOUND];
            }
        } elseif (array_key_exists(ERROR_INI_NOT_FOUND,$ldloc['errors'])) {
            $ini_files_not_found[] = $f;
        }
        if (!empty($ldloc['location'])) {
            break;
        }
    }
    if (!empty($ini_files_not_found)) {
        $plural = (count($ini_files_not_found) > 1)?"s":"";
        $ldloc['errors'][ERROR_INI_NOT_FOUND] = "向导没有找到以下配置文件: " . join(',',$ini_files_not_found);
        if (is_restricted_server()) {
            $ldloc['errors'][ERROR_INI_NOT_FOUND] .= "<br>可能是服务器进行了限制。";
        }
    }
    if (empty($ldloc['location'])) {
        $ldloc['errors'][ERROR_INI_ZE_LINE_NOT_FOUND] = "配置文件没有找到Zend扩展。";
    }
    return $ldloc;
}

function find_loader_filesystem()
{
    $ld_inst_dir = loader_install_dir(find_server_type());
    $loader_name = get_loader_name();
    $suggested_loader_path = $ld_inst_dir . DIRECTORY_SEPARATOR . $loader_name;
    if (@file_exists($suggested_loader_path)) {
        $location = $suggested_loader_path;
    } elseif (@file_exists($loader_name)) {
        $location = @realpath($loader_name);
    } else {
        $ld_loc = get_loader_location();
        if (@file_exists($ld_loc)) {
            $location = $ld_loc;
        } else {
            $location = '';
        }
    }
    return $location;
}

function find_loader($search_directories_if_not_ini = false)
{
    $sysinfo = get_sysinfo();
    $php_ini = $sysinfo['PHP_INI'];
    $rtl_path = get_runtime_loading_path_if_applicable();
    $location = '';
    $errors = array();

    if (!empty($rtl_path)) {
        $location = $rtl_path;
    } else {
        $loader_ini = scan_inis_for_loader();
        $location = $loader_ini['location'];
        $errors = $loader_ini['errors'];
    }
    if (empty($location) && (empty($errors) || $search_directories_if_not_ini)) {
        $errors = array(); 
        $location = find_loader_filesystem();
        if (empty($location)) {
            $errors[ERROR_LOADER_NOT_FOUND] = '加密程序不在php扩展的默认路径。';
        }
    }
    if (!empty($errors)) {
        return $errors;
    } else {
        return $location;
    }
}

function zend_extension_line_start()
{
    $sysinfo = get_sysinfo();
    $is_53_or_later = is_php_version_or_greater(5,3);
    return (is_bool($sysinfo['THREAD_SAFE']) && $sysinfo['THREAD_SAFE'] && !$is_53_or_later ? 'zend_extension_ts' : 'zend_extension');
}

function ioncube_loader_version_information()
{
    $old_version = true;
    $liv = "";
    $lv = "";
    $mv = 0;
    if (function_exists('ioncube_loader_iversion')) {
        $liv = ioncube_loader_iversion();
        $lv = sprintf("%d.%d.%d", $liv / 10000, ($liv / 100) % 100, $liv % 100);

        $latest_version =  get_latestversion();

        $lat_parts = explode('.',$latest_version);
        $cur_parts = explode('.',$lv);

        if (($cur_parts[0] > $lat_parts[0]) || 
            ($cur_parts[0] == $lat_parts[0] && $cur_parts[1] > $lat_parts[1]) ||
             ($cur_parts[0] == $lat_parts[0] && $cur_parts[1] == $lat_parts[1] && $cur_parts[2] >= $lat_parts[2])) {
            $old_version = false;
        } else {
            $old_version = $latest_version;
        }
        $mv = $cur_parts[0];
    }
    return array($lv,$mv,$old_version);
}

function default_loader_version_info()
{
    return array();
}

function get_loader_version_info()
{
    return get_remote_session_value('loader_version_info',LOADER_LATEST_VERSIONS_URL,'default_loader_version_info');
}

function calc_dirname()
{
    $platform_info = get_platforminfo();
    $loader = get_loaderinfo();
    $multiple_os_versions = false;
    if (is_array($loader) && array_key_exists('osvariants',$loader) && is_array($loader['osvariants'])) {
        $versions = array_values($loader['osvariants']);
        $multiple_os_versions = !empty($versions[0]);
    }
    if ($multiple_os_versions) {
        list($osvar,$exact_match) = get_reqd_version($loader['osvariants']);
    } else {
        $osvar = null;
    }
    $dirname = '';
    foreach ($platform_info as $p) {
        if ($p['os'] == $loader['oscode'] && $p['arch'] == $loader['arch'] && (empty($osvar) || $p['os_mod'] == "_" . $osvar)) {
            $dirname = $p['dirname'];
            break;
        }
    }
    return $dirname;
}

function calc_loader_latest_version()
{
    $lv_info = get_loader_version_info();
    $latest_version = RECENT_LOADER_VERSION;
    if (!empty($lv_info)) {
        $dirname = calc_dirname();
      
        if (!empty($dirname)) {
            $compiler_specific_version = false;
            if (is_ms_windows()) {
                $sys = get_sysinfo();
                $phpc = strtolower($sys['PHP_COMPILER']);
                if (!empty($phpc)) {
                    $dirname_comp = $dirname . "_" . $phpc;
                    if (array_key_exists($dirname_comp,$lv_info)) {
                        $latest_version = $lv_info[$dirname_comp];
                        $compiler_specific_version = true;
                    }
                }
            }
            if (!$compiler_specific_version && array_key_exists($dirname,$lv_info)) {
                $latest_version = $lv_info[$dirname];
            }
        } 
    }
    return $latest_version;
}

function get_latestversion()
{
    static $latest_version;

    if (empty($latest_version)) {
        $latest_version = calc_loader_latest_version();
    }
    return $latest_version;
}


function runtime_loader_location()
{
    $loader_path = false;
    $ext_path = extension_dir_path();
    if ($ext_path !== false) {
        $id = $ext_path;
        $here = dirname(__FILE__);
        if (isset($id[1]) && $id[1] == ':') {
            $id = str_replace('\\','/',substr($id,2));
            $here = str_replace('\\','/',substr($here,2));
        }
        $rd=str_repeat('/..',substr_count($id,'/')).$here.'/';
        $i=strlen($rd);

        $loader_loc = DIRECTORY_SEPARATOR . basename($here) . DIRECTORY_SEPARATOR . get_loader_name();
        while($i--) {
            if($rd[$i]=='/') {
                $loader_path = runtime_location_exists($ext_path,$rd,$i,$loader_loc);
                if ($loader_path !== false) {
                    break;
                }
            }
        }

        if (!$loader_path && !empty($loader_loc) && @file_exists($loader_loc)) {
            $loader_path = basename($loader_loc);
        }
    }
    return $loader_path;
}

function runtime_location_exists($ext_dir,$path_str,$sep_pos,$loc_name)
{
    $sub_path = substr($path_str,0,$sep_pos);
    $lp = $sub_path . $loc_name;
    $fqlp = $ext_dir.$lp;

    if(@file_exists($fqlp)) {
        return $lp;
    } else {
        return false;
    }
}

function runtime_loading_is_possible() {
    return !((is_php_version_or_greater(5,2,5)) || is_restricted_server() || !ini_get('enable_dl') || !function_exists('dl') || function_is_disabled('dl') || threaded_and_not_cgi());
}

function shared_and_runtime_loading()
{
    return (find_server_type() == SERVER_SHARED && empty($_SESSION['use_ini_method']) && runtime_loading_is_possible());
}

function get_valid_runtime_loading_path($ignore_loading_check = false)
{
    if ($ignore_loading_check || runtime_loading_is_possible()) {
        return runtime_loader_location();
    } else {
        return false;
    }
}

function runtime_loading($rtl_path = null)
{
    if (empty($rtl_path)) {
        $rtl_path = get_valid_runtime_loading_path();
    }
    if (!empty($rtl_path) && @dl($rtl_path)) {
        return $rtl_path;
    } else {
        return false;
    }
}

function get_runtime_loading_path_if_applicable()
{
    $rtl = null;
    if (shared_and_runtime_loading()) {
        $rtl = get_valid_runtime_loading_path();
    }
    return $rtl;
}

function try_runtime_loading_if_applicable()
{
    $rtl_path = get_runtime_loading_path_if_applicable();
    if (!empty($rtl_path)) {
        return runtime_loading($rtl_path);
    } else {
        return $rtl_path;
    }
}

function runtime_loading_instructions()
{
    $default = get_default_address();
    echo '<h4>”运行时加载程序“说明</h4>';
    echo '<div class=panel>';
    echo '<p>在共享服务器上，加密程序可以通过“运行时加载技术”安装，';
    echo " (<a href=\"{$default}&amp;manual=1\">如果您的程序<strong>不是</strong>运行在共享服务器，请单击这里</a>.)</p>";

    if ('.' == extension_dir()) {
        $dirphrase = is_ms_windows()?'folder':'directory';
        echo "注意：加密程序<em>必须</em>与第一个加密文件在同一个目录下。";
    }
    echo '<ol>';
    loader_download_instructions(); 
    $loader_dir = loader_install_instructions(SERVER_SHARED,dirname(__FILE__));
    shared_test_instructions();
    echo '</ol>';
    echo '</div>';
}

function runtime_loading_errors()
{
    $errors = array();
    $ext_path = extension_dir_path();
    if (false === $ext_path) {
        $errors[ERROR_RUNTIME_EXT_DIR_NOT_FOUND] = "扩展所在的目录没有找到。";
    } else {
        $expected_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . get_loader_name();
        if (!@file_exists($expected_file)) {
            $errors[ERROR_RUNTIME_LOADER_FILE_NOT_FOUND] = "加密程序应该放在$expected_file，但是没有发现。";
        } else {
            $errors = loader_compatibility_test($expected_file);
        }
    }
    return $errors;
}


function windows_package_name()
{
    $sys = get_sysinfo();
    return (LOADERS_PACKAGE_PREFIX . 'win' . '_' . ($sys['THREAD_SAFE']?'':'nonts_') . strtolower($sys['PHP_COMPILER']) .  '_' . 'x86');
}

function unix_package_name()
{
    $sysinfo = get_sysinfo();
    $loader = get_loaderinfo();
    $multiple_os_versions = false;
    if (is_array($loader) && array_key_exists('osvariants',$loader) && is_array($loader['osvariants'])) {
        $versions = array_values($loader['osvariants']);
        $multiple_os_versions = !empty($versions[0]);
    }
    if ($multiple_os_versions) {
        list($reqd_version,$exact_match) = get_reqd_version($loader['osvariants']);
        if ($reqd_version) {
            $basename = LOADERS_PACKAGE_PREFIX . $loader['oscode'] . '_' . $reqd_version . '_' . $loader['arch'];
        } else {
            $basename = "";
        }
    } else {
        $basename = LOADERS_PACKAGE_PREFIX . $loader['oscode'] . '_' . $loader['arch'];
    }
    return array($basename,$multiple_os_versions);
}

function loader_download_instructions()
{
    $sysinfo = get_sysinfo();
    $loader = get_loaderinfo();
    $multiple_os_versions = false;

    if (is_ms_windows()) {
        if (is_bool($sysinfo['THREAD_SAFE'])) {
            $download_str = '<li>下载下列Windows ' . $sysinfo['PHP_COMPILER'];
            if (!$sysinfo['THREAD_SAFE']) {
                $download_str .= ' non-TS';
            }
            $download_str .= ' x86 Loaders的一个压缩包:';
            echo $download_str;
            $basename = windows_package_name();
            echo make_archive_list($basename,array('zip','ipf.zip'));
            echo "<p>请注意：无论是从本地PC直接安装在Windows机器上，或上传到您的服务器，该MS Windows Installer都是通用的。<br>";
            echo '也可以从<a href="' . LOADERS_PAGE . '" target="loaders">' . LOADERS_PAGE . '</a>下载一个Loader包。';
        } else {
            echo '<li>从<a href="' . LOADERS_PAGE  . '" target=loaders>这里</a>下载一个Windows Loaders包。 如果PHP禁用线程安全模式，使用Windows non-TS Loaders。';
        }
    } else {
        list($basename,$multiple_os_versions) = unix_package_name(); 
        if ($basename == "") {
            echo '<li>从<a href="' . LOADERS_PAGE . '" target="loaders">这里</a>下载一个 ' . $loader['osname'] . ' ' . $loader['arch'] . ' Loaders包.';
            echo "<br>您的系统可能是${loader['wordsize']}位${loader['osnamequal']}。如果该程序在${loader['osname']}不可用，更早的版本的Loaders应该能够运行。注意：您需要重新安装兼容库。";
            echo '<br>如果你不能找到一个合适的Loaders，请联系我们 <a href="'. SUPPORT_SITE . '">获得支持和帮助</a>.';
        } else {
            echo '<li>下载一个' . $loader['osnamequal'] . ' ' . $loader['arch'] . '的Loaders包：'; 
            if (SERVER_SHARED == find_server_type()) {
                $archives = array('zip','tar.gz','tar.bz2','ipf.zip');
            } else {
                $archives = array('tar.gz','tar.bz2','ipf.zip');
            }
            echo make_archive_list($basename,$archives);
            echo "<p>注意：确保Windows installer在远程${loader['osname']}服务器上可用。<br>";
            echo "</p>";
            if ($multiple_os_versions && !$exact_match) {
                echo "<p>注意：您可能需要重新安装${loader['osname']}兼容库。</p>";
            }
        }
    }

    echo '</li>';
}

function ini_dir()
{
    $sysinfo = get_sysinfo();
    $parent_dir = '';
    if (!empty($sysinfo['PHP_INI'])) {
        $parent_dir = dirname($sysinfo['PHP_INI']);
    } else {
        $parent_dir = $_SERVER["PHPRC"];
        if (@is_file($parent_dir)) {
            $parent_dir = dirname($parent_dir);
        }
    }
    return $parent_dir;
}

function unix_install_dir()
{
    $ext_dir = extension_dir_path();
    $cur_dir = @realpath('.');
    if (empty($ext_dir) || $ext_dir == $cur_dir) {
        $loader_dir = UNIX_SYSTEM_LOADER_DIR;
    } else {
        $loader_dir = $ext_dir;
    }
    return $loader_dir;
}

function windows_install_dir()
{
    $sysinfo = get_sysinfo();
    if ($sysinfo['SS'] == 'IIS') {
        if (false === ($ext_dir = extension_dir_path())) {
            $parent_dir = ini_dir();
            $ext_dir = $parent_dir . '\\ext';
            if (!empty($parent_dir) && @file_exists($ext_dir)) {
                $loader_dir = $ext_dir;
            } else {
                $loader_dir = $_SERVER['windir'] . '\\' . WINDOWS_IIS_LOADER_DIR;
            }
        } else {
            $loader_dir = $ext_dir;
        }
    } else {
        $parent_dir = ini_dir();
        $loader_dir = $parent_dir . '\\' . 'ioncube';
    }
    return $loader_dir;
}

function loader_install_dir($server_type)
{
    if (SERVER_SHARED == $server_type && own_php_ini_possible()) {
        $loader_dir = get_default_loader_dir_webspace();
    } elseif (is_ms_windows()) {
        $loader_dir = windows_install_dir();
    } else {
        $loader_dir = unix_install_dir();
    }
    return $loader_dir;
}

function writeable_directories()
{
    $root_path = @realpath($_SERVER['DOCUMENT_ROOT']);
    $above_root_path = @realpath($_SERVER['DOCUMENT_ROOT'] . "/..");
    $root_path_cgi_bin = @realpath($_SERVER['DOCUMENT_ROOT'] . "/cgi-bin");
    $above_root_cgi_bin = @realpath($_SERVER['DOCUMENT_ROOT'] . "/../cgi-bin");

    $paths = array();
    foreach (array($root_path,$above_root_path,$root_path_cgi_bin,$above_root_cgi_bin) as $p) {
        if (@is_writeable($p)) {
            $paths[] = $p;
        }
    }
    return $paths;
}

function loader_install_instructions($server_type,$loader_dir = '')
{
    if (empty($loader_dir)) {
        $loader_dir = loader_install_dir($server_type);
    }
    if (SERVER_LOCAL == $server_type) {
        echo "<li>将Loader文件放到<code>$loader_dir</code></li>";
    } else {
        echo "<li>将Loaders上传到服务器，并在<code>$loader_dir</code>安装</li>";
    }
    return $loader_dir;
}

function zend_extension_lines($loader_dir)
{
    $zend_extension_lines = array();
    $sysinfo = get_sysinfo();
    $qt = (is_ms_windows()?'"':'');
    $loader = get_loaderinfo();

    if (!is_bool($sysinfo['THREAD_SAFE']) || !$sysinfo['THREAD_SAFE']) {
        $path = $qt . $loader_dir . DIRECTORY_SEPARATOR . $loader['file'] . $qt;
        $zend_extension_lines[] = "zend_extension = " . $path;
    }
    if ((!is_bool($sysinfo['THREAD_SAFE']) && !is_php_version_or_greater(5,3)) || $sysinfo['THREAD_SAFE']) {
        $line_start = is_php_version_or_greater(5,3)?'zend_extension':'zend_extension_ts';
        $path = $qt . $loader_dir . DIRECTORY_SEPARATOR . $loader['file_ts'] . $qt;
        $zend_extension_lines[] = $line_start . " = " . $path;
    }
    return $zend_extension_lines;
}

function user_ini_base()
{
    $doc_root_path = realpath($_SERVER['DOCUMENT_ROOT']);
    $above_root_path = @realpath($_SERVER['DOCUMENT_ROOT'] . "/..");
    if (!empty($above_root_path) && @is_writeable($above_root_path)) {
        $start_path = $above_root_path;
    } else {
        $start_path = $doc_root_path;
    }
    return $start_path;
}

function user_ini_space_path($file)
{
    $user_base = user_ini_base();
    $fpath = @realpath($file);
    if (!empty($fpath) && (0 === strpos($fpath,$user_base))) {
        return $fpath;
    } else {
        return false;
    }
}

function default_ini_path()
{
    return (realpath($_SERVER['DOCUMENT_ROOT']));
}

function shared_ini_location()
{
    $phprc = getenv('PHPRC');
    if (!empty($phprc)) {
        $phprc_path = user_ini_space_path($phprc);
        if (false !== $phprc_path) {
            return $phprc_path;
        } else {
            return default_ini_path();
        }
    } else {
        return default_ini_path();
    }
}


function zend_extension_instructions($server_type,$loader_dir)
{
    $sysinfo = get_sysinfo();
    $base = get_base_address();
    $editing_ini = true;

    $php_ini_name = ini_file_name();

    if (isset($sysinfo['PHP_INI']) && @file_exists($sysinfo['PHP_INI'])) {
        $php_ini_path = $sysinfo['PHP_INI'];
    } else {
        $php_ini_path = '';
    }

    if (is_bool($sysinfo['THREAD_SAFE'])) {
        $kwd = zend_extension_line_start();
    } else {
        $kwd = 'zend_extension/zend_extension_ts';
    }

    $server_type_code = server_type_code();

    $zend_extension_lines = zend_extension_lines($loader_dir);

    if (SERVER_SHARED == $server_type && own_php_ini_possible()) {
        $ini_dir = shared_ini_location();
        $php_ini_path = $ini_dir . DIRECTORY_SEPARATOR . $php_ini_name;
        if (@file_exists($php_ini_path)) {
            $edit_line = "<li>Edit the <code>$php_ini_name</code> in the <code>$ini_dir</code> directory";
            if (zend_extension_line_missing($php_ini_path) && @is_writeable($php_ini_path) && @is_writeable($ini_dir)) {
                if (function_exists('file_get_contents')) {
                    $ini_strs = @file_get_contents($php_ini_path);
                } else {
                    $lines = @file($php_ini_path);
                    $ini_strs = join(' ',$lines);
                }
                $fh = @fopen($php_ini_path,"wb");
                if ($fh !== false) {
                    foreach ($zend_extension_lines as $zl) {
                        fwrite($fh,$zl . PHP_EOL);
                    }
                    fwrite($fh,$ini_strs);
                    fclose($fh);
                    $editing_ini = false;
                    echo "<li>$php_ini_path下的php.ini已经修改，包含了对ionCube loader的引用。";
                } else {
                    echo $edit_line;
                }
            } else {
               echo $edit_line;
            }
        } else {
            $download_ini_file = "<li><a href=\"$base&amp;page=phpconfig&amp;ininame=$php_ini_name&amp;stype=$server_type_code&amp;download=1&amp;prepend=1\">保存<code>$php_ini_name</code></a>，并上传到<code>$ini_dir</code> (服务器的完整路径)。";
            if (@is_writeable($ini_dir)) {
                $fh = @fopen($php_ini_path,"wb");
                if ($fh !== false) {
                    foreach ($zend_extension_lines as $zl) {
                       fwrite($fh,$zl . PHP_EOL);
                    }
                    if (!empty($sysinfo['PHP_INI']) && is_readable($sysinfo['PHP_INI'])) {
                        if (function_exists('file_get_contents')) {
                           $ini_strs = @file_get_contents($sysinfo['PHP_INI']);
                        } else {
                           $lines = @file($sysinfo['PHP_INI']);
                           $ini_strs = join(' ',$lines);
                        }
                        fwrite($fh,$ini_strs);
                    }
                    fclose($fh); 
                    echo "<li><code>$php_ini_name</code>已经在<code>$ini_dir</code>创建。";
                } else {
                    echo $download_ini_file;
                }
            } else {
                echo $download_ini_file;
            }
            $editing_ini = false;
        }
    } elseif (!empty($sysinfo['PHP_INI'])) {
        if (empty($sysinfo['PHP_INI_DIR'])) {
            echo "<li>Edit the file <code>${sysinfo['PHP_INI']}</code>";
        } else {
            $php_ini_path = find_additional_ioncube_ini();
            if (empty($php_ini_path)) {
                $php_ini_name = ADDITIONAL_INI_FILE_NAME;
                echo "<li><a href=\"$base&amp;page=phpconfig&amp;download=1&amp;newlinesonly=1&amp;ininame=$php_ini_name&amp;stype=$server_type_code\">保存 $php_ini_name</a>，拷贝到ini目录, <code>${sysinfo['PHP_INI_DIR']}</code>";
                $editing_ini = false;
            } else {
                $php_ini_name = basename($php_ini_path);
                echo "<li>编辑文件<code>$php_ini_path</code>";
            }
        }
    } else {
        echo "<li>编辑系统文件<code>$php_ini_name</code>";
    }
    if ($editing_ini) {
        echo " 并且在其它$kwd行<b>之前</b>，确保下面的内容被包含：<br>";
        foreach ($zend_extension_lines as $zl) {
            echo "<code>$zl</code><br>";
        }
        if (!empty($php_ini_path)) {
            if (zend_extension_line_missing($php_ini_path)) {
                echo "<a>另外，下载<a href=\"$base&amp;page=phpconfig&amp;ininame=$php_ini_name&amp;stype=$server_type_code&amp;download=1&amp;prepend=1\">新的 $php_ini_name 文件</a>，替换当前的<code>$php_ini_path</code> 文件。"; 
            }
        }
    }
    echo '</li>';
}

function server_restart_instructions()
{
    $sysinfo = get_sysinfo();
    $base = get_base_address();

    if ($sysinfo['SS']) {
        echo "<li>重启${sysinfo['SS']} 服务器程序.</li>";
    } else {
        echo "<li>重启服务器程序。</li>";
    }

    echo "<li>服务器程序重启完毕后，<a href=\"$base&amp;page=loader_check\" onclick=\"showOverlay();\">点击这里测试Loader</a>。</li>";

    if ($sysinfo['SS'] == 'Apache' && !is_ms_windows()) {
        echo '<li>如果Loader安装失败，检查Apache错误日志，查看<a target="unix_errors" href="'. UNIX_ERRORS_URL . '">Unix相关错误</a>。</li>';
    }
}

function shared_test_instructions()
{
    $base = get_base_address();
    echo "<li><a href=\"$base&amp;page=loader_check\" onclick=\"showOverlay();\">点击这里测试Loader</a>。</li>";
}

function link_to_php_ini_instructions()
{
    $default = get_default_address();
    echo "<p><a href=\"{$default}&amp;stype=s&amp;ini=1\">请点击这里查看使用php.ini方式的介绍</a>。</p>";
}

function php_ini_instruction_list($server_type)
{
    echo '<h4>安装说明</h4>';
    echo '<div class=panel>';
    echo '<ol>';

    loader_download_instructions(); 
    $loader_dir = loader_install_instructions($server_type);
    zend_extension_instructions($server_type,$loader_dir);
    if ($server_type != SERVER_SHARED || !own_php_ini_possible()) {
        server_restart_instructions();
    } else {
        shared_test_instructions();
    } 
    echo '</ol>';
    echo '</div>';
}

function php_ini_install_shared($give_preamble = true)
{
    $php_ini_name = ini_file_name();
    $default = get_default_address();
    if ($give_preamble) {
        echo "<p>在您的<strong>共享</strong>服务器上, 建议使用<code>$php_ini_name</code>配置文件安装Loader。";
        echo " (<a href=\"{$default}&amp;manual=1\">如果服务器<strong>不是</strong>共享服务器，请单击这里</a>.)</p>";
    }

    if (own_php_ini_possible()) {
        echo '<p>您可能可以使用自己账号专有的PHP配置文件，进行配置。</p>';
    } else {
        echo "<p>可能您不能使用<code>$php_ini_name</code>文件安装ioncube loader。您的服务器提供商或者系统管理员应该能够为您安装。请将下面的介绍告诉他们。</p>";
    }

    php_ini_instruction_list(SERVER_SHARED);
}

function php_ini_install($server_type_desc = null, $server_type = SERVER_DEDICATED, $required = true)
{
    $php_ini_name = ini_file_name();
    $default = get_default_address();

    echo '<p>';
    if ($server_type_desc) {
        echo "在一个<strong> $server_type_desc </strong>服务器";
    } else {
        echo "在本地服务器";
    }

    if ($required) {
        echo "你应该使用配置文件<code>$php_ini_name</code>，来安装Ioncube Loader。";
    } else {
        echo "推荐使用配置文件<code>$php_ini_name</code>安装Ioncube Loader。";
    }
    if ($server_type_desc) {
        echo " (<a href=\"{$default}&amp;manual=1\">如果不是一个 $server_type_desc 服务器，点击此链接</a>.)";
    }
    echo '</p>';
      
    php_ini_instruction_list($server_type);
}

function help_resources($error_list = array())
{
    $base = get_base_address();
    $server_type_code = server_type_code();
    $server_type = find_server_type();
    $sysinfo = get_sysinfo();
    $resources = array(
            '<a target="_blank" href="' . LOADERS_FAQ_URL . '">ionCube Loaders FAQ</a>',
            '<a target="_blank" href="' . LOADER_FORUM_URL . '">ionCube Loader论坛</a>'
        );
    if (SERVER_SHARED != $server_type || own_php_ini_possible(true)) {
        $resources[2] = '<a target="_blank" href="' . SUPPORT_SITE . htmlentities('index.php?department=3&subject=ionCube+Loader+installation+problem&message='. support_ticket_information($error_list)) . '">通过提高我们的服务台支持票</a>';
    } 
    if (SERVER_LOCAL == $server_type) {
        $resources[2] .= "<br><span id=\"download-archive\">支持工单创建以后，请";
        $resources[2] .= " <a href=\"$base&amp;page=system_info_archive&amp;stype=$server_type_code\">单击这里获得系统信息包</a>。<br>";
        $resources[2] .= "请将系统信息包和创建的支持工单绑定在一起。</span>";
    }
    if (SERVER_SHARED == $server_type && own_php_ini_possible(true) && !user_ini_space_path($sysinfo['PHP_INI'])) {
        $resources[3] = '<strong>请检查您是否可以创建一个php.ini，来覆盖系统默认的php.ini。</strong>';
    }
    return $resources;
}

function system_info_temporary_files()
{
    $tmpfname_ini = tempnam("/tmp", "INI");
    $tmpfname_ini .= ".ini";
    $fh_ini = @fopen($tmpfname_ini,'wb');
    if ($fh_ini) {
        $config = all_ini_contents();
        fwrite($fh_ini,$config);
        fclose($fh_ini);
    } else {
        $tmpfname_ini = '';
    }

    $tmpfname_pinf = tempnam("/tmp", "PIN");
    $tmpfname_pinf .= ".html";
    $fh_pinfo = @fopen($tmpfname_pinf,'wb');
    if ($fh_pinfo) {
        ob_start();
        @phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();
        fwrite($fh_pinfo,$pinfo);
        fclose($fh_pinfo);
    } else {
        $tmpfname_pinf = '';
    }

    $tmpfname_add = tempnam("/tmp", "ADD");
    $tmpfname_add .= ".html";
    $fh_add = @fopen($tmpfname_add,'wb');
    if ($fh_add) {
        ob_start();
        extra_page();
        $extra = ob_get_contents();
        ob_end_clean();
        fwrite($fh_add,$extra);
        fclose($fh_add);
    } else {
        $tmpfname_add = '';
    }

    if (empty($tmpfname_ini) || empty($tmpfname_pinf) || empty($tmpfname_add)) {
        return (array());
    } else {
        return (array('ini'           =>   $tmpfname_ini,
                      'phpinfo'       =>   $tmpfname_pinf,
                      'additional'    =>   $tmpfname_add));
    }
}

function system_info_archive_page()
{
    info_disabled_check();
    $loader = find_loader(true);
    if (is_string($loader)) {
        $loader_file = $loader;
    } else {
        $loader_file = '';
    }
    $all_files = system_info_temporary_files();
    if (!empty($all_files)) {
        if (!empty($loader_file)) {
            $all_files['loader'] = $loader_file;
        }
        $archive_name =  tempnam('/tmp',"ARC");
        if (extension_loaded('zip')) {
            $archive_name .= '.zip';
            $zip = @new ZipArchive();
            $mode = @constant("ZIPARCHIVE::OVERWRITE");
            if (!$zip || $zip->open($archive_name, $mode)!==TRUE) {
                $archive_name = '';
            } else {
                foreach($all_files as $f) {
                    $zip->addFile($f,basename($f));
                }
                $zip->close();
            }
        } elseif (extension_loaded('zlib') && !is_ms_windows()) {
            $tar_name = $archive_name . ".tar";
            $all_files_str = join(' ',$all_files);
            $script = "tar -chf $tar_name $all_files_str";
            $result = @system($script,$retval);
            if ($result !== false) {
                $archive_name = $tar_name . '.gz';
                $zp = gzopen($archive_name,"w9");
                $tar_contents = get_file_contents($tar_name);
                gzwrite($zp,$tar_contents);
                gzclose($zp);
            } else {
                $archive_name = '';
            }
        } else {
            $archive_name = '';
        }
    } else {
        $archive_name = '';
    }
    if ($archive_name) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='. $archive_name);
        @readfile($archive_name);
    } else {
        $self = get_self();
        $base = get_base_address();
        $server_type_code = server_type_code();
        heading();
        echo "<p>没有创建可下载的系统信息包。<br> 
            <strong>请保存下面的这些，然后将它们绑定到支持工单：</strong></p>"; 
        echo "<ul>";
        echo "<li><a href=\"$base&amp;page=phpinfo\" target=\"phpinfo\">phpinfo()</a></li>";
        echo "<li><a href=\"$base&amp;page=phpconfig\" target=\"phpconfig\">config</a></li>";
        echo "<li><a href=\"$base&amp;page=extra&amp;stype=$server_type_code\" target=\"extra\">附加信息</a></li>";
        echo "<li><a href=\"$self?page=loaderbin\">loader file</a></li>";
        echo "</ul>";
        footer(true);
    }
}

function support_ticket_information($error_list = array())
{
    $sys = get_sysinfo();
    $ld = get_loaderinfo();

    $ticket_strs = array();
    $ticket_strs[] = "PLEASE DO NOT REMOVE THE FOLLOWING INFORMATION\r\n";
    $ticket_strs[] = "==============\r\n";
    if (!empty($error_list)) {
        $ticket_strs[] = "[hr]";
        $ticket_strs[] = "ERRORS";
        $ticket_strs[] = "[table]";
        $ticket_strs[] = '[tr][td]' . join('[/td][/tr][tr][td]',$error_list) . '[/td][/tr]';
        $ticket_strs[] = "[/table]";
    }
    $ticket_strs[] = "[hr]";
    $ticket_strs[] = "SYSTEM INFORMATION";
    $info_lines = array();
    $info_lines["Wizard version"] = script_version();
    $info_lines["PHP uname"] = $ld['uname'];
    $info_lines["Machine architecture"] = $ld['arch'];
    $info_lines["Word size"] = $ld['wordsize'];
    $info_lines["Operating system"] = $ld['osname'] . ' ' . $ld['osver'];
    if (selinux_is_enabled() || possibly_selinux()) {
        $info_lines["Security enhancements"] = "SELinux";
    } elseif (grsecurity_is_enabled()) {
        $info_lines["Security enhancements"] = "Grsecurity";
    } else {
        $info_lines["Security enhancements"] = "None";
    }
    $info_lines["PHP version"] = PHP_VERSION; 
    if ($sys['DEBUG_BUILD']) {
        $info_lines["DEBUG BUILD"] = "DEBUG BUILD OF PHP";
    }
    if (!$sys['SUPPORTED_COMPILER']) {
        $info_lines["SUPPORTED PHP COMPILER"] = "FALSE";
        $info_lines["PHP COMPILER"] = $sys['PHP_COMPILER'];
    }
    $info_lines["Is CLI?"] = ($sys['IS_CLI']?"Yes":"No");
    $info_lines["Is CGI?"] = ($sys['IS_CGI']?"Yes":"No");
    $info_lines["Is thread-safe?"] = ($sys['THREAD_SAFE']?"Yes":"No");
    $info_lines["Web server"] = $sys['FULL_SS'];
    $info_lines["Server type"] = server_type_string();
    $info_lines["PHP ini file"] = $sys['PHP_INI'];
    if (!@file_exists($sys['PHP_INI'])) {
        $info_lines["Ini file found"] = "INI FILE NOT FOUND";
    } else {
        if (is_readable($sys['PHP_INI'])) {
            $info_lines["Ini file found"] = "INI FILE READABLE";
        } else {
            $fh = @fopen($sys['PHP_INI'],"rb");
            if ($fh === false) {
                $info_lines["Ini file found"] = "INI FILE FOUND BUT POSSIBLY NOT READABLE";
            } else {
                $info_lines["Ini file found"] = "INI FILE READABLE";
            }
        }
    }
    $info_lines["PHPRC"] = $sys['PHPRC'];
    $loader_path = find_loader();
    if (is_string($loader_path)) {
        $info_lines["Loader path"] =  $loader_path;
        $info_lines["Loader file size"] = filesize($loader_path) . " bytes.";
        $info_lines["Loader MD5 sum"] =  md5_file($loader_path);
    } else {
        $info_lines["Loader path"] =  "LOADER PATH NOT FOUND";
    }
    $server_type_code = server_type_code();
    if (!empty($_SESSION['hostprovider'])) {
      $info_lines['Hosting provider'] = $_SESSION['hostprovider'];
      $info_lines['Provider URL'] = $_SESSION['hosturl'];
    }
    $info_lines["Wizard script path"] = '[url]http://' . $_SERVER["HTTP_HOST"] . get_self() . '?stype='. $server_type_code . '[/url]';
    $ticket_strs[] = "[table]";
    foreach ($info_lines as $h => $i) {
        $value = (empty($i))?'EMPTY':$i;
        $ticket_strs[] = '[tr][td]' . $h . '[/td]' . '[td]' . $value . '[/td][/tr]';
    }
    $ticket_strs[] = '[/table]';
    $ticket_strs[] = '[hr]';
    $ticket_strs[] = "\r\n==============\r\n";
    $ticket_strs[] = "PLEASE ENTER ANY ADDITIONAL INFORMATION BELOW\r\n";

    $support_ticket_str = join('',$ticket_strs);
    return urlencode($support_ticket_str);
}

function wizard_stats_data($page_id)
{
    $data = array();

    try_runtime_loading_if_applicable();
    $sysinfo = get_sysinfo();
    $ldinfo = get_loaderinfo();

    $data['sessionid'] = session_id();
    $data['wizard_version'] = script_version();
    $data['server_type'] = server_type_code();
    $data['hostprovider'] = (isset($_SESSION['hostprovider']))?$_SESSION['hostprovider']:'';
    $data['hosturl'] = (isset($_SESSION['hosturl']))?$_SESSION['hosturl']:'';
    $data['page_id'] = $page_id;
    $data['loader_state'] = (extension_loaded(LOADER_EXTENSION_NAME))?'installed':'failure';
    $data['ini_location'] = $sysinfo['PHP_INI'];
    $data['is_cgi'] = ($sysinfo['IS_CGI'])?"yes":"no";
    $data['is_ts'] = ($sysinfo['THREAD_SAFE'])?"yes":"no";
    $data['arch'] = $ldinfo['arch'];
    $data['php_version'] = PHP_VERSION;
    $data['os'] = $ldinfo['osname'];
    $data['word_size'] = $ldinfo['wordsize'];
    $data['referrer'] =  $_SERVER["HTTP_HOST"] . get_self();

    return $data;
}

function send_stats($page_id = 'default')
{
    $server_type = find_server_type();
    $res = false;

    if (SERVER_LOCAL != $server_type) {
        $stats_data = wizard_stats_data($page_id);

        if (!isset($_SESSION['stats_sent'][$page_id][$stats_data['loader_state']])) {
            $url = WIZARD_STATS_URL;

            if (!empty($stats_data)) {
                if(function_exists('http_build_query')) {
                    $qparams = http_build_query($stats_data);
                } else {
                    $qparams = php4_http_build_query($stats_data);
                }
                $url .= '?' . $qparams;
                $res = remote_file_contents($url);
            }
            $_SESSION['stats_sent'][$page_id][$stats_data['loader_state']] = 1;
        } else {
            $res = true;
        }
    } else {
        $res = 'LOCAL';
    }
    return $res;
}

function os_arch_string_check($loader_str)
{
    $errors = array();
    if (preg_match("/target os:\s*(([^_]+)_([^-]*)-([[:graph:]]*))/i",$loader_str,$os_matches)) {
        $loader_info = get_loaderinfo();
        $dirname = calc_dirname();
        $packed_osname = preg_replace('/\s/','',strtolower($loader_info['osname']));
        if (strtolower($dirname) != $os_matches[1] && $packed_osname != $os_matches[2]) {
            $errors[ERROR_LOADER_WRONG_OS] = "您服务器操作系统" . $loader_info['osname'] . "安装了错误的loader。";
        } else {
            $loader_wordsize = (strpos($os_matches[3],'64') === false)?32:64;
            if ($loader_info['arch'] != ($ap = required_loader_arch($os_matches[3],$loader_info['oscode'],$loader_wordsize))) {
                $err_str = "您服务器上安装了错误的loader。";
                $err_str .= " 您得系统是 " . $loader_info['arch'];
                $err_str .= " 但是该loader是用于 " . $ap . ".";
                $errors[ERROR_LOADER_WRONG_ARCH] = $err_str;
            }
        }
    }
    return $errors;
}

function get_loader_strings($loader_location)
{
    if (function_exists('file_get_contents')) {
        $loader_strs = @file_get_contents($loader_location);
    } else {
        $lines = @file($loader_location);
        $loader_strs = join(' ',$lines);
    }
    return $loader_strs;
}

function loader_system($loader_location)
{
    $loader_system = array();
    $loader_strs = get_loader_strings($loader_location);

    if (!empty($loader_strs)) {

        if (preg_match("/ioncube_loader_.\.._(.)\.(.)\.(..?)(_nonts)?\.dll/i",$loader_strs,$version_matches)) {
            $loader_system['oscode'] = 'win';
            $loader_system['thread_safe'] = (isset($version_matches[4]) && $version_matches[4] == '_nonts')?0:1;
            $loader_system['wordsize'] = 32;
            $loader_system['arch'] = 'x86';
            $loader_system['php_version_major'] = $version_matches[1];
            $loader_system['php_version_minor'] = $version_matches[2];
            if (preg_match("/assemblyIdentity.*version=\"([^.]+)\./",$loader_strs,$compiler_matches)) {
                $loader_system['compiler'] = "VC" . strtoupper($compiler_matches[1]);
            } else {
                $loader_system['compiler'] = 'VC6';
            }
        } elseif (preg_match("/php version:\s*(.)\.(.)\.(..?)(-ts)?/i",$loader_strs,$version_matches)) {
            $loader_system['thread_safe'] = (isset($version_matches[4]) && $version_matches[4] == '-ts')?1:0;
            $loader_system['php_version_major'] = $version_matches[1];
            $loader_system['php_version_minor'] = $version_matches[2];
            if (preg_match("/target os:\s*(([^_]+)_([^-]*)-([[:graph:]]*))/i",$loader_strs,$os_matches)) {
                $loader_system['oscode'] = strtolower(substr($os_matches[2],0,3));
                $loader_system['wordsize'] = (strpos($os_matches[3],'64') === false)?32:64;
                $loader_system['arch'] = required_loader_arch($os_matches[3],$loader_system['oscode'],$loader_system['wordsize']);
                $loader_system['compiler'] = $os_matches[4];
            }
        }
        if (preg_match("/ionCube Loader Version\s+(\S+)/",$loader_strs,$loader_version)) {
            $loader_system['loader_version'] = $loader_version[1];
        } else {
            $loader_system['loader_version'] = 'UNKNOWN';
        }
        if (isset($loader_system['php_version_major'])) {
            $loader_system['php_version'] = $loader_system['php_version_major'] . '.' . $loader_system['php_version_minor'];
        }
    }
    return $loader_system;
}

function loader_compatibility_test($loader_location)
{
    $errors = array();

    $sysinfo = get_sysinfo();
    if (LOADER_NAME_CHECK) {
        $installed_loader_name = basename($loader_location);
        $expected_loader_name = get_loader_name();
        if ($installed_loader_name != $expected_loader_name) {
            $errors[ERROR_LOADER_UNEXPECTED_NAME] = "安装的loader (<code>$installed_loader_name</code>) 不是系统支持的loader (<code>$expected_loader_name</code>) 。 请检查loader与系统是否兼容。";
        }
    }
    if (empty($errors) && !is_readable($loader_location)) {
        $execute_error = "$loader_location下的loader不可读。";
        $execute_error .= "<br>请检查它是否存在或可读。";
        $execute_error .= "<br>请检查目录权限 ";
        $execute_error .= (is_ms_windows()?'folder':'directory') . '.';
        if (($sysinfo['SS'] == 'IIS') || !($sysinfo['IS_CGI'] || $sysinfo['IS_CLI'])) {
            $execute_error .= "<br>请检查服务器是否已经重启了。";
        }
        $execute_error .= ".";
        $errors[ERROR_LOADER_NOT_READABLE] = $execute_error;
    }
    $loader_strs = get_loader_strings($loader_location);
    $phpv = php_version(); 
    if (preg_match("/php version:\s*(.)\.(.)\.(..?)(-ts)?/i",$loader_strs,$version_matches)) {
        if ($version_matches[1] != $phpv['major'] || $version_matches[2]  != $phpv['minor']) {
            $loader_php = $version_matches[1] . "." . $version_matches[2];
            $server_php =  $phpv['major'] . "." .  $phpv['minor'];
            $errors[ERROR_LOADER_PHP_MISMATCH] = "安装的loader用于 PHP $loader_php，但服务器是 PHP $server_php.";
        }
        if (is_bool($sysinfo['THREAD_SAFE']) &&  $sysinfo['THREAD_SAFE'] && !is_ms_windows() && !(isset($version_matches[4]) && $version_matches[4] == '-ts')) {
            $errors[ERROR_LOADER_NONTS_PHP_TS] = "服务器运行的PHP是线程安全的，但是该loader不是线程安全的。";
        } elseif (isset($version_matches[4]) && $version_matches[4] == '-ts' && !(is_bool($sysinfo['THREAD_SAFE']) &&  $sysinfo['THREAD_SAFE'])) {
            $errors[ERROR_LOADER_TS_PHP_NONTS] = "服务器运行的PHP版本是非线程安全的，但该版本的loader是线程安全的。";
        }
    } elseif (preg_match("/ioncube_loader_.\.._(.)\.(.)\.(..?)(_nonts)?\.dll/i",$loader_strs,$version_matches)) {
        if (!is_ms_windows()) {
            $errors[ERROR_LOADER_WIN_SERVER_NONWIN] = "这是一个Windows版本的loader，但服务器好像不是Windows系统。";
        } else {
            if (isset($version_matches[4]) && $version_matches[4] == '_nonts' && is_bool($sysinfo['THREAD_SAFE']) &&  $sysinfo['THREAD_SAFE']) {
                $errors[ERROR_LOADER_WIN_NONTS_PHP_TS] = "该loader版本是非线程安全的，这里需要一个线程安全的版本。";
            } elseif (!(is_bool($sysinfo['THREAD_SAFE']) &&  $sysinfo['THREAD_SAFE']) && !(isset($version_matches[4]) && $version_matches[4] == '_nonts')) {
                $errors[ERROR_LOADER_WIN_TS_PHP_NONTS] = "该loader版本是线程安全的，这里需要一个非线程安全的版本。"; 
            }
            if ($version_matches[1] != $phpv['major'] || $version_matches[2]  != $phpv['minor']) {
                $loader_php = $version_matches[1] . "." . $version_matches[2];
                $server_php =  $phpv['major'] . "." .  $phpv['minor'];
                $errors[ERROR_LOADER_WIN_PHP_MISMATCH] = "已安装的loader用于 PHP $loader_php ，但服务器的PHP版本是 $server_php.";
            }
            if (preg_match("/assemblyIdentity.*version=\"([^.]+)\./",$loader_strs,$compiler_matches)) {
                $loader_compiler = "VC" . strtoupper($compiler_matches[1]);
            } else {
                $loader_compiler = 'VC6';
            }
            if ($loader_compiler != $sysinfo['PHP_COMPILER']) {
                $errors[ERROR_LOADER_WIN_COMPILER_MISMATCH] = "该loader用 $loader_compiler 编译，这里需要用 ${sysinfo['PHP_COMPILER']}编译。";
            }
        }
    } else {
            $errors[ERROR_LOADER_PHP_VERSION_UNKNOWN] = "未能检测服务器的PHP版本 - 请自行安装可用的loader。";
    } 
    $errors += os_arch_string_check($loader_strs);

    return $errors;
}


function shared_server()
{
    if (!$rtl_path = runtime_loading()) {
        if (empty($_SESSION['use_ini_method']) && runtime_loading_is_possible()) {
            runtime_loading_instructions();
        } else {
            php_ini_install_shared();
        }
    } else {
        list($lv,$mv,$newer_version) = ioncube_loader_version_information();
        $phpv = php_version_maj_min();
        echo "<p>适用于 PHP $phpv 的ionCube Loader $lv 已经成功安装。</p>";
        $is_legacy_loader = loader_major_version_instructions($mv);
        if ($is_legacy_loader) {
            loader_upgrade_instructions($lv,$newer_version);
        }
        successful_install_end_instructions($rtl_path);
    }
}

function dedicated_server()
{
    php_ini_install('dedicated or VPS', SERVER_DEDICATED, true);
}

function local_install()
{
    php_ini_install('local',SERVER_LOCAL, true);
}


function unregister_globals()
{
    if (!ini_get('register_globals')) {
        return;
    }

    if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
        die('GLOBALS overwrite attempt detected');
    }

    $noUnset = array('GLOBALS',  '_GET',
                     '_POST',    '_COOKIE',
                     '_REQUEST', '_SERVER',
                     '_ENV',     '_FILES');

    $input = array_merge($_GET,    $_POST,
                         $_COOKIE, $_SERVER,
                         $_ENV,    $_FILES,
                         isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());

    foreach ($input as $k => $v) {
        if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
            unset($GLOBALS[$k]);
        }
    }
}

function clear_session($persist = array())
{
    $persist['not_go_daddy'] = empty($_SESSION['not_go_daddy'])?0:1;
    $persist['use_ini_method'] = empty($_SESSION['use_ini_method'])?0:1;
    $persist['server_type'] = empty($_SESSION['server_type'])?SERVER_UNKNOWN:$_SESSION['server_type'];
    @session_destroy();
    $_SESSION = array();
    $_SESSION['CREATED'] = time();
    $_SESSION = $persist;
}

function info_should_be_disabled()
{
    $elapsed = time() - max(filemtime(__FILE__),filectime(__FILE__));

    return (extension_loaded(LOADER_EXTENSION_NAME) && ($elapsed > WIZARD_EXPIRY_MINUTES * 60));
}

function info_disabled_text()
{
    return "Loader安装成功后，您访问的方法不可用。";
}

function info_disabled_check()
{
    if (info_should_be_disabled()) {
        heading();
        echo info_disabled_text();
        footer(true);
        exit;
    }
}

function run()
{
    unregister_globals();
    if (is_php_version_or_greater(4,3,0)) {
        ini_set('session.use_only_cookies',1);
    }
    $session_ok = @session_start();

    if (!defined('PHP_EOL')) {
        if (is_ms_windows()) {
            define('PHP_EOL',"\r\n");
        } else {
            define('PHP_EOL',"\n");
        }
    }

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > SESSION_LIFETIME_MINUTES * 60 ) {
        clear_session(); 
    }
    if (!isset($_SERVER)) $_SERVER =& $HTTP_SERVER_VARS;

    (php_sapi_name() == 'cli') && die("该脚本只能运行在网站服务器上。\n");

    $page = get_request_parameter('page');
    $host = get_request_parameter('host');
    $clear = get_request_parameter('clear');
    $ini = get_request_parameter('ini');
    $timeout = get_request_parameter('timeout');

    if ($timeout) {
        $_SESSION['timing_out'] = 1;
        $_SESSION['initial_run'] = 0;
    }

    if (!empty($host)) {
        if ($host == 'ngd') {
            $_SESSION['not_go_daddy'] = 1;
        }
    }
    if (!empty($ini)) {
        $_SESSION['use_ini_method'] = 1;
    }

    if (!empty($clear)) {
        clear_session();
        unset($_SESSION['not_go_daddy']);
        unset($_SESSION['use_ini_method']);
        unset($_SESSION['server_type']);
    } else {
        $stype = get_request_parameter('stype');
        $hostprovider = get_request_parameter('hostprovider');
        $hosturl = get_request_parameter('hosturl');
        if (!empty($hostprovider)) {
            $_SESSION['hostprovider'] = $hostprovider;
            $_SESSION['hosturl'] = $hosturl;
        }
        $server_type = find_server_type($stype,false,true);
    }
    if ($session_ok && !$timeout && !isset($_SESSION['initial_run']) && empty($page)) {
        $_SESSION['initial_run'] = 1;
        initial_page();
        @session_write_close();
        exit;
    } else {
        $_SESSION['initial_run'] = 0;
    }

    if (empty($_SESSION['server_type'])) {
        $_SESSION['server_type'] = SERVER_UNKNOWN;
    }

    if (empty($page) || !function_exists($page . "_page")) {
        $page = get_default_page();
    } 

    $fn = "${page}_page";
    $fn();

    @session_write_close();
    exit(0);
}

function wizardversion_page()
{
    $start_time = time();
    $wizard_version_only = get_request_parameter('wizard_only');
    $clear_session_info = get_request_parameter('clear_info');
    if ($clear_session_info) {
        unset($_SESSION['timing_out']);
        unset($_SESSION['latest_wizard_version']);
    }
    $wizard_version = latest_wizard_version();
    $message = '';
    if (false === $wizard_version) {
        $message = "0";
    } elseif (update_is_available($wizard_version)) {
        $message = "$wizard_version";
    } else {
        $message = "1";
    }
    echo $message;
    @session_write_close();
    exit(0);
}

function platforminfo_page()
{
    $message = '';
    $platforms = get_loader_platforms();
    $message = empty($platforms)?0:1;
    echo $message;
    @session_write_close();
    exit(0);
}

function loaderversion_page()
{
    $message = '';
    $loader_versions = get_loader_version_info();
    $message = empty($loader_versions)?0:1;
    echo $message;
    @session_write_close();
    exit(0);
}

function compilerversion_page()
{
    $message = '';
    $compiler_versions = find_win_compilers();
    $message = empty($compiler_versions)?0:1;
    echo $message;
    @session_write_close();
    exit(0);
}

function initial_page()
{
    $self = get_self();
    $start_page = get_default_address(false);
    $stage_timeout = 7000;
    $step_lag = 500;

    echo <<<EOT
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <title>ionCube Loader Wizard</title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <link rel="stylesheet" type="text/css" href="$self?page=css">
        <style type="text/css">
        body {
            height: 100%;
            width: 100%;
        }
        </style>
        <script type="text/javascript">
        var timingOut = 0;
        var xmlHttpTimeout;
        var ajax;
        var statusPar;
        var stage_timeout = $stage_timeout;
        var step_lag = $step_lag;

        function checkNextStep(ajax,expected,continuation) {
            if (ajax.readyState==4 && ajax.status==200)
            {
                clearTimeout(xmlHttpTimeout);
                if (ajax.responseText == expected) {
                   setTimeout('',step_lag);
                   continuation();
                } else {
                   statusPar.innerHTML = 'Unable to check for update<br>script continuing';
                   setTimeout("window.location.href = '$start_page&timeout=1'",1000);
                }
            }
        }

        function getXmlHttp() {
            if (window.XMLHttpRequest) {
                xmlhttp=new XMLHttpRequest();
            } else {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            return xmlhttp;
        }
        var startMainLoaderWizard = function() {
            window.location.href = '$start_page';
        }
        var loaderVersionCheck = function() {
            statusPar.innerHTML = 'Stage 4/4: 获取Loader最新版本';
            var xmlHttp = getXmlHttp();
            xmlHttp.onreadystatechange=function() {
                checkNextStep(xmlHttp,"1",startMainLoaderWizard);
            }
            xmlHttp.open("GET","$self?page=loaderversion",true);
            xmlHttp.send("");
            ajax = xmlHttp;
            xmlHttpTimeout=setTimeout('ajaxTimeout()',stage_timeout);
        }
        var platformCheck = function() {
            statusPar.innerHTML = 'Stage 3/4: 获取平台信息';
            var xmlHttp = getXmlHttp();
            xmlHttp.onreadystatechange=function() {
                checkNextStep(xmlHttp,"1",loaderVersionCheck);
            }
            xmlHttp.open("GET","$self?page=platforminfo",true);
            xmlHttp.send("");
            ajax = xmlHttp;
            xmlHttpTimeout=setTimeout('ajaxTimeout()',stage_timeout);
        }
        var compilerVersionCheck = function() {
            statusPar.innerHTML = 'Stage 2/4: 获取编译器版本';
            var xmlHttp = getXmlHttp();
            xmlHttp.onreadystatechange=function() {
                checkNextStep(xmlHttp,"1",platformCheck);
            }
            xmlHttp.open("GET","$self?page=compilerversion",true);
            xmlHttp.send("");
            ajax = xmlHttp;
            xmlHttpTimeout=setTimeout('ajaxTimeout()',stage_timeout);
        }
        var startChecks = function() {
            statusPar = document.getElementById('status');
            statusPar.innerHTML = 'Stage 1/4: 获取Loader安装向导版本';
            var xmlHttp = getXmlHttp();
            xmlHttp.onreadystatechange=function() {
                checkNextStep(xmlHttp,"1",compilerVersionCheck);
            }
            xmlHttp.open("GET","$self?page=wizardversion",true);
            xmlHttp.send("");
            ajax = xmlHttp;
            xmlHttpTimeout=setTimeout('ajaxTimeout()',stage_timeout);
        }
        function ajaxTimeout(){
           ajax.abort();
           statusPar.innerHTML = 'Cannot reach server<br>script continuing';
           setTimeout("window.location.href = '$start_page&timeout=1'",1000);
        }
        </script>
    </head>
    <body>

    <div id="loading"><script type="text/javascript">document.write('<p>ionCube Loader初始化<br>ionCube Loader 安装向导<br><span id="status"></span></p>');</script><p id="noscript">Your browser does not support JavaScript so the ionCube Loader Wizard initialisation cannot be made now. This script can get the latest loader version information from the ionCube server when you go to the next page.<br>Please choose one of the following. <br>If the script appears to hang please restart the script and choose the "NO" option.<br><br><br><a href="$start_page">YES - my server DOES have internet access</a><br><br><a href="$start_page&timeout=1">NO - my server does NOT have internet access</a></p></div>
    <script type="text/javascript">
        document.getElementById('noscript').style.display = 'none';
        window.onload = startChecks;
    </script>
    </body>
    </html>
EOT;
}

function default_page($loader_extension = LOADER_EXTENSION_NAME)
{
    $self = get_self();
    foreach (array('self') as $vn) {
        if (empty($$vn)) {
            error("Unable to initialise ($vn).");
        }
    }

    heading();

    $wizard_update = check_for_wizard_update(true);

    $rtl = try_runtime_loading_if_applicable();

    $server_type = find_server_type();

    if (extension_loaded($loader_extension) && $server_type != SERVER_UNKNOWN) {
        loader_already_installed($rtl);
    } else {
        loader_not_installed();
    }
    send_stats('default');

    footer($wizard_update);
}

function uninstall_wizard_instructions()
{
    echo '<p><strong>For security reasons we advise that you remove this Wizard script from your server now that the ionCube Loader is installed.</strong></p>';
}

function contact_script_provider_instructions()
{
    echo '<p>Please contact the script provider if you do experience any problems running encoded files.</p>';
}

function may_need_to_copy_ini()
{
    $sys = get_sysinfo();
    if (ini_same_dir_as_wizard() && $sys['IS_CGI']) {
        $dirphrase = is_ms_windows()?'folder':'directory';
        $ini = ini_file_name();
        echo "<p>注意：如果在不同的目录加密的文件解密失败，请将 $ini 文件复制到每个加密文件的目录下。</p>";
    }
}

function successful_install_end_instructions($rtl_path = null)
{
    if (empty($rtl_path)) {
        may_need_to_copy_ini();
    } elseif (is_string($rtl_path)) {
        echo "<p>”运行时加载方式“已在<code>$rtl_path</code>下使用。</p>";
    }
    contact_script_provider_instructions();
    uninstall_wizard_instructions();
}

function loader_major_version_instructions($mv)
{
    if ($mv < LATEST_LOADER_MAJOR_VERSION) {
        echo "<p><strong>已安装的Loader版本不能运行最新版本encoder加密的文件，</strong>";
        echo " 您需要一个版本为 " . LATEST_LOADER_MAJOR_VERSION . " 的loader运行这些文件。</p>";
    }
    return ($mv < LATEST_LOADER_MAJOR_VERSION);
}

function loader_already_installed($rtl = null)
{
    list($lv,$mv,$newer_version) = ioncube_loader_version_information();
    $phpv = php_version_maj_min();
    $php_str = ' for PHP ' . $phpv;
    echo '<div class="success">';
    echo '<h4>Loader已经安装</h4>';
    if ($newer_version) {
        echo '<p>版本为' . $lv . $php_str . '的ionCube loader<strong>已经安装</strong> 但该版本不是最新版本。';
        echo ' 请及时升级到最新版本。</p>';
        $know_latest_version = is_string($newer_version);
        $is_legacy_loader = loader_major_version_instructions($mv);
        echo '</div>';
        loader_upgrade_instructions($lv,$newer_version);
    } else {
        echo '<p>版本为' . $lv . $php_str . '的 ionCube loader已经安装，并能够正确运行加密文件。</p>'; 
        echo '</div>';
        $is_legacy_loader = loader_major_version_instructions($mv,true);
        if ($is_legacy_loader) {
            loader_upgrade_instructions($lv,true);
        }
    }

    successful_install_end_instructions($rtl);
}

function loader_upgrade_instructions($installed_version,$newer_version)
{
    if ($newer_version) {
        echo '<div class="panel">';
        echo '<h4>Loader升级说明</h4>';
        $restart_needed = true;
        $server_type = find_server_type();
        if ($server_type == SERVER_SHARED || $server_type == SERVER_UNKNOWN) {
            $loader_path = find_loader(true);
            if (!is_string($loader_path) || false === user_ini_space_path($loader_path)) {
                $verb_case = ($server_type == SERVER_UNKNOWN)?"may":"will";
                echo "<p>注意：你可能需要管理员权限进行升级。升级完成后服务器需要重启。</p>";
            }
            $restart_needed = false;
        }
        if (is_string($newer_version)) {
            $version_str = "version $newer_version";
        } else {
            $version_str = "a newer version";
        }
        $loader_name =  get_loader_name();
        echo "<p>为了从$installed_version 升级到 $version_str，请将下面安装包的$loader_name替换掉已经存在的同名文件：</p>";
        if (is_ms_windows()) {
            $basename = windows_package_name();
        } else {
            list($basename,$multiple_os_versions) = unix_package_name();
        }
        echo make_archive_list($basename,array('zip','tar.gz'));
        if ($restart_needed) {
            echo "<p>替换掉loader文件后，请重启服务器。</p>";
        }
        echo '</div>';
    }
}

function loader_not_installed()
{
    $loader = get_loaderinfo();
    $sysinfo = get_sysinfo();

    $stype = get_request_parameter('stype');
    $manual_select = get_request_parameter('manual');
    $host_type = find_server_type($stype,$manual_select,true);

    if ($host_type != SERVER_UNKNOWN && is_array($loader) && !$sysinfo['DEBUG_BUILD']) {
        $warnings = server_restriction_warnings();
        if (empty($_SESSION['use_ini_method']) && $host_type == SERVER_SHARED && runtime_loading_is_possible()) {
            $errors = runtime_loading_errors();
        } else {
            $errors = ini_loader_errors();
            $warnings = array_merge($warnings,ini_loader_warnings());
        }
        if (!empty($errors)) {
            echo '<div class="alert">' . '请注意，Ioncube Loader的安装目前存在以下问题：';
            echo make_list($errors,"ul"); 
            echo '</div>';
        }
        if (!empty($warnings)) {
            $addword = empty($errors)?'':'also';
            $plural = (count($warnings)>1)?'s':'';
            echo '<div class="warning">';
            echo "注意以下问题:";
            echo make_list($warnings,"ul"); 
            echo '</div>';
        }
    }
    if (!isset($stype)) {
        echo '<p>文件已经被 <a href="' . ENCODER_URL . '" target=encoder>ionCube PHP Encoder</a>加密保护, 必须安装ionCube Loader组件。</p>';
    }

    if (!is_supported_php_version()) {
        echo '<p>服务器目前运行的PHP版本为 ' . PHP_VERSION . ' ，ionCube Loaders支持该版本，同时还支持PHP4系列 PHP 4.2或更高版本， PHP5系列PHP 5.1 或更高版本。</p>';
    } elseif ($sysinfo['DEBUG_BUILD']) {
         echo '<p>服务器运行的PHP是Debug版本， Loader 不支持Debug版本。请确保使用非debug版本重新配置。</p>'; 
    } elseif (!is_array($loader)) {
        if ($loader == ERROR_WINDOWS_64_BIT) {
            echo '<p>Windows 64位的Loaders是可用的。但是如果你<b>安装并运行32位的PHP</b>，请使用相应32位的loader</p>';
            if ($sysinfo['THREAD_SAFE']) {
                echo '<li>下载下面的32位x86 Windows loader包：';
            } else {
                echo '<li>下载下面的一个32位x86 Windows非线程安全版本的Windows loader包：';
            }
            echo make_archive_list(windows_package_name());
        } else {
            echo '<p>ionCube Loader可能没有符合您操作系统的版本，但是，如果你创建了一个<a href="'  . SUPPORT_SITE . '">支持服务单</a>， 可能会需要更多的帮助， 请在工单中包含该向导的URL。</p>';
        }
    } elseif (!$sysinfo['SUPPORTED_COMPILER']) {
        $supported_compilers = supported_win_compilers();
        $supported_compiler_string = join('/',$supported_compilers);
        echo '<p>目前，ionCube Loader 需要使用' . $supported_compiler_string . '编译的PHP。你服务器的PHP是使用 ' . $sysinfo['PHP_COMPILER'] . '.编译的， 可以从<a href="http://windows.php.net/download/">PHP.net</a>下载合适的编译版本。';
    } else {
        switch ($host_type) {
            case SERVER_SHARED:
                shared_server();
                break;
            case SERVER_DEDICATED:
                dedicated_server();
                break;
            case SERVER_LOCAL:
                local_install();
                break;
            default:
                echo server_selection_form();
                break;
        }
    }
}

function server_selection_form()
{
    $self = get_self();
    $timeout = (isset($_SESSION['timing_out']) && $_SESSION['timing_out'])?1:0;
    $hostprovider = (!empty($_SESSION['hostprovider']))?$_SESSION['hostprovider']:'';
    $hostprovider = htmlspecialchars($hostprovider, ENT_QUOTES, 'UTF-8');
    $hosturl = (!empty($_SESSION['hosturl']))?$_SESSION['hosturl']:'';
    $hosturl =  htmlspecialchars($hosturl, ENT_QUOTES, 'UTF-8');
    $form = <<<EOT
    <p>本向导会教你如何安装Ioncube Loader</p>
    <p>请选择你的Web服务器的类型，然后单击“下一步”。</p>
    <script type=text/javascript>
        function trim(s) {
            return s.replace(/^\s+|\s+$/g,"");
        }
        function input_ok() {
            var l = document.getElementById('local');
            if (l.checked) {
                return true;
            } 

            var s = document.getElementById('shared');
            var d = document.getElementById('dedi');

            if (!s.checked && !d.checked) {
                alert("Please select one of the server types.");
                return false;
            } else {
                var hn = document.getElementById('hostprovider');
                var hu = document.getElementById('hosturl');
                var hostprovider = trim(hn.value);
                var hosturl = trim(hu.value);

                if (!hostprovider || !hosturl) {
                    alert("Please enter both a hosting provider name and their URL.");
                    return false;
                }
                if (hostprovider.length < 4) {
                    alert("The hosting provider name should be at least 4 characters in length.");
                    return false;
                }
                if (!hosturl.match(/[A-Za-z0-9-_]+\.[A-Za-z0-9-_%&\?\/.=]+/)) {
                    alert("The hosting provider URL is invalid.");
                    return false;
                }
                if (hosturl.length < 5) {
                    alert("The hosting provider URL should be at least 5 characters in length.");
                    return false;
                }
            }
            return true;
        }
    </script>
    <form method=GET action=$self>
        <input type="hidden" name="page" value="default">
        <input type="hidden" name="timeout" value="$timeout">
        <input type=radio id=shared name=stype value=s onclick="document.getElementById('hostinginfo').style.display = 'block';"><label for=shared>共享 <small>(例如, FTP访问服务器，并没有为php.ini)</small></label><br>
        <input type=radio id=dedi name=stype value=d onclick="document.getElementById('hostinginfo').style.display = 'block';"><label for=dedi>专用或VPS <small>(全根SSH访问服务器)</small></label><br>
        <div id="hostinginfo" style="display: none">如果你有一个共享或专用的服务器，请填入您的托管服务提供商名字和他们的URL:
            <table>
                <tr><td><label for=hostprovider>服务器提供商名字</label></td><td><input type=text id="hostprovider" name=hostprovider value="$hostprovider"></td></tr>
                <tr><td><label for=hosturl>服务器提供商URL</label></td><td><input type=text id="hosturl" name=hosturl value="$hosturl"></td></tr>
            </table>
        </div>
        <input type=radio id=local name=stype value=l onclick="document.getElementById('hostinginfo').style.display = 'none';"><label for=local>本地安装</label>
        <p><input type=submit value='下一步' onclick="return (input_ok(this) && showOverlay());"></p>
    </form>
EOT;
    return $form;
}

function phpinfo_page()
{
    info_disabled_check();
    if (function_is_disabled('phpinfo')) {
        echo "phpinfo is disabled on this server";
    } else {
        @phpinfo();
    }
}

function loader_check_page($ext_name = LOADER_EXTENSION_NAME)
{
    heading();

    $rtl_path = try_runtime_loading_if_applicable();

    if (extension_loaded($ext_name)) {
        list($lv,$mv,$newer_version) = ioncube_loader_version_information();
        $phpv = php_version_maj_min();
        $php_str = ' for PHP ' . $phpv;
        echo '<div class="success">';
        echo '<h4>Loader 安装成功</h4>';
        echo '<p>ionCube Loader ' . $lv . $php_str . ' <strong>已经安装</strong>，加密文件可以正确运行。';
        if ($newer_version) {
            echo ' 注意：您的ionCube loader是旧的版本。</p>';
            $is_legacy_loader = loader_major_version_instructions($mv);
            echo '</div>';
            loader_upgrade_instructions($lv,$newer_version);
        } else {
            echo '</p>';
            $is_legacy_loader = loader_major_version_instructions($mv);
            echo '</div>';
            if ($is_legacy_loader) {
                loader_upgrade_instructions($lv,true);
            }
        }
        successful_install_end_instructions($rtl_path);
    } else {
        echo '<div class="failure">';
        echo '<h4>Loader安装失败</h4>';
        echo '<p>ionCube Loader<b>没有</b>安装成功。</p>';
        if (!is_null($rtl_path)) {
            echo '<p>运行时加载失败。</p>';
            echo '</div>';
            $rt_errors = runtime_loading_errors();
            if (!empty($rt_errors)) {
                list_loader_errors($rt_errors);
            } 
            link_to_php_ini_instructions();
        } else {
            echo '</div>';
            list_loader_errors();
        }
    }
    send_stats('check');

    footer(true);
}

function ini_loader_errors()
{
    $errors = array();
    if (SERVER_SHARED == find_server_type() && !own_php_ini_possible(true)) {
        $errors[ERROR_INI_USER_CANNOT_CREATE] = "好像您没有权限在共享服务器上创建您自己的ini配置文件，<br><strong>请联系管理员安装ionCube loader。</strong>";
    }
    $loader_loc = find_loader(false);
    if (is_string($loader_loc)) {
        if (!shared_and_runtime_loading()) {
            $sys = get_sysinfo();
            if (empty($sys['PHP_INI'])) {
                $errors[ERROR_INI_NO_PATH] = '找不到配置文件(php.ini)的路径。';
            } elseif (!@file_exists($sys['PHP_INI'])) {
                $errors[ERROR_INI_NOT_FOUND] = 'PHP配置文件(' . $sys['PHP_INI'] .')没有找到。';
            }
        }
        $errors = $errors + loader_compatibility_test($loader_loc);
    } else {
        $errors = $errors + $loader_loc;
        $fs_location = find_loader_filesystem();
        if (!empty($fs_location)) {
            $fs_loader_errors = loader_compatibility_test($fs_location);
            if (!empty($fs_loader_errors)) {
                $errors[ERROR_LOADER_WRONG_GENERAL] = "在$fs_location 下的loader文件与您的系统不兼容。";
            }
            $errors = $errors + $fs_loader_errors;
        }
    } 
    return $errors;
}

function unix_path_dir($dir = '')
{
    if (empty($dir)) {
        $dir = dirname(__FILE__);
    }
    if (is_ms_windows()) {
        $dir = str_replace('\\','/',substr($dir,2));
    }
    return $dir;
}

function unrecognised_inis_webspace($startdir)
{
    $ini_list = array();

    $ini_name = ini_file_name();
    $sys = get_sysinfo();
    $depth = substr_count($startdir,'/');

    $rel_path = '';
    $rootpath = realpath($_SERVER['DOCUMENT_ROOT']);
    for ($seps = 0; $seps < $depth; $seps++) {
        $full_ini_loc = @realpath($startdir . '/' . $rel_path) . DIRECTORY_SEPARATOR . $ini_name;
        if (@file_exists($full_ini_loc) && $sys['PHP_INI'] != $full_ini_loc) {
            $ini_list[] = @realpath($full_ini_loc);
        }

        if (dirname($full_ini_loc) == $rootpath) {
            break;
        }
        $rel_path .= '../';
    }
    return $ini_list;
}

function correct_loader_wrong_location()
{
    $loader_location_pair = array();
    $loader_location = find_loader_filesystem();
    if (is_string($loader_location)) {
        $loader_errors = loader_compatibility_test($loader_location);
        if (empty($loader_errors)) {
            $ini_loader = scan_inis_for_loader();
            if (!empty($ini_loader['location'])) {
                $ini_loader_errors = loader_compatibility_test($ini_loader['location']);
                if (!empty($ini_loader_errors)) {
                    $loader_location_pair['loader'] = $loader_location;
                    $loader_location_pair['newloc'] = dirname($ini_loader['location']);
                }
            } else {
                $std_dir = loader_install_dir(find_server_type());
                $std_ld_path = $std_dir . DIRECTORY_SEPARATOR . get_loader_name();
                if (@file_exists($std_ld_path)) {
                    $stdloc_loader_errors = loader_compatibility_test($std_ld_path);
                } else {
                    $stdloc_loader_errors = array("Loader文件不存在。");
                }
                if (!empty($stdloc_loader_errors)) {
                    $loader_location_pair['loader'] = $loader_location;
                    $loader_location_pair['newloc'] = $std_dir;
                }
            }
        }
    }
    return $loader_location_pair;
}

function ini_loader_warnings()
{
    $warnings = array();
    if (find_server_type() == SERVER_SHARED)
    {
        if (own_php_ini_possible()) {
            $sys = get_sysinfo();
            $ini_name = ini_file_name();
            $rootpath = realpath($_SERVER['DOCUMENT_ROOT']);
            $root_ini_file = $rootpath . DIRECTORY_SEPARATOR . $ini_name;
            $cgibinpath = @realpath($_SERVER['DOCUMENT_ROOT'] . "/cgi-bin");
            $cgibin_ini_file = (empty($cgibinpath))?'':$cgibinpath . DIRECTORY_SEPARATOR . $ini_name;
            $here = unix_path_dir();
            $ini_files = unrecognised_inis_webspace($here);
            $shared_ini_loc = shared_ini_location();
            $shared_ini_file = $shared_ini_loc . DIRECTORY_SEPARATOR . $ini_name;
            $ini_dir = dirname($sys['PHP_INI']);
            $all_ini_locations_used = !empty($ini_files);
            foreach ($ini_files as $full_ini_loc) {
                $advice = "文件 $full_ini_loc 没有被PHP认可。";
                $advice .= " 请检查文件名、路径是正确的。";
                if (!ini_same_dir_as_wizard()) {
                    $ini_loc_dir = dirname($full_ini_loc);
                    if (!@file_exists($shared_ini_file) && !empty($shared_ini_loc) && $ini_loc_dir != $shared_ini_loc && $ini_dir != $shared_ini_loc) {
                        $all_ini_locations_used = false;
                        $advice .= " 请将<code>$full_ini_loc</code> 复制到<code>" . $shared_ini_loc . "</code>。";
                    } else {
                        if (!@file_exists($root_ini_file) && $rootpath != $shared_ini_loc && $full_ini_loc != $rootpath) {
                            $all_ini_locations_used = false;
                            $advice .= " 请将文件 <code>$full_ini_loc</code> 复制到 <code>" . $rootpath . "</code>.";
                        } 
                        if (!empty($cgibin_ini_file) && !@file_exists($cgibin_ini_file) && $cgibinpath != $shared_ini_loc && $full_ini_loc != $cgibinpath && $cgibinpath != $rootpath) {
                            $all_ini_locations_used = false;
                            $advice .= "  请将文件 <code>$full_ini_loc</code> 复制到 <code>" . $cgibinpath . "</code>。";
                        }
                        $herepath = realpath($here);
                        $here_ini_file = $herepath . DIRECTORY_SEPARATOR . $ini_name;
                        if (!@file_exists($here_ini_file) && $herepath != $rootpath && $herepath != $cgibinpath) {
                            $all_ini_locations_used = false;
                            $advice .= " 需要将文件 <code>$full_ini_loc</code> 复制到 <code>$herepath</code> 和所有加密文件所在的路径。";
                        }
                    }
                } else {
                    $all_ini_locations_used = false;
                }
                $warnings[] = $advice;
            }
            if ($all_ini_locations_used) {
                $warnings[] = "<strong>好像ini配置文件不在默认的位置，请联系服务器提供商，询问是否能够创建自己的PHP ini文件和创建位置。</strong>";
            }
        } else {
            if (own_php_ini_possible(true)) {
                $warnings[] = "你可能没有权限在共享服务器上创建自己的ini文件。 <br><strong>请联系管理员安装ionCube loader。</strong>";
            }
        }
    } else {
        $loader_dir_pair = correct_loader_wrong_location();
        if (!empty($loader_dir_pair)) {
            $advice = "在<code>${loader_dir_pair['loader']}</code>已经找到可用的loader。"; 
            if ($loader_dir_pair['loader'] != $loader_dir_pair['newloc']) {
                $advice .= " 你可能想将loader文件 <code>${loader_dir_pair['loader']}</code> 复制到 <code>${loader_dir_pair['newloc']}</code>。";
            }
            $warnings[] = $advice;
        }
    }
    return $warnings;
}

function list_loader_errors($errors = array(),$warnings = array(),$suggest_restart = true)
{
    $default = get_default_address();
    $retry_message = '';

    if (empty($errors)) {
        $errors = ini_loader_errors();
        if (empty($warnings)) {
            $warnings = ini_loader_warnings();
        }
    }
    if (!empty($errors)) {
        $try_again = '<a href="#" onClick="window.location.href=window.location.href">重试</a>';
        echo '<div class="alert">';
        if (count($errors) > 1) {
            echo '安装过程中出现以下问题：';
            $retry_message = "请解决这些问题并$try_again。";
        } else {
            echo '安装过程中出现以下问题：';
            $retry_message = "请解决这些问题并$try_again。";
        }
        if (array_key_exists(ERROR_INI_USER_CANNOT_CREATE,$errors)) {
            $retry_message = '';
        }
        echo make_list($errors,"ul");
        echo '</div>';
        if (!empty($warnings)) {
            echo '<div class="warning">';
            echo '同时请注意：';
            echo make_list($warnings,"ul");
            echo '</div>';
        }
    } elseif (!empty($warnings)) {
        echo '<div class="warning">';
        echo '注意下面潜在的问题：';
        echo make_list($warnings,"ul");
        echo '</div>';
    } elseif ($suggest_restart) {
        if (SERVER_SHARED == find_server_type()) {
            echo "<p>请联系管理员安装ionCube Loader。</p>";
        } else {
            if (selinux_is_enabled()) {
                echo "<p>好像服务器上的SElinux已经开启。 使用root 运行命令 <code>restorecon [full path to loader file]</code>能够解决该问题。如果没有解决，请参照 <a target=\"_blank\" href=\"http://www.cuteshift.com/57/install-ioncube-loader-while-selinux-enabled/\">http://www.cuteshift.com/57/install-ioncube-loader-while-selinux-enabled/</a> ，安装 ionCube Loader。</p>";
            } elseif (grsecurity_is_enabled()) {
                echo "<p>好像服务器上的 grsecurity 已经开启。 运行命令 <code>execstack -c [full path to loader file]</code> ，并重启服务器。</p>";
            } else {
                $sysinfo = get_sysinfo();
                $ss = $sysinfo['SS'];
                if (!$sysinfo['CGI_CLI'] || is_ms_windows()) {
                    echo "<p>请检查 $ss 服务器已经重启。</p>";
                } 
            }
        }
    }
    echo '<div>';
    echo $retry_message;
    echo " 你可能需要下面的帮助：";
    echo make_list(help_resources($errors),"ul");
    echo '<a href="' . $default . '">点击这里重新开始向导</a>。</div>';
}

function phpconfig_page()
{
    info_disabled_check();
    $sys = get_sysinfo();
    $download = get_request_parameter('download');
    $ini_file_name = '';
    if (!empty($download)) {
        $ini_file_name = get_request_parameter('ininame');
        if (empty($ini_file_name)) {
            $ini_file_name = ini_file_name();
        }
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $ini_file_name);
    } else {
        header('Content-Type: text/plain');
    }
    $exclude_original = get_request_parameter('newlinesonly');
    $prepend = get_request_parameter('prepend');
    $stype = get_request_parameter('stype');
    $server_type = find_server_type($stype);
    if (!empty($exclude_original) || !empty($prepend)) {
        $loader_dir = loader_install_dir($server_type);
        $zend_lines = zend_extension_lines($loader_dir);
        echo join(PHP_EOL,$zend_lines);
        echo PHP_EOL;
    }
    if (empty($ini_file_name) || empty($sys['PHP_INI_DIR']) || ($sys['PHP_INI_BASENAME'] == $ini_file_name)) {
        $original_ini_file = isset($sys['PHP_INI'])?$sys['PHP_INI']:'';
    } else {
        $original_ini_file = $sys['PHP_INI_DIR'] . DIRECTORY_SEPARATOR . $ini_file_name;
    }
    if (empty($exclude_original) && !empty($original_ini_file) && @file_exists($original_ini_file)) {
        if (!empty($download)) {
            @readfile($original_ini_file);
        } else {
            echo all_ini_contents();
        } 
    }
}

function extra_page()
{
    info_disabled_check();
    heading();
    $sys = get_sysinfo();
    $ini_loader = scan_inis_for_loader();
    $ini_loader_path = $ini_loader['location'];
    $loader_path = find_loader(true);
    $ldinf = get_loaderinfo();
    $self = get_self();
    echo "<h4>附加信息</h4>";
    echo "<table>";
    $lines = array();
    if (is_string($loader_path)) {
        $lines['Loader is at'] = $loader_path;
        $loader_system = loader_system($loader_path);
        if (!empty($loader_system)) {
            $lines['Loader OS code'] = $loader_system['oscode'];
            $lines['Loader architecture'] = $loader_system['arch'];
            $lines['Loader word size'] = $loader_system['wordsize'];
            $lines['Loader PHP version'] = $loader_system['php_version'];
            $lines['Loader thread safety'] = $loader_system['thread_safe']?'Yes':'No';
            $lines['Loader compiler'] = $loader_system['compiler'];
            $lines['Loader version'] = $loader_system['loader_version'];
            $lines['File size is'] = filesize($loader_path) . " bytes.";
            $lines['MD5 sum is'] = md5_file($loader_path);
        }
        $lines['Loader file'] = "<a href=\"$self?page=loaderbin\">Download loader file</a>";
    } else {
        $lines['Loader file'] = "Loader cannot be found.";
    }
    $lines['Loader found in ini file'] = empty($ini_loader_path)?"No":"Yes";
    if (!empty($ini_loader_path) && (!is_string($loader_path) || $ini_loader_path != $loader_path)) {
        $lines['Loader location found in ini file'] =  $ini_loader_path;
        $loader_system = loader_system($ini_loader_path);
        if (!empty($loader_system)) {
            $lines['Ini Loader OS code'] = $loader_system['oscode'];
            $lines['Ini Loader architecture'] = $loader_system['arch'];
            $lines['Ini Loader word size'] = $loader_system['wordsize'];
            $lines['Ini Loader PHP version'] = $loader_system['php_version'];
            $lines['Ini Loader thread safety'] = $loader_system['thread_safe']?'Yes':'No';
            $lines['Ini Loader compiler'] = $loader_system['compiler'];
            $lines['Ini Loader version'] = $loader_system['loader_version'];
        }
    }
    $lines["OS extra security"] = (selinux_is_enabled() || possibly_selinux())?"SELinux":(grsecurity_is_enabled()?"Grsecurity":"None");
    $lines['PHPRC is'] = $sys['PHPRC'];
    $lines['INI DIR is'] = $sys['PHP_INI_DIR'];
    $lines['Additional INI files'] = $sys['PHP_INI_ADDITIONAL'];
    $stype = get_request_parameter('stype');
    $server_type = find_server_type($stype);
    $lines['Server type is'] = server_type_string();
    $lines["PHP uname"] = $ldinf['uname'];
    $lines['Server word size is'] = $ldinf['wordsize'];
    $lines['Disabled functions'] = ini_get('disable_functions');
    $writeable_dirs = writeable_directories();
    $lines['Writeable loader locations'] = (empty($writeable_dirs))?"<em>None</em>":join(", ",$writeable_dirs);
    if (!empty($_SESSION['hostprovider'])) {
        $lines['Hosting provider'] = $_SESSION['hostprovider'];
        $lines['Provider URL'] = $_SESSION['hosturl'];
    }
    foreach ($lines as $h => $i) {
        $v = (empty($i))?'<em>EMPTY</em>':$i;
        echo '<tr><th>'. $h . ':</th>' . '<td>' . $v . '</td></tr>';
    }
    echo "</table>";
    footer(true);
}

function loaderbin_page()
{
    info_disabled_check();
    $loader_path = find_loader(true);
    if (is_string($loader_path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='. basename($loader_path));
        @readfile($loader_path);
    }
}



function GoDaddy_root($html_root = '')
{
    if (empty($_SESSION['not_go_daddy']) && empty($_SESSION['godaddy_root'])) {
        $godaddy_pattern = "[\\/]home[\\/]content[\\/][0-9a-z][\\/][0-9a-z][\\/][0-9a-z][\\/][0-9a-z]+[\\/]html";

        if (empty($html_root)) {
            $html_root =  $_SERVER['DOCUMENT_ROOT'];
        }
        if (preg_match("@$godaddy_pattern@i",$html_root,$matches)) {
            $_SESSION['godaddy_root'] = $matches[0];
        } else {
            $_SESSION['not_go_daddy'] = 1;
            $_SESSION['godaddy_root'] = '';
        } 
    } elseif (!empty($_SESSION['not_go_daddy'])) {
        $_SESSION['godaddy_root'] = '';
    }
    if (!empty($_SESSION['godaddy_root'])) {
        $_SESSION['hostprovider'] = 'GoDaddy';
        $_SESSION['hosturl'] = 'www.godaddy.com';
    }
    return $_SESSION['godaddy_root'];
}

function GoDaddy_windows_instructions()
{
    $instr = "It appears that you are hosted on a Windows server at GoDaddy.<br/>";
    $instr .= "Please change to a Linux hosting plan at GoDaddy.<br />";
    $instr .=  "If you <a href=\"http://help.godaddy.com/\">contact their support team</a> they should be able to switch you to a Linux server.";

    echo $instr;
}

function GoDaddy_linux_instructions($html_dir)
{
    $base = get_base_address();
    $loader_name = get_loader_name();
    $zend_extension_line="<code>zend_extension = $html_dir/ioncube/$loader_name</code>";
    $php_ini_name = is_php_version_or_greater(5,0)?'php5.ini':'php.ini';
    $ini_path = $html_dir . '/' . $php_ini_name;

    $instr = array();
    $instr[] = 'In your html directory, ' . $html_dir . ', create a sub-directory called <b>ioncube</b>.';
    if (@file_exists($ini_path)) {
       $instr[] = "Edit the $php_ini_name in your  $html_dir and add the following line to the <b>top</b> of the file:<br>" . $zend_extension_line ;
    } else {
        $instr[] = "<a href=\"$base&amp;page=phpconfig&amp;ininame=$php_ini_name&amp;stype=s&amp;download=1&amp;prepend=1\">Save this $php_ini_name file</a> and upload it to your html directory, $html_dir";
    }
    $instr[] = 'Download the <a target="_blank" href="http://downloads2.ioncube.com/loader_downloads/ioncube_loaders_lin_x86.zip">Linux ionCube Loaders</a>.';
    $instr[] = 'Unzip the loaders and upload them into the ioncube directory you created previously.';
    $instr[] = 'The encoded files should now be working.';

    echo '<div class=panel>';
    echo (make_list($instr));
    echo '</div>';
}

function GoDaddy_page()
{
    $base = get_base_address();

    heading();

        $inst_str = '<h4>GoDaddy Installation Instructions</h4>';
        $inst_str .= '<p>It appears that you are hosted with GoDaddy (<a target="_blank" href="http://www.godaddy.com/">www.godaddy.com</a>). ';
        $inst_str .= "If that is <b>not</b> the case then please <a href=\"$base&amp;page=default&amp;host=ngd\">click here to go to the main page of this installation wizard</a>.</p>";
        $inst_str .= "<p>If you have already installed the loader then please <a href=\"$base&amp;page=loader_check\" onclick=\"showOverlay();\">click here to test the loader</a>.</p>";

        echo $inst_str;

        if (is_ms_windows()) {
            GoDaddy_windows_instructions();
        } else {
            GoDaddy_linux_instructions($_SESSION['godaddy_root']);
        }

    send_stats('gd_default');

    footer(true);
}



function get_request_parameter($param_name)
{
    static $request_array;

    if (!isset($request_array)) {
        if (isset($_GET)) {
            $request_array = $_GET;
        } elseif (isset($HTTP_GET_VARS)) {
            $request_array = $HTTP_GET_VARS;
        }
    }

    if (isset($request_array[$param_name])) {
        $return_value = strip_tags($request_array[$param_name]);
    } else {
        $return_value = null;
    }
    return $return_value;
}

function make_list($list_items,$list_type='ol')
{
    $html = '';
    if (!empty($list_items)) {
        $html .= "<$list_type>";
        $html .= '<li>';
        $html .= join('</li><li>',$list_items);
        $html .= '</li>';
        $html .= "</$list_type>";
    }
    return $html;
} 

function make_archive_list($basename,$archives_list = array(),$download_server = IONCUBE_DOWNLOADS_SERVER)
{
    if (empty($archives_list)) {
        $archives_list = array('tar.gz','tar.bz2','zip','ipf.zip');
    }

    foreach ($archives_list as $a) {
        $link_text = ($a == 'ipf.zip')?'MS Windows installer':$a;
        $ext_sep = ($a == 'ipf.zip')?'_':'.';
        $archive_list[] = "<a href=\"$download_server/$basename$ext_sep$a\">$link_text</a>";
    }

    return make_list($archive_list,"ul");
}

function error($m)
{
    die("<b>错误:</b> <span class=\"error\">$m</span><p>你可以<a href=\"". SUPPORT_SITE . "\">反馈该错误</a>来完善文档。最好包含出错的URL，这样我们可以测试一下。");
}

function failsafe_get_self()
{
    $result = '';
    $sfn = $_SERVER['SCRIPT_FILENAME'];
    $dr = $_SERVER['DOCUMENT_ROOT'];
    if (!empty($sfn) && !empty($dr)) {
        if ($dr == '/' || $dr == '\\') {
            $result = $sfn;
        } else {
            $drpos = strpos($sfn,$dr);
            if ($drpos === 0) {
                $drlen = strlen($dr);
                $result = substr($sfn,$drlen);
            }
        }
        $result = str_replace('\\','/',$result);
    }
    if (empty($result)) {
        $result = DEFAULT_SELF;
    }
    return $result;
}

function get_self()
{ 
    if (empty($_SERVER['PHP_SELF'])) {
        if (empty($_SERVER['SCRIPT_NAME'])) {
            if (empty($_SERVER['REQUEST_URI'])) {
                return failsafe_get_self();
            } else {
                return $_SERVER['REQUEST_URI'];
            }
        } else {
            return $_SERVER['SCRIPT_NAME'];
        }
    } else {
        return $_SERVER['PHP_SELF'];
    }
}

function get_default_page()
{
    $godaddy_root = GoDaddy_root();
    if (empty($godaddy_root)) {
         $page = 'default';
    } else {
         $page = 'GoDaddy';
    }
    return $page;
}

function get_base_address()
{
    $self = get_self();
    $remote_timeout = (isset($_SESSION['timing_out']) && $_SESSION['timing_out'])?'timeout=1':'timeout=0';
    $using_ini = (isset($_SESSION['use_ini_method']) && $_SESSION['use_ini_method'])?'ini=1':'ini=0';
    return $self . '?' . $remote_timeout . '&' . $using_ini;
}

function get_default_address($include_timeout = true)
{
    if ($include_timeout) {
        $base =  get_base_address();
        $base .= "&amp;";
    } else {
        $base = get_self();
        $base .= "?";
    }
    $page = get_default_page();

    return $base . 'page=' . $page;
}

function heading()
{
    $self = get_self();

    echo <<<EOT
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <title>ionCube Loader Wizard</title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <link rel="stylesheet" type="text/css" href="$self?page=css">
        <script type="text/javascript">
            function showOverlay()
            {
                document.getElementById('overlay').style.display = 'block';
                return true;
            }

            function hideOverlay()
            {
                document.getElementById('overlay').style.display = 'none';
                return true;
            }
        </script>
    </head>
    <body onload="hideOverlay()">
    <div id="overlay">
        <div id="inner_overlay">检查服务器配置<br>请稍等</div>
    </div>
    <div id=header>
        <img src="?page=logo" alt="ionCube logo">
    </div>
    <div id=main>
    <h2>ionCube Loader 安装向导</h2>
EOT;
}

function footer($update_info = null)
{
    $self = get_self();
    $base = get_base_address();
    $default = get_default_address(false);
    $year = gmdate("Y");

    echo "</div>";
    echo "<div id=\"footer\">" .
    "Copyright ionCube Ltd. 2002-$year | " .
    "Loader Wizard version " . script_version() . " ";

    if ($update_info === true) {
        $update_info = check_for_wizard_update(false);  
    }
    $loader_wizard_loc = LOADER_WIZARD_URL;
    $wizard_version_string =<<<EOT
    <script type="text/javascript">
    var xmlhttp;
    function version_check()
    { 
        var body = document.getElementsByTagName('body')[0];
        var ldel = document.getElementById('loading');
        if (!ldel) {
            body.innerHTML += '<div id="loading"></div>';
            ldel = document.getElementById('loading');
        }
        ldel.innerHTML = '<p>向导版本信息检索<br>请稍等</p>';
        ldel.style.display = 'block';
        ldel.style.height = '300px';
        ldel.style.left = '200px';
        ldel.style.border = '4px #660000 solid';
        if (window.XMLHttpRequest) {
            xmlhttp=new XMLHttpRequest();
        } else {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            var loadedOkay = 0;
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var wizardversion = xmlhttp.responseText;
                var msg;
                clearTimeout(xmlHttpTimeout);
                buttons = '';
                if (wizardversion == '1') {
                    msg = '你的当前版本<br>ionCube Loader安装向导'; 
                } else if (wizardversion != '0') {
                    msg = 'loader安装向导有新版本：' + wizardversion;
                    buttons = '<button onclick="document.getElementById(\'loading\').style.display=\'none\'; window.open(\'$loader_wizard_loc\'); return false">获取新版本</button> &nbsp;'; 
                } else {
                    msg = '不能从Ioncube服务器获取向导版本信息';
                }
                buttons += '<button onclick="document.getElementById(\'loading\').style.display=\'none\'; return false">关闭</button>'; 
                ldel.innerHTML = '<p>' + msg +  '<br>' + buttons + '</p>';
            }
        }
        xmlhttp.open("GET",'$self?page=wizardversion&wizard_only=1&clear_info=1',true);
        xmlhttp.send();
        var xmlHttpTimeout=setTimeout(ajaxTimeout,7000);
    }
    function ajaxTimeout(){
       xmlhttp.abort();
       msg = '不能从Ioncube服务器获取向导版本信息';
       button = '<button onclick="document.getElementById(\'loading\').style.display=\'none\'; return false">关闭</button>';
       var ldel = document.getElementById('loading');
       ldel.innerHTML = '<p>' + msg +  '<br>' + button + '</p>';
    }
    </script>
EOT;

    $wizard_version_string .= '('; 
    if ($update_info === null) {
        $wizard_version_string .= '<a target="_blank" href="' . $loader_wizard_loc . '" onclick="version_check();return false;">check for new version</a>';
    } else if ($update_info !== false) {
        $wizard_version_string .= '<a href="' . LOADERS_PAGE .'" target="_blank">download version ' . $update_info . '</a>';
    } else {
        $wizard_version_string .=  "current";
    }
    $wizard_version_string .= ')'; 
    echo $wizard_version_string;

    $server_type_code = server_type_code();

    if (!info_should_be_disabled()) {
        echo " | <a href=\"$base&amp;page=phpinfo\" target=\"phpinfo\">phpinfo</a>";
        echo " | <a href=\"$base&amp;page=phpconfig\" target=\"phpconfig\">config</a>";
        echo " | <a href=\"$base&amp;page=extra&amp;stype=$server_type_code\" target=\"extra\">additional</a>";
        echo " | <a href=\"$base&amp;page=system_info_archive&amp;stype=$server_type_code\">info archive</a>";
    }
    echo " | <a href=\"$default\" onclick=\"showOverlay();\">wizard start</a>";
    echo " | <a href=\"$base&amp;page=loader_check\" onclick=\"showOverlay();\">loader test</a>";
    echo ' | <a href="' . LOADERS_PAGE . '" target="loaders">loaders</a>';

    echo "</div>\n";
    echo "\n</body></html>\n";
}

function css_page()
{
    header('Content-Type: text/css');
    echo <<<EOT
    BODY {
        font-family: verdana, helvetica, arial, sans-serif;
        font-size: 10pt;
        line-height: 150%;
        margin: 0px;
        min-height: 400px;
        position: relative;
    }

    CODE {
        color: #c00080;
    }

    LI {
        margin-top: 10px;
    }
    #overlay {
        display: block;
        z-index: 100;
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100%;
        background-color: white;
    }
    #inner_overlay {
        display: block;
        z-index: 100;
        position: absolute;
        font-size: 200%;
        color: #660000;
        top: 50%;
        left: 25%;
        width: 460px;
        height: 460px;
        line-height: 200%;
        text-align: center;
        vertical-align: middle;
    }

    #loading {
        display: block;
        position: absolute;
        top: 33%;
        left: 25%;
        margin: auto;
        height: 320px;
        width: 460px;
        padding: 4px;
        color: #660000;
        background-color: white;
        z-index: 100;
    }

    #loading p {
        position: absolute;
        margin-top: 10px;
        text-align: center;
        vertical-align: middle;
        padding-left: 40px;
        padding-right: 30px;
        font-size: 200%;
        line-height: 200%;
    }

    #loading p span#status{
        font-size: 60%;
        line-height: 120%;
    }
    #loading p#noscript {
        font-size: 120%;
        line-height: 120%;
        position: absolute;
        text-align: left;
        padding-top: 10px;
        bottom: 0;
    }
    #loading p#noscript a {
        text-align: center;
    }

    #loading button {
        margin-top: 20px;
        line-height: 100%;
        padding-top: 4px;
        padding-bottom: 4px;
    }


    h4 {
        margin-bottom: 0;
        padding-bottom: 4px;
    }

    p,#main div {
        max-width: 1000px;
        width: 75%;
    }

    #hostinginfo {
        margin-top: 10px;
        margin-left: 20px;
    }
    #hostinginfo table {
        font-size: 1.00em;
    }
    #hostinginfo table td {
        padding-right: 4px;
    }
    #hostinginfo input {
        margin-top: 6px;
    }

    #hostinginfo label {
        margin-left: 6px;
    }

    th {
        text-align: left;
    }

    .alert {
        margin: 2ex 0;
        border: 1px solid #660000;
        padding: 1ex 1em;
        background-color: #ffeeee;
        color: #660000; 
        width: 75%;
    }

    .warning {
        margin: 2ex 0;
        border: 1px solid #FFBF00;
        padding: 1ex 1em;
        background-color: #FDF5E6;
        color: #000000; 
        width: 75%;
    }

    .success {
        margin: 2ex 0;
        border: 1px solid #006600;
        padding: 1ex 1em;
        background-color: #EEFFEE;
        color: #000000; 
        width: 75%;
    }

    .error {
        color: #FF0000;
    }

    .panel {
        border: 1px solid #c0c0c0;
        background-color: #f0f0f0;
        width: 75%;
        padding: 1ex 1em;
    }

    #header {
        background: #3f0f0f;
    }

    #footer {
        border-top: 1px solid #404040;
        margin-top: 20px;
        padding-top: 10px;
        padding-left: 20px;
        font-size: 75%;
        text-align: left;
    }

    #main {
        margin: 20px;
    }
EOT;
}

function logo_page()
{
$img_encoded = 'iVBORw0KGgoAAAANSUhEUgAAAU4AAABQCAMAAABBJmwEAAADAFBMVEVBLA49Dg44Dg5FDg46DAxLIBIwCgoyCgpBDQ00FAtLLhA0DAwuCwuBYhwtEwltTxhmSBY2CgoSBARNOBErCQkqCgo4Cws4DAwsCgo0JAtFMQ9VPxNJMxBlQhYNAwMmCAh6XBs+Ig5VOhNhQhVmTRYsCAgXBQUgBwcwDAwVBARRNRIhBwdbMhUtHQlRORIcBgZFKRB4WxpGGhFXQBMWBQVVLBRUNRJoThdwVRkeBgYxHgtBFBBcQRR0VRk5FQwkCAgdEQYiBwdNNBEuCQk+Fw1fRxVPMBJXOxMnCgphRxUQAwMjEQc8Kw0aCgVADg5fQhUlFgg/HQ5ZPBRaQBRyVRleQRVCDQ05Gw1DIg4aBgYTCQRrThcjBwc6HA1YPxM2Dg45IAwZBQU+Dg4uDAw3JQwqGAlFJBAqDgpZQBMKAgIxGQsuDwpFKA8xDwtHMxB9XBslCwlIKA8wCQljSBYYBgYeCAhTPhIqHQlcNxRDJw4OAwM0GAw8JQ0WBgY8Gw0cBQUOCAM3Gw0GAQHv6+F/XRxDDw9JDw/OAAD0AADtAADNAAD+AAD/AADPAADRAAD4AADTAADXAAD8AAD9AADg18PAr4eRcy339fBHDg46Dg5GDg7eAAA9DAyokVrQw6XYzbRIDw91URqZfTwpCAjqAADmAADaAADcAAD1AADZAADrAADxAADsAADWAADSAADQAADMAAD2AADyAADwAADuAADoAADkAADlAADdAADYAADUAADVAADbAADvAADnAAD3AADpAAD5AADzAABDDg64pXjIuZahh0s1Cws7Dg4/DQ3n4dI8Dw8+DQ02DQ1qRBg8DAywm2lgOBYyDAxwSxk0DQ0oCAhrRRhgORaEYx3jAADfAADhAADiAADgAAA0CgpmPxc1CgqZmZk3DQ00Cwt6VxtQJhNCDg4+DAw3DAwzDAxlPhcqCQlGDw82DAwoCQlHDw8/DAw8DQ1BDw8yDQ06Dw5EDQ09DQ1sThc1Dg5dPBQ0HwtLOBCJaR5KDw////89Dw9KsskKAAAAAWJLR0QAiAUdSAAAF3VJREFUeNrtnAl4E9e1gIk0jBQiAhWScIFa0KRaEKAANkZAaowehShhaVrUmKg0SkJTB5K+UNI2TlvSyPXbd83IdrXY8tv3fd+1WcK2hFfeBvlssAMOJKTLey+Vxz333hntsi2Pg/P1y/kSjXTnLuf+99xz77kzZtnMh7KIsmypFfjJkg9xLqp8iHNR5UOciyof4lxUuRs4a7sup9OXB6+XuHWpJd1VW7LQ9cGWS7PWCkX782vvT+Mi6EZhk/D78sjynNJ83oIay+giGucvipdsZV1uD8d5AtH+omb6Uy7O3VVSg8GAi907m+YtLi6dX3uac7XwNwqa7Pe5PR5/bn183lzZy7rK6PLBwgn9Yxg/x/kKx77WxyWY8JZytII75oMzW3sOzvwmt/g4V8TvSuW0n49z+cjlmzM7gsWIFwnnL4iX/I6PjviK7bDLzQVHS9OcucQG+2dmkxycfO35OHOavJbg2KsjebM7H+eI33MZbDjIzu5fPjg4+V52If+0d7QljTxZfxqSwAFuuUbcHNhI+toW/kJc446rLemWqzuQW0O5bu7lky7l4ySfBTizWbLshKZxEvGf8Mkrgn9ncvSnR6BJlAMltcwPdBmcvyFeCnFe9nguu9wp8E9djIsDT3YpyCEJjoQT4ObYS9dZv4dLRPkLLrSFjbhgnrK1LS63D3Ix/eDhUJKvvwAnqr0IJ0rkrTOFl6VaoWmMk+SHTx9WxId+5+bwpxKcK9U/0w9JrjI+aYlwjg6muECLy8UlIjDF3UyAc7Es4wEPx/q5QJjxuNjBAOdn3EH+ggrtYBNcALK6u1pcHs4d8HDhLTuCHj8AjS7Pm+yo9kHAkUYiTHaciLJsiXq4QBRMLtP0jjycQaxIEP3Oy8EFoEnfljBSQhTOXxcveTgZUNGDEEWuXQ1zka7RQfi8ijvt45jBLTfhM80lro121fIXdO9qhAsPjg5GOAZ+hbsGo3Br5ublkatRzj+StxSh2q+nOUHS2USswc2om4MiW7JN5+FswYpgxLk5ON8gjIgbCLOjg/OiebdwcpzLH7wJX9gdaP3Zke3FiJ9jwiBYbX+0q5a/ZKcsrLjuHF+4fIT1hbHBZnCS2qFKjx+JB5fkE4nUdoGvYHKaLsZJvuXnIN8Yzp0S5zsXVfAy0QJzDWvNYxBwQReJuLvYAHyyteSSs7agSwYnuFaSO3ey49pxvpGREaFmkijIFpYD351puhzO/Bz8kgW+wBWcF8+7hTP7BZnI8pm9Qi/AVbLY43XVXh8MRmBKkosAZe/M8jzrhPzIy+XjJO0ULUUZWc4nZ5sm/6OVqsg6c3KQtL032fA8t6R3H+cow/nBPTG876xNcf5rV0evdtVeahkZxZzwhfedzOBol58L5++GrgYrw9kfbRkBx+vuyjaN8sKCHx3sChOc7MhyMrFzc5A6B0EHd97wVIrz38RLOZx7+cUzwe4QrDURYSJoesOKjsjxF7Syw+IKPwR2vHNAK3slOPey4EbRGl2bbRrlhZXJE3BzHnARfs7tZ3NWdj4HqTMQgACL7BEWiPNXxEs5nDO1/GZyC/kNO70Eiq+DLPTYxfTzF3zvEtqDuhiyMJHytawbNoOJxLVKcKIW3BDAZ5vGeZFTTPjcrpblUKkH4yzIgT8jeCd6fWYecjdwCmc/whch1OF/7x29hk9/rg9eTreM7uUv5B6KkOAHnxN/1nZBiHJNCJtyas89UcomYp6oBXxalGmaREA3UfzVBd+g0ss3cVp+Dvx5lY+TFo7zX8TLvJr/SZMyOP9MvCx1z5ZEyuD8XfGy1D1bEimD82fFy1L3bEnkQ5yLKmVw/rt4WeqeLYmUwfnn4mWpe7YkUgbnH4iXpe5ZWbl4sae9vb3n4sW7h/MvxMtSUytLs13Sh0TS/j7wLIPzn8RLbg+IvB9siKm1E3ObTwtAc8igqFIYht4PnmVw/qV4yelyu0TSNyaR9Cyy+qTivqGhGzduDA2BvQGgOZu4KBn6GD4Q/NiQ5K7h/Dnxku20pG/AoDAstjlc7JGMDUHFVWr14cOH1WowuIF5zOCePgN5CJJ+pq99Ie1OgywlTkmf4YnV6S/vemFI0rOYMPtuGNRnNm1etQob26pVmx86o5h7yNr7qnicasNY5Q6IoCwLtAzOPxQvQlU9YwOPRwOciznxwqJNL2TxNxTqTatZH+MmT0fcbibFrv7inE20D6l5nA1VC9BnegYbZ4U4f1+8ZPV/gfUg/Zldir7vLwpPMM0hg3rzRl+Ay5dA9NN9c5inZOAwj9OsNVTsfgAjKluWZxmcvylehK5LbnwqjPV3bVR/T7IY6ztanBWbVvtcXJGEPzXXDM7iPK9XV2ye0xyHrBM+K8L5r+Ili3MXwz/9rlcsxnQHmgPqQ2yAKyHsc1U3Zh+yLE6l+fZA5ThnsHFWiPOvxEtG/6FPR8lM/IrlcOXTq5hmD6IZ9fAAXX4m5QNh/C7UxMuHDWPt88PZfF5rGKtwceRy/qsA5z+LF6Gq9j7FA+jRuCe6p7Fe0SfaPGHjqD7k42n6o9t3vvjsVpAXd24PMuFl5rlmQBZndU29oa9SnGiiw8d0ZTh/S7zkdP9bx3wMw66xnTdV7q1K0FQIthkI7jze3NxsQwLX/S8uq2msVw/ME2eTEUa3Ypxous9MV4jzb8RLpv9gnrc3HdhwpFl53pSzNxHiw4LgEFJBcBrJUXAftuGbyE6BYw5uRSiVSmMNEiV80Znr1QaIjgqOOHJOPQpwSnrKB6glFOR4ohVO9r8WLxmlkK+rN39TqazJzkQSHw4hQcFhpu8X+QMKSXtPj5AjL3iElU29mqxC4VMngabSeL7RbH5Zr9e//DJcTYcVA0OFRxy5px45OJUmteG+Pl6H9oIQGGkyxmuYoyBHHGcZmuVw/sdsMt+ZIfRlzKCutzSeP2/WHzbgbSHswlHgCeEhEoUBFOY7Qw4oULg41ifkwMGj0NmePsVm8joms766GcE0m+oPk4pwsFlFGwwFRxx5px65OC1adVaHsdwdAdJwCAewBQpyXPZz/jj/ezapEKdw5nDCdPsZHGfi7lWdWXHsYUhduerxz6sVAAx35qLkezjzAxDjQ47VkOPh1SvOKG7wyzUyzo14v+muO1ndDAZv0iLeA2BEAwMA0vC9AUPhEUfeqUcOTluj/uzjOERFOuSeKRANX921eiPcfPjYQ+qc6JVs4yvD+V+zSWU4s2cO9bCNB6XAmQ5Ufe4YG/Yn0ILvjkQ3bkadQePf3vdFbHu+TdrnTrDhAORIBMLsITUxayBj2ESMM/pRoPlNcJUwFGMSXsb6xr5wQ1F4xJF36pHF+Xr1ho2pCA5RQYdVZ6sGBGQwoZCGQSaARi7hTyEF57UnKYPzf2aTynBmzxzqq+5DTkjSZ1AfCvpzNt+u8Kqzasyzve9V/Iq3770NLJO570kd+xzhCZUdwyUDB5uqbd+0aKsGsNvjBRaxsaGiI468Uw+JQcC5f30qJ65yB7Nj1l6ooQsUeGau8HUWnP85m1SIM3PmYFLfAGSg6+ePhT1cnrjBAFEECnMZZ/atieaFkP59zyn+r30GTdWVuGjqo03NNWYtnoQXSzeXOeLIS+rL4GT9eSp4wvsOK77Qwzv7EwUa+vd9ez575jI4vzubVIYz/8xBArq+eiy/I7gz0QNowyhkDkcLeDP7biNjy8z1dFO1srG+aqjoTLrEEUde0hAt/HAX6gA8DTCkaCdyghwzcAm3O5Ed0L7vz4nzOyXlu+JFqOrom4JtnK+nJffcs1ZxIkIIMlFw9EGGmKGLfUVBPX0PnzmCX8ZmIHiM8Fbqe6Vq7Ol378jIAYC7rsl2vr7q1j1HCzXPb+7pdwuTbo3zPyLYywRBhSjDj110c9Xa3nePSmieJsPurqvbHSTqMh9X3/rhu9+ZXcrg/FvxUtw/pUV1i5LIdqXwzwC75jRswvev4yed+0mtjLrnFsmM/gSQ2b5hz549a3Ju3+mV0KuwUTHrmpTmqXGqiGZ+c2/e825h0rhMnfmTBGjhUdj9H1/Dn6e4NqIBv3PfS/iQweM7dQRCA9v+9Riuh31OMfb0wnD+jngp7p+t8fZ9b8ZeYF1kLdkD8QxS9/h6Aix8YCpG3RI664muOVJTY4TbB0lfg/er77szphBwVhtNVYRWOZyouTtHC5Nk9JSAM7z+iFGnQ21sPcirsG54fOxWFWnDt82IRdm8Hs8IZpt2/M4c5rlsuqT8o3gRqurw0kJnnJ94k/oIma0udg+KCRudTqfxOAHs2uiQURo+M5daYzPCTadOuZU/fT6gjXmT/G3f12zOqVi8o7VI87zmKHtbYVIsZuVbYL5ardRduGC+YAYVtmMVErsdtCb2BHbPkVPGGp3TbLaYdUewBp6VJtorbZ2eTcrg/G3xUty/Zmf3ldg5sgsPr7Mpa5wWh3ZY2+BcR1yV7ylVLMbvGt0PNtmMZoupocFi3IlNJ/CInqYoAedWm8UKsGbDCc1p5G2FSW8IOF3sazZjox40uI1U4Hezzw/TNAlig4+iIwCTdnjY0bgtQvQb1ky0zcqzDM7fEy8l+yeTkcNP13YIEJ0mrVUWG6e1F4htRLY5aBnf2fBjTTadXquy0qqGb6TI6gP2uNYq4FQC3FBnsebzxsmse91WY9Gq6FwVmA3622dYvJLvbIbpgTSwduvf85GbDbLejoXg/DvxUqp/wzKazPXItmqbztQt0yTjvUmZ9uMRMtXMU7SKZGZfg7VGa41RVMz6Kjl+Thu1slgWZ5mZN2+c0eOvkxa88TiocIAhHt1pWhHmx7NZCTi7Vaph/RGWDKhZRUk7p2eRMjh/TbyUxElbiZtPPdusNA/LKLlU2hFK0q+QxT6tq1d1Z0JAncOq8con4jGtsC6b6HEr2cWHH1M6ZJOicKZfb6oxWWNeu7SjI+Sln/IRYkbzITy20QcfrKt78slVSJ6sS5ObOq3GPutsv8s4z1kfxtvi4H6bzkFT8o7O1tbOo7F7yR8PR0/rh4eFA4pm81RsMtTWGaJUwkJiscoyGyUlTDyROJvN3Ve8obZWUGEiRqY4NKJ7koTx+cLPj3qZvGMBOP9IvJTqn9YqoGmyAa24tBN0aw1RvEn69pi1gik2gS3itUaaFM40bGaVjF8nAgfhtkicTTa9lZJjY2uVUlNCFuNn+TioWGB+lJ4Sc+H8B/EyO85qpYXmV2boi0rAqXMIOKuN2nHMS5oLY5z3va7tNsu5H86Os3ounEaHzMu7wowKoFiaK4/TQidndZ5lcP69eJnDOnFfMI4cnFt1DfVCt3TDMTyx8mCMX3mC39EcN1upOXDqeJzS0jhhwISda+u8cLq2Ky2KktuJuXD+qngpg5MsJcH9NY7xOI8zRH2S+E72pM7kyOIsbVukAv9OgDUx60YpizNpLYlTaCFHhcTuaht/u1i2rzPC7sz+wcEJSxG/sn8U5jLv1dvksZf4lb26Ri/gbNaVmqoxDY2fMkME/ahj/J2OEjgn6QJcYHpz4ewUVHDXVdt2Y9/JvvZ6UzMJg0ks3FxdjZfPhVjnH4uXkjhp+nG8DYmsUdZfiXeQiTZJk72J+8Hqmox1ll5IYhQfAnKR9bAJLGEpHb0yYr/gOQiuTrtGWLZzcbInYbITnK1QiOjFrKtWPhIgA/56tQ1CekEgdjfq9MPj3oX4zl8WLyVxymTECmAp0ZOVubXNrhHeCVvXrGuYHafGS/H2zfm2mc4lQ5057hN2PK3TbXEZyRB+jOBCrM6minGi+2/gFpAKKhL7QvBqJBv6yI+amo0X9FmxWPSOYVoj/wDhhJidRxdecwEZV2trW4iiD5HnGLAZdTrmwDn5TuwsMU9PdFs97FylbZ2daOfY1tYBIUFbJ7iOzF6qAVa7Nqlco/pfdzFO94MwokkpKgqBxCFyCLeyWql7nhzPsfttZscUxJggKixW+oqmN7SQbfyfiJeSON/QyATft6eBpo5KQ3KKXhHkV5dqZXbfWQan3H6H3scfP/s+85w1lozL7aGQ3S6P9072ykMdHXYN/4pZcOsFlcYb92qsh1JcZu+UwQkj6hyOTU6E7KDCWX5+rGk2mvVkHx/5qs2srZJdQSJD8sablBca6FwAzl8SLyVxxijNt8gqHjj4nvacBoXkK9gEv/eBlWh4Lpw/mNDcyxfgGPbAvQpZTIMkJpP91Ne//q5dGqKEV8wOntarZOP0mcz7Yfk4wbx/ulu2ltKMW1d81k3G+KRN16DlQ97wztMOFX0lFothnBcp7zsTMBcWcgTyfuHUeJOyE8R4Auy2+4dVU587FCRwmPXVtkaHangOnB3SSdkKln/i4WKCDx/a9QTIS7se2BhkmK8nJ6S9MhLIcv7tG57atGnzRvTswiPgvMLjxOf9Gw98u1t179kTggpr0PxQDfOvmfjZfa+cUaGp/q2X/v/L7PJJ+1vgV6YXgPNnxEtpnHG55oWvEG09DH5QEyGm4995stlo6bZ2z4ETBfGKA2wmEExE0BMlXwq/kJh4WDMZkmtWpATa6E1F/NgpUogz5cEZoul0MOXneHM+aasxqWTnNvHVe/wp8s++RFMRV2CQmnh7dph3H6fcLr+yiS2Oiv07j1crnfVW4YCuPM7Wt+2aqocKnuoKwkoouZ3iA/useKJMIU5ftLBsgt3aDMYp02joFdFiDT0rY5M/6GxdEM6fFy8ZnL2yDI4p2GdIk/SKz0YKdGV2HrcpdXrVlRidm7mtuHzbdCes1d3Pf6nUu9xc6qWPeOXx2BP5A+aKrvFlcZ4jOL/qK3iSzj5ms+lMVk1vnFKsKPFyc/TT+ARsiXHCziXzpAZwdLxtp6z3n/LlPuX2B08dsSlrzMMyDXUlN3NbcXm0L5fGYyrLhu2pwi57/MF7afAnXvCuOQPGsBv2YJzB/c6pNzTfx3t639d+lPueh9v3peM2G1LBO4G2Vk894nMX1v3JWK+0bUE4/1S8ZHDaKQHHhSlhsjZ8oy7KoH8BFr2CFPzSaSXYpllLU/HJtXmZS5Sfxjw1qgbdswfZFMO/VuB2R5joyn3PO1Sx3gnY+dz/mSCu3xUIs6eOGAlO3x6E8zr+zp5sOr4ziIu73Ey07lkboqlFZ7BvI576b9QFmQCu3OMOML6V+16dQtvcBeFcRIG5Sd5fO2U0o6dl051vySmro/H0i3UrIXX3I8seRS+8Oi1aMKyJUDw/c4nyKPyRvqOhtWbjkWfX1e3GN+vqtm04raxxNkyNT4ak4D4b7t+A6t9+cNkRY41Od4qvQaVJJnF1p9B7to9uQMW310FR/K6t1qpBZ7BouKwO56MvHsSVr6x7ZMN7SEEFurvEOFvbpN4rKofTaHTiA2EUIh+l6GG906gk7w4bITQ2m7qBprSjozBzifKQ1ik9moREs04JdeC3uREPANKgik1KO2DAzgFtWzN+BVTnvGAxX3BC2O1sUFDvyKFk/QUIw1GqzojON4y4rB4GlEQ9ZLgsRENUd02N7gOCE3iEcNdBXVkSn8e0dkzc+YhVqzc78elCo9li0qpkVBw8U3HmEuURz7ZQfK3sE1qTGWEy4iFpbLQ0kKgaGqBkiDZKbzSjp7/dWofFYhqGSMwuvxOzak2QONU97LBgJZyQCVQQYkgYronkG0TDGnQO4nSa9fVT6NnWkuOcbn0rlIxVTWmnaH50AZo0TsXoKW29CcSh7bbKNEk59vNFmUsm4SrsvWvHaZW2voGcUTSQeiZDHRDDt030vgn3HEL1sRhknVIh83v7rQlvTKGa4hOhPIh2WEXH0AkA2afj4eI1hKpNfN1zROx3Byeyr16IAmMZdTCMOIR3NG21Wmm4k8xEw0WZSybhHkvlvRSk40qgGlQPFbejelADci+6x1cfj6OsMY0Xbnd22CfRd6o33pvk89DjqKhAM0dDmVA5yhBa4EZpkXF2doTi3qQ3Rx0Ewy7vTVJIkr3yUKYnJTMXJeEeQx0hedxLCeLtldulJAvg6Ajx9SfjUL1UKo/39hJinahp9F0qzeRBRfMC8jwN+Qxz0Zz+MVlFohLr6XtqAAAAR3RFWHRTb2Z0d2FyZQBAKCMpSW1hZ2VNYWdpY2sgNS4xLjAgMDAvMDEvMDEgUTo4IGNyaXN0eUBteXN0aWMuZXMuZHVwb250LmNvbYZbzesAAAAqdEVYdFNpZ25hdHVyZQAzOWY4N2UxZWUxYTI1ZjhjZmYwZjkzYjAwMGY3OWE5NLBiqVIAAAAASUVORK5CYII=';

    header('Content-Type: image/png');
    header('Cache-Control: public');
    echo base64_decode($img_encoded);
}
