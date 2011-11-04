<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Autors: 	Krišs Rauhvargers
 * Izveidots: 	05.08.2011 
 * Fails:  	Darbības ar attēlu failiem
 */
class Image extends MY_Controller {
		
        /**
         * Parāda lietotājam attēlu (rezultāts būs image, nevis text!)
         * @param $id - attēla ID no datubāzes
         * @param $size - attēla izmērs (full, thumb, wXXX, hXXX)
        */
	public function view($id = 0, $size='full')
	{
                //izmantos fotogrāfiju modeli, jo faila taka ir pie bildes
		$this->load->model("photo/photo_model");
		
                
                //vispirms mēģināsim atrast, vai attēls vispār ir atrodams
                $currentPic = $this->photo_model->read_by_id($id);
                
                $data = $currentPic->send_sized($size, true);

	}
}