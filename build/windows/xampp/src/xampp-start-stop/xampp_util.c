// Copyright (C) 2007-2010 Kai Seidler, oswald@apachefriends.org, GPL-licensed

#include <windows.h>
#include <stdio.h>
#include <process.h>
#include <string.h>

#include "xampp_util.h"

void xampp_call(char *start[10][10])
{
	int s;
	int i;
	char buffer[200];

	i=0;
	s=0;
	while(1)
	{
		if(start[i][0]==NULL)
			break;
		sprintf(buffer,"%s",start[i][0]);
		s=_spawnvp(P_NOWAIT,buffer, start[i]);
		if(s==-1)
		{
			printf("Error while calling %s...\n",buffer);
		}
		i++;
	}
	
	return;
}

void xampp_stop(char *pidfile,char *eventformat)
{
       	HANDLE shutdownEvent;
        char shutdownEventName[32];
	FILE *fp;
	long pid;

        fp=fopen(pidfile,"r");
        if(!fp)
        {
                printf("Can't find %s.\n", pidfile);
        }
        else
        {
                fscanf(fp,"%d", &pid);
                fclose(fp);

                sprintf_s(shutdownEventName, sizeof(shutdownEventName), eventformat, pid);
                shutdownEvent = OpenEvent(EVENT_MODIFY_STATE, FALSE, shutdownEventName);
                if (shutdownEvent != NULL)
                {
                        SetEvent(shutdownEvent);
                }
                else
                {
                        printf("Can't find process #%d.\n", pid);
                }
        }

}

void xampp_cdx()
{
	char path[1000];
	char *ptr;

	//path[ sizeof(path) -1] = 0;

	GetModuleFileName( NULL, path, sizeof( path ) -1 );
	ptr=strrchr(path,'\\');
	*ptr='\0';

	chdir(path);
	//printf("chdir(%s)\n", path);
}
