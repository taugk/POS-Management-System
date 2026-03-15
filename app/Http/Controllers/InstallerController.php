<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PDO;
use Exception;

class InstallerController extends Controller
{
    // --- View Methods ---
    public function database() { return view('installer.database'); }
    public function store() { return view('installer.store'); }
    public function admin() { return view('installer.admin'); }
    public function final() { return view('installer.install'); }

    // --- Logic Methods ---

    /**
     * Menangani Step 1: Validasi & Koneksi Database
     */
    public function storeDatabase(Request $request)
    {
        Log::info('Installer: Memulai validasi database...');
        
        $request->validate([
            'db_connection' => 'required|in:mysql,pgsql,sqlsrv,sqlite',
            'db_host'       => 'required_unless:db_connection,sqlite',
            'db_port'       => 'required_unless:db_connection,sqlite',
            'db_name'       => 'required',
            'db_user'       => 'required_unless:db_connection,sqlite',
        ]);

        $driver = $request->db_connection;
        $host   = $request->db_host;
        $port   = $request->db_port;
        $name   = $request->db_name; 
        $user   = $request->db_user;
        $pass   = $request->db_pass;

        try {
            Log::info("Installer: Mencoba koneksi PDO ke driver: $driver");

            if ($driver === 'sqlite') {
                $dbPath = base_path($name);
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                    Log::info("Installer: File SQLite baru dibuat di $dbPath");
                }
                new PDO("sqlite:$dbPath");
            } else {
                $dsnRaw = match($driver) {
                    'mysql'  => "mysql:host=$host;port=$port;charset=utf8mb4",
                    'pgsql'  => "pgsql:host=$host;port=$port",
                    'sqlsrv' => "sqlsrv:Server=$host,$port",
                };

                $pdo = new PDO($dsnRaw, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                Log::info("Installer: Koneksi server database berhasil.");

                if ($driver === 'mysql') {
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                    Log::info("Installer: Database $name dipastikan ada (MySQL).");
                }

                $dsnWithDb = match($driver) {
                    'mysql'  => "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4",
                    'pgsql'  => "pgsql:host=$host;port=$port;dbname=$name",
                    'sqlsrv' => "sqlsrv:Server=$host,$port;Database=$name",
                };
                new PDO($dsnWithDb, $user, $pass);
            }

            Log::info("Installer: Verifikasi database $name selesai.");

            session([
                'db_connection' => $driver,
                'db_host'       => $host,
                'db_port'       => $port,
                'db_name'       => $name,
                'db_user'       => $user,
                'db_pass'       => $pass
            ]);

            return redirect()->route('installer.store');

        } catch (Exception $e) {
            Log::error("Installer Error (Database Step): " . $e->getMessage());
            return back()->withInput()->with('error', 'Koneksi Database Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Menangani Step 2: Simpan Informasi Toko (PERBAIKAN ERROR ANDA)
     */
    public function storeStore(Request $request)
    {
        Log::info('Installer: Menyimpan data toko ke session.');
        session($request->only(['store_name', 'store_address', 'store_phone']));
        return redirect()->route('installer.admin');
    }

    /**
     * Menangani Step 3: Simpan Akun Admin
     */
    public function storeAdmin(Request $request)
    {
        Log::info('Installer: Menyimpan data admin ke session.');
        session([
            'admin_name'     => $request->name,
            'admin_email'    => $request->email,
            'admin_password' => $request->password
        ]);
        return redirect()->route('installer.install');
    }

    /**
     * Menangani Step 5: Eksekusi Final (AJAX)
     */
    public function install()
{
    // Tingkatkan limit untuk mencegah timeout saat migrasi
    set_time_limit(300);
    ini_set('memory_limit', '512M');

    Log::info('Installer: Memulai proses migrasi dan setup final...');

    try {
        // 1. Ambil data dari Session (dengan fallback)
        $connection = session('db_connection', config('database.default'));
        $dbName     = session('db_name', env('DB_DATABASE'));
        $dbHost     = session('db_host', env('DB_HOST'));
        $dbPort     = session('db_port', env('DB_PORT'));
        $dbUser     = session('db_user', env('DB_USERNAME'));
        $dbPass     = session('db_pass', env('DB_PASSWORD'));

        if (empty($dbName)) {
            throw new Exception("Sesi database hilang. Silakan kembali ke langkah pertama.");
        }

        // 2. Override Config Runtime agar koneksi instan tersedia
        config([
            "database.default" => $connection,
            "database.connections.{$connection}.host"     => $dbHost,
            "database.connections.{$connection}.port"     => $dbPort,
            "database.connections.{$connection}.database" => ($connection === 'sqlite') ? base_path($dbName) : $dbName,
            "database.connections.{$connection}.username" => $dbUser,
            "database.connections.{$connection}.password" => $dbPass,
        ]);

        DB::purge($connection);
        DB::reconnect($connection);

        // 3. Pembersihan Database Manual (Lebih stabil dari migrate:fresh)
        Log::info('Installer: Membersihkan tabel yang ada...');
        if ($connection === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableArray = (array)$table;
                $tableName = reset($tableArray);
                DB::statement("DROP TABLE IF EXISTS `$tableName` ");
            }
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            Log::info('Installer: Database berhasil dikosongkan.');
        }

        // 4. Jalankan Migrasi
        Log::info('Installer: Menjalankan Artisan migrate...');
        $outputLog = new \Symfony\Component\Console\Output\BufferedOutput;
        
        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ], $outputLog);

        $migrationResult = $outputLog->fetch();
        Log::info('Installer: Migration Output: ' . $migrationResult);

        if ($exitCode !== 0) {
            throw new Exception("Migrasi Gagal: " . $migrationResult);
        }

        // 5. Membuat Akun Admin
        Log::info('Installer: Membuat akun admin...');
        User::create([
            'name'     => session('admin_name'),
            'email'    => session('admin_email'),
            'password' => Hash::make(session('admin_password')),
            'role'     => 'admin',
            'is_active' => true,
        ]);

        // 6. Menyimpan Data Toko
        try {
            DB::table('settings')->updateOrInsert(
                ['id' => 1],
                [
                    'store_name'    => session('store_name'),
                    'store_address' => session('store_address'),
                    'store_phone'   => session('store_phone'),
                    'updated_at'    => now()
                ]
            );
        } catch (Exception $e) {
            Log::warning('Installer: Gagal simpan settings (mungkin tabel tidak ada): ' . $e->getMessage());
        }

        // 7. Update ENV Secara Permanen & Finishing
        $this->updateEnv([
            'DB_CONNECTION' => $connection,
            'DB_HOST'       => $dbHost,
            'DB_PORT'       => $dbPort,
            'DB_DATABASE'   => $dbName,
            'DB_USERNAME'   => $dbUser,
            'DB_PASSWORD'   => $dbPass,
            'APP_NAME'      => session('store_name', 'Laravel POS'),
        ]);

        file_put_contents(storage_path('installed'), now());
        
        // Bersihkan cache agar .env baru aktif
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        Log::info('Installer: INSTALASI BERHASIL.');
        return response()->json(['success' => true]);

    } catch (Exception $e) {
        Log::error("Installer Error Utama: " . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal install: ' . $e->getMessage(),
            'debug'   => "Terjadi kesalahan di " . basename($e->getFile()) . " baris " . $e->getLine()
        ], 500);
    }
}

    protected function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $path);
            }
        }

        $content = file_get_contents($path);
        foreach ($data as $key => $value) {
            $safeValue = '"' . addslashes($value) . '"';
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$safeValue}", $content);
            } else {
                $content .= "\n{$key}={$safeValue}";
            }
        }
        file_put_contents($path, $content);
    }
}