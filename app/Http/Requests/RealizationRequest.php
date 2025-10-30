<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RealizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sppd_id' => 'required|exists:sppds,id',
            'type' => 'required|string|in:transport,daily,accommodation,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sppd_id.required' => 'SPPD tidak valid.',
            'sppd_id.exists' => 'SPPD tidak ditemukan.',
            'type.required' => 'Jenis biaya wajib dipilih.',
            'type.in' => 'Jenis biaya tidak valid.',
            'amount.required' => 'Jumlah biaya wajib diisi.',
            'amount.numeric' => 'Jumlah biaya harus berupa angka.',
            'amount.min' => 'Jumlah biaya tidak boleh kurang dari 0.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            'file_path.mimes' => 'File harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_path.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
            'notes.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
        ];
    }
}