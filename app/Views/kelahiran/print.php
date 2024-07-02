<?= $this->extend('layouts/print'); ?>
<?= $this->section('content'); ?>
<div style="text-align: justify; line-height: 1.5;">

  <div class="text-center mb-4">
    <h6 class="mb-0 pb-0">SURAT KETERANGAN LAHIR</h6>
    <p class="mb-0 pb-0 text-center">Nomor: <?= $noSurat; ?></p>
  </div>

  <!-- <p class="mb-2 pb-2">Yang bertanda tangan dibawah ini kepala desa <?= strtolower(service('settings')->get('App.desa')) ?> kecamatan <?= strtolower(service('settings')->get('App.kecamatan')) ?> kabupaten <?= strtolower(service('settings')->get('App.kabupaten')) ?> menerangkan dengan sesungguhnya bahwa pada hari <?= \Carbon\Carbon::parse($kelahiran->tanggal)->isoFormat('dddd'); ?> tanggal <?= \Carbon\Carbon::parse($kelahiran->tanggal)->isoFormat('D MMMM Y'); ?> telah lahir seorang bayi :</p> -->
  <p class="mb-2 pb-2">Yang bertanda tangan dibawah ini kepala desa <?= strtolower(service('settings')->get('App.desa')) ?> kecamatan <?= strtolower(service('settings')->get('App.kecamatan')) ?> kabupaten <?= strtolower(service('settings')->get('App.kabupaten')) ?> menerangkan dengan bahwa telah lahir seorang bayi :</p>

  <table class="table table-sm table-compact table-borderless ml-3">
    <tr>
      <td style="width:30%;">Nama</td>
      <td>: <?= $kelahiran->nama; ?></td>
    </tr>
    <tr>
      <td style="width:30%;">Jenis Kelamin</td>
      <td>: <?= $kelahiran->jenis_kelamin; ?></td>
    </tr>
    <tr>
      <td style="width:30%;">Tempat Lahir</td>
      <td>: <?= $kelahiran->tempat_lahir; ?></td>
    </tr>
    <tr>
      <td style="width:30%;">Tanggal Lahir</td>
      <td>: <?= \Carbon\Carbon::parse($kelahiran->tanggal_lahir)->isoFormat('D MMMM Y'); ?></td>
    </tr>
    <!-- <tr>
      <td style="width:30%;">Usia</td>
      <td>: <?= '' // \Carbon\Carbon::parse($kelahiran->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y Tahun %m Bulan %d Hari'); ?></td>
    </tr> -->
    <tr>
      <td style="width:30%;">Anak Ke</td>
      <td>: <?= $kelahiran->anakke ?></td>
    </tr>
  </table>

  <p class="mb-1 pb-1">Dari seorang ibu :</p>

  <table class="table table-sm table-compact table-borderless ml-3">
    <tr>
      <td style="width:30%;">Nama</td>
      <td>: <?= $ibu ? $ibu->nama : "-"; ?></td>
    </tr>
    <!-- <tr>
      <td style="width:30%;">Tanggal Lahir</td>
      <td>: <?= '' //$ibu ? \Carbon\Carbon::parse($ibu->tanggal_lahir)->isoFormat('D MMMM Y') : '-' ?></td>
    </tr> -->
    <!-- <tr>
      <td style="width:30%;">Usia</td>
      <td>: <?= '' //$ibu ? \Carbon\Carbon::parse($ibu->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y Tahun %m Bulan %d Hari') : '-' ?></td>
    </tr> -->
    <tr>
      <td style="width:30%;">Istri dari</td>
      <td>: <?= $ayah->nama; ?></td>
    </tr>
  </table>

  <p class="mb-2 pb-2">Demikian surat keterangan lahir ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
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
        <br>
        <p class="mb-0 pb-0 text-center"><b><u><?= service('settings')->get('App.kepalaDesa'); ?></u></b></p>
        <p class="text-center p-0 m-0">Kepala Desa <?= service('settings')->get('App.desa'); ?></p>
      </td>
    </tr>
  </table>
</div>

<?= $this->endSection(); ?>