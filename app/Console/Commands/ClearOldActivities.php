<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Activity;

class ClearOldActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activities:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus log aktivitas yang sudah lewat hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Activity::whereDate('created_at', '<', today())->delete();

        if ($count > 0) {
            $this->info("✅ Berhasil menghapus {$count} log aktivitas lama.");
        } else {
            $this->info("✨ Tidak ada log aktivitas lama yang perlu dihapus.");
        }

        return 0;
    }
}
