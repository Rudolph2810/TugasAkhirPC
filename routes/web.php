<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\Project\ProjectList;
use App\Livewire\Project\InitiationForm;
use App\Livewire\Project\FillingForm;
use App\Livewire\Project\DetailPage;
use App\Livewire\Approval\ApprovalReviewPage;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\MasterDataManagement;
use App\Livewire\Admin\LogoSetting;
use App\Livewire\Rkap\RkapImportExport;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminDashboard;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================
// 1. GUEST ROUTES (Tidak perlu login)
// ============================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// ============================================================
// 2. AUTH ROUTES (Wajib login)
// ============================================================
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ============================================================
    // 3. DASHBOARD ROUTES
    // ============================================================
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', ProjectList::class)->name('dashboard');

    // ============================================================
    // 4. PROJECT ROUTES
    // ============================================================
    
    // 4.1 Initiation Project (Comercil & Admin)
    Route::get('/project/initiate', InitiationForm::class)
        ->name('project.initiate')
        ->middleware('role:comercil,admin');
    // 4.2 Fill Project Data (Pelaksana & Admin)
    Route::get('/project/fill/{project}', FillingForm::class)
        ->name('project.fill')
        ->middleware('role:pelaksana,admin');

    // 4.3 Project Detail (Semua user)
    Route::get('/project/{project}', DetailPage::class)
        ->name('project.detail');

    // 4.4 Project Approval (Approver)
    Route::get('/project/{project}/approve', ApprovalReviewPage::class)
        ->name('project.approve');

    // 4.5 Project RKAP (Pelaksana & Admin)
    Route::get('/project/{project}/rkap', RkapImportExport::class)
        ->name('project.rkap')
        ->middleware('role:pelaksana,admin');

    // ============================================================
    // 5. ATTACHMENT ROUTES
    // ============================================================
    
    // 5.1 Download Attachment
    Route::get('/project/attachment/download/{attachment}', function ($id) {
        $attachment = App\Models\ProjectAttachment::findOrFail($id);
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download($attachment->file_path, $attachment->original_filename);
        }
        abort(404, 'File tidak ditemukan.');
    })->name('project.attachment.download');

    // 5.2 Preview Attachment
    Route::get('/project/attachment/preview/{attachment}', function ($id) {
        $attachment = App\Models\ProjectAttachment::findOrFail($id);
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            return response()->file(Storage::disk('public')->path($attachment->file_path));
        }
        abort(404, 'File tidak ditemukan.');
    })->name('project.attachment.preview');

    // ============================================================
    // 6. NOTIFICATION ROUTES
    // ============================================================
    
    // 6.1 Mark all notifications as read
    Route::get('/notifications/read/all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.read.all');

    // 6.2 Mark single notification as read
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.read');

    // ============================================================
    // 7. ADMIN ROUTES (Hanya Admin)
    // ============================================================
    // Admin Dashboard

    Route::prefix('admin')
        ->middleware(['role:admin'])
        ->name('admin.')
        ->group(function () {
            
        // a. Admin Dashboard
        Route::get('/dashboard', AdminDashboard::class)
            ->name('admindashboard');
            
            // 7.1 User Management
        Route::get('/users', UserManagement::class)
            ->name('users');

        // 7.2 Master Data Management
        Route::get('/master-data', MasterDataManagement::class)
            ->name('master-data');

        // 7.3 Logo Setting
        Route::get('/logo', LogoSetting::class)
            ->name('logo');

        // ============================================================
        // 8. ADDITIONAL ADMIN ROUTES (Optional - bisa ditambahkan)
        // ============================================================
        
        // 8.1 Project Management (Admin melihat semua proyek)
        Route::get('/projects', function () {
            return view('admin.projects');
        })->name('projects');

        // 8.2 System Settings
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');

        // 8.3 Audit Log
        Route::get('/audit-log', function () {
            return view('admin.audit-log');
        })->name('audit-log');

        // 8.4 Reports
        Route::get('/reports', function () {
            return view('admin.reports');
        })->name('reports');
    });

    // ============================================================
    // 9. PDF SURAT RILIS (Download)
    // ============================================================
    Route::get('/project/{project}/download-release', function ($id) {
        $project = App\Models\Project::findOrFail($id);
        if ($project->status !== 'rilis') {
            session()->flash('error', 'Project Charter belum rilis.');
            return redirect()->back();
        }
        
        $service = new App\Services\PdfReleaseService();
        return $service->downloadSuratRilis($project);
    })->name('project.download-release');

    // ============================================================
    // 10. EXPORT RKAP (Excel)
    // ============================================================
    Route::get('/project/{project}/export-rkap', function ($id) {
        $project = App\Models\Project::findOrFail($id);

        if (!\Illuminate\Support\Facades\Gate::allows('exportRkap', $project)) {
            abort(403, 'Anda tidak memiliki akses download RKAP untuk proyek ini.');
        }

        return (new App\Exports\RkapExport($id))->download('RKAP_' . $project->code . '.xlsx');
    })->name('project.export-rkap');

    // ============================================================
    // 11. DOWNLOAD RKAP TEMPLATE
    // ============================================================
    Route::get('/download/rkap-template', function () {
        return (new App\Exports\RkapTemplateExport())->download('template_rkap.xlsx');
    })->name('download.rkap-template');

    // ============================================================
    // 12. PROFILE ROUTES (Optional)
    // ============================================================
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::post('/profile/update', function () {
        // Update profile logic
    })->name('profile.update');

    Route::post('/profile/change-password', function () {
        // Change password logic
    })->name('profile.change-password');

    // ============================================================
    // 13. API ROUTES (Optional - untuk AJAX)
    // ============================================================
    Route::prefix('api')->name('api.')->group(function () {
        
        // 13.1 Get users by role (untuk dropdown)
        Route::get('/users/by-role/{role}', function ($role) {
            return App\Models\User::where('role', $role)
                ->where('is_active', true)
                ->select('id', 'name', 'nip')
                ->get();
        })->name('users.by-role');

        // 13.2 Get divisions by department
        Route::get('/divisions/by-department/{department}', function ($department) {
            return App\Models\Division::where('department_id', $department)
                ->where('is_active', true)
                ->select('id', 'name')
                ->get();
        })->name('divisions.by-department');

        // 13.3 Get project status
        Route::get('/project/{project}/status', function ($id) {
            $project = App\Models\Project::findOrFail($id);
            return response()->json([
                'status' => $project->status,
                'label' => $project->status_label,
                'badge' => $project->status_badge_color,
                'is_complete' => $project->isComplete(),
            ]);
        })->name('project.status');

        // 13.4 Get notification count
        Route::get('/notifications/count', function () {
            return response()->json([
                'unread' => auth()->user()->unreadNotifications()->count(),
                'total' => auth()->user()->notifications()->count(),
            ]);
        })->name('notifications.count');
    });
});

// ============================================================
// 14. FALLBACK ROUTE (404)
// ============================================================
Route::fallback(function () {
    return view('errors.404');
});