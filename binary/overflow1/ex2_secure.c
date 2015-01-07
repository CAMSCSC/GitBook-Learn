/*Example 2 (secure)*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
int main(int argc, char *argv[])
{
	if(argc != 2) //If no argument was given, exit.
	{
		exit(0);
	}
	
	char pass[16]; //Give the character array 'pass' a buffer of 16 bytes.
	int auth = 0; //Set the integer auth to 0.
	
	if(strlen(argv[1]) <= 16) //Improved.
	{
		strcpy(pass, argv[1]);
	}
	
	if(strcmp(pass, "passkey") == 0) //If pass is equal to "passkey", set auth to 1.
	{
		auth = 1;
	}
	else
	{
		printf("Wrong Password.\n");
		exit(0); //Improved.
	}
	
	if(auth == 1) //If auth is 1, give the secret.
	{
		printf("Secret!\n");
	}
	
	return 0;
}