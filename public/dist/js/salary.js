$(document).ready(function () {
  var i = 1;

  var n = 1;

  /******************Salary Record*****************/
  $(".add-salary").on("click", function () {
    count = $("table#salary_info tr").length;
    var num = get_row_num("table#salary_info tbody","salary_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="salary_exchange_rate'+n+'" name="salary_exchange_rate[]" value="" onkeyup="calculateSalaryMMK('+n+')" onchange="calculateSalaryMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="salary_usd_amount'+n+'" name="salary_usd_amount[]" value="" onkeyup="calculateSalaryMMK('+n+')" onchange="calculateSalaryMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="salary_mmk_amount'+n+'" name="salary_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Salary(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#salary_info tbody").append(data);

    total_Salary();

    i++;
  });
  /******************Salary Record*****************/

  /******************SSC Record*****************/
  $(".add-ssc").on("click", function () {
    count = $("table#ssc_info tr").length;
    var num = get_row_num("table#ssc_info tbody","ssc_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="ssc_exchange_rate'+n+'" name="ssc_exchange_rate[]" value="" onkeyup="calculateSSCMMK('+n+')" onchange="calculateSSCMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="ssc_usd_amount'+n+'" name="ssc_usd_amount[]" value=""  onkeyup="calculateSSCMMK('+n+')" onchange="calculateSSCMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="ssc_mmk_amount'+n+'" name="ssc_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_SSC(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#ssc_info tbody").append(data);

    total_SSC();

    i++;
  });
  /******************SSC Record*****************/
  
  /******************OT Record*****************/
  $(".add-ot").on("click", function () {
    count = $("table#ot_info tr").length;
    var num = get_row_num("table#ot_info tbody","ot_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="ot_exchange_rate'+n+'" name="ot_exchange_rate[]" value="" onkeyup="calculateOTMMK('+n+')" onchange="calculateOTMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="ot_usd_amount'+n+'" name="ot_usd_amount[]" value="" onkeyup="calculateOTMMK('+n+')" onchange="calculateOTMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="ot_mmk_amount'+n+'" name="ot_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_OT(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#ot_info tbody").append(data);

    total_OT();

    i++;
  });
  /******************OT Record*****************/

  /******************Leave Record*****************/
  $(".add-leave").on("click", function () {
    count = $("table#leave_info tr").length;
    var num = get_row_num("table#leave_info tbody","leave_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="leave_exchange_rate'+n+'" name="leave_exchange_rate[]" value="" onkeyup="calculateLeaveMMK('+n+')" onchange="calculateLeaveMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="leave_usd_amount'+n+'" name="leave_usd_amount[]" value="" onkeyup="calculateLeaveMMK('+n+')" onchange="calculateLeaveMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="leave_mmk_amount'+n+'" name="leave_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Leave(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#leave_info tbody").append(data);

    total_Leave();

    i++;
  });
  
  /******************Leave Record*****************/

  /******************Adjustment Record*****************/
  $(".add-adjustment").on("click", function () {
    count = $("table#adjustment_info tr").length;
    var num = get_row_num("table#adjustment_info tbody","adjustment_name");
    n = Number(num) + 1;
    //var data = '<tr><td><input type="text" class="form-control" id="adjustment_name'+n+'" name="adjustment_name[]" value=""></td>';
    var data = '<tr><td><input type="month" class="form-control" id="adjustment_month'+n+'" name="adjustment_month[]"></td>';
    data += '<td><select name="adjustment_type[]" id="adjustment_type'+n+'" class="form-control" style="width: 100px;"><option value="Salary">Salary</option><option value="Overtime">Overtime</option></select></td>';
      
    data += '<td><input type="text" class="form-control" id="adjustment_exchange_rate'+n+'" name="adjustment_exchange_rate[]" value="" onkeyup="calculateAdjustmentMMK('+n+')" onchange="calculateAdjustmentMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="adjustment_usd_amount'+n+'" name="adjustment_usd_amount[]" value="" onkeyup="calculateAdjustmentMMK('+n+')" onchange="calculateAdjustmentMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="adjustment_mmk_amount'+n+'" name="adjustment_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Adjustment(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#adjustment_info tbody").append(data);

    var currentYear = (new Date).getFullYear();
    var currentMonth = (new Date).getMonth() + 1;
    if(currentMonth<10)
      currentMonth = "0"+currentMonth;
    var max_date = currentYear+"-"+currentMonth;
    $("#adjustment_month"+n).attr("max",max_date);

    $exchange = $('#adjustment_exchange_rate0').val();
    $('#adjustment_exchange_rate'+n).val($exchange);
    total_Adjustment();

    i++;
  });
  
  /******************Adjustment Record*****************/

  /******************Allowance Record*****************/
  $(".add-allowance").on("click", function () {
    count = $("table#allowance_info tr").length;
    var num = get_row_num("table#allowance_info tbody","allowance_name");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="allowance_name'+n+'" name="allowance_name[]"></td>';
      
    data += '<td><input type="text" class="form-control" id="allowance_amount'+n+'" name="allowance_amount[]" onkeyup="calculateTotalAllowance()" onchange="calculateTotalAllowance()"></td>';
    data += '<td><select name="allowance_currency[]" id="allowance_currency'+n+'" class="form-control" onchange="calculateTotalAllowance()"><option value="usd">USD</option><option value="mmk">MMK</option></select></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#allowance_info tbody").append(data);
    $("#allowance_name"+n).focus();
    

    calculateTotalAllowance();

    i++;
  });
  /******************Allowance Record*****************/

  /******************USD Allowance Record*****************/
  $(".add-usd-allowance").on("click", function () {
    count = $("table#usd_allowance_info tr").length;
    var num = get_row_num("table#usd_allowance_info tbody","usd_allowance_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="usd_allowance_exchange_rate'+n+'" name="usd_allowance_exchange_rate[]" value="" onkeyup="calculateAllowanceMMK('+n+')" onchange="calculateAllowanceMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="usd_allowance_usd_amount'+n+'" name="usd_allowance_usd_amount[]" value="" onkeyup="calculateAllowanceMMK('+n+')" onchange="calculateAllowanceMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="usd_allowance_mmk_amount'+n+'" name="usd_allowance_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_USD_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#usd_allowance_info tbody").append(data);

    total_USD_Allowance();

    i++;
  });
  /******************USD Allowance Record*****************/

  /******************MMK Allowance Record*****************/
  $(".add-mmk-allowance").on("click", function () {
    count = $("table#mmk_allowance_info tr").length;
    var num = get_row_num("table#mmk_allowance_info tbody","mmk_allowance_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="mmk_allowance_exchange_rate'+n+'" name="mmk_allowance_exchange_rate[]" value="" onkeyup="calculateAllowanceUSD('+n+')" onchange="calculateAllowanceUSD('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="mmk_allowance_mmk_amount'+n+'" name="mmk_allowance_mmk_amount[]" value=""  onkeyup="calculateAllowanceUSD('+n+')" onchange="calculateAllowanceUSD('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="mmk_allowance_usd_amount'+n+'" name="mmk_allowance_usd_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_MMK_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#mmk_allowance_info tbody").append(data);

    total_MMK_Allowance();

    i++;
  });
  /******************MMK Allowance Record*****************/

  /******************Detuction Record*****************/
  $(".add-deduction").on("click", function () {
    count = $("table#deduction_info tr").length;
    var num = get_row_num("table#deduction_info tbody","deduction_name");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="deduction_name'+n+'" name="deduction_name[]"></td>';
      
    data += '<td><input type="text" class="form-control" id="deduction_amount'+n+'" name="deduction_amount[]" onkeyup="calculateTotalDeduction()" onchange="calculateTotalDeduction()"></td>';
    data += '<td><select name="deduction_currency[]" id="deduction_currency'+n+'" class="form-control" onchange="calculateTotalDeduction()"><option value="usd">USD</option><option value="mmk">MMK</option></select></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#deduction_info tbody").append(data);
    $("#deduction_name"+n).focus();
    

    calculateTotalDeduction();

    i++;
  });
  /******************Detuction Record*****************/

  /******************USD Deduction Record*****************/
  $(".add-usd-deduction").on("click", function () {
    count = $("table#usd_deduction_info tr").length;
    var num = get_row_num("table#usd_deduction_info tbody","usd_deduction_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="usd_deduction_exchange_rate'+n+'" name="usd_deduction_exchange_rate[]" value="" onkeyup="calculateDeductionMMK('+n+')" onchange="calculateDeductionMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="usd_deduction_usd_amount'+n+'" name="usd_deduction_usd_amount[]" value="" onkeyup="calculateDeductionMMK('+n+')" onchange="calculateDeductionMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="usd_deduction_mmk_amount'+n+'" name="usd_deduction_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_USD_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#usd_deduction_info tbody").append(data);

    total_USD_Deduction();

    i++;
  });
  /******************USD Deduction Record*****************/

  /******************MMK Deduction Record*****************/
  $(".add-mmk-deduction").on("click", function () {
    count = $("table#mmk_deduction_info tr").length;
    var num = get_row_num("table#mmk_deduction_info tbody","mmk_deduction_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="mmk_deduction_exchange_rate'+n+'" name="mmk_deduction_exchange_rate[]" value="" onkeyup="calculateDeductionUSD('+n+')" onchange="calculateDeductionUSD('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="mmk_deduction_mmk_amount'+n+'" name="mmk_deduction_mmk_amount[]" value=""  onkeyup="calculateDeductionUSD('+n+')" onchange="calculateDeductionUSD('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="mmk_deduction_usd_amount'+n+'" name="mmk_deduction_usd_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_MMK_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#mmk_deduction_info tbody").append(data);

    total_MMK_Deduction();

    i++;
  });
  /******************MMK Deduction Record*****************/

  /******************Bonus Record*****************/
  $(".add-bonus").on("click", function () {
    count = $("table#bonus_info tr").length;
    var num = get_row_num("table#bonus_info tbody","bonus_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="bonus_exchange_rate'+n+'" name="bonus_exchange_rate[]" value="" onkeyup="calculateBonusMMK('+n+')" onchange="calculateBonusMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="bonus_usd_amount'+n+'" name="bonus_usd_amount[]" value="" onkeyup="calculateBonusMMK('+n+')" onchange="calculateBonusMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="bonus_mmk_amount'+n+'" name="bonus_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Bonus(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#bonus_info tbody").append(data);

    total_Bonus();

    i++;
  });
  /******************Bonus Record*****************/

  /******************KBZ Record*****************/
  $(".add-kbz").on("click", function () {
    count = $("table#kbz_info tr").length;
    var num = get_row_num("table#kbz_info tbody","kbz_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="kbz_exchange_rate'+n+'" name="kbz_exchange_rate[]" value="" onkeyup="calculateKBZMMK('+n+')" onchange="calculateKBZMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="kbz_usd_amount'+n+'" name="kbz_usd_amount[]" value="" onkeyup="calculateKBZMMK('+n+')" onchange="calculateKBZMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="kbz_mmk_amount'+n+'" name="kbz_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_KBZ(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#kbz_info tbody").append(data);

    total_KBZ();

    i++;
  });
  /******************KBZ Record*****************/

  /******************Transfer To Record*****************/
  $(".add-transfer-to").on("click", function () {
    count = $("table#transfer_to_info tr").length;
    var num = get_row_num("table#transfer_to_info tbody","transfer_to_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="transfer_to_exchange_rate'+n+'" name="transfer_to_exchange_rate[]" value="" onkeyup="calculateTransferToMMK('+n+')" onchange="calculateTransferToMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="transfer_to_usd_amount'+n+'" name="transfer_to_usd_amount[]" value="" onkeyup="calculateTransferToMMK('+n+')" onchange="calculateTransferToMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="transfer_to_mmk_amount'+n+'" name="transfer_to_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_TransferTo(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#transfer_to_info tbody").append(data);

    total_TransferTo();

    i++;
  });
  /******************Transfer To Record*****************/

  /******************Transfer From Record*****************/
  $(".add-transfer-from").on("click", function () {
    count = $("table#transfer_from_info tr").length;
    var num = get_row_num("table#transfer_from_info tbody","transfer_from_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="transfer_from_exchange_rate'+n+'" name="transfer_from_exchange_rate[]" value="" onkeyup="calculateTransferFromMMK('+n+')" onchange="calculateTransferFromMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="transfer_from_usd_amount'+n+'" name="transfer_from_usd_amount[]" value="" onkeyup="calculateTransferFromMMK('+n+')" onchange="calculateTransferFromMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="transfer_from_mmk_amount'+n+'" name="transfer_from_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_TransferFrom(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#transfer_from_info tbody").append(data);

    total_TransferFrom();

    i++;
  });
  /******************Transfer From Record*****************/

  /******************Electricity Record*****************/
  $(".add-electricity").on("click", function () {
    count = $("table#electricity_info tr").length;
    var num = get_row_num("table#electricity_info tbody","electricity_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="electricity_exchange_rate'+n+'" name="electricity_exchange_rate[]" value="" onkeyup="calculateElectricityMMK('+n+')" onchange="calculateElectricityMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="electricity_usd_amount'+n+'" name="electricity_usd_amount[]" value="" onkeyup="calculateElectricityMMK('+n+')" onchange="calculateElectricityMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="electricity_mmk_amount'+n+'" name="electricity_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Electricity(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#electricity_info tbody").append(data);

    total_Electricity();

    i++;
  });
  /******************Electricity Record*****************/

  /******************Car Record*****************/
  $(".add-car").on("click", function () {
    count = $("table#car_info tr").length;
    var num = get_row_num("table#car_info tbody","car_exchange_rate");
    n = Number(num) + 1;
    var data = '<tr><td><input type="text" class="form-control" id="car_exchange_rate'+n+'" name="car_exchange_rate[]" value="" onkeyup="calculateCarMMK('+n+')" onchange="calculateCarMMK('+n+')"></td>';
      
    data += '<td><input type="text" class="form-control" id="car_usd_amount'+n+'" name="car_usd_amount[]" value="" onkeyup="calculateCarMMK('+n+')" onchange="calculateCarMMK('+n+')"></td>';
    data += '<td><input type="text" class="form-control" id="car_mmk_amount'+n+'" name="car_mmk_amount[]" value="" readonly></td>';
    data += '<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_Car(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    console.log("New DAta : " + data);
    $("table#car_info tbody").append(data);

    total_Car();

    i++;
  });
  /******************Car Record*****************/
	
});
function delete_Row_Salary(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Salary();
  //checkSalary();
  //delete_doneby(small);
}

function delete_Row_SSC(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_SSC();
}

function delete_Row_OT(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_OT();
}
function delete_Row_Leave(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Leave();
}

function delete_Row_Adjustment(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Adjustment();
}

function delete_Row_Allowance(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  calculateTotalAllowance();
}

function delete_Row_USD_Allowance(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_USD_Allowance();
  //checkSalary();
  //delete_doneby(small);
}

function delete_Row_MMK_Allowance(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_MMK_Allowance();
}

function delete_Row_Deduction(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  calculateTotalDeduction();
}

function delete_Row_USD_Deduction(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_USD_Deduction();
  //checkSalary();
  //delete_doneby(small);
}

function delete_Row_MMK_Deduction(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_MMK_Deduction();
}
function delete_Row_Bonus(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Bonus();
}

function delete_Row_KBZ(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_KBZ();
}

function delete_Row_TransferTo(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_TransferTo();
}

function delete_Row_TransferFrom(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_TransferFrom();
}

function delete_Row_Electricity(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Electricity();
}

function delete_Row_Car(row) {
  var small = $(row).closest("tr").find("td input");
  $(row).parents("tr").remove();
  total_Car();
}

//number of item table
function get_row_num(tbl_id,field_id) {
  var last_row = $(tbl_id).find("tr").last();
  console.log("Last Row : " + last_row[0]);
  last_row = last_row[0];
  var small = $(last_row).find("td input");
  var sp = small[0];
  console.log("small : " + sp);
  if (typeof sp == "undefined" || sp == false) {
    return 0;
  }
  var strid = sp.id;
  console.log("s id "+strid);
  var row = strid.replace(field_id, "");
  return row;
}

function checkSalary() {
  obj = $("table#family_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function total_Salary(){
		var total_usd = 0.00;
		var total_mmk = 0.00;
		var amount = 0.00;	

		//calculate total amount
		$('input[name="salary_usd_amount[]"]').each(function(){
			amount = $(this).val();
			if(amount && !isNaN(amount)){
				total_usd = total_usd + parseFloat(amount);
			}
			
		});
		$('input[name="salary_mmk_amount[]"]').each(function(){
			amount = $(this).val();
			if(amount && !isNaN(amount)){
				total_mmk = total_mmk + parseFloat(amount);
			}
			
		});

		$("#salary_usd_label").html(total_usd.toFixed(2));
		$("#salary_usd").val(total_usd.toFixed(2));

		$("#salary_mmk_label").html(total_mmk.toFixed(2));
		$("#salary_mmk").val(total_mmk.toFixed(2));

    calculateIncomeTax();
    calculateNetSalary();
		
}

function total_SSC(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="ssc_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="ssc_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#ssc_usd_label").html(total_usd.toFixed(2));
    $("#ssc_usd").val(total_usd.toFixed(2));

    $("#ssc_mmk_label").html(total_mmk.toFixed(2));
    $("#ssc_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();

    
}

function total_OT(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="ot_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="ot_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#ot_usd_label").html(total_usd.toFixed(2));
    $("#ot_usd").val(total_usd.toFixed(2));

    $("#ot_mmk_label").html(total_mmk.toFixed(2));
    $("#ot_mmk").val(total_mmk.toFixed(2));

    calculateIncomeTax();
    calculateNetSalary();
}

function total_Leave(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="leave_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="leave_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#leave_usd_label").html(total_usd.toFixed(2));
    $("#leave_usd").val(total_usd.toFixed(2));

    $("#leave_mmk_label").html(total_mmk.toFixed(2));
    $("#leave_mmk").val(total_mmk.toFixed(2));

    calculateIncomeTax();
    calculateNetSalary();
}
function total_Adjustment(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="adjustment_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="adjustment_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#adjustment_usd_label").html(total_usd.toFixed(2));
    $("#adjustment_usd").val(total_usd.toFixed(2));

    $("#adjustment_mmk_label").html(total_mmk.toFixed(2));
    $("#adjustment_mmk").val(total_mmk.toFixed(2));

    calculateIncomeTax();
    calculateNetSalary();
}

function total_USD_Allowance(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="usd_allowance_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="usd_allowance_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#usd_allowance_usd_label").html(total_usd.toFixed(2));
    $("#usd_allowance_usd").val(total_usd.toFixed(2));

    $("#usd_allowance_mmk_label").html(total_mmk.toFixed(2));
    $("#usd_allowance_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_MMK_Allowance(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="mmk_allowance_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="mmk_allowance_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#mmk_allowance_usd_label").html(total_usd.toFixed(2));
    $("#mmk_allowance_usd").val(total_usd.toFixed(2));

    $("#mmk_allowance_mmk_label").html(total_mmk.toFixed(2));
    $("#mmk_allowance_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_USD_Deduction(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="usd_deduction_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="usd_deduction_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#usd_deduction_usd_label").html(total_usd.toFixed(2));
    $("#usd_deduction_usd").val(total_usd.toFixed(2));

    $("#usd_deduction_mmk_label").html(total_mmk.toFixed(2));
    $("#usd_deduction_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_MMK_Deduction(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="mmk_deduction_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="mmk_deduction_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#mmk_deduction_usd_label").html(total_usd.toFixed(2));
    $("#mmk_deduction_usd").val(total_usd.toFixed(2));

    $("#mmk_deduction_mmk_label").html(total_mmk.toFixed(2));
    $("#mmk_deduction_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_Bonus(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="bonus_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="bonus_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#bonus_usd_label").html(total_usd.toFixed(2));
    $("#bonus_usd").val(total_usd.toFixed(2));

    $("#bonus_mmk_label").html(total_mmk.toFixed(2));
    $("#bonus_mmk").val(total_mmk.toFixed(2));

    calculateIncomeTax();
    calculateNetSalary();
}

function total_KBZ(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="kbz_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="kbz_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#kbz_opening_usd_label").html(total_usd.toFixed(2));
    $("#kbz_opening_usd").val(total_usd.toFixed(2));

    $("#kbz_opening_mmk_label").html(total_mmk.toFixed(2));
    $("#kbz_opening_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_TransferTo(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="transfer_to_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="transfer_to_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#transfer_to_usd_label").html(total_usd.toFixed(2));
    $("#transfer_to_usd").val(total_usd.toFixed(2));

    $("#transfer_to_mmk_label").html(total_mmk.toFixed(2));
    $("#transfer_to_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_TransferFrom(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="transfer_from_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="transfer_from_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#transfer_from_usd_label").html(total_usd.toFixed(2));
    $("#transfer_from_usd").val(total_usd.toFixed(2));

    $("#transfer_from_mmk_label").html(total_mmk.toFixed(2));
    $("#transfer_from_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_Electricity(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="electricity_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="electricity_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#electricity_usd_label").html(total_usd.toFixed(2));
    $("#electricity_usd").val(total_usd.toFixed(2));

    $("#electricity_mmk_label").html(total_mmk.toFixed(2));
    $("#electricity_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function total_Car(){
    var total_usd = 0.00;
    var total_mmk = 0.00;
    var amount = 0.00;  

    //calculate total amount
    $('input[name="car_usd_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_usd = total_usd + parseFloat(amount);
      }
      
    });
    $('input[name="car_mmk_amount[]"]').each(function(){
      amount = $(this).val();
      if(amount && !isNaN(amount)){
        total_mmk = total_mmk + parseFloat(amount);
      }
      
    });

    $("#car_usd_label").html(total_usd.toFixed(2));
    $("#car_usd").val(total_usd.toFixed(2));

    $("#car_mmk_label").html(total_mmk.toFixed(2));
    $("#car_mmk").val(total_mmk.toFixed(2));

    calculateNetSalary();
}

function delete_doneby(small) {
  var sp = small[0];
  if (sp == false) {
    return false;
  }
  var strid = sp.id;
  var row = strid[strid.length - 1];
  $("#family_name" + row)
    .parents("tr")
    .remove();
}

