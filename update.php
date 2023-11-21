<!DOCTYPE html>
<html lang="ja">
    <head>
        <mrta charset="UTF-8">
        <title>m5-1</title>
    </head>
    <body>
       
<?PHP
$name="";
$text="";
$delete="";


//DB接続設定
$dsn='mysql:dbname=データベース名;host=localhost';
$user='ユーザ名';
$pass='パスワード';
$pdo=new PDO($dsn,$user,$pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


//テーブル作成
$sql="CREATE TABLE IF NOT EXISTS tbtest"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name CHAR(32),"
."comment TEXT,"
."pass TEXT,"
."time DATETIME"
.");";
$stmt=$pdo->query($sql);



//編集内容受け取り
if(!empty($_POST["hensyu"])&&!empty($_POST["number"])){
    $num=$_POST["number"];
    
    $sql = 'SELECT * FROM tbtest WHERE id=:id';
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':id', $num, PDO::PARAM_INT);
    $stm->execute();
    $resultt = $stm->fetchAll();
    foreach($resultt as $row){
        if($row['id']==$num){
           $numms=$row['id'];
           $names=$row['name'];
           $texts=$row['comment'];
        }
    }
}

    

//削除機能
if(!empty($_POST["delete"])&&!empty($_POST["pass"])&&!empty($_POST["sakujyo"])){
    $delete=$_POST["delete"];
    $delpass=$_POST["pass"];
    //レコード選択
    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $stmt= $pdo->prepare($sql);
    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
    $stmt->execute();//実行
    $resultde = $stmt->fetchAll();//実行したすべての結果
    foreach($resultde as $row){
        if($delpass==$row['pass']){//パスワードがあっているか
            $sql = 'delete from tbtest where id=:id ';
            $stmts = $pdo->prepare($sql);
            $stmts->bindParam(':id', $delete, PDO::PARAM_INT);
            $stmts->execute();//実行
        }
    }
}

?>
<div style="color:blue;">
<h1>お気持ちコメント</h1>
<p style="color:gray;font-size:15px;">
    今の気持ちを自由にコメントしてください。<br>
    パスワードの記入をお願いします。
</p>
</div>
 <form action="" method="post">
            <input type="text" name="name" placeholder="名前" value="<?php if(isset($names)){echo $names;}?>">
            <input type="text" name="text" placeholder="コメント" value="<?php if(isset($texts)){echo $texts;}?>">
            <input type="submit" name="submit">
            <input type="text" name="pass" placeholder="パスワード">
            <br>
            <br>
            <!--削除機能-->
            <input type="number" name="delete" placeholder="削除対象番号">
            <input type="submit" name="sakujyo" value="削除">
            
            <br>
            <br>
            <!-- 編集機能-->
            <input type="number" name="number" placeholder="編集対象番号">
            <input type="submit" name="hensyu" value="編集">
            <input type="hidden" name="hiden" value="<?php  if(isset($numms)){echo $numms;} ?>">
            
        </form>
<?PHP    

//新規投稿
if(!empty($_POST["name"])&&!empty($_POST["text"])&&!empty($_POST["pass"])){
    
    $name = $_POST['name'];
    $comment = $_POST['text'];
    $pass=$_POST["pass"];
    $time = date("Y-m-d H:i:s");
    //編集か新規投稿か
     if(empty($_POST["hiden"])){
        $sql = "INSERT INTO tbtest (name, comment, pass ,time) VALUES (:name, :comment, :pass, now())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute();
        
    }else{
    //編集機能
    $hiden=$_POST['hiden'];
    $name = $_POST['name'];
    $comment = $_POST['text'];
    $pass=$_POST["pass"];

    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $hensyu= $pdo->prepare($sql);
    $hensyu->bindParam(':id', $hiden, PDO::PARAM_INT);
    $hensyu->execute();//実行
    $resultch = $hensyu->fetchAll();
    
    
    foreach($resultch as $roww){
        if(isset($hiden)&&$hiden==$roww['id']&&$pass==$roww['pass']){
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment, time=:time WHERE id=:id ';
            $change = $pdo->prepare($sql);
            $change->bindParam(':id', $hiden, PDO::PARAM_STR);
            $change->bindParam(':name', $name, PDO::PARAM_STR);
            $change->bindParam(':comment', $comment, PDO::PARAM_STR);
            $change->bindParam(':time', $time, PDO::PARAM_STR);
            $change->execute();
            }
        }
    }

}

//データを表示
if(!empty($sql)){
    $sql = 'SELECT * FROM tbtest ORDER BY id';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
        foreach ($results as $row){
            print($row['id'].','.$row['name'].','.$row['comment'].','.$row['pass'].','.$row['time'].'<br>');
            echo "<hr>";
        }
}

?>
    </body>
</html>