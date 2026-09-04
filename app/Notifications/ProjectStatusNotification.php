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
        if ($this->status === ProjectStatusEnum::REVISI || $this->status === ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA) {
            $url = route('project.fill', $this->project->id);
        }

        // Jika status MENUNGGU_PENGISIAN_PELAKSANA, arahkan ke halaman fill
        if ($this->status === ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA) {
            $url = route('project.fill', $this->project->id);
        }

        // Jika status approval, arahkan ke halaman approve
        if ($this->status->isApprovalStatus()) {
            $url = route('project.approve', $this->project->id);
        }

        return [
            'project_id' => $this->project->id,
            'project_code' => $this->project->code,
            'project_title' => $this->project->title,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'message' => $message,
            'url' => $url,
            'type' => $this->getNotificationType(),
            'created_at' => now()->toDateTimeString(),
        ];
    }

    private function getMessage(): string
    {
        $projectCode = $this->project->code;
        $projectTitle = $this->project->title;

        // Jika revisi, tambahkan catatan revisi
        if ($this->status === ProjectStatusEnum::REVISI) {
            $notes = $this->project->revisi_notes ?? 'Tidak ada catatan revisi.';
            return "📝 Proyek **{$projectCode}** - {$projectTitle} memerlukan revisi.\n\n📌 **Catatan Revisi:**\n{$notes}";
        }

        return match($this->status) {
            // Comercil Flow
            ProjectStatusEnum::DRAFT_INISIASI => "📋 Proyek baru **{$projectCode}** - {$projectTitle} telah diinisiasi dan menunggu review Anda.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL => "📌 Proyek **{$projectCode}** - {$projectTitle} telah direview Dept Head Comercil. Mohon review oleh Division Head Comercil.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_COMERCIL => "✅ Proyek **{$projectCode}** - {$projectTitle} telah disetujui Division Head Comercil. Silakan isi data proyek.",
            
            // Pelaksana Flow
            ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA => "✏️ Anda diminta untuk mengisi data proyek **{$projectCode}** - {$projectTitle}.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA => "📌 Data proyek **{$projectCode}** - {$projectTitle} telah diisi. Mohon review oleh Dept Head Pelaksana.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PELAKSANA => "📌 Proyek **{$projectCode}** - {$projectTitle} telah direview Dept Head Pelaksana. Mohon review oleh Division Head Pelaksana.",
            
            // PCCM Flow
            ProjectStatusEnum::REVIEW_PCCM => "🔍 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh PCCM Staff.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PCCM => "📌 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh Dept Head PCCM.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PCCM => "📌 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh Division Head PCCM.",
            
            // Finance Flow
            ProjectStatusEnum::REVIEW_FINANCE => "💰 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh Finance Staff.",
            ProjectStatusEnum::REVIEW_DEPT_HEAD_FINANCE => "📌 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh Dept Head Finance.",
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_FINANCE => "📌 Proyek **{$projectCode}** - {$projectTitle} menunggu review oleh Division Head Finance.",
            
            // Direksi
            ProjectStatusEnum::REVIEW_DIREKSI => "🏛️ Proyek **{$projectCode}** - {$projectTitle} menunggu approval Direksi.",
            
            // Final
            ProjectStatusEnum::RILIS => "🎉 **Project Charter {$projectCode}** - {$projectTitle} telah **RILIS**!",
            ProjectStatusEnum::DIBATALKAN => "❌ Proyek **{$projectCode}** - {$projectTitle} telah dibatalkan.",
            
            default => "📢 Status proyek **{$projectCode}** berubah menjadi **{$this->status->label()}**.",
        };
    }

    private function getNotificationType(): string
    {
        return match($this->status) {
            ProjectStatusEnum::RILIS => 'success',
            ProjectStatusEnum::REVISI => 'warning',
            ProjectStatusEnum::DIBATALKAN => 'danger',
            ProjectStatusEnum::DRAFT_INISIASI => 'info',
            default => 'info',
        };
    }
}