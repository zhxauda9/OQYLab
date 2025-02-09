<?php
try {
    $db_name = 'mysql:host=127.0.0.1;dbname=course_db;charset=utf8';
    $user_name = 'root';
    $user_password = '';

    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully";

    function unique_id(){
        $str='';
        $rand=array();
        $length=strlen($str);
        for($i= 0;$i<$length;$i++){
            $n=mt_rand(0,$length);
            $rand[]=$str[$n];
        }
        return implode($rand);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
