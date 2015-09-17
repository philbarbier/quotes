
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
            
            <h1>Viewing quote <?=$qr ?></h1>
              
            <?
            
              $res = getquote($qr);
              
              if(!check_empty($res[0][col_qtext])) {
              
                for($i=0; $i < count($res); $i++) {
            
            ?>
              
              <p class="quote"><a class="quote" href="/?<?=$res[$i][col_qid] ?>">#<?=$res[$i][col_qid] ?></a> <a class="nlquote" href="/?markdn&q=<?=$res[$i][col_qid] ?>">-</a> (<?=$res[$i][col_score] ?>) <a class="nlquote" href="/?markup&q=<?=$res[$i][col_qid] ?>">+</a> [<a class="quote" href="/?markbd&q=<?=$res[$i][col_qid] ?>">sucks</a>]<br />
              <br/>
              <?=formatentry($res[$i][col_qtext]) ?>              
              </p>
              
            <?
            
                }
            
              } else {
              
            ?>
            
              <p>Quote <?=$qr ?> was not found! :/</p>
            
            <?
              
              }
              
            ?>
              
            </td>
          </tr>
        </table>
