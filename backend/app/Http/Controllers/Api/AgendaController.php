<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\JsonResponse;

class AgendaController extends Controller
{
    /** GET /api/agendas — tampil semua */
    public function index(): JsonResponse
    {
        $agendas = Agenda::orderBy('tanggal')->orderBy('waktu')->get();

        return response()->json([
            'message' => 'Daftar agenda kegiatan',
            'data'    => $agendas,
        ], 200);
    }

    /** POST /api/agendas — tambah */
    public function store(AgendaRequest $request): JsonResponse
    {
        $agenda = Agenda::create($request->validated());

        return response()->json([
            'message' => 'Agenda berhasil ditambahkan',
            'data'    => $agenda,
        ], 201);
    }

    /** GET /api/agendas/{agenda} — tampil satu */
    public function show(Agenda $agenda): JsonResponse
    {
        return response()->json([
            'message' => 'Detail agenda kegiatan',
            'data'    => $agenda,
        ], 200);
    }

    /** PUT/PATCH /api/agendas/{agenda} — ubah */
    public function update(AgendaRequest $request, Agenda $agenda): JsonResponse
    {
        $agenda->update($request->validated());

        return response()->json([
            'message' => 'Agenda berhasil diperbarui',
            'data'    => $agenda->fresh(),
        ], 200);
    }

    /** DELETE /api/agendas/{agenda} — hapus */
    public function destroy(Agenda $agenda): JsonResponse
    {
        $agenda->delete();

        return response()->json([
            'message' => 'Agenda berhasil dihapus',
        ], 200);
    }
}
