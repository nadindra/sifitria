@extends('sidebar')

@section('sidebar_buatpinjam', 'active')

@section('konten')
<div class="subsection">
	<h5>Buat Permohonan Maintenance Barang</h5>
    <div class="divider"></div><br>
    <div id="multiform" class="row">
    	<form>
    		<div class="row form-row">
                <div class="col s6">
                    <b>Subjek</b><br>
                    <input name="subjek" placeholder="Contoh: Permohonan Registrasi Barang BEM" id="subject" type="text" class="validate" value="">
                </div><br>

                <div class="col s12">
                    <b>Catatan Pemohon</b> <br>
                    <textarea name="catatanpemohon" class="materialize-textarea" length="240" placeholder="Contoh: Tidak ada" ></textarea>
                </div>
            </div><br><hr>

            <div class="row form-row">
            	<div class="col s6">
            		Nama Barang :<br>
            		<span class="error red-text"></span><br>
                    <input type="text" name="namabarang" length="100" value="">
            	</div>

            </div>
          			<div class="row form-row">
                    	<div class="col s4 input-field">
                    		Jenis Barang :<br>
                    		<span class="error red-text"></span><br>
                            <input type="text" name="namabarang" length="100" value="">
                    	</div>
                    	<div class="col s4 input-field">
                    		Kategori Barang : <br>
                    		<span class="error red-text"></span><br>
                    		 <select name="kategoribarang">

                                <option disabled selected>Kategori Barang</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Furnitur">Furnitur</option>
                                <option value="Kategori A">Kategori A</option>
                                <option value="Kategori B">Kategori B</option>
                                <option value="Kategori C">Kategori C</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>      
                    	</div>
                    	<div class="col s4 input-field">
                    		Kondisi Barang :<br>
                    		<span class="error red-text"></span><br>
                    		<select name="kondisibarang">
                                <option disabled selected>Kondisi Barang</option>
                                <option value="Baru">Baru</option>
                                <option value="Bekas">Bekas</option>
                                <option value="Rusak">Rusak</option>                                
                            </select>
                    	</div>
                    </div>
            <div class="row form-row">
            	<div class="col s12 input-field">
            		Keterangan Kerusakaan :<br>
            		<span class="error red-text"></span><br>                 
                    <textarea name="kerusakanbarang" class="materialize-textarea" cols="30" rows="10"></textarea>
            	</div>
            </div>
            <div class="row">
                <div class="col s4 offset-s4 valign-wrapper">
                    <div class="valign">    
                        <form action="" method="POST"> 
                            {!! csrf_field() !!}                    
                            <button class="btn waves-light waves-effect teal">
                                Submit Permohonan
                                <i class="material-icons right">send</i>
                            </button>&nbsp;&nbsp;               
                        </form>             
                    </div>
                </div>        
            </div>
    	</form>
    </div>

</div>
@stop