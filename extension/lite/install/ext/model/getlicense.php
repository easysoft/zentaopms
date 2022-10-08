<?php
/**
 * Get license according the client lang.
 *
 * @access public
 * @return string
 */
public function getLicense()
{
    $clientLang = $this->app->getClientLang();
    $licenseCN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.LITE.CN');
    $licenseEN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.LITE.EN');

    if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') return $licenseCN . $licenseEN;
    return $licenseEN . $licenseCN;
}
