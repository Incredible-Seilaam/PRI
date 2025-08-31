CREATE TABLE IF NOT EXISTS uzivatele (
    id SERIAL PRIMARY KEY,
    jmeno TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    heslo_hash TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS hry (
    id SERIAL PRIMARY KEY,
    nazev TEXT NOT NULL,
    zanr TEXT,
    platforma TEXT,
    rok INT,
    hodnoceni NUMERIC(3,1),
    uzivatel_id INT REFERENCES uzivatele(id)
);
