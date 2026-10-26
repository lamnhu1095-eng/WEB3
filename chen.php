<?php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

if (isset($_REQUEST['btnChen'])) {
    $idnv = $_REQUEST['txtIDNV'];
    $hoten = $_REQUEST['txtHoten'];
    $idpb = $_REQUEST['txtIDPB'];
    $diachi = $_REQUEST['txtDiachi'];

    $sql = "INSERT INTO NHANVIEN (IDNV, Hoten, IDPB, Diachi) VALUES ('$idnv', '$hoten', '$idpb', '$diachi')";
    mysqli_query($link, $sql);
    echo '<script>alert("Them nhan vien thanh cong!");</script>';
}

$rs_pb = mysqli_query($link, "SELECT IDPB, Tenpb FROM PhongBan");
?>
<HTML>
<BODY>
<form action="chen.php" method="post">
<table width="400" border="0" align="center">
    <caption><b>CHEN THONG TIN NHAN VIEN</b></caption>
    <TR>
        <TD>Ma nhan vien</TD>
        <TD><input type="text" name="txtIDNV" required></TD>
    </TR>
    <TR>
        <TD>Ho ten</TD>
        <TD><input type="text" name="txtHoten" required></TD>
    </TR>
    <TR>
        <TD>Phong ban</TD>
        <TD>
            <select name="txtIDPB">
                <?php while ($pb = mysqli_fetch_array($rs_pb, MYSQLI_BOTH)) { ?>
                    <option value="<?php echo $pb['IDPB']; ?>"><?php echo $pb['IDPB'] . ' - ' . $pb['Tenpb']; ?></option>
                <?php } ?>
            </select>
        </TD>
    </TR>
    <TR>
        <TD>Dia chi</TD>
        <TD><input type="text" name="txtDiachi"></TD>
    </TR>
    <TR>
        <TD align="right"><input type="submit" name="btnChen" value="Chen"></TD>
        <TD><input type="reset" value="Reset"></TD>
    </TR>
</table>
</form>
</BODY>
</HTML>
<?php
mysqli_close($link);
?>