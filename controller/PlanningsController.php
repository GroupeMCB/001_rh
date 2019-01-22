<?php

 /**
  *
  */
 class PlanningsController extends Controller
 {

   /**
   *
   **/
   public function dashboard()
   {
     $this->loadModel('Planning', 'planning');

     $mod = $this->loadModel('Campagnes', 'planning');

     $this->set('campagnes', $this->Campagnes->find());
     $this->render('dashboard');
   }

   /**
   *
   **/
   public function getAllPlannings()
   {
     $this->loadModel('Planning', 'planning');
     $this->render('getallplannings');
   }

   public function getCampagnePlanning()
   {
     $this->loadModel('Planning', 'planning');
     $this->render('getcampagneplannings');
   }

   /**
   *
   **/
   public function newPlanning()
   {
     $this->loadModel('Planning', 'planning');
     $this->render('newplanning');
   }

   public function planningsPermissions()
   {
     $this->loadModel('Planning', 'planning');
     $this->render('planningspermissions');
   }

   public function configuration()
   {
     $this->loadModel('Planning', 'planning');
     $this->render('configuration');
   }

 }
