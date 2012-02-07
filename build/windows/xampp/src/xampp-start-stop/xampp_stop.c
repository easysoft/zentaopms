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

	printf("Stopping XAMPP...\n\n");

	xampp_cdx();

	pidfile="apache\\logs\\httpd.pid";
	fp=fopen(pidfile,"r");
	if(!fp)
	{
		printf("Can't find %s.\n", pidfile);
		Sleep(10000);
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
			Sleep(10000);
		}
	}

	pidfile="mysql\\data\\mysql.pid";
	fp=fopen(pidfile,"r");
	if(!fp)
	{
		printf("Can't find %s.\n", pidfile);
		Sleep(10000);
		return(1);
	}
	else
	{
		fscanf(fp,"%d", &pid);
		fclose(fp);

		sprintf_s(shutdownEventName, sizeof(shutdownEventName), "MySQLShutdown%d", pid);
		shutdownEvent = OpenEvent(EVENT_MODIFY_STATE, FALSE, shutdownEventName);
		if (shutdownEvent != NULL)  
		{
			SetEvent(shutdownEvent);  
		} 
		else
		{
			printf("Can't find MySQL process #%d.\n", pid);
			Sleep(10000);
			return(1);
		}
	}
	Sleep(10000);
	return(0);
}
