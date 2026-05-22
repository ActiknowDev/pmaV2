<?php $session = new \Cake\Http\Session();
$userSession = $session->read('data');
?>
<section class="page page-dashboard">
   <!-- PAGE-TITLE -->
   <div class="page-title skin-light">
      <div class="container">
         <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
               <div class="heading ft-secondary">
                  <span class="icon"><i class="fa fa-user"></i></span>Roles & Responsibilities
                  <nav>
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= $this->Url->build('/Users/login/'); ?>"><i class="fa fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item active">Roles & Responsibilities</li>
                     </ol>
                  </nav>
               </div>
            </div>
            <div class="col-lg-2 col-sm-4 align-bottom pdr-0">
               <?php if (array_intersect($userSession['role_name'], array(12))) { ?>
                  <div class="actions-ctrl text-md-right mt-2">
                     <?= $this->Html->link('<span>Career Tracks</span>', [
                        "controller" => "CareerTracks", "action" => "index"
                     ], [
                        'class' => "v-btn v-btn-secondary btn-block",
                        "escape" => false,
                     ]); ?>                     
                  </div>
               <?php } ?>
            </div>
            <div class="col-lg-2 col-sm-4 align-bottom pdr-0">
               <?php if (array_intersect($userSession['role_name'], array(12))) { ?>
                  <div class="actions-ctrl text-md-right mt-2">
                     <?= $this->Html->link('<span>Competencies</span>', [
                        "controller" => "Competencies", "action" => "index"
                     ], [
                        'class' => "v-btn v-btn-secondary btn-block",
                        "escape" => false,
                     ]); ?>                                      
                  </div>
               <?php } ?>
            </div>
            <div class="col-lg-2 col-sm-4 align-bottom pdr-0">
               <?php if (array_intersect($userSession['role_name'], array(12))) { ?>
                  <div class="actions-ctrl text-md-right mt-2">
                     <?= $this->Html->link('<span>Career Levels</span>', [
                        "controller" => "CareerLevels", "action" => "index"
                     ], [
                        'class' => "v-btn v-btn-secondary btn-block",
                        "escape" => false,
                     ]); ?>                      
                  </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="page page-dashboard">
   <!-- PAGE-TITLE -->
   <!-- PAGE-CONTENT -->
   <div class="page-content">
      <div class="container">
         <!-- FILTER -->
         <div class="row">
            <?php if(array_intersect($userSession['role_name'],array(12)) ){ ?>
               <div class="col-md-4">
                  <?= $this->Form->create($careerTracksEntity, ['url' => ['controller' => 'CareerTracks', 'action' => 'rolesResponsibilities','method'=>'post']]) ?>
                  <div class="form-group">
                     <label for="">Select Career Track*</label>
                     <select  name="career_trackId"  class="form-control" onchange="this.form.submit()">
                        <option value="" >Select Career Track</option>
                        <?php foreach ($careerTracks as $key => $careerTrack) { ?>
                           <option value="<?=$careerTrack->id?>" <?=$career_track_id == $careerTrack->id ? 'selected' : ''?>><?=$careerTrack->name?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <?= $this->Form->end() ?>      
               </div> 
            <?php }else{ echo '<p style="font-size:28px;">Coming Soon!</p>';} ?>  
         </div>
         <?php if ($career_track_id > 0) { ?>
            <?= $this->Form->create(NULL, [
               'url' => [
                  'controller' => 'CareerTracks',
                  'action' => 'updateCompetencyLevelMapping'
               ]
            ]) ?>
            <!-- TABLE -->
            <div class="row">
               <div class="col-md-12">
                  <?= $this->Flash->render() ?>
                  <div class="table-responsive skin-light timesheet-table-container">
                     <table class="table table-default allocation-table table-timesheet">
                        <thead>
                           <tr>
                              <th>Career Levels / Competencies</th>
                              <th>Training</td>
                                 <?php foreach ($competencies as $key => $competency) { ?>
                              <th><?= $competency->name ?></th>
                           <?php } ?>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($careerLevels as $key => $careerLevel) { ?>
                              <tr class="active">
                                 <td style="text-align: left;"><?= $careerLevel->name ?><input type="hidden" name="competency_id[]" value="<?= $careerLevel->id ?>" /></td>
                                 <td style="text-align:left;">
                                    <?php if (array_intersect($userSession['role_name'], array(12))) { ?>
                                       <div class="form-group">
                                          <div class="adon-group">
                                             <select name="training_id[<?= (int)$careerLevel->id ?>][]" class="form-control langOpt" multiple id="">
                                                <?php foreach ($training as $k => $tValue) { ?>
                                                   <?php $selected = '';
                                                   foreach ($careerMappingData as $key => $careerMappingvalue) {
                                                      if (($careerMappingvalue->career_level_id ==  $careerLevel->id) && ($careerMappingvalue->training_id == $tValue->id)) {
                                                         $selected = 'selected';
                                                      }
                                                   } ?>
                                                   <option value="<?= $tValue->id ?>" <?= $selected ?>><?= $tValue->name ?></option>
                                                <?php } ?>
                                             </select>
                                          </div>
                                       </div>
                                    <?php } else { ?>
                                       <ul>
                                          <?php
                                          foreach ($training as $k => $tValue) { ?>
                                             <?php $selected = '';
                                             $recomended = '';
                                             foreach ($careerMappingData as $key => $careerMappingvalue) {
                                                if (($careerMappingvalue->career_level_id ==  $careerLevel->id) && ($careerMappingvalue->training_id == $tValue->id)) {
                                                   $selected = 'style="color:green;"';
                                                   $recomended = ' (Recommended)';
                                                }
                                             } ?>
                                             <li>
                                                <a <?= $selected ?> href="<?= $this->Url->build('/Training/view/') . $tValue->id ?>" target="_Blank"><?= $tValue->name . $recomended ?></a>
                                             </li>
                                          <?php
                                          } ?>
                                       </ul>
                                    <?php } ?>
                                 </td>
                                 <?php foreach ($competencies as $key => $competency) { ?>
                                    <?php $text = '';
                                    foreach ($competencyMappingData as $key => $competencyMapping) {
                                       if (($competencyMapping->competency_id ==  $competency->id) && ($competencyMapping->career_level_id == $careerLevel->id)) {
                                          $text = $competencyMapping->content;
                                       }
                                    } ?>
                                    <td style="text-align:left;">
                                       <?php if (array_intersect($userSession['role_name'], array(12))) { ?>
                                          <textarea id="" rows="6" name="content[<?= (int)$careerLevel->id ?>][]" value="<?= $text ?>" class="form-control"><?= $text ?></textarea>
                                          <input type="hidden" name="career_level_id[]" value="<?= $careerLevel->id ?>" />
                                       <?php } else { ?>
                                          <?= $text ?>
                                       <?php } ?>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
               </div>
               <div class="col-md-6">
                  <div class="form-group" style="margin-top:22px;">
                     <input type="hidden" name="career_track_id" value="<?= $career_track_id ?>" />
                     <button type="submit" name="submit" class="v-btn v-btn-secondary float-right">Update</button>
                  </div>
               </div>
            </div>
            <?= $this->Form->end() ?>
         <?php } ?>
      </div>
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script>
   el=document.getElementsByName("career_trackId")[0].value;
   if ( window.history.replaceState ) {
      // let selectedCT = document.getElementByName("career_trackId")[0].value;
      //if(selectedCT != '')
     
console.log( window.location.href);
      window.history.replaceState( null, null, window.location.href );
      //document.getElementByName("career_trackId")[0].value = '0';
      var els=document.getElementsByName("career_trackId");
      els[0].value = el;
   }
</script>