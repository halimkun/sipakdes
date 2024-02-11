<?php

namespace Myth\Auth\Language\en;

return [
    // Exceptions
    'invalidModel'        => 'Model {0} harus dimuat sebelum digunakan.',
    'userNotFound'        => 'Tidak dapat menemukan pengguna dengan ID = {0, number}.',
    'noUserEntity'        => 'Entity Pengguna harus disediakan untuk validasi kata sandi.',
    'tooManyCredentials'  => 'Anda hanya dapat memvalidasi satu kredensial selain kata sandi.',
    'invalidFields'       => 'Bidang "{0}" tidak dapat digunakan untuk memvalidasi kredensial.',
    'unsetPasswordLength' => 'Anda harus mengatur pengaturan `minimumPasswordLength` dalam file konfigurasi Auth.',
    'unknownError'        => 'Maaf, kami mengalami masalah saat mengirim email kepada Anda. Silakan coba lagi nanti.',
    'notLoggedIn'         => 'Anda harus masuk untuk mengakses halaman tersebut.',
    'notEnoughPrivilege'  => 'Anda tidak memiliki izin yang cukup untuk mengakses halaman tersebut.',

    // Registration
    'registerDisabled' => 'Maaf, akun pengguna baru tidak diizinkan saat ini.',
    'registerSuccess'  => 'Selamat datang! Silakan masuk dengan kredensial baru Anda.',
    'registerCLI'      => 'Pengguna baru dibuat: {0}, #{1}',

    // Activation
    'activationNoUser'       => 'Tidak dapat menemukan pengguna dengan kode aktivasi tersebut.',
    'activationSubject'      => 'Aktifkan akun Anda',
    'activationSuccess'      => 'Harap konfirmasi akun Anda dengan mengklik tautan aktivasi dalam email yang telah kami kirimkan.',
    'activationResend'       => 'Kirim ulang pesan aktivasi satu kali lagi.',
    'notActivated'           => 'Akun pengguna ini belum diaktifkan.',
    'errorSendingActivation' => 'Gagal mengirim pesan aktivasi ke: {0}',

    // Login
    'badAttempt'      => 'Tidak dapat masuk. Harap periksa kredensial Anda.',
    'loginSuccess'    => 'Selamat datang kembali!',
    'invalidPassword' => 'Tidak dapat masuk. Harap periksa kata sandi Anda.',

    // Forgotten Passwords
    'forgotDisabled'  => 'Opsi reset password telah dinonaktifkan.',
    'forgotNoUser'    => 'Tidak dapat menemukan pengguna dengan email tersebut.',
    'forgotSubject'   => 'Instruksi Reset Kata Sandi',
    'resetSuccess'    => 'Kata sandi Anda telah berhasil diubah. Silakan masuk dengan kata sandi baru.',
    'forgotEmailSent' => 'Token keamanan telah dikirimkan melalui email kepada Anda. Masukkan di bawah ini untuk melanjutkan.',
    'errorEmailSent'  => 'Tidak dapat mengirim email dengan instruksi reset password ke: {0}',
    'errorResetting'  => 'Tidak dapat mengirim instruksi reset ke {0}',

    // Passwords
    'errorPasswordLength'         => 'Kata sandi harus minimal {0, number} karakter.',
    'suggestPasswordLength'       => 'Frasa sandi - hingga 255 karakter - membuat kata sandi lebih aman yang mudah diingat.',
    'errorPasswordCommon'         => 'Kata sandi tidak boleh menjadi kata sandi umum.',
    'suggestPasswordCommon'       => 'Kata sandi telah diperiksa melawan lebih dari 65 ribu kata sandi umum atau kata sandi yang telah bocor melalui peretasan.',
    'errorPasswordPersonal'       => 'Kata sandi tidak boleh mengandung informasi pribadi yang di-hash kembali.',
    'suggestPasswordPersonal'     => 'Variasi dari alamat email atau nama pengguna Anda sebaiknya tidak digunakan untuk kata sandi.',
    'errorPasswordTooSimilar'     => 'Kata sandi terlalu mirip dengan nama pengguna.',
    'suggestPasswordTooSimilar'   => 'Jangan menggunakan bagian dari nama pengguna Anda dalam kata sandi Anda.',
    'errorPasswordPwned'          => 'Kata sandi {0} telah terungkap karena pelanggaran data dan telah terlihat {1, number} kali dalam {2} dari kata sandi yang kompromi.',
    'suggestPasswordPwned'        => '{0} seharusnya tidak pernah digunakan sebagai kata sandi. Jika Anda menggunakannya di mana pun, segera ubah.',
    'errorPasswordPwnedDatabase'  => 'basis data',
    'errorPasswordPwnedDatabases' => 'basis data',
    'errorPasswordEmpty'          => 'Kata Sandi dibutuhkan.',
    'passwordChangeSuccess'       => 'Kata sandi berhasil diubah',
    'userDoesNotExist'            => 'Kata sandi tidak diubah. Pengguna tidak ada',
    'resetTokenExpired'           => 'Maaf. Token reset Anda telah kadaluarsa.',

    // Groups
    'groupNotFound' => 'Tidak dapat menemukan grup: {0}.',

    // Permissions
    'permissionNotFound' => 'Tidak dapat menemukan izin: {0}',

    // Banned
    'userIsBanned' => 'Pengguna telah dilarang. Hubungi administrator',

    // Too many requests
    'tooManyRequests' => 'Terlalu banyak permintaan. Harap tunggu {0, number} detik.',

    // Login views
    'home'                      => 'Beranda',
    'current'                   => 'Saat Ini',
    'forgotPassword'            => 'Lupa Kata Sandi Anda?',
    'enterEmailForInstructions' => 'Tidak masalah! Masukkan email Anda di bawah ini dan kami akan mengirimkan instruksi untuk mereset kata sandi Anda.',
    'email'                     => 'Email',
    'emailAddress'              => 'Alamat Email',
    'sendInstructions'          => 'Kirim Instruksi',
    'loginTitle'                => 'Masuk',
    'loginAction'               => 'Masuk',
    'rememberMe'                => 'Ingat saya',
    'needAnAccount'             => 'Butuh akun?',
    'forgotYourPassword'        => 'Lupa kata sandi Anda?',
    'password'                  => 'Kata Sandi',
    'repeatPassword'            => 'Ulangi Kata Sandi',
    'emailOrUsername'           => 'Email atau nama pengguna',
    'username'                  => 'Nama Pengguna',
    'register'                  => 'Daftar',
    'signIn'                    => 'Masuk',
    'alreadyRegistered'         => 'Sudah terdaftar?',
    'weNeverShare'              => 'Kami tidak akan pernah membagikan email Anda kepada siapa pun.',
    'resetYourPassword'         => 'Reset Kata Sandi Anda',
    'enterCodeEmailPassword'    => 'Masukkan kode yang Anda terima melalui email, alamat email Anda, dan kata sandi baru Anda.',
    'token'                     => 'Token',
    'newPassword'               => 'Kata Sandi Baru',
    'newPasswordRepeat'         => 'Ulangi Kata Sandi Baru',
    'resetPassword'             => 'Reset Kata Sandi',
];
