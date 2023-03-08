<?php
#

// ファイルの先頭に文字列を追加する関数を作る
function f_add_first_row($str, $file_name) {

    // 事前にファイルの内容を取得
    $contents = file_get_contents($file_name);

    // 文字列を先頭に追加
    $contents = $str . "\n" . $contents;

    // 上書き 書き込み 
    $re = file_put_contents($file_name, $contents);

}

//ボタンクリック後の処理
if (isset($_POST['send']) === true and $_POST['message']) {
    $name = $_POST["name"];
    $message = $_POST["message"];

    //htmlエスケープ
    $name=htmlspecialchars($name,ENT_QUOTES | ENT_HTML5,$charset,false);
    $message=htmlspecialchars($message,ENT_QUOTES | ENT_HTML5,$charset,false);
    
    /*
    必要ならばipアドレスやホスト名も記録する
    $ipadress=$_SERVER['REMOTE_ADDR'];
    $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    */

    /*
    必要ならば、管理人などに成りすませないようにする
    $name=str_replace('管理人の名前' , '管理人ではない文字列' , $name);
    $name=str_replace('秘密の文字列' , '管理人の名前' , $name);
    */

    /*
    改行しても表示が崩れないようにする
    エスケープ処理（htmlspecialchars関数）の後に記述することで、必要なカッコを残せる。
    preg_replace関数で、改行を<br>に変換
    */
    $name=str_replace(array("\r\n","\n","\r"),"<br>",$name);
    $message=str_replace(array("\r\n","\n","\r"),"<br>",$message);

    $str =$name . "<pause>" . $message/*."<pause>".$ipadress."<pause>".$host_name*/;#必要ならばコメントアウトを外すこと。
    $file_name = "bord.txt";
    
    //処理した文字列を書き込む  
    
    f_add_first_row($str, $file_name);
}
 
    $fp = fopen("bord.txt", "r");//読み込み専用でbord.txtを開く変数を$fpとした
 
    $bord_array = [];//配列$bord_arrayを作る。初期値は空

while ($line = fgets($fp)) {
    $temp = explode("<pause>", $line);

    $temp_array = [
        "name" => $temp[0],//$tempの数値キー[0]にnameを対応させている
        "message" => $temp[1],//数値キー[1]にmessageを対応させている
        /*
        これはipアドレスおよびホスト名に関して
        "ipadress" => $temp[2],
        "hostname" => $temp[3]
        */
    ];
    $bord_array[] = $temp_array;//配列$bord_array[]に連想配列$temp_arrayを代入。
}


?>

<script>
    
</script>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>PHP簡易掲示板</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<form action="" method="post" class="go"
    style="
     width:auto;
    background-color:#dbe0e4;
    box-shadow: 0 0 10px;
    position:sticky;
    top:0;
    padding:0 2rem;
    margin-bottom:1rem;" ><!--ポストデータを送る-->

    <div>
        <label for="name"><p>名前</p></label>
        <input type="text" name="name" style="height:1.5rem;">
    </div>

    <div>
        <label for="message"><p>内容</p></label>
        <textarea rows="3" cols="70" type="text" name="message" style="max-width:100%;"></textarea>
    </div>

    <input style="margin-top:5px; height:3rem;" type="submit" name="send" value="送信する/内容が空ならリロード">

</form>
<p style="max-width:90%;line-height: 1.5em;">
PHPの簡易掲示板
</p>

<h2>表示欄</h2>
<div class="box">
<div class="container">
<ul style="max-width:90%;line-height: 1.5em;">
    <?php 
    foreach ($bord_array as $data): 
    ?>
        <?= "<li>".$data["name"] . ":" . $data["message"]."</li>"; //名前：メッセージという形で書き込む?>
    <?php endforeach; //endforeachはループでHTMLを生成しているとき、始まりと終わりのステートメントをよりはっきりさせるためにある。?>
</ul>
</div>
</div>

<style>
    *{
    margin: 0;
    padding: 0;
    }
    p{
    font-size: 16px;
    line-height: 2em;
    }
    input{
    font-size: 16px;
    line-height: 2em;
    }
    h1{
    font-size: 30px;
}
h2{
    font-size: 25px;
}
li{
    font-size: 16px;
    line-height: 2em;
}
textarea{
    max-width:auto;
}
.go{
    width:auto;
}

    /*box関係*/
.box{
    margin:30px auto;
    height:auto;
    background-color: #dbe0e4;
    box-shadow: 1px 1px 4px #d2d4d6;
}
.container{
    max-width: 980px;
    padding: 0 30px;
    margin-left: auto;
    margin-right: auto;
}
body, input, textarea, select { font-size: 16px; }
</style>
</body>
</html>