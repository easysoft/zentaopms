; Author: Kay Vogelgesang for ApacheFriends XAMPP win32

SetCompressor /solid lzma
XPStyle on

RequestExecutionLevel admin

; HM NIS Edit Wizard helper defines
!define PRODUCT_NAME "Tomcat Addon pl1"
!define PRODUCT_VERSION "6.0.18 (mod_jk/1.2.27)"
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
!insertmacro MUI_LANGDLL_DISPLAY
   ReadRegStr $INSTDIR HKLM "Software\xampp" "Install_Dir"
   StrCmp $INSTDIR "" 0 NoAbort
   MessageBox MB_OK "XAMPP win32 not found. Unable to get install path."
     Abort ; causes installer to quit.
   NoAbort:
   
   ReadRegStr $1 HKLM "Software\xampp" "apache"
   IntCmp $1 2211 is2211 lessthan2211 morethan2211
   lessthan2211:
   morethan2211:
    MessageBox MB_OK "In this package mod_jk/1.2.27 is only tested with apache version 2.2.11. It seems you a using a lower version!"
    MessageBox MB_OKCANCEL "Do you want to continue this installation?" IDOK true IDCANCEL false
    false:
    Abort
    true:
    is2211:
   
   ReadRegStr $2 HKLM "Software\xampp" "java"
   StrCmp $2 "" NoAddon 0
   MessageBox MB_OK "XAMPP Java Add-on is installed already!"
     Abort ; causes installer to quit.
   NoAddon:
  
   ReadRegStr $4 HKLM "Software\JavaSoft\Java Development Kit\1.5" "JavaHome"
   StrCmp $4 "" 0 YesJava
   ReadRegStr $5 HKLM "Software\JavaSoft\Java Development Kit\1.6" "JavaHome"
   StrCmp $5 "" 0 YesJava
   ReadRegStr $6 HKLM "SOFTWARE\Sun Microsystems\Application Server\9PE" "INSTALLPATH"
   StrCmp $6 "" 0 YesJava
   
   MessageBox MB_OK "SUN Java 5 or 6 SDK win32 not found. Unable to get install path."
   Abort ; causes installer to quit.
   YesJava:
 FunctionEnd


Name "${PRODUCT_NAME} ${PRODUCT_VERSION}"
Icon "C:\xamppdev\NSI\icons\xampp-icon.ico"
OutFile "C:\xamppdev\NSI\output\xampp-win32-tomcat-addon-6.0.18-2.2.11-pl1-installer.exe"

InstallDir $INSTDIR
ShowInstDetails show

Section "Hauptgruppe" SEC01
SectionIn RO
WriteRegStr HKLM "Software\xampp" "java" "1227"
  SetOutPath $INSTDIR
  SetOverwrite on
  File /r "H:\xamppdev\installer\xampp-win32-tomcat-addon-6.0.18-2.2.11-pl1\*.*"
  ;WriteINIStr "$INSTDIR\javapath.ini" "JavaPath" "JavaHome" $3
  ;ExecWait '"$INSTDIR\install\phpcli.exe" -n -d output_buffering=0 "$INSTDIR\install\javaaddon.php"' $9
  ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\install.php"' $9
SectionEnd

; Section "Start Menu Shortcuts"
Function .onInstSuccess
ReadRegStr $1 HKLM "Software\xampp" "programfiles"
StrCmp $1 "0" no_pfiles
 CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\Tomcat start.lnk" "$INSTDIR\tomcat_start.bat" "" "$INSTDIR\install\tomcat.ico"
  CreateShortCut "$SMPROGRAMS\Apache Friends\XAMPP\Tomcat stop.lnk" "$INSTDIR\tomcat_stop.bat" "" "$INSTDIR\install\tomcatstop.ico"
no_pfiles:
FunctionEnd
   
  
;SectionEnd

