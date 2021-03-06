<?php
class Syndique{

	//attributes
	private $_id;
	private $_date;
	private $_montant;
    private $_status;
	private $_idClient;
	private $_idProjet;
	private $_created;
	private $_createdBy;
	private $_updated;
	private $_updatedBy;

	//le constructeur
    public function __construct($data){
        $this->hydrate($data);
    }
    
    //la focntion hydrate sert à attribuer les valeurs en utilisant les setters d\'une façon dynamique!
    public function hydrate($data){
        foreach ($data as $key => $value){
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }

	//setters
	public function setId($id){
    	$this->_id = $id;
    }
	public function setDate($date){
		$this->_date = $date;
   	}

	public function setMontant($montant){
		$this->_montant = $montant;
   	}
    
    public function setStatus($status){
        $this->_status = $status;
    }

	public function setIdClient($idClient){
		$this->_idClient = $idClient;
   	}

	public function setIdProjet($idProjet){
		$this->_idProjet = $idProjet;
   	}

	public function setCreated($created){
        $this->_created = $created;
    }

	public function setCreatedBy($createdBy){
        $this->_createdBy = $createdBy;
    }

	public function setUpdated($updated){
        $this->_updated = $updated;
    }

	public function setUpdatedBy($updatedBy){
        $this->_updatedBy = $updatedBy;
    }

	//getters
	public function id(){
    	return $this->_id;
    }
	public function date(){
		return $this->_date;
   	}

	public function montant(){
		return $this->_montant;
   	}
    
    public function status(){
        return $this->_status;
    }

	public function idClient(){
		return $this->_idClient;
   	}

	public function idProjet(){
		return $this->_idProjet;
   	}

	public function created(){
        return $this->_created;
    }

	public function createdBy(){
        return $this->_createdBy;
    }

	public function updated(){
        return $this->_updated;
    }

	public function updatedBy(){
        return $this->_updatedBy;
    }

}