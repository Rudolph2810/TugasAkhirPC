<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case COMERCIL = 'comercil';
    case PELAKSANA = 'pelaksana';
    case PCCM = 'pccm';
    case FINANCE = 'finance';
    case DIREKSI = 'direksi';
    case PENDING = 'pending';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::COMERCIL => 'Comercil',
            self::PELAKSANA => 'Pelaksana Proyek',
            self::PCCM => 'PCCM',
            self::FINANCE => 'Finance',
            self::DIREKSI => 'Direksi',
            self::PENDING => 'Pending / Unassigned',
        };
    }

    /**
     * Get roles that require level
     */
    public static function getRolesWithLevel(): array
    {
        return [
            self::COMERCIL->value,
            self::PELAKSANA->value,
            self::PCCM->value,
            self::FINANCE->value,
        ];
    }

    /**
     * Check if role requires level
     */
    public static function roleRequiresLevel(string $role): bool
    {
        return in_array($role, self::getRolesWithLevel());
    }

    /**
     * Get roles that can approve
     */
    public static function getApproverRoles(): array
    {
        return [
            self::COMERCIL->value,
            self::PELAKSANA->value,
            self::PCCM->value,
            self::FINANCE->value,
            self::DIREKSI->value,
        ];
    }
}