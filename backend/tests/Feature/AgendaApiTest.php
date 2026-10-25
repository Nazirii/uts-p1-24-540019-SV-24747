<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AgendaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_akses_tanpa_token_ditolak_401(): void
    {
        $this->getJson('/api/agendas')->assertStatus(401);
    }

    public function test_tambah_agenda_berhasil_201(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/agendas', [
            'judul'   => 'Seminar DevOps',
            'tanggal' => '2026-11-01',
            'waktu'   => '13:00',
            'lokasi'  => 'Aula SV UGM',
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.judul', 'Seminar DevOps');

        $this->assertDatabaseHas('agendas', ['judul' => 'Seminar DevOps']);
    }

    public function test_tambah_agenda_tanpa_judul_gagal_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/agendas', [
            'tanggal' => '2026-11-01',
            'lokasi'  => 'Aula SV UGM',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    public function test_tampil_ubah_hapus_agenda(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $agenda = Agenda::factory()->create();

        $this->getJson("/api/agendas/{$agenda->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $agenda->id);

        $this->putJson("/api/agendas/{$agenda->id}", ['judul' => 'Judul Baru'])
            ->assertStatus(200)
            ->assertJsonPath('data.judul', 'Judul Baru');

        $this->deleteJson("/api/agendas/{$agenda->id}")->assertStatus(200);
        $this->assertDatabaseMissing('agendas', ['id' => $agenda->id]);
    }
}
