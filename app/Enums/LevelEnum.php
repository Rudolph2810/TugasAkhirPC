<?php

namespace App\Enums;

enum LevelEnum: string
{
    case STAFF = 'staff';
    case DEPARTMENT_HEAD = 'department_head';
    case DIVISION_HEAD = 'division_head';

    public function label(): string
    {
        return match($this) {
            self::STAFF => 'Staff',
            self::DEPARTMENT_HEAD => 'Department Head',
            self::DIVISION_HEAD => 'Division Head',
        };
    }

    public function order(): int
    {
        return match($this) {
            self::STAFF => 1,
            self::DEPARTMENT_HEAD => 2,
            self::DIVISION_HEAD => 3,
        };
    }

    /**
     * Get levels for specific role
     */
    public static function getLevelsForRole(string $role): array
    {
        // Role yang membutuhkan level
        $rolesWithLevel = ['comercil', 'pelaksana', 'pccm', 'finance'];
        
        if (in_array($role, $rolesWithLevel)) {
            return self::cases();
        }
        
        // Role lain tidak punya level
        return [];
    }

    /**
     * Check if role requires level
     */
    public static function roleRequiresLevel(string $role): bool
    {
        return in_array($role, ['comercil', 'pelaksana', 'pccm', 'finance']);
    }

    /**
     * Get department head level for a role
     */
    public static function getDepartmentHead(): self
    {
        return self::DEPARTMENT_HEAD;
    }

    /**
     * Get division head level for a role
     */
    public static function getDivisionHead(): self
    {
        return self::DIVISION_HEAD;
    }

    /**
     * Get staff level for a role
     */
    public static function getStaff(): self
    {
        return self::STAFF;
    }
}