<?php
// xemthongtinpb.php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());

// Chon CSDL de lam viec
mysqli_select_db($link, 'DULIEU');

$rs = mysqli_query($link, "SELECT * FROM PhongBan");

echo '<table border="1" width="100%">';
echo '<caption>Du lieu bang Phong Ban</caption>';
// Tieu de cua bang tren web
echo '<TR><TH>Ma Phong Ban</TH><TH>Ten Phong Ban</TH><TH>Mo ta</TH><TH>Xem Nhan Vien</TH></TR>';

// Hien thi tung hang
while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR><TD>' . $row['IDPB'] . '</TD><TD>' . $row['Tenpb'] . '</TD><TD>' . $row['Mota'] . '</TD><TD align="center"><A HREF="index.php?page=nhanvien_phongban&IDPB=' . $row['IDPB'] . '">xxx</A></TD></TR>';
}

echo '</TABLE>';

// Giai phong tap cac ban ghi trong $rs
mysqli_free_result($rs);
mysqli_close($link);
?>
