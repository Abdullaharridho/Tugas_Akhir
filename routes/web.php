<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataSantriController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\guruController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGuruController;
use Illuminate\Support\Facades\Route;



Route::get('/', [PortalController::class, 'home'])->name('portal.home');
Route::get('/Gallery', [PortalController::class, 'gallery'])->name('portal.gallery');
Route::get('/Kegiatan', [PortalController::class, 'kegiatan'])->name('portal.kegiatan');
Route::get('/login', [PortalController::class, 'login'])->name('login');


Route::middleware(['auth', 'guru'])->group(function () {
    Route::get('/guru/dashboard', [guruController::class, 'tampil'])->name('userguru.tampil');
    Route::get('/absensi', [guruController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/create', [guruController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [guruController::class, 'store'])->name('absensi.store');
    Route::get('/get-santri/{kelas_id}', [guruController::class, 'getSantriByKelas']);
    Route::get('/absensi/{tanggal}/{kelas}/{mapel}/edit', [guruController::class, 'edit'])->name('absensi.edit');
    Route::post('/absensi/update', [guruController::class, 'update'])->name('absensi.update');
    Route::get('/guru/password', [GuruController::class, 'formPassword'])->name('guru.password.form');
    Route::post('/guru/password', [GuruController::class, 'ubahPassword'])->name('guru.password.update');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/user/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
});

require __DIR__ . '/auth.php';
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/dashboard/managementportal', [AdminController::class, 'managementportal'])->name('admin.managementportal');

    Route::get('/slide', [SlideController::class, 'tampil'])->name('admin.manageportal.index');
    Route::get('/slide/create', [SlideController::class, 'tambah'])->name('admin.manageportal.tambah');
    Route::post('/slide', [SlideController::class, 'simpan'])->name('admin.manageportal.simpan');
    Route::get('/slide/{id}/edit', [SlideController::class, 'edit'])->name('admin.manageportal.edit');
    Route::put('/slide/{id}', [SlideController::class, 'update'])->name('slide.update');
    Route::delete('/slide/{id}', [SlideController::class, 'hapus'])->name('admin.manageportal.hapus');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.manageportal.gallery.index');
    Route::get('/gallery/tambah', [GalleryController::class, 'tambah'])->name('admin.manageportal.gallery.tambah');
    Route::post('/gallery', [GalleryController::class, 'simpan'])->name('admin.manageportal.gallery.simpan');
    Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('admin.manageportal.gallery.edit');
    Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/delete/{id}', [GalleryController::class, 'hapus'])->name('admin.manageportal.gallery.hapus');

    Route::get('/kegiatann', [KegiatanController::class, 'index'])->name('admin.manageportal.kegiatan.index');
    Route::get('/kegiatan/tambah', [KegiatanController::class, 'tambah'])->name('admin.manageportal.kegiatan.tambah');
    Route::post('/kegiatan', [KegiatanController::class, 'simpan'])->name('admin.manageportal.kegiatan.simpan');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('admin.manageportal.kegiatan.edit');
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/delete/{id}', [KegiatanController::class, 'hapus'])->name('admin.manageportal.kegiatan.hapus');

    Route::post('/data_santri/import', [DataSantriController::class, 'import'])->name('datasantri.import');

    Route::get('/data_santri', [DataSantriController::class, 'index'])->name('datasantri.index');
    Route::get('/data_santri/create', [DataSantriController::class, 'tambah'])->name('datasantri.tambah');
    Route::post('/data_santri', [DataSantriController::class, 'simpan'])->name('datasantri.simpan');
    Route::get('/data_santri/{nis}/edit', [DataSantriController::class, 'edit'])->name('datasantri.edit');
    Route::put('/data_santri/{nis}', [DataSantriController::class, 'update'])->name('datasantri.update');
    Route::delete('/data_santri/{nis}', [DataSantriController::class, 'hapus'])->name('datasantri.hapus');
    Route::post('/data_santri/import', [DataSantriController::class, 'import'])->name('datasantri.import');
    Route::get('/data_santri/export', [DataSantriController::class, 'export'])->name('datasantri.export');
    Route::get('/data_santri/{nis}/detail', [\App\Http\Controllers\DatasantriController::class, 'detail'])->name('datasantri.detail');



    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/tambah', [KelasController::class, 'tambah'])->name('kelas.tambah');
    Route::post('/kelas', [KelasController::class, 'simpan'])->name('kelas.simpan');
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/delete/{id}', [KelasController::class, 'hapus'])->name('kelas.hapus');

    Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel.index');
    Route::get('/mapel/tambah', [MataPelajaranController::class, 'tambah'])->name('mapel.tambah');
    Route::post('/mapel', [MataPelajaranController::class, 'simpan'])->name('mapel.simpan');
    Route::get('/mapel/{id}/edit', [MataPelajaranController::class, 'edit'])->name('mapel.edit');
    Route::put('/mapel/{id}', [MataPelajaranController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/delete/{id}', [MataPelajaranController::class, 'hapus'])->name('mapel.hapus');

    Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
    Route::get('/kamar/tambah', [KamarController::class, 'tambah'])->name('kamar.tambah');
    Route::post('/kamar', [KamarController::class, 'simpan'])->name('kamar.simpan');
    Route::get('/kamar/{id}/edit', [KamarController::class, 'edit'])->name('kamar.edit');
    Route::put('/kamar/{id}', [KamarController::class, 'update'])->name('kamar.update');
    Route::delete('/kamar/delete/{id}', [KamarController::class, 'hapus'])->name('kamar.hapus');

    Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
    Route::get('/pelanggaran/tambah', [PelanggaranController::class, 'tambah'])->name('pelanggaran.tambah');
    Route::post('/pelanggaran/simpan', [PelanggaranController::class, 'simpan'])->name('pelanggaran.simpan');
    Route::get('/pelanggaran/edit/{id}', [PelanggaranController::class, 'edit'])->name('pelanggaran.edit');
    Route::put('/pelanggaran/update/{id}', [PelanggaranController::class, 'update'])->name('pelanggaran.update');
    Route::delete('/pelanggaran/hapus/{id}', [PelanggaranController::class, 'hapus'])->name('pelanggaran.hapus');
    Route::get('/pelanggaran/riwayat/{nis}', [PelanggaranController::class, 'riwayat'])->name('pelanggaran.riwayat');
    Route::put('/pelanggaran/update-tindakan/{id}', [PelanggaranController::class, 'updateTindakan'])->name('pelanggaran.updateTindakan');

    Route::get('/user-admin', [UserAdminController::class, 'index'])->name('useradmin.index');
    Route::get('/user-admin/create', [UserAdminController::class, 'create'])->name('useradmin.create');
    Route::post('/user-admin', [UserAdminController::class, 'store'])->name('useradmin.store');
    Route::get('/user-admin/{id}/edit', [UserAdminController::class, 'edit'])->name('useradmin.edit');
    Route::put('/user-admin/{id}', [UserAdminController::class, 'update'])->name('useradmin.update');
    Route::delete('/user-admin/{id}', [UserAdminController::class, 'destroy'])->name('useradmin.destroy');
    Route::get('/ubah-password', [UserAdminController::class, 'showChangePasswordForm'])->name('useradmin.password.form');
    Route::post('/ubah-password', [UserAdminController::class, 'changePassword'])->name('useradmin.password.update');


    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan/simpan', [TabunganController::class, 'simpan'])->name('tabungan.simpan');
    Route::get('/tabungan/riwayat/{nis}', [TabunganController::class, 'riwayat'])->name('tabungan.riwayat');
    Route::get('/tabungan/{id}/edit', [TabunganController::class, 'edit'])->name('tabungan.edit');
    Route::put('/tabungan/{id}', [TabunganController::class, 'update'])->name('tabungan.update');
    Route::delete('/tabungan/{id}', [TabunganController::class, 'destroy'])->name('tabungan.destroy');


    Route::get('/perizinan', [PerizinanController::class, 'tampil'])->name('perizinan.tampil');
    Route::get('/perizinan/create', [PerizinanController::class, 'tambah'])->name('perizinan.tambah');
    Route::post('/perizinan', [PerizinanController::class, 'simpan'])->name('perizinan.simpan');
    Route::get('/perizinan/{id}/edit', [PerizinanController::class, 'edit'])->name('perizinan.edit');
    Route::put('/perizinan/update/{id}', [PerizinanController::class, 'update'])->name('perizinan.update');
    Route::delete('/perizinan/{id}', [PerizinanController::class, 'hapus'])->name('perizinan.hapus');
    Route::post('/perizinan/kembali/{id}', [PerizinanController::class, 'tandaiKembali'])->name('perizinan.kembali');
    Route::get('/perizinan/riwayat/{nis}', [PerizinanController::class, 'riwayat'])->name('perizinan.riwayat');
    Route::get('/perizinan/surat/ajax/{id}', [PerizinanController::class, 'getSurat'])->name('perizinan.getSurat');
    Route::get('/perizinan/surat-terlambat/{id}', [PerizinanController::class, 'cetakSuratTerlambat'])->name('perizinan.surat_terlambat');



    Route::get('/Guru', [UserGuruController::class, 'guru'])->name('guru.index');
    Route::get('/create', [UserGuruController::class, 'create'])->name('guru.create');
    Route::post('/Guru/Simpan', [UserGuruController::class, 'store'])->name('guru.store');
    Route::get('/{id}/edit', [UserGuruController::class, 'edit'])->name('guru.edit');
    Route::put('/{id}', [UserGuruController::class, 'update'])->name('guru.update');
    Route::delete('/{id}', [UserGuruController::class, 'destroy'])->name('guru.destroy');
});
