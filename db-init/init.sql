CREATE TABLE IF NOT EXISTS uzivatele (
    id SERIAL PRIMARY KEY,
    jmeno TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    heslo_hash TEXT NOT NULL
);

-- Unique games table
CREATE TABLE IF NOT EXISTS games (
    id SERIAL PRIMARY KEY,
    nazev TEXT NOT NULL,
    zanr TEXT,
    platforma TEXT,
    rok INT
);

-- User-specific ratings and ownership
CREATE TABLE IF NOT EXISTS user_games (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES uzivatele(id),
    game_id INT REFERENCES games(id),
    hodnoceni NUMERIC(3,1) CHECK (hodnoceni >= 0 AND hodnoceni <= 10),
    UNIQUE(user_id, game_id)
);
