<?php
class Modeltoko extends CI_Model
{
    function datatoko()
    {
        $this->db->limit('1');
        return $this->db->get('nn_namatoko');
    }

    function ambildatatoko($id)
    {
        return $this->db->get_where('nn_namatoko', ['idtoko' => $id]);
    }
}