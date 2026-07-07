<?php
require_once('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

global $tabWebServices;
$tabWebServices['impressionListeManage'] = [true]; // [accredité]

function ws_impressionListeManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    
    $SQLrequete = "insert into \${prefixe}impression_liste_param(lib_ilp, id_req, actif_ilp)
values( :lib_ilp, :id_req, :actif_ilp)";
    $SQLparams = Array(
        ':lib_ilp' => $globalData['params']['impression_liste_param']['lib_ilp'],
        ':id_req' => $globalData['params']['impression_liste_param']['id_req'],
        ':actif_ilp' => $globalData['params']['impression_liste_param']['actif_ilp']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $globalData['params']['impression_liste_param']['id_ilp'] = $globalData['db']->getLastId();
    
    foreach( $globalData['params']['impression_liste_param']['tab_champs'] as $idChamp => $champ )
    {
      $SQLrequete = "insert into \${prefixe}impression_liste_champ(id_ilp, ordre_ilc, colonne_ilc, libelle_ilc, type_ilc)
values(:id_ilp, :ordre_ilc, :colonne_ilc, :libelle_ilc, :type_ilc)";
     $SQLparams = Array(
        ':id_ilp' => $globalData['params']['impression_liste_param']['id_ilp'],
        ':ordre_ilc' => $champ['ordre_ilc'],
        ':colonne_ilc' => $champ['colonne_ilc'],
        ':libelle_ilc' => $champ['libelle_ilc'],
        ':type_ilc' => $champ['type_ilc']
      );
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    }
    
  }else if($globalData['context'] == 'mod'){
    
    $SQLrequete = "update \${prefixe}impression_liste_param
set lib_ilp = :lib_ilp
,id_req = :id_req
, actif_ilp = :actif_ilp
where id_ilp = :id_ilp";
    $SQLparams = Array(
        ':lib_ilp' => $globalData['params']['impression_liste_param']['lib_ilp'],
        ':id_req' => $globalData['params']['impression_liste_param']['id_req'],
        ':actif_ilp' => $globalData['params']['impression_liste_param']['actif_ilp'],
        ':id_ilp' => $globalData['params']['impression_liste_param']['id_ilp']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    $SQLrequete = "delete from \${prefixe}impression_liste_champ
where id_ilp = :id_ilp";
    $SQLparams = Array(
        ':id_ilp' => $globalData['params']['impression_liste_param']['id_ilp']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    foreach( $globalData['params']['impression_liste_param']['tab_champs'] as $idChamp => $champ )
    {
      $SQLrequete = "insert into \${prefixe}impression_liste_champ(id_ilp, ordre_ilc, colonne_ilc, libelle_ilc, type_ilc)
values(:id_ilp, :ordre_ilc, :colonne_ilc, :libelle_ilc, :type_ilc)";
     $SQLparams = Array(
        ':id_ilp' => $globalData['params']['impression_liste_param']['id_ilp'],
        ':ordre_ilc' => $champ['ordre_ilc'],
        ':colonne_ilc' => $champ['colonne_ilc'],
        ':libelle_ilc' => $champ['libelle_ilc'],
        ':type_ilc' => $champ['type_ilc']
      );
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    }
    
  }else if($globalData['context'] == 'del'){
    
    // suppresion impression de liste
    $SQLrequete = "delete
from \${prefixe}impression_liste_param
where id_ilp = :id_ilp";
    $SQLparams = Array(':id_ilp' => $globalData['params']['impression_liste_param']['id_ilp']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
  }else if($globalData['context'] == 'getChamps'){
    
    $globalData['message']['response']['erreur'] = '';
    $globalData['message']['response']['result'] = [];
    
    // Récupérer la requête result
    $SQLrequete = "SELECT select_req
FROM \${prefixe}requete
WHERE id_req = :id_req
AND result_req = 'result'";
    $SQLparams = Array(':id_req' => $globalData['params']['id_req']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    if(count($tabResult) == 0){
      $globalData['message']['response']['erreur'] = "Cette requête ne contient pas d'élément 'result'";
      
    }else if(count($tabResult) > 1){
      $globalData['message']['response']['erreur'] = "Cette requête contient plus d'un d'élément 'result'";
      
    }else{
      // executer la requête
      $SQLrequete = $tabResult[0]['select_req'];
      $globalData['message']['response']['result'] = $globalData['db']->getTabColumDefinition($SQLrequete);
    }
    return;
    
  }else if($globalData['context'] == 'requestCount' || $globalData['context'] == 'requestExport'){
    
    // Récupérer la requête
    $SQLrequete = "SELECT TabRequete.select_req, TabListeParam.lib_ilp
FROM \${prefixe}impression_liste_param AS TabListeParam
INNER JOIN \${prefixe}requete AS TabRequete ON TabRequete.id_req = TabListeParam.id_req
WHERE TabListeParam.id_ilp = :id_ilp";
    $SQLparams = Array(':id_ilp' => $globalData['params']['ImpressionListe']['id_ilp']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) === 0)
    {
      $globalData['errCode'] = "ERR_EXPORT_01";
      $globalData['errLib'] = "La requête de l'export n'existe pas !";
      throw new Exception('ERREUR');
    }
    
    $requeteExport = $tabResult[0]['select_req'];
    $titre = $tabResult[0]['lib_ilp'];
    
    // Construire la requête
    $select = '';
    $where = '';
    $SQLparams = Array();
    foreach($globalData['params']['ImpressionListe']['TabChampsExport'] as $idx => $champ){
      
      if($select <> ''){
        $select .= ', ';
      }
      $select .= $champ['colonne_ilc'];
      
      if($champ['filtre'] !== '-'){
        if($where !== ''){
          $where .= "
AND ";
        }
        
        if($champ['filtre'] === '='){
          if($champ['type_ilc'] === 'TEXT'){
            $where .= 'UPPER('.$champ['colonne_ilc'] . ') = UPPER(:' . $champ['colonne_ilc']. '1)';
          }else{
            $where .= $champ['colonne_ilc'] . ' = :' . $champ['colonne_ilc']. '1';
          }
          
        }elseif($champ['filtre'] === '<>'){
          if($champ['type_ilc'] === 'TEXT'){
            $where .= 'UPPER('.$champ['colonne_ilc'] . ') <> UPPER(:' . $champ['colonne_ilc']. '1)';
          }else{
            $where .= $champ['colonne_ilc'] . ' <> :' . $champ['colonne_ilc']. '1';
          }
          
        }elseif($champ['filtre'] === '>'){
          $where .= $champ['colonne_ilc'] . ' > :' . $champ['colonne_ilc']. '1';
          
        }elseif($champ['filtre'] === '>='){
          $where .= $champ['colonne_ilc'] . ' >= :' . $champ['colonne_ilc']. '1';
          
        }elseif($champ['filtre'] === '<'){
          $where .= $champ['colonne_ilc'] . ' < :' . $champ['colonne_ilc']. '1';
          
        }elseif($champ['filtre'] === '<='){
          $where .= $champ['colonne_ilc'] . ' <= :' . $champ['colonne_ilc']. '1';
          
        }elseif($champ['filtre'] === '[]'){
          if($champ['type_ilc'] === 'DATE' || $champ['type_ilc'] === 'DATETIME' ){
            $where .= 'DATE('.$champ['colonne_ilc'] . ') between :' . $champ['colonne_ilc']. '1 and :' . $champ['colonne_ilc']. '2';
          }else{
            $where .= $champ['colonne_ilc'] . ' between :' . $champ['colonne_ilc']. '1 and :' . $champ['colonne_ilc']. '2';
          }
          $SQLparams[$champ['colonne_ilc']. '2'] = $champ['valeur2'];
          
        }elseif($champ['filtre'] === '?%'){
          $where .= 'UPPER('.$champ['colonne_ilc'] . ') like UPPER(CONCAT(:' . $champ['colonne_ilc']. "1,'%'))";
          
        }elseif($champ['filtre'] === '%?%'){
          $where .= 'UPPER('.$champ['colonne_ilc'] . ") like UPPER(CONCAT('%',:" . $champ['colonne_ilc']. "1,'%'))";
          
        }
        $SQLparams[$champ['colonne_ilc']. '1'] = $champ['valeur1'];
        
      }
    }
    
    if($globalData['context'] == 'requestCount'){
      $SQLrequete = "SELECT COUNT(1) AS NbResult
FROM ( ".$requeteExport.") AS TabResult";
    }else{
      $SQLrequete = "SELECT ". $select."
FROM ( ".$requeteExport.") AS TabResult";
    }
    if($where <> ''){
      $SQLrequete .= "
WHERE ".$where;
    }
    
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    if($globalData['context'] == 'requestCount'){
      $globalData['message']['response']['NbResult'] = $tabResult[0]['NbResult'];
      
    }else{
      
      // Préparer l'objet mémoire excel
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getProperties()->setCreator("Pluri'L")
          ->setLastModifiedBy("Pluri'L")
          ->setTitle('Export ' .$titre)
          ->setSubject('Export ' .$titre)
          ->setDescription('Export ' .$titre)
          ->setKeywords('office 2007 openxml php')
          ->setCategory($titre);
      
      // Créer la première ligne
      $idxNum = 0;
      foreach($globalData['params']['ImpressionListe']['TabChampsExport'] as $idx => $champ){
        $idxNum ++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue(Coordinate::stringFromColumnIndex($idxNum).'1', $champ['libelle_ilc']);
        $spreadsheet->getActiveSheet()->getColumnDimension(Coordinate::stringFromColumnIndex($idxNum))->setAutoSize(true);
      }
      
      // Pour chaque ligne de résultat
      $idxNumRow = 1;
      foreach($tabResult as $idxRow => $row){
        $idxNumRow ++;
        $idxNumCol = 0;
        foreach($row as $idxCol => $colValue){
          $idxNumCol ++;
          $type = $globalData['params']['ImpressionListe']['TabChampsExport'][$idxNumCol -1]['type_ilc'];
          if($type === 'NUMBER' ){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow), floatval($colValue));
            
          }elseif($type === 'DATE' ){
            if($colValue <> ''){
              $spreadsheet->setActiveSheetIndex(0)->setCellValue(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow), Date::PHPToExcel($colValue));
              $spreadsheet->getActiveSheet()
                  ->getStyle(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow))
                  ->getNumberFormat()
                  ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            }
          }elseif($type === 'DATETIME' ){
            if($colValue <> ''){
              $spreadsheet->setActiveSheetIndex(0)->setCellValue(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow), Date::PHPToExcel($colValue));
              $spreadsheet->getActiveSheet()
                  ->getStyle(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow))
                  ->getNumberFormat()
                  ->setFormatCode("dd/mm/yyyy hh:mm:ss");
            }
          }else{
            $spreadsheet->setActiveSheetIndex(0)->setCellValue(Coordinate::stringFromColumnIndex($idxNumCol).($idxNumRow), $colValue);
          }
        }
      }

      // Rename worksheet
      $spreadsheet->getActiveSheet()->setTitle($titre);

      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $spreadsheet->setActiveSheetIndex(0);
      
      // Récupérer le binary pour le passer en base64
      $writer = new Xlsx($spreadsheet);
      ob_start();
      $writer->save('php://output');
      $globalData['message']['response']['excelBase64'] = base64_encode(ob_get_contents());
      $globalData['message']['response']['excelNom'] = "Export_".$titre.".xlsx";
      ob_end_clean();
      
    }

  }
  
  //Ajouter historique impression_liste_param
  if($globalData['context'] == 'add' || $globalData['context'] == 'mod' || $globalData['context'] == 'del'){
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}impression_liste_param", 'id_ilp', $globalData['params']['impression_liste_param']['id_ilp']);
  }
}

