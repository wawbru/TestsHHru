<?php 
	error_reporting(E_ALL);
	
	class Fruit{
		public $mass;
		
		public function __construct($min_mass, $max_mass){
			$this->mass = rand($min_mass, $max_mass);
		}		
	}
	
	class Pear extends Fruit{
		
	}
	
	class Apple extends Fruit{
		
	}
	
	class Tree{
		public $registration_number = 0;
		public $fruits = array();
		
		public function __construct($registration_number, $min_count, $max_count, $min_mass, $max_mass){
			$this->registration_number = $registration_number; 
			$fruits_count = rand($min_count, $max_count);
			for ($i=0; $i<$fruits_count; $i++){
				$this->fruits[$i] = new Fruit($min_mass, $max_mass);
			}
		}
		
	}
	
	class Pear_Tree extends Tree{
		
	}

	class Apple_Tree extends Tree{
		
	}
	
	class Garden{
		public $apple_trees = array();
		public $pear_trees = array();
		public $collector; // сборщик
		
		public function __construct($pear_trees_count = 15, $apple_trees_count = 10){
			for ($i=0; $i<$pear_trees_count; $i++){
				$this->add_pear_tree();
			}
			for ($j=0; $j<$apple_trees_count; $j++){
				$this->add_apple_tree();
			}
			$this->collector = new Collector();
		}
		
		public function add_apple_tree(){
			$this->apple_trees[] = new Apple_Tree(count($this->apple_trees)+count($this->pear_trees), 40, 50, 150, 180);
		} 

		public function add_pear_tree(){
			$this->pear_trees[] = new Pear_Tree(count($this->apple_trees)+count($this->pear_trees), 0, 20, 130, 170);
		} 

		//сбор одного яблока
		public function collect_apple(){
			foreach($this->apple_trees as $k=>$apple_tree){
				if (!isset($this->collector->empty_apple_trees[$k])){
					if (!empty($this->apple_trees[$k]->fruits)){
						$fruit_first_key = array_key_first($this->apple_trees[$k]->fruits);
						$this->collector->apples[] = $this->apple_trees[$k]->fruits[$fruit_first_key];
						unset($this->apple_trees[$k]->fruits[$fruit_first_key]);
						break;
					} else {
						$this->collector->empty_apple_trees[$k] = true;
					}
				}
			}
		}

		//сбор одной груши
		public function collect_pear(){
			foreach($this->pear_trees as $k=>$pear_tree){
				if (!isset($this->collector->empty_pear_trees[$k])){
					if (!empty($this->pear_trees[$k]->fruits)){
						$fruit_first_key = array_key_first($this->pear_trees[$k]->fruits);
						$this->collector->pears[] = $this->pear_trees[$k]->fruits[$fruit_first_key];
						unset($this->pear_trees[$k]->fruits[$fruit_first_key]);
						break;
					} else {
						$this->collector->empty_pear_trees[$k] = true;
					}
				}
			}
		}
		
		//сбор всех фруктов
		public function collect_fruits(){
			while (count($this->apple_trees)>count($this->collector->empty_apple_trees)){
				$this->collect_apple();
			}
			while (count($this->pear_trees)>count($this->collector->empty_pear_trees)){
				$this->collect_pear();
			}
		}
	}
	
	//сборщик
	class Collector{
		public $apples = array();
		public $pears = array();
		public $empty_apple_trees = array();
		public $empty_pear_trees = array();
		
		public function __construct(){
			
		}
		
		public function get_apples_count(){
			return count($this->apples);
		}

		public function get_pears_count(){
			return count($this->pears);
		}
		
		public function get_apples_mass(){
			$sum = 0;
			foreach($this->apples as $k=>$apple){
				$sum += $apple->mass;
			}
			return $sum;			
		}
		
		public function get_pears_mass(){
			$sum = 0;
			foreach($this->pears as $k=>$pear){
				$sum += $pear->mass;
			}
			return $sum;
		}		
		
		public function show_results(){
			echo 'Количество яблок: ' . $this->get_apples_count() . '<br>';
			echo 'Масса яблок: ' . $this->get_apples_mass() . ' гр.<br>';
			echo 'Количество груш: ' . $this->get_pears_count() . '<br>';
			echo 'Масса груш: ' . $this->get_pears_mass() . ' гр.<br>';
		}
	}
	
	$garden = new Garden();
	$garden->collect_fruits();
	$garden->collector->show_results();
	
		
?>