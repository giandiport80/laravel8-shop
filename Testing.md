kelemahannya ketika membuat aplikasi dulu, baru testing, ada kendala yang tidak diduga

beda ketika kita menggunakan konsep TDD
TDD : membuat testing dari awal, baru koding nya

konfigurasi database ada di phpunit.xml

```
  <server name="DB_CONNECTION" value="mysql"/>
  <server name="DB_DATABASE" value="laravel8_shop_testing"/>
  <server name="DB_HOST" value="127.0.0.1"/>
  <server name="DB_PORT" value="3306"/>
  <server name="DB_USERNAME" value="root"/>
  <server name="DB_PASSWORD" value=""/>
```

TestCase.php adalah root dari test2 dibawahnya akan extends

menjalankan test dengan `php artisan test`

membuat test di folder Unit
```
$ php artisan make:test Admin/CategoryRepositoryTest --unit
```

- Positive testing: testing yang diharapkan sukses hasilnya
- Negative testing: testing yang diharapkan gagal hasilnya


kita juga bisa secara spesifik menjalankan testnya
```
php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanCreateCategory
```

```
$ php artisan test tests/Unit/Admin/CategoryRepositoryTest.php
Warning: TTY mode is not supported on Windows platform.

   PASS  Tests\Unit\Admin\CategoryRepositoryTest
  ✓ can create category
  ✓ can create a category with parent

  Tests:  2 passed
  Time:   93.94s
```