<?php

namespace App\Notifications;

use App\Models\Project;
use App\Enums\ProjectStatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectStatusNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $status;

    public function __construct(Project $project, ProjectStatusEnum $status)
    {
        $this->project = $project;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = $this->getMessage();
        $url = route('project.detail', $this->project->id);

        // Jika status REVISI, arahkan ke halaman fill
        if ($this->status === ProjectStatusEnum::REVISI) {
            $url = route('project.fill', $this->project->id);
        }

        return [
            'project_id' => $this->project->id,
            'project_code' => $this->project->code,
            'project_title' => $this->project->title,
            'status' => $this->status->value,
            'message' => $message,
            'url' => $url,
            'type' => $this->getNotificationType(),
        ];
    }

    private function getMessage(): string
    {
        // Jika revisi, tambahkan catatan revisi
        if ($this->status === ProjectStatusEnum::REVISI) {
            $notes = $this->project->revisi_notes ?? 'Tidak ada catatan revisi.';
            return "📝 Proyek {$this->project->code} memerlukan revisi.\n\nCatatan Revisi:\n{$notes}";
        }

        return match($this->status) {
            ProjectStatusEnum::DRAFT_INISIASI => "Proyek baru {$this->project->code} - {$this->project->title} telah diinisiasi dan menunggu review Dept Head Comercil.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL => "Proyek {$this->project->code} telah direview Dept Head Comercil. Mohon review oleh Division Head Comercil.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_COMERCIL => "Proyek {$this->project->code} telah disetujui Division Head Comercil. Silakan isi data proyek.",
            ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA => "Anda diminta untuk mengisi data proyek {$this->project->code}.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA => "Data proyek {$this->project->code} telah diisi. Mohon review oleh Dept Head Pelaksana.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PELAKSANA => "Proyek {$this->project->code} telah direview Dept Head Pelaksana. Mohon review oleh Division Head Pelaksana.",
            ProjectStatusEnum::REVIEW_PCCM => "Proyek {$this->project->code} menunggu review oleh PCCM.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PCCM => "Proyek {$this->project->code} menunggu review oleh Dept Head PCCM.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PCCM => "Proyek {$this->project->code} menunggu review oleh Division Head PCCM.",
            ProjectStatusEnum::REVIEW_FINANCE => "Proyek {$this->project->code} menunggu review oleh Finance.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_FINANCE => "Proyek {$this->project->code} menunggu review oleh Dept Head Finance.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_FINANCE => "Proyek {$this->project->code} menunggu review oleh Division Head Finance.",
            ProjectStatusEnum::REVIEW_DIREKSI => "Proyek {$this->project->code} menunggu approval Direksi.",
            ProjectStatusEnum::RILIS => "🎉 Project Charter {$this->project->code} telah RILIS!",
            ProjectStatusEnum::DIBATALKAN => "❌ Proyek {$this->project->code} telah dibatalkan.",
            default => "Status proyek {$this->project->code} berubah menjadi {$this->status->label()}.",
        };
    }

    private function getNotificationType(): string
    {
        return match($this->status) {
            ProjectStatusEnum::RILIS => 'success',
            ProjectStatusEnum::REVISI => 'warning',
            ProjectStatusEnum::DIBATALKAN => 'danger',
            default => 'info',
        };
    }
}