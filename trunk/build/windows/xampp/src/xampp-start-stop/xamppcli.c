// Copyright (C) 2007-2010 Kai Seidler, oswald@apachefriends.org, GPL-licensed

#include <stdio.h>
#include <windows.h>
#include <stdlib.h>

#include "xampp_util.h"

int main(int argc, char **argv)
{
	long pid;
	HANDLE shutdownEvent;
	char shutdownEventName[32]; 
	FILE *fp;
	char *pidfile;
	char *startapache[10][10] = { {"httpd.exe", "-DPHP5", NULL}, {NULL} };
	char *startmysql[10][10] = { {"mysqld.exe", "--standalone",  NULL}, {NULL} };

	if(argc!=2)
	{
		printf("Usage: %s <command>\n",argv[0]);
		return 1;
	}

	xampp_cdx();
	chdir("..");

	if(!strcmp(argv[1],"stopapache"))
	{
		xampp_stop("logs\\httpd.pid","ap%d_shutdown");
	}
	else if(!strcmp(argv[1],"startapache"))
	{
		xampp_call(startapache);
	}
	else if(!strcmp(argv[1],"startmysql"))
	{
		xampp_call(startmysql);
	}
	else if(!strcmp(argv[1],"stopmysql"))
	{
		xampp_stop("var\\mysql\\mysql.pid","MySQLShutdown%d");
	}
	else
	{
		printf("Unknown command %s\n",argv[1]);
		return 2;
	}
	return 0;

	printf("Stopping XAMPP...\n");

	Sleep(1000);

	xampp_cdx();

	pidfile="logs\\httpd.pid";
	fp=fopen(pidfile,"r");
	if(!fp)
	{
		printf("Can't find %s.\n", pidfile);
	}
	else
	{
		fscanf(fp,"%d", &pid);
		fclose(fp);

		sprintf_s(shutdownEventName, sizeof(shutdownEventName), "ap%d_shutdown", pid);
		shutdownEvent = OpenEvent(EVENT_MODIFY_STATE, FALSE, shutdownEventName);
		if (shutdownEvent != NULL)  
		{
			SetEvent(shutdownEvent);  
		} 
		else
		{
			printf("Can't find Apache process #%d.\n", pid);
			return(1);
		}
	}

	pidfile="var\\mysql\\mysql.pid";
	fp=fopen(pidfile,"r");
	if(!fp)
	{
		printf("Can't find %s.\n", pidfile);
		return(1);
	}
	else
	{
		fscanf(fp,"%d", &pid);
		fclose(fp);

	pidfile="var\\mysql\\mysql.pid";
		sprintf_s(shutdownEventName, sizeof(shutdownEventName), "MySQLShutdown%d", pid);
		shutdownEvent = OpenEvent(EVENT_MODIFY_STATE, FALSE, shutdownEventName);
		if (shutdownEvent != NULL)  
		{
			SetEvent(shutdownEvent);  
		} 
		else
		{
			printf("Can't find MySQL process #%d.\n", pid);
			return(1);
		}
	}
	return(0);
}
