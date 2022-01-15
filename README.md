# test2
Testing Sistem Kasbon dengan unit test

silahkan clone aplikasi dengan git clone seperti contoh di bawah ini:

<img src="https://user-images.githubusercontent.com/59255271/149613562-f56cc24c-30be-49b6-93b7-838a5269e94a.png" />

setelah melakukan cloning lakukan penginstallan package dari laravel dengan <b>composer install</b>

<img src="https://user-images.githubusercontent.com/59255271/149613549-545e159f-e298-4035-821b-3d2e1fce7630.png" />

jangan lupa untuk mengaktifkan database kalian disini menggunakan MySQL dengan settingan sebagai berikut
- buka .env
- dan setting database sebagai berikut

<img src="https://user-images.githubusercontent.com/59255271/149613589-10eb358f-1f03-4002-a99b-83a693812e05.png" />

*jika tidak ada .env bisa new file dengan nama .env dan copykan isiannya dari .env.example

setelah settingan database selesai silahkan buat database dengan nama test2 dan ketikkan di CLI kalian seperti dibawah ini:

<img src="https://user-images.githubusercontent.com/59255271/149613658-a57bb52b-2cdf-4fe2-a651-8a2073cb17f1.png" />

dan ketikan juga seeder di CLI seperti dibawah ini:

<img src="https://user-images.githubusercontent.com/59255271/149613664-32fc81a8-1e91-4274-b660-60efa433beef.png" />

setelah settingan database, migrate dan seeder selesai kita lakukan test di CLI 
- pertama test terlebih dahulu it_store_pegawai
- kedua test dengan it_get_pegawai
- ketiga test dengan it_store_kasbon
- keempat test dengan it_get_kasbon
- kelima test dengan it_patch_kasbon
- terakhir test dengan it_post_massal_kasbon

untuk cara nya bisa lihat dibawah ini:

<img src="https://user-images.githubusercontent.com/59255271/149613679-d60a0d50-45ca-4cb4-9a4b-ade800e508af.png" />
