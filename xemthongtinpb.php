<?php
// xemthongtinpb.php
$link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
mysqli_select_db($link, 'DULIEU');

$rs = mysqli_query($link, "SELECT * FROM PhongBan");
?>

<p class="tbl-title">✦ Danh Sách Phòng Ban ✦</p>

<table>
    <thead>
        <tr>
            <th>Mã PB</th>
            <th>Tên Phòng Ban</th>
            <th>Mô Tả</th>
            <th>Nhân Viên</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)): ?>
        <tr>
            <td><?= htmlspecialchars($row['IDPB']) ?></td>
            <td><?= htmlspecialchars($row['Tenpb']) ?></td>
            <td><?= htmlspecialchars($row['Mota']) ?></td>
            <td>
                <a href="index.php?page=nhanvien_phongban&IDPB=<?= urlencode($row['IDPB']) ?>">
                    Xem &rarr;
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
mysqli_free_result($rs);
mysqli_close($link);
?>
