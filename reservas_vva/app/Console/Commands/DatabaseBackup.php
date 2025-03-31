<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;



class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

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
        $config = \Config::get('database.connections.mysql');

        $carpeta="";

        switch (request()->slug_instalacion){
            case'vvadecordoba':
                $carpeta="vva";
                break;

            case'villafranca-de-cordoba':
                $carpeta="villafranca";
            break;

            case'manager':
                $carpeta="manager";
                break;

                case'la-guijarrosa':
                    $carpeta="guijarrosa";
                break;

                case'santaella':
                    $carpeta="santaella";
                break;



        }

        $filename = "backup-".request()->slug_instalacion."-". Carbon::now()->format("Y-m-d_H-i-s") . ".gz";

        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . $config['password'] . " --host=" . env('DB_HOST') . " " . $config['database'] . "  | gzip > " . storage_path() . "/app/backup/" . $filename;

        $returnVar = NULL;
        $output  = NULL;

        exec($command, $output, $returnVar);
        $disk = Storage::build([
            'driver' => 'ftp',
            'host' => "tallercasa.duckdns.org",
            'username' => "ftpuser",
            'password' => "2016Taller#",
            'port' => 21,
            'ssl' => false,
            'passive' => true,
            'root' => '/copiasautomaticasbd/reservas/'.$carpeta.'', ]);

        $disk->put($filename, fopen(storage_path() . "/app/backup/" . $filename, 'r+'));

        $nombre_archivo= "/app/backup/" . $filename;


        unlink(storage_path($nombre_archivo));



    }
}
