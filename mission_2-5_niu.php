<!DOCTYPE html>
<html lang ="ja">
<head>
<meta http-equiv="content-type" charset="UTF-8" >
</head>

<?php
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$pass = $_POST['pass'];
	$time = date("Y/m/d H:i:s");
	$filename = 'mission_2-5_niu.text';
	$fp = fopen($filename,'a');

//入力データ書き込み
if(empty($_POST['editing']) and !empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['pass']))
 {
	$line = file($filename);	//テキストファイルの内容を配列($line)に読み込む
	$count = count($line);		//テキストファイルの列を数える。
	$number = $count + 1;		//配列が0から始まるので、1を加える。
	$data = $number."<>".$name."<>".$comment."<>".$time."<>".$pass;
  	fwrite($fp,"$data"."\n");	//データ($data)をファイルに書き込む。
  	fclose($fp);		
 }

//○行目削除
	$num = $_POST['delete'];
	$delete_pass = $_POST['delete_pass'];
	$stack = array();
if(ctype_digit($_POST['delete']) and ctype_digit($_POST['delete_pass']))	//削除対象番号が数字であるとき
{
 	$file = file($filename);	//テキストファイルの中身を配列に読み込む
	foreach($file as $value)		//配列($file)を一行ずつ変数($value)に入れ込む
 	{	$piece = explode("<>",$value);	//変数($value)を<>で分割して配列に入れる
  		if($piece[0] == $num)
		{	
			if((int)$piece[4] == (int)$delete_pass)
			{
				unset($value);
			}
			elseif((int)$piece[4] != (int)$delete_pass)
			{
				echo "パスワードが違います。";
				$i++;
				$another = $i."<>".$piece[1]."<>".$piece[2]."<>".$piece[3]."<>".$piece[4];
				array_push($stack, $another);
			}
		}else{	$i++;
			$another = $i."<>".$piece[1]."<>".$piece[2]."<>".$piece[3]."<>".$piece[4];
			array_push($stack, $another);}
	}
	file_put_contents($filename,$stack);
}

//編集番号の指定
	$edit_pass = $_POST['edit_pass'];
if(ctype_digit($_POST['editor']) and ctype_digit($_POST['edit_pass']))	//編集番号が数字で入力されているとき
{
	$edit_num = $_POST['editor'];
 	$works = file($filename);	//テキストファイルの中身を配列に読み込む
	foreach($works as $points)
	{	$point = explode("<>",$points);	//変数($points)を<>で分割して配列に入れる
  		if($point[0] == $edit_num and (int)$point[4] == (int)$edit_pass)
		{
			$ed_num = $point[0];
			$back_name = $point[1];
			$back_comment = $point[2];
		}
		elseif($point[0] == $edit_num and (int)$point[4] != (int)$edit_pass)
		{
			echo "パスワードが違います。";
		}
		

	}
}

//編集操作
	$edited = array();
if(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['editing']) and ctype_digit($_POST['editing']))
{	
 	$edit_list = file($filename);	//テキストファイルの中身を配列に読み込む
	foreach($edit_list as $edit_line)
 	{	$edit_piece = explode("<>",$edit_line);
		if($edit_piece[0] == $_POST['editing'])
		{	$edit_data = $edit_piece[0]."<>".$_POST['name']."<>".$_POST['comment']."<>".$time."<>".$edit_piece[4];
			array_push($edited, $edit_data);
		}else{
			array_push($edited, $edit_line);
		}
	}
	file_put_contents($filename,$edited);
}

?>

<body>
<form action = "mission_2-5_niu.php" method="post">
<input type="text" name="name"  value="<?php echo $back_name; ?>" placeholder="名前">
<br>
<input type="text" name="comment"  value="<?php echo $back_comment; ?>" placeholder="コメント">
<br>
<input type="password" name="pass" placeholder="パスワード">
<input type="hidden" name="editing"  value="<?php echo $edit_num; ?>">
<input type="submit"  value="送信" />
<br>
<br>
<input type="text" name="delete" placeholder="削除対象番号"><br>
<input type="password" name="delete_pass"  placeholder="パスワード">
<input type="submit"  value="削除" />
<br>
<br>
<input type="text" name="editor" placeholder="編集対象番号"><br>
<input type="password" name="edit_pass"  placeholder="パスワード">
<input type="submit"  value="編集" />
<br>
</form>
</body>
</html>

<?php
//ファイル内容の表示
	$lines = file($filename);	//$filenameの中身を配列に読み込む
if(!empty($lines))	//$linesが空でないとき
{	foreach ($lines as $key)	//$linesを一行ずつ$keyに代入
		{	$pieces = explode("<>",$key);	//一行($key)の内容を<>で区切る
     				for($i = 0 ; $i <count($pieces)-1 ; $i++)
					{	echo $pieces[$i]." ";}
			echo "<br>";
		}
}

?>
