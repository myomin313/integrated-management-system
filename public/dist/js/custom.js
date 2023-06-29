$(document).ready(function () {
  var i = 1;

  var n = 1;

/******************Family Record*****************/

    $(".family-addmore").on("click", function () {
        count = $("table#family_records tr").length;
        var num = get_row_num("table#family_records");
        n = Number(num) + 1;
        var data =
            "<tr id='familyNumone"+n+"'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td><td style='border-top: none;'><label>Relationship <span class='required text-danger'>*</span></label><input type='text' id='family_relationship" +
            n +
            "' name='family_relationship[]'' class='form-control' required></td>";
        data +=
            "<td style='border-top: none;'><label>Name <span class='required text-danger'>*</span></label><input type='text' id='family_name" +
            n +
            "' name='family_name[]'' class='form-control' required></td>";
            
        data +="<td style='border-top: none;'><label> Allownace </label><div style='width:200px;' id='allowance_select" +
            n +"'></div></td>";
        data +=
            "<td style='border-top: none;'><label>Allowance Fee </label><input type='text' id='allowance_fee" +
            n +
            "' name='allowance_fee[]'' onkeypress='return isNumberKey(event)' class='form-control' ></td></tr>";
        data +=
            "<tr id='familyNumtwo"+n+"'><td style='border-top: none;'><label>DOB </label><input type='date' name='family_dob[]' id='date_of_issue" +
            n +
            "' class='form-control'  /></td>";
        data +=
            "<td style='border-top: none;'><label>Work / School  </label><input type='text' id='family_work" +
            n +
            "' name='family_work[]'' class='form-control' ></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Family(" + n +")' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#family_records tbody").append(data);

        //copy family relationship
         //copy english department type
        var sel = $("#allowance0");
        var clone = sel.clone();

        clone.attr("id", "allowance" + n);
        clone.show();
        clone
            .insertAfter("#allowance_select" + n)
            .wrap("<div></div>")
            .select2();
      
        i++;
    });
  /******************Family Record*****************/

  /******************user bank Record*****************/

  $(".user-bank-addmore").on("click", function () {
    alert("hi");
        count = $("table#user_bank_records tr").length;
        var num = get_row_num("table#user_bank_records");
        n = Number(num) + 1;
        var data =
            "<tr id='userbankNumone"+n+"'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small><td style='border-top: none;'><label> Bank Name </label><div style='width:200px;' id='bank_id_select" +
            n +"'></div></td>";
            
        data +="<td style='border-top: none;'><label> Currency </label><div style='width:200px;' id='currency_select" +
            n +"'></div></td>";      
       
        data +=
            "<td style='border-top: none;'><label>Bank Account </label><input type='text' id='bank_account" +
            n +
            "' name='bank_account[]'' class='form-control' ></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Family(" + n +")' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
       
        $("table#user_bank_records tbody").append(data);

        //copy family relationship
        var sel2 = $("#bank_id0");
        var clone2 = sel2.clone();

        clone2.attr("id", "bank_id" + n);
        clone2.show();
        clone2
            .insertAfter("#bank_id_select" + n)
            .wrap("<div></div>")
            .select2();
        var sel = $("#currency0");
        var clone = sel.clone();

        clone.attr("id", "currency" + n);
        clone.show();
        clone
            .insertAfter("#currency_select" + n)
            .wrap("<div></div>")
            .select2();
      
        i++;
    });
  /******************user bank Record*****************/


  /******************Education Record*****************/

    $(".education-addmore").on("click", function () {
        count = $("table#education_records tr").length;
        var num = get_row_num("table#education_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td><td style='border-top: none;'><label>Education Type <span class='required text-danger'>*</span></label><input type='text' id='education_type" +
            n +
            "' name='education_type[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>School Name <span class='required text-danger'>*</span></label><input type='text' id='school_name" +
            n +
            "' name='school_name[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Year <span class='required text-danger'>*</span></label><input type='text' id='date_of_graduation" +
            n +
            "' name='date_of_graduation[]'  onkeypress='return isNumberKey(event)'  required class='form-control graduation'></td>";
        data +=
            "<td style='border-top: none;'><label>Major <span class='required text-danger'>*</span></label><input type='text' id='major" +
            n +
            "' name='major[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Education(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#education_records tbody").append(data);

        //copy education type
     

        i++;
    });

  
  /******************Education Record*****************/

 
    /******************Qualification Record*****************/

    $(".qualification-addmore").on("click", function () {
        count = $("table#qualification_records tr").length;
        var num = get_row_num("table#qualification_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Year <span class='required text-danger'>*</span> </label><input type='text' id='date_of_acquition" +
            n +
            "' name='date_of_acquition[]' onkeypress='return isNumberKey(event)' required class='form-control acquisition'></td>";
        data +=
            "<td style='border-top: none;'><label>Certificate <span class='required text-danger'>*</span></label><input type='text' id='certificate" +
            n +
            "' name='certificate[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Qualification(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#qualification_records tbody").append(data);

        i++;
    });

    /******************Qualification Record*****************/

 /******************Language Record*****************/

    $(".language-addmore").on("click", function () {
        count = $("table#language_records tr").length;
        var num = get_row_num("table#language_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Language Skill  <span class='required text-danger'>*</span> </label><input type='text' id='language_skill" +
            n +
            "' name='language_skill[]' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Level <span class='required text-danger'>*</span></label><input type='text' id='level" +
            n +
            "' name='level[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Language(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#language_records tbody").append(data);

        i++;
    });

    /******************Language Record*****************/
    
 /******************warning Record*****************/

  
    $(".warning-addmore").on("click", function () {
        count = $("table#warning_records tr").length;
        var num = get_row_num("table#warning_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";

        data +=
            "<td style='border-top: none;'><label>Date </label><input type='date' name='date[]' required id='date" +
            n +
            "' class='form-control'/></td>";
        data +=
            "<td style='border-top: none;'><label>Reason </label><input type='text' id='reason" +
            n +
            "' name='reason[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_warning(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#warning_records tbody").append(data);

        i++;
    });

  /******************warning Record*****************/

  /******************english Record*****************/
 
    $(".english-addmore").on("click", function () {
        count = $("table#english_records tr").length;
        var num = get_row_num("table#english_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td><td style='border-top: none;'><label>Type of English Test <span class='required text-danger'>*</span>  </label><input type='text' id='test_type" +
            n +
            "' name='test_type[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Mark </label><input type='number' id='mark" +
            n +
            "' name='mark[]'' onkeypress='return setTwoNumberDecimal(event,this)'  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Level </label><input type='text' id='level" +
            n +
            "' name='level[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Year </label><input type='number' id='date_of_acquition" +
            n +
            "' name='date_of_acquition[]' onkeypress='return isNumberKey(event)'  class='form-control english-test-date'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_English(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#english_records tbody").append(data);

        //copy english test type

        i++;
    });
    
  /******************english Record*****************/
   /******************evaluation Record*****************/
 $(".evaluation-addmore").on("click", function () {
        count = $("table#evaluation_records tr").length;
        var num = get_row_num("table#evaluation_records");
        n = Number(num) + 1;
        var data =
            "<tr id='evaluationNumone" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td > <td style='border-top: none;'><label>Year <span class='required text-danger'>*</span></label><input type='number' id='date" +
            n +
            "' name='year[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Band </label><input type='number' id='grade" +
            n +
            "' name='grade[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Title </label><input type='text' id='title" +
            n +
            "' name='title[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Department <span class='required text-danger'>*</span></label><div style='width:200px;' id='department_type" +
            n +
            "'></div></td>";
        data +=
            "<tr id='evaluationNumtwo" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td > <td style='border-top: none;'><label>Competency</label><input type='text' id='competency" +
            n +
            "' name='competency[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Performance </label><div style='width:200px;' id='performance_type" +
            n +
            "'></div></td>";
        data +=
            "<td style='border-top: none;'><label>Salary (Net Pay) <span class='required text-danger'>*</span> </label><input type='text' id='net_pay" +
            n +
            "' name='net_pay[]'' required onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Basic Salary <span class='required text-danger'>*</span> </label><input type='text' id='basic_salary" +
            n +
            "' name='basic_salary[]' required onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td></tr>";
        data +=
            "<tr id='evaluationNumthree" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td > <td style='border-top: none;'><label>Duty Allowance </label><input type='text' id='allowance" +
            n +
            "' name='allowance[]''  onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>OT Rate </label><input type='text' id='ot_rate" +
            n +
            "' name='ot_rate[]''   onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Bonus (Water Festival)</label><input type='text' id='water_festival_bonus" +
            n +
            "' name='water_festival_bonus[]''  onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Bonus (Thadingyut)</label><input type='text' id='thadingyut_bonus" +
            n +
            "' name='thadingyut_bonus[]'  onkeypress='return setTwoNumberDecimal(event,this)' class='form-control'></td></tr>";
        data +=
            "<tr id='evaluationNumfour" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td ><td style='border-top: none;'><label>Branch</label><div style='width:200px;' id='branch_type" +
            n +
            "'></div></td><td style='border-top: none;'><label>Position</label><div style='width:200px;' id='position_type" +
            n +
            "'></div></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_evaluation(" +
            n +
            ")' title='Remove row'><i class='fa fa-minus' style='font - size: 14px; font - weight: bold; color: white; '></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#evaluation_records tbody").append(data);

        //copy english department type
        var sel = $("#department_id0");
        var clone = sel.clone();

        clone.attr("id", "department_id" + n);
        clone.show();
        clone
            .insertAfter("#department_type" + n)
            .wrap("<div></div>")
            .select2();

        //copy english branch type
        var seel = $("#branch_id0");
        var clones = seel.clone();

        clones.attr("id", "branch_id" + n);
        clones.show();
        clones
            .insertAfter("#branch_type" + n)
            .wrap("<div></div>")
            .select2();

        i++;
        //copy english position type
        var sele = $("#position_id0");
        var clonee = sele.clone();

        clonee.attr("id", "position_id" + n);
        clonee.show();
        clonee
            .insertAfter("#position_type" + n)
            .wrap("<div></div>")
            .select2();

      i++;
      
        //copy english position type
        var seld = $("#performance_id0");
        var cloned = seld.clone();

        cloned.attr("id", "performance_id" + n);
        cloned.show();
        cloned
            .insertAfter("#performance_type" + n)
            .wrap("<div></div>")
            .select2();

      i++;
      
    });
      /******************evaluation Record*****************/
  /******************PC Record*****************/
 $(".pc-addmore").on("click", function () {
        count = $("table#pc_records tr").length;
        var num = get_row_num("table#pc_records");
        n = Number(num) + 1;
        var data =
            "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>SKill Title  <span class='required text-danger'>*</span></label><input type='text' id='skill_title" +
            n +
            "' name='skill_title[]'' required class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Level </label><input type='text' id='skill_level" +
            n +
            "' name='skill_level[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_PC(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
        console.log("New DAta : " + data);
        $("table#pc_records tbody").append(data);

        //copy english test type
        var sel = $("#test_type0");
        var clone = sel.clone();

        clone.attr("id", "test_type" + n);
        clone.show();
        clone
            .insertAfter("#english_type" + n)
            .wrap("<div></div>")
            .select2();

        i++;
    });
    /******************PC Record*****************/
    
    
	 /******************Employement Record*****************/

  
    $(".employement-addmore").on("click", function () {
        count = $("table#emplyement_records tr").length;
        var num = get_row_num("table#emplyement_records");
        n = Number(num) + 1;
        var data =
            "<tr id='employementNumone" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Company Name  <span class='required text-danger'>*</span></label><input type='text' id='employment_company_name" +
            n +
            "' name='company_name[]'' required class='form-control'></td>";

        data +=
            "<td style='border-top: none;'><label>From (Year) </label><input type='number' name='start_date[]' onkeypress='return isNumberKey(event)'  id='employment_start_date" +
            n +
            "' class='form-control ' /></td>";

        data +=
            "<td style='border-top: none;padding-top:18px;'> <label >To (Year) </label><input type='number' name='end_date[]'  onkeypress='return isNumberKey(event)'  id='employment_end_date" +
            n +
            "' class='form-control'  /></td>";

        data +=
            "<tr id='employementNumtwo" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Position</label><input type='text' id='employment_position" +
            n +
            "' name='position[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label>Department </label><input type='text' id='employment_department" +
            n +
            "' name='department[]''  class='form-control'></td>";
        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Employement(" +
            n +
            ")' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";

        console.log("New DAta : " + data);
        $("table#emplyement_records tbody").append(data);

        //copy english test type
        // var sel = $("#test_type0");
        // var clone = sel.clone();

        // clone.attr("id", "test_type" + n);
        // clone.show();
        // clone
        //   .insertAfter("#english_type" + n)
        //   .wrap("<div></div>")
        //   .select2();

        i++;
    });

	
		 /******************oversea Record*****************/
$(".oversea-addmore").on("click", function () {
        count = $("table#oversea_records tr").length;
        var num = get_row_num("table#oversea_records");
        n = Number(num) + 1;
        var data =
            "<tr id='overseaNumone" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Country Name  <span class='required text-danger'>*</span> </label><input type='text' id='oversea_record" +
            n +
            "' name='country_name[]'' required class='form-control'></td>";

        data +=
            "<td style='border-top: none;'><label>From (Year) </label><input type='number' name='start_date[]' id='oversea_record" +
            n +
            "' onkeypress='return isNumberKey(event)'  class='form-control' /></td>";

        data +=
            "<td style='border-top: none;padding-top:18px;'> <label >To (Year)</label><input type='number' name='end_date[]' id='oversea_record" +
            n +
            "' onkeypress='return isNumberKey(event)'  class='form-control'/></td>";

        data +=
            "<tr id='overseaNumtwo" +
            n +
            "'><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
            n +
            "'>" +
            count +
            "</small></td>";
        data +=
            "<td style='border-top: none;'><label>Purpose</label><textarea  id = 'oversea_record" +
            n +
            "' name = 'purpose[]'   class='form-control' ></textarea ></td > ";

        data +=
            "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_oversea(" +
            n +
            ")' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";

        console.log("New DAta : " + data);
        $("table#oversea_records tbody").append(data);

        //copy english test type
        var sel = $("#test_type0");
        var clone = sel.clone();

        clone.attr("id", "test_type" + n);
        clone.show();
        clone
            .insertAfter("#english_type" + n)
            .wrap("<div></div>")
            .select2();

        i++;
    });

	
  /******************Salary Record*****************/

  $(".salary-addmore").on("click", function () {
    count = $("table#salary_records tr").length;
    var num = get_row_num("table#salary_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='salary_exchange_rate" +
      n +
      "' name='salary_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='salary_exchange_USD" +
      n +
      "' name='salary_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='salary_exchange_MMK" +
      n +
      "' name='salary_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Salary(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#salary_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });

  /******************Salary Record*****************/

  $(".salary-japan-addmore").on("click", function () {
    count = $("table#salary_japan_records tr").length;
    var num = get_row_num("table#salary_japan_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='salary_japan_exchange_rate" +
      n +
      "' name='salary_japan_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='salary_japan_exchange_USD" +
      n +
      "' name='salary_japan_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='salary_japan_exchange_MMK" +
      n +
      "' name='salary_japan_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_Salary_Japan(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#salary_japan_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });


   /******************without pay Record*****************/

  $(".without-pay-addmore").on("click", function () {
    count = $("table#without_pay_records tr").length;
    var num = get_row_num("table#without_pay_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='without_pay_exchange_rate" +
      n +
      "' name='without_pay_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='without_pay_exchange_USD" +
      n +
      "' name='without_pay_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='without_pay_exchange_MMK" +
      n +
      "' name='without_pay_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_without_pay(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#without_pay_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });

     /******************total ot pay Record*****************/

  $(".total-ot-pay-addmore").on("click", function () {
    count = $("table#total_ot_pay_records tr").length;
    var num = get_row_num("table#total_ot_pay_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='total_ot_pay_exchange_rate" +
      n +
      "' name='total_ot_pay_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='total_ot_pay_exchange_USD" +
      n +
      "' name='total_ot_pay_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='total_ot_pay_exchange_MMK" +
      n +
      "' name='total_ot_pay_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_total_ot_pay(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#total_ot_pay_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });

      /******************tax per month Record*****************/

  $(".tax-per-month-addmore").on("click", function () {
    count = $("table#total_ot_pay_records tr").length;
    var num = get_row_num("table#tax_per_month_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='tax_per_month_exchange_rate" +
      n +
      "' name='tax_per_month_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='tax_per_month_exchange_USD" +
      n +
      "' name='tax_per_month_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='tax_per_month_exchange_MMK" +
      n +
      "' name='tax_per_month_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_tax_per_month(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#tax_per_month_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });

   /******************total Deduction Record*****************/

  $(".total-deduction-addmore").on("click", function () {
    count = $("table#total_ot_pay_records tr").length;
    var num = get_row_num("table#total_deduction_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='total_deduction_exchange_rate" +
      n +
      "' name='total_deduction_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='total_deduction_exchange_USD" +
      n +
      "' name='total_deduction_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='total_deduction_exchange_MMK" +
      n +
      "' name='total_deduction_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_total_deduction(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#total_deduction_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });


   /******************toal allowance  Record*****************/

  $(".total-allowance-addmore").on("click", function () {
    count = $("table#total_ot_pay_records tr").length;
    var num = get_row_num("table#total_Allowance_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Rate </label><input type='text' id='total_allowance_exchange_rate" +
      n +
      "' name='total_allowance_exchange_rate[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>USD </label><input type='text' id='total_allowance_exchange_USD" +
      n +
      "' name='total_allowance_exchange_USD[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>MMK </label><input type='text' id='total_allowance_exchange_MMK" +
      n +
      "' name='total_allowance_exchange_MMK[]' class='form-control english-test-date'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_total_allowance(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#total_allowance_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });


   /******************other deduction Record*****************/

  $(".other-deduction-addmore").on("click", function () {
    count = $("table#other_deduction_records tr").length;
    var num = get_row_num("table#other_deduction_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Name </label><input type='text' id='other_deduction_name" +
      n +
      "' name='other_deduction_name[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>Amount </label><input type='text' id='other_deduction_amount" +
      n +
      "' name='other_deduction_amount[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_other_deduction(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#other_deduction_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });

   /******************other allowance Record*****************/

  $(".other-allowance-addmore").on("click", function () {
    count = $("table#other_allowance_records tr").length;
    var num = get_row_num("table#other_allowance_records");
    n = Number(num) + 1;
    var data =
      "<tr><td style='padding-left: 9px;vertical-align: middle; display: none;border-top: none;'><small id='snum" +
      n +
      "'>" +
      count +
      "</small></td>";
    data +=
      "<td style='border-top: none;'><label>Name </label><input type='text' id='other_allowance_name" +
      n +
      "' name='other_allowance_name[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label>Amount </label><input type='text' id='other_allowance_amount" +
      n +
      "' name='other_allowance_amount[]'' class='form-control'></td>";
    data +=
      "<td style='border-top: none;'><label style='visibility: hidden;'>Action </label><br><div class='delete-row btn btn-danger remove' onclick='delete_Row_other_deduction(this)' title='Remove row'><i class='fa fa-minus' style='font-size: 14px;font-weight: bold;color: white;'></i></div></td></tr>";
    console.log("New DAta : " + data);
    $("table#other_allowance_records tbody").append(data);

    //copy english test type
    var sel = $("#type_of_test0");
    var clone = sel.clone();

    clone.attr("id", "type_of_test" + n);
    clone.show();
    clone
      .insertAfter("#english_type" + n)
      .wrap("<div></div>")
      .select2();

    i++;
  });
	
	
	
});
function delete_Row_evaluation(n) {
	jQuery('#evaluationNumone' + n).remove();
	jQuery('#evaluationNumtwo' + n).remove();
	jQuery('#evaluationNumthree'+n).remove();
}

function delete_Row_Employement(n) {
	jQuery('#employementNumone' + n).remove();
	jQuery('#employementNumtwo' + n).remove();
}
function delete_Row_oversea(n) {
	jQuery('#overseaNumone' + n).remove();
	jQuery('#overseaNumtwo' + n).remove();
}

function delete_Row_Family(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkFamily();
  //delete_doneby(small);
}
function delete_Row_warning(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkWarning();
  //delete_doneby(small);
}

function delete_Row_Education(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkEducation();
  //delete_doneby(small);
}

function delete_Row_Qualification(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkQualification();
  //delete_doneby(small);
}

function delete_Row_Language(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkLanguage();
  //delete_doneby(small);
}

function delete_Row_English(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkEnglish();
  //delete_doneby(small);
}
// function delete_Row_evaluation(row) {
//   var small = $(row).closest("tr").find("td small");
//   $(row).parents("tr").remove();
//   checkEvaluation();
//   //delete_doneby(small);
// }
function delete_Row_PC(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkPC();
  //delete_doneby(small);
}
function delete_Row_Salary(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkSalary();
  //delete_doneby(small);
}
function delete_Row_Salary_Japan(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkSalaryJapan();
  //delete_doneby(small);
}
function delete_Row_without_pay(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkWithoutPay();
  //delete_doneby(small);
}

function delete_Row_total_ot_pay(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checktotalotPay();
  //delete_doneby(small);
}
function delete_Row_tax_per_month(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkTaxPerMonth();
  //delete_doneby(small);
}
function delete_Row_total_deduction(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkTotaldeduction();
  //delete_doneby(small);
}
function delete_Row_other_deduction(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkotherdeduction();
  //delete_doneby(small);
}

function delete_Row_total_allowance(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkTotalallowance();
  //delete_doneby(small);
}
function delete_Row_other_allowance(row) {
  var small = $(row).closest("tr").find("td small");
  $(row).parents("tr").remove();
  checkOtherallowance();
  //delete_doneby(small);
}



//number of item table
function get_row_num(tbl_id) {
  var last_row = $(tbl_id).find("tr").last();
  console.log("Last Row : " + last_row[0]);
  last_row = last_row[0];
  var small = $(last_row).find("td small");
  var sp = small[0];
  console.log("small : " + sp);
  if (typeof sp == "undefined" || sp == false) {
    return 0;
  }
  var strid = sp.id;
  var row = strid.replace("snum", "");
  return row;
}

function checkFamily() {
  obj = $("table#family_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkWarning() {
  obj = $("table#family_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function checkEducation() {
  obj = $("table#education_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function checkQualification() {
  obj = $("table#qualification_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function checkLanguage() {
  obj = $("table#language_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function checkEnglish() {
  obj = $("table#english_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkSalary() {
  obj = $("table#salary_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkSalaryJapan() {
  obj = $("table#salary_Japan_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkWithoutPay() {
  obj = $("table#without_pay_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}

function checktotalotPay() {
  obj = $("table#total_ot_pay_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkTaxPerMonth() {
  obj = $("table#tax_per_month_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkTotaldeduction() {
  obj = $("table#total_deduction_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkotherdeduction() {
  obj = $("table#other_deduction_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkTotalallowance() {
  obj = $("table#total_allowance_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
function checkOtherallowance() {
  obj = $("table#total_allowance_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
}
// function checkEvaluation() {
//   obj = $("table#evaluation_records tbody tr").find("small");
//   $.each(obj, function (key, value) {
//     id = value.id;
//     console.log("small id " + id);
//     $("#" + id).html(key + 1);
//   });
// }
function checkPC() {
  obj = $("table#pc_records tbody tr").find("small");
  $.each(obj, function (key, value) {
    id = value.id;
    console.log("small id " + id);
    $("#" + id).html(key + 1);
  });
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

function showLoading() {
  document.querySelector("#loading").classList.add("loading");
  document.querySelector("#loading-content").classList.add("loading-content");
}

function hideLoading() {
  document.querySelector("#loading").classList.remove("loading");
  document
    .querySelector("#loading-content")
    .classList.remove("loading-content");
}
