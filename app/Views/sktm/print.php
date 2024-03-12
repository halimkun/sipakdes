<?= $this->extend('layouts/print'); ?>
<?= $this->section('content'); ?>
<?php
$p = $pengantar->toArray();
$alamat = "Kelurahan {$p['kelurahan']} RT " . str_pad($p['rt'], 3, 0, STR_PAD_LEFT) . " RW " . str_pad($p['rw'], 3, 0, STR_PAD_LEFT) . " kecamatan {$p['kecamatan']} kabupaten {$p['kabupaten']} provinsi {$p['provinsi']}";
?>

<div style="text-align: justify; line-height: 1.5;">
  <div class="text-center mb-4">
    <h6 class="mb-0 pb-0">SURAT KETERANGAN TIDAK MAMPU</h6>
    <p class="mb-0 pb-0 text-center">Nomor: <?= $noSurat; ?></p>
  </div>

  <p class="mb-2 pb-2">Yang bertanda tangan dibawah ini kepala desa <?= strtolower(service('settings')->get('App.desa')) ?> kecamatan <?= strtolower(service('settings')->get('App.kecamatan')) ?> kabupaten <?= strtolower(service('settings')->get('App.kabupaten')) ?> menerangkan dengan sesungguhnya bahwa :</p>

  <div class="px-3 pb-3">
    <table style="width: 100%;">
      <tr>
        <th style="width: 40%; vertical-align: top;">Nama Lengkap</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->nama ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">NIK / No. KTP</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->nik ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">No. KK</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->kk ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Tempat / Tanggal Lahir</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->tempat_lahir ?>, <?= \Carbon\Carbon::parse($pengantar->tanggal_lahir)->isoFormat('D MMMM Y'); ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Agama</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->agama ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Jenis Kelamin</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->jenis_kelamin ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Alamat</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $alamat ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Kewarganegaraan</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->kewarganegaraan ?></td>
      </tr>
      <tr>
        <th style="width: 40%; vertical-align: top;">Keperluan</th>
        <td style="width:10px; vertical-align: top;">:</td>
        <td style="vertical-align: top;"><?= $pengantar->keperluan ?></td>
      </tr>
    </table>
  </div>

  <p class="mb-2 pb-2">Orang tersebut diatas adalah benar-benar warga desa <?= strtolower(service('settings')->get('App.desa')) ?> kecamatan <?= strtolower(service('settings')->get('App.kecamatan')) ?> kabupaten <?= strtolower(service('settings')->get('App.kabupaten')) ?>, Dengan sepengatahuan kami dan data yang ada di kantor kepala desa <?= strtolower(service('settings')->get('App.desa')) ?> bahwa yang bersangkutan benar-benar keluarga kurang mampu <b><i>(KELUARGA BERPENGHASILAN RENDAH)</i></b>, dan surat ini dibuat untuk keperluan <u><?= $pengantar->keperluan ?></u>.
</p>

  <p class="mb-2 pb-2">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
</div>

<div class="mt-5">
  <table class="table table-sm table-borderless">
    <tr>
      <td style="width: 50%;"></td>
      <td class="text-center">
        <?= service('settings')->get('App.kabupaten'); ?>, <?= \Carbon\Carbon::now()->isoFormat('D MMMM Y'); ?>
        <br>
        <br>
        <br>
        <br>
        <p class="mb-0 pb-0 text-center"><b><u><?= service('settings')->get('App.kepalaDesa'); ?></u></b></p>
        <p class="text-center p-0 m-0">Kepala Desa <?= service('settings')->get('App.desa'); ?></p>
      </td>
    </tr>
  </table>
</div>
<?= $this->endSection(); ?>