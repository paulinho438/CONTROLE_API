<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {--password=admin123}';
    protected $description = 'Reseta a senha do usuário admin';

    public function handle()
    {
        $password = $this->option('password');
        
        $user = User::where('login', 'admin')->first();
        
        if (!$user) {
            // Criar usuário admin se não existir
            $userId = DB::table('users')->insertGetId([
                'login' => 'admin',
                'nome_completo' => 'Administrador',
                'email' => 'admin@rialma.com.br',
                'password' => Hash::make($password),
                'status' => 'A',
                'created_at' => DB::raw('GETDATE()'),
                'updated_at' => DB::raw('GETDATE()')
            ]);
            
            $user = User::find($userId);
            $this->info('Usuário admin criado!');
        } else {
            // Atualizar senha
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($password),
                    'status' => 'A', // Garantir que está ativo
                    'updated_at' => DB::raw('GETDATE()')
                ]);
            
            $this->info('Senha do usuário admin atualizada!');
        }
        
        // Garantir que está associado ao grupo Administrador
        $adminGroup = DB::table('permgroups')->where('name', 'Administrador')->first();
        if ($adminGroup) {
            $exists = DB::table('permgroup_user')
                ->where('user_id', $user->id)
                ->where('permgroup_id', $adminGroup->id)
                ->exists();
            
            if (!$exists) {
                DB::table('permgroup_user')->insert([
                    'user_id' => $user->id,
                    'permgroup_id' => $adminGroup->id
                ]);
                $this->info('Usuário associado ao grupo Administrador!');
            }
        }
        
        $this->info('');
        $this->info('========================================');
        $this->info('Credenciais de acesso:');
        $this->info('Login: admin');
        $this->info('Senha: ' . $password);
        $this->info('========================================');
        
        return 0;
    }
}

