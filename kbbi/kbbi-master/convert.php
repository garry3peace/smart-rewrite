<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "smart_rewrite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insert($name, $type, $note)
{
	global $conn;
	$name= mysqli_real_escape_string($conn,$name);
	$type = mysqli_real_escape_string($conn,$type);
	$note = mysqli_real_escape_string($conn,$note);
	$sql = "INSERT INTO lemma (name, type, note) VALUES ('$name', '$type', '$note')";
	
	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

function split_word($string)
{
	if(str_word_count($string)==1){
		$item[1] = '';
		$item[2] = $string;
		return $item;
	}
	
	$new = explode(' ',$string, 2);
	$item[1] = trim($new[0],',');
	$item[2] = $new[1];
	return $item;
}


function isProperType($string)
{	
	$list = ['a','n','v','adj','->','pron','dr','p','Isl','num'];
	if(in_array($string, $list)){
		return true;
	}
	
	return false;
}

function stop_loop($data){
	if(isProperType($data)){
		return true;
	}
	
	if(empty($data)){
		return true;
	}
	
	return false;
}

function process($data)
{
	$item = explode(' ', $data, 3);
	$item[1] = trim($item[1],',');
	
	//simpan part terakhir untuk jaga-jaga jika butuh diproses lebih lanjut.
	$last = $item[2];
	
	//yang awal ada akhir koma, sudah pasti berikutnya gak benar.
	//hapus item[1] ganti dengan yang bagian last
	if(substr($item[0],-1)==','){
		$item[0] = trim($item[0],',');
		$newItem = split_word($last);
		$item = $newItem + $item;
	}
	
	
	do{
		
		if (!isProperType($item[1])){
			$newItem = split_word($last);
			$item = $newItem+$item;
			$last = $item[2];
		}
		
		if(stop_loop($item[1])){
			break;
		}
		
	}while(true);
	
	
	
	return $item;
}

//////////////////////////////////////////////////////////////////
ini_set('max_execution_time', 0);
die('DISTOP. Lwat code die() ini akan import data KBBI ke database');
for($i=66; $i<=90;$i++){
	$r = fopen('teks/'.chr($i).'.txt','r');

	$count = 0;
	while($data = fgets($r)){
		$row = process($data);
		insert($row[0], $row[1], $row[2]);

	//	$count++;	
	//	if($count==100){
	//		break;
	//	}
	}
}

$conn->close();
