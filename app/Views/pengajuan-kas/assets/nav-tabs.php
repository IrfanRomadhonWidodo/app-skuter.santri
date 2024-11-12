<?php $uri= service('uri') ?>
<ul class="nav nav-tabs mb-1">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('pengajuan-kas') ?>">
            <i class="fas fa-arrow-alt-circle-left mr-1"></i>Kembali</a>
    </li>
    <li class="nav-item">
        <a
            class="nav-link <?= $uri->getSegment(4) == null ? 'active' : '' ?>"
            href="<?= base_url('pengajuan-kas/d/'.$pengajuan['kas_id'].'/') ?>">Detail</a>
    </li>
    <li class="nav-item">
        <a
            class="nav-link <?= $uri->getSegment(4) == 'persetujuan' ? 'active' : '' ?>"
            href="<?= base_url('pengajuan-kas/d/'.$pengajuan['kas_id'].'/persetujuan') ?>">Persetujuan
        </a>
    </li>
</ul>