# test2
Testing Sistem Kasbon dengan unit test

silahkan clone aplikasi dengan git clone seperti contoh di bawah ini:


setelah melakukan cloning lakukan penginstallan package dari laravel dengan <b>composer install</b>

jangan lupa untuk mengaktifkan database kalian disini menggunakan MySQL dengan settingan sebagai berikut
- buka .env
- dan setting database sebagai berikut


*jika tidak ada .env bisa new file dengan nama .env dan copykan isiannya dari .env.example

setelah settingan database selesai silahkan buat database dengan nama test2 dan ketikkan di CLI kalian seperti dibawah ini:


dan ketikan juga seeder di CLI seperti dibawah ini:


setelah settingan database, migrate dan seeder selesai kita lakukan test di CLI 
- pertama test terlebih dahulu it_store_pegawai
- kedua test dengan it_get_pegawai
- ketiga test dengan it_store_kasbon
- keempat test dengan it_get_kasbon
- kelima test dengan it_patch_kasbon
- terakhir test dengan it_post_massal_kasbon

untuk cara nya bisa lihat dibawah ini:
