<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SPTUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow editing if SPT is in draft status and user owns it or is admin
        $spt = $this->route('spt');

        if ($spt->status !== 'draft') {
            return false;
        }

        return $this->user()->id === $spt->user_id || $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'purpose' => 'required|string|min:10',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
            'cost_types.*' => 'required|string|in:transport,daily,accommodation,other',
            'cost_amounts.*' => 'required|numeric|min:0',
            'cost_descriptions.*' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul perjalanan dinas wajib diisi.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'purpose.required' => 'Maksud perjalanan dinas wajib diisi.',
            'purpose.min' => 'Maksud perjalanan dinas minimal 10 karakter.',
            'destination.required' => 'Tempat tujuan wajib diisi.',
            'destination.max' => 'Tempat tujuan tidak boleh lebih dari 255 karakter.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'cost_types.*.required' => 'Jenis biaya wajib dipilih.',
            'cost_types.*.in' => 'Jenis biaya tidak valid.',
            'cost_amounts.*.required' => 'Jumlah biaya wajib diisi.',
            'cost_amounts.*.numeric' => 'Jumlah biaya harus berupa angka.',
            'cost_amounts.*.min' => 'Jumlah biaya tidak boleh kurang dari 0.',
            'cost_descriptions.*.max' => 'Deskripsi biaya tidak boleh lebih dari 255 karakter.',
            'notes.max' => 'Catatan tambahan tidak boleh lebih dari 1000 karakter.',
        ];
    }
}