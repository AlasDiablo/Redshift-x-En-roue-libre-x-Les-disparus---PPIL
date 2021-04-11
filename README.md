# Share My Ride

[![GitHub](https://img.shields.io/github/license/AlasDiablo/Rs-Rl-Ld-PPIL?style=for-the-badge)](https://github.com/AlasDiablo/Rs-Rl-Ld-PPIL/blob/master/LICENSE)
[![GitHub contributors](https://img.shields.io/github/contributors-anon/AlasDiablo/Rs-Rl-Ld-PPIL?style=for-the-badge)](https://github.com/AlasDiablo/Rs-Rl-Ld-PPIL/graphs/contributors)

## Requirement:

- ![php](https://img.shields.io/badge/php-%5E7.3-blue)
- ![mysql-mariadb](https://img.shields.io/badge/MySQL-MariaDB-blue)

## Who to install

### Downloading project

<summary><b>1. Cloning this repository</b></summary>

```
git clone https://github.com/AlasDiablo/Rs-Rl-Ld-PPIL.git
```

### Install Composer and project Dependencies

<details><summary><b>1. Installing Composer</b></summary>
<ul><li>

A. Downloading Composer:
- Windows:
[Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)    
- Ubuntu & other:
[Command-line installation](https://getcomposer.org/download/)
</li>
<li>
B. Chose php version (Windows only):<br>
<img src="https://raw.githubusercontent.com/AlasDiablo/Rs-Rl-Ld-PPIL/readme/readme-assets/compser.jpg" alt="composer-windows" width="400px"/>

</li></ul>
</details>

<details><summary><b>2. Install Dependencies and Autoloader</b></summary>
<ul><li>
A. Via make

```
project location $> make
```
</li>
<li>
B. Via composer directly

```
project location $> composer install
```
</li></ul>
</details>

### Apache configuration

<details><summary><b>1. Create a VirtualHost</b></summary>
<ul><li>

A. For the apache configuration you need to create a VirtualHost with the name of your choice.

</li><li>

B. This VirtualHost need to allow override this is needed by the `.htaccess` file.

</li><li>

C. Don't forget to enable `mod_write` on apache, the `.htaccess` file use this module for enable feature for slim(php framework for url rooting).

</li></ul>
</details>

### Php configuration

On php you don't need a specifique configuration, you juste need to enable `mysql pdo` and `mysqli` module.

### Project configuration

<details><summary><b>1. Setup database</b></summary>
<ul><li>

A. Import tables into the DBMS
- Import `sql/bdd.sql` (tables use for all interation on the application)
- Import `sql/ville_france.sql` (table containing all french city)

</li><li>

B. Create database config file
- Create a new folder into `src` named `conf`
- Create a new file into `src/conf` named `conf.ini`
- Add this content into it with the proper modification
```ini
# driver to use
driver=mysql
# DBMS ip or domain name
host=127.0.0.1
# DBMS port
port=3307
# Name of your database
database=test
# Username use with your DBMS
username=root
# Password linked to your Username
password=
```

</li></ul>
</details>
