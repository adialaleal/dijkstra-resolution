<?php

// Complete the shortestReach function below.
function shortestReach($n, $edges, $s) {
    print_r ($n);
    echo "<br>";
    print_r ($edges);
    echo "<br>";
    print_r ($s);
    exit;

    $sOrigem = 0;
    $sCongelado = $s;
    $m = count($edges);
    $jumps = $n * $m;
    for($i=0; $i < $m; $i++) {
    //definição de trechos, pontos e distancias
    if(!isset($edges[$i][1])){
        $trajetos[$edges[$i][0].'_0']['distance'] = -1;
        $trajetos[$edges[$i][0].'_0']['verified'] = 1;
    }else $trajetos[$edges[$i][0].'_'.$edges[$i][1]]['distance'] = $edges[$i][2];
    $pontos[] = $edges[$i][0];
    $pontos[] = $edges[$i][1];
  }
  $idPercurso = 1;
  $pontos = array_unique($pontos);
  sort($pontos);
  //contrução dos percursos.
  $jumpsVerify = 0;
  //Montando todos os percursos possíveis.
  do {
    //Construindo todos os trajetos possíveis do percuso.
    do {
      $registry = 0;
      $verify = 0;
      //Percorrendo todos os pontos existentes do percurso.
      foreach ($pontos as $value){
        //procurando os trajetos cadastrados
        if (isset($trajetos[$s.'_'.$value])
          && !isset($trajetos[$s.'_'.$value]['verified'])
          && $s != $sOrigem){
            $rotaDefault = $rota = $s.'_'.$value;
            $registry = 1;
        } elseif (isset($trajetos[$value.'_'.$s])
          && !isset($trajetos[$value.'_'.$s]['verified'])
          && $value != $sOrigem) {
            $rota = $value.'_'.$s;
            $rotaDefault = $s.'_'.$value;
            $registry = 1;
        } else $registry = 0;
        if ($registry) {
            $idPercursoFracao = $idPercurso;
          if ($sOrigem){
              $percursoFracao[$idPercursoFracao] = $percurso[$idPercurso];
              $idPercurso++;
              $percurso[$idPercurso] = $percursoFracao[$idPercursoFracao];
          }
          //registrando da existencia do trecho.
          $trajetos[$rota]['verified'] = 1;
          //adicionando da distancia do trecho ao percurso.
          if (!isset($percurso[$idPercurso]['distance']))
              $percurso[$idPercurso]= array('distance'=>0);
          $percurso[$idPercurso]['distance'] += $trajetos[$rota]['distance'];
          //recuperando a origem e o destino do trecho.
          $startEnd = explode('_', $rotaDefault);
          //atualizando dos pontos percurso A -> B -> C ==> A -> C
          if (empty($percurso[$idPercurso]['start']))
              $percurso[$idPercurso]['start'] = $startEnd[0];
          $percurso[$idPercurso]['finaly'] = $startEnd[1];
          //concatenando os trechos percorridos da rota.
          if (!isset($percurso[$idPercurso]['route']))
              $percurso[$idPercurso]['route'] = null;
          $percurso[$idPercurso]['route'] .= $rota.',';
          $s = $startEnd[1];
          $registry = 0;
          $sOrigem = $sCongelado;
          break;
        }
        $verify++;
        $jumpsVerify++;
      }
    } while ($verify < $n);
    $s = $sCongelado;
    $sOrigem = 0;
    //$idPercurso = $idPercursoFracao;
    $idPercurso++;
  } while ($jumpsVerify <= $jumps);
    /* [1] => Array
        (
            [distance] => 24
            [start] => 1
            [finaly] => 2
            [route] => 1_2,
        )
    */
  for($i = 1;$i <= count($percurso); $i++){
    //$percurso[$i]
    for($j = 1;$j <= count($percurso); $j++){
      //$percurso[$i]['deleted'] = false;
      if($percurso[$i]['start'] == $percurso[$j]['start']
        && $percurso[$i]['finaly'] == $percurso[$j]['finaly']
        && $percurso[$i]['distance'] > $percurso[$j]['distance'])
          $percurso[$i]['deleted'] = true;
    }
  }
    for($i = 0;$i <= count($percurso); $i++)
      if (!isset($percurso[$i]['deleted']))
      $result[] = $percurso[$i]['distance'];
     return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $t);

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    fscanf($stdin, "%[^\n]", $nm_temp);
    $nm = explode(' ', $nm_temp);

    $n = intval($nm[0]);

    $m = intval($nm[1]);

    $edges = array();

    for ($i = 0; $i < $m; $i++) {
        fscanf($stdin, "%[^\n]", $edges_temp);
        $edges[] = array_map('intval', preg_split('/ /', $edges_temp, -1, PREG_SPLIT_NO_EMPTY));
    }

    fscanf($stdin, "%d\n", $s);

    $result = shortestReach($n, $edges, $s);

    fwrite($fptr, implode(" ", $result) . "\n");
}

fclose($stdin);
fclose($fptr);
