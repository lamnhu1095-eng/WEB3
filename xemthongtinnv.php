<?php
// xemthongtinnv.php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

$rs = mysqli_query($link, "SELECT * FROM NHANVIEN");
?>

<p class="tbl-title">✦ Danh Sách Nhân Viên ✦</p>

<table>
    <thead>
        <tr>
            <th>Mã NV</th>
            <th>Họ Tên</th>
            <th>Phòng Ban</th>
            <th>Địa Chỉ</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)): ?>
        <tr>
            <td><?= htmlspecialchars($row['IDNV']) ?></td>
            <td><?= htmlspecialchars($row['Hoten']) ?></td>
            <td><?= htmlspecialchars($row['IDPB']) ?></td>
            <td><?= htmlspecialchars($row['Diachi']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
mysqli_free_result($rs);
mysqli_close($link);
?>
