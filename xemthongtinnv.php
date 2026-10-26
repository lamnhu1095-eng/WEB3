<?php
// xemthongtinnv.php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

$rs = mysqli_query($link, "SELECT * FROM NHANVIEN");

echo '<table border="1" width="100%">';
echo '<caption>Du lieu bang Nhan vien</caption>';
echo '<TR><TH>IDNV</TH><TH>Ho ten</TH><TH>IDPB</TH><TH>Dia chi</TH></TR>';

while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR><TD>' . $row['IDNV'] . '</TD><TD>' . $row['Hoten'] . '</TD><TD>' . $row['IDPB'] . '</TD><TD>' . $row['Diachi'] . '</TD></TR>';
}

echo '</TABLE>';
mysqli_free_result($rs);
mysqli_close($link);
?>
