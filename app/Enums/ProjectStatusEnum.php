<?php

namespace App\Enums;

enum ProjectStatusEnum: string
{
    case DRAFT_INISIASI = 'draft_inisiasi';
    case REVIEW_DEPT_HEAD_COMERCIL = 'review_dept_head_comercil';
    case REVIEW_DIVISION_HEAD_COMERCIL = 'review_division_head_comercil';
    case MENUNGGU_PENGISIAN_PELAKSANA = 'menunggu_pengisian_pelaksana';
    case REVIEW_DEPT_HEAD_PELAKSANA = 'review_dept_head_pelaksana';
    case REVIEW_DIVISION_HEAD_PELAKSANA = 'review_division_head_pelaksana';
    case REVIEW_PCCM = 'review_pccm';
    case REVIEW_DEPT_HEAD_PCCM = 'review_dept_head_pccm';
    case REVIEW_DIVISION_HEAD_PCCM = 'review_division_head_pccm';
    case REVIEW_FINANCE = 'review_finance';
    case REVIEW_DEPT_HEAD_FINANCE = 'review_dept_head_finance';
    case REVIEW_DIVISION_HEAD_FINANCE = 'review_division_head_finance';
    case REVIEW_DIREKSI = 'review_direksi';
    case RILIS = 'rilis';
    case REVISI = 'revisi';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match($this) {
            self::DRAFT_INISIASI => 'Draft Inisiasi',
            self::REVIEW_DEPT_HEAD_COMERCIL => 'Review Dept Head Comercil',
            self::REVIEW_DIVISION_HEAD_COMERCIL => 'Review Division Head Comercil',
            self::MENUNGGU_PENGISIAN_PELAKSANA => 'Menunggu Pengisian Pelaksana',
            self::REVIEW_DEPT_HEAD_PELAKSANA => 'Review Dept Head Pelaksana',
            self::REVIEW_DIVISION_HEAD_PELAKSANA => 'Review Division Head Pelaksana',
            self::REVIEW_PCCM => 'Review PCCM',
            self::REVIEW_DEPT_HEAD_PCCM => 'Review Dept Head PCCM',
            self::REVIEW_DIVISION_HEAD_PCCM => 'Review Division Head PCCM',
            self::REVIEW_FINANCE => 'Review Finance',
            self::REVIEW_DEPT_HEAD_FINANCE => 'Review Dept Head Finance',
            self::REVIEW_DIVISION_HEAD_FINANCE => 'Review Division Head Finance',
            self::REVIEW_DIREKSI => 'Review Direksi',
            self::RILIS => 'Rilis',
            self::REVISI => 'Revisi',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::DRAFT_INISIASI => 'gray',
            self::REVIEW_DEPT_HEAD_COMERCIL,
            self::REVIEW_DIVISION_HEAD_COMERCIL,
            self::REVIEW_DEPT_HEAD_PELAKSANA,
            self::REVIEW_DIVISION_HEAD_PELAKSANA,
            self::REVIEW_PCCM,
            self::REVIEW_DEPT_HEAD_PCCM,
            self::REVIEW_DIVISION_HEAD_PCCM,
            self::REVIEW_FINANCE,
            self::REVIEW_DEPT_HEAD_FINANCE,
            self::REVIEW_DIVISION_HEAD_FINANCE,
            self::REVIEW_DIREKSI => 'yellow',
            self::MENUNGGU_PENGISIAN_PELAKSANA => 'blue',
            self::RILIS => 'green',
            self::REVISI => 'orange',
            self::DIBATALKAN => 'red',
        };
    }

    /**
     * ✅ NEXT STATUS SETELAH APPROVE
     */
    public function nextStatus(): ?self
    {
        return match($this) {
            // Comercil Flow
            self::DRAFT_INISIASI => self::REVIEW_DEPT_HEAD_COMERCIL,
            self::REVIEW_DEPT_HEAD_COMERCIL => self::REVIEW_DIVISION_HEAD_COMERCIL,
            self::REVIEW_DIVISION_HEAD_COMERCIL => self::MENUNGGU_PENGISIAN_PELAKSANA,
            
            // Pelaksana Flow
            self::MENUNGGU_PENGISIAN_PELAKSANA => self::REVIEW_DEPT_HEAD_PELAKSANA,
            self::REVIEW_DEPT_HEAD_PELAKSANA => self::REVIEW_DIVISION_HEAD_PELAKSANA,
            self::REVIEW_DIVISION_HEAD_PELAKSANA => self::REVIEW_PCCM,
            
            // PCCM Flow
            self::REVIEW_PCCM => self::REVIEW_DEPT_HEAD_PCCM,
            self::REVIEW_DEPT_HEAD_PCCM => self::REVIEW_DIVISION_HEAD_PCCM,
            self::REVIEW_DIVISION_HEAD_PCCM => self::REVIEW_FINANCE,
            
            // Finance Flow
            self::REVIEW_FINANCE => self::REVIEW_DEPT_HEAD_FINANCE,
            self::REVIEW_DEPT_HEAD_FINANCE => self::REVIEW_DIVISION_HEAD_FINANCE,
            self::REVIEW_DIVISION_HEAD_FINANCE => self::REVIEW_DIREKSI,
            
            // Direksi Flow
            self::REVIEW_DIREKSI => self::RILIS,
            
            // Revisi
            self::REVISI => self::MENUNGGU_PENGISIAN_PELAKSANA,
            
            default => null,
        };
    }

    /**
     * ✅ GET APPROVER INFO UNTUK SETIAP STATUS
     */
    public function getApproverInfo(): ?array
    {
        return match($this) {
            self::DRAFT_INISIASI => ['role' => 'comercil', 'level' => 'staff'],
            self::REVIEW_DEPT_HEAD_COMERCIL => ['role' => 'comercil', 'level' => 'department_head'],
            self::REVIEW_DIVISION_HEAD_COMERCIL => ['role' => 'comercil', 'level' => 'division_head'],
            self::MENUNGGU_PENGISIAN_PELAKSANA => ['role' => 'pelaksana', 'level' => 'staff'],
            self::REVIEW_DEPT_HEAD_PELAKSANA => ['role' => 'pelaksana', 'level' => 'department_head'],
            self::REVIEW_DIVISION_HEAD_PELAKSANA => ['role' => 'pelaksana', 'level' => 'division_head'],
            self::REVIEW_PCCM => ['role' => 'pccm', 'level' => 'staff'],
            self::REVIEW_DEPT_HEAD_PCCM => ['role' => 'pccm', 'level' => 'department_head'],
            self::REVIEW_DIVISION_HEAD_PCCM => ['role' => 'pccm', 'level' => 'division_head'],
            self::REVIEW_FINANCE => ['role' => 'finance', 'level' => 'staff'],
            self::REVIEW_DEPT_HEAD_FINANCE => ['role' => 'finance', 'level' => 'department_head'],
            self::REVIEW_DIVISION_HEAD_FINANCE => ['role' => 'finance', 'level' => 'division_head'],
            self::REVIEW_DIREKSI => ['role' => 'direksi', 'level' => null],
            self::REVISI => ['role' => 'pelaksana', 'level' => 'staff'],
            default => null,
        };
    }

    /**
     * ✅ CEK APAKAH STATUS INI BISA DIAPPROVE
     */
    public function isApprovalStatus(): bool
    {
        return in_array($this, [
            self::REVIEW_DEPT_HEAD_COMERCIL,
            self::REVIEW_DIVISION_HEAD_COMERCIL,
            self::REVIEW_DEPT_HEAD_PELAKSANA,
            self::REVIEW_DIVISION_HEAD_PELAKSANA,
            self::REVIEW_PCCM,
            self::REVIEW_DEPT_HEAD_PCCM,
            self::REVIEW_DIVISION_HEAD_PCCM,
            self::REVIEW_FINANCE,
            self::REVIEW_DEPT_HEAD_FINANCE,
            self::REVIEW_DIVISION_HEAD_FINANCE,
            self::REVIEW_DIREKSI,
        ]);
    }
}