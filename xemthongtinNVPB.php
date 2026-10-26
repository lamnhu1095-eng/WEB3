<?php
// xemthongtinNVPB.php
$mapb = isset($_REQUEST['IDPB']) ? $_REQUEST['IDPB'] : '';
$bienketnoi = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($bienketnoi, 'DULIEU');

if ($mapb == '') {
    $rs = mysqli_query($bienketnoi, "SELECT * FROM NHANVIEN");
    $subtitle = 'Tất Cả Nhân Viên';
} else {
    $rs = mysqli_query($bienketnoi, "SELECT * FROM NHANVIEN WHERE IDPB='" . mysqli_real_escape_string($bienketnoi, $mapb) . "'");
    $subtitle = 'Nhân Viên — Phòng ' . htmlspecialchars($mapb);
}
?>

<p class="tbl-title">✦ <?= $subtitle ?> ✦</p>

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
mysqli_close($bienketnoi);
?>