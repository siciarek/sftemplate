
# language: pl

@login
Funkcjonalność: Prywatna strefa użytkownika
  Aby ukrywać dane niejawne
  Jako Użytkownik
  Chciałbym posiadać strefę prywatną

  Scenariusz: Uzyskanie dostępu do prywatnej strefy
    Zakładając że odwiedzę stronę "/login"
    I wypełnię pole "username" wartością "admin"
    I wypełnię pole "password" wartością "admin"
    Kiedy kliknę guzik "_submit"
    I poczekam aż strona się załaduje
    Wtedy powinienem być na stronie "/"

  Scenariusz: Próba uzyskania dostępu do prywatnej strefy nieprawidłowymi danymi
    Zakładając że odwiedzę stronę "/login"
    I wypełnię pole "username" wartością "wrongusername"
    I wypełnię pole "password" wartością "password"
    Kiedy kliknę guzik "_submit"
    I poczekam aż strona się załaduje
    Wtedy powinienem być na stronie "/login"