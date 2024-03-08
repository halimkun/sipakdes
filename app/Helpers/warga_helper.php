<?php

$penduduk_data = [
  'nik', 'kk', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
  'golongan_darah', 'agama', 'pendidikan', 'jenis_pekerjaan', 'hubungan',
  'kewarganegaraan', 'status_perkawinan', 'rt', 'rw', 'kelurahan', 'kecamatan',
  'kabupaten', 'provinsi'
];

// if function is not exists 
if (!function_exists('isLengkap')) {
  function isLengkap($id_penduduk)
  {
    if ($id_penduduk == null) {
      return false;
    }

    // get data from penduduk table by id_penduduk if the data except created_at and updated_at and deleted_at is not null then return true else return false
    $penduduk = new \App\Models\PendudukModel();
    $data = $penduduk->find($id_penduduk);

    if ($data) {
      foreach ($data->toArray() as $key => $value) {
        if (!in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
          if (in_array($key, ['is_verified', 'is_kepala_keluarga'])) {
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
}

// if function is not exists 
if (!function_exists('isLengkapOnFilter')) {
  function isLengkapOnFilter($id_penduduk)
  {
    if ($id_penduduk == null) {
      return false;
    }

    // get data from penduduk table by id_penduduk if the data except created_at and updated_at and deleted_at is not null then return true else return false
    $penduduk = new \App\Models\PendudukModel();
    $data = $penduduk->find($id_penduduk);

    if ($data) {
      foreach ($data->toArray() as $key => $value) {
        if (!in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
          if (in_array($key, ['is_verified', 'is_kepala_keluarga'])) {
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
}



// if function is not exists
if (!function_exists('isMarkFilled')) {
  // check the passed data is marked as a valid data or not
  function isMarkFilled($data)
  {
    return !empty($data) && $data !== 0 && $data !== '-';
  }
}

if (!function_exists('dataLengkap')) {
  // check data lengkap withot query to database
  function dataLengkap($data)
  {
    foreach ($data as $key => $value) {
      if (!in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
        if (in_array($key, ['is_verified', 'is_kepala_keluarga'])) {
          continue;
        }

        if ($value == null || $value == '' || $value == 0 || $value == '-') {
          return false;
        }
      }
    }
    return true;
  }
}
