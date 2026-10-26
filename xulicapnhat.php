<?php
$myID = $_REQUEST['IDPB'];
$Tenpb = $_REQUEST['txtTenpb'];
$Mota = $_REQUEST['txtMota'];

$bienketnoi = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());

// Chon CSDL de lam viec
mysqli_select_db($bienketnoi, 'DULIEU');

$rs = mysqli_query($bienketnoi, "UPDATE PhongBan SET Tenpb='$Tenpb', Mota='$Mota' WHERE IDPB='$myID'");

// Chuyen ve trang hien thi danh sach phong ban
header("Location:capnhat.php");
?>