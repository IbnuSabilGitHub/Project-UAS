<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>HRIS Login</title>
</head>
<body class="bg-[#0B0F1A] min-h-screen flex items-center justify-center p-4">

    <div class="text-center mb-6">
        <h1 class="text-6xl font-extrabold text-white tracking-wide">HRIS</h1>
        <p class="text-lg text-gray-300">(Human Resource Information System)</p>
    </div>

    <div class="w-full max-w-md bg-[#111827] p-6 rounded-2xl shadow-xl border border-gray-700">

        <h3 class="text-white text-center text-lg font-semibold mb-4">Login Sebagai</h3>
        <div class="flex justify-center gap-4 mb-6">
            <button class="px-6 py-2 rounded-lg bg-[#1F2A44] text-white hover:bg-[#24304f]">Admin</button>
            <button class="px-6 py-2 rounded-lg bg-[#1F2A44] text-white hover:bg-[#24304f]">Karyawan</button>
        </div>

        <!-- FORM BARU YANG KAMU MINTA -->
        <div class="w-full max-w-sm mx-auto bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
            <form action="#">
                <h5 class="text-xl font-semibold text-heading mb-6 text-white">Login ke akun karyawan</h5>

                <div class="mb-4">
                    <label for="email" class="block mb-2.5 text-sm font-medium text-white">Your email</label>
                    <input type="email" id="email" class="bg-neutral-secondary-medium border border-default-medium text-white text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-gray-400" placeholder="example@company.com" required />
                </div>

                <div>
                    <label for="password" class="block mb-2.5 text-sm font-medium text-white">Your password</label>
                    <input type="password" id="password" class="bg-neutral-secondary-medium border border-default-medium text-white text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-gray-400" placeholder="•••••••••" required />
                </div>

                </div>

                <button type="submit" class="text-white bg-blue-600 border border-transparent hover:bg-blue-700 focus:ring-4 focus:ring-blue-400 shadow-xs font-medium rounded-base text-sm px-4 py-2.5 w-full mb-3">Login to your account</button>

                            </form>
        </div>

    </div>

</body>
</html>
