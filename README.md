# Flight Companion API

API for collect, process all necessary data for Flight Companion Game.

## ENDPOINTS

### Login

 - URL: /api/login
 - Body Params: 
    - user_name (string)
    - flight_name (string)
 - Response:
`{
    "airtpor_id":7,
    "departure":"Barcelona",
    "arrival":"Moscow, Sheremetyevo",
    "status":"Arrived",
    "user_id":1,
    "flight_name":"AEA3289",
    "flight_id":12
}`
 - Description: With the user name and the number of the flight creates flight in the database or give the exists id and some valuable information from a aerodatabox API.

### Flight scores

 - URL: /api/flightscores
 - Body Params: 
    - flight_id (int)
 - Response:
`{
    "total_score":[
        {
            "total_score":"713",
            "total_multiplier":"1.2",
            "user_id":"2",
            "number":"VLG6526",
            "name":"Arnau"
        },
        {
            "total_score":"244.2",
            "total_multiplier":"1.1",
            "user_id":"3",
            "number":"VLG6526",
            "name":"Marc"
        }
    ]
}`
 - Description: Gets scores from all of passengers of the flight.

### Airport scores

 - URL: /api/airportscores
 - Body Params: 
    - airport_id (int)
 - Response:
`{
    "airport_scores":[
        {
            "flight_id":1,
            "number":"VY-3306",
            "total_score":"158684"
        }
    ]
}`
 - Description: Return scores for all flights of the airport.

### Set game

 - URL: /api/endgame
 - Body Params: 
    - flight_id (int)
    - user_id (int)
    - score (float) -> number of points the player won
    - multiplier (float) -> booster the player won
 - Response:
`{
    "flight_total_score":2000,
    "flight_multiplier":2,
    "total_score":2000,
    "top_5":[
        {
            "id":4,
            "user_id":"5",
            "flight_id":"10",
            "score":"2000",
            "multiplier":"2.0"
        }
    ]
}`
 - Description: Calculate and sets game total score and multiplier, update flight score and multiplier and return total score, flight state and top 5 games of this user.
 
### Get top 5 games from user and flight

 - URL: /api/top5
 - Body Params: 
    - flight_id (int)
    - user_id (int)
 - Response:
`{
    "top_5":[
        {
            "id":8,
            "user_id":"2",
            "flight_id":"41",
            "score":"16950",
            "multiplier":"3.0"
        },
        {
            "id":7,
            "user_id":"2",
            "flight_id":"41",
            "score":"7470",
            "multiplier":"1.0"
        },
        {
            "id":5,
            "user_id":"2",
            "flight_id":"41",
            "score":"4300",
            "multiplier":"2.0"
        },
        {
            "id":6,
            "user_id":"2",
            "flight_id":"41",
            "score":"2190",
            "multiplier":"3.0"
        },
        {
            "id":2,
            "user_id":"2",
            "flight_id":"41",
            "score":"713",
            "multiplier":"1.2"
        }
    ]
}`
 - Description: Get the top five games from user and flight.
 
