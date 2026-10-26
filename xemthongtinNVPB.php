<?php
// Lay ma phong ban IDPB tu trang xemthongtinpb.php
$mapb = $_REQUEST['IDPB'];

$bienketnoi = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());

// Chon CSDL de lam viec
mysqli_select_db($bienketnoi, 'DULIEU');

if ($mapb == "") {
    $rs = mysqli_query($bienketnoi, "SELECT * FROM NHANVIEN");
} else {
    $rs = mysqli_query($bienketnoi, "SELECT * FROM NHANVIEN WHERE IDPB='$mapb'");
}

echo '<table border="1" width="100%">';
echo '<caption>Du lieu bang Nhan vien</caption>';
// Tieu de cua bang chua du lieu khi hien thi tren web
echo '<TR><TH>IDNV</TH><TH>Ho ten</TH><TH>IDPB</TH><TH>Dia chi</TH></TR>';

// Hien thi tung hang
while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR><TD>' . $row['IDNV'] . '</TD><TD>' . $row['Hoten'] . '</TD><TD>' . $row['IDPB'] . '</TD><TD>' . $row['Diachi'] . '</TD></TR>';
}

echo '</TABLE>';
mysqli_free_result($rs);
mysqli_close($bienketnoi);
?>