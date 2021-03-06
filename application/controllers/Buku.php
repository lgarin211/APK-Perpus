<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buku extends CI_Controller
{
	public function index()
	{
		// $this->load->view('welcome_message');
		$this->Data();
	}
	public function editDataBuku()
	{
		// var_dump($_POST);die;
		if (!empty($_POST)) {
			$this->db->set($_POST['dos'], $_POST['pasing']);
			$this->db->where('ID_BUKU', $_POST['ID_BUKU']);
			$this->db->update('BUKU');
		}
		redirect(base_url('/Buku'));
	}
	public function Data()
	{
		$query = $this->db->get('BUKU')->result_array();
		$data['allBuku'] = $query;
		$data['res'] = array_reverse($query);
		// var_dump($_SESSION);die;
		$this->load->view('BUK/head');
		$this->load->view('BUK/fintlike', $data, FALSE);
		$this->load->view('BUK/foot');
		// var_dump($data);die;
	}
	public function Pinjam()
	{
		var_dump($_GET);
		$v = array('ID_BUKU' => $_GET['buku']);
		$val = $this->db->get_where('BUKU', $v)->result();
		$val = $val[0];
		$dos = array(
			'OutSide' => ($val->OutSide) + 1,
		);
		$this->db->where($v);
		$this->db->update('BUKU', $dos);

		$das = array(
			'ID_BUKU' => $val->ID_BUKU,
			'ID_Peminjam' => $_SESSION['id'],
			'Barcode_Buku' => 'sampe;',
			'tgl_keluar' => 2020 - 12 - 01,
			'deatline' => 2020 - 12 - 01,
			'kembali' => 0,

		);
		$this->db->insert('Peminjaman', $das);
		redirect('/');
	}

	public function Kembali()
	{
		if (!empty($_GET)) {
			// var_dump($_GET);
			$v = array('ID_BUKU' => $_GET['buku']);
			$val = $this->db->get_where('BUKU', $v)->result();
			$val = $val[0];
			$dos = array(
				'OutSide' => ($val->OutSide) - 1,
			);
			$this->db->where($v);

			$this->db->update('BUKU', $dos);
			$las = array(
				'ID_BUKU' => $_GET['buku'],
				'ID_Peminjam' => $_GET['peminjam'],
				'Barcode_Buku' => $_GET['Barcode']
			);
			$das = array(
				'kembali' => 1
			);
			$this->db->where($las);
			$this->db->update('Peminjaman', $das);
			redirect('/Buku_Kembali');
		}
		$v = array('ID_Peminjam=' => $_SESSION['id']);
		$val = $this->db->get_where('Peminjaman', $v)->result_array();
		if (!empty($val)) {
			# code...
			foreach ($val as $key => $value) {
				$dol = array(
					'ID_BUKU' => ($value['ID_BUKU']),
				);
				$das = $this->db->get_where('BUKU', $dol)->result_array();
				$dom[$key] = $das[0];
			}
			$data['allBuku'] = array_reverse($dom);
		}
		// var_dump($dom);die;
		$data['nu'] = $val;
		$this->load->view('LengkapiData/allpin', $data, FALSE);

		// var_dump($data);die;
	}
	public function like($sampel = 'Judul_Buku')
	{

		if (empty($_GET)) {
			$this->db->like($sampel, '');
			$val = $this->db->get_where('BUKU')->result_array();
			$data['res'] = $val;
			// var_dump($data);die;	
			$this->load->view('BUK/fintlike', $data);
		} elseif (!empty($_GET['mog'] == 'm')) {
			$this->db->like($sampel, $_GET['key']);
			$val = $this->db->get_where('BUKU')->result_array();
			$data['res'] = $val;
			$this->load->view('BUK/fintlikeajax', $data);
		} elseif (!empty($_GET['mog'] == 'z')) {
			$this->db->like($sampel, $_GET['key']);
			$val = $this->db->get_where('BUKU')->result_array();
			$data['res'] = $val;
			$this->load->view('BUK/fintlikeajax', $data);
		}
	}

	public function Input_Buku()
	{
		redirect('Siswa/form');
	}
}
