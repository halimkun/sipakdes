<?php 
  if (!function_exists('getColorHubungan')) {
    function getColorHubungan($hubungan)
    {
        $colors = [
            'Kepala Keluarga' => 'primary',
            'Ayah' => 'primary',
            'Ibu' => 'success',
            'Anak' => 'info',
        ];

        return $colors[$hubungan];
    }
  }
?>