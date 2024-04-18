<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SIPAKDES | Home</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="h-screen w-screen bg-blue-100">
        <div class="flex flex-col lg:flex-row h-full w-full">
            <div class="w-full lg:w-1/2 h-[50%] lg:h-full bg-blue-500">
                <div class="flex flex-col gap-2 justify-center items-center h-full relative">
                    <h1 class="text-5xl lg:text-7xl text-white font-bold">SIPAKDES</h1>
                    <p class="text-sm lg:text-base text-white bg-blue-700 p-1 px-2 font-semibold rounded-lg">Sistem Informasi Pelayanan Administrasi Desa</p>
                    <!-- text left -->
                    <div class="hidden lg:block absolute bottom-0 w-full mb-3 lg:mb-7 px-10">
                        <p class="text-sm lg:text-base text-center text-white lg:font-bold">Desa <?= ucwords(service('settings')->get('App.desa')) ?> Kecamatan <?= ucwords(service('settings')->get('App.kecamatan')) ?> Kabupaten <?= ucwords(service('settings')->get('App.kabupaten')) ?></p>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 h-full bg-white">
                <div class="flex flex-col gap-3 justify-center items-center h-full">
                    <?php if (logged_in()) : ?>
                        <div class="flex flex-col gap-2 justify-center items-center mb-10">
                            <h1 class="text-xl font-bold">Anda sudah login</h1>
                            <p class="text-sm lg:text-lg text-center">Silahkan akses dashboard untuk melanjutkan dan menggunakan layanan yang ada.</p>
                        </div>

                        <a href="/dashboard" class="w-[50%] bg-blue-500 text-white p-2 rounded-lg text-center text-xl font-semibold uppercase hover:bg-blue-700 transition-all duration-300">
                            Dashboard
                        </a>

                        <a href="/logout" class="w-[50%] border-2 border-red-400 text-red-400 p-2 rounded-lg text-center text-xl font-semibold uppercase hover:bg-red-400 hover:text-white transition-all duration-300">
                            Logout
                        </a>
                    <?php else : ?>
                        <div class="flex flex-col gap-2 justify-center items-center mb-10">
                            <h1 class="text-xl font-bold">Sebelum Melanjutkan</h1>
                            <p class="text-sm lg:text-lg text-center">Silahkan login atau register terlebih dahulu</p>
                        </div>

                        <a href="/login" class="w-[50%] bg-blue-500 text-white p-2 rounded-lg text-center text-xl font-semibold uppercase hover:bg-blue-700 transition-all duration-300">
                            Login
                        </a>
                        <a href="/register" class="w-[50%] border-2 border-blue-500 text-blue-500 p-2 rounded-lg text-center text-xl font-semibold uppercase hover:bg-blue-700 hover:text-white hover:border-blue-700 transition-all duration-300">
                            Register
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>