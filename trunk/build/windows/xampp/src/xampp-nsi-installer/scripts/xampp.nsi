; Author: Kay Vogelgesang for ApacheFriends XAMPP win32

;---------------------
;Include Modern UI
   !include "MUI.nsh"
;--------------------------------

SetCompressor /solid lzma
XPStyle on
; HM NIS Edit Wizard helper defines
  !define PRODUCT_NAME "XAMPP"
  !define PRODUCT_VERSION "1.7.5"
  !define PRODUCT_PUBLISHER "Kay Vogelgesang, Kai Oswald Seidler, ApacheFriends"
  !define PRODUCT_WEB_SITE "http://www.apachefriends.org"
Caption "XAMPP ${PRODUCT_VERSION} win32"
InstallDirRegKey HKLM "Software\xampp" "Install_Dir"
; InstallDirRegKey HKCU "Software\xampp" ""
Name "${PRODUCT_NAME} ${PRODUCT_VERSION}"
OutFile "F:\xampp-dev\xampp-win32-${PRODUCT_VERSION}-VC9-installer.exe"
;Vista redirects $SMPROGRAMS to all users without this
RequestExecutionLevel admin
BGGradient f87820 FFFFFF FFFFFF
InstallColors FF8080 000030
CheckBitmap "${NSISDIR}\Contrib\Graphics\Checks\classic-cross.bmp"

;--------------------------------
;Language Selection Dialog Settings

;Remember the installer language
  !define MUI_LANGDLL_REGISTRY_ROOT "HKLM"
  !define MUI_LANGDLL_REGISTRY_KEY "Software\xampp"
  !define MUI_LANGDLL_REGISTRY_VALUENAME "lang"

;--------------------------------

; MUI Settings
  !define MUI_ABORTWARNING
  !define MUI_ICON "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon.ico"
  !define MUI_UNICON "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon-uninstall.ico"
  !define MUI_WELCOMEPAGE
  !define MUI_CUSTOMPAGECOMMANDS
  !define MUI_COMPONENTSPAGE
  !define MUI_COMPONENTSPAGE_NODESC

; Welcome page
  !insertmacro MUI_PAGE_WELCOME
; Components
; !insertmacro MUI_PAGE_COMPONENTS
; License page
;!insertmacro MUI_PAGE_LICENSE "${NSISDIR}\license.txt"
; Directory page
  !insertmacro MUI_PAGE_DIRECTORY
             Page custom CustomPageC
; Instfiles page
  !insertmacro MUI_PAGE_INSTFILES
; Finish page
  !insertmacro MUI_PAGE_FINISH
  !insertmacro MUI_UNPAGE_CONFIRM
  !insertmacro MUI_UNPAGE_INSTFILES

;--------------------------------

;Languages

  !insertmacro MUI_LANGUAGE "English" # first language is the default language
  !insertmacro MUI_LANGUAGE "German"
  ; !insertmacro MUI_LANGUAGE "Japanese"

;--------------------------------
;Reserve Files

  ;These files should be inserted before other files in the data block
  ;Keep these lines before any File command
  ;Only for solid compression (by default, solid compression is enabled for BZIP2 and LZMA)

  ReserveFile "xampp.ini"
  ; ReserveFile "xampp-japanese.ini"
  ReserveFile "xampp_home.ini"
 ;  ReserveFile "xampp_home-japanese.ini"
  ReserveFile "xampp-german.ini"
  ReserveFile "xampp_home-german.ini"

  !insertmacro MUI_RESERVEFILE_LANGDLL
  !insertmacro MUI_RESERVEFILE_INSTALLOPTIONS

;--------------------------------
;Variables

  Var INI_VALUE
  Var INI_VALUE2
  Var INI_VALUE3
  Var INI_VALUE4
  Var INI_VALUE5
  Var INST_MESS
  Var INST_MESS1
  Var INST_MESS2
  Var INST_MESS3
  Var INST_MESS4
  Var MESS_INSTDIR1
  Var MESS_INSTDIR2
  Var DB_DEL
  Var NO_DEL
  
InstallDir "c:\xampp"
Icon "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon.ico"
UninstallIcon "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon-uninstall.ico"
ShowInstDetails show
ShowUninstDetails show

Section "XAMPP Files" SEC01
SetOutPath "$INSTDIR"
SetOverwrite ifnewer
File /r "F:\release175\release_rc2\xampp\*.*"
ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\install.php"' $4

WriteRegStr HKLM "Software\xampp" "Install_Dir" "$INSTDIR"
WriteRegStr HKLM "Software\xampp" "apache" "2217"
WriteRegStr HKLM "Software\xampp" "version" "1750"
WriteRegStr HKLM "Software\xampp" "apacheservice" "0"
WriteRegStr HKLM "Software\xampp" "mysqlservice" "0"
WriteRegStr HKLM "Software\xampp" "tomcatservice" "0"
WriteRegStr HKLM "Software\xampp" "filezillainstall" "1"
WriteRegStr HKLM "Software\xampp" "filezillaservice" "0"
WriteRegStr HKLM "Software\xampp" "mercuryinstall" "1"
WriteRegStr HKLM "Software\xampp" "addonperl" "1"
WriteRegStr HKLM "Software\xampp" "addonpython" "0"
WriteRegStr HKLM "Software\xampp" "addontomcat" "1"
WriteRegStr HKLM "Software\xampp" "addoncocoon" "0"
WriteRegStr HKLM "Software\xampp" "programfiles" "0"
WriteRegStr HKLM "Software\xampp" "desktopicon" "0"
WriteRegStr HKLM "Software\xampp" "services" "0"
WriteRegStr HKLM "Software\xampp" "lang" "$LANGUAGE"
WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp" "DisplayName" "${PRODUCT_NAME} ${PRODUCT_VERSION}"
WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp" "UninstallString" '"$INSTDIR\uninstall.exe"'
WriteRegDWORD HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp" "NoModify" 1
WriteRegDWORD HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp" "NoRepair" 1
WriteUninstaller "$INSTDIR\Uninstall.exe"

ReadRegStr $R0 HKLM "SOFTWARE\Microsoft\Windows NT\CurrentVersion" CurrentVersion
   StrCmp $R0 "" 0 NTsec
   MessageBox MB_OK "No Service Installation avaible on Windows 98/ME/Home"
  ; StrCmp $LANGUAGE "1041" japanese1
   StrCmp $LANGUAGE "1031" german1
  ;Read a value from an InstallOptions INI file
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp_home.ini" "Field 2" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp_home.ini" "Field 4" "State"
  Goto no_srv
  german1:
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp_home-german.ini" "Field 2" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp_home-german.ini" "Field 4" "State"
   Goto no_srv
 ; japanese1:
 ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp_home-japanese.ini" "Field 2" "State"
 ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp_home-japanese.ini" "Field 4" "State"
 ;  Goto no_srv
NTsec:
; FULL SERVICES --------------
  ; StrCmp $LANGUAGE "1041" japanese2
   StrCmp $LANGUAGE "1031" german2
  ;Read a value from an InstallOptions INI file
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp.ini" "Field 2" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp.ini" "Field 4" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE3 "xampp.ini" "Field 6" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE4 "xampp.ini" "Field 7" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE5 "xampp.ini" "Field 8" "State"
  Goto defaultlang2
  german2:
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp-german.ini" "Field 2" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp-german.ini" "Field 4" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE3 "xampp-german.ini" "Field 6" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE4 "xampp-german.ini" "Field 7" "State"
  !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE5 "xampp-german.ini" "Field 8" "State"
  Goto defaultlang2
  ; japanese2:
  ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE "xampp-japanese.ini" "Field 2" "State"
  ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE2 "xampp-japanese.ini" "Field 4" "State"
  ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE3 "xampp-japanese.ini" "Field 6" "State"
  ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE4 "xampp-japanese.ini" "Field 7" "State"
  ; !insertmacro MUI_INSTALLOPTIONS_READ $INI_VALUE5 "xampp-japanese.ini" "Field 8" "State"
  defaultlang2:

  
  StrCmp $INI_VALUE3 "1" "" noapache2
  WriteRegStr HKLM "Software\xampp" "apacheservice" "1"
  WriteRegStr HKLM "Software\xampp" "services" "1"
  noapache2:
  StrCmp $INI_VALUE4 "1" "" nomysql5
  WriteRegStr HKLM "Software\xampp" "mysqlservice" "1"
  WriteRegStr HKLM "Software\xampp" "services" "1"
  nomysql5:
  StrCmp $INI_VALUE5 "1" "" noftp2
  WriteRegStr HKLM "Software\xampp" "filezillaservice" "1"
  WriteRegStr HKLM "Software\xampp" "services" "1"
  noftp2:
  no_srv:

StrCmp $INI_VALUE "1" "" nodesktop
WriteRegStr HKLM "Software\xampp" "desktopicon" "1"
nodesktop:
StrCmp $INI_VALUE2 "1" "" noprofiles
WriteRegStr HKLM "Software\xampp" "programfiles" "1"
noprofiles:

SectionEnd

; ---------------------------------------

Function .onInit
  !insertmacro MUI_LANGDLL_DISPLAY
  !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp.ini"
 ; !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp-japanese.ini"
  !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp_home.ini"
  ; !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp_home-japanese.ini"
  !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp-german.ini"
  !insertmacro MUI_INSTALLOPTIONS_EXTRACT "xampp_home-german.ini"

  ReadRegStr $R1 HKLM "SOFTWARE\Microsoft\Windows NT\CurrentVersion" CurrentVersion
        StrCmp $R1 "6.0" detection_VISTA
        StrCmp $R1 "6.1" detection_VISTA
        Goto no_vista
        detection_VISTA:
                        ReadRegStr $R2 HKLM "SOFTWARE\Microsoft\Windows\CurrentVersion\Policies\System" EnableLUA
                        ReadRegStr $R0 HKCU "Control Panel\International" Locale
                        StrCmp $R0 "00000407" detection_de
                        GOTO no_de
                        detection_de:
                        StrCmp $R2 "1" IS_UACDE
                        MessageBox MB_OK "Die Benutzerkontensteuerung unter Windows (UAC) ist auf Ihrem System deaktiviert (empfohlen). Bitte beachten Sie, das eine nachtr臠liche Aktivierung des Benutzerkontenschutz die Funktionalit舩 der XAMPP-Komponenten beeintr臘htigen kann."
                        GOTO ISNO_UACDE
                        IS_UACDE:
                        MessageBox MB_OK "Warnung! Aufgrund der aktivierten Windows Benutzerkontensteuerung (UAC) auf Ihrem System sind XAMPP-Komponenten und Funktionen ggf. nur eingeschr舅kt einsetzbar. Vermeiden Sie die Installation von XAMPP unter $PROGRAMFILES oder deaktivieren Sie den Benutzerkontensteuerung �ber msconfig nach diesem Setup."
                        ISNO_UACDE:
                        GOTO no_vista
                        no_de:
                        StrCmp $R2 "1" IS_UACE
                        MessageBox MB_OK "The User Account Control (UAC) is deactivated on your system (recommended). Please note: A later activation of UAC can restrict the functionality of XAMPP."
                        GOTO no_vista
                        IS_UACE:
                        MessageBox MB_OK "Important! Because an activated User Account Control (UAC) on your sytem some functions of XAMPP are possibly restricted. With UAC please avoid to install XAMPP to $PROGRAMFILES (missing write permisssions). Or deactivate UAC with msconfig after this setup."
                        no_vista:
FunctionEnd


Function CustomPageC
ReadRegStr $R1 HKLM "SOFTWARE\Microsoft\Windows NT\CurrentVersion" CurrentVersion
   StrCmp $R1 "" 0 NTsrv
  ;  StrCmp $LANGUAGE "1041" japanesehome
   StrCmp $LANGUAGE "1031" germanhome
  !insertmacro MUI_HEADER_TEXT "XAMPP Options" "Install options on Windows Home systems."
  !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp_home.ini"
   Goto no_srv
   germanhome:
  !insertmacro MUI_HEADER_TEXT "XAMPP Optionen" "Konfiguration f�r Windows Home Systeme."
  !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp_home-german.ini"
   Goto no_srv
  ; japanesehome:
  ; !insertmacro MUI_HEADER_TEXT "XAMPP Options" "Windowsのシステムのオプションをインストールします。"
  ; !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp_home-japanese.ini"
  ; Goto no_srv
NTsrv:
  ; StrCmp $LANGUAGE "1041" japanese
  StrCmp $LANGUAGE "1031" german
  !insertmacro MUI_HEADER_TEXT "XAMPP Options" "Install options on NT/2000/XP Professional systems."
  !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp.ini"
  Goto no_srv
  german:
  !insertmacro MUI_HEADER_TEXT "XAMPP Optionen" "Konfiguration f�r NT/2000/XP Professional Systeme."
  !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp-german.ini"
  Goto no_srv
  ; japanese:
  ; !insertmacro MUI_HEADER_TEXT "XAMPP Options" "Windows NT/2000/XP/2003にシステムオプションをインストールします"
  ; !insertmacro MUI_INSTALLOPTIONS_DISPLAY "xampp-japanese.ini"
no_srv:

FunctionEnd

!insertmacro MUI_FUNCTION_DESCRIPTION_BEGIN
    !insertmacro MUI_DESCRIPTION_TEXT ${SEC01} "SERVICES:"
!insertmacro MUI_FUNCTION_DESCRIPTION_END

Function .onInstSuccess

; SERVICE INSTALLATION
ReadRegStr $4 HKLM "Software\xampp" "lang"
ReadRegStr $0 HKLM "Software\xampp" "services"
StrCmp $0 "0" no_srv
ExecWait 'cmd /C cd "$INSTDIR\install" & portcheck.bat' $7
ReadRegStr $1 HKLM "Software\xampp" "apacheservice"
StrCmp $1 "0" no_httpd
       ReadINIStr $R0 "$INSTDIR\install\portcheck.ini" "Ports" "Port80"
       StrCmp $R0 "BLOCKED" Port80Abort
       ReadINIStr $R1 "$INSTDIR\install\portcheck.ini" "Ports" "Port443"
       StrCmp $R1 "BLOCKED" Port80Abort
       ExecWait 'cmd /C cd "$INSTDIR\apache\bin" & httpd.exe -k install & net start Apache2.2' $9
       Goto no_httpd
       Port80Abort:
       StrCmp $4 "1031" german
    ;   StrCmp $4 "1041" japan
       StrCpy $INST_MESS1 "Ports 80 or 443 (SSL) already in use! Installing Apache2.2 service failed!"
       Goto mess1
       ; japan:
       ; StrCpy $INST_MESS1 "ポート 80 または 443 (SSL) はすでに利用されています。Apache2.2をサービスとしてインストールするのに失敗しました。"
       ; Goto mess1
       german:
       StrCpy $INST_MESS1 "Ports 80 oder 443 (SSL) bereits in Nutzung! Apache2.2-Dienst konnte nicht eingerichtet werden."
       mess1:
       WriteRegStr HKLM "Software\xampp" "apacheservice" "0"
       MessageBox MB_OK "$INST_MESS1"
       
no_httpd:
ReadRegStr $2 HKLM "Software\xampp" "mysqlservice"
StrCmp $2 "0" no_mysql
       ReadINIStr $R0 "$INSTDIR\install\portcheck.ini" "Ports" "Port3306"
       StrCmp $R0 "BLOCKED" Port3306Abort
       ExecWait 'cmd /C cd "$INSTDIR\mysql\bin" & mysqld.exe --install mysql --defaults-file="$INSTDIR\mysql\bin\my.ini" & net start mysql' $9
       Goto no_mysql
       Port3306Abort:
       StrCmp $4 "1031" german1
       ;StrCmp $4 "1041" japan1
       StrCpy $INST_MESS2 "Port 3306 already in use! Installing MySQL service failed!"
       Goto mess2
      ; japan1:
      ; StrCpy $INST_MESS2 "ポート3306 はすでに使用されています。MySQLをサービスとしてインストールするのに失敗しました。"
      ; Goto mess2
       german1:
       StrCpy $INST_MESS2 "Port 3306 bereits in Nutzung! MySQL-Dienst kann nicht eingerichtet werden."
       mess2:
       WriteRegStr HKLM "Software\xampp" "mysqlservice" "0"
       MessageBox MB_OK "$INST_MESS2"

no_mysql:
ReadRegStr $3 HKLM "Software\xampp" "filezillaservice"
StrCmp $3 "0" no_ftp
       ReadINIStr $R0 "$INSTDIR\install\portcheck.ini" "Ports" "Port21"
       StrCmp $R0 "BLOCKED" Port21Abort
       ExecWait '"$INSTDIR\FileZillaFTP\FileZillaServer.exe" /install' $9
       ExecWait '"$INSTDIR\FileZillaFTP\FileZillaServer.exe" /start' $9
       Goto no_ftp
       Port21Abort:
       StrCmp $4 "1031" german2
      ;  StrCmp $4 "1041" japan2
       StrCpy $INST_MESS3 "Port 21 already in use! Installing FileZilla FTPD service failed!"
       Goto mess3
      ; japan2:
      ; StrCpy $INST_MESS3 "ポート 21 はすでに使用されています。FileZilla FTPD をサービスとしてインストールすることに失敗しました。"
      ; Goto mess3
       german2:
       StrCpy $INST_MESS3 "Port 21 bereits in Nutzung! FileZilla-Dienst kann nicht eingerichtet werden!"
       mess3:
       WriteRegStr HKLM "Software\xampp" "filezillaservice" "0"
       MessageBox MB_OK "$INST_MESS3"

no_ftp:
       StrCmp $4 "1031" german3
  ;     StrCmp $4 "1041" japan3
       StrCpy $INST_MESS4 "Service installation finished! Hint: Use also the XAMPP Control Panel to manage services."
       Goto mess4
      ; japan3:
      ; StrCpy $INST_MESS4 "サービスとしてインストールに成功しました。Xamppコントロールパネルを確認し、サービスを管理してください。"
      ; Goto mess4
       german3:
       StrCpy $INST_MESS4 "Dienste-Installation abgeschlossen! Tipp: Dienste knen Sie auch mit XAMPP Control Panel verwalten."
       mess4:
       MessageBox MB_OK "$INST_MESS4"
       
no_srv:
; DESKTOP & START MENU SECTION
ReadRegStr $0 HKLM "Software\xampp" "desktopicon"
StrCmp $0 "0" no_icon
CreateShortCut "$DESKTOP\XAMPP Control Panel.lnk" "$INSTDIR\xampp-control.exe" ""

no_icon:
ReadRegStr $1 HKLM "Software\xampp" "programfiles"
StrCmp $1 "0" no_pfiles
   CreateDirectory "$SMPROGRAMS\Apache Friends\XAMPP"
   CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP" "" ""
   CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\XAMPP Control Panel.lnk" "$INSTDIR\xampp-control.exe" "" "$INSTDIR\install\xampp.ico"
   CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\XAMPP htdocs folder.lnk" "$INSTDIR\htdocs" "" "$INSTDIR\install\folder.ico"
   ;CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\Port check.lnk" "$INSTDIR\xampp-portcheck.exe" "" "$INSTDIR\install\xamppcontrol.ico"
   ;CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\PHP switch.lnk" "$INSTDIR\php-switch.bat" "" "$INSTDIR\install\php.ico"
   CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\Uninstall.lnk" "$INSTDIR\uninstall.exe" "" "$INSTDIR\install\xampp-icon-uninstall.ico"

no_pfiles:
; StrCmp $4 "1041" japanese
; Delete "$INSTDIR\xampp-control-jp.exe"
; Rename "$INSTDIR\xampp-control-default.exe" "$INSTDIR\xampp-control.exe"
; Goto xamppcontrol_out
; japanese:
; Delete "$INSTDIR\xampp-control-default.exe"
; Rename "$INSTDIR\xampp-control-jp.exe" "$INSTDIR\xampp-control.exe"
xamppcontrol_out:

StrCmp $4 "1031" german4
; StrCmp $4 "1041" japan4
StrCpy $INST_MESS "Congratulations! The installation was successful! Start the XAMPP Control Panel now?"
GOTO Xcontrol
german4:
StrCpy $INST_MESS "Herzlichen Gl�ckwunsch! Die Installation war erfolgreich! Das XAMPP Control Panel jetzt starten?"
GOTO Xcontrol
; japan4:
; StrCpy $INST_MESS "おめでとうございます。インストールに成功しました。Xamppコントロールパネルを今すぐ起動しますか？"
Xcontrol:
MessageBox MB_YESNO "$INST_MESS" IDNO NoXcontrol
      Exec '"$INSTDIR\xampp-control.exe"'
NoXcontrol:
FunctionEnd


Section "Uninstall"
Exec '"$INSTDIR\apache\bin\pv.exe" -f -k xampp-control.exe'

ReadRegStr $9 HKLM "Software\xampp" "lang"
; StrCmp $9 "1041" japanese0
StrCmp $9 "1031" german0
StrCpy $INST_MESS "Really uninstall XAMPP with all services?"
GOTO mess_box
german0:
StrCpy $INST_MESS "XAMPP mit allen Server-Diensten wirklich deinstallieren?"
GOTO mess_box
; japanese0:
; StrCpy $INST_MESS "本当にXamppをサービスを含めいてアンインストールしますか？"
mess_box:
MessageBox MB_YESNO|MB_ICONQUESTION "$INST_MESS" IDNO ExitDel

ReadRegStr $5 HKLM "Software\xampp" "services"
StrCmp $5 "0" srv_Abort
ReadRegStr $2 HKLM "Software\xampp" "apacheservice"
StrCmp $2 "0" no_httpd
ExecWait 'cmd /C net stop Apache2.2 & "$INSTDIR\apache\bin\httpd.exe" -k uninstall' $9
no_httpd:
ReadRegStr $3 HKLM "Software\xampp" "mysqlservice"
StrCmp $3 "0" no_mysql
ExecWait 'cmd /C net stop mysql & "$INSTDIR\mysql\bin\mysqld.exe" --remove mysql' $9
no_mysql:
ReadRegStr $4 HKLM "Software\xampp" "filezillaservice"
StrCmp $4 "0" no_ftpd
; Starte: "F:\temp\program files\xampp\FileZillaFTP\FileZillaServer.exe" /stop
; Starte: "F:\temp\program files\xampp\FileZillaFTP\FileZillaServer.exe" /uninstall
; net stop "FileZilla Server FTP server"
ExecWait '"$INSTDIR\FileZillaFTP\FileZillaServer.exe" /stop' $8
ExecWait '"$INSTDIR\FileZillaFTP\FileZillaServer.exe" /uninstall' $8
no_ftpd:

ReadRegStr $6 HKLM "Software\xampp" "tomcatservice"
StrCmp $6 "0" NoJavaAddon
ReadRegStr $7 HKLM "SYSTEM\CurrentControlSet\Services\Tomcat7" "ImagePath"
StrCmp $7 "$INSTDIR\tomcat\bin\tomcat6.exe //RS//Tomcat7" Tomcat5Uninstall
Goto Tomcat5Abort
Tomcat5Uninstall:
MessageBox MB_OK "Service detected! Uninstall Tomcat 7 as service!"
ExecWait 'cmd /C net stop Tomcat7 & cd "$INSTDIR\tomcat\bin" & service.bat remove Tomcat7' $9
Tomcat5Abort:
NoJavaAddon:

srv_Abort:

ReadRegStr $0 HKLM "Software\xampp" "desktopicon"
StrCmp $0 "0" no_icon
Delete "$DESKTOP\XAMPP Control Panel.lnk"
no_icon:
ReadRegStr $8 HKLM "Software\xampp" "programfiles"
StrCmp $8 "0" no_pfiles
  Delete "$SMPROGRAMS\Apache Friends\xampp\*.*"
  RMDir "$SMPROGRAMS\Apache Friends\xampp"
  RMDir "$SMPROGRAMS\Apache Friends"
no_pfiles:



RMDir /r "$INSTDIR\anonymous"
RMDir /r "$INSTDIR\apache"
RMDir /r "$INSTDIR\cgi-bin"
RMDir /r "$INSTDIR\FileZillaFTP"
RMDir /r "$INSTDIR\install"
RMDir /r "$INSTDIR\licenses"
RMDir /r "$INSTDIR\MercuryMail"
RMDir /r "$INSTDIR\perl"
RMDir /r "$INSTDIR\php"
RMDir /r "$INSTDIR\phpMyAdmin"
RMDir /r "$INSTDIR\python"
RMDir /r "$INSTDIR\security"
RMDir /r "$INSTDIR\sendmail"
RMDir /r "$INSTDIR\tmp"
RMDir /r "$INSTDIR\tomcat"
RMDir /r "$INSTDIR\webalizer"
RMDir /r "$INSTDIR\webdav"
RMDir /r "$INSTDIR\nsis"
RMDir /r "$INSTDIR\contrib"
RMDir /r "$INSTDIR\src"

Delete "$INSTDIR\apache_start.bat"
Delete "$INSTDIR\apache_stop.bat"
Delete "$INSTDIR\filezilla_setup.bat"
Delete "$INSTDIR\filezilla_start.bat"
Delete "$INSTDIR\filezilla_stop.bat"
Delete "$INSTDIR\mercury_start.bat"
Delete "$INSTDIR\mercury_stop.bat"
Delete "$INSTDIR\mysql_start.bat"
Delete "$INSTDIR\mysql_stop.bat"
Delete "$INSTDIR\php-switch.bat"
Delete "$INSTDIR\readme_de.txt"
Delete "$INSTDIR\readme_en.txt"
Delete "$INSTDIR\service.exe"
Delete "$INSTDIR\setup_xampp.bat"
Delete "$INSTDIR\xampp_restart.exe"
Delete "$INSTDIR\xampp_start.exe"
Delete "$INSTDIR\xampp_stop.exe"
Delete "$INSTDIR\xampp-changes.txt"
Delete "$INSTDIR\xampp-portcheck.exe"
Delete "$INSTDIR\xampp-control.exe"
Delete "$INSTDIR\Uninstall.exe"
Delete "$INSTDIR\javapath.ini"
Delete "$INSTDIR\readme-addon-perl.txt"
Delete "$INSTDIR\readme-addon-tomcat.txt"
Delete "$INSTDIR\tomcat_start.bat"
Delete "$INSTDIR\tomcat_stop.bat"
Delete "$INSTDIR\passwords.txt"
Delete "$INSTDIR\xampp_cli.exe"
Delete "$INSTDIR\xampp_chkdll.exe"
Delete "$INSTDIR\xampp_service_mercury.exe"
Delete "$INSTDIR\catalina_start.bat"
Delete "$INSTDIR\catalina_stop.bat"
Delete "$INSTDIR\xampp-control-3-beta.exe"
;Delete "$INSTDIR\msvcr71.dll"

DeleteRegKey HKLM "Software\xampp"
DeleteRegKey HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp"

; StrCmp $LANGUAGE "1041" japanese1
StrCmp $LANGUAGE "1031" german1
StrCpy $INST_MESS1 "Remove all MySQL data bases from $INSTDIR\mysql\data?"
GOTO messa1
german1:
StrCpy $INST_MESS1 "Alle MySQL Datenbanken in $INSTDIR\mysql\data lchen?"
GOTO messa1
; japanese1:
; StrCpy $INST_MESS1 "MySQLデータベースを $INSTDIR\mysql\data から削除しますか？"
messa1:
MessageBox MB_YESNO|MB_ICONQUESTION "$INST_MESS1" IDYES NoMysql
Delete "$INSTDIR\mysql\*.*"
RMDir /r "$INSTDIR\mysql\bin"
RMDir /r "$INSTDIR\mysql\scripts"
RMDir /r "$INSTDIR\mysql\share"
StrCpy $DB_DEL "0"
Goto DeleteHtdocs
NoMysql:
RMDir /r "$INSTDIR\mysql"
DeleteHtdocs:

; StrCmp $LANGUAGE "1041" japanese2
StrCmp $LANGUAGE "1031" german2
StrCpy $INST_MESS2 "Remove the $INSTDIR\htdocs folder too?"
StrCpy $MESS_INSTDIR1 "Shall the installer try to remove $INSTDIR?"
StrCpy $MESS_INSTDIR2 "Note: $INSTDIR could not be removed!"
GOTO messa2
german2:
StrCpy $INST_MESS2 "Auch das Verzeichnis $INSTDIR\htdocs lchen?"
StrCpy $MESS_INSTDIR1 "Soll der Installer (versuchen) das Verzeichnis $INSTDIR (zu) lchen?"
StrCpy $MESS_INSTDIR2 "Achtung: Knte $INSTDIR nicht lchen!"
GOTO messa2
; japanese2:
; StrCpy $INST_MESS2 "$INSTDIR\htdocs のフォルダーを削除しますか？"
; StrCpy $MESS_INSTDIR1 "Shall the installer try to remove $INSTDIR?"
; StrCpy $MESS_INSTDIR2 "Note: $INSTDIR could not be removed!"
messa2:
MessageBox MB_YESNO|MB_ICONQUESTION "$INST_MESS2" IDYES noHtdocs
Goto NoDelete
noHtdocs:
RMDir /r "$INSTDIR\htdocs"
GOTO NoDocs
NoDelete:
GOTO ExitDel
NoDocs:
StrCmp $DB_DEL "0" NoXaDir

MessageBox MB_YESNO|MB_ICONQUESTION "$MESS_INSTDIR1" IDYES noIDIR
Goto yesIDIR
noIDIR:
RMDir "$INSTDIR"
IfFileExists "$INSTDIR\*.*" ErrorMsg
Goto yesIDIR
ErrorMsg:
MessageBox MB_OK "$MESS_INSTDIR2" ; skipped if file doesn't exist
yesIDIR:

NoXaDir:
ExitDel:
SectionEnd

;--------------------------------
;Uninstaller Functions
Function un.onInit
  !insertmacro MUI_UNGETLANGUAGE
FunctionEnd
