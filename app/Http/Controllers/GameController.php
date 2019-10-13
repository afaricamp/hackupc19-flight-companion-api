<?php

namespace App\Http\Controllers;

use App\User;
use App\Airport;
use App\Flight;
use App\UserGame;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Create user for the game
     *
     * @param Request $request
     *
     * @return string
     */
    public function login(Request $request)
    {
        $userName = $request->get('user_name');
        $flightNumber = $request->get('flight_name');

        $flightInfo = $this->getFlightInfoFromFlightNumber($flightNumber);

        $airport = Airport::where('name', $flightInfo['departure'])->first();
        if (!$airport) {
            $airport = new Airport();
            $airport->name = $flightInfo['departure'];
            $airport->save();
        }
        $response['airtpor_id'] = $airport->id;
        $response['departure'] = $flightInfo['departure'];
        $response['arrival'] = $flightInfo['arrival'];
        $response['status'] = $flightInfo['status'];

        $user = User::where('name', $userName)->first();
        if (!$user) {
            $user = new User();
            $user->name = $userName;
            $user->save();
        }
        $response['user_id'] = $user->id;

        $flight = Flight::where('number', $flightNumber)->first();
        if (!$flight) {
            $flight = new Flight();
            $flight->number = $flightNumber;
            $flight->airport_id = $airport->id;
            $flight->departure = $response['departure'];
            $flight->arrival = $response['arrival'];
            $flight->status = $response['status'];
            $flight->save();
        }
        $response['flight_name'] = $flightNumber;
        $response['flight_id'] = $flight->id;

        return json_encode($response);
    }

    /**
     * Save stadistics and return top five and total score.
     *
     * @param Request $request
     *
     * @return string
     */
    public function endgame(Request $request)
    {
        $userId = $request->get('user_id');
        $flightId = $request->get('flight_id');
        $score = str_replace(',', '.', $request->get('score'));
        $multiplier = str_replace(',', '.', $request->get('multiplier'));

        $flight = Flight::find($flightId);
        $flight->multiplier += $multiplier;
        $totalScore = ($score ?? 1) * ($flight->multiplier ?? 1);
        $flight->score += $totalScore;
        $flight->save();

        $userGame = new UserGame();
        $userGame->user_id = $userId;
        $userGame->flight_id = $flightId;
        $userGame->score = $totalScore;
        $userGame->multiplier = $multiplier;
        $userGame->save();

        $response = [
            'flight_total_score' => $flight->score,
            'flight_multiplier' => $flight->multiplier,
            'total_score' => $totalScore,
            'top_5' => UserGame::select('id', 'user_id', 'flight_id', 'score', 'multiplier')->
            where('user_id', $userId)
                ->where('flight_id', $flightId)
                ->orderBy('score', 'DESC')
                ->limit(5)
                ->get(),
        ];
        return json_encode($response);
    }

    /**
     * Save stadistics and return top five and total score.
     *
     * @param Request $request
     *
     * @return string
     */
    public function top5(Request $request)
    {
        $userId = $request->get('user_id');
        $flightId = $request->get('flight_id');

        $response = [
            'top_5' => UserGame::select('id', 'user_id', 'flight_id', 'score', 'multiplier')
                ->where('user_id', $userId)
                ->where('flight_id', $flightId)
                ->orderBy('score', 'DESC')
                ->limit(5)
                ->get(),
        ];
        return json_encode($response);
    }

    /**
     * Save stadistics and return top five and total score.
     *
     * @param Request $request
     *
     * @return string
     */
    public function flightScores(Request $request)
    {
        $flightId = $request->get('flight_id');

        $flightUserScores = UserGame::groupBy('user_id')
            ->selectRaw('sum(user_games.score) as total_score, sum(user_games.multiplier) as total_multiplier, user_games.user_id, flights.number, users.name')
            ->leftJoin('flights', 'user_games.flight_id', '=', 'flights.id')
            ->leftJoin('users', 'user_games.user_id', '=', 'users.id')
            ->where('flight_id', $flightId)
            ->orderBy('total_score', 'DESC')->get();

        $response = [
            'total_score' => $flightUserScores,
        ];
        return json_encode($response);
    }

    /**
     * Save stadistics and return top five and total score.
     *
     * @param Request $request
     *
     * @return string
     */
    public function airportScores(Request $request)
    {
        $airportId = $request->get('airport_id');

        $flights = Flight::where('airport_id', $airportId)
            ->orderBy('total_score', 'DESC')
            ->get();

        $airportScores = collect();
        foreach ($flights as $flight) {
            $airportScores->push([
                'flight_id' => $flight->id,
                'number' => $flight->number,
                'total_score' => $flight->score ?? 0,
            ]);
        }

        $response = [
            'airport_scores' => $airportScores,
        ];
        return json_encode($response);
    }

    private function getFlightInfoFromFlightNumber(string $flightNumber) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://aerodatabox.p.rapidapi.com/flights/' . $flightNumber,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: aerodatabox.p.rapidapi.com",
                "x-rapidapi-key: " . env('FLIGHT_API_KEY')
            ],
        ]);

        $curlResponse = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = [
            'departure' => '',
            'arrival' => '',
            'status' => '',
        ];

        if ($err) {
            return $response;
        }

        $flightData = json_decode($curlResponse);
        if(empty($flightData)){
            return $response;
        }

        $response['departure'] = $flightData[0]->departure->airport->name;
        $response['arrival'] = $flightData[0]->arrival->airport->name;
        $response['status'] = $flightData[0]->status;

        return $response;
    }
}