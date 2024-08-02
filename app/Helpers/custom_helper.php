<?php 
  if (!function_exists('getColorHubungan')) {
    function getColorHubungan($hubungan)
    {
        $colors = [
            'Ayah' => 'primary',
            'Ibu'  => 'success',
            'Anak' => 'info',
            '-'    => 'secondary'
        ];

        return $colors[$hubungan];
    }
  }

  if (!function_exists('getColorStatus')) {
    function getColorStatus($color)
    {
        $colors = [
          "selesai" => "success",
          "pending" => "warning",
          "ditolak" => "danger",
          "total"   => "primary"
        ];

        return $colors[$color];
    }
  }

  if (!function_exists('getTitleText')) {
    function getTitleText($color)
    {
        $colors = [
          'domisili'  => 'Surat Domisili',
          'kematian'  => 'Keterangan Kematian',
          'kelahiran' => 'Keterangan Lahiran',
          'kia'       => 'Kartu Identitas Anak',
          'sktm'      => 'Surat Keterangan Tidak Mampu',
          'skck'      => 'Surat Keterangan Catatan Kepolisian'
        ];

        return $colors[$color];
    }
  }

  if (!function_exists('getIconText')) {
    function getIconText($color)
    {
        $colors = [
          'domisili'  => 'fas fa-map-marked-alt',
          'kematian'  => 'fas fa-heart-broken',
          'kelahiran' => 'fas fa-baby',
          'kia'       => 'fas fa-id-card-alt',
          'sktm'      => 'fas fa-donate',
          'skck'      => 'fas fa-fingerprint'
        ];

        return $colors[$color];
    }
  }
?>