<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BusinessSegment;
use Illuminate\Support\Facades\Auth;

class ProjectInitiationController extends Controller
{
    public function create()
    {
        try {
            // Cek akses
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            if ($user->role !== 'comercil' && $user->role !== 'admin') {
                abort(403, 'Anda tidak memiliki akses.');
            }

            $businessSegments = BusinessSegment::where('is_active', true)->orderBy('name')->get();
            
            // Generate kode proyek
            $year = date('Y');
            $lastProject = Project::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            if ($lastProject) {
                $lastNumber = intval(substr($lastProject->code, -4));
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }
            $projectCode = 'PROJ-' . $year . '-' . $newNumber;

            return view('project.initiate', compact('businessSegments', 'projectCode'));
            
        } catch (\Exception $e) {
            // Jika error, tampilkan di log dan halaman error
            \Log::error('ProjectInitiationController::create error: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'projectCode' => 'required|string|unique:projects,code|max:50',
                'title' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'businessSegmentId' => 'required|exists:business_segments,id',
                'location' => 'required|string|max:500',
                'startDate' => 'required|date|before_or_equal:endDate',
                'endDate' => 'required|date|after_or_equal:startDate',
                'contractValue' => 'required|numeric|min:0',
            ]);

            // Simpan proyek
            $project = Project::create([
                'code' => $request->projectCode,
                'title' => $request->title,
                'client' => $request->client,
                'business_segment_id' => $request->businessSegmentId,
                'location' => $request->location,
                'start_date' => $request->startDate,
                'end_date' => $request->endDate,
                'contract_value' => $request->contractValue,
                'status' => 'draft_inisiasi',
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('project.detail', $project->id)
                ->with('success', 'Proyek berhasil diinisiasi!');
                
        } catch (\Exception $e) {
            \Log::error('ProjectInitiationController::store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}