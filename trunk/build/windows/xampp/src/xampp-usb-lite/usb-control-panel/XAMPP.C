// xampp.c - Winmain
//
//			Visual Studio 6 version
//          XP manifest included in xampp.rc
//
// Copyright NAT Software, 2007. http://www.nat32.com/xampp
//
// Modified by 青岛易软天创网络科技有限公司 2012.02.07 http://www.zentao.net
//
// Bugfixes
//
// 11. February, 2006:	After creating a Worker thread, wait for hSem to
//						ensure that we don't exit before CreateProcess has
//						returned.
//
//						All semaphores are created before UpdateStatus is
//						called.
//
// 17. May, 2006:		MySql args now use the full pathname.
//
//  9. May, 2007		PV.EXE no longer used to check status
//						Multiple Apache and MySql processes now supported
//						Optional xampp.ini file now processed
// 
#include <windows.h>
#include <commctrl.h>
#include <commdlg.h>
#include <tlhelp32.h>

#include "xampp.h"
#include "stdafx.h"
#include "stdio.h"

// Global variables

struct ProcessInfo {
	int pid;
	char module[256];
	char path[MAX_PATH];
};

struct ProcessInfo proc_tab[1024];
int proc_index;

int xampp_service;
int xampp_app;

char current_user[256];

char win_ver[256];

OSVERSIONINFO   version;
OSVERSIONINFOEX versionx;

int platform;
int platforme;
int platform2;
int platformx;
int platformx2;
int platformv;

int icon_up;
char localhost[20] = "http://localhost";
int  argc;
char *argv[16];

char cur_dir[1024];
char ini_file[1024];
char install_dir[1024];

HINSTANCE hInst;

HWND hAbout;

HWND hList;
HWND hWnd;
HWND hHelp;
HFONT hFont;

SC_HANDLE hSCM;
SC_HANDLE hService;
SERVICE_STATUS ss;

HANDLE hStopEvent;              // SCM sets this
HANDLE hServiceStopEvent;       // Used to notify the SCM
HANDLE hWatchdogEvent;

char saved_value[1024];

HWND hServiceDlg;
int service_arg;
int service_type;
int service_changed;
int service_enabled;
int splash;

int timerID;

NOTIFYICONDATA iconData;
HICON hIcon;

int incr;
int dlg_flag;
int hide_flag;
int update_flag;
int system_shutdown;
int xampp_flag;

unsigned long exit_code;

struct job_entry job[NJOBS];

char module[NJOBS][64];
char action[NJOBS][8];
HBRUSH hBrushOn;
HBRUSH hBrushOff;

char hide[64];
char apache_port80[64];
char mysql_port3306[64];
char apache_port88[64];
char mysql_port3308[64];
char apache_start[64];
char mysql_start[64];
char apache_stop[64];
char mysql_stop[64];
char ftp_port[64];
char mail_port[64];
char ftp_start[64];
char mail_start[64];
char ftp_stop[64];
char mail_stop[64];

char xampp_setup[64];
char xampp_dir[512];

int WINAPI WinMain(HINSTANCE hExe, HINSTANCE hPrev, LPSTR CmdLine, int CmdShow)
{
    char    *pstart;
    char    *pnext;
    int     i, result;
    WNDCLASSEX wcx;
    INITCOMMONCONTROLSEX InitCtrls;

    hInst = hExe;

    SetProcessShutdownParameters(0x3FF, 0);
        
    InitCtrls.dwSize = sizeof(INITCOMMONCONTROLSEX);
    InitCtrls.dwICC = ICC_BAR_CLASSES | ICC_WIN95_CLASSES;
    InitCommonControlsEx(&InitCtrls);
    
    #define MYON  RGB(200,255,200)
    #define MYOFF GetSysColor(COLOR_3DFACE)

    hBrushOn  = CreateSolidBrush(MYON);
    hBrushOff = CreateSolidBrush(MYOFF);

    // Register a WNDCLASS
      
    wcx.cbSize = sizeof(WNDCLASSEX);
    wcx.style = CS_HREDRAW | CS_VREDRAW;
    wcx.lpfnWndProc = DefDlgProc;
    wcx.cbClsExtra = 0;
    wcx.cbWndExtra = DLGWINDOWEXTRA;
    wcx.hInstance = hInst;
    wcx.hIcon = LoadIcon(hInst, "MyIcon");
    wcx.hCursor = LoadCursor(NULL, IDC_ARROW);
    wcx.hbrBackground = CreateSolidBrush(COLOR_WINDOW + 1);
    wcx.lpszMenuName = NULL;
    wcx.lpszClassName = "XamppClass";
    wcx.hIconSm = LoadIcon(hInst, "TrayIcon");
    RegisterClassEx(&wcx);

	// Get platform

    version.dwOSVersionInfoSize = sizeof(OSVERSIONINFO);
    GetVersionEx(&version);

    if (version.dwMajorVersion == 5) {
        platform2 = 1;
        if (version.dwMinorVersion >= 1) {
            platformx = version.dwMinorVersion;

            // Check for SP2 on XP

            if (platformx == 1) {   // XP only, not 2003
                versionx.dwOSVersionInfoSize = sizeof(OSVERSIONINFOEX);
                GetVersionEx((LPOSVERSIONINFO)&versionx);
                if (versionx.wServicePackMajor == 2)
                    platformx2 = 2;
            }

            // Check for SP1 on 2003

            if (platformx == 2) {   // 2003 only
                versionx.dwOSVersionInfoSize = sizeof(OSVERSIONINFOEX);
                GetVersionEx((LPOSVERSIONINFO)&versionx);
                if (versionx.wServicePackMajor >= 1)
                    platformx2 = 2;
            }
        }
    }
    
    if (version.dwMajorVersion == 6) {

        // Assume that Vista has everything and then some

        platform2  = 1;
        platformx  = 2;
        platformx2 = 2;
        platformv  = 1;
    }

    if (version.dwPlatformId == VER_PLATFORM_WIN32_NT)
        platform = 1;

    if (platform == 0 && version.dwMinorVersion == 90)  // Windows 98ME
        platforme = 1;

    sprintf(win_ver, "Windows %d.%d Build %d Platform %d %s",
        version.dwMajorVersion,
        version.dwMinorVersion,
        platform?version.dwBuildNumber:LOWORD(version.dwBuildNumber),
        version.dwPlatformId,
        version.szCSDVersion);

    GetCurrentDirectory(1024, cur_dir);

	// Get settings from xampp.ini

	sprintf(ini_file, "%s\\xampp.ini", cur_dir);

	GetPrivateProfileString("START", "hide",   "0", hide,         64, ini_file);
	GetPrivateProfileString("START", "httpd", "0", apache_start, 64, ini_file);
	GetPrivateProfileString("START", "mysql",  "0", mysql_start,  64, ini_file);

	GetPrivateProfileString("EXIT", "httpd",  "0", apache_stop,   64, ini_file);
	GetPrivateProfileString("EXIT", "mysql",   "0", mysql_stop,    64, ini_file);

	GetPrivateProfileString("PORTS", "apache", "80", apache_port80, 64, ini_file);
	GetPrivateProfileString("PORTS", "mysql",  "3306", mysql_port3306, 64, ini_file);
	GetPrivateProfileString("PORTS", "apache", "88", apache_port88, 64, ini_file);
	GetPrivateProfileString("PORTS", "mysql",  "3308", mysql_port3308, 64, ini_file);

	GetPrivateProfileString("PORTS", "ftp",    "21", ftp_port, 64, ini_file);
	GetPrivateProfileString("PORTS", "mercury","25", mail_port, 64, ini_file);

	GetPrivateProfileString("XAMPP", "setup", "0", xampp_setup, 64, ini_file);

	sprintf(ini_file, "%s\\apache\\bin\\php.ini", cur_dir);

	GetPrivateProfileString("PHP", "extension_dir", "0", xampp_dir, 512, ini_file);

	if (stristr(xampp_dir, cur_dir))
		xampp_flag = 0;
	else
		xampp_flag = 1;

    // Initialize job names and ports

    strcpy(job[0].name, "httpd80");
	strcpy(job[0].port, apache_port80);
    strcpy(job[1].name, "mysql3306");
	strcpy(job[1].port, mysql_port3306);
    strcpy(job[2].name, "httpd88");
	strcpy(job[2].port, apache_port88);
    strcpy(job[3].name, "mysql3308");
	strcpy(job[3].port, mysql_port3308);
        
	// Get current User Name

    i = 1024;
    GetUserName(current_user, (DWORD *)&i);

    // Generate standard string args

    argc = 1;
    argv[0] = "xampp";
    pstart = CmdLine;

    while (strlen(pstart)) {

        argv[argc++] = pstart;
        pnext = strchr(pstart, ' ');

        if (pnext == 0)
            break;

        *pnext++ = 0;
        pstart = pnext;

        if (argc > 16) {
            kprintf("Usage: zentaoamp [module]+");
            return 0;
        }
        while (*pstart && isspace(*pstart)) pstart++;
    }
 
    if (argv[argc-1][0] == 's' && argv[argc-1][1] == 0) {
        argc--;
        service_arg = 1;
    }

    result = start();

    if (result == OK) {

        // Create the WatchdogEvent

        CloseHandle(hWatchdogEvent);

        hWatchdogEvent = CreateEvent(NULL, TRUE, FALSE, "ZENTAOAMPWATCHDOG");

        if (hWatchdogEvent == 0)
            kprintf("FATAL ERROR: The Watchdog Event could not be created.");
        else
            work(argc, argv);
    }

    if (hServiceStopEvent) {
        SetEvent(hServiceStopEvent);    // Notify the SCM
        Shell_NotifyIcon(NIM_DELETE, &iconData);
    }

    return 1;
}

int start() {

    int result;

    // Is another instance running?

    if (platform == 0) {

        // For 9X platforms, check for the Watchdog event

        xampp_app = 1;

        hWatchdogEvent = OpenEvent(EVENT_ALL_ACCESS, TRUE, "ZENTAOAMPAMPWATCHDOG");

        if (hWatchdogEvent) {
            HWND hTemp;
            // A previous instance exists

            hTemp = FindWindow("XAMPPAPP", "zentaoamp");
            if (hTemp != 0) {
                ShowWindow(hTemp, SW_SHOWDEFAULT);
                SetForegroundWindow(hTemp);                 // give focus to other window
                if (argc == 2 && !strcmp(argv[1], "-t"))    // terminate the other one
                    SendMessage(hTemp, WM_COMMAND, IDCANCEL, 0);
                else
                    kprintf("ERROR: zentaoamp is already running");
            }
            return SYSERR;
        }
    }

    // For NT platforms, we could be a SERVICE or an APP
    //
    //      SERVICE:    an APP can't already be running.
    //                  a SERVICE can't already be running.
    //      APP:        a previous APP may already be running
    //                  a SERVICE may already be running.

    if (platform) {

        // First try opening the Watchdog Event

        hWatchdogEvent = OpenEvent(EVENT_ALL_ACCESS, TRUE, "ZENTAOAMPWATCHDOG");

        // Are we running as an APP or a SERVICE?

        if (service_arg) {

            // We're a SERVICE so open the events

            hStopEvent = OpenEvent(EVENT_ALL_ACCESS, TRUE, "ZENTAOAMPSTOP");
            hServiceStopEvent = OpenEvent(EVENT_ALL_ACCESS, TRUE, "ZENTAOAMPSERVICESTOP");

            // No APP exists so continue normally

            xampp_service = 1;
            xampp_app = 0;
            return OK;
        }
        else {

            // We're an APP, so first check for another APP

            xampp_service = 0;
            xampp_app = 1;

            if (hWatchdogEvent) {

                // An APP exists, so we can't continue

                kprintf("ERROR: zentaoamp is already running");
                return SYSERR;
            }

            // We're an APP and no other APP exists so check for a SERVICE

            hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

            if (hSCM == 0) {

                // We can't get full access so try Read Access

                hSCM = OpenSCManager(NULL, NULL, GENERIC_READ);

                if (hSCM == 0) {
                    kprintf("ERROR: Read access to Service Control Manager denied");
                    return SYSERR;
                }
            }

            hService = OpenService(hSCM, "zentaoamp", SERVICE_ALL_ACCESS);
            if (hService == 0) {
                CloseServiceHandle(hSCM);
                return OK;              // The SERVICE doesn't exist
            }

            // The SERVICE exists and we have access

            if (ControlService(hService, SERVICE_CONTROL_INTERROGATE, &ss)) {
                if (ss.dwCurrentState == SERVICE_RUNNING) {
                    if (kprintf("ERROR: zentaoamp is already running as a Service.\n\nClick OK to terminate the Service or Cancel to exit.") == IDCANCEL) {
                        CloseServiceHandle(hService);
                        CloseServiceHandle(hSCM);
                        return SYSERR;
                    }
                    else {
                        result = ControlService(hService, SERVICE_CONTROL_STOP, &ss);
                        kprintf("SERVICE STOP %s", result?"SUCCEEDED.":"FAILED.");
                        CloseServiceHandle(hService);
                        CloseServiceHandle(hSCM);
                        return OK;      // The SERVICE has been stopped
                    }
                }
            }
            else {

                // The SERVICE exists but isn't running

                CloseServiceHandle(hService);
                CloseServiceHandle(hSCM);
                return OK;
            }

            // The SERVICE may be in some other state

            kprintf("ERROR: zentaoamp Service State %x", ss.dwCurrentState);

            CloseServiceHandle(hService);
            CloseServiceHandle(hSCM);
            return SYSERR;
        }
    }
    return OK;
}

int ShowIcon()  // called only if desktop interaction is desired
{
    while (Shell_NotifyIcon(NIM_ADD, &iconData) == FALSE) {

        // Hang around until the Desktop is up, otherwise autodial won't work

        if (icon_up)
            break;      // Just in case watchdog has already added the icon

        Sleep(2000);
    }
    return OK;
}

int work(int argc, char **argv) {

    int i, j, m, n;

    update_flag = 1;

    // Handle icon for Service

    if (xampp_service) {
        if (platform == 0)
            ShowIcon();     // Waits until the Desktop is up

        if (platform) {
            char value[256];
            char object[256];
            int type = 0;

            GetServiceValues(value, object, &type);
            if (type & 0x100)
                icon_up = 0;    // An icon will be added by watchdog
            else
                icon_up = 1;    // No icon will be added by watchdog
        }
    }

    GetCurrentDirectory(1024, cur_dir);

	GetInstallDirectory(1024, install_dir);

    // Initalize semaphores

    for (i=0; i<NJOBS; i++)
        job[i].hSem = CreateSemaphore(NULL, 0, 1, NULL);

    UpdateStatus(0);

    if (xampp_service) {
        for (i=1; i<argc; i++) {
            m = GetModuleCode(argv[i]);
            if (m == SYSERR)
                continue;

            if (job[m].state == 0) {

                do_job(m, 1);

//                if (m != 2)
//                    WaitForSingleObject(job[m].hSem, 2000);
            }
        }

        // GUI Service mode

        EnterDialogBox();

        return 1;
    }

    // Process arguments

    if (argc < 3) {

        if (argc == 2 && strcmp(argv[1], "status") == 0) {
// 11.2.2006 was kprintf
            kprintf("Apache \t%s \t%s\nMySql \t%s \t%s\nFilezilla \t%s \t%s\nMercury \t%s \t%s\n",
                    job[0].service?"svc":"app", job[0].state?"running":"stopped",
                    job[1].service?"svc":"app", job[1].state?"running":"stopped",
                    job[2].service?"svc":"app", job[2].state?"running":"stopped",
                    job[3].service?"svc":"app", job[3].state?"running":"stopped");
            return 0;
        }

        // GUI App mode

        EnterDialogBox();

        return 1;
    }

    if (argc % 2 == 0) {
        kprintf("Usage: zentaoamp [status | [stop | start module]...]\n\nmodule: %s | %s | %s | %s\n\naction: start | stop",
                job[0].name, job[1].name, job[2].name, job[3].name);
        return SYSERR;
    }

    // Command mode

    for (i=1, j=0; i<argc; i++) {
        strcpy(action[j],   argv[i]);
        strcpy(module[j++], argv[++i]);
    }

    for (i=0; i<j; i++) {
        UpdateStatus(0);
        m = GetModuleCode(module[i]);
        if (m == SYSERR) {
            kprintf("ERROR: Invalid module name.\n\nSupported modules: %s %s %s %s",
                            job[0].name,
                            job[1].name,
                            job[2].name,
                            job[3].name);
            return SYSERR;
        }

        n = GetActionCode(action[i]);
        if (n == SYSERR) {
            kprintf("ERROR: Invalid action.\n\nSupported actions: stop start");
            return SYSERR;
        }

        do_job(m, n);

        if (m != 2)
            WaitForSingleObject(job[m].hSem, 2000);
    }
    return 0;
}

int GetModuleCode(char *str) {

    int i;

    for (i=0; i<NJOBS; i++) {
        if (stristr(job[i].name, str))
            return i;
    }
    return SYSERR;
}

int GetActionCode(char *str) {

    if (strcmp(str, "stop") == 0)
        return 0;

    if (strcmp(str, "start") == 0)
        return 1;

    return SYSERR;              // we treat this case as a NOOP
}

int SetServiceName(char *szUserName, char *szPassword, char *szDomain) {

    char ServiceStartName[256];

    SC_HANDLE hSCM;
    SC_HANDLE hService;

    SC_LOCK sclLock;

    sprintf(ServiceStartName, "%s\\%s", szDomain, szUserName);
 
    hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

    if (hSCM == 0) {
        kprintf("ERROR: Access to Service Control Manager denied");
        return SYSERR;
    }

    // Acquire database lock
 
    sclLock = LockServiceDatabase(hSCM);
 
    // If the database cannot be locked, report an error
 
    if (sclLock == NULL) {
        kprintf("ERROR: could not lock the Service Database");
        return SYSERR;
    }
 
    // The database is locked, so it is safe to make changes. 
 
    // Open a handle to the service. 
 
    hService = OpenService(hSCM,
                           "zentaoamp",
                           SERVICE_CHANGE_CONFIG);

    if (hService == NULL) {

        kprintf("OpenService failed");

        // Release the database lock.
 
        UnlockServiceDatabase(sclLock);
        CloseServiceHandle(hSCM);

        return SYSERR;
    }
 
    // Change Username

    if (!ChangeServiceConfig(
        hService,               // handle of service
        SERVICE_NO_CHANGE,      // service type: no change
        SERVICE_NO_CHANGE,      // start type: no change
        SERVICE_NO_CHANGE,      // error control: no change
        NULL,                   // binary path: no change
        NULL,                   // load order group: no change
        NULL,                   // tag ID: no change
        NULL,                   // dependencies: no change
        ServiceStartName,       // account name
        szPassword,             // password
        NULL))                  // display name: no change
    {
        kprintf("ERROR: could not change service start name [%d]", GetLastError());
        CloseServiceHandle(hService);
        UnlockServiceDatabase(sclLock);
        CloseServiceHandle(hSCM);
        return SYSERR;
    }

    CloseServiceHandle(hService);
    UnlockServiceDatabase(sclLock);
    CloseServiceHandle(hSCM);
    return OK;
}

int GetServiceStatus(char *name) {

    int result;

    hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_CONNECT);

    if (hSCM == 0)
        return SYSERR;

    hService = OpenService(hSCM, name, SERVICE_QUERY_STATUS);

    if (hService == 0) {
        CloseServiceHandle(hSCM);
        return 0;               // The SERVICE doesn't exist
    }

    // The SERVICE exists and we have access

    if (QueryServiceStatus(hService, &ss)) {

        result = 0;             // Default to SERVICE doesn't exist

        if (ss.dwCurrentState == SERVICE_RUNNING)
            result = 2;         // The SERVICE is running

        if (ss.dwCurrentState == SERVICE_STOPPED)
            result = 1;         // The SERVICE is not running
    }
    else
        result = SYSERR;        // Could not interrogate

    CloseServiceHandle(hService);
    CloseServiceHandle(hSCM);

    return result;
}

int ServiceStart(char *name) {

    int result;

    hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

    if (hSCM == 0)
        return SYSERR;

    hService = OpenService(hSCM, name, SERVICE_START);

    if (hService == 0) {
        CloseServiceHandle(hSCM);
        return SYSERR;          // The SERVICE doesn't exist or no access rights
    }

    // The SERVICE exists and we have access

    if (StartService(hService, 0, NULL))
        result = 2;             // The SERVICE is starting
    else
        result = SYSERR;        // The SERVICE could not be started

    CloseServiceHandle(hService);
    CloseServiceHandle(hSCM);

    return result;
}

int ServiceStop(char *name) {

    int result;

    hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

    if (hSCM == 0)
        return SYSERR;

    hService = OpenService(hSCM, name, SERVICE_STOP);

    if (hService == 0) {
        CloseServiceHandle(hSCM);
        return SYSERR;          // The SERVICE doesn't exist or no access rights
    }

    // The SERVICE exists and we have access

    if (ControlService(hService, SERVICE_CONTROL_STOP, &ss))
        result = 1;             // The SERVICE is stopping
    else
        result = SYSERR;        // The SERVICE could not be stopped

    CloseServiceHandle(hService);
    CloseServiceHandle(hSCM);

    return result;
}

int ServiceDelete(char *name) {

    int result;

    hSCM = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

    if (hSCM == 0)
        return SYSERR;

    hService = OpenService(hSCM, name, DELETE);

    if (hService == 0) {
        CloseServiceHandle(hSCM);
        return SYSERR;          // The SERVICE doesn't exist or no access rights
    }

    // The SERVICE exists and we have access

    if (DeleteService(hService))
        result = 1;             // The SERVICE is stopping
    else
        result = SYSERR;        // The SERVICE could not be stopped

    CloseServiceHandle(hService);
    CloseServiceHandle(hSCM);

    return result;
}

//
// MyDlgProc - all the GUI work is done here
//


BOOL APIENTRY MyDlgProc (HWND hDlg, UINT msg, WPARAM wParam, LPARAM lParam)
{
    int  wmID, error, i, result;
    char buf[256];
    UINT param;

    HWND hTmp;
    RECT rc1, rc2;
                
    switch (msg) {

        case MYWM_NOTIFYICON:

            param = (UINT) lParam;

            if (param != WM_LBUTTONDOWN && param != WM_RBUTTONUP)
                break;

            if (param == WM_LBUTTONDOWN) {
                if (dlg_flag == 1) {
                    dlg_flag = 0;
                    ShowWindow(hWnd, SW_HIDE);
                }
                else {
                    dlg_flag = 1;

                    ShowWindow(hWnd, SW_SHOWMINIMIZED);
                    ShowWindow(hWnd, SW_SHOWNORMAL);
                }
                break;
            }

            if (param == WM_RBUTTONUP) {

                refresh();
                break;
            }

            break;

        case WM_QUERYENDSESSION:

            system_shutdown = 1;

            // NT platforms: if we're a service, the SCM closes us if needed

            if (platform && xampp_service)
                return 0;

            // All platforms

            if (!(lParam & ENDSESSION_LOGOFF)) {

                // It's a shutdown

                ExitProcess(0);
            }
            return TRUE;
            
        case WM_ENDSESSION:

            // wParam indicates END SESSION
            //
            // lParam indicate logoff
        
            if (lParam & ENDSESSION_LOGOFF) {
                system_shutdown = 0;    // It's only a user logoff
                return 0;
            }
                    
            if (wParam)                  // It's definitely a Shutdown
                ExitProcess(0);

            return DefDlgProc(hDlg, msg, wParam, lParam);

        case WM_CTLCOLOREDIT: {

            int id = GetDlgCtrlID((HWND)lParam);

            if (id == IDC_EDIT0) {
                SetBkMode((HDC) wParam, TRANSPARENT);
                if (job[0].state)
                    return (BOOL) hBrushOn;
                SetTextColor((HDC) wParam, MYOFF);
                return (BOOL) hBrushOff;
            }
            if (id == IDC_EDIT1) {
                SetBkMode((HDC) wParam, TRANSPARENT);
                if (job[1].state)
                    return (BOOL) hBrushOn;
                SetTextColor((HDC) wParam, MYOFF);
                return (BOOL)  hBrushOff;
            }
            if (id == IDC_EDIT2) {
                SetBkMode((HDC) wParam, TRANSPARENT);
                if (job[2].state)
                    return (BOOL) hBrushOn;
                SetTextColor((HDC) wParam, MYOFF);
                return (BOOL) hBrushOff;
            }
            if (id == IDC_EDIT3) {
                SetBkMode((HDC) wParam, TRANSPARENT);
                if (job[3].state)
                    return (BOOL) hBrushOn;
                SetTextColor((HDC) wParam, MYOFF);
                return (BOOL) hBrushOff;
            }
            break;
        }

        case WM_INITDIALOG:

            hWnd = hDlg;

            hList = GetDlgItem(hDlg, IDC_LISTBOX1);

            hIcon = LoadIcon(hInst, "TrayIcon");

            iconData.cbSize = sizeof(NOTIFYICONDATA);
            iconData.hWnd = hWnd;
            iconData.uID = 3680;   // any old value
            iconData.uFlags = NIF_ICON | NIF_MESSAGE | NIF_TIP;
            iconData.uCallbackMessage = MYWM_NOTIFYICON;
            iconData.hIcon = hIcon;

            strcpy(iconData.szTip, "XAMPP Control/Refresh");

            icon_up = Shell_NotifyIcon(NIM_ADD, &iconData);

            BottomRightWindow(hDlg);
            
            hFont = CreateFont(14, 8, 0, 0, FW_NORMAL,
                               0, 0, 0, ANSI_CHARSET,
                               OUT_DEFAULT_PRECIS,
                               CLIP_DEFAULT_PRECIS,
                               DEFAULT_QUALITY,
                               FIXED_PITCH,
                               "Courier New");

            SendDlgItemMessage(hDlg, IDC_LISTBOX1, WM_SETFONT, (WPARAM) hFont, (LPARAM) TRUE);
            SendDlgItemMessage(hDlg, IDC_LISTBOX1, LB_SETHORIZONTALEXTENT, (WPARAM) 640, (LPARAM) 0);

            lbprintf(hList, XAMPP_VERSION);
            lbprintf(hList, win_ver);
            lbprintf(hList, "Current Directory: %s", cur_dir);
            // lbprintf(hList, "Install Directory: %s", install_dir);
      
			if (stricmp(cur_dir, install_dir)) {
			  lbprintf(hList, "Install(er)) Directory: No installer package found");
			} else {
  			lbprintf(hList, "Install(er) Directory: %s", install_dir);
			}
            if (stricmp(cur_dir, install_dir))
                //lbprintf(hList, "*** WARNING: Directory mismatch ***");

            error = UpdateStatus(hDlg);

            if (error) {
				// AF: We comment it out because we have a mystery Windows 7 64 Bit bug here
				/*
                if (kprintf("zentaoamp Component Status Check failure [%d].\n\nCurrent directory: %s\n\nRun this program only from your zentaoamp root directory.",
                    error, cur_dir) == IDCANCEL) {
                    EndDialog(hDlg, IDCANCEL);
                    return OK;
                }

                lbprintf(hList, "ERROR: Status Check Failure [%d]", error);
                lbprintf(hList, "This program must be run from your zentaoamp root directory."); */
                lbprintf(hList, "WARN:This program must be run from your zentaoamp root directory.");
				lbprintf(hList, "INFO:Perhaps this program running on a 64 bit plattform so ignore the message above.");
            }
            else
                lbprintf(hList, "Status Check OK");

            // Compute the position of the listbox top y coordinate

            GetWindowRect(hList, &rc1);
            hTmp = GetDlgItem(hDlg, IDC_EDIT9); // an invisible item at y=0
            GetWindowRect(hTmp, &rc2);
            incr = rc1.top - rc2.top;

            if (xampp_service == 0) {
                dlg_flag = 1;
                ShowWindow(hDlg, SW_HIDE);
            }

            timerID = SetTimer(hDlg, 1, 1000, NULL);      // time is 1000 msec

            update_flag = 1;

            if (xampp_service) {
                splash = 1;
                SetWindowText(hDlg, "zentaoamp Control Panel Service");
                EnableWindow(GetDlgItem(hDlg, IDC_SERVICE), 0);
            }
            else {
                SetWindowText(hDlg, "zentao集成运行环境(基于xampp精简版)");
                EnableWindow(GetDlgItem(hDlg, IDC_SERVICE), 1);
            }

            return TRUE;


        case WM_COMMAND:

            wmID = LOWORD(wParam);

            switch (wmID) {

                case IDC_PUSHBUTTON1:

                    if (platform == 0) {
                        kprintf("Feature unavailable on this Windows platform.");
                        break;
                    }

                    // @0 Users
                    // @1 SCM
                    // @2 Devices

                    if (platform2 == 0) {
                        result = execs("control.exe srvmgr.cpl,@1");
                        if (result)
                            kprintf("Exec Error %d", result);
                        break;
                    }

                    result = execs("cmd.exe /C services.msc");

                    if (result)
                        kprintf("Exec Error %d", result);
                    break;

                case IDC_DEBUG:
                    lbprintf(hList, "zentaoamp %s Status %d.%d.%d.%d", xampp_service?"Service":"Application", platform, platform2, platformx, platformx2);
                    for (i=0; i<NJOBS; i++)
                        lbprintf(hList, "%8s State %d Service %d Start %d Thread %x Op %d Port %s",
                                 job[i].name,
                                 job[i].state,
                                 job[i].service,
                                 job[i].start,
                                 job[i].hThread,
                                 job[i].op,
								 job[i].port);
                    return 0;

                case IDC_XAMPP:
                    ShellExecute(hWnd, "open", localhost, NULL, NULL, SW_SHOWNORMAL);
                    return 0;

                case IDC_CHECKBOX0:

                    if (job[0].state) {
                        kprintf("ERROR: Apache is currently running");
                        if (job[0].service)
                            CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_CHECKED);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_UNCHECKED);
                        break;
                    }

                    update_flag = 0;

                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX0) == BST_UNCHECKED) {
                        if (kprintf("Click OK to uninstall the Apache Service") == IDOK)
                            execw("apache\\bin\\httpd.exe -k uninstall", SW_HIDE, &exit_code, 0, DETACHED_PROCESS);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_CHECKED);
                    }
                    else {
                        if (kprintf("Click OK to install the Apache Service") == IDOK)
                            execw("apache\\bin\\httpd.exe -k install", SW_HIDE, &exit_code, 0, DETACHED_PROCESS);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_UNCHECKED);
                    }
                    update_flag = 1;
                    break;

                case IDC_CHECKBOX1:

                    if (job[1].state) {
                        kprintf("ERROR: MySql is currently running");
                        if (job[1].service)
                            CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_CHECKED);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_UNCHECKED);
                        break;
                    }

                    update_flag = 0;

                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX1) == BST_UNCHECKED) {
                        if (kprintf("Click OK to uninstall the MySql Service") == IDOK) {
                            char tmp[512];
                            sprintf(tmp, "%s\\mysql\\bin\\mysqld.exe --remove mysql", cur_dir);
                            execw(tmp, SW_HIDE, &exit_code, 0, DETACHED_PROCESS);
                        }
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_CHECKED);
                    }
                    else {
                        if (kprintf("Click OK to install the MySql Service") == IDOK) {
                            char tmp[512];
                            sprintf(tmp, "%s\\mysql\\bin\\mysqld.exe --install mysql --defaults-file=%s\\mysql\\bin\\my.ini", cur_dir, cur_dir);
                            execw(tmp, SW_HIDE, &exit_code, 0, DETACHED_PROCESS);
                        }
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_UNCHECKED);
                    }
                    update_flag = 1;
                    break;

                case IDC_CHECKBOX2:

                    if (job[2].state) {
                        kprintf("ERROR: FileZilla is currently running");
                        if (job[2].service)
                            CheckDlgButton(hDlg, IDC_CHECKBOX2, BST_CHECKED);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX2, BST_UNCHECKED);
                        break;
                    }

                    update_flag = 0;

                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX2) == BST_UNCHECKED) {
                        if (kprintf("Click OK to uninstall the FileZilla FTP Service") == IDOK)
                            ServiceDelete("FileZilla Server");
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX2, BST_CHECKED);
                    }
                    else {
                        if (kprintf("Click OK to install the FileZilla FTP Service") == IDOK)
                            execw("filezillaftp\\filezillaserver.exe", SW_HIDE, &exit_code, 0, DETACHED_PROCESS);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX2, BST_UNCHECKED);
                    }
                    update_flag = 1;
                    break;

                case IDC_CHECKBOX3:
                    CheckDlgButton(hDlg, IDC_CHECKBOX3, BST_UNCHECKED);
                    break;

                case IDC_SERVICE:
                    EnterDialogBoxService();
                    break;

                case IDC_ACTION0:  //apache:80

                    if (job[0].state == 0)
					{
						system("copy .\\apache\\conf\\httpd80.conf .\\apache\\conf\\httpd.conf");
                        do_job(0, 1);
					}
                    else
                        do_job(0, 0);

                    break;
                    
                case IDC_ACTION1:  //mysql:3306

                    if (job[1].state == 0)
					{
						system("copy .\\mysql\\bin\\my3306.ini .\\mysql\\bin\\my.ini");
						system("copy .\\zentao\\config\\my3306.php .\\zentao\\config\\my.php");
                        do_job(1, 1);
					}
                    else
                        do_job(1, 0);

                    break;

				 case IDC_ACTION2:  //apache:88

                    if (job[0].state == 0)
					{
						system("copy .\\apache\\conf\\httpd88.conf .\\apache\\conf\\httpd.conf");
                        do_job(2, 1);
					}
                    else
                        do_job(2, 0);

                    break;
					                    
                case IDC_ACTION3:  //mysql:3308

                    if (job[3].state == 0)
					{
						system("copy .\\mysql\\bin\\my3308.ini .\\mysql\\bin\\my.ini");
						system("copy .\\zentao\\config\\my3308.php .\\zentao\\config\\my.php");
                        do_job(3, 1);
					}
                    else
                        do_job(3, 0);

                    break;

                case IDC_HELPX:                 // Help

					ShellExecute(hWnd, "open", "http://www.zentao.net/goto.php?item=zentaoamp", NULL, NULL, SW_SHOWNORMAL);

					break;
                
                case IDC_EXPLORE:               // Launch Explorer

                    sprintf(buf, "explorer.exe /e,%s", cur_dir);

                    exec(buf, SW_SHOW);

                    break;

                case IDC_XREFRESH:

                    update_flag = 1;
                    refresh();
                    break;

                case IDOK:

                    return TRUE;
                
                case IDCANCEL:

                    if (hHelp)
                        EndDialog(hHelp, IDOK);

                    if (hServiceDlg)
                        EndDialog(hServiceDlg, IDOK);

                    KillTimer(hDlg, timerID);

                    EndDialog(hDlg, IDOK);

                    if (hServiceStopEvent)
                        SetEvent(hServiceStopEvent);    // Notify the SCM

                    return TRUE;
            }
            break;

        case WM_CLOSE:

            dlg_flag = 0;
            ShowWindow(hWnd, SW_HIDE);
            return TRUE;

        case WM_SIZE:
            if (wParam == 10) {
                ShowWindow(hDlg, SW_HIDE);
                hide_flag = 1;
                return TRUE;
            }

            if ((wParam == SIZE_MINIMIZED) && (hide_flag)) {
                ShowWindow(hDlg, SW_HIDE);
                return TRUE;
            }

            MoveWindow(hList, 0, incr, LOWORD(lParam), HIWORD(lParam) - incr, TRUE);
            break;

        case WM_TIMER:

            if (splash) {
                ShowWindow(hDlg, SW_HIDE);
                splash = 0;
            }

            if (icon_up == 0) {
                iconData.hWnd = hWnd;
                icon_up = Shell_NotifyIcon(NIM_ADD, &iconData);
            }

            error = UpdateStatus(hDlg);
            if (error)
                lbprintf(hList, "ERROR: Status Check Failure [%d]", error);

            if (hStopEvent && WaitForSingleObject(hStopEvent, 100) != WAIT_TIMEOUT)
                SendMessage(hDlg, WM_COMMAND, IDCANCEL, 0);

            if (hWatchdogEvent && WaitForSingleObject(hWatchdogEvent, 100) != WAIT_TIMEOUT)
                SendMessage(hDlg, WM_COMMAND, IDCANCEL, 0);

            break;

    }
    return FALSE;
}

//
// Update Status
//
int UpdateStatus(HWND hDlg)
{
    char tmp[256];
    int i, result, error;

    tmp[0] = 0;
    error = 0;

    if (update_flag == 0)
        return 0;

	GetProcessList();

	// Determine Apache:80 SERVICE status

    result = GetServiceStatus("Apache2.2");

    if (result > 0) {

        // The SERVICE exists and is either running (2) or not (1)

        CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_CHECKED);
        job[0].service = 1;

        if (result == 2) {
            SetDlgItemText(hDlg, IDC_EDIT0, "运行中");
            SetDlgItemText(hDlg, IDC_ACTION0, "停止");
            job[0].state = 1;
        }
        else {
            SetDlgItemText(hDlg, IDC_EDIT0, "停止");
            SetDlgItemText(hDlg, IDC_ACTION0, "运行80");
            job[0].state = 0;
        }
    }
    else {

        job[0].service = 0;

		// Determine Apache APP status

		for (i=0; i<proc_index; i++) {
			if (stristr(proc_tab[i].module, "httpd.exe") &&
				stristr(proc_tab[i].path, cur_dir)) {
			    if (hDlg && update_flag) {
					SetDlgItemText(hDlg, IDC_EDIT0, "运行中");
					SetDlgItemText(hDlg, IDC_ACTION0, "停止");
				}
				job[0].state = 1;
				job[0].dwPID = proc_tab[i].pid;
				break;
			}
		}
		if (i == proc_index) {
			if (hDlg && update_flag) {
				SetDlgItemText(hDlg, IDC_EDIT0, "停止");
				SetDlgItemText(hDlg, IDC_ACTION0, "运行80");
			}
			job[0].state = 0;
			job[0].dwPID = 0;
		}
	}

	// Determine Apache:88 SERVICE status

	result = GetServiceStatus("Apache2.2");

    if (result > 0) {

        // The SERVICE exists and is either running (2) or not (1)

        CheckDlgButton(hDlg, IDC_CHECKBOX0, BST_CHECKED);
        job[2].service = 1;

        if (result == 2) {
            SetDlgItemText(hDlg, IDC_EDIT0, "运行中");
            SetDlgItemText(hDlg, IDC_ACTION2, "停止");
            job[2].state = 1;
        }
        else {
            SetDlgItemText(hDlg, IDC_EDIT0, "停止");
            SetDlgItemText(hDlg, IDC_ACTION2, "运行88");
            job[2].state = 0;
        }
    }
    else {

        job[2].service = 0;

		// Determine Apache APP status

		for (i=0; i<proc_index; i++) {
			if (stristr(proc_tab[i].module, "httpd.exe") &&
				stristr(proc_tab[i].path, cur_dir)) {
			    if (hDlg && update_flag) {
					SetDlgItemText(hDlg, IDC_EDIT0, "运行中");
					SetDlgItemText(hDlg, IDC_ACTION2, "停止");
				}
				job[2].state = 1;
				job[2].dwPID = proc_tab[i].pid;
				break;
			}
		}
		if (i == proc_index) {
			if (hDlg && update_flag) {
				SetDlgItemText(hDlg, IDC_EDIT0, "停止");
				SetDlgItemText(hDlg, IDC_ACTION2, "运行88");
			}
			job[2].state = 0;
			job[2].dwPID = 0;
		}
	}

    // Determine MySql:3306 SERVICE status

    result = GetServiceStatus("mysql");

    if (result > 0) {

        // The SERVICE exists and is either running (2) or not (1)

        CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_CHECKED);
        job[1].service = 1;

        if (result == 2) {
            SetDlgItemText(hDlg, IDC_EDIT1, "运行中");
            SetDlgItemText(hDlg, IDC_ACTION1, "停止");
            job[1].state = 1;
        }
        else {
            SetDlgItemText(hDlg, IDC_EDIT1, "停止");
            SetDlgItemText(hDlg, IDC_ACTION1, "运行3306");
            job[1].state = 0;
        }
    }
    else {

        job[1].service = 0;

		// Determine MySql APP status

		for (i=0; i<proc_index; i++) {
			if (stristr(proc_tab[i].module, "mysqld.exe") &&
				stristr(proc_tab[i].path, cur_dir)) {
				if (hDlg && update_flag) {
					SetDlgItemText(hDlg, IDC_EDIT1, "运行中");
					SetDlgItemText(hDlg, IDC_ACTION1, "停止");
			}
				job[1].state = 1;
				job[1].dwPID = proc_tab[i].pid;
				break;
			} 
		}
		if (i == proc_index) {
			if (hDlg && update_flag) {
				SetDlgItemText(hDlg, IDC_EDIT1, "停止");
				SetDlgItemText(hDlg, IDC_ACTION1, "运行3306");
			}
			job[1].state = 0;
			job[1].dwPID = 0;
		}
	}

    // Determine MySql:3308 SERVICE status

    result = GetServiceStatus("mysql");

    if (result > 0) {

        // The SERVICE exists and is either running (2) or not (1)

        CheckDlgButton(hDlg, IDC_CHECKBOX1, BST_CHECKED);
        job[1].service = 1;

        if (result == 2) {
            SetDlgItemText(hDlg, IDC_EDIT1, "运行中");
            SetDlgItemText(hDlg, IDC_ACTION3, "停止");
            job[1].state = 1;
        }
        else {
            SetDlgItemText(hDlg, IDC_EDIT1, "停止");
            SetDlgItemText(hDlg, IDC_ACTION3, "运行3308");
            job[1].state = 0;
        }
    }
    else {

        job[1].service = 0;

		// Determine MySql APP status

		for (i=0; i<proc_index; i++) {
			if (stristr(proc_tab[i].module, "mysqld.exe") &&
				stristr(proc_tab[i].path, cur_dir)) {
				if (hDlg && update_flag) {
					SetDlgItemText(hDlg, IDC_EDIT1, "运行中");
					SetDlgItemText(hDlg, IDC_ACTION3, "停止");
			}
				job[1].state = 1;
				job[1].dwPID = proc_tab[i].pid;
				break;
			} 
		}
		if (i == proc_index) {
			if (hDlg && update_flag) {
				SetDlgItemText(hDlg, IDC_EDIT1, "停止");
				SetDlgItemText(hDlg, IDC_ACTION3, "运行3308");
			}
			job[1].state = 0;
			job[1].dwPID = 0;
		}
	}

    return 0;
}
 
//
// Worker Thread
//


void Worker(struct job_entry *pjob) {

    HANDLE hTmp;
    int error;

    error = execw(pjob->cmd, SW_HIDE, &exit_code, pjob->hSem, DETACHED_PROCESS);

    if (error)
        return;

    hTmp = pjob->hThread;
    pjob->hThread = 0;
    CloseHandle(hTmp);
    return;
}

//
// execs - execute a program in the SYSTEM Directory
//
int execs(char *cmdline)
{
    BOOL result;
    STARTUPINFO si;
    PROCESS_INFORMATION pi;    
    SECURITY_ATTRIBUTES sa;

    char dir[256];

    sa.nLength = sizeof(sa);
    sa.lpSecurityDescriptor = NULL;
    sa.bInheritHandle = TRUE;

    GetSystemDirectory(dir, 256);

    GetStartupInfo(&si);
    ZeroMemory(&pi, sizeof(PROCESS_INFORMATION));

    result = CreateProcess(
    (LPCTSTR)               NULL,
    (LPTSTR)                cmdline,
    (LPSECURITY_ATTRIBUTES) &sa,
    (LPSECURITY_ATTRIBUTES) &sa,
    (BOOL)                  FALSE,
    (DWORD)                 DETACHED_PROCESS,
    (LPVOID)                NULL,
    (LPCTSTR)               dir,
    (LPSTARTUPINFO)         &si,
    (LPPROCESS_INFORMATION) &pi);
    
    if (result)
        return 0;
    else
        return GetLastError();
}

//
// exec - execute an application and don't wait for completion
//
int exec(char *cmdline, short flag)
{
    BOOL result;
    STARTUPINFO si;
    PROCESS_INFORMATION pi;    

    GetStartupInfo(&si);

    si.dwFlags |= STARTF_USESHOWWINDOW;
    si.wShowWindow = flag;

    ZeroMemory(&pi, sizeof(PROCESS_INFORMATION));
        
    result = CreateProcess(
    (LPCTSTR)               NULL,
    (LPTSTR)                cmdline,
    (LPSECURITY_ATTRIBUTES) NULL,
    (LPSECURITY_ATTRIBUTES) NULL,
    (BOOL)                  FALSE,
    (DWORD)                 CREATE_NEW_CONSOLE | NORMAL_PRIORITY_CLASS,
    (LPVOID)                NULL,
    (LPCTSTR)               NULL,
    (LPSTARTUPINFO)         &si,
    (LPPROCESS_INFORMATION) &pi);
    
    if (result)
        return 0;

    return GetLastError();
}

//
// execw - execute an application and wait for completion, signalling
//         hSem after CreateProcess returns.
//
int execw(char *cmdline, int flag, DWORD *pexit, HANDLE hSem, DWORD dwCreationFlags)
{
    BOOL result;
    STARTUPINFO si;
    PROCESS_INFORMATION pi;

    if (system_shutdown) {
        if (hSem)
            ReleaseSemaphore(hSem, 1, 0);
        if (pexit)
            *pexit = 1;
        return 1;
    }

    GetStartupInfo(&si);

    si.dwFlags |= STARTF_USESHOWWINDOW;
    si.wShowWindow = flag;

    ZeroMemory(&pi, sizeof(PROCESS_INFORMATION));

    result = CreateProcess(
    (LPCTSTR)               NULL,
    (LPTSTR)                cmdline,
    (LPSECURITY_ATTRIBUTES) NULL,
    (LPSECURITY_ATTRIBUTES) NULL,
    (BOOL)                  FALSE,
    (DWORD)                 dwCreationFlags,
    (LPVOID)                NULL,
    (LPCTSTR)               NULL,
    (LPSTARTUPINFO)         &si,
    (LPPROCESS_INFORMATION) &pi);

    if (hSem)
        ReleaseSemaphore(hSem, 1, 0);
    
    if (result) {
        WaitForSingleObject(pi.hProcess, INFINITE);
        if (pexit)
            GetExitCodeProcess(pi.hProcess, pexit);
        CloseHandle(pi.hProcess);
        CloseHandle(pi.hThread);
        return 0;
    }
    else
        return GetLastError();
}

//
// execwh - execute an application and wait for completion (hidden)
//
int execwh(char *cmdline)
{
    DWORD result;
    STARTUPINFO si;
    PROCESS_INFORMATION pi;    
    SECURITY_ATTRIBUTES sa;

    char dir[256];

    sa.nLength = sizeof(sa);
    sa.lpSecurityDescriptor = NULL;
    sa.bInheritHandle = TRUE;

    GetCurrentDirectory(256, dir);

    GetStartupInfo(&si);
    si.dwFlags |= STARTF_USESHOWWINDOW;
    si.wShowWindow = SW_HIDE;

    ZeroMemory(&pi, sizeof(PROCESS_INFORMATION));
        
    result = CreateProcess(
    (LPCTSTR)               NULL,
    (LPTSTR)                cmdline,
    (LPSECURITY_ATTRIBUTES) &sa,
    (LPSECURITY_ATTRIBUTES) &sa,
    (BOOL)                  FALSE,
    (DWORD)                 DETACHED_PROCESS,
    (LPVOID)                NULL,
    (LPCTSTR)               dir,
    (LPSTARTUPINFO)         &si,
    (LPPROCESS_INFORMATION) &pi);
    
    if (result) {
        WaitForSingleObject(pi.hProcess, INFINITE);
        GetExitCodeProcess(pi.hProcess, &result);
        CloseHandle(pi.hProcess);
        CloseHandle(pi.hThread);

        return result;
    }
    else
        return GetLastError();
}

//
//  sprintf  --  print a formatted message to a buffer
//
int sprintf(char *buf, char *fmt, ...)
{
    va_list args;
    int  n;

    va_start(args, fmt);

    n = vsprintf(buf, fmt, args);

    va_end(args);

    if (n < 0) {
        kprintf("sprintf error: %d", n);;
        return SYSERR;
    }

    buf[n] = 0;

    return n;
}
//
// printf - low-level printf to Console
//
int printf(char *fmt, ...)
{
    HANDLE hOut;
    DWORD len, result;
    va_list args;
    char buf[0x8000];

    va_start(args, fmt);
    vsprintf(buf, fmt, args);
    va_end(args);

    AllocConsole();
    hOut = GetStdHandle(STD_OUTPUT_HANDLE);
    result = WriteFile(hOut, buf, strlen(buf), &len, NULL);

    CloseHandle(hOut);
    return OK;
}

//
// dprintf - low-level printf via OutputDebugString
//           Run www.sysinternals.com DebugView.exe
//           to view this output.
//
int dprintf(char *fmt, ...)
{
    va_list args;
    char buf[0x8000];

    va_start(args, fmt);
    vsprintf(buf, fmt, args);
    va_end(args);

    OutputDebugString(buf);
    return OK;
}

//
// kprintf - low-level printf
//
int kprintf(char *fmt, ...)
{
    va_list args;
    char buf[0x8000];

    va_start(args, fmt);
    vsprintf(buf, fmt, args);
    va_end(args);

    return MessageBoxEx(GetForegroundWindow(), buf, "zentaoamp Control", MB_OKCANCEL | MB_SETFOREGROUND | MB_ICONWARNING, 0);

}

//
// lbprintf - listbox printf
//
int lbprintf(HWND hList, char *fmt, ...)
{
    va_list args;
    int i;
    char buf[0x8000];

    va_start(args, fmt);
    vsprintf(buf, fmt, args);
    va_end(args);

    i = SendMessage(hList, LB_ADDSTRING, 0, (LPARAM)(LPCSTR) buf);
    SendMessage(hList, LB_SETCARETINDEX, i, 0);

    return strlen(buf);

}

BOOL BottomRightWindow(HWND hWin)
{
    int result;
    HWND hDesktop;

    RECT rc;
    RECT rcd;

    hDesktop = GetDesktopWindow();
    result = GetWindowRect(hWin, &rc);

    if (result)
        result = GetWindowRect(hDesktop, &rcd);

    if (result == 0)
        return SYSERR;

    MoveWindow(hWin,
               rcd.right - (rc.right - rc.left),
               rcd.bottom - (rc.bottom - rc.top + 32), // 24 for task bar
               rc.right - rc.left, rc.bottom - rc.top,
               TRUE);
    return OK;
}


BOOL CenterWindow(HWND hWin)
{
    int result;
    HWND hDesktop;

    RECT rc;
    RECT rcd;
        
    hDesktop = GetDesktopWindow();
    result = GetWindowRect(hWin, &rc);

    if (result)
        result = GetWindowRect(hDesktop, &rcd);
    
    if (result == 0)
        return -1;
        
    SetWindowPos(hWin, HWND_TOP,
                 rcd.right/2 - (rc.right - rc.left)/2,
                 rcd.bottom/2 - (rc.bottom - rc.top)/2,
                 0, 0,
                 SWP_NOSIZE);
    return 0;
}

char      TargetClass[1024];
char      TargetTitle[1024];

HWND      hWindow;

BOOL CALLBACK EnumWindowsProc(HWND hWnd,      // handle to parent window
                              LPARAM lParam)  // application-defined value
{
    char WinTitle[1024];
    char WinClass[1024];
    int  clen, tlen;
    DWORD pid;
    DWORD tid;

    tid = GetWindowThreadProcessId(hWnd, &pid);

    tlen = GetWindowText(hWnd, WinTitle, 1023);
    WinTitle[tlen] = 0;

    clen = GetClassName(hWnd, WinClass, 1023);
    WinClass[clen] = 0;

    if (tlen && strlen(TargetTitle)) {

        if (stristr(strupr(WinTitle), strupr(TargetTitle))) {

            // Title matches so check class

            if (strlen(TargetClass) == 0) {

                // No target class so assume a match

                hWindow = hWnd;
                return FALSE;
            }

            if (stristr(strupr(WinClass), strupr(TargetClass))) {

                hWindow = hWnd;
                return FALSE;
            }
        }
    }

    if (clen && strlen(TargetClass)) {

        if (stristr(strupr(WinClass), strupr(TargetClass))) {

            // Class matches so check title

            if (strlen(TargetTitle) == 0) {

                // No target title so assume no match

                return TRUE;
            }

            if (stristr(strupr(WinTitle), strupr(TargetTitle))) {

                hWindow = hWnd;
                return FALSE;
            }
        }
    }

    // Carry on

    return TRUE;
}      

int find(char *title)
{

    strcpy(TargetTitle, title);

    EnumWindows(EnumWindowsProc, (LPARAM) 0);

    if (hWindow == 0)
        return 0;

    ShowWindow(hWindow, SW_SHOWNORMAL);

    if (!IsWindowVisible(hWindow))
        ShowWindow(hWindow, SW_RESTORE);

    SetForegroundWindow(hWindow);      // give focus to other window

    Sleep(2000);

    return 1;
}

int refresh() {

    lbprintf(hList, "Refresh...");

    UpdateStatus(hWnd);

    lbprintf(hList, "Done");

    return OK;
}

// Display the dialog box

int EnterDialogBox()
{    
    int result;

    result = DialogBox(hInst, "XAMPP", NULL, MyDlgProc);

    if (result == -1) {
        kprintf("ERROR: unable to create zentaoamp dialog\n");
        return SYSERR;
    }

    Shell_NotifyIcon(NIM_DELETE, &iconData);

    return OK;
}
                   
//run apache(80 88) and mysql(3306 3308)
int do_job(int m, int n) {

    int result;
    struct job_entry *pjob = &job[m];

    if (n == SYSERR)            // NOOP
        return OK;

    if (pjob->state == n)      // nothing to do
        return OK;

    lbprintf(hList, "Busy...");

    switch (m) {

        case 0: //apache:80

            if (n) {
				localhost[16] = ':';
				localhost[17] = '8';
				localhost[18] = '0';
				localhost[19] = '\0';
                if (pjob->service) {
                    result = ServiceStart("Apache2.2");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: Apache service not started [%d]", result);
                    else
                        lbprintf(hList, "Apache service started");
                    break;
                }

                // strcpy(pjob->cmd, "apache\\bin\\apache.exe");
                 if (pjob->service) {
                strcpy(pjob->cmd, "apache\\bin\\httpd.exe -k start");
                }
                else {
                strcpy(pjob->cmd, "apache\\bin\\httpd.exe");
                }

                if (pjob->hThread) {
                    lbprintf(hList, "WARNING: terminating worker thread 0");
                    TerminateThread(pjob->hThread, 0);
                    pjob->hThread = 0;
                }

                pjob->hThread = CreateThread(0, 0,
                                            (LPTHREAD_START_ROUTINE) Worker,
                                            pjob,
                                            0, &pjob->dwTID);

                WaitForSingleObject(pjob->hSem, 5000);  // Be sure execw has been called

                lbprintf(hList, "Apache started [Port %s]", apache_port80);

            }
            else {
                if (pjob->service) {
                    result = ServiceStop("Apache2.2");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: %d", result);
                    else
                        lbprintf(hList, "Apache service stopped");
                    break;
                }
				else {
					char cmd[1024];

					// Kill the first Apache process

					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);

					execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);

					UpdateStatus(0);

					// Kill the second Apache process

					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);

			        execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);
 
                    lbprintf(hList, "Apache stopped [Port %s]", apache_port80);
				}
			}

            break;
		case 2: //apache:88

            if (n) {
				localhost[16] = ':';
				localhost[17] = '8';
				localhost[18] = '8';
				localhost[19] = '\0';
                if (pjob->service) {
                    result = ServiceStart("Apache2.2");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: Apache service not started [%d]", result);
                    else
                        lbprintf(hList, "Apache service started");
                    break;
                }

                // strcpy(pjob->cmd, "apache\\bin\\apache.exe");
                 if (pjob->service) {
                strcpy(pjob->cmd, "apache\\bin\\httpd.exe -k start");
                }
                else {
                strcpy(pjob->cmd, "apache\\bin\\httpd.exe");
                }

                if (pjob->hThread) {
                    lbprintf(hList, "WARNING: terminating worker thread 0");
                    TerminateThread(pjob->hThread, 0);
                    pjob->hThread = 0;
                }

                pjob->hThread = CreateThread(0, 0,
                                            (LPTHREAD_START_ROUTINE) Worker,
                                            pjob,
                                            0, &pjob->dwTID);

                WaitForSingleObject(pjob->hSem, 5000);  // Be sure execw has been called

                lbprintf(hList, "Apache started [Port %s]", apache_port88);

            }
            else {
                if (pjob->service) {
                    result = ServiceStop("Apache2.2");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: %d", result);
                    else
                        lbprintf(hList, "Apache service stopped");
                    break;
                }
				else {
					char cmd[1024];

					// Kill the first Apache process

					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);

					execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);

					UpdateStatus(0);

					// Kill the second Apache process

					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);

			        execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);
 
                    lbprintf(hList, "Apache stopped [Port %s]", apache_port88);
				}
			}

            break;
                    
        case 1: //mysql:3306

            if (n) {
                if (pjob->service) {
                    result = ServiceStart("mysql");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: MySql service not started [%d]", result);
                    else
                        lbprintf(hList, "MySql service started");
                    break;
                }

                strcpy(pjob->cmd, "mysql\\bin\\mysqld.exe --defaults-file=mysql\\bin\\my.ini --standalone");

                if (pjob->hThread) {
                    lbprintf(hList, "WARNING: terminating worker thread 1");
                    TerminateThread(pjob->hThread, 0);
                    pjob->hThread = 0;
                }

                pjob->hThread = CreateThread(0, 0,
                                            (LPTHREAD_START_ROUTINE) Worker,
                                            pjob,
                                            0, &pjob->dwTID);

                WaitForSingleObject(pjob->hSem, 5000);  // Be sure execw has been called

                lbprintf(hList, "MySql started [Port %s]", mysql_port3306);
            }
            else {
                if (pjob->service) {
                    result = ServiceStop("mysql");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: MySql service not stopped [%d]", result);
                    else
                        lbprintf(hList, "MySql service stopped");
                    break;
                }
				else {	
					int i;
					char cmd[256];

					// sprintf(cmd, "mysql\\bin\\mysqladmin.exe --port=%s -u root -h localhost shutdown", mysql_port);
					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);
				    result = execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);

					// Wait for MySql to actually terminate 

					i = 20;

					while (i-- && pjob->state) {
						UpdateStatus(0);
						Sleep(500);
					}
					if (i < 0)
						lbprintf(hList, "ERROR: MySql not stopped [%d]", result);
					else	
						lbprintf(hList, "MySql stopped [Port %s]", mysql_port3306);
				}
			}

            break;

		case 3: //mysql:3308

            if (n) {
                if (pjob->service) {
                    result = ServiceStart("mysql");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: MySql service not started [%d]", result);
                    else
                        lbprintf(hList, "MySql service started");
                    break;
                }

                strcpy(pjob->cmd, "mysql\\bin\\mysqld.exe --defaults-file=mysql\\bin\\my.ini --standalone");

                if (pjob->hThread) {
                    lbprintf(hList, "WARNING: terminating worker thread 1");
                    TerminateThread(pjob->hThread, 0);
                    pjob->hThread = 0;
                }

                pjob->hThread = CreateThread(0, 0,
                                            (LPTHREAD_START_ROUTINE) Worker,
                                            pjob,
                                            0, &pjob->dwTID);

                WaitForSingleObject(pjob->hSem, 5000);  // Be sure execw has been called

                lbprintf(hList, "MySql started [Port %s]", mysql_port3308);
            }
            else {
                if (pjob->service) {
                    result = ServiceStop("mysql");
                    if (result == SYSERR)
                        lbprintf(hList, "ERROR: MySql service not stopped [%d]", result);
                    else
                        lbprintf(hList, "MySql service stopped");
                    break;
                }
				else {	
					int i;
					char cmd[256];

					// sprintf(cmd, "mysql\\bin\\mysqladmin.exe --port=%s -u root -h localhost shutdown", mysql_port);
					sprintf(cmd, "apache\\bin\\pv.exe -f -k -q -i %d", pjob->dwPID);
				    result = execw(cmd, SW_HIDE, 0, 0, DETACHED_PROCESS);

					// Wait for MySql to actually terminate 

					i = 20;

					while (i-- && pjob->state) {
						UpdateStatus(0);
						Sleep(500);
					}
					if (i < 0)
						lbprintf(hList, "ERROR: MySql not stopped [%d]", result);
					else	
						lbprintf(hList, "MySql stopped [Port %s]", mysql_port3308);
				}
			}

            break;
        
    }

    strcpy(iconData.szTip, "Right-click to refresh");
    Shell_NotifyIcon(NIM_MODIFY, &iconData);

    return OK;
}

BOOL APIENTRY ServiceDlgProc (HWND hDlg, UINT msg, WPARAM wParam, LPARAM lParam)
{
    int wmID, i, n, result, exit_code;
    char value[1024];
    char tmp[512];
    char tmp1[512];
    char account[256] = "";
    char password[256] = "";
    char object[256] = "";
    char *ptr;

    switch (msg) {

        case WM_INITDIALOG:

            hServiceDlg = hDlg;

            CenterWindow(hDlg);

            if (xampp_service)
                SetWindowText(hDlg, "服务设置 [active]");
            else
                SetWindowText(hDlg, "服务设置");

            service_changed = 0;

            if (GetServiceValues(value, object, &service_type) == 0) {

                // Service is currently enabled

                service_enabled = 1;

                CheckDlgButton(hDlg, IDC_CHECKBOX9, 1);

                // Set checkboxes according to service parameters

                for (i=0; i<NJOBS; i++) {
                    if (stristr(value, job[i].name)) {
                        job[i].start = 1;
                        CheckDlgButton(hDlg, IDC_CHECKBOX4+i, BST_CHECKED);
                    }
                    else {
                        job[i].start = 0;
                        CheckDlgButton(hDlg, IDC_CHECKBOX4+i, BST_UNCHECKED);
                    }
                }
                if (platform) {

                    if (strcmp(object, "LocalSystem") == 0) {
                        CheckDlgButton(hDlg, IDC_RADIOBUTTON7, 1);
                        if (service_type == 0x110)
                            CheckDlgButton(hDlg, IDC_CHECKBOX30, 1);
                        else
                            CheckDlgButton(hDlg, IDC_CHECKBOX30, 0);
                    }
                    else {
                        CheckDlgButton(hDlg, IDC_RADIOBUTTON8, 1);
                        ptr = stristr(object, ".\\");
                        if (ptr)
                            ptr+= 2;
                        else
                            ptr = object;

                        SetDlgItemText(hDlg, IDC_EDIT83, ptr);
                    }
                }
            }
            else {

                // Service does not exist

                service_enabled = 0;
                service_type = 0x110;   // default to allow Desktop Interaction

                InitServiceCmd(value);

                for (i=0; i<NJOBS; i++) {
                    if (job[i].state) {
                        CheckDlgButton(hDlg, IDC_CHECKBOX4+1, BST_CHECKED);
                        job[i].start = 1;
                        strcat(value, job[i].name);
                        strcat(value, " ");
                    }
                }
            }

            SetDlgItemText(hDlg, IDC_EDIT30, value);

            return TRUE;

        case WM_DESTROY:
            break;

        case WM_COMMAND:

            wmID = LOWORD(wParam);

            switch (wmID) {

                case IDHELP:
					ShellExecute(hWnd, "open", "http://www.zentao.net", NULL, NULL, SW_SHOWNORMAL);
                    return TRUE;

                case IDC_CHECKBOX4:
                case IDC_CHECKBOX5:
                case IDC_CHECKBOX6:
                case IDC_CHECKBOX7:


                    if (IsDlgButtonChecked(hDlg, wmID))
                        job[wmID-IDC_CHECKBOX4].start = 1;
                    else
                        job[wmID-IDC_CHECKBOX4].start = 0;

                    InitServiceCmd(value);

                    for (i=0; i<NJOBS; i++) {
                        if (job[i].start) {
                            strcat(value, job[i].name);
                            strcat(value, " ");
                        }
                    }

                    SetDlgItemText(hDlg, IDC_EDIT30, value);

                    break;

                case IDC_CHECKBOX9:
                    service_changed = 1;
                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX9)) {
                        EnableButtons(hDlg, 1);
                        if (platform) {
                            EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX30), 1);
                            if (service_type == 0x110)
                                CheckDlgButton(hDlg, IDC_CHECKBOX30, 1);
                            else
                                CheckDlgButton(hDlg, IDC_CHECKBOX30, 0);
                            EnableWindow(GetDlgItem(hDlg, IDC_RADIOBUTTON7), 1);
                            CheckDlgButton(hDlg, IDC_RADIOBUTTON7, 1);
                            EnableWindow(GetDlgItem(hDlg, IDC_RADIOBUTTON8), 1);
                            CheckDlgButton(hDlg, IDC_RADIOBUTTON8, 0);
                        }

                        InitServiceCmd(value);

                        for (i=0; i<NJOBS; i++) {
                            if (job[i].state) {
                                CheckDlgButton(hDlg, IDC_CHECKBOX4+1, BST_CHECKED);
                                job[i].start = 1;
                                strcat(value, job[i].name);
                                strcat(value, " ");
                            }
                        }

                        SetDlgItemText(hDlg, IDC_EDIT30, value);

                    }
                    else {

                        EnableButtons(hDlg, 0);

                        if (service_enabled) {
                            if (kprintf("Click OK to uninstall the zentaoamp Service.") != IDCANCEL) {

                                if (platform)
                                    execw("service.exe -remove", SW_HIDE, &exit_code, 0, DETACHED_PROCESS);

                                DeleteServiceValue();

                                service_enabled = 0;
                                service_changed = 0;
                                goto service_done;
                            }
                            else {
                                CheckDlgButton(hDlg, IDC_CHECKBOX9, 1);
                                goto service_done;
                            }
                        }

                        CheckDlgButton(hDlg, IDC_CHECKBOX30, 0);
                        EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX30), 0);

                        if (platform) {
                            EnableWindow(GetDlgItem(hDlg, IDC_RADIOBUTTON7), 0);
                            EnableWindow(GetDlgItem(hDlg, IDC_RADIOBUTTON8), 0);
                            CheckDlgButton(hDlg, IDC_RADIOBUTTON7, 0);
                            CheckDlgButton(hDlg, IDC_RADIOBUTTON8, 0);
                            EnableWindow(GetDlgItem(hDlg, IDC_PASSWORD), 0);
                            EnableWindow(GetDlgItem(hDlg, IDC_EDIT83), 0);
                            EnableWindow(GetDlgItem(hDlg, IDC_EDIT84), 0);
                            SetDlgItemText(hDlg, IDC_EDIT83, "");
                        }
                    }
                    break;

                case IDC_RADIOBUTTON7:
                    service_changed = 1;
                    if (IsDlgButtonChecked(hDlg, IDC_RADIOBUTTON7)) {
                        EnableWindow(GetDlgItem(hDlg, IDC_PASSWORD), 0);
                        SetDlgItemText(hDlg, IDC_EDIT83, "");
                        SetDlgItemText(hDlg, IDC_EDIT84, "");
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT83), 0);
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT84), 0);
                        EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX30), 1);
                    }
                    else {
                        EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX30), 0);
                        CheckDlgButton(hDlg, IDC_CHECKBOX30, 0);
                    }
                    break;

                case IDC_RADIOBUTTON8:
                    service_changed = 1;
                    if (IsDlgButtonChecked(hDlg, IDC_RADIOBUTTON8)) {
                        EnableWindow(GetDlgItem(hDlg, IDC_PASSWORD), 1);
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT83), 1);
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT84), 1);
                        SetDlgItemText(hDlg, IDC_EDIT83, current_user);
                        EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX30), 0);
                        CheckDlgButton(hDlg, IDC_CHECKBOX30, 0);
                    }
                    else {
                        EnableWindow(GetDlgItem(hDlg, IDC_PASSWORD), 0);
                        SetDlgItemText(hDlg, IDC_EDIT83, "");
                        SetDlgItemText(hDlg, IDC_EDIT84, "");
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT83), 0);
                        EnableWindow(GetDlgItem(hDlg, IDC_EDIT84), 0);
                    }
                    break;

                case IDC_CHECKBOX30:
                    service_changed = 1;
                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX30))
                        service_type = 0x110;
                    else
                        service_type = 0x10;

                    break;

                case IDC_PUSHBUTTON41:

                    if (platform == 0) {
                        kprintf("Feature unavailable on this Windows platform.");
                        break;
                    }

                    // @0 Users
                    // @1 SCM
                    // @2 Devices

                    if (platform2 == 0) {
                        result = execs("control.exe srvmgr.cpl,@1");
                        if (result)
                            kprintf("Exec Error %d", result);
                        break;
                    }

                    result = execs("cmd.exe /C services.msc");

                    if (result)
                        kprintf("Exec Error %d", result);
                    break;

                case IDOK:

                    if (!service_changed)
                        goto service_done;

                    if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX9) == 1) {
                        n = GetDlgItemText(hDlg, IDC_EDIT30, value, 1024);
                        if (n == 0) {
                            kprintf("Please enter a full zentaoamp command line.\n");
                            SetFocus(GetDlgItem(hDlg, IDC_EDIT30));
                            break;
                        }
                        n = 0;
                        if (IsDlgButtonChecked(hDlg, IDC_RADIOBUTTON8)) {
                            n = GetDlgItemText(hDlg, IDC_EDIT83, account, 256);
                            if (n == 0) {
                                kprintf("ERROR: no Account specified");
                                SetFocus(GetDlgItem(hDlg, IDC_EDIT83));
                                break;
                            }
                            n = GetDlgItemText(hDlg, IDC_EDIT84, password, 256);
                            if (n == 0) {
                                kprintf("ERROR: no Password specified");
                                SetFocus(GetDlgItem(hDlg, IDC_EDIT84));
                                break;
                            }
                        }

                        InitServiceCmd(value);

                        for (i=0; i<NJOBS; i++)
                            if (job[i].start) {
                                strcat(value, job[i].name);
                                strcat(value, " ");
                            }

                        strcpy(saved_value, value);

                        if (IsDlgButtonChecked(hDlg, IDC_RADIOBUTTON7))
                            strcpy(account, "SYSTEM");

                        if (platform == 0)
                            tmp[0] = 0;
                        else
                            if (service_type & 0x100)
                                tmp[0] = 0;
                            else
                                sprintf(tmp, "\n\nWARNING: The zentaoamp Service will not display a User Interface.");

                        if (platform2)
                            sprintf(tmp1, "\n\nWARNING: The zentaoamp Service will run under the %s Account.", account);
                        else
                            tmp1[0] = 0;

                        if (kprintf("Click OK to install the zentaoamp Service: %s%s%s", value, tmp, tmp1) != IDCANCEL) {

                            DeleteServiceValue();   // delete the old one
                            if (IsDlgButtonChecked(hDlg, IDC_CHECKBOX30))
                                service_type = 0x110;
                            else
                                service_type = 0x10;

                            result = AddServiceValue(value, service_type);

                            if (result) {
                                if (platform) {
                                    if (result != SYSERR) {
                                        execwh("service.exe -remove");
                                        execwh("service.exe -install");
                                        AddServiceValue(saved_value, service_type);
                                        if (n) {
                                            SetServiceName(account, password, ".");
                                        }
                                    }
                                }
                            }
                            else
                                kprintf("ERROR: the service could not be installed");
                        }
                    }
                    else
                        if (GetServiceValues(value, object, &service_type) == 0)
                            if (kprintf("Click OK to uninstall the zentaoamp Service.") != IDCANCEL) {

                                if (platform)
                                    execw("service.exe -remove", SW_HIDE, &exit_code, 0, DETACHED_PROCESS);

                                DeleteServiceValue();
                            }

                    // fall through
service_done:
                case IDCANCEL:
                    if (hAbout) {
                        EndDialog(hAbout, IDOK);
                        hAbout = 0;
                    }
                    hServiceDlg = 0;
                    EndDialog(hDlg, IDOK);
                    return TRUE;
            }
            break;
    }
    return FALSE;
}

int EnterDialogBoxService(int argc, char **argv)
{
    int result;

    if (hServiceDlg) {

        ShowWindow(hServiceDlg, SW_RESTORE);
        SetForegroundWindow(hServiceDlg);
        return OK;
/*
        EndDialog(hServiceDlg, IDOK);
        hServiceDlg = 0;
        return TRUE;
*/
     }

    result = DialogBox(hInst, "SERVICE", NULL, ServiceDlgProc);
    if (result == -1) {
        kprintf("unable to create SERVICE dialog\n");
        return SYSERR;
    }
    hServiceDlg = 0;
    return OK;
}
int EnableButtons(HWND hDlg, int flag) {

    int i;
    

    EnableWindow(GetDlgItem(hDlg, IDC_EDIT30), flag);

    for (i=0; i<NJOBS; i++)
        EnableWindow(GetDlgItem(hDlg, IDC_CHECKBOX4+i), flag);

    return OK;
}

// Add a service value to the registry

int AddServiceValue(char *value, int type)
{
    if (platform)
        return AddServiceValueNT(value, type);
    else
        return AddServiceValue95(value);
}

int AddServiceValue95(char *value)
{

    HKEY    CurrentVersionKey;
    HKEY    RunKey;

    char    buf[1024];
    DWORD   len = 1024;

    LONG    Status;

    DWORD   RegType;
    DWORD   result;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("Software\\Microsoft\\Windows\\CurrentVersion"),
                          0,
                          KEY_READ,
                          &CurrentVersionKey);

    if (Status == ERROR_SUCCESS) {

        Status = RegCreateKeyEx(CurrentVersionKey,
                                TEXT("RunServices"),
                                0,
                                "",
                                0,
                                KEY_ALL_ACCESS,
                                NULL,
                                &RunKey,
                                &result);

        if (Status == ERROR_SUCCESS) {

            Status = RegQueryValueEx(RunKey,
                                    TEXT("XAMPP"),
                                    NULL,
                                    &RegType,
                                    (LPBYTE)buf,
                                    (LPDWORD)&len);
            if (Status != ERROR_SUCCESS) {
                Status = RegSetValueEx(RunKey,
                                       TEXT("XAMPP"),
                                       0,
                                       REG_SZ,
                                       (LPBYTE) value,
                                       strlen(value));
            }
            RegCloseKey(RunKey);
        }
        RegCloseKey(CurrentVersionKey);
    }
    return Status;
}

int AddServiceValueNT(char *value, int type)
{

    HKEY    ServicesKey;
    HKEY    XamppKey;
    HKEY    ParametersKey;

    int     flag;
    int     flen = 4;

    char    tmp[256];
    char    description[256];

    LONG    Status;
    LONG    Result;

    DWORD   RegType;

    char    *ptr;

    ptr = stristr(value, ".exe ");
    if (ptr == 0)
        return TRUE;    // syntax error

    ptr += 4;           // skip to the space after .exe

    *(ptr++) = 0;       // value now is just the command

    strcpy(tmp, ptr);   // tmp now contains the args

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("SYSTEM\\CurrentControlSet\\Services"),
                          0,
                          KEY_ALL_ACCESS,
                          &ServicesKey);

    if (Status == ERROR_SUCCESS) {

        Status = RegOpenKeyEx(ServicesKey,
                              TEXT("XAMPP"),
                              0,
                              KEY_ALL_ACCESS,
                              &XamppKey);

        if (Status == ERROR_SUCCESS) {

            Status = RegQueryValueEx(XamppKey,
                                     TEXT("DeleteFlag"),
                                     NULL,
                                     &RegType,
                                     (LPBYTE)&flag,
                                     (LPDWORD)&flen);

            if (Status == ERROR_SUCCESS)
                if (flag) {
                    kprintf("The zentaoamp service is marked for deletion.\nPlease restart XAMPP.\n");
                    Status = SYSERR;
                    goto label;
                }

            // Set Desktop Interaction

            RegSetValueEx(XamppKey,
                          TEXT("Type"),
                          0,
                          REG_DWORD,
                          (LPBYTE)&type,
                          4);

            sprintf(description, "%s", XAMPP_VERSION);

            RegSetValueEx(XamppKey,
                          TEXT("Description"),
                          0,
                          REG_SZ,
                          (LPBYTE)description,
                          strlen(description));

            Status = RegOpenKeyEx(XamppKey,
                                  TEXT("Parameters"),
                                  0,
                                  KEY_ALL_ACCESS,
                                  &ParametersKey);

            if (Status != ERROR_SUCCESS)
                Status = RegCreateKeyEx(XamppKey,
                                        TEXT("Parameters"),
                                        0,
                                        "",
                                        0,
                                        KEY_ALL_ACCESS,
                                        NULL,
                                        &ParametersKey,
                                        (LPDWORD) &Result);

            if (Status == ERROR_SUCCESS) {

                RegSetValueEx(ParametersKey,
                              TEXT("Application"),
                              0,
                              REG_SZ,
                              (LPBYTE) value,
                              strlen(value)+1);

                RegSetValueEx(ParametersKey,
                              TEXT("AppParameters"),
                              0,
                              REG_SZ,
                              (LPBYTE) tmp,
                              strlen(tmp)+1);

                RegCloseKey(ParametersKey);
            }
label:
            RegCloseKey(XamppKey);
        }
        RegCloseKey(ServicesKey);
    }
    return Status;
}

// Delete XAMPP service value from the registry

int DeleteServiceValue()
{
    if (platform)
        return DeleteServiceValueNT();
    else
        return DeleteServiceValue95();
}

int DeleteServiceValue95()
{

    HKEY    RunKey;

    char    buf[1024];
    DWORD   len = 1024;

    LONG    Status;

    DWORD   RegType;


    buf[0] = 0;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("Software\\Microsoft\\Windows\\CurrentVersion\\RunServices"),
                          0,
                          KEY_READ,
                          &RunKey);

    if (Status == ERROR_SUCCESS) {

        Status = RegQueryValueEx(RunKey,
                                 TEXT("XAMPP"),
                                 NULL,
                                 &RegType,
                                 (LPBYTE)buf,
                                 &len);
        if (Status == ERROR_SUCCESS) {
            Status = RegDeleteValue(RunKey,
                                    TEXT("XAMPP"));
        }
        RegCloseKey(RunKey);
    }
    return Status;
}

int DeleteServiceValueNT()
{
    HKEY    ServicesKey;
    HKEY    XamppKey;
    LONG    Status;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("SYSTEM\\CurrentControlSet\\Services"),
                          0,
                          KEY_ALL_ACCESS,
                          &ServicesKey);

    if (Status == ERROR_SUCCESS) {
        Status = RegOpenKeyEx(ServicesKey,
                          TEXT("XAMPP"),
                          0,
                          KEY_ALL_ACCESS,
                          &XamppKey);

        if (Status == ERROR_SUCCESS) {
            RegDeleteKey(XamppKey,
                         TEXT("Enum"));
            RegDeleteKey(XamppKey,
                         TEXT("Parameters"));
            RegDeleteKey(XamppKey,
                         TEXT("Security"));

            RegCloseKey(XamppKey);
        }
        Status = RegDeleteKey(ServicesKey, TEXT("XAMPP"));
        RegCloseKey(ServicesKey);
    }
    return Status;
}

// Initialize the service command line

int InitServiceCmd(char *value) {

    GetCurrentDirectory(1024, value);
    if (platform)
        strcat(value, "\\service.exe ");
    else
        strcat(value, "\\xampp.exe ");

    return OK;
}

// Get the XAMPP service values from the registry

int GetServiceValues(char *value, char *object, int *type)
{
    if (platform)
        return GetServiceValuesNT(value, object, type);
    else
        return GetServiceValue95(value);
}

int GetServiceValue95(char *value)
{

    HKEY    RunKey;

    DWORD   len = 1024;

    LONG    Status;

    DWORD   RegType;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("Software\\Microsoft\\Windows\\CurrentVersion\\RunServices"),
                          0,
                          KEY_READ,
                          &RunKey);

    if (Status == ERROR_SUCCESS) {

        Status = RegQueryValueEx(RunKey,
                                 TEXT("XAMPP"),
                                 NULL,
                                 &RegType,
                                 (LPBYTE) value,
                                 &len);

        RegCloseKey(RunKey);
    }
    return Status;
}

int GetServiceValuesNT(char *value, char *object, int *type)
{

    HKEY    XamppKey;
    HKEY    ParametersKey;

    int     len;

    char    tmp[256];

    LONG    Status;

    DWORD   RegType;

    value[0] = 0;
    object[0] = 0;
    tmp[0] = 0;
    *type = 0;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("SYSTEM\\CurrentControlSet\\Services\\XAMPP"),
                          0,
                          KEY_READ,
                          &XamppKey);

    if (Status == ERROR_SUCCESS) {

        len = 4;
        RegQueryValueEx(XamppKey,
                        TEXT("Type"),
                        NULL,
                        &RegType,
                        (LPBYTE) type,
                        (LPDWORD)&len);

        len = 256;
        RegQueryValueEx(XamppKey,
                        TEXT("ObjectName"),
                        NULL,
                        &RegType,
                        (LPBYTE) object,
                        (LPDWORD)&len);

        Status = RegOpenKeyEx(XamppKey,
                              TEXT("Parameters"),
                              0,
                              KEY_READ,
                              &ParametersKey);

        if (Status == ERROR_SUCCESS) {
            len = 1024;
            RegQueryValueEx(ParametersKey,
                            TEXT("Application"),
                            NULL,
                            &RegType,
                            (LPBYTE) value,
                            (LPDWORD)&len);

            len = 256;
            RegQueryValueEx(ParametersKey,
                            TEXT("AppParameters"),
                            NULL,
                            &RegType,
                            (LPBYTE) tmp,
                            (LPDWORD)&len);

            strcat(value, " ");
            strcat(value, tmp);

            RegCloseKey(ParametersKey);
        }
        else {
            RegCloseKey(XamppKey);
            DeleteServiceValueNT();
            return SYSERR;              // Indicate that something is wrong
        }
        RegCloseKey(XamppKey);
    }
    return Status;
}

int GetInstallDirectory(int len, char *path)
{

    HKEY    XamppKey;

    LONG    Status;

    DWORD   RegType;

    path[0] = 0;

    Status = RegOpenKeyEx(HKEY_LOCAL_MACHINE,
                          TEXT("Software\\xampp"),
                          0,
                          KEY_READ,
                          &XamppKey);

    if (Status == ERROR_SUCCESS) {

        RegQueryValueEx(XamppKey,
                        TEXT("Install_Dir"),
                        NULL,
                        &RegType,
                        (LPBYTE) path,
                        (LPDWORD)&len);

        RegCloseKey(XamppKey);
    }
    return Status;
}

BOOL APIENTRY AboutDlgProc (HWND hDlg, UINT msg, WPARAM wParam, LPARAM lParam)
{
    int wmID;

    switch (msg) {

        case WM_INITDIALOG:
            hAbout = hDlg;
            return TRUE;

        case WM_DESTROY:
            break;

        case WM_COMMAND:

            wmID = LOWORD(wParam);

            switch (wmID) {

                case IDOK:
                case IDCANCEL:

                    hAbout = 0;
                    EndDialog(hDlg, IDOK);
                    return TRUE;

            }
            break;
    }
    return FALSE;
}

BOOL GetProcessDetails(DWORD dwPID)
{
	HANDLE hModuleSnap = INVALID_HANDLE_VALUE;
	MODULEENTRY32 me32;

	// Take a snapshot of all modules in the specified process.

	hModuleSnap = CreateToolhelp32Snapshot(TH32CS_SNAPMODULE, dwPID );

	if (hModuleSnap == INVALID_HANDLE_VALUE) {
//		dprintf("No module snapshot for PID %d", dwPID);
		return FALSE;
	}
  

	// Set the size of the structure before using it.

	me32.dwSize = sizeof(MODULEENTRY32);

	// Retrieve information about the first module

	if (!Module32First(hModuleSnap, &me32)) {
	    CloseHandle(hModuleSnap);
		return FALSE;
	}

	// Now save details of the first module of the process

    if (dwPID) {
		proc_tab[proc_index].pid = dwPID;
		strcpy(proc_tab[proc_index].module, me32.szModule);
		strcpy(proc_tab[proc_index].path, me32.szExePath);
		//		dprintf("%d %d %s %s", proc_index, dwPID, me32.szModule, me32.szExePath);
		proc_index++;
	}
	CloseHandle(hModuleSnap);
	return TRUE;
}

BOOL GetProcessList()
{
	HANDLE hProcessSnap;
	PROCESSENTRY32 pe32;

	proc_index = 0;

	// Take a snapshot of all user processes in the system.

	hProcessSnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);

	if (hProcessSnap == INVALID_HANDLE_VALUE)
	    return FALSE;

	// Set the size of the structure before using it.
	
	pe32.dwSize = sizeof(PROCESSENTRY32);

	// Retrieve information about the first process

	if (!Process32First(hProcessSnap, &pe32)) {
		CloseHandle(hProcessSnap);
		return FALSE;
	}

	// Now walk the snapshot of processes, and
	// display information about each process in turn

	do {
		GetProcessDetails(pe32.th32ProcessID);
	} while (Process32Next(hProcessSnap, &pe32));

	CloseHandle(hProcessSnap);
	return TRUE;
}

/*-----------------------------------------------------------------------------
 *
 * This code comes from http://www.codeproject.com/string/stringsearch.asp
 * and contains the Todd Smith modifications.
 *
 *------------------------------------------------------------------------------
*/


// If this code works, it was written by Ralph Walden. If it doesn't work, I don't know who wrote it.

#pragma warning(disable : 4035) // no return value
 
char* __fastcall stristrA(const char* pszMain, const char* pszSub)
{
//    pszMain;    // compiler thinks these are unreferenced because
//    pszSub;     // they are in ecx and edx registers

    char* pszTmp1;
    char* pszTmp2;
    char  lowerch, upperch;

// We keep the first character of pszSub in lowerch and upperch (lower and
// upper case). First we loop trying to find a match for this character. Once
// we have found a match, we start with the second character of both pszMain
// and pszSub and walk through both strings doing a CharLower on both
// characters before comparing. If we make it all the way through pszSub with
// matches, then we bail with a pointer to the string's location in pszMain.

    _asm {
 //       mov esi, ecx    // pszMain
 //       mov edi, edx    // pszSub
        mov esi, pszMain    // pszMain
        mov edi, pszSub    // pszSub

        // Check for NULL pointers

        test esi, esi
        je short NoMatch // NULL pointer for pszMain
        test edi, edi
        je short NoMatch // NULL pointer for pszSub

        sub eax, eax
        mov al, [edi]
        push eax
        call DWORD PTR CharLower
        mov lowerch, al
        push eax
        call DWORD PTR CharUpper
        mov upperch, al

        push edi    // increment the second string pointer
        call DWORD PTR CharNext
        mov  edi, eax

        mov pszTmp2, edi
        mov edi, DWORD PTR CharNext // faster to call through a register

Loop1:
        mov al, [esi]
        test al, al
        je short NoMatch        // end of main string, so no match
        cmp al, lowerch
        je short CheckString    // lowercase match?
        cmp al, upperch
        je short CheckString    // upppercase match?
        push esi
        call edi                // Call CharNext to update main string pointer
        mov esi, eax
        jmp short Loop1

CheckString:
        mov pszTmp1, esi    // save current pszMain pointer in case its a match
        push esi
        call edi            // first character of both strings match,
        mov  esi, eax       // so move to next pszMain character
        mov edi, pszTmp2
        mov al, [edi]
        jmp short Branch1

Loop3:
        push esi
        call DWORD PTR CharNext    // CharNext to change pszMain pointer
        mov  esi, eax
        push edi
        call DWORD PTR CharNext    // CharNext to change pszSub pointer
        mov  edi, eax

        mov al, [edi]
Branch1:
        test al, al
        je short Match       // zero in sub string, means we've got a match
        cmp al, [esi]
        je short Loop3

        // Doesn't match, but might be simply a case mismatch. Lower-case both
        // characters and compare again

        sub ecx, ecx
        mov cl, al  // character from pszSub
        push ecx
        call DWORD PTR CharLower
        mov cl, al
        sub eax, eax
        mov al,  [esi]   // character from pszMain
        push ecx    // preserve register
        push eax
        call DWORD PTR CharLower
        pop ecx
        cmp al, cl
        je short Loop3  // we still have a match, keep checking

        // No match, put everything back, update pszMain to the next character
        // and try again from the top

        mov esi, pszTmp1
        mov  edi, DWORD PTR CharNext
        push esi
        call edi
        mov  esi, eax
        jmp short Loop1

Match:
        mov eax, pszTmp1
        jmp short Done  // Don't just return -- always let the C portion of the code handle the return

NoMatch:
        sub eax, eax
Done:
     }

    // Note lack of return in the C portion of the code. Return value is always in
    // eax register which we have set by the time we get here
}

WCHAR* __fastcall stristrW(const WCHAR* pszMain, const WCHAR* pszSub)
{
//    pszMain;    // compiler thinks these are unreferenced
//    pszSub;

    WCHAR* pszTmp1;
    WCHAR* pszTmp2;
    WCHAR  lowerch, upperch;

// We keep the first character of pszSub in lowerch and upperch (lower and
// upper case). First we loop trying to find a match for this character. Once
// we have found a match, we start with the second character of both pszMain
// and pszSub and walk through both strings doing a CharLower on both
// characters before comparing. If we make it all the way through pszSub with
// matches, then we bail with a pointer to the strings location in pszMain.

    _asm {
//       mov esi, ecx    // pszMain
//       mov edi, edx    // pszSub
        mov esi, pszMain    // pszMain
        mov edi, pszSub    // pszSub

        // Check for NULL pointers

        test esi, esi
        je short NoMatch // NULL pointer for pszMain
        test edi, edi
        je short NoMatch // NULL pointer for pszSub

        sub eax, eax
        mov ax, [edi]
        push eax
        call DWORD PTR CharLowerW
        mov lowerch, ax
        push eax
        call DWORD PTR CharUpperW
        mov upperch, ax

        lea edi, [edi+2]

        mov pszTmp2, edi

Loop1:
        mov ax, [esi]
        test ax, ax
        je short NoMatch        // end of main string, so no match
        cmp ax, lowerch
        je short CheckString    // lowercase match?
        cmp ax, upperch
        je short CheckString    // upppercase match?
        lea esi, [esi+2]
        jmp short Loop1

CheckString:
        mov pszTmp1, esi    // save current pszMain pointer
        lea esi, [esi+2]
        mov edi, pszTmp2
        mov ax, [edi]
        jmp short Branch1

Loop3:
        lea esi, [esi+2]
        lea edi, [edi+2]

        mov ax, [edi]
Branch1:
        test ax, ax
        je short Match       // zero in main string, means we've got a match
        cmp ax, [esi]
        je short Loop3

        // Doesn't match, but might be simply a case mismatch. Lower-case both
        // characters and compare again

        sub ecx, ecx
        mov cx, ax  // character from pszSub
        push ecx
        call DWORD PTR CharLowerW
        mov cx, ax
        sub eax, eax
        mov ax, [esi]   // character from pszMain
        push ecx        // preserve register
        push eax
        call DWORD PTR CharLowerW
        pop ecx
        cmp ax, cx
        je short Loop3  // we still have a match, keep checking

        // No match, put everything back, update pszMain to the next character
        // and try again from the top

        mov esi, pszTmp1
        lea esi, [esi+2]
        jmp short Loop1

Match:
        mov eax, pszTmp1
        jmp short Done

NoMatch:
        sub eax, eax
Done:
     }
    // Note lack of return in the C portion of the code. Return value is always in
    // eax register which we have set by the time we get here
}
