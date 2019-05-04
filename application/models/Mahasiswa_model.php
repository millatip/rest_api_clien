<?php 
use GuzzleHttp\Client;

class Mahasiswa_model extends CI_model {

    private $_client;

    public function __construct(){
        $this->_client = new Client([
            'base_uri' => 'http://localhost/restful_api/api/',
            'auth' => ['milla', 'keren']
        ]);
        $res = $this->_client->request('GET', 'mahasiswa', [
            'query' => ['milla-key'=>'rhs']
        ]);
    
        $res = json_decode($res->getBody()->getContents(), true);

        $nim = $res['data']->row->nim;
    
        $this->db->where('nim', $nim);
        $this->db->update('new_mahasiswa', $res['data']);

        //sudah dapat menampilkan data json dari server, sedang berusaha mengupdate database client dengan data baru yang masuk namun masih error
    }

    public function getAllMahasiswa()
    {
        return $this->db->get('new_mahasiswa')->result_array();
    }

    public function getMahasiswaById($nim)
    {
        
        $res = $this->_client->request('GET', 'mahasiswa', [
            'query' => [
                'milla-key'=>'tif123',
                'nim' => $nim
            ] 
        ]);

        $res = json_decode($res->getBody()->getContents(), true);
        return $res['data'][0];
        //$this->db->get('new_mahasiswa', ['nim' => $nim]);
    }

    public function ubahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nrp" => $this->input->post('nrp', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true)
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mahasiswa', $data);
    }


    public function tambahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nrp" => $this->input->post('nrp', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true)
        ];

        $this->db->insert('mahasiswa', $data);
    }

    public function hapusDataMahasiswa($id)
    {
        // $this->db->where('id', $id);
        //$this->db->delete('mahasiswa', ['id' => $id]);

        $res = $this->_client->request('DELETE', 'mahasiswa', [
            'form_params' => [
                'milla-key'=>'tif123',
                'id' => $id
            ]
        ]);

        $res = json_decode($res->getBody()->getContents(), true);
        return $res;
    }


    

    public function cariDataMahasiswa()
    {
        $keyword = $this->input->post('keyword', true);
        $this->db->like('nama', $keyword);
        $this->db->or_like('jurusan', $keyword);
        $this->db->or_like('nrp', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('mahasiswa')->result_array();
    }
}