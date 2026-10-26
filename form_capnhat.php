<?php
$myID = $_REQUEST['IDPB']; // nhan ma phong ban tu trang capnhat.php

$bienketnoi = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($bienketnoi, 'DULIEU');

$rs = mysqli_query($bienketnoi, "SELECT * FROM PhongBan WHERE IDPB='$myID'");
$row = mysqli_fetch_array($rs, MYSQLI_BOTH);
?>
<HTML>
<BODY>
<Form action="xulicapnhat.php?IDPB=<?php echo $row['IDPB']; ?>" method="post">
<table width="100%" border="0">
<TR>
    <TD>Ma phong ban</TD>
    <TD><INPUT TYPE="Text" size="20" name="txtIDPB" readonly value="<?php echo $row['IDPB']; ?>"></TD>
</TR>
<TR>
    <TD>Ten phong ban</TD>
    <TD><INPUT TYPE="Text" size="20" name="txtTenpb" value="<?php echo $row['Tenpb']; ?>"></TD>
</TR>
<TR>
    <TD>Mo ta</TD>
    <TD><INPUT TYPE="Text" size="20" name="txtMota" value="<?php echo $row['Mota']; ?>"></TD>
</TR>
<TR>
    <TD align="right"><INPUT TYPE="Submit" value="Cap nhat"></TD>
    <TD><INPUT TYPE="Reset" value="Reset"></TD>
</TR>
</table>
</Form>
</BODY>
</HTML>
<?php
mysqli_free_result($rs);
mysqli_close($bienketnoi);
?>