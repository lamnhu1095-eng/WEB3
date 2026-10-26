<?php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

// Xu ly xoa khi click link
if (isset($_REQUEST['IDNV'])) {
    $idnv = $_REQUEST['IDNV'];
    mysqli_query($link, "DELETE FROM NHANVIEN WHERE IDNV='$idnv'");
}

$rs = mysqli_query($link, "SELECT * FROM NHANVIEN");

echo '<table border="1" width="100%">';
echo '<caption>Danh sach nhan vien</caption>';
echo '<TR><TH>IDNV</TH><TH>Ho ten</TH><TH>IDPB</TH><TH>Dia chi</TH><TH>Xoa</TH></TR>';

while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR>';
    echo '<TD>' . $row['IDNV'] . '</TD>';
    echo '<TD>' . $row['Hoten'] . '</TD>';
    echo '<TD>' . $row['IDPB'] . '</TD>';
    echo '<TD>' . $row['Diachi'] . '</TD>';
    echo '<TD align="center"><a href="xoa.php?IDNV=' . $row['IDNV'] . '" onclick="return confirm(\'Ban co chac muon xoa nhan vien nay?\');">Xoa</a></TD>';
    echo '</TR>';
}

echo '</TABLE>';
mysqli_free_result($rs);
mysqli_close($link);
?>