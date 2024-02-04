<?php 

  $penduduk_data = [
    'nik', 'kk', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
    'golongan_darah', 'agama', 'pendidikan', 'jenis_pekerjaan', 'hubungan',
    'kewarganegaraan', 'status_perkawinan', 'rt', 'rw', 'kelurahan', 'kecamatan',
    'kabupaten', 'provinsi'
  ];

  function isLengkap($id_penduduk)
  {
    if ($id_penduduk == null) {
      return false;
    }

    // get data from penduduk table by id_penduduk if the data except created_at and updated_at and deleted_at is not null then return true else return false
    $penduduk = new \App\Models\PendudukModel();
    $data = $penduduk->find($id_penduduk);
    
    if ($data){
      foreach ($data as $key => $value) {
        if (!in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
          if ($key == 'is_verified') {
            continue;
          }

          if ($value == null || $value == '' || $value == 0 || $value == '-') {
            return false;
          }
        }
      }
      return true;
    } else {
      return false;
    }
  }