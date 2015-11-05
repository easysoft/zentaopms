<?php
/**
 * change log.
 * Fix for chanzhi. 20150713 chujilu@cnezsoft.com
 *
 */
class HTMLPurifier_Filter_YouTube extends HTMLPurifier_Filter
{

    /**
     * @type string
     */
    public $name = 'YouTube';

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, $config, $context)
    {
        $pre_regex_list = array();
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/player\.youku\.com\/player\.php\/[\/\-_A-Za-z0-9=]*\/v.swf)".*\/>.*/i';
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/player\.video\.qiyi\.com\/[\/\-_A-Za-z0-9=]*\.swf[\/\-_A-Za-z0-9=]*)".*\/>.*/i';
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/www\.tudou\.com\/[\/\-\&_A-Za-z0-9=]*\/v\.swf)".*\/>.*/i';
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/share\.vrs\.sohu\.com\/[\/\-_A-Za-z0-9=]*\/v.swf[\/\-\&_A-Za-z0-9=]*)".*\/>.*/i';
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/static\.video\.qq\.com\/TPout\.swf\?[\/\-\&_A-Za-z0-9=]*)".*\/>.*/i';
        $pre_regex_list[] = '/.*<embed.*src="(http\:\/\/player\.ku6\.com\/[\/\-\._A-Za-z0-9=]*\/v\.swf)".*\/>.*/i';
        $pre_replace = '<span class="chanzhi-embed">$1</span>';
        foreach($pre_regex_list as $pre_regex) $html = preg_replace($pre_regex, $pre_replace, $html);
        return $html;
    }

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, $config, $context)
    {
        $post_regex = '/<span class="chanzhi-embed">(.*)<\/span>/i';
        return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
    }

    /**
     * @param $url
     * @return string
     */
    protected function armorUrl($url)
    {
        return str_replace('--', '-&#45;', $url);
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function postFilterCallback($matches)
    {
        $url = $this->armorUrl($matches[1]);
        return '<object width="425" height="350" type="application/x-shockwave-flash" ' .
        'data="' . $url . '">' .
        '<param name="movie" value="' . $url . '"></param>' .
        '<!--[if IE]>' .
        '<embed src="' . $url . '"' .
        'type="application/x-shockwave-flash"' .
        'wmode="transparent" width="425" height="350" />' .
        '<![endif]-->' .
        '</object>';
    }
}

// vim: et sw=4 sts=4
