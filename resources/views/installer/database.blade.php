<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Database Configuration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .input { @apply w-full border border-gray-300 px-3 py-2 rounded mt-1 focus:ring-2 focus:ring-blue-500 outline-none transition-all disabled:bg-gray-100; }
        .btn-primary { @apply bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700 transition active:scale-95 flex items-center justify-center; }
        .db-field.hidden { display: none; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

<div class="max-w-3xl w-full bg-white p-8 rounded-xl shadow-lg border border-gray-200">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Database Configuration</h2>
            <p class="text-sm text-gray-500">Step 1 of 3: Configure your data source</p>
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-blue-500 bg-blue-50 px-3 py-1 rounded-full">Step 1</span>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded flex items-start">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('installer.storeDatabase') }}" method="POST" id="dbForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-gray-600">Database Connection</label>
                <select name="db_connection" id="db_connection" class="input">
                    <option value="mysql" {{ old('db_connection') == 'mysql' ? 'selected' : '' }}>MySQL / MariaDB</option>
                    <option value="sqlite" {{ old('db_connection') == 'sqlite' ? 'selected' : '' }}>SQLite</option>
                    <option value="pgsql" {{ old('db_connection') == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                    <option value="sqlsrv" {{ old('db_connection') == 'sqlsrv' ? 'selected' : '' }}>SQL Server</option>
                </select>
                <p id="sqlite_note" class="text-[11px] text-blue-500 mt-2 hidden italic font-medium">
                    *SQLite will automatically create a file in <code>database/database.sqlite</code>
                </p>
            </div>

            <div class="db-field">
                <label class="text-sm font-semibold text-gray-600">Database Host</label>
                <input type="text" name="db_host" class="input" value="{{ old('db_host', '127.0.0.1') }}" placeholder="localhost">
            </div>

            <div class="db-field">
                <label class="text-sm font-semibold text-gray-600">Database Port</label>
                <input type="text" name="db_port" id="db_port" class="input" value="{{ old('db_port', '3306') }}">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-gray-600" id="label_db_name">Database Name</label>
                <input type="text" name="db_name" id="db_name" class="input" value="{{ old('db_name') }}" placeholder="e.g. pos_db" required>
            </div>

            <div class="db-field">
                <label class="text-sm font-semibold text-gray-600">Database Username</label>
                <input type="text" name="db_user" class="input" value="{{ old('db_user') }}" placeholder="root">
            </div>

            <div class="db-field">
                <label class="text-sm font-semibold text-gray-600">Database Password</label>
                <input type="password" name="db_pass" class="input" placeholder="••••••••">
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8 border-t pt-6">
            <button type="submit" id="submitBtn" class="btn-primary w-full md:w-auto">
                <span id="btnText">Test & Next</span>
                <svg id="loadingIcon" class="hidden animate-spin h-4 w-4 ml-2 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg id="arrowIcon" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </form>
</div>

<script>
    const dbConn = document.getElementById('db_connection');
    const dbPort = document.getElementById('db_port');
    const dbName = document.getElementById('db_name');
    const labelDbName = document.getElementById('label_db_name');
    const sqliteNote = document.getElementById('sqlite_note');
    const extraFields = document.querySelectorAll('.db-field');
    const dbForm = document.getElementById('dbForm');

    function handleConnectionChange(driver) {
        if (driver === 'mysql') dbPort.value = '3306';
        else if (driver === 'pgsql') dbPort.value = '5432';
        else if (driver === 'sqlsrv') dbPort.value = '1433';

        if (driver === 'sqlite') {
            extraFields.forEach(el => el.classList.add('hidden'));
            sqliteNote.classList.remove('hidden');
            labelDbName.innerText = "Database Path";
            if(!dbName.value) dbName.value = "database/database.sqlite";
        } else {
            extraFields.forEach(el => el.classList.remove('hidden'));
            sqliteNote.classList.add('hidden');
            labelDbName.innerText = "Database Name";
            if(dbName.value === "database/database.sqlite") dbName.value = "";
        }
    }

    dbConn.addEventListener('change', function() { handleConnectionChange(this.value); });
    window.addEventListener('load', () => handleConnectionChange(dbConn.value));

    dbForm.addEventListener('submit', () => {
        document.getElementById('btnText').innerText = 'Connecting...';
        document.getElementById('loadingIcon').classList.remove('hidden');
        document.getElementById('arrowIcon').classList.add('hidden');
        document.getElementById('submitBtn').disabled = true;
    });
</script>
</body>
</html>