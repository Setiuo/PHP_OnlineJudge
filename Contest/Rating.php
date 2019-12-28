
<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php");?>

  <body>
	<?php 
	require_once("Header.php");
	?>
	
	<div class="container">

<div class="panel panel-default">
	<div id="contesthead" class="panel-heading" style="padding:0 0 0 0;">
		<ul class="nav nav-tabs" role="tablist">
		
		<li><h4>&nbsp;</h4></li>
		</ul>
	</div>
    <div class="panel-body">

<div class="panel panel-default">
	<table class="table table-striped table-hover text-center">
	<thead>
	  <tr>
		<th>用户名</th>
		<th>比赛排名</th>
		<th>赛前战斗力</th>
		<th>战斗力增减</th>
		<th>赛后战斗力</th>
	  </tr>
	</thead>
	<tbody>
	
	  <tr data-rank="2">
		<td><a href="/User/星星"  class="myuser-base myuser-violet" >星星</a></td>
		<td>2</td>
		<td>1906.483915</td>
		<td class="SlateFixBlack rankyes">24.604204</td>
		<td>1931.088119</td>
      </tr>
      
	  <tr data-rank="10">
		<td><a href="/User/温思海"  class="myuser-base myuser-blue" >温思海</a></td>
		<td>8</td>
		<td>1693.690769</td>
		<td class="SlateFixBlack rankno">-48.570514</td>
		<td>1645.120254</td>
	  </tr>
	
	</tbody>
  </table>
</div>
</div></div>
	</div>
	<?php
    $PageActive = "#c_rating";
	require_once('Footer.php');
	?>
	
	
  </body>
</html>