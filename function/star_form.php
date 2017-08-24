<?php

function star_form($title,$num){
$form = '';
$form .= '<div class="item_'.$num.' item">';
$form .= '<div class="row item_title">';
$form .= '    <div class="col-sm-1 hidden-xs"></div>';
$form .= '    <div class="col-sm-10"><h3 class="text-center">'.$title.'</h3></div>';
$form .= '    <div class="col-sm-1 hidden-xs"></div>';
$form .= '  </div>';
$form .= '  <div class="row">';
$form .= '    <div class="col-sm-1 hidden-xs"></div>';
$form .= '    <div class="form-group col-sm-3">';
$form .= '      <div class="row">';
$form .= '          <div class="col-sm-3 text-center"><label for="score_'.$num.'">score</label></div>';
$form .= '          <div class="col-sm-8 text-center">';
$form .= '            <span class="star-rating">';
$form .= '              <input type="radio" name="score_'.$num.'" value="1"><i></i>';
$form .= '              <input type="radio" name="score_'.$num.'" value="2"><i></i>';
$form .= '              <input type="radio" name="score_'.$num.'" value="3"><i></i>';
$form .= '              <input type="radio" name="score_'.$num.'" value="4"><i></i>';
$form .= '              <input type="radio" name="score_'.$num.'" value="5"><i></i>';
$form .= '            </span>';
$form .= '          </div>';
$form .= '          <div class="col-sm-1"></div>';
$form .= '      </div>';
$form .= '    </div>';
$form .= '    <div class="form-group col-sm-7">';
$form .= '      <div class="row">';
$form .= '          <div class="col-sm-3 text-center"><label for="qualitative_'.$num.'">comment</label></div>';
$form .= '          <div class="col-sm-9"><textarea class="form-control" name="qualitative_'.$num.'" rows="5"></textarea></div>';
$form .= '      </div>';
$form .= '    </div>';
$form .= '    <div class="col-sm-1 hidden-xs"></div>';
$form .= '  </div>';
$form .= '</div>';
return $form;
}



function star_form_detail($title,$num,$score,$comment){
$form_detail = '';
$form_detail.= '<div class="item_'.$num.' item">';
$form_detail.= '<div class="row item_title">';
$form_detail.= '    <div class="col-sm-1 hidden-xs"></div>';
$form_detail.= '    <div class="col-sm-10"><h3 class="text-center">'.$title.'</h3></div>';
$form_detail.= '    <div class="col-sm-1 hidden-xs"></div>';
$form_detail.= '  </div>';
$form_detail.= '  <div class="row">';
$form_detail.= '    <div class="col-sm-1 hidden-xs"></div>';
$form_detail.= '    <div class="form-group col-sm-3">';
$form_detail.= '      <div class="row">';
$form_detail.= '          <div class="col-sm-3 text-center"><label for="score_'.$num.'">score</label></div>';
$form_detail.= '          <div class="col-sm-8 text-center">';
$form_detail.= '            <span class="star-rating">';
if($score && $score == 1){
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="1" checked><i></i>';
}else{
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="1"><i></i>';
}
if($score && $score == 2){
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="2" checked><i></i>';
}else{
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="2"><i></i>';
}
if($score && $score == 3){
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="3" checked><i></i>';
}else{
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="3"><i></i>';
}
if($score && $score == 4){
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="4" checked><i></i>';
}else{
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="4"><i></i>';
}
if($score && $score == 5){
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="5" checked><i></i>';
}else{
$form_detail.= '              <input type="radio" name="score_'.$num.'" value="5"><i></i>';
}
// $form_detail.= '              <input type="radio" name="score_'.$num.'" value="2"><i></i>';
// $form_detail.= '              <input type="radio" name="score_'.$num.'" value="3"><i></i>';
// $form_detail.= '              <input type="radio" name="score_'.$num.'" value="4"><i></i>';
// $form_detail.= '              <input type="radio" name="score_'.$num.'" value="5"><i></i>';
$form_detail.= '            </span>';
$form_detail.= '          </div>';
$form_detail.= '          <div class="col-sm-1"></div>';
$form_detail.= '      </div>';
$form_detail.= '    </div>';
$form_detail.= '    <div class="form-group col-sm-7">';
$form_detail.= '      <div class="row">';
$form_detail.= '          <div class="col-sm-3 text-center"><label for="qualitative_'.$num.'">comment</label></div>';
if($comment){
$form_detail.= '          <div class="col-sm-9"><textarea class="form-control" name="qualitative_'.$num.'" rows="5">'.h($comment).'</textarea></div>';
}else{
  $form_detail.= '          <div class="col-sm-9"><textarea class="form-control" name="qualitative_'.$num.'" rows="5"></textarea></div>';
}
$form_detail.= '      </div>';
$form_detail.= '    </div>';
$form_detail.= '    <div class="col-sm-1 hidden-xs"></div>';
$form_detail.= '  </div>';
$form_detail.= '</div>';
return $form_detail;
}
?>

?>
