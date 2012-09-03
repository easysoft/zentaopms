; Author: Kay Vogelgesang for ApacheFriends XAMPP win32

SetCompressor /SOLID lzma
XPStyle on

RequestExecutionLevel admin

; HM NIS Edit Wizard helper defines
!define PRODUCT_NAME "(Mod) Perl Addon"
!define PRODUCT_VERSION "5.10.0 (Apache 2.2.11)"
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
   StrCmp $1 "2211" NoMessage
   MessageBox MB_YESNO "Warning: Cannot found Apache httpd 2.2.11 for mod_perl (recommended)! Continue?" IDNO NoMessage
   Abort
   NoMessage:
   
   ReadRegStr $8 HKLM "Software\xampp" "perl"
   StrCmp $8 "" 0 isPerl
   Goto newPerl
   isPerl:
   IntCmp $8 5100001 is51 lessthan51 morethan51
   is51:
         MessageBox MB_OKCANCEL "Found XAMPP Perl Add-On! Try to upgrade this Add-On?" IDOK true IDCANCEL false
         false:
         abort
         true:
         Goto newPerl
    lessthan51:
    morethan51:
      MessageBox MB_OK "Sorry, but no upgrade possible with your old Add-On (Perl version $8)!"
      abort
   newPerl:

FunctionEnd


Name "${PRODUCT_NAME} ${PRODUCT_VERSION}"
Icon "C:\xamppdev\NSI\icons\xampp-icon.ico"
OutFile "C:\xamppdev\NSI\output\xampp-win32-perl-addon-5.10.0-2.2.11-pl2-installer.exe"
InstallDir $INSTDIR
ShowInstDetails show


Section "Hauptgruppe" SEC01
SectionIn RO
   ReadRegStr $8 HKLM "Software\xampp" "perl"

   SetOutPath $INSTDIR
   SetOverwrite on
   File /r "H:\xamppdev\installer\xampp-win32-perl-addon-5.10.0-2.2.11-pl2\*.*"
   WriteRegStr HKLM "Software\xampp" "perl" "510000101"
   WriteRegStr HKLM "Software\xampp" "modperl" "204000"
   ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\install.php"' $9

   # WriteRegStr HKLM "Software\xampp" "perl" "5100001"
   # WriteRegStr HKLM "Software\xampp" "modperl" "203000"
   # ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\install.php"' $4
SectionEnd




