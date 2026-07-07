<?php

class FusionJsonHtml
{
  private $_tabJson = [];
  private $_html = '';
  private $_niveau = 0;
  
  public function __construct($json, $html)
  {
    $this->_tabJson[0] = $json;
    $this->_html = $html;
  }
  
  public function process(){
    return $this->traiterBloc($this->_html);
  }
  
  private function traiterBloc($bloc, $count = -1){
    $posDebAutre = 0;
    $nbBaliseOuverture = 0;
    
    // tant qu'il y a des boucles de 1er niveau à traiter
    $posDeb = strpos($bloc, "<fusionJsonLoop");
    while($posDeb !== false){
      // trouver la balise de fermeture correspondante
      $posDebAutre = $posDeb;
      $posFinAutre = $posDeb;
      while (0 === 0) {
        // chercher la prochaine balise de fermeture
        $posFin = strpos($bloc, "</fusionJsonLoop>", $posFinAutre);
        
        // chercher la prochaine balise d'ouverture
        $posDebAutre = strpos($bloc, "<fusionJsonLoop", $posDebAutre + 1);
        
        // Si l'on est au même niveau que la balise d'ouverture recherchée
        if($nbBaliseOuverture === 0){
          // si pas d'autre balise d'ouverture ou la balise de fin est avant la prochaine balise d'ouverture
          if($posDebAutre === false || $posDebAutre > $posFin){
            break;
          }
        }else{ // si dans un niveau supérieur
          
          // si pas de nouvelle balise d'ouverture
          if($posDebAutre === false){
            // recupérer la fin
            while($nbBaliseOuverture > 0){
              $nbBaliseOuverture --;
              $posFin = strpos($bloc, "</fusionJsonLoop>", $posFin + 1);
            }
            break;
            
          }elseif($posDebAutre > $posFin ){
            $posFinAutre = $posFin + 1;
            $posFin = strpos($bloc, "</fusionJsonLoop>", $posFinAutre);
            $nbBaliseOuverture --;
          }
        }
        
        if($posDebAutre < $posFin){
          $nbBaliseOuverture ++;
        }
      }
      
      // récupérer nom du noeud
      $posDebNoeud = strpos($bloc, 'list="', $posDeb);
      $posFinNoeud = strpos($bloc, '"',$posDebNoeud + 6);
      $posFermeNoeud = strpos($bloc, '>',$posDebNoeud + 6);
      $noeud = substr($bloc, $posDebNoeud + 6, $posFinNoeud - ($posDebNoeud +6));
      
      // extraire le bloc
      $blocLoop = substr($bloc, $posFermeNoeud + 1, $posFin - $posFermeNoeud - 1);
      
      // récupérer le tableau du noeud
      $jsonTab = $this->getNode($noeud);

      $this->_niveau ++;
            
      // pour chaque occurence
      $resultat = "";
      $compteur = 0;
      foreach($jsonTab as $json){
        $this->_tabJson[$this->_niveau] = $json;
        $compteur ++;
        $resultat .= $this->traiterBloc($blocLoop, $compteur);
      }
      $this->_niveau --;
      
      // remplacer la boucle par le résultat
      $bloc = substr_replace($bloc, $resultat, $posDeb, $posFin+18 - $posDeb - 1);

      $posDeb = strpos($bloc, "<fusionJsonLoop");
    }
    
    $posDeb = strpos($bloc, "<fusionJsonValue>");
    while($posDeb !== false){
      $posFin = strpos($bloc, '</fusionJsonValue>');
      $code = substr($bloc, $posDeb + 17, $posFin - ($posDeb +17));
      
      if($code == '[compteur]'){
        $value = $count;
      }else{
        $value = $this->getNode($code);
        if($value === null || $value == []){
          $value = '';
        }
      }

      $bloc = substr_replace($bloc, $value, $posDeb, $posFin+18 - $posDeb);
      
      $posDeb = strpos($bloc, "<fusionJsonValue>");
    }
    return $bloc;

  }
  
  private function getNode($noeud){
    $niveau = 0;

    $char = $noeud[0];
    if($char == "."){
      $niveau = $this->_niveau + 1;
    }
    while($char == '.'){
      $niveau -- ;
      $noeud = substr($noeud, 1);
      $char = $noeud[0];
    }
    
    $json = $this->_tabJson[$niveau];

    $path = explode(".", $noeud);

    foreach($path as $tag)
    {
      if(isset($node))
      {
        if(!array_key_exists($tag, $node)) {
          $node = [];
          break;
        }
        $node = $node[$tag];
      }
      else
      {
        if(!array_key_exists($tag, $json)) {
          $node = [];
          break;
        }
        $node = $json[$tag];
      }
    }
    if(!isset($node)){
      $node = [];
    }

    return $node;
    
  }
  
}

