<?php

global $tabWebServices;
$tabWebServices['userGroupManage'] = [true]; // [accredité]

function ws_userGroupManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}gro_uti(id_gro, id_uti, princ_gro_uti)
select *
from (select id_gro, :id_uti as id_uti, 0 as princ_gro_uti
from \${prefixe}groupe
where id_gro in (".formater_liste($globalData['params']['gro_uti']['liste_gro']).")) as tab_gro
where not exists (select 1 from \${prefixe}gro_uti ta where ta.id_gro = tab_gro.id_gro and ta.id_uti = :id_uti)";
#echo $SQLrequete;
    $SQLparams = Array(':id_uti' => $globalData['params']['gro_uti']['id_uti']);

  }else if($globalData['context'] == 'del'){
    $SQLrequete = "delete from \${prefixe}gro_uti
where id_gro in (".formater_liste($globalData['params']['gro_uti']['liste_gro']).")
and id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['gro_uti']['id_uti']);
    
  }else if($globalData['context'] == 'default'){
    $SQLrequete = "update \${prefixe}gro_uti
set princ_gro_uti = (case id_gro
when :id_gro then 1
else 0 
end)
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['gro_uti']['id_uti'], ':id_gro' => $globalData['params']['gro_uti']['id_gro'], );
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}utilisateur", 'id_uti', $globalData['params']['gro_uti']['id_uti']);

}