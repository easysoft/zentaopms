<?php
global $lang, $app;
$app->loadLang('repo');

$config->program = new stdclass();
$config->program->suffix['c']    = "cpp";
$config->program->suffix['cpp']  = "cpp";
$config->program->suffix['asp']  = "asp";
$config->program->suffix['php']  = "php";
$config->program->suffix['cs']   =  "cs";
$config->program->suffix['sh']   = "bash";
$config->program->suffix['jsp']  = "java";
$config->program->suffix['lua']  = "lua";
$config->program->suffix['sql']  = "sql";
$config->program->suffix['js']   = "javascript";
$config->program->suffix['ini']  = "ini";
$config->program->suffix['conf'] = "apache";
$config->program->suffix['bat']  = "dos";
$config->program->suffix['py']   = "python";
$config->program->suffix['rb']   = "ruby";
$config->program->suffix['as']   = "actionscript";
$config->program->suffix['html'] = "xml";
$config->program->suffix['xml']  = "xml";
$config->program->suffix['htm']  = "xml";
$config->program->suffix['pl']   = "perl";

$config->repo->cacheTime   = 10;
$config->repo->syncTime    = 10;
$config->repo->batchNum    = 100;
$config->repo->svnBatchNum = 10;
$config->repo->images      = '|png|gif|jpg|ico|jpeg|bmp|';
$config->repo->binary      = '|pdf|';
$config->repo->synced      = '';

$config->repo->repoSyncLog = new stdclass();
$config->repo->repoSyncLog->one            = '1';
$config->repo->repoSyncLog->done           = array('done', '完成', 'git/attributes');
$config->repo->repoSyncLog->total          = array('Total', '总数');
$config->repo->repoSyncLog->fatal          = array('fatal', '致命');
$config->repo->repoSyncLog->error          = array('error', '错误');
$config->repo->repoSyncLog->failed         = array('failed', '失败');
$config->repo->repoSyncLog->finish         = 'finish';
$config->repo->repoSyncLog->emptyRepo      = array('empty repository', '空仓库');
$config->repo->repoSyncLog->finishCount    = array('Counting objects: 100%');
$config->repo->repoSyncLog->logFilePrefix  = '/log/clone.progress.';
$config->repo->repoSyncLog->finishCompress = array('Compressing objects: 100%');

$config->repo->editor = new stdclass();
$config->repo->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->repo->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->repo->editor->view   = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->repo->editor->diff   = array('id' => 'commentText', 'tools' => 'simpleTools');

$config->repo->switcherModuleList = array('repo', 'job', 'compile', 'mr');
$config->repo->switcherMethodList = array('browse', 'review', 'view', 'diff', 'log', 'revision', 'blame');

$config->repo->create = new stdclass();
$config->repo->create->requiredFields = 'product,SCM,name,encoding';

$config->repo->createRepo = new stdclass();
$config->repo->createRepo->requiredFields = 'product,name';

$config->repo->edit = new stdclass();
$config->repo->edit->requiredFields = 'product,SCM,name,encoding';

$config->repo->svn = new stdclass();
$config->repo->svn->requiredFields = 'account,password';

$config->repo->gitlab = new stdclass;
$config->repo->gitlab->perPage = 300;
$config->repo->gitlab->apiPath = "%s/api/v4/projects/%s/repository/";

$config->repo->gitea = new stdclass;
$config->repo->gitea->apiPath = "%s/api/v1/repos/%s/";

$config->repo->gitServiceList     = array('gitlab', 'gitea', 'gogs');
$config->repo->gitServiceTypeList = array('Gitlab', 'Gitea', 'Gogs');
$config->repo->gitTypeList        = array('Gitlab', 'Gitea', 'Gogs', 'Git');

$config->repo->rules['module']['task']     = 'Task';
$config->repo->rules['module']['bug']      = 'Bug';
$config->repo->rules['module']['story']    = 'Story';
$config->repo->rules['task']['start']      = 'Start';
$config->repo->rules['task']['finish']     = 'Finish';
$config->repo->rules['task']['logEfforts'] = 'Effort';
$config->repo->rules['task']['consumed']   = 'Cost';
$config->repo->rules['task']['left']       = 'Left';
$config->repo->rules['bug']['resolve']     = 'Fix';
$config->repo->rules['id']['mark']         = '#';
$config->repo->rules['id']['split']        = ',';
$config->repo->rules['mark']['consumed']   = ':';
$config->repo->rules['mark']['left']       = ':';
$config->repo->rules['unit']['consumed']   = 'h';
$config->repo->rules['unit']['left']       = 'h';

$config->repo->fileExt["abap"]             = array('.abap');
$config->repo->fileExt["apex"]             = array('.cls');
$config->repo->fileExt["azcli"]            = array('.azcli');
$config->repo->fileExt["bat"]              = array('.bat', '.cmd');
$config->repo->fileExt["c"]                = array('.c', '.h');
$config->repo->fileExt["cameligo"]         = array('.mligo');
$config->repo->fileExt["clojure"]          = array('.clj', '.cljs', '.cljc', '.edn');
$config->repo->fileExt["coffeescript"]     = array('.coffee');
$config->repo->fileExt["cpp"]              = array('.cpp', '.cc', '.cxx', '.hpp', '.hh', '.hxx');
$config->repo->fileExt["csharp"]           = array('.cs', '.csx', '.cake');
$config->repo->fileExt["css"]              = array('.css');
$config->repo->fileExt["dart"]             = array('.dart');
$config->repo->fileExt["dockerfile"]       = array('.dockerfile');
$config->repo->fileExt["ecl"]              = array('.ecl');
$config->repo->fileExt["fsharp"]           = array('.fs', '.fsi', '.ml', '.mli', '.fsx', '.fsscript');
$config->repo->fileExt["go"]               = array('.go');
$config->repo->fileExt["graphql"]          = array('.graphql', '.gql');
$config->repo->fileExt["handlebars"]       = array('.handlebars', '.hbs');
$config->repo->fileExt["hcl"]              = array('.tf', '.tfvars', '.hcl');
$config->repo->fileExt["html"]             = array('.html', '.htm', '.shtml', '.xhtml', '.mdoc', '.jsp', '.asp', '.aspx', '.jshtm');
$config->repo->fileExt["ini"]              = array('.ini', '.properties', '.gitconfig');
$config->repo->fileExt["java"]             = array('.java', '.jav');
$config->repo->fileExt["javascript"]       = array('.js', '.es6', '.jsx', '.mjs');
$config->repo->fileExt["julia"]            = array('.jl');
$config->repo->fileExt["kotlin"]           = array('.kt');
$config->repo->fileExt["less"]             = array('.less');
$config->repo->fileExt["lexon"]            = array('.lex');
$config->repo->fileExt["lua"]              = array('.lua');
$config->repo->fileExt["m3"]               = array('.m3', '.i3', '.mg', '.ig');
$config->repo->fileExt["markdown"]         = array('.md', '.markdown', '.mdown', '.mkdn', '.mkd', '.mdwn', '.mdtxt', '.mdtext');
$config->repo->fileExt["mips"]             = array('.s');
$config->repo->fileExt["msdax"]            = array('.dax', '.msdax');
$config->repo->fileExt["objective-c"]      = array('.m');
$config->repo->fileExt["pascal"]           = array('.pas', '.p', '.pp');
$config->repo->fileExt["pascaligo"]        = array('.ligo');
$config->repo->fileExt["perl"]             = array('.pl');
$config->repo->fileExt["php"]              = array('.php', '.php4', '.php5', '.phtml', '.ctp');
$config->repo->fileExt["postiats"]         = array('.dats', '.sats', '.hats');
$config->repo->fileExt["powerquery"]       = array('.pq', '.pqm');
$config->repo->fileExt["powershell"]       = array('.ps1', '.psm1', '.psd1');
$config->repo->fileExt["pug"]              = array('.jade', '.pug');
$config->repo->fileExt["python"]           = array('.py', '.rpy', '.pyw', '.cpy', '.gyp', '.gypi');
$config->repo->fileExt["r"]                = array('.r', '.rhistory', '.rmd', '.rprofile', '.rt');
$config->repo->fileExt["razor"]            = array('.cshtml');
$config->repo->fileExt["redis"]            = array('.redis');
$config->repo->fileExt["restructuredtext"] = array('.rst');
$config->repo->fileExt["ruby"]             = array('.rb', '.rbx', '.rjs', '.gemspec', '.pp');
$config->repo->fileExt["rust"]             = array('.rs', '.rlib');
$config->repo->fileExt["sb"]               = array('.sb');
$config->repo->fileExt["scala"]            = array('.scala', '.sc', '.sbt');
$config->repo->fileExt["scheme"]           = array('.scm', '.ss', '.sch', '.rkt');
$config->repo->fileExt["scss"]             = array('.scss');
$config->repo->fileExt["shell"]            = array('.sh', '.bash');
$config->repo->fileExt["sophia"]           = array('.aes');
$config->repo->fileExt["sol"]              = array('.sol');
$config->repo->fileExt["sql"]              = array('.sql');
$config->repo->fileExt["st"]               = array('.st', '.iecst', '.iecplc', '.lc3lib');
$config->repo->fileExt["swift"]            = array('.swift');
$config->repo->fileExt["systemverilog"]    = array('.sv', '.svh');
$config->repo->fileExt["tcl"]              = array('.tcl');
$config->repo->fileExt["twig"]             = array('.twig');
$config->repo->fileExt["typescript"]       = array('.ts', '.tsx');
$config->repo->fileExt["vb"]               = array('.vb');
$config->repo->fileExt["verilog"]          = array('.v', '.vh');
$config->repo->fileExt["xml"]              = array('.xml', '.dtd', '.ascx', '.csproj', '.config', '.wxi', '.wxl', '.wxs', '.xaml', '.svg', '.svgz', '.opf', '.xsl');
$config->repo->fileExt["yaml"]             = array('.yaml', '.yml');

$config->repo->search['module'] = 'repo';
$config->repo->search['fields']['name']     = $lang->repo->name;
$config->repo->search['fields']['product']  = $lang->repo->product;
$config->repo->search['fields']['projects'] = $lang->repo->projects;
$config->repo->search['fields']['SCM']      = $lang->repo->SCM;

$config->repo->search['params']['name']     = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->repo->search['params']['product']  = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->repo->search['params']['projects'] = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->repo->search['params']['SCM']      = array('operator' => '=', 'control' => 'select', 'values' => $lang->repo->scmList);
