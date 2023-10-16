function cocherTout(etat)
{
   var cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
   for(var i=0; i<cases.length; i++)     // on les parcourt
      if(cases[i].type == 'checkbox')     // si on a une checkbox...
         cases[i].checked = etat;     // ... on la coche ou non
}