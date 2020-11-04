<?php
//Noticeのエラーを非表示にする
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
//DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル mission4-2
     $sql = "CREATE TABLE IF NOT EXISTS tbtest"
     ."("
     ."id INT AUTO_INCREMENT PRIMARY KEY ,"
     ."name char(32),"
		 ."comment TEXT,"
		 ."date TIMESTAMP,"
		 ."pass INT(3)"
     .");";
	$stmt = $pdo->query($sql);

	//mission4-3

/*	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
*/
//mission4-4
	/*$sql ='SHOW CREATE TABLE tbtest';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";
*/


//追加
	if(!empty($_POST["name"]) && !empty($_POST["message"]) && empty($_POST["sign"]) && !empty($_POST["pass_n"])){

			$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);


			$name = $_POST["name"];
			$comment = $_POST["message"]; //好きな名前、好きな言葉は自分で決めること
			$date = date("Y/m/d H:i:s");
			$pass = $_POST["pass_n"];
			$sql -> execute();
	}elseif(!empty($_POST["name"]) || !empty($_POST["message"]) || !empty($_POST["sign"]) || !empty($_POST["pass_n"])){
		echo "記入漏れがあります.<br>";
	}

	//パスワード変数 編集
if(!empty($_POST["pass_e"])){

	$id = $_POST["edit"]; // idがこの値のデータだけを抽出したい、とする

$sql = 'SELECT * FROM tbtest WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll(); 

		foreach ($results as $row){
			//$rowの中にはテーブルのカラム名が入る
			$ep = $row['pass'];
		}
}
//編集機能
	if(!empty($_POST["name"]) && !empty($_POST["message"]) && !empty($_POST["sign"]) && !empty($_POST["pass_n"])){
				$id = $_POST["sign"]; //変更する投稿番号
				$name = $_POST["name"];
				$comment = $_POST["message"]; //変更したい名前、変更したいコメントは自分で決めること
				$date = date("Y/m/d H:i:s");

				$sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':date', $date, PDO::PARAM_STR);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
		}

		//パスワード変数 削除
		if(!empty($_POST["pass_d"])){

			$id = $_POST["delete"]; // idがこの値のデータだけを抽出したい、とする
	
	$sql = 'SELECT * FROM tbtest WHERE id=:id ';
	$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
	$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
	$stmt->execute();                             // ←SQLを実行する。
	$results = $stmt->fetchAll(); 
		foreach ($results as $row){
			//$rowの中にはテーブルのカラム名が入る
	
			$dp = $row['pass'];
		}
	
		}

	//削除機能
	if (!empty($_POST["delete"]) && !empty($_POST["pass_d"])){

			if($dp === $_POST["pass_d"]){
			$id = $_POST["delete"];
			$sql = 'delete from tbtest where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
				}else{
					echo "パスワードが間違っています.<br>";
				}
	}elseif(!empty($_POST["delete"]) || !empty($_POST["pass_d"])){
		echo "記入漏れがあります.<br>";
	}

//フォームに表示させる
if(!empty($_POST["edit"])){
	//パスワードの条件分岐
 if($ep === $_POST["pass_e"]){

 $id = $_POST["edit"]; // idがこの値のデータだけを抽出したい、とする

$sql = 'SELECT * FROM tbtest WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll(); 
	 foreach ($results as $row){
		 //$rowの中にはテーブルのカラム名が入る
		 $n = $row['name'];
		 $c = $row['comment'];
		 $p = $row['pass'];
	 }
}else{
 echo "パスワードが間違っています.<br>";
}
}

//表示
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].',';
echo $row['pass'].',<br>';
echo "<hr>";
}
		//【！この SQLは tbtest テーブルを削除します！】
	  /*$sql = 'DROP TABLE tbtest';
		$stmt = $pdo->query($sql);
		*/
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

<h3>提示版</h3>
    <form action = "" method = "post">
       <input type="text" name="name" placeholder="名前"
       value="<?php if($ep === $_POST["pass_e"] && isset($_POST['edit'])){echo $n;}?>"><br> 
       
       <input type="text" name="message" placeholder="コメント" 
       value="<?php if($ep === $_POST["pass_e"] && isset($_POST['edit'])){echo $c;} ?>"><br> 
       
       <input type="text" name="pass_n" placeholder="数字のパスワード"
			 value="<?php if($ep === $_POST["pass_e"] && isset($_POST['edit'])){echo $p;} ?>">

       <input type="hidden" name="sign" value="<?php if($ep === $_POST["pass_e"] && !empty($_POST['edit'])){echo $_POST['edit'];}?>">
        <input type="submit" value="送信"><br>
        
        <p></p>
    </form>
    
    <form action = "" method = "post">
       <input type="number" name="delete" placeholder="削除対象番号"><br>
       <input type="text" name="pass_d" placeholder="数字のパスワード">
       <input type="submit" value="削除">
        
         <p></p>
    </form>
    
    <form action = "" method = "post">
         <input type="number" name="edit" placeholder="編集対象番号"><br>
         <input type="text" name="pass_e" placeholder="数字のパスワード">
         <input type="submit"  valuer="編集">
    </form>
   
</body>
</html>
