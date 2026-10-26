<?php
session_start();
$msg = "";

if (isset($_REQUEST['btnLogin'])) {
    $user = $_REQUEST['txtUsername'];
    $pass = $_REQUEST['txtPassword'];

    $link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
    mysqli_select_db($link, 'DULIEU');

    // Kiem tra trong bang admin
    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $rs = mysqli_query($link, $sql);

    if (mysqli_num_rows($rs) > 0) {
        $_SESSION['admin_user'] = $user;
        mysqli_close($link);
        header("Location: index.php");
        exit();
    } else {
        $msg = "Sai ten dang nhap hoac mat khau!";
    }

    mysqli_close($link);
}
?>
<HTML>
<HEAD>
    <TITLE>Dang nhap</TITLE>
</HEAD>
<BODY>
<form action="login.php" method="post">
<table border="1" align="center" width="350">
    <caption><b>DANG NHAP HE THONG</b></caption>
    <?php if ($msg != "") { ?>
    <TR>
        <TD colspan="2" align="center"><font color="red"><?php echo $msg; ?></font></TD>
    </TR>
    <?php } ?>
    <TR>
        <TD>Username:</TD>
        <TD><input type="text" name="txtUsername" required></TD>
    </TR>
    <TR>
        <TD>Password:</TD>
        <TD><input type="password" name="txtPassword" required></TD>
    </TR>
    <TR>
        <TD align="right"><input type="submit" name="btnLogin" value="Login"></TD>
        <TD><input type="reset" value="Reset"></TD>
    </TR>
</table>
</form>
<p align="center"><a href="index.php">← Quay ve trang chu</a></p>
</BODY>
</HTML>
