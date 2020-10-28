
<?php

function findeSimple($a,$b){
  $simplenum= [];
  for ($i = 1; $i<=$b ;++$i){
    $num = 0;
    for ($j=1; $j <= $i ; $j++) { 
      if($i%$j==0){
        $num++;
      }
    }
    if($num==2 and $i>=$a ){$simplenum[]=$i;}
  }
    // print_r($simplenum);
    return ($simplenum);
}

// findeSimple(1,13);

function createTrapeze($a){
  $iter=count($a);
  $iterarr=count($a)/3;
  $a=array_chunk($a, 3);
  for ($i=0; $i < $iterarr ; $i++) { 
    $a[$i]['a']=$a[$i][0];
     unset($a[$i][0]);
     $a[$i]['b']=$a[$i][1];
     unset($a[$i][1]);
     $a[$i]['c']=$a[$i][2];
     unset($a[$i][2]);
  }
  // print_r($a);
 return ($a);
}

// createTrapeze(findeSimple(1,13));

function squareTrapeze($a){ 
  for ($i=0; $i < count($a) ; $i++) { 
    $a[$i]['s'] = ($a[$i]['a'] + $a[$i]['b'])/2 * $a[$i]['c'];
  }
  // print_r($a);  
  return ($a);
}

// squareTrapeze(createTrapeze(findeSimple(1,13)));

function getSizeForLimit($a,$b){
  $arrayLimit=[];
  for ($i=0; $i < count($a) ; $i++) { 
    if($a[$i]['s']<=$b){
      $arrayLimit[$i]=$a[$i]['s'];
    }
   }
    // print_r($arrayLimit);
  return($arrayLimit);
  }

// getSizeForLimit(squareTrapeze(createTrapeze(findeSimple(1,13))),117);

function getMin($a){
  $min = null;
  foreach ($a as  $v) {
    if($v < $min or $min === null)
      {
       $min = $v;
      }
    }
  
  print_r($min);
}

$arrayForFunctionGetMin = array('a'=> 6 , 'b' => 1 , 'c' => 2);
// getMin($arrayForFunctionGetMin );

function printTrapeze($a){
  echo '<table border = "1">';
    foreach( $a as $k=> $v) { 
      echo '<tr>'.'</tr>';
      foreach ($v as $key=> $volue){
        if($v['s']%2 != 0 or round($v['s'], 0 , PHP_ROUND_HALF_ODD)%2 != 0){echo '<td bgcolor="#ffcc00">' .$key."=>". $volue . '</td>';}
        else {echo '<td >' .$key."=>". $volue . '</td>';}
      }
    }
  echo '</table>';
}
// printTrapeze(squareTrapeze(createTrapeze(findeSimple(1,13))));

abstract class BaseMath{
  function __construct($a, $b, $c){
    $this->a=$a;
    $this->b=$b;
    $this->c=$c;
  }
  function exp1(){
    return $this->a*pow($this->b,$this->c);
  }
  function exp2(){
    return pow(($this->a/$this->b),$this->c);
  }
  function getValue(){
    return F1::fDecision();
  }
}


class F1 extends BaseMath{
  public $f;
  function __construct($a ,$b ,$c){
    parent::__construct( $a, $b, $c);
  }
  function fDecision(){
  return $this->f=$this->exp1()+ pow(($this->exp2() % 3),min($a,$b,$c));
  }  
  function getValue(){
    return BaseMath::getValue();
  }   
}
  
$matheamtica = new F1(5,3,4);
echo $matheamtica -> getValue();
?>