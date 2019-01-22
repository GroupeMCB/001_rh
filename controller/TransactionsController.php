<?php 
/**
* 
*/
class TransactionsController extends Controller
{

	public function index()
	{
		$this->loadModel('Transaction');
		$conditions = array("pourcentage" => 75);
	    $d['transaction'] = $this->Transaction->find();
	    $d['total'] = $this->Transaction->findcount($conditions);
	    $this->set('trans',$d);
		
	}
	function view($id =null){
		if(!isset($id))
		{
				$this->e404('Nous ne pouvons vous afficher cette page');
		}else{
		$this->loadModel('Transaction');

		
		if($this->request->data){

			$this->Transaction->save($this->request->data);
		}

 
	    $transaction = $this->Transaction->find(array
	    	('conditions'=>'ID_AUTO ='.$id));

	   // $transaction = $this->Transaction->findfirst($transaction);

	    if(empty($transaction)){

	    	$this->e404('Impossible d\'afficher cette page');
	    	die();
	    }
	    debug($transaction); 
	    $this->Session->setFlash('Session ok','danger');
	    $this->set('transaction',$transaction);
	  }
	   
	}
}
 ?>