<?php 
  if (!function_exists('getColorHubungan')) {
    function getColorHubungan($hubungan)
    {
        $colors = [
            'Ayah' => 'primary',
            'Ibu' => 'success',
            'Anak' => 'info',
            '-' => 'secondary'
        ];

        return $colors[$hubungan];
    }
  }
?>