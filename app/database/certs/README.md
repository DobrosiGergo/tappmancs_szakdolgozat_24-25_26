# Adatbázis TLS CA tanúsítvány

Ide kerül a menedzselt MySQL szolgáltató CA tanúsítványa `ca.pem` néven.

Aiven esetén: service → Overview → *Download CA certificate*, majd:

```bash
cp ~/Downloads/ca.pem app/database/certs/ca.pem
```

Egyetlen `ca.pem` kell, kliens-tanúsítvány és kulcs **nem** — a szolgáltató
szerveroldali TLS-t használ, ez a fájl csak a szerver tanúsítványának
ellenőrzésére szolgál. A fájl nem titkos (publikus CA cert, nem kulcs),
ezért verziókövethető.

A `config/database.php` automatikusan megtalálja, ha itt van ezen a néven —
nem kell `MYSQL_ATTR_SSL_CA` env változót beállítani. Az env változó csak
felülbírálásra szolgál, ha máshol tartod a fájlt.

Ez a könyvtár azért nem a `storage/` alatt van, mert serverless futtatáskor az
`api/index.php` a `storage_path()`-ot `/tmp`-re tereli (read-only filesystem),
a `database_path()` viszont stabil marad.
