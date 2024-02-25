<?= $this->extend('layouts/print'); ?>
<?= $this->section('content'); ?>
<div style="text-align: justify; line-height: 1.5;">

  <div class="text-center mb-4">
    <h6 class="mb-0 pb-0">SURAT KETERANGAN KEMATIAN</h6>
    <p class="mb-0 pb-0 text-center">Nomor: <?= $noSurat; ?></p>
  </div>

  <p class="mb-2 pb-2">Yang bertanda tangan dibawah ini kepala desa <?= service('settings')->get('App.desa'); ?> kecamatan <?= service('settings')->get('App.kecamatan'); ?> kabupaten <?= service('settings')->get('App.kabupaten'); ?> menerangkan dengan sesungguhnya bahwa :</p>

  <table class="table table-sm table-compact table-borderless">
    <tr>
      <td>Nama</td>
      <td>: <?= $kematian->nama; ?></td>
    </tr>
    <tr>
      <td>Jenis Kelamin</td>
      <td>: <?= $kematian->jenis_kelamin; ?></td>
    </tr>
    <tr>
      <td>Tempat, Tanggal Lahir</td>
      <td>: <?= $kematian->tempat_lahir . ', ' . \Carbon\Carbon::parse($kematian->tanggal_lahir)->isoFormat('D MMMM Y'); ?></td>
    </tr>
    <tr>
      <td>Agama</td>
      <td>: <?= $kematian->agama; ?></td>
    </tr>
    <tr>
      <td>Pekerjaan</td>
      <td>: <?= $kematian->jenis_pekerjaan; ?></td>
    </tr>
  </table>

  <p class="mb-1 pb-1">Bahwa yang bersangkutan telah meninggal dunia pada :</p>

  <table class="table table-sm table-compact table-borderless">
    <tr>
      <td>Hari</td>
      <td>: <?= \Carbon\Carbon::parse($kematian->tanggal)->isoFormat('dddd'); ?></td>
    </tr>
    <tr>
      <td>Tanggal</td>
      <td>: <?= \Carbon\Carbon::parse($kematian->tanggal_kematian)->isoFormat('D MMMM Y'); ?></td>
    </tr>
    <tr>
      <td>Pukul</td>
      <td>: <?= \Carbon\Carbon::parse($kematian->tanggal_kematian)->isoFormat('HH:mm'); ?> WIB</td>
    </tr>
    <tr>
      <td>Yang menerangkan</td>
      <td>: <?= $pelapor ? $pelapor->nama : '-'; ?></td>
    </tr>
    <tr>
      <td>Sebab Kematian</td>
      <td>: <?= $kematian->sebab; ?></td>
    </tr>
    <tr>
      <td>Tempat Pemakaman</td>
      <td>: <?= $kematian->tempat; ?></td>
    </tr>
  </table>

  <p class="mb-2 pb-2">Demikian surat keterangan kematian ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.</p>
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