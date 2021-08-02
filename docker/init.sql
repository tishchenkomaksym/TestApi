FLUSH PRIVILEGES;

DROP USER IF EXISTS bot;

CREATE USER bot IDENTIFIED WITH mysql_native_password BY '1caeZprqnWf6Q7Nb';
GRANT ALL PRIVILEGES ON `lar_test`.* TO bot;

DROP USER IF EXISTS phpmyadmin;

CREATE USER phpmyadmin IDENTIFIED WITH mysql_native_password BY '8IUikmw4Ac2O4nck';
GRANT ALL PRIVILEGES ON `lar_test`.* TO phpmyadmin;

