<?php

namespace App\Console\Commands;

use App\Models\EmployeeAttendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MigrateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $directory = 'public/storage/attendees';

        // // Get all files in the directory
        // $files = glob($directory . '/*');
        // $totalFiles = count($files);
        // $currentFile = 1;
        // foreach ($files as $key => $file) {
        //     Storage::disk('vultr')->put("public/storage/attendees/" . basename($file), file_get_contents($file));

        //     // Calculate and display progress
        //     $percentage = round(($currentFile / $totalFiles) * 100, 2);
        //     echo "Progress: $percentage% \r"; // \r is a carriage return to overwrite the line
        //     flush(); // Flush the output buffer to immediately display the echo
        //     Log::info("Progress: $key%");
        //     $currentFile++;
        // }
        // echo "Progress: 100% \n"; // Ensure completion message is shown
        //where 1 tahun yang lalu
        // $employeeAttendances = EmployeeAttendance::where('created_at', '<', now()->subYear())->get();
        // $totalFiles = count($employeeAttendances);
        $waktu_dan_tanggal = date("Y-m-d H:i:s", 1678078588);
        dd($waktu_dan_tanggal);
    }
}
