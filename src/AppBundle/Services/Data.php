<?php

namespace AppBundle\Services;

class Data
{
    private $data;

    private $type = true;

    public function setType($type)
    {
        $this -> type = $type;

        return $this;
    }

    public function getType()
    {
        return $this -> type;
    }

    public function setData($users)
    {
        $data = array();

        foreach ($users as $key => $value) {
            if ( $value -> getProfile() -> getDateNaissance() != null ) {
                $dateNaissance = $value -> getProfile() -> getDateNaissance() -> format('d-m-Y');
            } else {
                $dateNaissance = null;
            }
            $user = array(
                'prenom' => $value -> getPrenom(),
                'nom' => $value -> getNom(),
                'dateNaissance' => $value -> getProfile() -> getDateNaissance() -> format('d-m-Y')
            );
            array_push($data, $user);
        }

        $this -> data = $data;
    }

    public function getData()
    {
        return $this -> data;
    }
}
