<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahorcado</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Ahorcado</h1>
    <main>
        <?php
            session_start();
            /* new game */
            if(!isset($_SESSION['game']) || isset($_GET['restart']) == true){
                /* turn */
                $_SESSION['turn'] = 1;
                /* players lives */
                $_SESSION['p1Lives'] = 0;
                $_SESSION['p2Lives'] = 0;
                /* players words */
                $_SESSION['p1Word'] = '';
                $_SESSION['p2Word'] = '';
                /* players words in separate in array */
                $_SESSION['p1WordArr'] = '';
                $_SESSION['p2WordArr'] = '';
                /* players good letters founded */
                $_SESSION['p1ArrGood'] = '';
                $_SESSION['p2ArrGood'] = '';
                /* players letters found */
                $_SESSION['p1Letters'] = "1";
                $_SESSION['p2Letters'] = "1";
                /* game state */
                $_SESSION['game'] = 'p1Word';
                $_SESSION['winner'] = 0;
            }
            /* stats */
             

            /* get letters */
            if(isset($_GET['p1Letter']) || isset($_GET['p2Letter'])){
                if($_SESSION['turn'] == 1){
                    $_SESSION['p1Letters'] = $_SESSION['p1Letters'].$_GET['p1Letter'];   
                    
                    /* Compare words */
                    $del = true;
                    for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                        if($_SESSION['p2WordArr'][$i] == $_GET['p1Letter']){
                            $_SESSION['p1ArrGood'][$i] = $_SESSION['p2WordArr'][$i];
                            $del = false;
                        }
                    };
                    if($del == true){
                        $_SESSION['p1Lives']++;
                    }

                    $_SESSION['turn'] = 2; 
                    $_SESSION['game'] = 'intermediate';
                    header('Location: index.php');

                }else if($_SESSION['turn'] == 2){
                    $_SESSION['p2Letters'] = $_SESSION['p2Letters'].$_GET['p2Letter'];

                    /* Compare words */
                    $del = true;
                    for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                        if($_SESSION['p1WordArr'][$i] == $_GET['p2Letter']){
                            $_SESSION['p2ArrGood'][$i] = $_SESSION['p1WordArr'][$i];
                            $del = false;
                        }
                    };
                    if($del == true){
                        $_SESSION['p2Lives']++;
                    }
                    
                    $_SESSION['turn'] = 1; 
                    header('Location: index.php');
                };
            };


            /* player save word */
            if(isset($_GET['p1Word'])){
                $_SESSION['p1Word'] = $_GET['p1Word'];
                $_SESSION['game'] = 'p2Word';
                $_SESSION['p1WordArr']= str_split($_GET['p1Word']);
                for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                    $_SESSION['p2ArrGood'][$i] = 0;
                };
                header('Location: index.php');
            }
            if(isset($_GET['p2Word'])){
                $_SESSION['p2Word'] = $_GET['p2Word'];
                $_SESSION['game'] = 'game';
                $_SESSION['p2WordArr']= str_split($_GET['p2Word']);
                for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                    $_SESSION['p1ArrGood'][$i] = 0;
                };
                header('Location: index.php');
            }


            /* Check winner */
            if($_SESSION['game'] == 'game'){
                /* 1 word founded*/
                $cun = true;
                for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                   if($_SESSION['p2WordArr'][$i] != $_SESSION['p1ArrGood'][$i]){
                    $cun = false;
                   };
                };

                if($cun == true){
                    $_SESSION['game'] = 'winner';
                    $_SESSION['winner'] = 1;
                    header('Location: index.php');
                }

                /* 2 word founded*/
                $cun = true;
                for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                   if($_SESSION['p1WordArr'][$i] != $_SESSION['p2ArrGood'][$i]){
                    $cun = false;
                   };
                };
                if($cun == true){
                    $_SESSION['game'] = 'winner';
                    $_SESSION['winner'] = 2;
                    header('Location: index.php');
                } 

                  /* 1 no lives*/
                   if($_SESSION['p1Lives'] == 7){
                      $_SESSION['game'] = 'winner';
                      $_SESSION['winner'] = 2;
                      header('Location: index.php');
                  } 
  
                  /* 2  no lives*/
                   if($_SESSION['p2Lives'] == 7){
                    $_SESSION['game'] = 'winner';
                    $_SESSION['winner'] = 1;
                    header('Location: index.php');
                } 
            }
               
            /* winner screen */
            if($_SESSION['game'] == 'winner'){
                ?>
                    <section class="winnerrr">
                        <div>
                            <?php
                            if($_SESSION['winner'] == 1){
                                ?>
                                    <h1 class="ppp">GANADOR</h1>
                                <?php
                            }else{
                                ?>
                                    <h1 class="lll">PERDEDOR</h1>
                                <?php
                            }
                            ?>
                            <h2>PLAYER 1</h2>
                            <img class="xddd" src="img/<?php echo $_SESSION['p1Lives'] ?>.png" alt="">
                            <p>
                                <?php
                                   for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                                    if(!$_SESSION['p1ArrGood'][$i] != 0){
                                        echo "_";
                                    }else{
                                        echo $_SESSION['p1ArrGood'][$i];
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div>
                        <?php
                            if($_SESSION['winner'] == 2){
                                ?>
                                    <h1 class="ppp">GANADOR</h1>
                                <?php
                            }else{
                                ?>
                                    <h1 class="lll">PERDEDOR</h1>
                                <?php
                            }
                            ?>
                            <h2>PLAYER 2</h2>
                            <img class="xddd" src="img/<?php echo $_SESSION['p2Lives'] ?>.png" alt="">
                            <p>
                            <?php
                                    for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                                        if(!$_SESSION['p2ArrGood'][$i] != 0){
                                            echo "_";
                                        }else{
                                            echo $_SESSION['p2ArrGood'][$i];
                                        }
                                    }
                                ?>
                            </p>
                        </div>
                        <button onclick="window.location.href='index.php?restart=true'">RESTART</button>
                    </section>
                <?php

            }

            /* intermediate step */

            if($_SESSION['game'] == 'intermediate'){
                    if($_SESSION['turn'] != 1){
                        /* turn player 1 */
                        ?>
                        <section class="comida">
                        <div class="gameBoard">
                            <section class="board">
                                <img src="img/<?php echo $_SESSION['p1Lives'];?>.png">
                                <p>
                                    <?php
                                        for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                                            if(!$_SESSION['p1ArrGood'][$i] != 0){
                                                echo "_";
                                            }else{
                                                echo $_SESSION['p1ArrGood'][$i];
                                            }
                                        };
                                    ?>
                                </p>
    
                            </section>
    
                            <section class="letter">
                               <button>SIGUIENTE</button>
                            </section>
                            </div>
                            <div class="stats">
                                <div class="turn p1">
                                    <p>PLAYER 1</p>
                                </div>
                                <div class="lives">
                                    <?php
                                        for($i = 0; $i < (7-$_SESSION['p1Lives']); $i++){
                                            ?>
                                                <img src="img/h1.png" alt="">
                                            <?php
                                        }
                                        for($i = 0; $i < $_SESSION['p1Lives']; $i++){
                                            ?>
                                                <img src="img/h2.png" alt="">
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="otherGame">
                                    <div class="otherPlayer">
                                        <p>PLAYER 2</p>
                                    </div>
                                    <div class="otherBoard">
                                        <img src="img/<?php echo $_SESSION['p2Lives']?>.png" alt="">
                                        <p class="text-r">
                                        <?php
                                                for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                                                    if(!$_SESSION['p2ArrGood'][$i] != 0){
                                                        echo "_";
                                                    }else{
                                                        echo $_SESSION['p2ArrGood'][$i];
                                                    }
                                                }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="otherLives">
                                        <?php
                                            for($i = 0; $i < (7-$_SESSION['p2Lives']); $i++){
                                                ?>
                                                    <img src="img/h1.png" alt="">
                                                <?php
                                            }
                                            for($i = 0; $i < $_SESSION['p2Lives']; $i++){
                                                ?>
                                                    <img src="img/h2.png" alt="">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="restart" onclick="window.location.href='index.php?restart=true'">
                                    <p>RESTART</p>
                                </div>
                        </div>
                        </section>
                        <?php
                    }else{
                        /* turn player 2 */
                        ?>
                        <section class="comida">
                        <div class="gameBoard">
                        <section class="board">
                            <img src="img/<?php echo $_SESSION['p2Lives'];?>.png">
                            <p>
                                <?php
                                    for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                                        if(!$_SESSION['p2ArrGood'][$i] != 0){
                                            echo "_";
                                        }else{
                                            echo $_SESSION['p2ArrGood'][$i];
                                        }
                                    }
                                ?>
                            </p>
                        </section>
                        <section class="letter">
                            <!-- Q-P -->
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p2Letter=q'" <?php if(strpos($_SESSION['p2Letters'],'q')){echo 'disabled';}; ?>>Q</button>
                                <button onclick="window.location.href='index.php?p2Letter=w'" <?php if(strpos($_SESSION['p2Letters'],'w')){echo 'disabled';};; ?>>W</button>
                                <button onclick="window.location.href='index.php?p2Letter=e'" <?php if(strpos($_SESSION['p2Letters'],'e')){echo 'disabled';};; ?>>E</button>
                                <button onclick="window.location.href='index.php?p2Letter=r'" <?php if(strpos($_SESSION['p2Letters'],'r')){echo 'disabled';};; ?>>R</button>
                                <button onclick="window.location.href='index.php?p2Letter=t'" <?php if(strpos($_SESSION['p2Letters'],'t')){echo 'disabled';};; ?>>T</button>
                                <button onclick="window.location.href='index.php?p2Letter=y'" <?php if(strpos($_SESSION['p2Letters'],'y')){echo 'disabled';};; ?>>Y</button>
                                <button onclick="window.location.href='index.php?p2Letter=u'" <?php if(strpos($_SESSION['p2Letters'],'u')){echo 'disabled';};; ?>>U</button>
                                <button onclick="window.location.href='index.php?p2Letter=i'" <?php if(strpos($_SESSION['p2Letters'],'i')){echo 'disabled';};; ?>>I</button>
                                <button onclick="window.location.href='index.php?p2Letter=o'" <?php if(strpos($_SESSION['p2Letters'],'o')){echo 'disabled';};; ?>>O</button>
                                <button onclick="window.location.href='index.php?p2Letter=p'" <?php if(strpos($_SESSION['p2Letters'],'p')){echo 'disabled';};; ?>>P</button>
                            </div>
                            <!-- A-Ñ --> <br>
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p2Letter=a'" <?php if(strpos($_SESSION['p2Letters'],'a')){echo 'disabled';}; ?>>A</button>
                                <button onclick="window.location.href='index.php?p2Letter=s'" <?php if(strpos($_SESSION['p2Letters'],'s')){echo 'disabled';};; ?>>S</button>
                                <button onclick="window.location.href='index.php?p2Letter=d'" <?php if(strpos($_SESSION['p2Letters'],'d')){echo 'disabled';};; ?>>D</button>
                                <button onclick="window.location.href='index.php?p2Letter=f'" <?php if(strpos($_SESSION['p2Letters'],'f')){echo 'disabled';};; ?>>F</button>
                                <button onclick="window.location.href='index.php?p2Letter=g'" <?php if(strpos($_SESSION['p2Letters'],'g')){echo 'disabled';};; ?>>G</button>
                                <button onclick="window.location.href='index.php?p2Letter=h'" <?php if(strpos($_SESSION['p2Letters'],'h')){echo 'disabled';};; ?>>H</button>
                                <button onclick="window.location.href='index.php?p2Letter=j'" <?php if(strpos($_SESSION['p2Letters'],'j')){echo 'disabled';};; ?>>J</button>
                                <button onclick="window.location.href='index.php?p2Letter=k'" <?php if(strpos($_SESSION['p2Letters'],'k')){echo 'disabled';};; ?>>K</button>
                                <button onclick="window.location.href='index.php?p2Letter=l'" <?php if(strpos($_SESSION['p2Letters'],'l')){echo 'disabled';};; ?>>L</button>
                                <button onclick="window.location.href='index.php?p2Letter=ñ'" <?php if(strpos($_SESSION['p2Letters'],'ñ')){echo 'disabled';};; ?>>Ñ</button>
                            </div>
                            <!-- Z-M --> <br>
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p2Letter=z'" <?php if(strpos($_SESSION['p2Letters'],'z')){echo 'disabled';};; ?>>Z</button>
                                <button onclick="window.location.href='index.php?p2Letter=x'" <?php if(strpos($_SESSION['p2Letters'],'x')){echo 'disabled';};; ?>>X</button>
                                <button onclick="window.location.href='index.php?p2Letter=c'" <?php if(strpos($_SESSION['p2Letters'],'c')){echo 'disabled';};; ?>>C</button>
                                <button onclick="window.location.href='index.php?p2Letter=v'" <?php if(strpos($_SESSION['p2Letters'],'v')){echo 'disabled';};; ?>>V</button>
                                <button onclick="window.location.href='index.php?p2Letter=b'" <?php if(strpos($_SESSION['p2Letters'],'b')){echo 'disabled';};; ?>>B</button>
                                <button onclick="window.location.href='index.php?p2Letter=n'" <?php if(strpos($_SESSION['p2Letters'],'n')){echo 'disabled';};; ?>>N</button>
                                <button onclick="window.location.href='index.php?p2Letter=m'" <?php if(strpos($_SESSION['p2Letters'],'m')){echo 'disabled';};; ?>>M</button>
                            </div>
                        </section>
                        </div>
                        <div class="stats">
                            <div class="turn p2">
                                <p>PLAYER 2</p>
                            </div>
                            <div class="lives">
                                    <?php
                                        for($i = 0; $i < (7-$_SESSION['p2Lives']); $i++){
                                            ?>
                                                <img src="img/h1.png" alt="">
                                            <?php
                                        }
                                        for($i = 0; $i < $_SESSION['p2Lives']; $i++){
                                            ?>
                                                <img src="img/h2.png" alt="">
                                            <?php
                                        }
                                    ?>
                            </div>
                            <div class="otherGame">
                                    <div class="otherPlayer p1">
                                        <p>PLAYER 1</p>
                                    </div>
                                    <div class="otherBoard">
                                        <img src="img/<?php echo $_SESSION['p1Lives']?>.png" alt="">
                                        <p class="text-r">
                                            <?php
                                               for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                                                    if(!$_SESSION['p1ArrGood'][$i] != 0){
                                                        echo "_";
                                                    }else{
                                                        echo $_SESSION['p1ArrGood'][$i];
                                                    }
                                                };
                                            ?>
                                        </p>
                                    </div>
                                    <div class="otherLives">
                                        <?php
                                            for($i = 0; $i < (7-$_SESSION['p1Lives']); $i++){
                                                ?>
                                                    <img src="img/h1.png" alt="">
                                                <?php
                                            }
                                            for($i = 0; $i < $_SESSION['p1Lives']; $i++){
                                                ?>
                                                    <img src="img/h2.png" alt="">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            <div class="restart" onclick="window.location.href='index.php?restart=true'">
                                <p>RESTART</p>
                            </div>
                        </div>
                        </section>
                    <?php
                    };
            };

            /* get words */
            if($_SESSION['game'] == 'p1Word'){
                ?>
                <div class="container ce">
                    <div class="inputMain">
                        <div class="text-field">
                            <input id="passwd1" type="password" required autocomplete="off" name="password">
                            <label class="label">Palabra jugador 1</label>
                        </div>
                        <p id="error1" style="color:red; word-spacing:1px; display: none;">Escribe una palabra en minúsculas sin espacios ni números<br>mínimo 5 letras máximo 10 letras</p>
                        <button class="btn-sa" onclick="word()">Enviar</button>
                    </div>
            </div>
                <?php
            }else if($_SESSION['game'] == 'p2Word'){
                ?>
                 <div class="container ce">
                    <div class="inputMain">
                        <div class="text-field">
                            <input id="passwd1" type="password" required autocomplete="off" name="password">
                            <label class="label">Palabra jugador 2</label>
                        </div>
                        <p id="error1" style="color:red; word-spacing:1px; display: none;">Escribe una palabra en minúsculas sin espacios ni números<br>mínimo 5 letras máximo 10 letras</p>
                        <button class="btn-sa" onclick="word()">Enviar</button>
                </div>
            <?php
            }


         


            /* game */
            if($_SESSION['game'] == 'game'){
                if($_SESSION['turn'] == 1){
                    /* turn player 1 */
                    ?>
                    <section class="comida">
                    <div class="gameBoard">
                        <section class="board">
                            <img src="img/<?php echo $_SESSION['p1Lives'];?>.png">
                            <p>
                                <?php
                                    for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                                        if(!$_SESSION['p1ArrGood'][$i] != 0){
                                            echo "_";
                                        }else{
                                            echo $_SESSION['p1ArrGood'][$i];
                                        }
                                    };
                                ?>
                            </p>

                        </section>

                        <section class="letter">
                            <!-- Q-P -->
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p1Letter=q'" <?php if(strpos ($_SESSION['p1Letters'],'q')){echo 'disabled';}; ?>>Q</button>
                                <button onclick="window.location.href='index.php?p1Letter=w'" <?php if(strpos($_SESSION['p1Letters'],'w')){echo 'disabled';};; ?>>W</button>
                                <button onclick="window.location.href='index.php?p1Letter=e'" <?php if(strpos($_SESSION['p1Letters'],'e')){echo 'disabled';};; ?>>E</button>
                                <button onclick="window.location.href='index.php?p1Letter=r'" <?php if(strpos($_SESSION['p1Letters'],'r')){echo 'disabled';};; ?>>R</button>
                                <button onclick="window.location.href='index.php?p1Letter=t'" <?php if(strpos($_SESSION['p1Letters'],'t')){echo 'disabled';};; ?>>T</button>
                                <button onclick="window.location.href='index.php?p1Letter=y'" <?php if(strpos($_SESSION['p1Letters'],'y')){echo 'disabled';};; ?>>Y</button>
                                <button onclick="window.location.href='index.php?p1Letter=u'" <?php if(strpos($_SESSION['p1Letters'],'u')){echo 'disabled';};; ?>>U</button>
                                <button onclick="window.location.href='index.php?p1Letter=i'" <?php if(strpos($_SESSION['p1Letters'],'i')){echo 'disabled';};; ?>>I</button>
                                <button onclick="window.location.href='index.php?p1Letter=o'" <?php if(strpos($_SESSION['p1Letters'],'o')){echo 'disabled';};; ?>>O</button>
                                <button onclick="window.location.href='index.php?p1Letter=p'" <?php if(strpos($_SESSION['p1Letters'],'p')){echo 'disabled';};; ?>>P</button>
                            </div>
                            <!-- A-Ñ --> <br>
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p1Letter=a'" <?php if(strpos($_SESSION['p1Letters'],'a')){echo 'disabled';}; ?>>A</button>
                                <button onclick="window.location.href='index.php?p1Letter=s'" <?php if(strpos($_SESSION['p1Letters'],'s')){echo 'disabled';};; ?>>S</button>
                                <button onclick="window.location.href='index.php?p1Letter=d'" <?php if(strpos($_SESSION['p1Letters'],'d')){echo 'disabled';};; ?>>D</button>
                                <button onclick="window.location.href='index.php?p1Letter=f'" <?php if(strpos($_SESSION['p1Letters'],'f')){echo 'disabled';};; ?>>F</button>
                                <button onclick="window.location.href='index.php?p1Letter=g'" <?php if(strpos($_SESSION['p1Letters'],'g')){echo 'disabled';};; ?>>G</button>
                                <button onclick="window.location.href='index.php?p1Letter=h'" <?php if(strpos($_SESSION['p1Letters'],'h')){echo 'disabled';};; ?>>H</button>
                                <button onclick="window.location.href='index.php?p1Letter=j'" <?php if(strpos($_SESSION['p1Letters'],'j')){echo 'disabled';};; ?>>J</button>
                                <button onclick="window.location.href='index.php?p1Letter=k'" <?php if(strpos($_SESSION['p1Letters'],'k')){echo 'disabled';};; ?>>K</button>
                                <button onclick="window.location.href='index.php?p1Letter=l'" <?php if(strpos($_SESSION['p1Letters'],'l')){echo 'disabled';};; ?>>L</button>
                                <button onclick="window.location.href='index.php?p1Letter=ñ'" <?php if(strpos($_SESSION['p1Letters'],'ñ')){echo 'disabled';};; ?>>Ñ</button>
                            </div>
                            <!-- Z-M --> <br>
                            <div class="lbox">
                                <button onclick="window.location.href='index.php?p1Letter=z'" <?php if(strpos($_SESSION['p1Letters'],'z')){echo 'disabled';};; ?>>Z</button>
                                <button onclick="window.location.href='index.php?p1Letter=x'" <?php if(strpos($_SESSION['p1Letters'],'x')){echo 'disabled';};; ?>>X</button>
                                <button onclick="window.location.href='index.php?p1Letter=c'" <?php if(strpos($_SESSION['p1Letters'],'c')){echo 'disabled';};; ?>>C</button>
                                <button onclick="window.location.href='index.php?p1Letter=v'" <?php if(strpos($_SESSION['p1Letters'],'v')){echo 'disabled';};; ?>>V</button>
                                <button onclick="window.location.href='index.php?p1Letter=b'" <?php if(strpos($_SESSION['p1Letters'],'b')){echo 'disabled';};; ?>>B</button>
                                <button onclick="window.location.href='index.php?p1Letter=n'" <?php if(strpos($_SESSION['p1Letters'],'n')){echo 'disabled';};; ?>>N</button>
                                <button onclick="window.location.href='index.php?p1Letter=m'" <?php if(strpos($_SESSION['p1Letters'],'m')){echo 'disabled';};; ?>>M</button>
                            </div>
                        </section>
                        </div>
                        <div class="stats">
                            <div class="turn p1">
                                <p>PLAYER 1</p>
                            </div>
                            <div class="lives">
                                <?php
                                    for($i = 0; $i < (7-$_SESSION['p1Lives']); $i++){
                                        ?>
                                            <img src="img/h1.png" alt="">
                                        <?php
                                    }
                                    for($i = 0; $i < $_SESSION['p1Lives']; $i++){
                                        ?>
                                            <img src="img/h2.png" alt="">
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="otherGame">
                                <div class="otherPlayer">
                                    <p>PLAYER 2</p>
                                </div>
                                <div class="otherBoard">
                                    <img src="img/<?php echo $_SESSION['p2Lives']?>.png" alt="">
                                    <p class="text-r">
                                    <?php
                                            for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                                                if(!$_SESSION['p2ArrGood'][$i] != 0){
                                                    echo "_";
                                                }else{
                                                    echo $_SESSION['p2ArrGood'][$i];
                                                }
                                            }
                                        ?>
                                    </p>
                                </div>
                                <div class="otherLives">
                                    <?php
                                        for($i = 0; $i < (7-$_SESSION['p2Lives']); $i++){
                                            ?>
                                                <img src="img/h1.png" alt="">
                                            <?php
                                        }
                                        for($i = 0; $i < $_SESSION['p2Lives']; $i++){
                                            ?>
                                                <img src="img/h2.png" alt="">
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="restart" onclick="window.location.href='index.php?restart=true'">
                                <p>RESTART</p>
                            </div>
                    </div>
                    </section>
                    <?php
                }else{
                    /* turn player 2 */
                    ?>
                    <section class="comida">
                    <div class="gameBoard">
                    <section class="board">
                        <img src="img/<?php echo $_SESSION['p2Lives'];?>.png">
                        <p>
                            <?php
                                for($i = 0; $i < count($_SESSION['p1WordArr']);$i++){
                                    if(!$_SESSION['p2ArrGood'][$i] != 0){
                                        echo "_";
                                    }else{
                                        echo $_SESSION['p2ArrGood'][$i];
                                    }
                                }
                            ?>
                        </p>
                    </section>
                    <section class="letter">
                        <!-- Q-P -->
                        <div class="lbox">
                            <button onclick="window.location.href='index.php?p2Letter=q'" <?php if(strpos($_SESSION['p2Letters'],'q')){echo 'disabled';}; ?>>Q</button>
                            <button onclick="window.location.href='index.php?p2Letter=w'" <?php if(strpos($_SESSION['p2Letters'],'w')){echo 'disabled';};; ?>>W</button>
                            <button onclick="window.location.href='index.php?p2Letter=e'" <?php if(strpos($_SESSION['p2Letters'],'e')){echo 'disabled';};; ?>>E</button>
                            <button onclick="window.location.href='index.php?p2Letter=r'" <?php if(strpos($_SESSION['p2Letters'],'r')){echo 'disabled';};; ?>>R</button>
                            <button onclick="window.location.href='index.php?p2Letter=t'" <?php if(strpos($_SESSION['p2Letters'],'t')){echo 'disabled';};; ?>>T</button>
                            <button onclick="window.location.href='index.php?p2Letter=y'" <?php if(strpos($_SESSION['p2Letters'],'y')){echo 'disabled';};; ?>>Y</button>
                            <button onclick="window.location.href='index.php?p2Letter=u'" <?php if(strpos($_SESSION['p2Letters'],'u')){echo 'disabled';};; ?>>U</button>
                            <button onclick="window.location.href='index.php?p2Letter=i'" <?php if(strpos($_SESSION['p2Letters'],'i')){echo 'disabled';};; ?>>I</button>
                            <button onclick="window.location.href='index.php?p2Letter=o'" <?php if(strpos($_SESSION['p2Letters'],'o')){echo 'disabled';};; ?>>O</button>
                            <button onclick="window.location.href='index.php?p2Letter=p'" <?php if(strpos($_SESSION['p2Letters'],'p')){echo 'disabled';};; ?>>P</button>
                        </div>
                        <!-- A-Ñ --> <br>
                        <div class="lbox">
                            <button onclick="window.location.href='index.php?p2Letter=a'" <?php if(strpos($_SESSION['p2Letters'],'a')){echo 'disabled';}; ?>>A</button>
                            <button onclick="window.location.href='index.php?p2Letter=s'" <?php if(strpos($_SESSION['p2Letters'],'s')){echo 'disabled';};; ?>>S</button>
                            <button onclick="window.location.href='index.php?p2Letter=d'" <?php if(strpos($_SESSION['p2Letters'],'d')){echo 'disabled';};; ?>>D</button>
                            <button onclick="window.location.href='index.php?p2Letter=f'" <?php if(strpos($_SESSION['p2Letters'],'f')){echo 'disabled';};; ?>>F</button>
                            <button onclick="window.location.href='index.php?p2Letter=g'" <?php if(strpos($_SESSION['p2Letters'],'g')){echo 'disabled';};; ?>>G</button>
                            <button onclick="window.location.href='index.php?p2Letter=h'" <?php if(strpos($_SESSION['p2Letters'],'h')){echo 'disabled';};; ?>>H</button>
                            <button onclick="window.location.href='index.php?p2Letter=j'" <?php if(strpos($_SESSION['p2Letters'],'j')){echo 'disabled';};; ?>>J</button>
                            <button onclick="window.location.href='index.php?p2Letter=k'" <?php if(strpos($_SESSION['p2Letters'],'k')){echo 'disabled';};; ?>>K</button>
                            <button onclick="window.location.href='index.php?p2Letter=l'" <?php if(strpos($_SESSION['p2Letters'],'l')){echo 'disabled';};; ?>>L</button>
                            <button onclick="window.location.href='index.php?p2Letter=ñ'" <?php if(strpos($_SESSION['p2Letters'],'ñ')){echo 'disabled';};; ?>>Ñ</button>
                        </div>
                        <!-- Z-M --> <br>
                        <div class="lbox">
                            <button onclick="window.location.href='index.php?p2Letter=z'" <?php if(strpos($_SESSION['p2Letters'],'z')){echo 'disabled';};; ?>>Z</button>
                            <button onclick="window.location.href='index.php?p2Letter=x'" <?php if(strpos($_SESSION['p2Letters'],'x')){echo 'disabled';};; ?>>X</button>
                            <button onclick="window.location.href='index.php?p2Letter=c'" <?php if(strpos($_SESSION['p2Letters'],'c')){echo 'disabled';};; ?>>C</button>
                            <button onclick="window.location.href='index.php?p2Letter=v'" <?php if(strpos($_SESSION['p2Letters'],'v')){echo 'disabled';};; ?>>V</button>
                            <button onclick="window.location.href='index.php?p2Letter=b'" <?php if(strpos($_SESSION['p2Letters'],'b')){echo 'disabled';};; ?>>B</button>
                            <button onclick="window.location.href='index.php?p2Letter=n'" <?php if(strpos($_SESSION['p2Letters'],'n')){echo 'disabled';};; ?>>N</button>
                            <button onclick="window.location.href='index.php?p2Letter=m'" <?php if(strpos($_SESSION['p2Letters'],'m')){echo 'disabled';};; ?>>M</button>
                        </div>
                    </section>
                    </div>
                    <div class="stats">
                        <div class="turn p2">
                            <p>PLAYER 2</p>
                        </div>
                        <div class="lives">
                                <?php
                                    for($i = 0; $i < (7-$_SESSION['p2Lives']); $i++){
                                        ?>
                                            <img src="img/h1.png" alt="">
                                        <?php
                                    }
                                    for($i = 0; $i < $_SESSION['p2Lives']; $i++){
                                        ?>
                                            <img src="img/h2.png" alt="">
                                        <?php
                                    }
                                ?>
                        </div>
                        <div class="otherGame">
                                <div class="otherPlayer p1">
                                    <p>PLAYER 1</p>
                                </div>
                                <div class="otherBoard">
                                    <img src="img/<?php echo $_SESSION['p1Lives']?>.png" alt="">
                                    <p class="text-r">
                                        <?php
                                           for($i = 0; $i < count($_SESSION['p2WordArr']);$i++){
                                                if(!$_SESSION['p1ArrGood'][$i] != 0){
                                                    echo "_";
                                                }else{
                                                    echo $_SESSION['p1ArrGood'][$i];
                                                }
                                            };
                                        ?>
                                    </p>
                                </div>
                                <div class="otherLives">
                                    <?php
                                        for($i = 0; $i < (7-$_SESSION['p1Lives']); $i++){
                                            ?>
                                                <img src="img/h1.png" alt="">
                                            <?php
                                        }
                                        for($i = 0; $i < $_SESSION['p1Lives']; $i++){
                                            ?>
                                                <img src="img/h2.png" alt="">
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        <div class="restart" onclick="window.location.href='index.php?restart=true'">
                            <p>RESTART</p>
                        </div>
                    </div>
                    </section>
                <?php
                }
            }
        ?>
        </div>
    </main>
    <style>
    <?php
        if($_SESSION['game'] == 'p1Word' || $_SESSION['game'] == 'p2Word'){
            ?>
                main{
                    display: flex;
                    align-items: center;
                    justify-content: center;  
                }
                form {
                    display: flex;
                    justify-content: center;
                    flex-direction: column;
                    gap: 18px;
                    align-items: center;
                }
            <?php
        }else if($_SESSION['game'] == 'game'){
            ?> 
                main {
                    
                }
            <?php
        }
    ?>

</style>

<?php

 /* 
        stats
 echo "turn: ".$_SESSION['turn']."<br>";
 echo "p1Lives: ".$_SESSION['p1Lives']."<br>";
 echo "p2Lives: ".$_SESSION['p2Lives']."<br>";
 echo "p1Word: ".$_SESSION['p1Word']."<br>";
 echo "p2Word: ".$_SESSION['p2Word']."<br>";
 echo "p1WordArr: ".$_SESSION['p1WordArr']."<br>";
 echo "p2WordArr: ".$_SESSION['p2WordArr']."<br>";
 echo "p1ArrGood: ".$_SESSION['p1ArrGood']."<br>";
 echo "p2ArrGood: ".$_SESSION['p2ArrGood']."<br>";
 echo "p1Letters: ".$_SESSION['p1Letters']."<br>";
 echo "p2Letters: ".$_SESSION['p2Letters']."<br>";
 echo "game: ".$_SESSION['game']."<br>";
 echo "winner: ".$_SESSION['winner']."<br>"; */

?>
    <script>
        <?php
            if($_SESSION['game'] == 'p1Word'){
                ?>
                    const word = () => {
                        document.querySelector(".text-field").classList.remove("error")
                        document.querySelector("#error1").style.display = "hidden";

                        
                        const x =  document.querySelector("input").value;
                        if(x.split(" ").length <= 1 && x.match(/^[a-z]{5,10}$/g)){
                            window.location.href='index.php?p1Word='+x;
                        }else{
                            document.querySelector("#error1").style.display = "block";
                            document.querySelector(".text-field").classList.add("error");
                        }
                    }
                    
                <?php
            }else if($_SESSION['game'] == 'p2Word'){
                ?>
                     const word = () => {
                        document.querySelector(".text-field").classList.remove("error")
                        document.querySelector("#error1").style.display = "hidden";

                        const x =  document.querySelector("input").value;
                        if(x.split(" ").length <= 1 && x.match(/^[a-z]{5,10}$/g)){
                            window.location.href='index.php?p2Word='+x;
                        }else{
                            document.querySelector("#error1").style.display = "block";
                            document.querySelector(".text-field").classList.add("error");
                        }
                    }
                <?php
            }
        ?>
    </script>
</body>
</html>
