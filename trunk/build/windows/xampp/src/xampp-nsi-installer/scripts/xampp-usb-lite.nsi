; Author: Kay Vogelgesang for ApacheFriends XAMPP win32

;---------------------
;Include Modern UI
   !include "MUI.nsh"
;--------------------------------

SetCompressor /solid lzma
XPStyle on
; HM NIS Edit Wizard helper defines
  !define PRODUCT_NAME "XAMPP (USB)"
  !define PRODUCT_VERSION "1.7.5"
  !define PRODUCT_PUBLISHER "Kay Vogelgesang, Kai Oswald Seidler, ApacheFriends"
  !define PRODUCT_WEB_SITE "http://www.apachefriends.org"
Caption "XAMPP USB & Lite ${PRODUCT_VERSION} win32"
; InstallDirRegKey HKCU "Software\xampp" ""
Name "${PRODUCT_NAME} ${PRODUCT_VERSION}"
OutFile "F:\xampp-dev\xampp-win32-${PRODUCT_VERSION}-usb-lite.exe"
;Vista redirects $SMPROGRAMS to all users without this
RequestExecutionLevel admin
BGGradient f87820 FFFFFF FFFFFF
InstallColors FF8080 000030
CheckBitmap "${NSISDIR}\Contrib\Graphics\Checks\classic-cross.bmp"

;--------------------------------

; MUI Settings
  !define MUI_ABORTWARNING
  !define MUI_ICON "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon.ico"
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
; Instfiles page
  !insertmacro MUI_PAGE_DIRECTORY
  !insertmacro MUI_PAGE_INSTFILES
; Finish page
  !insertmacro MUI_PAGE_FINISH

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
  Var FTP_INSTALL
  Var MAIL_INSTALL
  Var DB_DEL
  Var NO_DEL

InstallDir "c:\xampp"
Icon "C:\xampp\src\xampp-nsi-installer\icons\xampp-icon.ico"
ShowInstDetails show


Section "XAMPP Files" SEC01
SetOutPath "$INSTDIR"
SetOverwrite ifnewer
File /r "F:\release175\release_rc2\xampp-win32-1.7.5-usb-lite\xampp\*.*"
ExecWait '"$INSTDIR\php\php.exe" -n -d output_buffering=0 "$INSTDIR\install\install.php" usb' $4

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
                        ;ReadRegStr $R0 HKCU "Control Panel\International" Locale
                        ;StrCmp $R0 "00000407" detection_de
                        StrCmp $LANGUAGE "1031" detection_de
                        GOTO no_de
                        detection_de:
                        StrCmp $R2 "1" IS_UACDE
                        MessageBox MB_OK "Die Windows Vista Benutzerkontensteuerung (UAC) ist auf Ihrem System deaktiviert (empfohlen!). Bitte beachten Sie, das eine nachträgliche Aktivierung des Benutzerkontenschutz die Funktionalität der XAMPP-Komponenten beeinträchtigen kann."
                        GOTO ISNO_UACDE
                        IS_UACDE:
                        MessageBox MB_OK "Wichtige MS Vista Warnung! Aufgrund der aktivierten Benutzerkontensteuerung (UAC) auf Ihrem System sind XAMPP-Komponenten und Funktionen ggf. nur eingeschränkt einsetzbar. Vermeiden Sie in diesem Fall die Installation von XAMPP unter $PROGRAMFILES oder deaktivieren Sie den Benutzerkontensteuerung über msconfig nach diesem Setup."
                        ISNO_UACDE:
                        GOTO no_vista
                        no_de:
                        StrCmp $R2 "1" IS_UACE
                        MessageBox MB_OK "Windows Vista User Account Control (UAC) is deactivated on your system (recommended!). Please consider: A later activation of UAC can restrict the functionality of XAMPP!"
                        GOTO no_vista
                        IS_UACE:
                        MessageBox MB_OK "Important MS Vista Note! Because an activated Windows Vista User Account Control (UAC) on your sytem some functions of xampp are possibly restricted. With UAC please avoid to install XAMPP to $PROGRAMFILES (because of not enough write permisssions). Or deactivate UAC (with msconfig) after this Setup."
                        no_vista:

                        ;ReadRegStr $R0 HKCU "Control Panel\International" Locale
                        StrCmp $LANGUAGE "1031" detect_de
                        GOTO non_de
                        detect_de:
                        MessageBox MB_OK "Bitte wählen Sie im nächsten Schritt ihren Wechseldatenträger z.B. USB Stick aus. Alternativ können Sie diese Version auch als XAMPP Lite auf ihre Festplatte installieren."
                        GOTO usb_end
                        non_de:
                        MessageBox MB_OK "In the next step please choice your usb device for installation. Alternate you can take your hard disk for a lite installation."
                        usb_end:


FunctionEnd


