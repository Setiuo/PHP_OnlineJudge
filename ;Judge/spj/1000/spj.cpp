#include <stdio.h>
#include <iostream>
#include <string>
#include <fstream>
#include <string.h>

using namespace std;

enum ProgramState
{
	WATING = 0,
	PENDING,
	COMPILING,
	RUNNING,
	CORRECT,
	PRESENTATION_ERROR,
	TIMELIMIT,
	MEMORYLIMIT,
	WRONG_ANSWER,
	RUN_ERROR,
	OUTPUTLIMIT,
	COMPILE_ERROR,
	SYSTEM_ERROR
};

int main(int argc, char **argv)
{
	int status = CORRECT;
	//输入数据
	FILE *f_in = fopen(argv[1], "r");
	//输出数据
	FILE *f_out = fopen(argv[2], "r");
	//用户输出数据
	FILE *f_user_out = fopen(argv[3], "r");

    double a, b;
    fscanf(f_in, "%lf%lf", &a, &b);

    double ans = a + b;

    double out_ans;
    fscanf(f_user_out, "%lf", &out_ans);

    if(ans == out_ans)
    {
        status = CORRECT;
    }
    else
    {
        status = WRONG_ANSWER;
    }

    return status;
}
