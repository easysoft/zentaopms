// xampp.h - resource header for xampp.rc

#define XAMPP_VERSION "XAMPP Control Panel Version 2.5 (USB lite version 2011)"

#define IDC_HELPX        42
#define IDC_EXPLORE      43
#define IDC_XREFRESH     44
#define IDC_EDIT9        45
#define IDC_CHECKBOX0    46         // Begin sequence
#define IDC_CHECKBOX1    47         //
#define IDC_CHECKBOX2    48         //
#define IDC_CHECKBOX3    49         // End sequence
#define IDC_XAMPP        50
#define IDC_DEBUG        51
#define IDC_MYSQL        52
#define IDC_FILEZILLA    53
#define IDC_MERCURY      54
#define IDC_SERVICE      55
#define IDC_PUSHBUTTON1	56
#define IDC_GROUPBOX1	101

#define IDC_EDIT0        61
#define IDC_EDIT1        62
#define IDC_EDIT2        63
#define IDC_EDIT3        64
#define IDC_EDIT4        65
#define IDC_EDIT5        66
#define IDC_EDIT6        67
#define IDC_EDIT7        68
#define IDC_ACTION0     161
#define IDC_ACTION1     162
#define IDC_ACTION2     163
#define IDC_ACTION3     164
#define IDC_ACTION4     165
#define IDC_ACTION5     166
#define IDC_ACTION6     167
#define IDC_ACTION7     168
#define IDC_LISTBOX1    180

#define IDC_README       41
#define IDC_LISTBOX2     43
#define IDC_EDIT10       44
#define IDC_SUPPORT	42

#define MYWM_NOTIFYICON 200

// Service Constants (from generic.h)

#define IDC_PASSWORD      16

#define IDC_EDIT83        12
#define IDC_EDIT84        13
#define IDC_EDIT30        14
#define IDC_CHECKBOX9     17
#define IDC_CHECKBOX30    10
#define IDC_PUSHBUTTON41  11
#define IDC_RADIOBUTTON7  18
#define IDC_RADIOBUTTON8  15
#define IDC_CHECKBOX4     19        // Begin sequence
#define IDC_CHECKBOX5     20
#define IDC_CHECKBOX6     21
#define IDC_CHECKBOX7     22        // End sequence

#define IDC_GROUPBOX38   102
#define IDC_GROUPBOX39   103


#define OK 1
#define SYSERR -1

#define NJOBS 4     // Number of entries in our Job Table

struct job_entry {
    char name[64];  // Module name  (apache | mysql | ftp | mercury)
	char port[64];	// Module port
    int  op;        // Op Code      (0 == stop,    1 == start, SYSERR)
    int  state;     // Module state (0 == stopped, 1 == running, SYSERR)
    int  start;     // XAMPP service should start this module
    int  service;   // Service      (0 = no, 1 = yes, SYSERR)
    HANDLE hThread; // Job Thread Handle
    DWORD  dwTID;   // Job Thread ID
	DWORD  dwPID;	// Job Process ID
    HANDLE hSem;    // Semaphore Handle
    char cmd[4096]; // Command string to execute
};

// Function prototypes

int start();
int work(int, char **);
int GetInstallDirectory(int, char *);
int UpdateStatus(void *);
int EnterDialogBox();
int GetModuleCode(char *);
int GetActionCode(char *);
int do_job(int, int);
int refresh();
int exec(char *, short);
int execw(char *, int, DWORD *, void *, DWORD);
int execs(char *);
BOOL GetProcessList( );
void Worker(struct job_entry *pjob);
int GetServiceValues(char *, char *, int *);
int GetServiceValue95(char *value);
int GetServiceValuesNT(char *, char *, int *);
int find(char *);
int EnterDialogBoxService();
int InitServiceCmd(char *value);
int EnableButtons();
int DeleteServiceValue();
int AddServiceValue(char *value, int type);
int AddServiceValue95(char *value);
int DeleteServiceValue95();
int DeleteServiceValueNT();
int AddServiceValueNT(char *value, int type);
int BottomRightWindow(hDlg);
BOOL APIENTRY MyDlgProc (HWND hDlg, UINT msg, WPARAM wParam, LPARAM lParam);

int printf(char *fmt, ...);
int sprintf(char *buf, char *fmt, ...);
int dprintf(char *fmt, ...);
int kprintf(char *fmt, ...);
int lbprintf(HWND hList, char *fmt, ...);
int vsprintf(char *, char *, va_list);

BOOL CenterWindow(HWND hWin);

void Thread0(char *param);
void Thread1(char *param);
void Thread2(char *param);
void Thread3(char *param);

