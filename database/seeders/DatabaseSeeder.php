<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Division;
use App\Models\BusinessSegment;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. CREATE DEPARTMENTS
        // ============================================================
        $this->createDepartments();

        // ============================================================
        // 2. CREATE DIVISIONS
        // ============================================================
        $this->createDivisions();

        // ============================================================
        // 3. CREATE BUSINESS SEGMENTS
        // ============================================================
        $this->createBusinessSegments();

        // ============================================================
        // 4. CREATE USERS
        // ============================================================
        $this->createUsers();

        // ============================================================
        // 5. OUTPUT INFO
        // ============================================================
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📋 Login Credentials:');
        $this->command->info('  Admin       : ADMIN001 / password123');
        $this->command->info('  Direksi     : DIR001 / password123');
        $this->command->info('  Comercil    : COM001 / password123');
        $this->command->info('  Pelaksana   : PEL001 / password123');
        $this->command->info('  PCCM        : PCCM001 / password123');
        $this->command->info('  Finance     : FIN001 / password123');
        $this->command->info('');
        $this->command->info('📌 Department Heads & Division Heads:');
        $this->command->info('  Comercil Dept Head   : COM002 / password123');
        $this->command->info('  Comercil Div Head    : COM003 / password123');
        $this->command->info('  Pelaksana Dept Head  : PEL002 / password123');
        $this->command->info('  Pelaksana Div Head   : PEL003 / password123');
        $this->command->info('  PCCM Dept Head       : PCCM002 / password123');
        $this->command->info('  PCCM Div Head        : PCCM003 / password123');
        $this->command->info('  Finance Dept Head    : FIN002 / password123');
        $this->command->info('  Finance Div Head     : FIN003 / password123');
    }

    // ============================================================
    // CREATE DEPARTMENTS
    // ============================================================
    private function createDepartments(): void
    {
        $departments = [
            ['name' => 'Comercial', 'code' => 'COM', 'description' => 'Departemen Comercial'],
            ['name' => 'Project Management', 'code' => 'PM', 'description' => 'Departemen Manajemen Proyek'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Departemen Keuangan'],
            ['name' => 'PCCM', 'code' => 'PCCM', 'description' => 'Departemen PCCM'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }

    // ============================================================
    // CREATE DIVISIONS
    // ============================================================
    private function createDivisions(): void
    {
        $divisions = [
            ['department_id' => 1, 'name' => 'Comercial Division', 'code' => 'COM-DIV'],
            ['department_id' => 2, 'name' => 'Project Management Division', 'code' => 'PM-DIV'],
            ['department_id' => 3, 'name' => 'Finance Division', 'code' => 'FIN-DIV'],
            ['department_id' => 4, 'name' => 'PCCM Division', 'code' => 'PCCM-DIV'],
        ];

        foreach ($divisions as $div) {
            Division::create($div);
        }
    }

    // ============================================================
    // CREATE BUSINESS SEGMENTS
    // ============================================================
    private function createBusinessSegments(): void
    {
        $segments = [
            ['name' => 'Konstruksi', 'code' => 'KON'],
            ['name' => 'Telekomunikasi', 'code' => 'TEL'],
            ['name' => 'Energi', 'code' => 'ENG'],
            ['name' => 'Infrastruktur', 'code' => 'INF'],
            ['name' => 'Teknologi Informasi', 'code' => 'TI'],
        ];

        foreach ($segments as $segment) {
            BusinessSegment::create($segment);
        }
    }

    // ============================================================
    // CREATE USERS
    // ============================================================
    private function createUsers(): void
    {
        $users = [
            // Admin
            [
                'nip' => 'ADMIN001',
                'name' => 'Administrator',
                'email' => 'admin@system.com',
                'role' => RoleEnum::ADMIN->value,
                'level' => null,
                'department_id' => null,
                'division_id' => null,
            ],
            
            // Direksi
            [
                'nip' => 'DIR001',
                'name' => 'Direksi',
                'email' => 'direksi@system.com',
                'role' => RoleEnum::DIREKSI->value,
                'level' => null,
                'department_id' => null,
                'division_id' => null,
            ],
            
            // Comercil
            [
                'nip' => 'COM001',
                'name' => 'Comercil Staff',
                'email' => 'comercil@system.com',
                'role' => RoleEnum::COMERCIL->value,
                'level' => LevelEnum::STAFF->value,
                'department_id' => 1,
                'division_id' => 1,
            ],
            [
                'nip' => 'COM002',
                'name' => 'Comercil Dept Head',
                'email' => 'comercil.dept@system.com',
                'role' => RoleEnum::COMERCIL->value,
                'level' => LevelEnum::DEPARTMENT_HEAD->value,
                'department_id' => 1,
                'division_id' => 1,
            ],
            [
                'nip' => 'COM003',
                'name' => 'Comercil Div Head',
                'email' => 'comercil.div@system.com',
                'role' => RoleEnum::COMERCIL->value,
                'level' => LevelEnum::DIVISION_HEAD->value,
                'department_id' => 1,
                'division_id' => 1,
            ],
            
            // Pelaksana
            [
                'nip' => 'PEL001',
                'name' => 'Pelaksana Staff',
                'email' => 'pelaksana@system.com',
                'role' => RoleEnum::PELAKSANA->value,
                'level' => LevelEnum::STAFF->value,
                'department_id' => 2,
                'division_id' => 2,
            ],
            [
                'nip' => 'PEL002',
                'name' => 'Pelaksana Dept Head',
                'email' => 'pelaksana.dept@system.com',
                'role' => RoleEnum::PELAKSANA->value,
                'level' => LevelEnum::DEPARTMENT_HEAD->value,
                'department_id' => 2,
                'division_id' => 2,
            ],
            [
                'nip' => 'PEL003',
                'name' => 'Pelaksana Div Head',
                'email' => 'pelaksana.div@system.com',
                'role' => RoleEnum::PELAKSANA->value,
                'level' => LevelEnum::DIVISION_HEAD->value,
                'department_id' => 2,
                'division_id' => 2,
            ],
            
            // PCCM
            [
                'nip' => 'PCCM001',
                'name' => 'PCCM Staff',
                'email' => 'pccm@system.com',
                'role' => RoleEnum::PCCM->value,
                'level' => LevelEnum::STAFF->value,
                'department_id' => 4,
                'division_id' => 4,
            ],
            [
                'nip' => 'PCCM002',
                'name' => 'PCCM Dept Head',
                'email' => 'pccm.dept@system.com',
                'role' => RoleEnum::PCCM->value,
                'level' => LevelEnum::DEPARTMENT_HEAD->value,
                'department_id' => 4,
                'division_id' => 4,
            ],
            [
                'nip' => 'PCCM003',
                'name' => 'PCCM Div Head',
                'email' => 'pccm.div@system.com',
                'role' => RoleEnum::PCCM->value,
                'level' => LevelEnum::DIVISION_HEAD->value,
                'department_id' => 4,
                'division_id' => 4,
            ],
            
            // Finance
            [
                'nip' => 'FIN001',
                'name' => 'Finance Staff',
                'email' => 'finance@system.com',
                'role' => RoleEnum::FINANCE->value,
                'level' => LevelEnum::STAFF->value,
                'department_id' => 3,
                'division_id' => 3,
            ],
            [
                'nip' => 'FIN002',
                'name' => 'Finance Dept Head',
                'email' => 'finance.dept@system.com',
                'role' => RoleEnum::FINANCE->value,
                'level' => LevelEnum::DEPARTMENT_HEAD->value,
                'department_id' => 3,
                'division_id' => 3,
            ],
            [
                'nip' => 'FIN003',
                'name' => 'Finance Div Head',
                'email' => 'finance.div@system.com',
                'role' => RoleEnum::FINANCE->value,
                'level' => LevelEnum::DIVISION_HEAD->value,
                'department_id' => 3,
                'division_id' => 3,
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'nip' => $user['nip'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt('password123'),
                'role' => $user['role'],
                'level' => $user['level'],
                'department_id' => $user['department_id'],
                'division_id' => $user['division_id'],
                'is_active' => true,
                
            ]);
        }
    }
}