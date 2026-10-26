<?php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

$rs = mysqli_query($link, "SELECT * FROM PhongBan");

echo '<table border="1" width="100%">';
echo '<caption>Danh sach phong ban cap nhat</caption>';
echo '<TR><TH>Ma Phong Ban</TH><TH>Ten Phong Ban</TH><TH>Mo ta</TH><TH>Cap nhat</TH></TR>';

while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo '<TR><TD>' . $row['IDPB'] . '</TD><TD>' . $row['Tenpb'] . '</TD><TD>' . $row['Mota'] . '</TD><TD align="center"><A HREF="form_capnhat.php?IDPB=' . $row['IDPB'] . '">Cap nhat</A></TD></TR>';
}

echo '</TABLE>';
mysqli_free_result($rs);
mysqli_close($link);
?>