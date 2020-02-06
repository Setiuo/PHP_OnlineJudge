# PHP_OnlineJudge

<a href="https://judge.setiuo.top">项目预览</a><br />
一个运行在 Windows 平台的源程序判题系统

<br/>
<h2>功能：</h2>
方便编辑管理员权限，可细分权限<br/>
支持SpecialJudge<br/>
支持部署多个评测机评测<br/>
基本的ACM/OI赛制<br/>
ACM赛制的封榜功能<br/>
战斗力功能：支持带Rating的比赛，算法参考Codeforces<br/>
用户名颜色根据战斗力划分<br/>
比赛代码相似度检测：支持字符串检测和Standford Moss检测<br/>

<h2>网站部署</h2>
网站使用PHP编写，可运行在7.2.10版本（其它版本也可以运行）<br />
服务器使用nginx或apache均可<br />
更改LoadData.php文件中的SQL_USER，SQL_PASSWORD，SQL_BASE，即MYSQL连接用户名，MYSQL连接密码，数据库名称<br />
验证码功能Captcha.php文件中字体文件路径是绝对路径，注意更改。<br />

<h2>MYSQL数据库导入</h2>
运行openjudge.sql即可初始化数据<br />
管理员账号为admin,密码为123456，请注意更改<br />

<h2>评测机部署</h2>
在Judger目录里新建data文件夹<br />
在Judger目录里新建log文件夹<br />
在Judger目录里新建temp文件夹<br />
更改config.ini文件中所有MYSQL连接对应数据<br />
一台机器可以运行多个评测机，但需要使用任务管理器绑定CPU。一般一个CPU绑定一个评测机<br />
远程服务器也可运行评测机，只要可以连接服务器MYSQL即可<br />
运行Daemon.exe即开启评测机。Daemon.exe用于检测评测运行状态并将运行状态写入数据库。<br />
！！强烈建议使用限制权限的用户运行评测机；命令行：runas /env /user:Judger C:\Judger\Daemon.exe<br />
！！命令行意思为使用Judger用户运行C:\Judger\Daemon.exe程序<br />
！！Judger文件夹内的文件及文件夹均需要读写权限<br />
！！temp文件夹需要删除权限<br />
！！spj文件夹只设置读权限即可<br />

注：<br />
评测机 API HOOK 安全模块参考并使用了https://gitee.com/ismallcode/online-judge 的 hook.dll
