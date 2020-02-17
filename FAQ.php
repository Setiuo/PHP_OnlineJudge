<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<body>
	<?php require_once('Php/Page_Header.php') ?>
	<link rel="stylesheet" href="/highlight/styles/default.css">
	<script src="/highlight/highlight.pack.js"></script>
	<script>
		hljs.initHighlightingOnLoad("gcc", "g++", "C++", "Java", "Python");
	</script>

	<div class="container">

		<div class="panel panel-default animated fadeInLeft">
			<div class="panel-heading">Frequently Asked Questions</div>
			<div class="panel-body">
				<p>Q:可以使用哪种编程语言?<br />
					A:目前提供C(gcc)、C++(g++)、Java和Python3.7</p>

				<p>Q:如何进行读取和输出?<br />
					A:只能stdin/stdout(使用标准输入/输出),不允许读取和写入任何文件.在C/C++中可以使用scanf读取,printf输出.</p>

				<p>Q:罚时是什么？如何计算？<br />
					A:<br />当参赛选手AC题目数量相同时，就要按照总罚时进行排名；通过题的数量相同，罚时越小，排名越靠前.<br />
					每题罚时 = AC时间 - 比赛开始时间 + 提交但未通过次数 * 20.
				</p>


				<p>Q:各种状态的含义是什么?<br />
					A:<br /></p>
				<div class="panel panel-default">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>状态</th>
								<th>简称</th>
								<th>含义</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="label label-primary">WAITING</span></td>
								<td></td>
								<td>代码已经提交,等待分配评测机</td>
							</tr>
							<tr>
								<td><span class="label label-primary">PENDING</span></td>
								<td></td>
								<td>评测机已经接收评测任务,正在等待测评</td>
							</tr>
							<tr>
								<td><span class="label label-primary">COMPILING</span></td>
								<td></td>
								<td>代码正在编译</td>
							</tr>
							<tr>
								<td><span class="label label-primary">RUNNING</span></td>
								<td></td>
								<td>编译成功，正在进行测评</td>
							</tr>
							<tr>
								<td><span class="label label-success">CORRECT</span></td>
								<td>AC</td>
								<td>代码正确</td>
							</tr>
							<tr>
								<td><span class="label label-warning">COMPILER-ERROR</span></td>
								<td>CE</td>
								<td>编译错误.请检查代码在自己的机器上能否正常编译,以及是否选错了语言.在比赛中编译错误不会增加罚时</td>
							</tr>
							<tr>
								<td><span class="label label-danger">PRESENTATION-ERROR</span></td>
								<td>PE</td>
								<td>输出格式错误.一般来说结果是正确的,但是多输出或者少输出了空格，换行等符号</td>
							</tr>
							<tr>
								<td><span class="label label-danger">WRONG-ANSWER</span></td>
								<td>WA</td>
								<td>答案错误.你需要检查你的代码</td>
							</tr>
							<tr>
								<td><span class="label label-danger">RUN-ERROR</span></td>
								<td>RE或者RTE</td>
								<td>运行时错误.引发的原因有很多,包括但不仅限于使用未初始化的指针,数组越界,堆栈溢出,除数为零,返回值不为0等</td>
							</tr>
							<tr>
								<td><span class="label label-danger">TIMELIMIT</span></td>
								<td>TLE</td>
								<td>超出时间限制.可能是代码中包含死循环,也可能是算法不够优化</td>
							</tr>
							<tr>
								<td><span class="label label-danger">MEMORYLIMIT</span></td>
								<td>MLE</td>
								<td>内存超出限制.你使用的内存太多了,可能由于使用了过大的数组,或是忘记释放使用过的内存导致内存泄漏</td>
							</tr>
							<tr>
								<td><span class="label label-danger">OUTPUTLIMIT</span></td>
								<td>OLE</td>
								<td>你的输出超过标准答案的限制</td>
							</tr>
							<tr>
								<td><span class="label label-danger">SYSTEM-ERROR</span></td>
								<td>SE</td>
								<td>系统傲娇了</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br />
				<p>Q:各语言的编译选项是怎样的?<br />
					A:<br />
					C: gcc Code.c -o main -O -Wall -lm --static -std=c99 -DONLINE_JUDGE<br />
					C++: g++ Code.cpp -o main -O -Wall -lm --static -DONLINE_JUDGE<br />
					Java: javac -J-Xms32m -J-Xmx256m Main.java<br />
					Python3.7: python -m py_compile Code.py<br />
				</p>
				<div>
					Q:如何编写代码?<br />
					A:以<a href="/Question.php?Problem=1000">A+B Problem</a>为例:<br />
					C<br />
					<pre><code class="C">#include &lt;stdio.h&gt;
int main(void)
{
    int a,b;
    scanf("%d%d",&amp;a,&amp;b);
    printf("%d",a+b);
    return 0;
}
</code></pre><br />
					C++<br />
					<pre><code class="C++">#include &lt;iostream&gt;
using namespace std;
int main()
{
    int a,b;
    cin&gt;&gt;a&gt;&gt;b;
    cout&lt;&lt;a+b&lt;&lt;endl;
    return 0;
}
</code></pre><br />
					Java<br />
					<pre><code class="Java">import java.util.*;
import java.io.*;
public class Main
{
    public static void main(String[] args)
    {
        Scanner reader=new Scanner(System.in);
        int a,b;
        a=reader.nextInt();
        b=reader.nextInt();
        System.out.println(a+b);
    }
}
</code></pre><br />
					Python3.7<br />
					<pre><code class="Python">import sys
for line in sys.stdin:
 a = line.split() 
print (int(a[0]) + int (a[1]))
</code></pre><br />
				</div>
			</div>
		</div>

	</div>
	<?php
	$PageActive = '';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>