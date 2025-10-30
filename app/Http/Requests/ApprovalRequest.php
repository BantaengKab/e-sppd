<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $spt = $this->route('spt');

        // Only allow access to approval roles
        if (!$user->hasRole(['supervisor', 'finance', 'verifikator', 'admin'])) {
            return false;
        }

        // SPT must be in submitted status
        if ($spt->status !== 'submitted') {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'comment' => 'required_if:status,rejected|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Keputusan persetujuan wajib dipilih.',
            'status.in' => 'Keputusan persetujuan tidak valid.',
            'comment.required_if' => 'Catatan wajib diisi saat menolak SPT.',
            'comment.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
        ];
    }
}
