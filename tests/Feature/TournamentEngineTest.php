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

        $leagueMatches = $this->tournamentMatches($sport, MatchStage::League);
        $scores = [[2, 0], [2, 0], [3, 1], [2, 0], [1, 0], [0, 2]];

        foreach ($leagueMatches as $index => $match) {
            $results->submit($match, ...$scores[$index]);
        }

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
            ->assertSee('Belum selesai')
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
            ->assertSee('LIVE')
            ->assertDontSee('Pilih pemenang');
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
