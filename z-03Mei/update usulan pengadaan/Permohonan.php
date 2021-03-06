<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permohonan extends Model
{   
    protected $table = 'PERMOHONAN';

    /**
     * Create permohonan record on database
     * @param  Array $data  Create permohonan data
     * @return void
     */
    public static function createPermohonan($data)
    {
        // create permohonan record on database
        DB::table('permohonan')->insert($data);
    }

    /**
     * Update permohonan record on database
     * @param  String $hash Permohonan hash
     * @param  Array  $data Update permohonan data
     * @return void
     */
    public static function updatePermohonan($hashPermohonan, $data)
    {
        // update permohonan record given the hash
        DB::table('permohonan')->where('hashPermohonan', $hashPermohonan)->update($data);
    }
     
     public static function getPengadaan($role, $nomorinduk='')
    {
        $query =
           'SELECT * FROM permohonan p, users u WHERE p.JenisPermohonan = 3 AND p.IdPemohon = u.NomorInduk AND p.deleted= 0';

         //seems not working :( and ended up doing the same in blade


         if ($role == 'Staf Fasilitas & Infrastruktur') {
            // staf PPAA only see jenis ruangan Kelas
           
            $query .=  ' AND StatusPermohonan = 0';
            
        } else if ($role == 'Manajer Fasilitas & Infrastruktur') {
            // staf sekertariat can see all ruangan except Kelas
           
            $query .= ' AND StatusPermohonan>=1' ;  
        } else {
            // if user != manajer peminjaman get only his/her permohonan
            if($nomorinduk != '') $query .= ' AND IdPemohon = "'.$nomorinduk.'"';  
                    
        }
        
        $allkandidat = KandidatBarang::all();
        $allpermohonan = DB::select(DB::raw($query));

        $allcatatan = DB::select(DB::raw(
            'SELECT * 
            FROM permohonan p, catatan c, users u 
            WHERE  
                p.IdPermohonan = c.IdPermohonan AND 
                c.NomorIndukPenulis = u.NomorInduk'
        ));


        // get list catatan
        $allcatatan = DB ::select(DB::raw(
            'SELECT *
            FROM catatan c, users u 
            WHERE c.NomorIndukPenulis = u.NomorInduk'
        ));

        return array(
            'allpermohonan' => $allpermohonan,
            'allcatatan' => $allcatatan,
            'allkandidat' => $allkandidat
        );
    }

     public static function updatePengadaan($hashPermohonan, $data)
    { 
        DB::table('permohonan')->where('hashPermohonan', $hashPermohonan)->update($data);
    }

    public static function getMaintenance($role, $nomorinduk='')
    {
        $query='SELECT * FROM permohonan p, users u WHERE p.JenisPermohonan = 4  AND p.IdPemohon = u.NomorInduk AND p.deleted= 0';

        $allpermohonan = DB::select(DB::raw($query));
        $allcatatanpermohonan = DB::select(DB::raw(
            'SELECT * 
            FROM permohonan p, catatan c, users u 
            WHERE  
                p.IdPermohonan = c.IdPermohonan AND 
                c.NomorIndukPenulis = u.NomorInduk'
        ));


        // get list catatan
        $allcatatan = DB::select(DB::raw(
            'SELECT *
            FROM catatan c, users u 
            WHERE c.NomorIndukPenulis = u.NomorInduk'
        ));
        return array(
                'allpermohonan' => $allpermohonan,
                'allcatatanpermohonan' => $allcatatanpermohonan,
                'allcatatan' => $allcatatan
        );


    }
    /**
     * Get Permohonan peminjaman ruangan, jadwal, ruangan, 
     * user, and catatan record
     * @return Array   Peminjaman ruangan, Jadwal, Ruangan, Catatan, User record
     */
    public static function getPeminjaman($role, $nomorinduk = '')
    {
        $query = 
            'SELECT * 
            FROM permohonan p, jadwal j, ruangan r, gedung g, users u
            WHERE  
                p.JenisPermohonan = 1 AND 
                p.IdRuangan = j.IdRuangan AND 
                j.IdRuangan = r.IdRuangan AND
                p.IdGedung = j.IdGedung AND
                j.IdGedung = r.IdGed AND
                p.IdJadwal = j.IdJadwal AND 
                p.IdPemohon = u.NomorInduk AND
                p.IdGedung = g.IdGedung AND
                j.IdGedung = g.IdGedung AND
                r.IdGed = g.IdGedung AND
                p.deleted = 0';

        // check role user, different role different data will be retrieve
        if ($role == 'Staf PPAA') {
            // staf PPAA only see jenis ruangan Kelas
            $query .= ' AND r.JenisRuangan="Kelas"';
        } else if ($role == 'Staf Sekretariat') {
            // staf sekertariat can see all ruangan except Kelas
            $query .= ' AND (r.JenisRuangan="Auditorium" OR 
                            r.JenisRuangan="RuangRapatBesar" OR 
                            r.JenisRuangan="RuangRapatKecil")' ;  
        } else {
            // if user != manajer peminjaman get only his/her permohonan
            if($nomorinduk != '') $query .= ' AND p.IdPemohon = "'.$nomorinduk.'"';            
        }
        
        // get all permohonan peminjaman ruangan
    	$allpermohonan = DB::select(DB::raw($query));

        // dd($allpermohonan);

        // get all catatan
        $allcatatan = DB::select(DB::raw(
    		'SELECT * 
    		FROM permohonan p, catatan c, users u 
    		WHERE  
    			p.IdPermohonan = c.IdPermohonan AND 
    			c.NomorIndukPenulis = u.NomorInduk'
        ));

        // return the array
        return array(
        	'allpermohonan' => $allpermohonan,
        	'allcatatan' => $allcatatan
        );
    }

    /**
     * Get Permohonan registrasi barang, kandidat barang, and catatan record
     * @return Array
     */
    public static function getRegistrasi($nomorinduk = '')
    {
        $query = 
            'SELECT * 
            FROM permohonan p, users u 
            WHERE
                p.JenisPermohonan = 2 AND 
                p.IdPemohon = u.NomorInduk AND
                p.deleted= 0';

        // if user != pihak fasilitas
        // get only his/her permohonan
        if($nomorinduk != '') $query .= ' AND p.IdPemohon = "'.$nomorinduk.'"';

    	// get list registrasi barang
		$allregistrasi = DB::select(DB::raw($query));   

        // get list kandidat barang
        $allkandidat = KandidatBarang::all();

		// get list catatan
		$allcatatan = DB ::select(DB::raw(
			'SELECT *
			FROM catatan c, users u 
			WHERE c.NomorIndukPenulis = u.NomorInduk'
		));

        // return the array
		return array(
			'allregistrasi' => $allregistrasi,
            'allkandidat' => $allkandidat,
			'allcatatan' => $allcatatan,
		);
    }

    /**
     * Update status permohonan on database
     * @param  [type] $id          [description]
     * @param  [type] $persetujuan [description]
     * @return [type]              [description]
     */
    public static function updateStatus($id, $persetujuan) 
    {
        if($persetujuan == 'setuju')
            $persetujuan = 2;        
        else
            $persetujuan = 1;        

        DB::update(
            DB::raw(
                "UPDATE PERMOHONAN 
                SET StatusPermohonan = $persetujuan
                WHERE IdPermohonan = $id"
            )
        );
    }

    /**
     * Soft-delete permohonan on database
     * Update 'deleted' column to 1
     * @param  String $hash Hash value of permohonan
     * @return void
     */
	public static function deletePermohonan($hash) 
    {
		// set deleted status permohonan given the hash    
        DB::table('permohonan')->where('hashPermohonan', $hash)->update(['deleted' => 1]);
	}
}