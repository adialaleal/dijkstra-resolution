<?php

// Complete the shortestReach function below.
function shortestReach($n, $edges, $s) {
    $sOrigem = 0;
    $sCongelado = $s;
    $m = count($edges);
    $jumps = $n * $m;
    for($i=0; $i < $m; $i++) {
    //definição de trechos, pontos e distancias
    $trajetos[$edges[$i][0].'_'.$edges[$i][1]]['distance'] = $edges[$i][2];
    $pontos[] = $edges[$i][0];
    $pontos[] = $edges[$i][1];
  }
  $idPercurso = 1;
  $pontos = array_unique($pontos);
  sort($pontos);
  //contrução dos percursos.
  $jumpsVerify = 0;
    echo '<pre>';
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
          echo ">>>>>>>>>> \n";
          print_r ($s.'_'.$value);
          //print_r ($rotaDefault);
          print_r ($registry);
          //print_r ($trajetos);
          echo "<<<<<<<<<< \n";
        if ($registry) {
            $idPercursoFracao = $idPercurso;
          if ($sOrigem){
              $idPercursoFracao++;
              $percurso[$idPercursoFracao] = $percurso[$idPercurso];
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
         //print_r ($trajetos);
        $verify++;
        $jumpsVerify++;
      }
    } while ($verify < $n);
    $s = $sCongelado;
    $sOrigem = 0;
    print_r  ($percurso);
    print_r($trajetos);
    $idPercurso = $idPercursoFracao;
    $idPercurso++;
      echo '('.$idPercurso.') '.$jumpsVerify.' <= '.$jumps."\n";
  } while ($jumpsVerify <= $jumps);
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
        
