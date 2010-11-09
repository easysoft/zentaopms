<?php
/**
 * The convert module Korean file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->convert->common  = '수입';
$lang->convert->next    = '다음';
$lang->convert->pre     = '뒤로';
$lang->convert->reload  = '새로고침';
$lang->convert->error   = '오류';

$lang->convert->start   = '시작 가져오기';
$lang->convert->desc    = <<<EOT
<p>Welcome to use this convert wizard which will help you to import other system data to ZenTaoPMS.</p>
<strong>Importing is dangerous. Be sure to backup your database and other data files and sure nobody is using pms when importing.</strong>
EOT;

$lang->convert->selectSource     = '선택 소스 시스템 및 버전';
$lang->convert->source           = '소스 시스템';
$lang->convert->version          = '버전';
$lang->convert->mustSelectSource = "시스템을 소스를 선택합니다";

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');

$lang->convert->setting     = '설정';
$lang->convert->checkConfig = '설정 확인';

$lang->convert->ok         = '확인 통과 (√)';
$lang->convert->fail       = '체크 실패 (×)';

$lang->convert->settingDB  = '설정 데이터베이스';
$lang->convert->dbHost     = '데이터베이스 서버';
$lang->convert->dbPort     = '서버 포트';
$lang->convert->dbUser     = '데이터베이스 사용자';
$lang->convert->dbPassword = '데이터베이스 암호';
$lang->convert->dbName     = '%s database';
$lang->convert->dbCharset  = '%s 데이터베이스 코드';
$lang->convert->dbPrefix   = '%s table prefix';
$lang->convert->installPath= '%s installed path';

$lang->convert->checkDB    = '데이터베이스';
$lang->convert->checkTable = '테이블';
$lang->convert->checkPath  = '설치 경로';

$lang->convert->execute    = '가져오기 실행';
$lang->convert->item       = '수입 품목';
$lang->convert->count      = '카운트';
$lang->convert->info       = '정보';

$lang->convert->bugfree->users    = '사용자';
$lang->convert->bugfree->projects = '프로젝트';
$lang->convert->bugfree->modules  = '모듈';
$lang->convert->bugfree->bugs     = '곤충';
$lang->convert->bugfree->cases    = '케이스';
$lang->convert->bugfree->results  = '결과';
$lang->convert->bugfree->actions  = '역사';
$lang->convert->bugfree->files    = '파일';

$lang->convert->errorConnectDB     = '연결하는 데이터베이스 서버가 실패했습니다.';
$lang->convert->errorFileNotExits  = 'File %s not exits.';
$lang->convert->errorUserExists    = 'User %s exits already.';
$lang->convert->errorCopyFailed    = 'file %s copy failed.';
