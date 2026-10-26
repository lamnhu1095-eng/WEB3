<?php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

// Xu ly xoa cac muc duoc chon
if (isset($_REQUEST['btnDelete'])) {
    $rs_all = mysqli_query($link, "SELECT IDNV FROM NHANVIEN");
    while ($r = mysqli_fetch_array($rs_all, MYSQLI_BOTH)) {
        $id = $r['IDNV'];
        if (isset($_REQUEST[$id])) {
            mysqli_query($link, "DELETE FROM NHANVIEN WHERE IDNV='$id'");
        }
    }
}

$rs = mysqli_query($link, "SELECT * FROM NHANVIEN");

echo '<form action="xoatatca.php" method="post">';
echo '<table border="1" width="100%">';
echo '<caption>Du lieu bang Nhan vien</caption>';
echo '<TR><TH>IDNV</TH><TH>Ho ten</TH><TH>IDPB</TH><TH>Dia chi</TH><TH>Chon</TH></TR>';

while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR>';
    echo '<TD>' . $row['IDNV'] . '</TD>';
    echo '<TD>' . $row['Hoten'] . '</TD>';
    echo '<TD>' . $row['IDPB'] . '</TD>';
    echo '<TD>' . $row['Diachi'] . '</TD>';
    // Name checkbox trung voi ma IDNV
    echo '<TD align="center"><input type="checkbox" name="' . $row['IDNV'] . '" value="' . $row['IDNV'] . '"></TD>';
    echo '</TR>';
}

echo '<TR><TD colspan="5" align="right"><input type="submit" name="btnDelete" value="Xoa cac muc chon"></TD></TR>';
echo '</TABLE>';
echo '</form>';

mysqli_close($link);
?>
