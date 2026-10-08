<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiKey;
use App\Models\User;

class GenerateApiKey extends Command
{
    protected $signature = 'api:generate-key 
                            {email : User email} 
                            {--name=CLI Generated : Key name} 
                            {--limit=100 : Daily limit}';
    protected $description = 'Generate API key untuk user tertentu';

    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $limit = (int) $this->option('limit');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan.");
            return 1;
        }

        [$plainKey, $apiKey] = ApiKey::generate($user->id, $name, $limit);

        $this->info('');
        $this->info('✅ API Key berhasil dibuat!');
        $this->info('');
        $this->line('  Owner   : ' . $user->name . ' (' . $user->email . ')');
        $this->line('  Name    : ' . $apiKey->name);
        $this->line('  Limit   : ' . $apiKey->daily_limit . ' request/hari');
        $this->line('');
        $this->warn('  ⚠️  SIMPAN KEY INI. Tidak akan ditampilkan lagi!');
        $this->info('  ' . $plainKey);
        $this->info('');

        return 0;
    }
}
