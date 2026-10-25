<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // PUT/PATCH: field boleh dikirim sebagian (sometimes), POST: wajib lengkap.
        $required = $this->isMethod('post') ? 'required' : 'sometimes|required';

        return [
            'judul'      => "$required|string|max:150",
            'tanggal'    => "$required|date_format:Y-m-d",
            'waktu'      => 'nullable|date_format:H:i',
            'lokasi'     => "$required|string|max:150",
            'keterangan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'      => 'Judul kegiatan wajib diisi.',
            'tanggal.required'    => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'waktu.date_format'   => 'Format waktu harus HH:MM.',
            'lokasi.required'     => 'Lokasi kegiatan wajib diisi.',
        ];
    }
}
