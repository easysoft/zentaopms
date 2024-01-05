<?php
$p = new prepareUpdate();

class prepareUpdate
{
    public $internalZT;
    public $mysqlConfig;

    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->initZT();
        $this->initMysql();
        $this->addChangelog();
        $this->changeVersion();
        $this->addReleaseDetail();
        $this->addLastVersion();
        $this->addVersionCorrespondence();
        $this->addConfirmedSql();
        $this->exportStandardSql();

        $this->checkInstallSql();
        $this->checkLang();
    }

    /**
     * Init mysql config.
     *
     * @access public
     * @return void
     */
    public function initMysql()
    {
        $this->mysqlConfig = new stdclass();
        $this->mysqlConfig->account      = 'zentao';
        $this->mysqlConfig->password     = '123456';
        $this->mysqlConfig->databaseName = 'zentaostandard';
    }

    /**
     * Init zentao config.
     *
     * @access public
     * @return void
     */
    public function initZT()
    {
        $this->internalZT = new stdclass();
        $this->internalZT->apiRoot  = '';
        $this->internalZT->account  = '';
        $this->internalZT->password = '';
        $this->internalZT->token    = $this->getZTToken();

        $this->internalZT->pmsProductID = '';
        $this->internalZT->bizProductID = '';
        $this->internalZT->maxProductID = '';
        $this->internalZT->ipdProductID = '';

        $this->getLatestVersion();
    }

    /**
     * Get zentao token.
     *
     * @access public
     * @return void
     */
    public function getZTToken()
    {
        $tokenUrl = "{$this->internalZT->apiRoot}/tokens";
        $data     = array('account' => $this->internalZT->account, 'password' => $this->internalZT->password);

        $response = $this->http($tokenUrl, $data, array(), array(), 'json');
        $response = json_decode($response);

        return $response->token;
    }

    /**
     * Add the changelog of the newest release.
     *
     * @access public
     * @return void
     */
    public function addChangelog()
    {
        $relatedStoriesAndBugs = $this->getRelatedStoriesAndBugs();
        $oldChangelog = file_get_contents('../doc/CHANGELOG');
        file_put_contents('../doc/CHANGELOG', $relatedStoriesAndBugs . "\n" . $oldChangelog);
    }

    /**
     * Get stories and bugs of the releases for changelog.
     *
     * @access public
     * @return string
     */
    public function getRelatedStoriesAndBugs()
    {
        $latestReleases = $this->getLatestRelease();
        $title          = array('pms' => '开源版：', 'biz' => '企业版：', 'max' => '旗舰版：', 'ipd' => 'IPD版：');

        $releaseHeader = date('Y-m-d') . ' ' . $this->internalZT->pmsVersion . "\n";
        $doneStories   = "完成的需求\n";
        $fixedBugs     = "修复的Bug\n";
        foreach($latestReleases as $product => $release)
        {
            $stories = array_filter(explode(',', trim($release->stories, ',')));
            $bugs    = array_filter(explode(',', trim($release->bugs, ',')));

            if(!empty($stories))   $doneStories .= $title[$product] . "\n";
            if(!empty($bugs))      $fixedBugs   .= $title[$product] . "\n";
            foreach($stories as $storyID)
            {
                $storyTitle   = $this->getStoryOrBugTitle('story', $storyID);
                $storyTitle   = htmlspecialchars_decode($storyTitle);
                $doneStories .= $storyTitle ? $storyID . ' ' . $storyTitle . "\n" : '';
            }

            foreach($bugs as $bugID)
            {
                $bugTitle   = $this->getStoryOrBugTitle('bug', $bugID);
                $bugTitle   = htmlspecialchars_decode($bugTitle);
                $fixedBugs .= $bugTitle ? $bugID . ' ' . $bugTitle . "\n" : '';
            }
        }

        return $releaseHeader . $doneStories . $fixedBugs;
    }

    /**
     * Get the title of story or bug.
     *
     * @access public
     * @return string
     */
    public function getStoryOrBugTitle($objectType, $objectID)
    {
        $type      = $objectType == 'story' ? 'stories' : 'bugs';
        $storyUrl  = "{$this->internalZT->apiRoot}/{$type}/{$objectID}";
        $headers[] = "Token: {$this->internalZT->token}";

        $response = $this->http($storyUrl, null, array(), $headers, 'json', 'GET');
        $response = json_decode($response);

        return $response ? $response->title : '';
    }

    /**
     * Get pms, biz and max latest version.
     *
     * @access public
     * @return void
     */
    public function getLatestVersion()
    {
        $releases = $this->getLatestRelease();
        foreach($releases as $product => $release)
        {
            $releaseParse = explode(' ', $release->name);
            $this->internalZT->{$product . 'Version'}   = str_replace('.stable', '', $releaseParse[1]);
            $this->internalZT->{$product . 'VersionAB'} = str_replace('.', '_', $this->internalZT->{$product . 'Version'});
        }
    }

    /**
     * Get last ipd version.
     *
     * @param  object  $lastIPDRelease
     * @access public
     * @return array
     */
    public function getLastIPDVersion($lastIPDRelease)
    {
        $releaseParse = explode(' ', $lastIPDRelease->name);
        $this->internalZT->lastIPDVersion   = str_replace('.stable', '', $releaseParse[1]);
        $this->internalZT->lastIPDVersionAB = str_replace('.', '_', $this->internalZT->lastIPDVersion);
    }

    /**
     * Get pms, biz, max and ipd latest release.
     *
     * @access public
     * @return array
     */
    public function getLatestRelease()
    {
        $pmsReleases      = $this->getReleases($this->internalZT->pmsProductID);
        $pmsLatestRelease = current($pmsReleases);

        $bizReleases      = $this->getReleases($this->internalZT->bizProductID);
        $bizLatestRelease = current($bizReleases);

        $maxReleases      = $this->getReleases($this->internalZT->maxProductID);
        $maxLatestRelease = current($maxReleases);

        $ipdReleases      = $this->getReleases($this->internalZT->ipdProductID);
        $ipdLatestRelease = current($ipdReleases);
        $this->getLastIPDVersion($ipdReleases[1]);

        return array('pms' => $pmsLatestRelease, 'biz' => $bizLatestRelease, 'max' => $maxLatestRelease, 'ipd' => $ipdLatestRelease);
    }

    /**
     * Get releases by api.
     *
     * @param  int  $productID
     * @access public
     * @return array
     */
    public function getReleases($productID)
    {
        $releaseUrl = "{$this->internalZT->apiRoot}/products/{$productID}/releases";
        $headers[]  = "Token: {$this->internalZT->token}";

        $response = $this->http($releaseUrl, null, array(), $headers, 'json', 'GET');
        $response = json_decode($response);

        return $response->releases;
    }

    /**
     * Update the newest version.
     *
     * @access public
     * @return void
     */
    public function changeVersion()
    {
        $lastVersion = file_get_contents('../VERSION');
        $lastVersion = trim($lastVersion);

        $this->internalZT->lastPmsVersion   = $lastVersion;
        $this->internalZT->lastPmsVersionAB = str_replace('.', '_', $lastVersion);

        file_put_contents('../VERSION', $this->internalZT->pmsVersion);
        `sed -i "s/\$config->version       = '$lastVersion'/\$config->version       = '{$this->internalZT->pmsVersion}'/" ../config/config.php`;
    }

    /**
     * Add the release's detail.
     *
     * @access public
     * @return void
     */
    public function addReleaseDetail()
    {
        $date     = date('Y-m-d');
        $releases = $this->getLatestRelease();

        $detail = '';
        foreach($releases as $product => $release) $detail .= $release->desc;

        foreach(array('de', 'fr', 'en', 'zh-cn') as $lang) `sed -i "s/\/\* Release Date. \*\/$/\/* Release Date. *\/\\n\\\$lang->misc->releaseDate['{$this->internalZT->pmsVersion}']        = '$date';/" ../module/misc/lang/$lang.php`;

        `sed -i "s/\/\* Release Detail. \*\/$/\/* Release Detail. *\/\\n\\\$lang->misc->feature->all['{$this->internalZT->pmsVersion}'][]       = array('title' => '$detail', 'desc' => '');/" ../module/misc/lang/zh-cn.php`;
    }

    /**
     * Add the last version for update.
     *
     * @access public
     * @return void
     */
    public function addLastVersion()
    {
        `sed -i "s/ \/\/ pms insert position\.$/\\n\\\$lang->upgrade->fromVersions['{$this->internalZT->pmsVersionAB}']       = '{$this->internalZT->pmsVersion}'; \/\/ pms insert position\./" ../module/upgrade/lang/version.php`;
        `sed -i "s/ \/\/ biz insert position\.$/\\n\\\$lang->upgrade->fromVersions['biz{$this->internalZT->bizVersionAB}']        = 'Biz{$this->internalZT->bizVersion}'; \/\/ biz insert position\./" ../module/upgrade/lang/version.php`;
        `sed -i "s/ \/\/ max insert position\.$/\\n\\\$lang->upgrade->fromVersions['max{$this->internalZT->maxVersionAB}']        = 'Max{$this->internalZT->maxVersion}'; \/\/ max insert position\./" ../module/upgrade/lang/version.php`;
        `sed -i "s/ \/\/ ipd insert position\.$/\\n\\\$lang->upgrade->fromVersions['ipd{$this->internalZT->lastIPDVersionAB}']        = 'Ipd{$this->internalZT->lastIPDVersion}'; \/\/ ipd insert position\./" ../module/upgrade/lang/version.php`;
    }

    /**
     * Add the version correspondence between open source and business.
     *
     * @access public
     * @return void
     */
    public function addVersionCorrespondence()
    {
        `sed -i "s/ \/\/ biz insert position\.$/\\n\\\$config->upgrade->bizVersion['biz{$this->internalZT->bizVersionAB}']        = '{$this->internalZT->pmsVersionAB}'; \/\/ biz insert position\./" ../module/upgrade/config.php`;
        `sed -i "s/ \/\/ max insert position\.$/\\n\\\$config->upgrade->maxVersion['max{$this->internalZT->maxVersionAB}']        = '{$this->internalZT->pmsVersionAB}'; \/\/ max insert position\./" ../module/upgrade/config.php`;
        `sed -i "s/ \/\/ ipd insert position\.$/\\n\\\$config->upgrade->ipdVersion['ipd{$this->internalZT->ipdVersionAB}']        = '{$this->internalZT->pmsVersionAB}'; \/\/ ipd insert position\./" ../module/upgrade/config.php`;
    }

    /**
     * Add the confirm sql for update.
     *
     * @access public
     * @return void
     */
    public function addConfirmedSql()
    {
        if(file_exists("../db/update{$this->internalZT->lastPmsVersion}.sql"))
        {
            `sed -i "s/ \/\/ confirm insert position\.$/\\n             case '{$this->internalZT->lastPmsVersionAB}':\\n                \\\$confirmContent .= file_get_contents(\\\$this->getUpgradeFile('{$this->internalZT->lastPmsVersion}')); \/\/ confirm insert position\./" ../module/upgrade/model.php`;
        }
        else
        {
            `sed -i "s/ \/\/ confirm insert position\.$/\\n             case '{$this->internalZT->lastPmsVersionAB}': \/\/ confirm insert position\./" ../module/upgrade/model.php`;
        }
    }

    /**
     * Export standard sql.
     *
     * @access public
     * @return void
     */
    public function exportStandardSql()
    {
        $updateSqlFile = "../db/update{$this->internalZT->lastPmsVersion}.sql";
        if(!file_exists($updateSqlFile))
        {
            `cp ../db/standard/zentao{$this->internalZT->lastPmsVersion}.sql ../db/standard/zentao{$this->internalZT->pmsVersion}.sql`;
            return true;
        }

        $lastStandardSql = file_get_contents("../db/standard/zentao{$this->internalZT->lastPmsVersion}.sql");
        file_put_contents("/tmp/lastStandard.sql", "SET @@sql_mode='';\n$lastStandardSql");

        `mysql -u{$this->mysqlConfig->account} -p{$this->mysqlConfig->password} -e "DROP DATABASE IF EXISTS {$this->mysqlConfig->databaseName}"`;
        `mysql -u{$this->mysqlConfig->account} -p{$this->mysqlConfig->password} -e "CREATE DATABASE IF NOT EXISTS {$this->mysqlConfig->databaseName}"`;
        `mysql -u{$this->mysqlConfig->account} -p{$this->mysqlConfig->password} -D {$this->mysqlConfig->databaseName} < /tmp/lastStandard.sql`;
        `mysql -u{$this->mysqlConfig->account} -p{$this->mysqlConfig->password} -D {$this->mysqlConfig->databaseName} < $updateSqlFile`;

        `mysqldump -u{$this->mysqlConfig->account} -p{$this->mysqlConfig->password} --compact --no-data {$this->mysqlConfig->databaseName} > ../db/standard/zentao{$this->internalZT->pmsVersion}.sql`;

        $content = file_get_contents("../db/standard/zentao{$this->internalZT->pmsVersion}.sql");
        $content = preg_replace(array("/\/\*[\s\S]*?\*\/;\n/", "/SET (.*?);\n/"), '', $content);
        file_put_contents("../db/standard/zentao{$this->internalZT->pmsVersion}.sql", $content);
    }

    /**
     * Check whether update sql and install sql are consistent.
     *
     * @access public
     * @return void
     */
    public function checkInstallSql()
    {
        $updateSqlFile = "../db/update{$this->internalZT->lastPmsVersion}.sql";
        if(!file_exists($updateSqlFile)) return true;

        $updateSql = file_get_contents($updateSqlFile);
        $sqlList   = explode(';', $updateSql);
        $errorSql  = '';

        foreach($sqlList as $sql)
        {
            preg_match("/(CREATE|ALTER) TABLE (.*?)(`(.*?)`)[\s\S]*/", $sql, $matches);

            if(empty($matches)) continue;

            $table     = $matches[3];
            $updateSql = $matches[0];

            preg_match("/CREATE TABLE (.*)$table [\s\S]*?;/", file_get_contents('../db/zentao.sql'), $matches);
            $createTableSql = $matches[0];

            $errorSql .= $table . ":\n";

            /* Create table sql. */
            if(strpos($updateSql, 'CREATE TABLE') !== false)
            {
                if($updateSql . ';' != $createTableSql) $errorSql .= $updateSql . "\n";
                continue;
            }

            /* Alter table sql. */
            foreach(explode(',', $sql) as $option)
            {
                $option = preg_replace('/ AFTER `.*`/', '', $option);
                $option = trim($option, ';');
                if(stripos($option, 'ADD ') !== false or stripos($option, 'MODIFY ') !== false)
                {
                    preg_match("/(ADD|MODIFY) (COLUMN )*((`(.*?)`) (.*)?)/i", $option, $matches);
                    if(strpos($createTableSql, $matches[3]) === false) $errorSql .= $option . "\n";
                }
                if(stripos($option, 'CHANGE ') !== false)
                {
                    preg_match("/CHANGE (COLUMN )*(`(.*?)`) ((`(.*?)`) (.*)?)/i", $option, $matches);
                    if(strpos($createTableSql, $matches[4]) === false) $errorSql .= $option . "\n";
                }
                if(stripos($option, 'DROP ') !== false)
                {
                    preg_match("/DROP (COLUMN )*(`(.*?)`)/i", $option, $matches);
                    if(strpos($createTableSql, $matches[2]) !== false) $errorSql .= $option . "\n";
                }
            }
        }
        echo $errorSql;
    }

    /**
     * Check lang.
     *
     * @access public
     * @return void
     */
    public function checkLang()
    {
        `php check.php`;
    }

    /**
     * Http.
     *
     * @param  string       $url
     * @param  string|array $data
     * @param  array        $options   This is option and value pair, like CURLOPT_HEADER => true. Use curl_setopt function to set options.
     * @param  array        $headers   Set request headers.
     * @param  string       $dataType
     * @param  string       $method    POST|PATCH|PUT
     * @access public
     * @return string
     */
    public function http($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST')
    {
        if(!is_array($headers)) $headers = (array)$headers;
        if($dataType == 'json')
        {
            $headers[] = 'Content-Type: application/json;charset=utf-8';
            if(!empty($data)) $data = json_encode($data);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sae T OAuth2 v0.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 6000);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);

        if(!empty($data))
        {
            if(is_object($data)) $data = (array) $data;
            if($method == 'POST') curl_setopt($curl, CURLOPT_POST, true);
            if(in_array($method, array('PATCH', 'PUT'))) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if($options) curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $errors   = curl_error($curl);

        curl_close($curl);

        if($errors) return $errors;

        return $response;
    }
}
