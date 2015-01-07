/*Example 2*/
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
	
	strcpy(pass, argv[1]); //Vulnerable. Copies the input argument into pass. *Strcpy does not check for memory bounds.
	
	if(strcmp(pass, "passkey") == 0) //If pass is equal to "passkey", set auth to 1.
	{
		auth = 1;
	}
	else
	{
		printf("Wrong Password.\n"); //Vulnerable. Prints "Wrong Password." *Application does not exit.
	}
	
	if(auth == 1) //If auth is 1, give the secret.
	{
		printf("Secret!\n");
	}
	
	return 0;
}