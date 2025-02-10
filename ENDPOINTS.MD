| Method  | Rute                                               | Description                      
|---------|----------------------------------------------------|-------------------------------------------------|
| **Jugadores - players** |||
| `GET`   | `/api/players/`                                    | Find all players                                |
| `GET`   | `/api/players/id/{id}`                             | Find player by id                               |
| `GET`	  | `/api/players/name/{nombre}`                       | Find player by name                             |
| `GET`   | `/api/players/mail/{correo}`                       | Find player by email                            |
| `GET`   | `/api/players/rol/{rol}`                           | Find players by rol                             |
| `GET`   | `/api/players/games/{juegoId}/players`             | Find players who played a game by idGame        |
| `GET`   | `/api/players/{idJugador}/matches/won`             | Find matches a player won by idPlayer           |
| `POST`  | `/api/players/`                                    | Create a player                                 |
| `DELETE`| `/api/players/{id}`                                | Delete a player by id                           |
| `PATCH` | `/api/players/{id}`                                | Update a player by id                           |
| **Autores - authors** |||
| `GET`   | `/api/authors/`                                    | Find all authors                                |
| `GET`   | `/api/authors/id/{id}`                             | Find author by id                               |
| `GET`   | `/api/authors/name/{nombre}`                       | Find author by name                             |
| `GET`   | `/api/authors/nationality/{nacionalidad}`          | Find authors by nacionality                     |
| `POST`  | `/api/authors/`                                    | Create an author                                |
| `DELETE`| `/api/authors/{id}`                                | Delete an author by id                          |
| **Juegos - games** |||
| `GET`   | `/api/games/`                                      | Find all games                                  |
| `GET`   | `/api/games/id/{id}`                               | Find a game by id                               |
| `GET`   | `/api/games/name/{nombre}`                         | Find game by name                               |
| `GET`   | `/api/games/year/{anio}`                           | Find games by release year                      |
| `GET`   | `/api/games/localEditorial/{editorialLocal}`       | Find game by local publisher                    |
| `GET`   | `/api/games/originEditorial/{editorialMadre}`      | Find games by original publisher                |
| `GET`   | `/api/games/priceRange/{minPrice}/{maxPrice}`      | Find games between a range of prices            |
| `GET`   | `/api/games/underPrice/{price}`                    | Find games cheaper than a price                 |
| `GET`   | `/api/games/overPrice/{price}`                     | Find games more expensive than a price          |
| `GET`   | `/api/games/playersRange/{minPlayers}/{maxPlayers}`| Find games with a range of players              |
| `GET`   | `/api/games/minplayers/{minjugadores}`             | Find games for a a minimun of players           |
| `GET`   | `/api/games/maxPlayers/{maxJugadores}`             | Find games for a maximun of players             |
| `GET`   | `/api/games/gamesByAutors/{id}`                    | Find games of an author                         |
| `GET`   | `/api/games/yesAutoma`                             | Find games with automa                          |
| `GET`   | `/api/games/noAutoma`                              | Find games without automa                       |
| `GET`   | `/api/games/type/{tipo}`                           | Find games by type                              |
| `GET`   | `/api/games/expansionsByJuego/{idJuego}`           | Find expansions of a geme by id                 |
| `GET`   | `/api/games/expansions`                            | Find all expansions                             |
| `GET`   | `/api/games/expansionsWithAutoma`                  | Find expansions with automa                     |
| `GET`   | `/api/games/expansionsWithoutAutoma`               | Find expansions without automa                  |
| `POST`  | `/api/games/`                                      | Create a game                                   |
| `DELETE`| `/api/games/{id}`                                  | Delete a game by id                             |
| **Partidas - matches** |||
| `GET`   | `/api/matches/`                                    | Find all matches                                |
| `GET`   | `/api/matches/id/{id}`                             | Find match by id                                |
| `GET`   | `/api/matches/date/{fecha}`                        | Find matches by date                            |
| `GET`   | `/api/matches/winnersRanking`                      | Find a winners ranking                          |
| `GET`   | `/api/matches/player/{jugadorId}`                  | Find all matches played by a Player by idPlayer |
| `GET`   | `/api/matches/gamesByPlayer/{jugadorId}`           | Find games played by a player by idPaler        |
| `GET`   | `/api/matches/playersByGame/{juegoId}`             | Find players who played a game by idGame        |
| `GET`   | `/api/matches/winnersByGame/{juegoId}`             | Find al winners of a game by idGame             |
| `POST`  | `/api/matches/`                                    | Create a match                                  |
| `DELETE`| `/api/matches/{id}`                                | Delete a match by id                            |
