<?php $uri = service('uri'); ?>
<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link active" href="<?= base_url('mahasiswa/'.$user['usr_id'].'/e') ?>">Data dasar</a>
    </li>

    <li class="nav-item"></li>
        <a class="nav-link" href="<?= base_url('mahasiswa/'.$user['usr_id'].'/e/pendidikan') ?>">Pendidikan</a>
    </li>
    <li class="nav-item"></li>
        <a class="nav-link" href="<?= base_url('mahasiswa/'.$user['usr_id'].'/e/alamat') ?>">Alamat</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('mahasiswa/'.$user['usr_id'].'/e/orang-tua') ?>">Orang Tua</a>
    </li>
</ul>