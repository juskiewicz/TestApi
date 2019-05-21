
### Docker

--------------------------------------------------------------------------------

Tworzymy sieć z dedykowanym lokalnym IP
``` 
docker network create nginx-proxy --subnet=172.20.0.0/16 --gateway=172.20.0.1
```

Sprawdzamy sieć
``` 
docker network inspect nginx-proxy
```

Dodajemy do /ete/hosts IP gateway 
``` 
sudo echo "172.18.0.1 souvre.lh" >> /etc/hosts
```

### Jak uruchomić

--------------------------------------------------------------------------------

Katalog projektu 
```
cd Docker
```

Ustaw swoje ustawienia
```
cp .env.dist .env 
```

* Pierwsze postawienie projektu i budowanie
```
docker-compose up -d --build
```

* Ponowne uruchamianie projektu
```
docker-compose up -d
```

* Zatrzymanie
```
docker-compose down
```

### Załadowanie fixture

--------------------------------------------------------------------------------

Wchodzimy do katalogu projektu
```
cd ms_security
```

Odpalamy ładowanie fixture
```
sudo ./docker_load_fixture.sh
```

### POSTMAN

--------------------------------------------------------------------------------

postman.json