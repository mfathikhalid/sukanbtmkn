<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\Participant;
use App\Models\Sport;
use App\Models\User;
use App\Services\BowlingService;
use App\Services\DartService;
use App\Services\HousePointService;
use App\Services\KnockoutService;
use App\Services\LeagueFixtureService;
use App\Services\LeagueStandingService;
use App\Services\MatchResultService;
use App\Services\ScoreboardService;
use Carbon\CarbonImmutable;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TournamentEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_runs_a_complete_league_and_knockout_tournament(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        $fixtures = app(LeagueFixtureService::class);
        $results = app(MatchResultService::class);
        $knockout = app(KnockoutService::class);

        $this->assertSame(6, $fixtures->generate($sport, Gender::Male));
        $this->assertSame(0, $fixtures->generate($sport, Gender::Male));
        $eventStatus = fn (): string => app(ScoreboardService::class)
            ->eventBreakdown()
            ->first(fn (array $event) => $event['event'] === 'FIFA' && $event['category'] === 'Lelaki')['status'];
        $this->assertSame('not_started', $eventStatus());

        $leagueMatches = $this->tournamentMatches($sport, MatchStage::League);
        $scores = [[2, 0], [2, 0], [3, 1], [2, 0], [1, 0], [0, 2]];

        foreach ($leagueMatches as $index => $match) {
            $results->submit($match, ...$scores[$index]);
        }
        $this->assertSame('ongoing', $eventStatus());

        $standings = app(LeagueStandingService::class)->calculate($sport, Gender::Male);

        $this->assertSame(['Merah', 'Hijau', 'Biru', 'Kuning'], $standings->pluck('house.name')->all());
        $this->assertSame([3, 2, 1, 0], $standings->pluck('points')->all());

        $this->assertSame(2, $knockout->generateSemiFinals($sport, Gender::Male));
        $semiFinals = $this->tournamentMatches($sport, MatchStage::SemiFinal);
        $this->assertSame(['Merah', 'Kuning'], [$semiFinals[0]->homeHouse->name, $semiFinals[0]->awayHouse->name]);
        $this->assertSame(['Hijau', 'Biru'], [$semiFinals[1]->homeHouse->name, $semiFinals[1]->awayHouse->name]);

        $results->submit($semiFinals[0], 2, 0);
        $results->submit($semiFinals[1], 0, 1);

        $this->assertSame(1, $knockout->generateFinal($sport, Gender::Male));
        $this->assertSame(1, $knockout->generateThirdPlace($sport, Gender::Male));

        $final = $this->tournamentMatches($sport, MatchStage::Final)->first();
        $thirdPlace = $this->tournamentMatches($sport, MatchStage::ThirdPlace)->first();
        $results->submit($final, 3, 1);
        $results->submit($thirdPlace, 0, 2);
        $this->assertSame('complete', $eventStatus());

        $points = app(HousePointService::class)->pointsByHouse();

        $this->assertSame(10, $points[$final->home_house_id]);
        $this->assertSame(7, $points[$final->away_house_id]);
        $this->assertSame(5, $points[$thirdPlace->away_house_id]);
        $this->assertSame(3, $points[$thirdPlace->home_house_id]);
    }

    public function test_a_result_cannot_be_submitted_twice(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        app(LeagueFixtureService::class)->generate($sport, Gender::Male);
        $match = $this->tournamentMatches($sport, MatchStage::League)->first();
        $service = app(MatchResultService::class);
        $service->submit($match, 1, 0);

        $this->expectException(ValidationException::class);
        $service->submit($match, 2, 0);
    }

    public function test_an_admin_can_reset_all_matches_and_results(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => House::query()->firstOrFail()->id,
            'employee_no' => 'RESET-001',
            'name' => 'Peserta Reset',
            'gender' => Gender::Male,
        ]);
        $sport->participants()->attach($participant);
        app(LeagueFixtureService::class)->generate($sport, Gender::Male);
        $match = $this->tournamentMatches($sport, MatchStage::League)->first();
        app(MatchResultService::class)->submitWinner($match, $match->home_house_id);

        $this->actingAs(User::factory()->create())
            ->delete(route('matches.reset'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('matches', 0);
        $this->assertDatabaseCount('match_results', 0);
        $this->assertDatabaseCount('sport_registrations', 1);
    }

    public function test_the_dashboard_shows_a_reset_confirmation_dialog(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('resetMatchesModal')
            ->assertSee('Reset semua perlawanan?')
            ->assertSee('Ya, Reset Semua');
    }

    public function test_a_draw_is_not_allowed_during_the_league_stage(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        app(LeagueFixtureService::class)->generate($sport, Gender::Male);
        $match = $this->tournamentMatches($sport, MatchStage::League)->first();

        $this->expectException(ValidationException::class);
        app(MatchResultService::class)->submit($match, 1, 1);
    }

    public function test_an_admin_can_view_the_knockout_bracket(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('events.fifa'));

        $response
            ->assertOk()
            ->assertSee('FIFA')
            ->assertSee('E-Sukan FIFA')
            ->assertSee('data-event-hero="true"', false)
            ->assertSee('Round Robin')
            ->assertSee('Separuh Akhir 1')
            ->assertSee('Tempat Ketiga');
    }

    public function test_the_dart_bracket_explains_combined_team_scoring(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::factory()->create())
            ->get(route('dart.index'))
            ->assertOk()
            ->assertSee('Tiga peserta bermain sebagai satu pasukan rumah');
    }

    public function test_dart_uses_one_501_game_and_stores_the_selected_winner(): void
    {
        $this->seed(DatabaseSeeder::class);
        $dart = Sport::query()->where('name', 'Dart')->firstOrFail();
        app(LeagueFixtureService::class)->generate($dart, Gender::Male);
        $match = $this->tournamentMatches($dart, MatchStage::League)->first();

        $this->actingAs(User::factory()->create())
            ->get(route('dart.index'))
            ->assertOk()
            ->assertSee('Dart 501')
            ->assertDontSee('Best of 3 Legs')
            ->assertSee('Pilih pemenang 501')
            ->assertSee('Perlawanan Liga 1');

        try {
            app(DartService::class)->submitWinner($match, 999999);
            $this->fail('A house outside the Dart match was accepted as winner.');
        } catch (ValidationException) {
            $this->assertDatabaseMissing('match_results', ['match_id' => $match->id]);
        }

        app(DartService::class)->submitWinner($match, $match->home_house_id);
        $this->assertDatabaseHas('match_results', [
            'match_id' => $match->id,
            'home_score' => 1,
            'away_score' => 0,
            'winner_house_id' => $match->home_house_id,
        ]);
    }

    public function test_an_admin_can_generate_round_robin_from_the_knockout_page(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('events.fifa'))
            ->post(route('matches.league-fixtures', $sport), [
                'gender' => Gender::Male->value,
            ]);

        $response
            ->assertRedirect(route('events.fifa'))
            ->assertSessionHas('success');

        $this->assertCount(6, $this->tournamentMatches($sport, MatchStage::League));

        $match = $this->tournamentMatches($sport, MatchStage::League)->first();
        $this->from(route('events.fifa'))
            ->put(route('matches.update', $match), [
                'winner_house_id' => $match->home_house_id,
            ])
            ->assertRedirect(route('events.fifa'));

        $this->assertDatabaseHas('match_results', [
            'match_id' => $match->id,
            'winner_house_id' => $match->home_house_id,
            'home_score' => 1,
            'away_score' => 0,
        ]);
    }

    public function test_an_admin_can_view_the_updated_scoreboard(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('scoreboard.index'));

        $response
            ->assertOk()
            ->assertSee('Papan Skor Keseluruhan')
            ->assertSee('Pecahan Mata')
            ->assertSee('Pecahan Mata Mengikut Acara')
            ->assertSee('Jumlah Mata Keseluruhan')
            ->assertSee('Jumlah Acara')
            ->assertSee('data-live-score="true"', false)
            ->assertSee('data-live-interval="5000"', false)
            ->assertSee('Belum bermula')
            ->assertSee('FIFA')
            ->assertSee('Round Robin')
            ->assertSee('Mata Boling')
            ->assertDontSee('<pre', false);
    }

    public function test_the_public_can_view_live_scoreboard_and_knockout_without_login(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('live.index'))
            ->assertOk()
            ->assertSee('Keputusan Langsung')
            ->assertSee('Kedudukan Keseluruhan')
            ->assertSee('Pendahulu')
            ->assertSee('data-ranking-card', false)
            ->assertSee('podium-gold', false)
            ->assertSee('podium-silver', false)
            ->assertSee('podium-bronze', false)
            ->assertSee('Mata Mengikut Acara')
            ->assertSee('Peringkat Knockout')
            ->assertSee('Kedudukan Round Robin')
            ->assertSee('Keputusan Round Robin')
            ->assertSee('Belum bermula')
            ->assertSee('LIVE')
            ->assertDontSee('Pilih pemenang');
    }

    public function test_live_knockout_events_follow_the_scoreboard_order(): void
    {
        $this->seed(DatabaseSeeder::class);

        $events = $this->get(route('live.index'))
            ->assertOk()
            ->viewData('events')
            ->pluck('sport.name')
            ->unique()
            ->values()
            ->all();

        $this->assertSame(
            ['Congkak', 'FIFA', 'Tekken', 'Dart', 'Carrom', 'Bowling', 'Pickleball'],
            $events,
        );
    }

    public function test_the_updated_dashboard_shows_live_operations_and_event_progress(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pusat Kawalan Karnival')
            ->assertSee('Kedudukan Rumah')
            ->assertSee('Kemajuan Keseluruhan')
            ->assertSee('Kemajuan Setiap Acara')
            ->assertSee('Tindakan Pantas')
            ->assertSee('Paparan Awam Live')
            ->assertSee('data-admin-theme="carnival"', false)
            ->assertSee('Karnival Sukan BTMKN')
            ->assertDontSee('Lapisan sistem pertama');
    }

    public function test_bowling_awards_position_points_by_total_pins_after_two_games(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'Bowling')->firstOrFail();
        $house = House::query()->where('name', 'Merah')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => $house->id,
            'employee_no' => 'BOWL-001',
            'name' => 'Pemain Boling',
            'gender' => Gender::Male,
        ]);
        $participant->sports()->attach($sport);
        $bowling = app(BowlingService::class);

        $this->actingAs(User::factory()->create())
            ->get(route('bowling.index'))
            ->assertOk()
            ->assertSee('Pemain Boling')
            ->assertSee('Game 1')
            ->assertSee('Game 2')
            ->assertDontSee('<select name="participant_id"', false);

        $bowling->save($sport->id, $participant->id, 1, 120);
        $this->assertFalse($bowling->isComplete());
        $this->assertSame(0, app(HousePointService::class)->pointsByHouse()[$house->id]);

        $bowling->save($sport->id, $participant->id, 2, 150);

        $this->assertTrue($bowling->isComplete());
        $this->assertSame(270, $bowling->houseTotals()[$house->id]);
        $this->assertSame(10, app(HousePointService::class)->pointsByHouse()[$house->id]);

        foreach (['Biru' => 250, 'Hijau' => 230, 'Kuning' => 210] as $houseName => $total) {
            $otherHouse = House::query()->where('name', $houseName)->firstOrFail();
            $otherParticipant = Participant::query()->create([
                'house_id' => $otherHouse->id,
                'employee_no' => 'BOWL-'.$otherHouse->id,
                'name' => 'Pemain '.$houseName,
                'gender' => Gender::Male,
            ]);
            $otherParticipant->sports()->attach($sport);
            $bowling->saveGames($sport->id, $otherParticipant->id, 100, $total - 100);
        }

        $positionPoints = app(HousePointService::class)->pointsByHouse();

        $this->assertSame(10, $positionPoints[House::query()->where('name', 'Merah')->value('id')]);
        $this->assertSame(7, $positionPoints[House::query()->where('name', 'Biru')->value('id')]);
        $this->assertSame(5, $positionPoints[House::query()->where('name', 'Hijau')->value('id')]);
        $this->assertSame(3, $positionPoints[House::query()->where('name', 'Kuning')->value('id')]);
    }

    public function test_bowling_game_one_can_be_saved_before_game_two(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'Bowling')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => House::query()->firstOrFail()->id,
            'employee_no' => 'BOWL-PROGRESSIVE',
            'name' => 'Pemain Berperingkat',
            'gender' => Gender::Male,
        ]);
        $participant->sports()->attach($sport);
        $admin = User::factory()->create();
        $bowling = app(BowlingService::class);

        $this->assertSame('not_started', $bowling->status());

        $this->actingAs($admin)->post(route('bowling.store'), [
            'sport_id' => $sport->id,
            'participant_id' => $participant->id,
            'game_1' => 120,
            'game_2' => null,
        ])->assertRedirect(route('bowling.index'));

        $this->assertDatabaseHas('bowling_scores', [
            'participant_id' => $participant->id,
            'game_number' => 1,
            'score' => 120,
        ]);
        $this->assertDatabaseMissing('bowling_scores', [
            'participant_id' => $participant->id,
            'game_number' => 2,
        ]);
        $this->assertSame('ongoing', $bowling->status());

        $this->actingAs($admin)->post(route('bowling.store'), [
            'sport_id' => $sport->id,
            'participant_id' => $participant->id,
            'game_1' => 120,
            'game_2' => 145,
        ])->assertRedirect(route('bowling.index'));

        $this->assertDatabaseHas('bowling_scores', [
            'participant_id' => $participant->id,
            'game_number' => 2,
            'score' => 145,
        ]);
        $this->assertSame('complete', $bowling->status());
    }

    public function test_an_admin_can_reset_only_bowling_scores(): void
    {
        $this->seed(DatabaseSeeder::class);
        $sport = Sport::query()->where('name', 'Bowling')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => House::query()->firstOrFail()->id,
            'employee_no' => 'BOWL-RESET',
            'name' => 'Pemain Reset Bowling',
            'gender' => Gender::Male,
        ]);
        $participant->sports()->attach($sport);
        app(BowlingService::class)->save($sport->id, $participant->id, 1, 130);

        $leagueSport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        app(LeagueFixtureService::class)->generate($leagueSport, Gender::Male);

        $this->actingAs(User::factory()->create())
            ->delete(route('bowling.reset'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('bowling_scores', 0);
        $this->assertDatabaseCount('matches', 6);
        $this->assertDatabaseCount('sport_registrations', 1);
    }

    public function test_a_participant_can_register_for_events_from_the_public_page(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-14 08:00:00', 'Asia/Kuala_Lumpur'));
        $this->seed(DatabaseSeeder::class);
        $house = House::query()->where('name', 'Merah')->firstOrFail();
        $fifa = Sport::query()->where('name', 'FIFA')->firstOrFail();
        $dart = Sport::query()->where('name', 'Dart')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => $house->id,
            'employee_no' => 'PUBLIC-001',
            'name' => 'Peserta Awam',
            'gender' => Gender::Male,
            'department' => 'Teknologi Maklumat',
        ]);

        $this->get(route('public-registration.create'))
            ->assertOk()
            ->assertSee('Pendaftaran Peserta')
            ->assertSee('Peserta Awam')
            ->assertSee('Hantar Pendaftaran');

        $this->post(route('public-registration.store'), [
            'house_id' => $house->id,
            'participant_id' => $participant->id,
            'sport_ids' => [$fifa->id, $dart->id],
        ])->assertRedirect(route('public-registration.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('participants', 1);
        $this->assertEqualsCanonicalizing(
            [$fifa->id, $dart->id],
            $participant->sports()->pluck('sports.id')->all(),
        );
    }

    public function test_public_event_registration_is_closed_before_14_july_2026(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-13 23:59:59', 'Asia/Kuala_Lumpur'));
        $this->seed(DatabaseSeeder::class);
        $house = House::query()->firstOrFail();
        $sport = Sport::query()->where('name', 'FIFA')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => $house->id,
            'employee_no' => 'BEFORE-OPENING',
            'name' => 'Peserta Awal',
            'gender' => Gender::Male,
        ]);

        $this->get(route('public-registration.create'))
            ->assertOk()
            ->assertSee('Pendaftaran belum dibuka')
            ->assertSee('Dibuka 14 Julai 2026');

        $this->post(route('public-registration.store'), [
            'house_id' => $house->id,
            'participant_id' => $participant->id,
            'sport_ids' => [$sport->id],
        ])->assertSessionHasErrors('sport_ids');

        $this->assertDatabaseCount('sport_registrations', 0);
    }

    public function test_the_public_participant_listing_hides_employee_numbers(): void
    {
        $this->seed(DatabaseSeeder::class);
        $house = House::query()->where('name', 'Biru')->firstOrFail();
        $sport = Sport::query()->where('name', 'Pickleball')->firstOrFail();
        $participant = Participant::query()->create([
            'house_id' => $house->id,
            'employee_no' => 'PRIVATE-EMPLOYEE-NUMBER',
            'name' => 'Peserta Senarai Awam',
            'gender' => Gender::Female,
        ]);
        $participant->sports()->attach($sport);

        $this->get(route('public-participants.index', ['house_id' => $house->id]))
            ->assertOk()
            ->assertSee('Senarai Peserta')
            ->assertSee('Peserta Senarai Awam')
            ->assertSee('Pickleball')
            ->assertSee('Senarai Mengikut Acara')
            ->assertSee('Rumah Biru')
            ->assertDontSee('PRIVATE-EMPLOYEE-NUMBER');
    }

    private function tournamentMatches(Sport $sport, MatchStage $stage)
    {
        return LeagueMatch::query()
            ->with(['homeHouse', 'awayHouse', 'result'])
            ->whereBelongsTo($sport)
            ->where('gender', Gender::Male)
            ->where('stage', $stage)
            ->orderBy('match_no')
            ->get();
    }
}
