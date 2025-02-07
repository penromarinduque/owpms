function ajaxCheckMembershipNo(membership_no_fld) {
    var membership_no = document.getElementById(membership_no_fld).value;
    $.ajax({
        type: 'POST',
        url: '/member/ajax-check-membershipno',
        data: { membership_no: membership_no },
        dataType: 'json',
        success: function (result){
            console.log(result);
            if (result!=null) {
                if (result.mn_count==1) {
                    alert('MEMBERSHIP NUMBER ALREADY EXISTS!');
                    $('#'+membership_no_fld).focus().val('');
                }
            }
        },
        error: function (result){
            console.log(result);
        }
    });
}

function getPolicycodeData(policycode_id, arf_id=null) {
    $.ajax({
        type: 'POST',
        url: '/policycode/ajax-get-policycode',
        data: { policycode_id: policycode_id },
        dataType: 'json',
        success: function (result){
            // console.log(result);
            if (result!=null) {
                $('#membershipvalue').val(result.contracted_price);
                $('#p_category').val(result.category);
                $('#payment').val(result.monthly);
                $('#payment_cbi').val(result.cbi);
                showHideAR(result.category, arf_id);
            }
        },
        error: function (result){
            // console.log(result);
        }
    });
}

function showHideAR(category, arf_id) {
    if (category=='nip') {
        $('#'+arf_id).hide();
    } else {
        $('#'+arf_id).show();
    }
    // console.log(category);   
}

function ajaxGetStaffByBranch(branch_id, staff_fld_id) {
    $('#'+staff_fld_id).prop('disabled',true).html('<option value="">Loading Collector List...</option>').fadeTo(200,1,function (){
        $.ajax({
            type: 'POST',
            url: '/staff/ajax-opt-by-branch',
            data: { branch_id:branch_id },
            // dataType: 'json',
            success: function (result){
                // console.log(result);
                if (result) {
                    $('#'+staff_fld_id).prop('disabled',false).html('');
                    $('#'+staff_fld_id).append(result);
                    $('#datepaid').prop('readonly',false).val('');
                }
            },
            error: function (result){
                // console.log(result);
                $('#'+staff_fld_id).prop('disabled',false).html('');
                $('#'+staff_fld_id).append('<option value="">Error! Please reload page</option>');
            }
        });
    });
    return false;
}

function ajaxCheckOR(ornumber_fld, branch_fld_id, staff_fld_id, datepaid_fld_id, inst_fld) {
    var ornumber = $('#'+ornumber_fld).val();
    if (ornumber) {
        var branch_id = $('#'+branch_fld_id).val();
        var staff_id = $('#'+staff_fld_id).val();
        var datepaid = $('#'+datepaid_fld_id).val();
        $("#"+inst_fld).prop('disabled',true);
        $('#o_r').html('<i><small>Checking O.R. Please wait</small></i>').fadeTo(300,1,function (){
            $.ajax({
                type: 'POST',
                url: '/member/ledger/ajax-check-or',
                data: { ornumber:ornumber, branch_id:branch_id, staff_id:staff_id, datepaid:datepaid },
                // dataType: 'json',
                success: function (result){
                    console.log(result.res);
                    // if (result.str) {
                    //     $("#o_r").html(result.str);
                    // }
                    if (result.res) {
                        if (result.res == 1) {
                            alert('OFFICIAL RECEIPT NUMBER ALREADY USED!');
                            $('#o_r').html('');
                            $('#'+ornumber_fld).val('').focus();
                        } else if (result.res == 3) {
                            alert('OFFICIAL RECEIPT NOT YET LOGGED OR NOT ASSIGNED TO SELECTED COLLECTOR!');
                            $('#o_r').html('');
                            $('#'+ornumber_fld).focus();
                        } else {
                            $("#"+inst_fld).prop('disabled',false);
                            $('#o_r').html('');
                        }
                        $("#"+inst_fld).prop('disabled',false);
                    }
                },
                error: function (result){
                    console.log(result);
                    if (result.error) {
                        $("#o_r").html(result.error);
                    }
                }
            });
        });
          
    }else{
        $("#o_r").html('');
    }
}

function changeInst(inst_fld, instnum_a_fld, instnum_b_fld, amountpaid_fld, tmpamountpaid_fld) {
    var a = $("#"+instnum_a_fld).val();
    var x = (+a + +$("#"+inst_fld).val()) -1;
    if(a==x){
        $("#"+instnum_b_fld).val("");
        $("#"+amountpaid_fld).val($("#"+tmpamountpaid_fld).val());
    }else{
        var ap=$("#"+tmpamountpaid_fld).val();
        var t=+ap * +$("#"+inst_fld).val();
        $("#"+amountpaid_fld).val(t);
        $("#"+instnum_b_fld).val(x);
    }   
    console.log(x);

    /* call changeStat function */
    changeStat();
}

function checkStat(member_id, stat, datepaid, inst, remark_status_fld, remark_fld) {
    $.ajax({
        type: 'POST',
        url: '/member/ledger/ajax-checkstat',
        data: { member_id:member_id, mcs_stat:stat, datepaid:datepaid, inst:inst },
        dataType: 'json',
        success: function (result){
            console.log(result.stat);
            if (result!=null) {
                $('#'+remark_status_fld).removeAttr('readonly','readonly');
                $('#'+remark_status_fld).val(result.stat);
                $('#'+remark_fld).val('');
            }
        },
        error: function (result) {
            console.log(result);
        }
    });
    return false;
}

function changeStat(){
    var due_o = "";
    var datepaid = "";
    var member_id = $('#member_id').val();
    var mt = $('#mt').val();
    var duedate = $('#duedate').val();
    var datepaid = $('input[name=datepaid]').val();
    var stat = $('#mcs_remark').val();
    var inst = $('#inst').val();
    var duedate = $('#duedate').val();
    // console.log(stat);

    $.ajax({
        type: 'POST',
        url: '/member/ledger/ajax-checkd',
        data: { member_id:member_id, datepaid:datepaid, duedate:duedate },
        dataType: 'json',
        success: function (result){
            console.log(result);
            if (result!=null) {
                var due_o = result.due_o;

                if(stat=='no payment' || stat==''){
                    if(inst == 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('30'); 
                    }else if(inst > 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('advance');
                    }
                    $('#remark').val('').attr('disabled','disabled');
                    
                }else if(stat=='30days' || stat=='30'){
                    
                    if(inst == 1){
                        checkStat(member_id, stat, datepaid, inst, 'remark_status', 'remark');
                    }else if(inst > 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('advance');
                        $('#remark').val('')
                    }
                    $('#remark').val('')/* .attr('disabled','disabled') */;
                    
                    
                }else if(stat=='60days' || stat=='60'){
                    if(inst == 1){
                        
                        if(due_o==1 && mt=="nip"){
                            $('#remark_status').val('30').attr('readonly','readonly');
                            $('#remark').val('overdue').attr('readonly','readonly');
                        
                        }else{
                            checkStat(member_id, stat, datepaid, inst, 'remark_status', 'remark');
                        }
                        
                    }else if(inst != 1 && inst == 2){
                        
                        if(due_o==1 && mt=="nip"){
                            $('#remark_status').val('advance').attr('readonly','readonly');
                            $('#remark').val('overdue').attr('readonly','readonly');
                        }else{
                            checkStat(member_id, stat, datepaid, inst, 'remark_status', 'remark');
                        }
                        
                    }else if(inst > 1 && inst > 2){
                        if(due_o==1 && mt=="nip"){
                            $('#remark_status').val('advance').attr('readonly','readonly');
                            $('#remark').val('overdue').attr('readonly','readonly');
                        }else{
                            $('#remark_status').removeAttr('readonly','readonly');
                            $('#remark_status').val('advance');
                            $('#remark').val('');
                        }
                    }
                    //$('#remark').val('')/* .attr('disabled','disabled') */;
                    
                }else if(stat=='advance'){
                    if(inst > 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('advance');
                    } else {
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('30');
                    }
                    $('#remark').val('').attr('readonly','readonly');
                    
                }else if(stat=='lapsed'){
                    if(inst == 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('30');
                    }else if(inst > 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('advance');
                    }
                    $('#remark').val('ri').attr('readonly','readonly');

                }else if(stat=='overdue'){
                    if(inst == 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('30');
                    }else if(inst > 1){
                        $('#remark_status').removeAttr('readonly','readonly');
                        $('#remark_status').val('advance');
                    }
                    $('#remark').val('overdue').attr('readonly','readonly');
                }
            }
            
            
        },
        error: function (result){
            console.log(result);
        }
    });
    return false;      
}

function changeTransNumber(req, transnumber_fld, transnum_fld, addnew_id, backcurr_id) {
    if (req == 'new') {
        // document.getElementById(transnumber_fld).style.display="none";
        $('#'+transnumber_fld).hide().prop('required',false).prop('disabled',true);
        document.getElementById(addnew_id).style.display="none";
        // document.getElementById(transnum_fld).style.display="block";
        $('#'+transnum_fld).show().prop('required',true).prop('disabled',false);
        document.getElementById(backcurr_id).style.display="block";
    } else {
        // document.getElementById(transnumber_fld).style.display="block";
        $('#'+transnumber_fld).show().prop('required',true).prop('disabled',false);
        document.getElementById(addnew_id).style.display="block";
        // document.getElementById(transnum_fld).style.display="none";
        $('#'+transnum_fld).hide().prop('required',false).prop('disabled',true);
        document.getElementById(backcurr_id).style.display="none";
    }
}

function ajaxGetTransNumber(datepaid_fld, transnumber_fld, ornumber_fld, datepaid_str_my=null, trans_number_old=null) {
    var datepaid = $('#'+datepaid_fld).val();
    $('#'+transnumber_fld).prop('disabled', false).html('<option>Loading Trans.No...</option>').fadeTo(300,1,function (){
        $.ajax({
            type: 'POST',
            url: '/member/ledger/ajax-get-transnumber',
            data: { datepaid:datepaid, datepaid_str_my:datepaid_str_my, trans_number_old:trans_number_old },
            dataType: 'json',
            success: function (result){
                console.log(result);
                if (result.opt) {
                    $('#'+transnumber_fld).html(result.opt);
                    $('#'+ornumber_fld).prop('readonly',false);
                }
            },
            error: function (result) {
                console.log(result);
                $('#'+transnumber_fld).html('<option>Error! Pls. Refresh</option>');
                $('#'+ornumber_fld).prop('readonly',true);
            }
        });
    });
    return false;
}

function paymentRemark(payment_remark_fld, remark_status_fld, inst_fld, amountpaid_fld) {
    var remark = $('#'+payment_remark_fld).val();
    var remark_status = $('#'+remark_status_fld).val();
    var inst = $('#'+inst_fld).val();

    if(remark=="ri" && inst!=1 && inst>=2){
        $("#"+remark_status_fld).val('advance').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="overdue" && inst!=1 && inst>=2){
        $("#"+remark_status_fld).val('advance').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="ri" && inst==1){
        $("#"+remark_status_fld).val('30').prop('readonly',true);
        // $('#mcsremark').val('');
    }else if(remark=="overdue" && inst==1){
        $("#"+remark_status_fld).val('30').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="rifee" || remark=="updowngradefee"){
        $("#"+remark_status_fld).prop('required',false).val('');
        $('#'+amountpaid_fld).focus().val('');
        /* $('#mcsremark').val(''); */
    }else if(remark=="upgrade" || remark=="downgrade"){
        changeStat();
        $("#"+remark_status_fld).val(remark_status);
        /* $('#mcsremark').val(''); */
    }else if(remark=="partial" || remark=="partialfull" && inst==1){
        $("#"+remark_status_fld).val('30').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="partial" || remark=="partialfull" && inst>=2){
        $("#"+remark_status_fld).val('advance').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="servicerendered" && inst>=1){
        console.log(remark);
        $("#"+remark_status_fld).val('deceased').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else if(remark=="serviceunrendered" && inst>=1){
        $("#"+remark_status_fld).val('deceased1').prop('readonly',true);
        /* $('#mcsremark').val(''); */
    }else{
        $("#"+remark_status_fld).val('').prop('required',true);
    }
    // return false;
}

function ajaxGetCollector(month_fld, year_fld, collector_fld) {
    var mcovered = document.getElementById(month_fld).value;
    var ycovered = document.getElementById(year_fld).value;

    $('#'+collector_fld).prop('disabled',true).html('<option value="">Loading Collector List...</option>').fadeTo(200,1,function (){
        $.ajax({
            type: 'POST',
            url: '/input/monthlycollsheet/ajax-get-collectors',
            data: { mcovered:mcovered, ycovered:ycovered },
            // dataType: 'json',
            success: function (result){
                console.log(result);
                if (result) {
                    $('#'+collector_fld).prop('disabled',false).html('');
                    $('#'+collector_fld).append(result);
                }
            },
            error: function (result){
                // console.log(result);
                $('#'+collector_fld).prop('disabled',false).html('');
                $('#'+collector_fld).append('<option value="">Error! Please reload page</option>');
            }
        });
    });
    return false;
}

/* JQuery functions */
var $ = jQuery.noConflict();
jQuery(document).ready(function ($){

});