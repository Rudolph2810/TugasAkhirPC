<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectInitiationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'comercil' || auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'projectCode' => 'required|string|unique:projects,code',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'businessSegmentId' => 'required|exists:business_segments,id',
            'location' => 'required|string',
            'startDate' => 'required|date|before:endDate',
            'endDate' => 'required|date|after:startDate',
            'contractValue' => 'required|numeric|min:0',
            'attachments.*' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'projectCode.required' => 'Kode proyek wajib diisi.',
            'projectCode.unique' => 'Kode proyek sudah digunakan.',
            'title.required' => 'Judul pekerjaan wajib diisi.',
            'client.required' => 'Nama client wajib diisi.',
            'businessSegmentId.required' => 'Segmen bisnis wajib dipilih.',
            'location.required' => 'Lokasi pekerjaan wajib diisi.',
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'endDate.required' => 'Tanggal selesai wajib diisi.',
            'startDate.before' => 'Tanggal mulai harus sebelum tanggal selesai.',
            'endDate.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'contractValue.required' => 'Nilai kontrak wajib diisi.',
            'contractValue.min' => 'Nilai kontrak tidak boleh negatif.',
            'attachments.*.mimes' => 'File lampiran harus berformat PDF.',
            'attachments.*.max' => 'Ukuran file lampiran maksimal 5MB.',
        ];
    }
}