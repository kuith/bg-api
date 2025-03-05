| Method  | Rute                                               | Description                                    Required Params.  
|---------|----------------------------------------------------|----------------------------------------------|---------------
|`GET`   |`/`                                                  |Home/root                               |
**Jugadores - players**
|`GET`   |`/api/players/`                                    |Find all players                                |
|`GET`   |`/api/players/id/{id}`                             |Find player by id                               |`{id}`
|`GET`	 |`/api/players/name/{nombre}`                       |Find player by name                             |`{nombre}`
|`GET`   |`/api/players/mail/{correo}`                       |Find player by email                            |`{correo}`
|`GET`   |`/api/players/rol/{rol}`                           |Find players by rol                             |`{rol}`
|`GET`   |`/api/players/games/{juegoId}/players`             |Find players who played a game by idGame        |`{juegoId}`
|`GET`   |`/api/players/{idJugador}/matches/won`             |Find matches a player won by idPlayer           |`{idJugador}`
|`POST`  |`/api/players/`                                    |Create a player                                 |#
|`DELETE`|`/api/players/{id}`                                |Delete a player by id                           |`{id}`
|`PATCH` |`/api/players/{id}`                                |Update a player by id                           |`{id, data to update}`

**Autores - authors**
|`GET`   |`/api/authors/`                                    |Find all authors                                |
|`GET`   |`/api/authors/id/{id}`                             |Find author by id                               |`{id}`
|`GET`   |`/api/authors/name/{nombre}`                       |Find author by name                             |`{nombre}`
|`GET`   |`/api/authors/nationality/{nacionalidad}`          |Find authors by nationality                   |`{nationality}`
|`POST`  |`/api/authors/`                                    |Create an author                                |#
|`PATCH` |`/api/authors/{id}`                                |Update an author by id               |`{id, data to update}  
|`DELETE`|`/api/authors/{id}`                                |Delete an author by id                          |`{id}`

**Juegos - games**
|`GET`   |`/api/games/`                                      |Find all games                                 |
|`GET`   |`/api/games/id/{id}`                               |Find a game by id                              |`{id}`
|`GET`   |`/api/games/name/{nombre}`                         |Find game by name                              |`{nombre}`
|`GET`   |`/api/games/year/{anio}`                           |Find games by release year                     |`{anio}`
|`GET`   |`/api/games/localEditorial/{editorialLocal}`       |Find game by local publisher              |`{editorialLocal}`
|`GET`   |`/api/games/originEditorial/{editorialMadre}`      |Find games by original publisher          |`{editorialMadre}`
|`GET`   |`/api/games/priceRange/{minPrice}/{maxPrice}`      |Find games between a range of price  |`{minPrice}/{maxPrice}`
|`GET`   |`/api/games/underPrice/{price}`                    |Find games cheaper than a price                |`{price}`
|`GET`   |`/api/games/overPrice/{price}`                     |Find games more expensive than a price         |`{price}`
|`GET`   |`/api/games/playersRange/{minPlayers}/{maxPlayers}`|Find games with a range of players          |`{minjugadores}`
|`GET`   |`/api/games/minplayers/{minjugadores}`             |Find games for a a minimun of players       |`{maxJugadores}`
|`GET`   |`/api/games/maxPlayers/{maxJugadores}`             |Find games for a maximun of players            |`{id}`
|`GET`   |`/api/games/gamesByAutors/{id}`                    |Find games of an author                        |
|`GET`   |`/api/games/yesAutoma`                             |Find games with automa                         |
|`GET`   |`/api/games/noAutoma`                              |Find games without automa                      |
|`GET`   |`/api/games/type/{tipo}`                           |Find games by type                             |`{tipo}`
|`GET`   |`/api/games/expansionsByJuego/{idJuego}`           |Find expansions of a geme by id                |`{idJuego}` 
|`GET`   |`/api/games/expansions`                            |Find all expansions                            |
|`GET`   |`/api/games/expansionsWithAutoma`                  |Find expansions with automa                    |
|`GET`   |`/api/games/expansionsWithoutAutoma`               |Find expansions without automa                 |
|`POST`  |`/api/games/`                                      |Create a game                                  |#
|`PATCH` |`/api/games/{id}`                                  |Update an game by id                           |`{id}`
|`DELETE`|`/api/games/{id}`                                  |Delete a game by id                            |`{id}`

**Partidas - matches**
|`GET`   |`/api/matches/`                                    |Find all matches                               |
|`GET`   |`/api/matches/id/{id}`                             |Find match by id                               |`{id}`
|`GET`   |`/api/matches/date/{fecha}`                        |Find matches by date                           |`{fecha}`
|`GET`   |`/api/matches/winnersRanking`                      |Find a winners ranking                         |
|`GET`   |`/api/matches/player/{jugadorId}`                  |Find all matches played by a Player by idPlayer|`{jugadorId}`
|`GET`   |`/api/matches/gamesByPlayer/{jugadorId}`           |Find games played by a player by idPlayer      |`{juegoId}`
|`GET`   |`/api/matches/playersByGame/{juegoId}`             |Find players who played a game by idGame       |`{juegoId}`
|`GET`   |`/api/matches/winnersByGame/{juegoId}`             |Find al winners of a game by idGame            |
|`POST`  |`/api/matches/`                                    |Create a match                                 |#
|`PATCH` |`/api/matches/{id}`                                |Update an match by id                          |`{id}`
|`DELETE`|`/api/matches/{id}`                                |Delete a match by id                           |`{id}`

## General notes:
- For the endpoints that use route parameters ({id}, {name}, etc.), replace them with the corresponding values.
- Parameters for POST or PATCH requests must be sent in the body of the request in JSON format.

# Special notes for entyties creation:
- Players:
    - Required fields: {nombre, correo, rol, fecha_registro, password}
- Authors:
    - Required fields: {nombre, nacionalidad}
- Games:
    - Required fields: {nombre, tipo, descripcion, baseExpansion (values: "base", "expansion"), juegoBaseId (if baseExpansion === expansion), anioPublicacion, editorialLocal, editorialMadre, precio, minJugadores, maxJugadores}
- Matches:
    - Required fields: {fecha, juego_id, jugadores_ids, ganadores_ids}
