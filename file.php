<?php
$dir="FileBox/";// 文件目录
$NowPage=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf8">
  <link rel="stylesheet" href="bootstrap.min.css" type="text/css">
  <title>生蚝文件档案馆 / FileBox</title>
  <style>
  th{font-weight:bolder;text-align:center;}
  .tips{font-size:22;font-weight:bolder;}
  </style>
</head>
<body style="font-family:Microsoft YaHei; background-color:#f9f9f9">

<br>

<center>
<a class="btn btn-primary" style="width:98%" href="<?php echo $NowPage; ?>">刷 新 / Refresh</a>
</center>

<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>文件名</th>
  <th>删除</th>
</tr>

<?php
// 文件显示
if($dh=opendir($dir)){
  while(($file=readdir($dh))==true){
    // 文件名的全路径
    $FilePath=$dir.$file;
    if(substr($FilePath,-1)!="."){
    	if(is_dir($FilePath)==true){
    		$isDir=1;
    	}else{
    		$isDir=0;
    	}
?>
<tr>
	<td>
		<?php if($isDir==1){ ?>
		<a href="<?php echo $FilePath; ?>" style='font-size:16;font-weight:bolder;color:red;'>/<?php echo $file; ?>/</a>
		<?php }else{ ?>
		<a href="<?php echo $FilePath; ?>" style='font-size:16;font-weight:bolder;'><?php echo $file; ?></a>
		<?php } ?>
	</td>
	<td>
		<?php if($isDir==0){ ?>
		<a class='btn btn-danger' href="<?php echo $NowPage; ?>?del=<?php echo $FilePath; ?>">删除 / Delete</a>
		<?php } ?>
	</td>
</tr>

<?php
    }
  }
  closedir($dh);
}
?>
</table>

<?php
// 上传处理
if(isset($_POST) && $_POST){
  foreach($_FILES["file"]["error"] as $key => $error){
    if($error == UPLOAD_ERR_OK){
      $name=$_FILES["file"]["name"][$key];
      $tmp_name=$_FILES["file"]["tmp_name"][$key];
      if(file_exists($dir.$name)){
        echo "<center><font color='red' class='tips'>".$name." 已经存在</font></center>";
      }else{
        move_uploaded_file($tmp_name,$dir.$name);
        echo "<center>";
        echo "<font color='green' class='tips'>成功上传文件：</font>";
        echo "<font color='orange' class='tips'>".$dir.$name."</font>";
        echo "<br>";
        echo "<font color='blue' class='tips'>文件大小：".($_FILES["file"]["size"][$key]/1024)." KB</font>";
        echo "</center>";
      }
    }elseif($_FILES["file"]["error"][$key]!="4"){
      echo "<font color='red' class='tips'>Error Code： ".$_FILES["file"]["error"][$key]."</font><br>";
    }
  }
}

if(isset($_GET['del']) && $_GET['del']){
  $DelFile=$_GET['del'];
  if(!@unlink($DelFile)){
    echo "<script>alert('删除 ".$DelFile." 失败！');window.location.href='".$NowPage."';</script>";
  }else{
    echo "<script>alert('成功删除 ".$DelFile." ！');window.location.href='".$NowPage."';</script>";
  }
}
?>

<hr>

<form method="post" enctype="multipart/form-data">
<input type="file" name="file[]" style="height:28">
<input type="file" name="file[]" style="height:28">
<input type="file" name="file[]" style="height:28">
<input type="file" name="file[]" style="height:28">
<input type="file" name="file[]" style="height:28">

<center>
  <input class="btn btn-success" style="width:98%" type="submit" name="upload" value="上 传 文 件">
</center>

</form>
</body>
</html>