
 <body class="nav-md" style="background-color: <?php echo $themecolor?:''; ?>;color: <?php echo $fontcolor?:'';?>;">
    <div class="container body">
      <div class="main_container">
              
        <!-- page content -->
        <div role="main" >
            <form action="<?php echo base_url().'mr/doctorCallSaveNoneFieldWork';?>" method="post">
                 <div class="row mt-3" >
                          <div class="col-12">
                            <div class="page-title"><?php echo $title; ?></div>
                          </div> 
                          <div class="col-7 text-center"> <p style="margin-top: 8px;">Select  Reporting Date</p></div>
                          <div class="col-4"> 
                                <input type="date" name="reporting_date" id="reporting_date" class="form-control mt-2" onclick="this.showPicker();" onchange="getDoctorCall()" 
                                value="<?php if(isset($_SESSION['reportDate'])&& $_SESSION['reportDate']!='1970-01-01'){echo date('Y-m-d',strtotime($_SESSION['reportDate']))?:'';} ?>"     placeholder="Select Reporting Date" style=""> 
                                <script>
                                    getDoctorCall();
                                    function getDoctorCall()
                                    {
                                        console.log("doctorcall called");
                                        $('#workType').val('Field Work').attr('selected',true);
                                        $('#formDiv').addClass('d-none');
                                        
                                         $('#notFieldWork').addClass('d-none');
                                       
                                        
                                        
                                        var reporting_date= $('#reporting_date').val();
                                        var worktype= $('#workType').val();
                                        var ecode='<?php echo $this->positioncode; ?>';
                                        var c_id='<?php echo C_ID; ?>';
                                        $.ajax({
                                                    method:'POST',
                                                    url:'<?php echo base_url().'mr/getDoctorCallData'; ?>',
                                                    data:{'ecode':ecode,'c_id':c_id,'report_date':reporting_date},
                                                    success:function(res)
                                                    {
                                                        console.log(res);
                                                        $('#formDiv').removeClass('d-none');
                                                        $('#rowsdata').html(res);
                                                         $('#rowsdata,#rowDataContainer').removeClass('d-none');
                                                    }
                                               })
                                    }
                                     
                                    </script>
                          </div>
                          <div class="col-7 text-center">
                              <label>Select Work Type</label>
                              
                          </div>
                          <div class="col-4">
                            <select class="form-control" name="workType" id="workType" onchange="getDoctorCall()">
                                <option selected>Field Work</option>
                                <option>Meeting</option>
                                <option>Transit</option>
                                <option>Leave</option>
                            </select>
                            </div>
                    </div>
                    
                    <?php
                    if($this->session->flashdata('msg'))
                    {
                        echo $this->session->flashdata('msg');
                    }
                    $this->session->set_flashdata('msg','')
                    ?>
                    
                    <div class="row mt-3" id="rowDataContainer">
                        <div class="col-12">
                            <div id="rowsdata"></div>
                        </div>
                    </div>
                    
                    <div class="" id="formDiv">
                        <div class="row mt-2 d-none" id="notFieldWork" >
                            
                            <div class="col-12">
                                <label>Remark</label>
                                <textarea name="remark" class="form-control"></textarea>
                            </div>
                            <div class="col-12 p-2">
                                <input type="hidden" name='ecode' value="<?php echo $ecode; ?>">
                                <input type="submit" class="btn btn-success">
                            </div>
                        </div>
                    </div>
            </form>
            
            
        </div>
      </div>
        <!-- /page content -->
         
      </div>
    </div>
    </div>
    </div>
    
    <script>
        $('#workType').on('change',function(){
            var worktype=$(this).val();
            var rowsdata=$('#rowsdata').text();
           
           
            if(worktype!='Field Work')
            {
                if(rowsdata=="Allready Doctor Call Reported")
                {
                    $('#formDiv').addClass('d-none');
                }
                else
                {
                    $('#formDiv').removeClass('d-none');
                    $('#notFieldWork').removeClass('d-none');
                    $('#rowDataContainer').addClass('d-none');
                }
            }
            else
            {
                $('#notFieldWork').addClass('d-none');
                $('#rowDataContainer').removeClass('d-none');
            }
            
           
            
        })
    </script>
    
    
    