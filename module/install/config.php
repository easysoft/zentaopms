<?php
$config->install->downloadGitfoxURL = array();
$config->install->downloadGitfoxURL['linux'] = 'https://pkg.zentao.net/gitfox/2.0_beta1/linux-2.0.beta1.zip';
$config->install->downloadGitfoxURL['win']   = 'https://pkg.zentao.net/gitfox/2.0_beta1/windows-2.0.beta1.zip';

$config->install->installGitfox = array();
$config->install->installGitfox['linux'] = <<<EOT
#!/bin/bash
INSTALL_DIR="%s"
if [ ! -d "\${INSTALL_DIR}" ]; then
    mkdir -p "\${INSTALL_DIR}"
fi
cd "\${INSTALL_DIR}" || { echo "Error: cd \${INSTALL_DIR} failed"; exit 1; }

GITFOX_ZIP="gitfox_latest.zip"
GITFOX_URL="{$config->install->downloadGitfoxURL['linux']}"

if command -v wget >/dev/null 2>&1; then
    wget -O "\${GITFOX_ZIP}" "\${GITFOX_URL}"
elif command -v curl >/dev/null 2>&1; then
    curl -L -o "\${GITFOX_ZIP}" "\${GITFOX_URL}"
else
    echo "Error: wget or curl is required"
    exit 1
fi

if command -v unzip >/dev/null 2>&1; then
    unzip -j -o "\${GITFOX_ZIP}" -d "\${INSTALL_DIR}"
else
    if command -v apt >/dev/null 2>&1; then
        apt update && apt install -y unzip
    elif command -v yum >/dev/null 2>&1; then
        yum install -y unzip
    elif command -v brew >/dev/null 2>&1; then
        brew install unzip
    else
        echo "Error: unzip is required"
        exit 1
    fi
    unzip -j -o "\${GITFOX_ZIP}" -d "\${INSTALL_DIR}"
fi

rm -f "\${GITFOX_ZIP}"

chmod +x "\${INSTALL_DIR}/gitfox"
"\${INSTALL_DIR}/gitfox" install
"\${INSTALL_DIR}/gitfox" status
"\${INSTALL_DIR}/gitfox" help

echo "GitFox has been installed to \${INSTALL_DIR}"
EOT;

$config->install->installGitfox['win'] = <<<EOT
@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

set "INSTALL_DIR=%s"
if not exist "%INSTALL_DIR%" (
    mkdir "%INSTALL_DIR%"
)
cd /d "%INSTALL_DIR%" || (
    echo Error: cd "%INSTALL_DIR%" failed
    pause
    exit /b 1
)

set "GITFOX_ZIP=gitfox_latest.zip"
set "GITFOX_URL={$config->install->downloadGitfoxURL['win']}"

where certutil >nul 2>&1
if %errorlevel% equ 0 (
    certutil -urlcache -split -f "%GITFOX_URL%" "%GITFOX_ZIP%"
) else (
    bitsadmin /transfer "GitFoxInstall" "%GITFOX_URL%" "%cd%\%GITFOX_ZIP%"
)

if not exist "%GITFOX_ZIP%" (
    echo Error: wget or curl is required
    pause
    exit /b 1
)

echo Set objShell = CreateObject("Shell.Application") > "%temp%\unzip.vbs"
echo Set objSource = objShell.NameSpace("%cd%\%GITFOX_ZIP%") >> "%temp%\unzip.vbs"
echo Set objTarget = objShell.NameSpace("%cd%") >> "%temp%\unzip.vbs"
echo objTarget.CopyHere objSource.Items, 20 >> "%temp%\unzip.vbs"
cscript //nologo "%temp%\unzip.vbs"

del /f /q "%GITFOX_ZIP%"

"%INSTALL_DIR%\gitfox.exe" install
"%INSTALL_DIR%\gitfox.exe" status
"%INSTALL_DIR%\gitfox.exe" help

echo "GitFox has been installed to %INSTALL_DIR%"
EOT;
