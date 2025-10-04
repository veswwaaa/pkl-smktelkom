<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;

class AddActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:activity {title} {description} {type=info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan activity log secara manual';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title = $this->argument('title');
        $description = $this->argument('description');
        $type = $this->argument('type');

        // Validasi type
        $validTypes = ['login', 'create', 'update', 'delete', 'info', 'warning', 'success'];
        if (!in_array($type, $validTypes)) {
            $this->error("Type tidak valid! Gunakan salah satu: " . implode(', ', $validTypes));
            return 1;
        }

        // Simpan activity menggunakan helper
        $activity = logActivity($type, $title, $description);

        $this->info("✅ Activity berhasil ditambahkan!");
        $this->line("📝 Title: {$activity->title}");
        $this->line("📄 Description: {$activity->description}");
        $this->line("🏷️  Type: {$activity->type}");
        $this->line("👤 Username: {$activity->username}");
        $this->line("⏰ Time: {$activity->created_at->format('d M Y H:i:s')}");

        return 0;
    }
}
