<?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $num_of_rec=2;
              $offset=($pageno-1)*$num_of_rec;

              if(empty($_POST['search'])){
                $stmt=$db->prepare('SELECT * FROM posts ORDER BY id DESC');
                $stmt->execute();
                $rawresult=$stmt->fetchALL();
                $totalpage=ceil(count($rawresult)/$num_of_rec);

                $stmt=$db->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$num_of_rec");
                $stmt->execute();
                $result=$stmt->fetchALL();
              }else{
                $searchkey=$_POST['search'];
                
                $stmt=$db->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' ORDER BY id DESC");
                $stmt->execute();
                $rawresult=$stmt->fetchALL();
                $totalpage=ceil(count($rawresult)/$num_of_rec);

                $stmt=$db->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$num_of_rec");
                $stmt->execute();
                $result=$stmt->fetchALL();
              }

                // $totalpage=ceil(50/7);//value nearest number.
                // echo $totalpage;
                // print_r($result[0]['content']);
            ?>

<?php
                      if($result):
                        $i=1;
                        foreach($result as $value): ?>
                          <tr>
                            <td><?= $i ?></td>
                            <td><?= escape($value['title'])?></td>
                            <td><?= escape(substr($value['content'],0,50)) ?></td>
                            <td>
                              <a href="edit.php?id=<?= escape($value['id']) ?>" class="btn btn-primary">Edit</a>
                              <a href="delete.php?id=<?= escape($value['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                          </tr>
                  <?php
                          $i++;
                        endforeach;
                      endif;
                  ?>
     <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end mt-3">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno<=1){ echo 'disabled';}?>"><a class="page-link" href="<?php if($pageno<=1){echo '#';} else{ echo "?pageno=".($pageno-1);} ?>">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno>=$totalpage){ echo 'disabled';}?>"><a class="page-link" href="<?php if($pageno>=$totalpage){echo '#';} else{ echo "?pageno=".($pageno+1);} ?>">Next</a></li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpage; ?>">Last</a></li>
                </ul>
              </nav>             