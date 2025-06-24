# Format Import Data Excel

## Sheet 1: families

| kk_number | head_of_family | wife_name | house_block | phone_1 | phone_2 | house_status | status | family_members_count | license_plate_1 | license_plate_2 |
| --------- | -------------- | --------- | ----------- | ------- | ------- | ------------ | ------ | -------------------- | --------------- | --------------- |
| 123456789 | Budi           | Siti      | A1          | 081234  | 082345  | owner        | active | 4                    | B1234CD         | D5678EF         |

## Sheet 2: members

| kk_number | name | nik       | relationship    | birth_date | gender | occupation       |
| --------- | ---- | --------- | --------------- | ---------- | ------ | ---------------- |
| 123456789 | Budi | 320101... | Kepala Keluarga | 1980-01-01 | L      | Karyawan         |
| 123456789 | Siti | 320102... | Istri           | 1982-02-02 | P      | Ibu Rumah Tangga |
| 123456789 | Andi | 320103... | Anak            | 2005-03-03 | L      | Pelajar          |
| 123456789 | Ani  | 320104... | Anak            | 2008-04-04 | P      | Pelajar          |

-   Sheet pertama bernama `families` untuk data keluarga.
-   Sheet kedua bernama `members` untuk data anggota keluarga.
-   Kolom `kk_number` pada sheet `members` harus sesuai dengan `kk_number` di sheet `families`.
-   Format tanggal lahir: YYYY-MM-DD.
