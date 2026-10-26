<?php
$tieuchi = isset($_REQUEST['tieuchi']) ? $_REQUEST['tieuchi'] : 'IDNV';
$tukhoa = isset($_REQUEST['tukhoa']) ? trim($_REQUEST['tukhoa']) : '';
?>
<form action="index.php?page=timkiem" method="post">
<table border="1" align="center" width="400">
    <caption><b>Tim Kiem Thong Tin</b></caption>
    <tr>
        <td align="center">
            <input type="radio" name="tieuchi" value="IDNV" <?php if ($tieuchi == 'IDNV') echo 'checked'; ?>> IDNV
            <input type="radio" name="tieuchi" value="Hoten" <?php if ($tieuchi == 'Hoten') echo 'checked'; ?>> Hoten
            <input type="radio" name="tieuchi" value="Diachi" <?php if ($tieuchi == 'Diachi') echo 'checked'; ?>> Diachi
        </td>
    </tr>
    <tr>
        <td align="center">
            <input type="text" name="tukhoa" value="<?php echo htmlspecialchars($tukhoa); ?>">
            <input type="submit" name="btnSearch" value="Search">
        </td>
    </tr>
</table>
</form>

<?php
if (isset($_REQUEST['btnSearch']) && $tukhoa != '') {
    $link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
    mysqli_select_db($link, 'DULIEU');

    $rs = mysqli_query($link, "SELECT * FROM NHANVIEN WHERE $tieuchi LIKE '%$tukhoa%'");

    echo '<table border="1" width="100%">';
    echo '<caption>Ket qua tim kiem</caption>';
    echo '<TR><TH>IDNV</TH><TH>Ho ten</TH><TH>IDPB</TH><TH>Dia chi</TH></TR>';

    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
            echo '<TR><TD>' . $row['IDNV'] . '</TD><TD>' . $row['Hoten'] . '</TD><TD>' . $row['IDPB'] . '</TD><TD>' . $row['Diachi'] . '</TD></TR>';
        }
    } else {
        echo '<TR><TD colspan="4" align="center">Khong tim thay ket qua!</TD></TR>';
    }
    echo '</TABLE>';

    mysqli_free_result($rs);
    mysqli_close($link);
}
?>
