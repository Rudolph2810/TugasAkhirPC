<?php

namespace App\Enums;

enum ApprovalActionEnum: string
{
    case APPROVE = 'approve';
    case CANCEL = 'cancel';
    case REVISI = 'revisi';

    public function label(): string
    {
        return match($this) {
            self::APPROVE => 'Disetujui',
            self::CANCEL => 'Dibatalkan',
            self::REVISI => 'Revisi',
        };
    }
}