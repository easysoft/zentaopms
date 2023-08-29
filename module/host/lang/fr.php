<?php
$lang->host->common       = 'Serveurs';
$lang->host->browse       = 'Liste Serveurs';
$lang->host->create       = 'Ajouter Serveur';
$lang->host->view         = 'Détail Serveur';
$lang->host->edit         = 'Editer';
$lang->host->editAction   = 'Editer Serveur';
$lang->host->delete       = 'Supprimer';
$lang->host->deleteAction = 'Supprimer Serveur';
$lang->host->treemap      = 'Topologie Serveur';
$lang->host->changeStatus = 'Online/Offline';
$lang->host->byQuery      = 'Recherche';
$lang->host->reason       = 'Raison';

$lang->host->name         = 'Nom';
$lang->host->type         = 'Type';
$lang->host->hardwareType = 'Type Hardware';
$lang->host->group        = 'Groupe Serveur';
$lang->host->admin        = 'Admin';
$lang->host->cabinet      = 'Local';
$lang->host->intranet     = 'IP Privée';
$lang->host->extranet     = 'Extranet IP';
$lang->host->mac          = 'MAC';
$lang->host->cpuBrand     = 'CPU Marque';
$lang->host->cpuModel     = 'CPU Modèle';
$lang->host->cpuNumber    = 'CPU Numéro';
$lang->host->cpuCores     = 'CPU Cores';
$lang->host->cpuRate      = 'CPU Freq.';
$lang->host->memory       = 'Capacité Mémoire';
$lang->host->serverRoom   = 'IDC';

$lang->host->createdBy   = 'Créé par';
$lang->host->createdDate = 'Créé le';
$lang->host->editedBy    = 'Edité par';
$lang->host->editedDate  = 'Edité le';
$lang->host->all         = 'Tous Serveurs';

$lang->host->empty = 'No Host';

$lang->host->groupMaintenance = 'Gérer Groupes';

$lang->host->status = 'Statut';
$lang->host->statusList['online']  = 'Online';
$lang->host->statusList['busy']    = 'Overloaded';
$lang->host->statusList['offline'] = 'Offline';

$lang->host->online  = 'On';
$lang->host->busy    = 'Overload';
$lang->host->offline = 'Off';

$lang->host->onlineReason  = 'Raison Online';
$lang->host->offlineReason = 'Raison Offline';

$lang->host->unitList['GB'] = 'GB';
$lang->host->unitList['TB'] = 'TB';

$lang->host->cpuBrandList[''] = '';
$lang->host->cpuBrandList['intel'] = 'Intel';
$lang->host->cpuBrandList['amd']   = 'AMD';

$lang->host->database = 'Database';
$lang->host->databaseList['']             = '';
$lang->host->databaseList['mysql55']      = 'MySQL 5.5';
$lang->host->databaseList['mysql56']      = 'MySQL 5.6';
$lang->host->databaseList['mysql57']      = 'MySQL 5.7';
$lang->host->databaseList['mysql80']      = 'MySQL 8.0';
$lang->host->databaseList['oracle10g']    = 'Oracle 10g';
$lang->host->databaseList['oracle11g']    = 'Oracle 11g';
$lang->host->databaseList['mongodb30']    = 'MongoDB 3.0';
$lang->host->databaseList['mongodb34']    = 'MongoDB 3.4';
$lang->host->databaseList['postgresql95'] = 'PostgreSQL 9.5';
$lang->host->databaseList['postgresql96'] = 'PostgreSQL 9.6';

$lang->host->webserver = 'Serveur Web';
$lang->host->webserverList['']           = '';
$lang->host->webserverList['apache2425'] = 'Apache 2.4.25';
$lang->host->webserverList['apache2426'] = 'Apache 2.4.26';
$lang->host->webserverList['apache2427'] = 'Apache 2.4.27';
$lang->host->webserverList['nginx181']   = 'Nginx 1.8.1';
$lang->host->webserverList['nginx1102']  = 'Nginx 1.10.2';
$lang->host->webserverList['nginx1115']  = 'Nginx 1.11.5';
$lang->host->webserverList['iis6']       = 'IIS 6';
$lang->host->webserverList['iis7']       = 'IIS 7';
$lang->host->webserverList['iis8']       = 'IIS 8';
$lang->host->webserverList['iis10']      = 'IIS 10';

$lang->host->language = 'Langage';
$lang->host->languageList['']   = '';
$lang->host->languageList['php56'] = 'PHP 5.6';
$lang->host->languageList['php70'] = 'PHP 7.0';
$lang->host->languageList['java7'] = 'JAVA 7';
$lang->host->languageList['java8'] = 'JAVA 8';
$lang->host->languageList['c#3']   = 'C# 3.0';
$lang->host->languageList['c#4']   = 'C# 4.0';

$lang->host->featureBar['browse']['all']        = $lang->host->all;
$lang->host->featureBar['browse']['serverroom'] = 'Physical Topology';
$lang->host->featureBar['browse']['group']      = 'Logical Topology';

$lang->host->serverModel              = 'Modèle Serveur';
$lang->host->hostType                 = 'Type Serveur';
$lang->host->hostTypeList['physical'] = 'Physique';
$lang->host->hostTypeList['virtual']  = 'Virtuel';

$lang->host->osVersion             = 'OS Version';
$lang->host->osName                = 'OS Name';
$lang->host->zap                   = 'Agent Port';
$lang->host->instanceNum           = 'Max instances';
$lang->host->pri                   = 'Priority';
$lang->host->tags                  = 'Platform Label';
$lang->host->provider              = 'Priority';
$lang->host->bridgeID              = 'Virtual Bridge';

$lang->host->osNameList['linux']   = 'Linux';
$lang->host->osNameList['windows'] = 'Microsoft Windows';
$lang->host->osNameList['solaris'] = 'Solaris';
$lang->host->osNameList['netware'] = 'Novell NetWare';
$lang->host->osNameList['esx']     = 'VMware ESX';
$lang->host->osNameList['other']   = 'Autres';

$lang->host->tagsList['vm']     = 'VM';
$lang->host->tagsList['native'] = 'Native';
$lang->host->providerList['native'] = 'Native';

$lang->host->linux   = 'Linux';
$lang->host->windows = 'Microsoft Windows';
$lang->host->solaris = 'Solaris';
$lang->host->netware = 'Novell NetWare';
$lang->host->esx     = 'VMware ESX';
$lang->host->other   = 'Autres';

$lang->host->linuxList['']   = '';
$lang->host->linuxList['centos65'] = 'CentOS 6.5';
$lang->host->linuxList['centos66'] = 'CentOS 6.6';
$lang->host->linuxList['centos67'] = 'CentOS 6.7';
$lang->host->linuxList['centos70'] = 'CentOS 7.0';
$lang->host->linuxList['centos71'] = 'CentOS 7.1';

$lang->host->windowsList[''] = '';
$lang->host->windowsList['win10-pro']      = 'Windows 10 pro';
$lang->host->windowsList['winserver08x64'] = 'Windows Server2008 x64';
$lang->host->windowsList['winserver12']    = 'Windows Server2012';
$lang->host->windowsList['winserver16']    = 'Windows Server2016';

$lang->host->solarisList[''] = '';
$lang->host->solarisList['solaris10']    = 'Solaris 10';
$lang->host->solarisList['solaris10x64'] = 'Solaris 10 x64';
$lang->host->solarisList['solaris11']    = 'Solaris 11';

$lang->host->netwareList[''] = '';
$lang->host->netwareList['netware5'] = 'NetWare 5';
$lang->host->netwareList['netware6'] = 'NetWare 6';

$lang->host->esxList[''] = '';
$lang->host->esxList['esx']  = 'VMware ESX/ESXi 4';
$lang->host->esxList['esx5'] = 'VMware ESXi 5';
$lang->host->esxList['esx6'] = 'VMware ESXi 6';

$lang->host->otherList['']   = '';
$lang->host->otherList['freebsd']   = 'FreeBSD';
$lang->host->otherList['freebsdx64']   = 'FreeBSD X64';

$lang->host->diskSize   = 'Taille Disque';
$lang->host->diskType   = 'Type Disque';
$lang->host->diskTypeList['hdd'] = 'HDD';
$lang->host->diskTypeList['ssd'] = 'SSD';

$lang->host->confirmDelete = 'Voulez-vous supprimer ce serveur ?';

$lang->host->notice = new stdclass();
$lang->host->notice->memory    = 'La capacité mémoire doit être numérique !';
$lang->host->notice->diskSize  = 'La capacité du disque doit être numérique !';
$lang->host->notice->cpuNumber = 'CPU number doit être numérique !';
$lang->host->notice->cpuCores  = 'CPU cores doit être numérique !';
$lang->host->notice->int       = '『%s』should be a positive integer!';
$lang->host->notice->ip        = '『%s』incorrect format!';
