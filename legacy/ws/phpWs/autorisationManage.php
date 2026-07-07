<?php

global $tabWebServices;
$tabWebServices['autorisationManage'] = [true]; // [accredité]

function ws_autorisationManage($request)
{
  // récupération des variables globale
  global $globalData;

  // selon le contexte
  if($globalData['context'] == 'add'){
    $tabResult = $globalData['db']->prepLancerExcep("insert into \${prefixe}autorisation(id_gro, type_acc, code_acc)
select *
from (select :id_gro, 'COM' as type_acc, \${prefixe}composant.cd_com as code_acc
from \${prefixe}composant
where cd_com in (".formater_liste($globalData['params']['autorisation']['liste_com'], true).")
UNION
select :id_gro, 'FON' as type_acc, \${prefixe}fonction.cd_fon as code_acc
from \${prefixe}fonction
where cd_fon in(".formater_liste($globalData['params']['autorisation']['liste_fon'], true).")
UNION
select :id_gro, 'ETAT' as type_acc, \${prefixe}etatparam.cd_epm as code_acc
from \${prefixe}etatparam
where cd_epm in(".formater_liste($globalData['params']['autorisation']['liste_etat'], true).")) as tab_acc
where not exists (select 1 from \${prefixe}autorisation ta where ta.id_gro = :id_gro and ta.type_acc = tab_acc.type_acc and ta.code_acc = tab_acc.code_acc)",
            Array(':id_gro' => $globalData['params']['autorisation']['id_gro']),
            $globalData['errCode'], $globalData['errLib']);
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}groupe", 'id_gro', $globalData['params']['autorisation']['id_gro']);
    
  }else if($globalData['context'] == 'del'){
    $tabResult = $globalData['db']->prepLancerExcep("delete from \${prefixe}autorisation
where ((type_acc = 'FON' and code_acc in (".implode(",",explode(",",$globalData['params']['autorisation']['liste_fon']))."))
    or (type_acc = 'COM' and code_acc in (".implode(",",explode(",",$globalData['params']['autorisation']['liste_com']))."))
    or (type_acc = 'ETAT' and code_acc in (".implode(",",explode(",",$globalData['params']['autorisation']['liste_etat'])).")))
and id_gro = :id_gro",
            Array(':id_gro' => $globalData['params']['autorisation']['id_gro']),
            $globalData['errCode'], $globalData['errLib']);
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}groupe", 'id_gro', $globalData['params']['autorisation']['id_gro']);

  }
}
