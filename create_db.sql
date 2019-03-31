CREATE TABLE IF NOT EXISTS urls (
        short_url TEXT PRIMARY KEY,
        origin_url TEXT,
        hits INTEGER,
        status INTEGER
);
