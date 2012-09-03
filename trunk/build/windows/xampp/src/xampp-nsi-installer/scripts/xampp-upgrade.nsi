; Author: Kay Vogelgesang for ApacheFriends XAMPP win32

SetCompressor /solid lzma
XPStyle on
SilentInstall normal

RequestExecutionLevel admin

; HM NIS Edit Wizard helper defines
!define PRODUCT_NAME "XAMPP Upgrade"
!define PRODUCT_VERSION "1.7.1"
!define PRODUCT_PUBLISHER "Kay Vogelgesang, Kai Oswald Seidler, ApacheFriends"
!define PRODUCT_WEB_SITE "http://www.apachefriends.org"

; MUI 1.67 compatible ------
!include "MUI.nsh"
BGGradient f87820 FFFFFF FFFFFF
InstallColors FF8080 000030
CheckBitmap "${NSISDIR}\Contrib\Graphics\Checks\classic-cross.bmp"

; MUI Settings
!define MUI_ABORTWARNING
!define MUI_ICON "C:\xamppdev\NSI\icons\xampp-icon.ico"
!define MUI_UNICON "C:\xamppdev\NSI\icons\xampp-icon-uninstall.ico"
!define MUI_WELCOMEPAGE
!define MUI_CUSTOMPAGECOMMANDS
!define MUI_COMPONENTSPAGE
!define MUI_COMPONENTSPAGE_NODESC

; Welcome page
!insertmacro MUI_PAGE_WELCOME
; License page
; !insertmacro MUI_PAGE_LICENSE "${NSISDIR}\license.txt"
; Directory page
!insertmacro MUI_PAGE_DIRECTORY
; Instfiles page
!insertmacro MUI_PAGE_INSTFILES
; Finish page
!insertmacro MUI_PAGE_FINISH
; Default LANGUAGE
;!insertmacro MUI_LANGUAGE "German"

 !insertmacro MUI_LANGUAGE "English"
  !insertmacro MUI_LANGUAGE "French"
  !insertmacro MUI_LANGUAGE "German"
  !insertmacro MUI_LANGUAGE "Spanish"
  !insertmacro MUI_LANGUAGE "SimpChinese"
  !insertmacro MUI_LANGUAGE "TradChinese"
  !insertmacro MUI_LANGUAGE "Japanese"
  !insertmacro MUI_LANGUAGE "Korean"
  !insertmacro MUI_LANGUAGE "Italian"
  !insertmacro MUI_LANGUAGE "Dutch"
  !insertmacro MUI_LANGUAGE "Danish"
  !insertmacro MUI_LANGUAGE "Swedish"
  !insertmacro MUI_LANGUAGE "Norwegian"
  !insertmacro MUI_LANGUAGE "Finnish"
  !insertmacro MUI_LANGUAGE "Greek"
  !insertmacro MUI_LANGUAGE "Russian"
  !insertmacro MUI_LANGUAGE "Portuguese"
  !insertmacro MUI_LANGUAGE "PortugueseBR"
  !insertmacro MUI_LANGUAGE "Polish"
  !insertmacro MUI_LANGUAGE "Ukrainian"
  !insertmacro MUI_LANGUAGE "Czech"
  !insertmacro MUI_LANGUAGE "Slovak"
  !insertmacro MUI_LANGUAGE "Croatian"
  !insertmacro MUI_LANGUAGE "Bulgarian"
  !insertmacro MUI_LANGUAGE "Hungarian"
  !insertmacro MUI_LANGUAGE "Thai"
  !insertmacro MUI_LANGUAGE "Romanian"
  !insertmacro MUI_LANGUAGE "Latvian"
  !insertmacro MUI_LANGUAGE "Macedonian"
  !insertmacro MUI_LANGUAGE "Estonian"
  !insertmacro MUI_LANGUAGE "Turkish"
  !insertmacro MUI_LANGUAGE "Lithuanian"
  !insertmacro MUI_LANGUAGE "Catalan"
  !insertmacro MUI_LANGUAGE "Slovenian"
  !insertmacro MUI_LANGUAGE "Serbian"
  !insertmacro MUI_LANGUAGE "Arabic"
  !insertmacro MUI_LANGUAGE "Farsi"
  !insertmacro MUI_LANGUAGE "Hebrew"
  !insertmacro MUI_LANGUAGE "Indonesian"

  !insertmacro MUI_RESERVEFILE_LANGDLL
; MUI end ------

Function .onInit
SetSilent normal
!insertmacro MUI_LANGDLL_DISPLAY
   ReadRegStr $INSTDIR HKLM "Software\xampp" "Install_Dir"
   StrCmp $INSTDIR "" 0 NoAbort
   MessageBox MB_OK "XAMPP win32 not found. Unable to get install path."
     Abort ; causes installer to quit.
   NoAbort:
   ReadRegStr  $2 HKLM "Software\xampp" "version"
   StrCmp $2 "1700" NoVersionAbort
   MessageBox MB_OK "Need XAMPP version 1.7.0 but cannot find this version. Stop installation."
   Abort ; causes no correct version found
   NoVersionAbort:

   ; ReadRegStr $2 HKLM "Software\xampp" "apache"
  ; StrCmp $2 "2240" NoversionAbort
  ; MessageBox MB_OK "Need XAMPP with Apache 2.2.4 for this Update!"
  ;  Abort ; causes installer to quit.
  ; NoversionAbort:
  
  ReadRegStr $R0 HKCU "Control Panel\International" Locale
  StrCmp $R0 "00000407" detection_de
  GOTO no_de
  detection_de:
  MessageBox MB_OK "Bitte alle Apache, MySQL, FileZilla, Mercury Prozesse im XAMPP vor dem Upgrading beenden. Diese Prozesse können nach dem Upgrade wieder gestartet werden."
  GOTO end_box
  no_de:
  MessageBox MB_OK "Please stop all Apache, MySQL, FileZilla, Mercury processes in XAMPP before upgrading! After upgrade you can (re)start these services."
  end_box:
FunctionEnd


;Section  desktopIcon
;Section "XAMPP Desktop" desktopIcon
;CreateShortCut "$DESKTOP\XAMPP Control Panel.lnk" "$INSTDIR\xampp-control.exe" ""
;SectionEnd


Name "${PRODUCT_NAME} ${PRODUCT_VERSION}"
Icon "C:\xamppdev\NSI\icons\xampp-icon.ico"
OutFile "C:\xamppdev\NSI\output\xampp-win32-upgrade-1.7.0-1.7.1-installer.exe"
; InstallDir "$PROGRAMFILES"

InstallDir $INSTDIR
ShowInstDetails show


Section "Hauptgruppe" SEC01
  AllowSkipFiles on
  SetOutPath $INSTDIR
  SetOverwrite on
  File /r "H:\xamppdev\installer\xampp-win32-upgrade\*.*"
  ; ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\upgrade.php"' $4
WriteRegStr HKLM "Software\xampp" "version" "1710"
WriteRegStr HKLM "Software\xampp" "apache" "2211"
WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\xampp" "DisplayName" "XAMPP ${PRODUCT_VERSION}"
SectionEnd


Function .onInstSuccess
ReadRegStr $4 HKLM "Software\xampp" "lang"
Delete "$INSTDIR\xampp-control.exe"
StrCmp $4 "1041" japanese
Delete "$INSTDIR\xampp-control-jp.exe"
Rename "$INSTDIR\xampp-control-default.exe" "$INSTDIR\xampp-control.exe"
Goto xamppcontrol_out
japanese:
Delete "$INSTDIR\xampp-control-default.exe"
Rename "$INSTDIR\xampp-control-jp.exe" "$INSTDIR\xampp-control.exe"
xamppcontrol_out:
FunctionEnd

;Section "Start Menu Shortcuts"
;Delete "$SMPROGRAMS\apachefriends\xampp\*.*"
;RMDir "$SMPROGRAMS\apachefriends\xampp"
;RMDir "$SMPROGRAMS\apachefriends"
;CreateDirectory "$SMPROGRAMS\apachefriends\xampp"
;  CreateShortCut "$SMPROGRAMS\apachefriends\xampp" "" ""
;   CreateShortCut "$SMPROGRAMS\apachefriends\xampp\CONTROL XAMPP SERVER PANEL.lnk" "$INSTDIR\xampp-control.exe" "" "$INSTDIR\install\xampp.ico"
;   CreateShortCut "$SMPROGRAMS\apachefriends\xampp\xampp httpdoc folder.lnk" "$INSTDIR\htdocs" "" "$INSTDIR\install\folder.ico"
;   CreateShortCut "$SMPROGRAMS\apachefriends\xampp\port checking.lnk" "$INSTDIR\xampp-portcheck.exe" "" "$INSTDIR\install\xamppcontrol.ico"
;  CreateShortCut "$SMPROGRAMS\apachefriends\xampp\php switch.lnk" "$INSTDIR\php-switch.bat" "" "$INSTDIR\install\php.ico"
;   CreateShortCut "$SMPROGRAMS\apachefriends\xampp\xampp uninstall.lnk" "$INSTDIR\uninstall.exe" "" "$INSTDIR\install\xampp-icon-uninstall.ico"
;SectionEnd


